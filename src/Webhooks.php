<?php

namespace OntraportAPI;

/**
 * Class Webhooks
 *
 * @author ONTRAPORT
 *
 * @package OntraportAPI
 */
class Webhooks extends BaseApi
{
    /**
     * $var string endpoint for single contact
     */
    protected $_endpoint = "Webhook";

    /**
     * $var string endpoint for plural contacts
     */
    protected $_endpointPlural = "Webhooks";

    /**
     * @param Ontraport $client
     */
    public function __construct(Ontraport $client)
    {
        parent::__construct($client);
    }

    // Webhook-specific function endpoints
    const SUBSCRIBE = "subscribe";
    const UNSUBSCRIBE = "unsubscribe";

    /**
     * @brief Retrieve a single specified webhook
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
     * @brief Retrieve multiple webhooks according to specific criteria, handle pagination
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
     * @brief Retrieve multiple webhooks according to specific criteria
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
     * @brief Subscribe to a webhook.
     *
     * @param mixed[] $requestParams Array of parameters to submit with POST request.
     *                               Possible array keys: "event" (required), "url" (required), "data"
     *
     * @return string JSON formatted response
     */
    public function subscribe($requestParams)
    {
        $requiredParams = array("event","url");
        return $this->client->request($requestParams, $this->_endpoint . "/" . self::SUBSCRIBE, "post", $requiredParams, $options = NULL);
    }

    /**
     * @brief Unsubscribe from a webhook.
     *
     * @param mixed[] $requestParams Array of parameters to submit with DELETE request.
     *                               Possible array keys: "id" (required)
     *
     * @return string JSON formatted response
     */
    public function unsubscribe($requestParams)
    {
        $requiredParams = array("id");
        return $this->client->request($requestParams, $this->_endpoint . "/" . self::UNSUBSCRIBE, "delete", $requiredParams, $options = NULL);
    }
}
