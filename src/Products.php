<?php
namespace OntraportAPI;


class Products extends BaseApi
{
    /**
     * @var string endpoint for single product
     */
    protected $_endpoint = "Product";

    /**
     * @var string endpoint for plural products
     */
    protected $_endpointPlural = "Products";

    /**
     * Products constructor.
     * @param Ontraport $client
     */
    public function __construct(Ontraport $client)
    {
        parent::__construct($client);
    }

    /**
     * @brief Retrieve a single specified product
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
     * @brief Retrieve multiple products according to specific criteria, handle pagination
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
     * @brief Retrieve multiple products according to specific criteria
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
     * @brief Retrieve information (such as number of products) about a product collection
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
     * @brief Retrieve meta for a product object
     *
     * @return string JSON formatted meta for product object
     */
    public function retrieveMeta()
    {
        return parent::_retrieveMeta();
    }

    /**
     * @brief Create a product
     *
     * @param mixed[] $requestParams Array of parameters to submit with POST request.
     *                               Possible array keys: "name" (required), "price"
     *
     * @return string JSON formatted response
     */
    public function create($requestParams)
    {
        $options["headers"] = self::retrieveContentTypeHeader(self::CONTENT_TYPE_JSON);
        $requiredParams = array(
            "name"
        );
        unset($requestParams["id"]);

        return $this->client->request($requestParams, $this->_endpointPlural, "post", $requiredParams, $options);
    }

    /**
     * @brief Retrieve Section and Field information for a given product
     *
     * @param mixed[] $requestParams Array of parameters to submit with GET request.
     *                               Possible array keys: "section", "field"
     *
     * @return string JSON formatted HTTP response or error message
     */
    public function retrieveFields($requestParams)
    {
        return parent::_retrieveFields($requestParams);
    }

    /**
     * @brief Update a product's data
     *
     * @param mixed[] $requestParams Array of parameters to submit with GET request. All parameters are optional.
     *                               Possible array keys: "id" (required),"name", "price"
     *
     * @return string JSON formatted response
     */
    public function update($requestParams)
    {
        $requiredParams = array("id", "name");
        $options["headers"] = self::retrieveContentTypeHeader(self::CONTENT_TYPE_FORM);

        return $this->client->request($requestParams, $this->_endpointPlural, "put", $requiredParams, $options);
    }

    /**
     * @brief Delete a single specified product
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
     * @brief Delete multiple products according to specific criteria
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
     * @brief Create Sections and Fields in a given object record
     *
     * @param mixed[] $requestParams Array of parameters to submit with POST request.
     *                               Varies by object.
     *
     * @return string JSON formatted HTTP response or error message
     */
    public function createFields($requestParams)
    {
        return parent::_createFields($requestParams);
    }

    /**
     * @brief Update Sections and Fields in a given object record
     *
     * @param mixed[] $requestParams Array of parameters to submit with PUT request.
     *                               Varies by object.
     *
     * @return string JSON formatted HTTP response or error message
     */
    public function updateFields($requestParams)
    {
        return parent::_updateFields($requestParams);
    }

    /**
     * @brief Delete Sections and Fields from a given object record
     *
     * @param mixed[] $requestParams Array of parameters to submit with DELETE request.
     *                               Possible array keys: "section", "field"
     *
     * @return string JSON formatted HTTP response or error message
     */
    public function deleteFields($requestParams)
    {
        return parent::_deleteFields($requestParams);
    }
}
