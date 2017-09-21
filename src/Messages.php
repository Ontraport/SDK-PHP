<?php

namespace OntraportAPI;

/**
 * Class Messages
 *
 * @author ONTRAPORT
 *
 * @package OntraportAPI
 */
class Messages extends BaseApi
{
    /**
     * $var string endpoint for single message
     */
    protected $_endpoint = "Message";

    /**
     * $var string endpoint for plural messages
     */
    protected $_endpointPlural = "Messages";

    /**
     * @param Ontraport $client
     */
    public function __construct(Ontraport $client)
    {
        parent::__construct($client);
    }

    /*
     * @TODO This is a placeholder, API needs to be revised to follow one standard for endpoint naming
     */
    private $_mainMessageEndpoint = "message";

    /**
     * @brief Retrieve a single specified message
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
     * @brief Retrieve multiple messages according to specific criteria, handle pagination
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
     * @brief Retrieve multiple messages according to specific criteria
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
     * @brief Retrieve information (such as number of messages) about message collection
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
     * @brief Retrieve meta for a message object
     *
     * @return string JSON formatted meta for message object
     */
    public function retrieveMeta()
    {
        return parent::_retrieveMeta();
    }

    /**
     * @brief Create a new message
     *
     * @param mixed[] $requestParams Array of parameters to submit with POST request.
     *                               Possible array keys: "alias","name","subject","type" (must be "template","email",
     *                                                    "sms", or "task), "object_type_id" (default 0 for contacts),
     *                                                    "from" (can be "owner","custom", or a staff ID), "send_out_name"
     *                                                    "reply_to_email","plaintext","send_from","message_body",
     *                                                    "email_title"
     *                               Legacy email only: "message_body"
     *                               ONTRAmail only: "resource" (use with caution)
     *                               SMS only: "send_to" (default sms_number)
     *                               Task only: "task_data","due_date","task_owner","task_form"
     *
     * @return string JSON formatted response
     */
    public function create($requestParams)
    {
        $requiredParams = array("type");
        return $this->client->request($requestParams, $this->_mainMessageEndpoint, "post", $requiredParams, $options = NULL);
    }

    /**
     * @brief Update an existing message
     *
     * @param mixed[] $requestParams Array of parameters to submit with POST request.
     *                               Possible array keys: "id" (required),"alias","name","subject","type" (must be
     *                                                    "template","email", "sms", or "task),"object_type_id"
     *                                                    (default 0 for contacts),"from" (can be "owner","custom", or a
     *                                                    staff ID), "send_out_name","reply_to_email","plaintext",
     *                                                    "send_from","message_body","email_title"
     *                               Legacy email only: "message_body"
     *                               ONTRAmail only: "resource" (use with caution)
     *                               SMS only: "send_to" (default sms_number)
     *                               Task only: "task_data","due_date","task_owner","task_form"
     *
     * @return string JSON formatted response
     */
    public function update($requestParams)
    {
        $requiredParams = array("id","type");
        return $this->client->request($requestParams, $this->_mainMessageEndpoint, "put", $requiredParams, $options = NULL);
    }
}