<?php

namespace OntraportAPI;


class Offers extends BaseApi
{
    /**
     * @var string endpoint for single offer
     */
    protected $_endpoint = "Offer";

    /**
     * @var string endpoint for plural offers
     */
    protected $_endpointPlural = "Offers";

    /**
     * Offers constructor.
     * @param Ontraport $client
     */
    public function __construct(Ontraport $client)
    {
        parent::__construct($client);
    }

    /**
     * @brief Retrieve a single specified offer
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
     * @brief Retrieve multiple offers according to specific criteria, handle pagination
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
     * @brief Retrieve multiple offers according to specific criteria
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
     * @brief Retrieve information (such as number of offers) about an offers collection
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
     * @brief Retrieve meta for an offer object
     *
     * @return string JSON formatted meta for offer object
     */
    public function retrieveMeta()
    {
        return parent::_retrieveMeta();
    }

    /**
     * @brief Create an offer
     *
     * @param mixed[] $requestParams Array of parameters to submit with POST request.
     *                               Possible array keys: "name" (required), "data" (required), "public"
     *
     * @return string JSON formatted response
     */
    public function create($requestParams)
    {
        $options["headers"] = self::retrieveContentTypeHeader(self::CONTENT_TYPE_JSON);
        $requiredParams = array(
            "name",
            "data"
        );
        unset($requestParams["id"]);

        return $this->client->request($requestParams, $this->_endpointPlural, "post", $requiredParams, $options);
    }

    /**
     * @brief Update an offer's data
     *
     * @param mixed[] $requestParams Array of parameters to submit with GET request. All parameters are optional.
     *                               Possible array keys: "id" (required),"name", "data", "public"
     *
     * @return string JSON formatted response
     */
    public function update($requestParams)
    {
        $options["headers"] = self::retrieveContentTypeHeader(self::CONTENT_TYPE_JSON);
        $requiredParams = array(
            "id"
        );

        return $this->client->request($requestParams, $this->_endpointPlural, "put", $requiredParams, $options);
    }
}
