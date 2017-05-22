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
    /**
     * @var array of headers sent with HTTP requests
     */
    private $_requestHeaders = array();

    public function __construct($apiKey,$siteID)
    {
        $this->_setRequestHeader("Api-key", $apiKey);
        $this->_setRequestHeader("Api-Appid", $siteID);
    }

    /**
     * @brief sets an element in an array of headers to be sent with HTTP requests
     *
     * @param string $header
     * @param string $value
     */
    private function _setRequestHeader($header, $value)
    {
        $this->_requestHeaders[$header] = $header . ": " . $value;
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
     * @throws \Exception
     *
     * @return boolean
     */
    private function _validateRequest($requestParams, $requiredParams, $method)
    {
        $allowedMethods = $this->_getWhitelistedRequestTypes();

        if (!in_array($method, $allowedMethods))
        {
            throw new \Exception($method . " is not a supported HTTP method.");
        }

        if ($requestParams && (!is_array($requestParams)))
        {
            $dataType = gettype($requestParams);
            throw new \Exception("Invalid input: expected array, received $dataType");
        }

        if ($missingParams = $this->_checkRequiredParams($requestParams, $requiredParams))
        {
            $missingParams = implode(",",$missingParams);
            throw new \Exception("Invalid input: missing required parameter(s): $missingParams");
        }

        return true;
    }

    /**
     * @brief checks that all required parameters were included with HTTP request
     *
     * @param array $requestParams
     * @param array $requiredParams
     * @param string $url
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
                    if ($requiredParam == "ids")
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
     */
    public function httpRequest($requestParams, $url, $method, $requiredParams, $options)
    {
        if (!$this->_validateRequest($requestParams, $requiredParams, $method))
        {
            return false;
        }

        if ($options && is_array($options))
        {
            if (array_key_exists("headers", $options))
            {
                foreach ($options["headers"] as $header => $value)
                {
                    $this->_setRequestHeader($header, $value);
                }
            }
        }

        if (array_key_exists("Content-Type", $this->_requestHeaders) && $this->_requestHeaders["Content-Type"] == "Content-Type: application/json")
        {
            $requestParams = json_encode($requestParams);
        }

        if (is_array($requestParams))
        {
            $requestParams = http_build_query($requestParams);
        }

        $curlHandle = curl_init();

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
                $url = $url."?".$requestParams;
                break;
        }

        curl_setopt($curlHandle, CURLOPT_URL, $url);
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $this->_requestHeaders);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, 60);

        $result = curl_exec($curlHandle);
        curl_close($curlHandle);

        unset($this->_requestHeaders["Content-Type"]);

        return $result;
    }
}