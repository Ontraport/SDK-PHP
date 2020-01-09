<?php

namespace OntraportAPI;

/**
 * Class CurlClient
 *
 * @author ONTRAPORT
 *
 * @package OntraportAPI
 */
class CurlClient
{
    const HTTP_OK = 200;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_UNAUTHORIZED = 401;
    const HTTP_FORBIDDEN = 403;
    const HTTP_NOT_FOUND = 404;
    const HTTP_RATE_LIMIT = 429;
    const HTTP_ERROR = 500;

    const RATE_LIMIT_RESET = "x-rate-limit-reset";
    const RATE_LIMIT = "x-rate-limit";
    const RATE_LIMIT_REMAINING = "x-rate-limit-remaining";
    const DEFAULT_RATE_LIMIT = 180;

    /**
     * @var array of headers sent with HTTP requests
     */
    private $_requestHeaders = array();

    /**
     * @var array of headers received with HTTP responses
     */
    private $_responseHeaders = array();

    /**
     * @var bool keep the request rate below the API rate limit
     */
    private $_keepBelowRateLimit;
    
     * @var array of extra CURL options
     */
    private $_curlOptions = array();

    /**
     * @var int the last HTTP status code received
     */
    private $_lastStatusCode;

    public function __construct($apiKey = "", $siteID = "", $keepBelowRateLimit = true)
    {
        $this->setCredentials($apiKey, $siteID);
        $this->_keepBelowRateLimit = $keepBelowRateLimit;
    }

    /**
     * @brief sets API key credential
     *
     * @param string $apiKey
     * @param string $siteID
     */
    public function setCredentials($apiKey, $siteID)
    {
        $this->setRequestHeader("Api-key", $apiKey);
        $this->setRequestHeader("Api-Appid", $siteID);
    }

    /**
     * @brief sets an element in an array of headers to be sent with HTTP requests
     *
     * @param string $header
     * @param string $value
     */
    public function setRequestHeader($header, $value)
    {
        $this->_requestHeaders[$header] = $header . ": " . $value;
    }

    /**
     * @brief retrieves current request headers
     *
     * @return array
     */
    public function getRequestHeaders()
    {
        return $this->_requestHeaders;
    }

    /**
     * @brief sets an element in an array of headers to be sent with HTTP requests
     *
     * @param string $option
     * @param string $value
     */
    public function setExtraOpt($option, $value)
    {
        $this->_curlOptions[$option] = $value;
    }

    /**
     * @brief gets an array of supported cURL methods
     *
     * @return array
     */
    private function _getWhitelistedRequestTypes()
    {
        return array(
            "get",
            "post",
            "put",
            "delete"
        );
    }

    /**
     * @brief checks that HTTP request is formed properly before sending
     *
     * @param array $requestParams
     * @param array $requiredParams
     * @param int $method
     *
     * @return boolean
     *
     * @throws Exceptions\HttpMethodException
     * @throws Exceptions\RequiredParamsException
     * @throws Exceptions\TypeException
     */
    private function _validateRequest($requestParams, $requiredParams, $method)
    {
        $allowedMethods = $this->_getWhitelistedRequestTypes();

        if (!in_array($method, $allowedMethods, true))
        {
            throw new Exceptions\HttpMethodException($method);
        }

        if ($requestParams && (!is_array($requestParams)))
        {
            $dataType = gettype($requestParams);
            throw new Exceptions\TypeException($dataType);
        }

        if ($missingParams = $this->_checkRequiredParams($requestParams, $requiredParams))
        {
            $missingParams = implode(",",$missingParams);
            throw new Exceptions\RequiredParamsException($missingParams);
        }

        return true;
    }

    /**
     * @brief checks that all required parameters were included with HTTP request
     *
     * @param array $requestParams
     * @param array $requiredParams
     *
     * @return array $missingParams
     */
    private function _checkRequiredParams($requestParams, $requiredParams)
    {
        $missingParams = array();

        if ($requiredParams && is_array($requiredParams))
        {
            foreach ($requiredParams as $requiredParam)
            {
                if (!array_key_exists($requiredParam, $requestParams))
                {
                    // Covers special case: when ids is required, group_ids can be substituted
                    if ($requiredParam === "ids")
                    {
                        if (!array_key_exists("group_ids", $requestParams))
                        {
                            $missingParams[] = $requiredParam;
                        }
                    }
                    else
                    {
                        $missingParams[] = $requiredParam;
                    }
                }
            }
        }

        return $missingParams;
    }

    /**
     * @brief makes an HTTP request
     *
     * @param array $requestParams
     * @param string $url
     * @param int $method
     * @param array $requiredParams
     * @param array $options
     *
     * @return string|boolean
     *
     * @throws Exceptions\TypeException
     * @throws Exceptions\RequiredParamsException
     * @throws Exceptions\HttpMethodException
     */
    public function httpRequest($requestParams, $url, $method, $requiredParams, $options)
    {
        if (!$this->_validateRequest($requestParams, $requiredParams, $method))
        {
            return false;
        }

        if ($this->_keepBelowRateLimit)
        {
            $this->_checkRateLimit();
        }

        if ($options &&
            is_array($options) &&
            array_key_exists("headers", $options) &&
            is_array($options["headers"]))
        {
            foreach ($options["headers"] as $header => $value)
            {
                $this->setRequestHeader($header, $value);
            }
        }

        if (array_key_exists("Content-Type", $this->_requestHeaders) &&
            $this->_requestHeaders["Content-Type"] === "Content-Type: application/json")
        {
            $requestParams = json_encode($requestParams);
        }

        if (is_array($requestParams))
        {
            $requestParams = http_build_query($requestParams);
        }

        $curlHandle = curl_init();
        $headers = array();

        curl_setopt_array($curlHandle, $this->_curlOptions);

        switch(strtolower($method))
        {
            case "post":
                curl_setopt($curlHandle, CURLOPT_POST, 1);
                curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $requestParams);
                break;

            case "get":
                curl_setopt($curlHandle, CURLOPT_HTTPGET, 1);
                $url = $url."?".$requestParams;
                break;

            case "put":
                curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $requestParams);
                break;

            case "delete":
                curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, "DELETE");
                if (array_key_exists("Content-Type", $this->_requestHeaders) &&
                    $this->_requestHeaders["Content-Type"] === "Content-Type: application/json")
                {
                    curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $requestParams);
                }
                else
                {
                    $url = $url."?".$requestParams;
                }
                break;
        }

        curl_setopt($curlHandle, CURLOPT_URL, $url);
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $this->_requestHeaders);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandle, CURLOPT_HEADER, false);
        curl_setopt($curlHandle, CURLOPT_HEADERFUNCTION,
            function($curl, $header) use (&$headers)
            {
                $len = strlen($header);
                $header = explode(":", $header, 2);
                if (count($header) < 2)
                {
                    return $len;
                }

                $name = strtolower(trim($header[0]));
                $headers[$name] = trim($header[1]);

                return $len;
            }
        );
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, 60);

        $result = curl_exec($curlHandle);

        $this->_responseHeaders = $headers;

        $this->_lastStatusCode = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
        if ($this->_lastStatusCode == self::HTTP_RATE_LIMIT)
        {
            $result = $this->retry($headers[self::RATE_LIMIT_RESET], $curlHandle);
        }

        curl_close($curlHandle);

        unset($this->_requestHeaders["Content-Type"]);
        return $result;
    }

    /**
     * @brief get the last HTTP status code received
     *
     * @return int
     */
    public function getLastStatusCode()
    {
         return $this->_lastStatusCode;
    }

    /**
     * @brief set the last HTTP status code received
     *
     * @param int $statusCode The code that was received
     */
    public function setLastStatusCode($statusCode)
    {
        $this->_lastStatusCode = $statusCode;
    }

    /**
     * @brief Retry the request after waiting for the rate limit to roll over
     *
     * @param int $wait
     * @param $curlHandle
     *
     * @return mixed
     */
    public function retry($wait, $curlHandle)
    {
        sleep($wait);
        $result = curl_exec($curlHandle);
        $this->_lastStatusCode = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
        return $result;
    }

    private function _checkRateLimit()
    {
        // If no API calls have been made, no need to delay.
        if (empty($this->_responseHeaders))
        {
            return;
        }

        // Default the $limit and $remaining values if not set in the last response header.
        /** @var int $limit */
        $limit = isset($this->_responseHeaders[self::RATE_LIMIT])
                    ? (int)$this->_responseHeaders[self::RATE_LIMIT]
                    : self::DEFAULT_RATE_LIMIT;

        /** @var int $remaining */
        $remaining = isset($this->_responseHeaders[self::RATE_LIMIT_REMAINING])
                        ? (int)$this->_responseHeaders[self::RATE_LIMIT_REMAINING]
                        : self::DEFAULT_RATE_LIMIT;

        // If no API calls have been made, no need to delay.
        if ($limit == $remaining)
        {
            return;
        }

        // If we are below 5% remaining, sleep for 0.50 seconds.
        if ($remaining / $limit < 0.05)
        {
            usleep(500000);
            return;
        }

        // If we are below 10% remaining, sleep for 0.25 seconds.
        if ($remaining / $limit < 0.1)
        {
            usleep(250000);
        }
    }
}
