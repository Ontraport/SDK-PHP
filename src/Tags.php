<?php

namespace OntraportAPI;

/**
 * Class Tags
 *
 * @author ONTRAPORT
 *
 * @package OntraportAPI
 */
class Tags extends BaseApi
{
    /**
     * $var string endpoint for single tag
     */
    protected $_endpoint = "Tag";

    /**
     * $var string endpoint for plural tags
     */
    protected $_endpointPlural = "Tags";

    /**
     * @param Ontraport $client
     */
    public function __construct(Ontraport $client)
    {
        parent::__construct($client);
    }

    /**
     * @brief Retrieve a single specified tag
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
     * @brief Retrieve multiple tags according to specific criteria, handle pagination
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
     * @brief Retrieve multiple tags according to specific criteria
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
     * @brief Retrieve information (such as number of tags) about a collection of tags
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
     * @brief Retrieve meta for a tag object
     *
     * @return string JSON formatted meta for tag object
     */
    public function retrieveMeta()
    {
        return parent::_retrieveMeta();
    }

    /**
     * @brief Create a new tag
     *
     * @param mixed[] $requestParams Array of parameters to submit with POST request.
     *                               Possible array keys: "tag_name" (required),
     *                                                    "object_type_id" (optional, default 0)
     *                               
     * @return string JSON formatted response
     */
    public function create($requestParams)
    {
        return parent::_create($requestParams);
    }

    /**
     * @brief Update an existing tag
     *
     * @param mixed[] $requestParams Array of parameters to submit with POST request.
     *                               Possible array keys: "id" (required),
     *                                                    "tag_name" (required)
     *                               
     * @return string JSON formatted response
     */
    public function update($requestParams)
    {
        return parent::_update($requestParams);
    }

    /**
     * @brief Delete a single specified tag
     *
     * @param mixed[] $requestParams Array of the parameters to submit with DELETE request.
     *                               Possible array keys: "id" (required)
     *
     * @return string JSON formatted response
     */
    public function deleteSingle($requestParams)
    {
        return parent::_deleteSingle($requestParams);
    }

    /**
     * @brief Delete multiple tags according to specific criteria
     *
     * @param mixed[] $requestParams The parameters to submit with DELETE request. All parameters are optional but if "ids"
     *                               are not specified, all will be deleted.
     *                               Possible array keys: "ids","start","range","sort","sortDir","condition","search",
     *                                                    "searchNotes","group_ids","performAll","externs","listFields"
     *
     * @return string JSON formatted response
     */
    public function deleteMultiple($requestParams)
    {
        return parent::_deleteMultiple($requestParams);
    }
}