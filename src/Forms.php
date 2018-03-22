<?php

namespace OntraportAPI;

/**
 * Class Forms
 *
 * @author ONTRAPORT
 *
 * @package OntraportAPI
 */
class Forms extends BaseApi
{
    /**
     * $var string endpoint for single form
     */
    protected $_endpoint = "Form";

    /**
     * $var string endpoint for plural forms
     */
    protected $_endpointPlural = "Forms";

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
    private $_mainFormEndpoint = "form";

    // Form specific function endpoint
    const FORM_BLOCKS = "getAllFormBlocks";
    const FORM_BLOCKS_BY_NAME = "getBlocksByFormName";

    /**
     * @brief Retrieve a single specified form
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
     * @brief Retrieve multiple forms according to specific criteria, handle pagination
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
     * @brief Retrieve multiple forms according to specific criteria
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
     * @brief Retrieve information (such as number of forms) about form collection
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
     * @brief Retrieve meta for a form object
     *
     * @return string JSON formatted response
     */
    public function retrieveMeta()
    {
        return parent::_retrieveMeta();
    }

    /**
     * @brief Retrieve HTML for a Smart Form
     *
     * @param mixed[] $requestParams Array of parameters to submit with GET request.
     *                               Possible array keys: "id" (required)
     *
     * @return string JSON formatted response
     */
    public function retrieveSmartFormHTML($requestParams)
    {
        $requiredParams = array("id");
        return $this->client->request($requestParams, $this->_mainFormEndpoint, "get", $requiredParams, $options = NULL);
    }

    /**
     * @brief Retrieve name and ID pairs for form blocks
     *
     * @param mixed[] $requestParams Array of parameters to submit with GET request.
     *                               Possible array keys: "page"
     *
     * @return string JSON formatted response
     */
    public function retrieveFormBlocks($requestParams = NULL)
    {
        return $this->client->request($requestParams, $this->_endpoint . "/" . self::FORM_BLOCKS, "get", NULL, $options = NULL);
    }

    /**
     * @brief Retrieve name and ID pairs for form blocks
     *
     * @param mixed[] $requestParams Array of parameters to submit with GET request.
     *                               Possible array keys: "page"
     *
     * @return string JSON formatted response
     */
    public function retrieveBlocksByForm($requestParams)
    {
        $requiredParams = array("name");
        return $this->client->request($requestParams, $this->_endpoint . "/" . self::FORM_BLOCKS_BY_NAME, "get", $requiredParams, $options = NULL);
    }
}
