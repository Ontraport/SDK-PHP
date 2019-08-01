<?php

namespace OntraportAPI;

/**
 * Class Purchases
 *
 * @author ONTRAPORT
 *
 * @package OntraportAPI
 */
class Purchases extends BaseApi
{
    /**
     * $var string endpoint for single purchase
     */
    protected $_endpoint = "Purchase";

    /**
     * $var string endpoint for plural purchases
     */
    protected $_endpointPlural = "Purchases";

    /**
     * @param Ontraport $client
     */
    public function __construct(Ontraport $client)
    {
        parent::__construct($client);
    }

    /**
     * @TODO: This is a placeholder, API needs to be changed to follow one standard for naming endpoints
     */
    private $_mainPurchaseEndpoint = "purchase";

    /**
     * @brief Retrieve a single specified purchase
     *
     * @param mixed[] $requestParams The parameters to submit with GET request.
     *                               Possible array keys: "id" (required)
     *
     * @return string JSON formatted HTTP response
     */
    public function retrieveSingle($requestParams)
    {
        return parent::_retrieveSingle($requestParams);
    }

    /**
     * @brief Retrieve multiple purchases according to specific criteria, handle pagination
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
     * @brief Retrieve multiple purchases according to specific criteria
     *
     * @param mixed[] $requestParams Array of parameters to submit with GET request. All parameters are optional but if "ids"
     *                               are not specified, all will be selected.
     *                               Possible array keys: "ids","start","range","sort","sortDir","condition","search",
     *                                                    "searchNotes","group_ids","performAll","externs","listFields"
     *
     * @return string JSON formatted HTTP response
     */
    public function retrieveMultiple($requestParams)
    {
        return parent::_retrieveMultiple($requestParams);
    }

    /**
     * @brief Retrieve information (such as number of purchases) about purchase collection
     *
     * @param mixed[] $requestParams Array of parameters to submit with GET request. All parameters are optional.
     *                               Possible array keys: "condition","search","searchNotes","group_ids","performAll"
     *
     * @return string JSON formatted HTTP response
     */
    public function retrieveCollectionInfo($requestParams)
    {
        return parent::_retrieveCollectionInfo($requestParams);
    }

    /**
     * @brief Retrieve meta for a purchase object
     *
     * @return string JSON formatted meta for purchase object
     */
    public function retrieveMeta()
    {
        return parent::_retrieveMeta();
    }
}
