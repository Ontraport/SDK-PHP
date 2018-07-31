<?php

namespace OntraportAPI;

require_once(__DIR__."/Models/Rules/RuleBuilder.php");
use OntraportAPI\Models\Rules\RuleBuilder;
/**
* Class Rules
*
* @author ONTRAPORT
*
* @package OntraportAPI
*/
class Rules extends BaseApi
{
    /**
    * @var string endpoint for single rule
    */
    protected $_endpoint = "Rule";

    /**
    * @var string endpoint for plural rules
    */
    protected $_endpointPlural = "Rules";

    /**
     * @param Ontraport $client
     */
    public function __construct(Ontraport $client)
    {
        parent::__construct($client);
    }

    /**
     * @brief Retrieve a single specified rule
     *
     * @param mixed[] $requestParams The parameters to submit with GET request.
     *                               Possible array keys: "id" (required)
     *
     * @return string JSON formatted HTTP response or error message
     */
    public function retrieveSingle($requestParams)
    {
        return parent::_retrieveSingle($requestParams);
    }

    /**
     * @brief Retrieve multiple rules according to specific criteria, handle pagination
     *
     * @param mixed[] $requestParams Array of parameters to submit with GET request. If "ids"
     *                               are not specified, all will be selected.
     *                               Possible array keys: "objectID" (required),"ids","start","range","sort","sortDir",
     *                                                    "condition","search","searchNotes","group_ids","performAll",
     *                                                    "externs","listFields"
     *
     * @return string JSON formatted array of paginated response data: each page of data will be an element in that array
     */
    public function retrieveMultiplePaginated($requestParams)
    {
        return parent::_retrieveMultiplePaginated($requestParams);
    }

    /**
     * @brief Retrieve multiple rules according to specific criteria
     *
     * @param mixed[] $requestParams Array of parameters to submit with GET request.
     *                               Possible array keys: "ids","start","range","sort","sortDir","condition","search",
     *                                                    "searchNotes","group_ids","performAll","externs","listFields"
     *
     * @return string JSON formatted HTTP response or error message.
     */
    public function retrieveMultiple($requestParams)
    {
        return parent::_retrieveMultiple($requestParams);
    }

    /**
     * @brief Retrieve meta for a rule
     *
     * @return string JSON formatted meta for a rule
     */
    public function retrieveMeta()
    {
        return parent::_retrieveMeta();
    }

    /**
     * @brief Retrieve information (such as number of rules) about a collection
     *
     * @param mixed[] $requestParams Array of parameters to submit with GET request.
     *                               Possible array keys: "condition","search","searchNotes","group_ids","performAll"
     *
     * @return string JSON formatted HTTP response or error message
     */
    public function retrieveCollectionInfo($requestParams)
    {
        return parent::_retrieveCollectionInfo($requestParams);
    }

    /**
     * @brief Delete a single specified rule
     *
     * @param mixed[] $requestParams Array of the parameters to submit with DELETE request.
     *                               Possible array keys: "id" (required)
     *
     * @return string JSON formatted HTTP response or error message
     */
    public function deleteSingle($requestParams)
    {
        return parent::_deleteSingle($requestParams);
    }

    /**
     * @brief Delete multiple rules according to specific criteria
     *
     * @param mixed[] $requestParams The parameters to submit with DELETE request.
     *                               Possible array keys: "ids","start","range","sort","sortDir","condition","search",
     *                                                    "searchNotes","group_ids","performAll","externs","listFields"
     *
     * @return string JSON formatted HTTP response or error message
     */
    public function deleteMultiple($requestParams)
    {
        return parent::_deleteMultiple($requestParams);
    }

    /**
     * @brief Create a rule
     *
     * @param mixed[] $requestParams Array of parameters to submit with POST request.
     *                               Possible array keys: "events" (required), "conditions", "actions" (required), "name",
     *                                                    "object type id" (required)
     *
     * @return string JSON formatted HTTP response or error message
     */
    public function create($requestParams)
    {
        $options["headers"] = self::retrieveContentTypeHeader(self::CONTENT_TYPE_JSON);
        $requiredParams = array(
            "events",
            "actions",
            "object_type_id"
        );
        unset($requestParams["id"]);

        return $this->client->request($requestParams, $this->_endpointPlural, "post", $requiredParams, $options);
    }

    /**
     * @brief Update a rule's data
     *
     * @param mixed[] $requestParams The parameters to submit with PUT request.
     *                               Possible array keys: "id" (required), "events", "conditions", "actions", "name"
     *
     * @return string JSON formatted HTTP response or error message
     */
    public function update($requestParams)
    {
        $requiredParams = array("id");
        $options["headers"] = self::retrieveContentTypeHeader(self::CONTENT_TYPE_FORM);

        return $this->client->request($requestParams, $this->_endpointPlural, "put", $requiredParams, $options);
    }
}
