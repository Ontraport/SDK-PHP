<?php

namespace OntraportAPI;

/**
 * Class Tasks
 *
 * @author ONTRAPORT
 *
 * @package OntraportAPI
 */
class Tasks extends BaseApi
{
    /**
     * $var string endpoint for single task
     */
    protected $_endpoint = "Task";

    /**
     * $var string endpoint for plural tasks
     */
    protected $_endpointPlural = "Tasks";

    /**
     * @param Ontraport $client
     */
    public function __construct(Ontraport $client)
    {
        parent::__construct($client);
    }

    /*
     * @TODO: This is just a placeholder, the API needs to be fixed to only use the new endpoint standards
     */
    private $_mainTaskEndpoint = "task";

    // Task-specific function endpoints
    const TASK_ASSIGN = "assign";
    const TASK_CANCEL = "cancel";
    const TASK_COMPLETE = "complete";
    const TASK_RESCHEDULE = "reschedule";

    /**
     * @brief Retrieve a single specified task
     *
     * @param mixed[] $requestParams The parameters to submit with GET request.
     *                               Possible array keys: "id" (required)
     *
     * @return string JSON formatted response
     */
    public function retrieveSingle($requestParams)
    {
        return parent::_retrieveSingle($requestParams);
    }

    /**
     * @brief Retrieve multiple tasks according to specific criteria, handle pagination
     *
     * @param mixed[] $requestParams Array of parameters to submit with GET request. All parameters are optional but if "ids"
     *                               are not specified, all will be selected.
     *                               Possible array keys: "ids","start","range","sort","sortDir","condition","search",
     *                                                    "searchNotes","group_ids","performAll","externs","listFields"
     *
     * @return string JSON formatted array of response data: each page of data will be an element in that array.
     */
    public function retrieveMultiplePaginated($requestParams)
    {
        return parent::_retrieveMultiplePaginated($requestParams);
    }

    /**
     * @brief Retrieve multiple tasks according to specific criteria
     *
     * @param mixed[] $requestParams Array of parameters to submit with GET request. All parameters are optional but if "ids"
     *                               are not specified, all will be selected.
     *                               Possible array keys: "ids","start","range","sort","sortDir","condition","search",
     *                                                    "searchNotes","group_ids","performAll","externs","listFields"
     *
     * @return string JSON formatted response
     */
    public function retrieveMultiple($requestParams)
    {
        return parent::_retrieveMultiple($requestParams);
    }

    /**
     * @brief Update a task's data
     *
     * @param mixed[] $requestParams Array of parameters to submit with GET request. All parameters are optional.
     *                               Possible array keys: "id" (required),"owner","date_due","status"
     *
     * @return string JSON formatted response
     */
    public function update($requestParams)
    {
        return parent::_update($requestParams);
    }

    /**
     * @brief Retrieve information (such as number of tasks) about task collection
     *
     * @param mixed[] $requestParams Array of parameters to submit with GET request. All parameters are optional.
     *                               Possible array keys: "condition","search","searchNotes","group_ids","performAll"
     *
     * @return string JSON formatted response
     */
    public function retrieveCollectionInfo($requestParams)
    {
        return parent::_retrieveCollectionInfo($requestParams);
    }

    /**
     * @brief Retrieve meta for a task object
     *
     * @return string JSON formatted meta for task object
     */
    public function retrieveMeta()
    {
        return parent::_retrieveMeta();
    }

    /**
     * @brief Assign a task to one or more contacts
     *
     * @param mixed[] $requestParams Array of parameters to submit with POST request.
     *                               Possible array keys: "object_type_id" (required),"ids" (required),"group_ids",
     *                                                    "performAll","message"
     *
     * @return string JSON formatted response
     */
    public function assign($requestParams)
    {
        $options["headers"] = self::retrieveContentTypeHeader(self::CONTENT_TYPE_JSON);
        $requiredParams = array(
            "object_type_id",
            "ids"
            );
        return $this->client->request($requestParams, $this->_mainTaskEndpoint . "/" . self::TASK_ASSIGN, "post", $requiredParams, $options);
    }

    /**
     * @brief Cancel a task or list of tasks
     *
     * @param mixed[] $requestParams Array of parameters to submit with POST request.
     *                               Possible array keys: "objectID" (required),"ids","start","range","condition","search","searchNotes",
     *                                                    "group_ids","performAll"
     *
     * @return string JSON formatted response
     */
    public function cancel($requestParams)
    {
        $options["headers"] = self::retrieveContentTypeHeader(self::CONTENT_TYPE_JSON);
        $requiredParams = array("objectID");
        return $this->client->request($requestParams, $this->_mainTaskEndpoint . "/" . self::TASK_CANCEL, "post", $requiredParams, $options);
    }

    /**
     * @brief Marks one or more tasks as completed
     *
     * @param mixed[] $requestParams Array of parameters to submit with POST request.
     *                               Possible array keys: "object_type_id" (required),"ids","group_ids","performAll","data"
     *
     * @return string JSON formatted response
     */
    public function complete($requestParams)
    {
        $options["headers"] = self::retrieveContentTypeHeader(self::CONTENT_TYPE_JSON);
        $requiredParams = array("object_type_id");
        return $this->client->request($requestParams, $this->_mainTaskEndpoint."/". self::TASK_COMPLETE, "post", $requiredParams, $options);
    }

    /**
     * @brief Reschedules a task
     *
     * @param mixed[] $requestParams Array of parameters to submit with POST request.
     *                               Possible array keys: "id" (required),"newtime"
     *
     * @return string JSON formatted response
     */
    public function reschedule($requestParams)
    {
        $options["headers"] = self::retrieveContentTypeHeader(self::CONTENT_TYPE_JSON);
        $requiredParams = array("id");
        return $this->client->request($requestParams, $this->_mainTaskEndpoint . "/" . self::TASK_RESCHEDULE, "post", $requiredParams, $options);
    }
}