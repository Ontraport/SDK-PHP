<?php

namespace OntraportAPI;

/**
 * Class BaseApi
 *
 * @author ONTRAPORT
 *
 * @package OntraportAPI
 */
abstract class BaseApi
{
    /**
     * @var Ontraport instance
     */
    protected $client;

    /**
     * @var string endpoint for all objects retrieve meta functions
     */
    const META = "meta";

    /**
     * @var string endpoint for all objects saveorupdate functions
     */
    const SAVE_OR_UPDATE = "saveorupdate";

    /**
     * @var string endpoint for all objects getInfo functions
     */
    const GET_INFO = "getInfo";

    /**
     * @var string constant for Content-Type header value type JSON-encoded
     */
    const CONTENT_TYPE_JSON = "json";

    /**
     * @var string constant for Content-Type header value type form-encoded
     */
    const CONTENT_TYPE_FORM = "form";

    /**
     * @var string endpoint for object
     */
    protected $_endpoint = NULL;

    /**
     * @var string plural endpoint for object
     */
    protected $_endpointPlural = NULL;

    /**
     * @param Ontraport $client
     */
    public function __construct(Ontraport $client)
    {
        $this->client = $client;
    }

    /**
     * @brief Retrieve a single specified object
     *
     * @param mixed[] $requestParams The parameters to submit with GET request.
     *                               Varies by object.
     *
     * @return string JSON formatted HTTP response or error message
     */
    protected function _retrieveSingle($requestParams)
    {
        $requiredParams = array("id");
        return $this->client->request($requestParams, $this->_endpoint, "get", $requiredParams, $options = NULL);
    }

    /**
     * @brief Retrieve multiple objects according to specific criteria, handle pagination
     *
     * @param $requestParams mixed[] Array of parameters to submit with GET request. If "ids"
     *                               are not specified, all will be selected.
     *                               Possible array keys: "objectID" (required),"ids","start","range","sort","sortDir",
     *                                                    "condition","search","searchNotes","group_ids","performAll",
     *                                                    "externs","listFields"
     *
     * @return string JSON formatted array of paginated response data: each page of data will be an element in that array
     */
    protected function _retrieveMultiplePaginated($requestParams)
    {
        $collection = json_decode($this->_retrieveCollectionInfo($requestParams), true);
        $requestParams["start"] = $requestParams["start"] ?: 0;
        $requestParams["range"] = $requestParams["range"] ?: 50;

        $object_data = array();
        while ($requestParams["start"] < $collection["data"]["count"])
        {
            $object_data[] = json_decode($this->_retrieveMultiple($requestParams), true);
            $requestParams["start"] += $requestParams["range"];
        }
        return json_encode($object_data);
    }

    /**
     * @brief Retrieve multiple objects according to specific criteria
     *
     * @param mixed[] $requestParams Array of parameters to submit with GET request.
     *                               Varies by object.
     *
     * @return string JSON formatted HTTP response or error message.
     */
    protected function _retrieveMultiple($requestParams)
    {
        return $this->client->request($requestParams, $this->_endpointPlural, "get", $requiredParams = NULL, $options = NULL);
    }

    /**
     * @brief Create an object
     *
     * @param mixed[] $requestParams Array of parameters to submit with POST request.
     *                               Varies by object.
     *
     * @return string JSON formatted HTTP response or error message
     */
    protected function _create($requestParams)
    {
        $options["headers"] = self::retrieveContentTypeHeader(self::CONTENT_TYPE_FORM);
        return $this->client->request($requestParams, $this->_endpointPlural, "post", $requiredParams = NULL, $options);
    }

    /**
     * @brief Delete a single specified object
     *
     * @param mixed[] $requestParams Array of the parameters to submit with DELETE request.
     *                               Varies by object.
     *
     * @return string JSON formatted HTTP response or error message
     */
    protected function _deleteSingle($requestParams)
    {
        $requiredParams = array("id");
        return $this->client->request($requestParams, $this->_endpoint, "delete", $requiredParams, $options = NULL);
    }

    /**
     * @brief Delete multiple objects according to specific criteria
     *
     * @param mixed[] $requestParams The parameters to submit with DELETE request.
     *                               Varies by object.
     *
     * @return string JSON formatted HTTP response or error message
     */
    protected function _deleteMultiple($requestParams)
    {
        return $this->client->request($requestParams, $this->_endpointPlural, "delete", $requiredParams = NULL, $options = NULL);
    }

    /**
     * @brief Update an object's data
     *
     * @param mixed[] $requestParams The parameters to submit with PUT request.
     *                               Varies by object.
     *
     * @return string JSON formatted HTTP response or error message
     */
    protected function _update($requestParams)
    {
        $requiredParams = array("id");
        $options["headers"] = self::retrieveContentTypeHeader(self::CONTENT_TYPE_FORM);
        return $this->client->request($requestParams, $this->_endpointPlural, "put", $requiredParams, $options);
    }

    /**
     * @brief Retrieve meta for an object
     *
     * @param mixed[] $requestParams The parameters to submit with GET request. Ignored if not sent.
     *
     * @return string JSON formatted meta for an object
     */
    protected function _retrieveMeta()
    {
        return $this->client->request($requestParams = NULL, $this->_endpointPlural . "/" . self::META, "get", $requiredParams = NULL, $options = NULL);
    }

    /**
     * @brief Either create an object or merge with an existing object on unique field
     *
     * @param mixed[] $requestParams The parameters to submit with PUT request.
     *                               Varies by object.
     *
     * @return string JSON formatted HTTP response or error message
     */
    protected function _saveOrUpdate($requestParams)
    {
        $options["headers"] = self::retrieveContentTypeHeader(self::CONTENT_TYPE_FORM);
        return $this->client->request($requestParams, $this->_endpointPlural . "/" . self::SAVE_OR_UPDATE, "post", $requiredParams = NULL, $options);
    }

    /**
     * @brief Retrieve information (such as number of objects) about a collection
     *
     * @param mixed[] $requestParams Array of parameters to submit with GET request.
     *                               Varies by object.
     *
     * @return string JSON formatted HTTP response or error message
     */
    protected function _retrieveCollectionInfo($requestParams)
    {
        return $this->client->request($requestParams, $this->_endpointPlural . "/" . self::GET_INFO, "get", $requiredParams = NULL, $options = NULL);
    }

    protected static function retrieveContentTypeHeader($encoding)
    {
        if ($encoding == self::CONTENT_TYPE_FORM)
        {
            return array("Content-Type" => "application/x-www-form-urlencoded");
        }

        else if ($encoding == self::CONTENT_TYPE_JSON)
        {
            return array("Content-Type" => "application/json");
        }
    }
}
