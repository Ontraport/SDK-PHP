<?php

namespace OntraportAPI;

/**
 * Class LandingPages
 *
 * @author ONTRAPORT
 *
 * @package OntraportAPI
 */
class LandingPages extends BaseApi
{
    /**
     * $var string endpoint for single landing page
     */
    protected $_endpoint = "LandingPage";

    /**
     * $var string endpoint for plural landing page
     */
    protected $_endpointPlural = "LandingPages";

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
    private $_mainLandingPageEndpoint = "landingPage";

    // Landing Page specific function endpoint
    const HOSTED_URL = "getHostedURL";

    /**
     * @brief Retrieve a single specified landing page
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
     * @brief Retrieve multiple landing pages according to specific criteria
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
     * @brief Retrieve information (such as number of landing pages) about landing page collection
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
     * @brief Retrieve meta for a landing page object
     *
     * @return string JSON formatted meta for landing page object
     */
    public function retrieveMeta()
    {
        return parent::_retrieveMeta();
    }

    /**
     * @brief Retrieve the permanent URL for a landing page.
     *
     * @param mixed[] $requestParams Array of parameters to submit with GET request.
     *                               Possible array keys: "id" (required)
     *
     * @return string JSON formatted response
     */
    public function getHostedURL($requestParams)
    {
        $requiredParams = array("id");
        return $this->client->request($requestParams, $this->_mainLandingPageEndpoint . "/" . self::HOSTED_URL, "get", $requiredParams, $options = NULL);
    }
}