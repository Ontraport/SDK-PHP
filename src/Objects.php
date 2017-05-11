<?php

namespace OntraportAPI;

/**
 * Class Objects
 *
 * @author ONTRAPORT
 *
 * @package OntraportAPI
 */
class Objects extends BaseApi
{
    /**
     * $var string endpoint for single object
     */
    protected $_endpoint = "object";

    /**
     * $var string endpoint for plural object
     */
    protected $_endpointPlural = "objects";

    /**
     * @param Ontraport $client
     */
    public function __construct(Ontraport $client)
    {
        parent::__construct($client);
    }

    // Object-specific function endpoints
    const TAG = "tag";
    const SEQUENCE = "sequence";
    const PAUSE = "pause";
    const UNPAUSE = "unpause";

    /**
     * @brief Retrieve a single specified object
     *
     * @param $requestParams mixed[] The parameters to submit with GET request.
     *                               Possible array keys: "id" (required), "objectID" (required)
     *
     * @return string JSON formatted response
     */
    public function retrieveSingle($requestParams)
    {
        $requiredParams = array(
            "id",
            "objectID"
        );
        return $this->client->request($requestParams, $this->_endpoint, "get", $requiredParams, $options = NULL);
    }

    /**
     * @brief Retrieve multiple objects according to specific criteria
     *
     * @param $requestParams mixed[] Array of parameters to submit with GET request. If "ids"
     *                               are not specified, all will be selected.
     *                               Possible array keys: "objectID" (required),"ids","start","range","sort","sortDir",
     *                                                    "condition","search","searchNotes","group_ids","performAll",
     *                                                    "externs","listFields"
     *
     * @return string JSON formatted response
     */
    public function retrieveMultiple($requestParams)
    {
        $requiredParams = array(
            "objectID"
        );
        return $this->client->request($requestParams, $this->_endpointPlural, "get", $requiredParams , $options = NULL);
    }

    /**
     * @brief Create an object
     *
     * @param $requestParams mixed[] Array of parameters to submit with POST request.
     *                               Possible array keys: "objectID" (required), other parameters will vary by object type.
     *                               If you do not know what parameters to use, you may wish to consult our API Reference
     *                               or make a call to getMeta for the object in question. Invalid parameters passed in
     *                               will be ignored.
     *
     * @return string JSON formatted response
     */
    public function create($requestParams)
    {
        $requiredParams = array(
            "objectID"
        );
        $options["headers"] = self::retrieveContentTypeHeader(self::CONTENT_TYPE_JSON);
        return $this->client->request($requestParams, $this->_endpointPlural, "post", $requiredParams, $options);
    }

    /**
     * @brief Delete a single specified object
     *
     * @param $requestParams mixed[] Array of the parameters to submit with DELETE request.
     *                               Possible array keys: "id" (required),"objectID" (required)
     *
     * @return string JSON formatted response
     */
    public function deleteSingle($requestParams)
    {
        $requiredParams = array(
            "id",
            "objectID"
        );
        return $this->client->request($requestParams, $this->_endpoint, "delete", $requiredParams, $options = NULL);
    }

    /**
     * @brief Delete multiple objects according to specific criteria
     *
     * @param $requestParams mixed[] Array of parameters to submit with DELETE request. If "ids"
     *                               are not specified, all will be selected.
     *                               Possible array keys: "objectID" (required),"ids","start","range","sort","sortDir",
     *                                                    "condition","search","searchNotes","group_ids","performAll",
     *                                                    "externs","listFields"
     *
     * @return string JSON formatted response
     */
    public function deleteMultiple($requestParams)
    {
        $requiredParams = array(
            "objectID"
        );
        return $this->client->request($requestParams, $this->_endpointPlural, "delete", $requiredParams, $options = NULL);
    }

    /**
     * @brief Update an object's data
     *
     * @param $requestParams mixed[] Array of parameters to submit with PUT request.
     *                               Possible array keys: "objectID" (required), other parameters will vary by object type.
     *                               If you do not know what parameters to use, you may wish to consult our API Reference
     *                               or make a call to getMeta for the object in question. Invalid parameters passed in
     *                               will be ignored.
     *
     * @return string JSON formatted response
     */
    public function update($requestParams)
    {
        $requiredParams = array(
            "objectID"
        );
        $options["headers"] = self::retrieveContentTypeHeader(self::CONTENT_TYPE_JSON);
        return $this->client->request($requestParams, $this->_endpointPlural, "put", $requiredParams, $options);
    }

    /**
     * @brief Retrieve meta for objects
     *
     * @param $requestParams mixed[] The parameters to submit with GET request.
     *                               ObjectID is optional but query will return all objects if not included.
     *                               Possible array keys: "format","objectID"
     *
     * @return string JSON formatted response
     */
    public function retrieveMeta($requestParams = NULL)
    {
        return $this->client->request($requestParams, $this->_endpointPlural . "/" . self::META, "get", $requiredParams = NULL, $options = NULL);
    }

    /**
     * @brief Either create an object or merge with an existing object on unique field
     *
     * @param $requestParams mixed[] The parameters to submit with PUT request.
     *                               Possible array keys:  "objectID" (required), other parameters will vary by object type.
     *                               If you do not know what parameters to use, you may wish to consult our API Reference
     *                               or make a call to getMeta for the object in question. Invalid parameters passed in
     *                               will be ignored.
     *
     * @return string JSON formatted response
     */
    public function saveOrUpdate($requestParams)
    {
        $requiredParams = array(
            "objectID"
        );
        $options["headers"] = self::retrieveContentTypeHeader(self::CONTENT_TYPE_FORM);
        return $this->client->request($requestParams, $this->_endpointPlural . "/" . self::SAVE_OR_UPDATE, "post", $requiredParams, $options);
    }

    /**
     * @brief Retrieve information (such as number of objects) about object collection
     *
     * @param $requestParams mixed[] Array of parameters to submit with GET request.
     *                               Possible array keys:  "objectID" (required),"condition","search","searchNotes",
     *                              "group_ids","performAll"
     *
     * @return string JSON formatted response
     */
    public function retrieveCollectionInfo($requestParams)
    {
        $requiredParams = array(
            "objectID"
        );
        return $this->client->request($requestParams, $this->_endpointPlural . "/" . self::GET_INFO, "get", $requiredParams, $options = NULL);
    }

    /**
     * @brief get an array of all custom objects in the account's database.
     *
     * @return array
     */
    public function retrieveCustomObjects()
    {
        $objects = $this->retrieveMeta($requestParams = NULL);
        $objects = json_decode($objects, true);
        $objects = $objects["data"];

        $customObjects = array();
        foreach ($objects as $id => $data)
        {
            if ($id >= 10000)
            {
                $customObjects[$id] = $data;
            }
        }

        return $customObjects;
    }

    /**
     * @brief Pause rules, sequences, and sequence subscribers for one or more objects.
     *
     * @param mixed[] $requestParams Array of parameters to submit with POST request.
     *                               Possible array keys:  "objectID" (required),"ids","start","range","sort","sortDir",
     *                                                     "condition","search","group_ids","performAll","externs","listFields".
     *                                                      Either "ids" or "group_ids" is required.
     *
     * @return string JSON formatted HTTP response
     */
    public function pause($requestParams)
    {
        $requiredParams = array(
            "ids"
        );
        $options["headers"] = self::retrieveContentTypeHeader(self::CONTENT_TYPE_FORM);
        return $this->client->request($requestParams, $this->_endpointPlural . "/" . self::PAUSE, "post", $requiredParams, $options);
    }

    /**
     * @brief Unpause rules, sequences, and sequence subscribers for one or more objects.
     *
     * @param mixed[] $requestParams Array of parameters to submit with POST request.
     *                               Possible array keys:  "objectID" (required),"ids","start","range","sort","sortDir",
     *                                                     "condition","search","group_ids","performAll","externs","listFields".
     *                                                      Either "ids" or "group_ids" is required.
     *
     * @return string JSON formatted HTTP response
     */
    public function unpause($requestParams)
    {
        $requiredParams = array(
            "ids"
        );
        $options["headers"] = self::retrieveContentTypeHeader(self::CONTENT_TYPE_FORM);
        return $this->client->request($requestParams, $this->_endpointPlural . "/" . self::UNPAUSE, "post", $requiredParams, $options);
    }

    /**
     * @brief Remove one or more objects from one or more sequences
     *
     * @param mixed[] $requestParams Array of parameters to submit with DELETE request.
     *                               Possible array keys:  "objectID" (required),"remove_list" (required),"ids","start",
     *                                                     "range","sort","sortDir","condition","search","searchNotes",
     *                                                     "group_ids","performAll","externs","listFields".
     *                                                      Either "ids" or "group_ids" is required.
     *
     * @return string JSON formatted HTTP response
     */
    public function removeFromSequence($requestParams)
    {
        $requiredParams = array(
            "remove_list",
            "ids"
        );
        $options["headers"] = self::retrieveContentTypeHeader(self::CONTENT_TYPE_FORM);
        return $this->client->request($requestParams, $this->_endpointPlural . "/" . self::SEQUENCE, "delete", $requiredParams, $options);
    }

    /**
     * @brief Add one or more objects to one or more sequences
     *
     * @param mixed[] $requestParams Array of parameters to submit with PUT request.
     *                               Possible array keys:  "objectID" (required),"remove_list" (required),"ids","start",
     *                                                     "range","sort","sortDir","condition","search","searchNotes",
     *                                                     "group_ids","performAll","externs","listFields".
     *                                                      Either "ids" or "group_ids" is required.
     *
     * @return string JSON formatted HTTP response
     */
    public function addToSequence($requestParams)
    {
        $requiredParams = array(
            "add_list",
            "ids"
        );
        $options["headers"] = self::retrieveContentTypeHeader(self::CONTENT_TYPE_FORM);
        return $this->client->request($requestParams, $this->_endpointPlural . "/" . self::SEQUENCE, "put", $requiredParams, $options);
    }

    /**
     * @brief Remove one or more tags from one or more sequences
     *
     * @param mixed[] $requestParams Array of parameters to submit with DELETE request.
     *                               Possible array keys:  "objectID" (required),"remove_list" (required),"ids","start",
     *                                                     "range","sort","sortDir","condition","search","searchNotes",
     *                                                     "group_ids","performAll","externs","listFields".
     *                                                      Either "ids" or "group_ids" is required.
     *
     * @return string JSON formatted HTTP response
     */
    public function removeTag($requestParams)
    {
        $requiredParams = array(
            "remove_list",
            "ids"
        );
        $options["headers"] = self::retrieveContentTypeHeader(self::CONTENT_TYPE_FORM);
        return $this->client->request($requestParams, $this->_endpointPlural . "/" . self::TAG, "delete", $requiredParams, $options);
    }

    /**
     * @brief Add one or more tags to one or more objects
     *
     * @param mixed[] $requestParams Array of parameters to submit with PUT request.
     *                               Possible array keys:  "objectID" (required),"remove_list" (required),"ids","start",
     *                                                     "range","sort","sortDir","condition","search","searchNotes",
     *                                                     "group_ids","performAll","externs","listFields".
     *                                                      Either "ids" or "group_ids" is required.
     *
     * @return string JSON formatted HTTP response
     */
    public function addTag($requestParams)
    {
        $requiredParams = array(
            "add_list",
            "ids"
        );
        $options["headers"] = self::retrieveContentTypeHeader(self::CONTENT_TYPE_FORM);
        return $this->client->request($requestParams, $this->_endpointPlural . "/" . self::TAG, "put", $requiredParams, $options);
    }
}