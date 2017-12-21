<?php

namespace OntraportAPI;

/**
 * Class CreditCards
 *
 * @author ONTRAPORT
 *
 * @package OntraportAPI
 */
class CreditCards extends BaseApi
{
    /**
     * $var string endpoint for single credit card
     */
    protected $_endpoint = "CreditCard";

    /**
     * $var string endpoint for plural forms
     */
    protected $_endpointPlural = "CreditCards";

    // Credit card-specific function endpoints
    const DEFAULT_CARD = "default";

    /**
     * @param Ontraport $client
     */
    public function __construct(Ontraport $client)
    {
        parent::__construct($client);
    }

    /**
     * @brief Retrieve a single specified credit card.
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
     * @brief Retrieve multiple credit cards according to specific criteria, handle pagination
     *
     * @param mixed[] $requestParams Array of parameters to submit with GET request. All parameters are optional but if "ids"
     *                               are not specified, all will be selected.
     *                               Possible array keys: "ids","start","range","sort","sortDir","condition","search",
     *                                                    "searchNotes","group_ids","performAll","externs","listFields"
     *
     * @return string JSON formatted array of response data: each page of data will be an element in that array
     */
    public function retrieveMultiplePaginated($requestParams)
    {
        return parent::_retrieveMultiplePaginated($requestParams);
    }

    /**
     * @brief Retrieve multiple credit cards according to specific criteria
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
     * @brief Retrieve information (such as number of credit cards) about credit card collection
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
     * @brief Retrieve meta for a credit card object
     *
     * @return string JSON formatted response
     */
    public function retrieveMeta()
    {
        return parent::_retrieveMeta();
    }

    /**
     * @brief Set a specifed credit card as the default.
     *
     * @param mixed[] $requestParams The parameters to submit with PUT request.
     *                               Possible array keys: "id" (required)
     *
     * @return string JSON formatted response
     */
    public function setDefault($requestParams)
    {
        $requiredParams = array("id");
        return $this->client->request($requestParams, $this->_endpoint . "/" . self::DEFAULT_CARD, "put", $requiredParams, $options = NULL);
    }
}
