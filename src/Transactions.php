<?php

namespace OntraportAPI;

/**
 * Class Transactions
 *
 * @author ONTRAPORT
 *
 * @package OntraportAPI
 */
class Transactions extends BaseApi
{
    /**
     * $var string endpoint for single transaction
     */
    protected $_endpoint = "Transaction";

    /**
     * $var string endpoint for plural transactions
     */
    protected $_endpointPlural = "Transactions";

    /**
     * @param Ontraport $client
     */
    public function __construct(Ontraport $client)
    {
        parent::__construct($client);
    }

    // Transaction-specific function endpoints
    const CONVERT_COLLECTIONS = "convertToCollections";
    const CONVERT_DECLINE = "convertToDecline";
    const MARK_PAID = "markPaid";
    const ORDER = "order";
    const PROCESS_MANUAL = "processManual";
    const REFUND = "refund";
    const RERUN = "rerun";
    const RERUN_COMMISSION = "rerunCommission";
    const RESEND_INVOICE = "resendInvoice";
    const VOID = "void";
    const WRITE_OFF = "writeOff";

    /**
     * @TODO: This is a placeholder, API needs to be changed to follow one standard for naming endpoints
     */
    private $_mainTransactionEndpoint = "transaction";

    /**
     * @brief Retrieve a single specified transaction
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
     * @brief Retrieve multiple transactions according to specific criteria, handle pagination
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
     * @brief Retrieve multiple transactions according to specific criteria
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
     * @brief Retrieve information (such as number of transactions) about transaction collection
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
     * @brief Retrieve meta for a transaction object
     *
     * @return string JSON formatted meta for transaction object
     */
    public function retrieveMeta()
    {
        return parent::_retrieveMeta();
    }

    /**
     * @brief Convert the status of one or more transactions to collections
     *
     * @param mixed[] $requestParams Array of parameters to submit with PUT request.
     *                               Possible array keys: "id" (required)
     *
     * @return string JSON formatted HTTP response
     */
    public function convertToCollections($requestParams)
    {
        $requiredParams = array("id");
        $options["headers"] = self::retrieveContentTypeHeader(self::CONTENT_TYPE_FORM);
        return $this->client->request($requestParams, $this->_mainTransactionEndpoint . "/" . self::CONVERT_COLLECTIONS, "put", $requiredParams, $options);
    }

    /**
     * @brief Convert the status of one or more transactions to declined
     *
     * @param mixed[] $requestParams Array of parameters to submit with PUT request.
     *                               Possible array keys: "id" (required)
     *
     * @return string JSON formatted HTTP response
     */
    public function convertToDeclined($requestParams)
    {
        $options["headers"] = self::retrieveContentTypeHeader(self::CONTENT_TYPE_FORM);
        $requiredParams = array("id");
        return $this->client->request($requestParams, $this->_mainTransactionEndpoint . "/" . self::CONVERT_DECLINE, "put", $requiredParams, $options);
    }

    /**
     * @brief Mark a transaction as paid
     *
     * @param mixed[] $requestParams Array of parameters to submit with PUT request.
     *                               Possible array keys: "id" (required)
     *
     * @return string JSON formatted HTTP response
     */
    public function markAsPaid($requestParams)
    {
        $options["headers"] = self::retrieveContentTypeHeader(self::CONTENT_TYPE_FORM);
        $requiredParams = array("id");
        return $this->client->request($requestParams, $this->_mainTransactionEndpoint . "/" . self::MARK_PAID, "put", $requiredParams, $options);
    }

    /**
     * @brief Retrieve information about an order
     *
     * @param mixed[] $requestParams Array of parameters to submit with GET request.
     *                               Possible array keys: "id" (required)
     *
     * @return string JSON formatted HTTP response
     */
    public function retrieveOrder($requestParams)
    {
        $requiredParams = array("id");
        return $this->client->request($requestParams, $this->_mainTransactionEndpoint . "/" . self::ORDER, "get", $requiredParams, $options = NULL);
    }

    /**
     * @brief Update order information
     *
     * @param mixed[] $requestParams Array of parameters to submit with GET request.
     *                               Possible array keys: "id" (required)
     *
     * @return string JSON formatted HTTP response
     */
    public function updateOrder($requestParams)
    {
        $options["headers"] = self::retrieveContentTypeHeader(self::CONTENT_TYPE_JSON);
        $requiredParams = array("offer");
        return $this->client->request($requestParams, $this->_mainTransactionEndpoint . "/" . self::ORDER, "put", $requiredParams, $options);
    }

    /**
     * @brief Create a transaction for a contact
     *
     * @param mixed[] $requestParams Array of parameters to submit with POST request.
     *                               Possible array keys: "contact_id" (required),"chargeNow" (required),"trans_date",
     *                                                    "invoice_template" (required),"gateway_id (required),"offer" (required),
     *                                                    "billing_address","payer"
     *
     * @return string JSON formatted HTTP response
     */
    public function processManual($requestParams)
    {
        $options["headers"] = self::retrieveContentTypeHeader(self::CONTENT_TYPE_JSON);
        $requiredParams = array(
            "contact_id",
            "chargeNow",
            "offer"
        );
        return $this->client->request($requestParams, $this->_mainTransactionEndpoint . "/" . self::PROCESS_MANUAL, "post", $requiredParams, $options);
    }

    /**
     * @brief Refund one or more transactions
     *
     * @param mixed[] $requestParams Array of parameters to submit with PUT request.
     *                               Possible array keys: "objectID" (required),"ids","condition","start","range","group_ids",
     *                                                    "performAll","search","searchNotes"
     *
     * @return string JSON formatted response
     */
    public function refund($requestParams)
    {
        $options["headers"] = self::retrieveContentTypeHeader(self::CONTENT_TYPE_JSON);
        $requiredParams = array("objectID");
        return $this->client->request($requestParams, $this->_mainTransactionEndpoint . "/" . self::REFUND, "put", $requiredParams, $options);
    }

    /**
     * @brief Rerun a single transaction or a group of transactions in collections
     *
     * @param mixed[] $requestParams Array of parameters to submit with POST request.
     *                               Possible array keys: "objectID" (required),"ids","condition","start","range","group_ids",
     *                                                    "performAll","search","searchNotes"
     *
     * @return string JSON formatted response
     */
    public function rerun($requestParams)
    {
        $options["headers"] = self::retrieveContentTypeHeader(self::CONTENT_TYPE_JSON);
        $requiredParams = array("objectID");
        return $this->client->request($requestParams, $this->_mainTransactionEndpoint . "/" . self::RERUN, "post", $requiredParams, $options);
    }

    /**
     * @brief Rerun a partner commission
     *
     * @param mixed[] $requestParams Array of parameters to submit with PUT request.
     *                               Possible array keys: "objectID" (required),"ids","condition","start","range","group_ids",
     *                                                    "performAll","search","searchNotes"
     *
     * @return string JSON formatted response
     */
    public function rerunCommission($requestParams)
    {
        $options["headers"] = self::retrieveContentTypeHeader(self::CONTENT_TYPE_JSON);
        $requiredParams = array("objectID");
        return $this->client->request($requestParams, $this->_mainTransactionEndpoint . "/" . self::RERUN_COMMISSION, "put", $requiredParams, $options);
    }

    /**
     * @brief Resend a transaction invoice
     *
     * @param mixed[] $requestParams Array of parameters to submit with POST request.
     *                               Possible array keys: "objectID" (required),"ids","condition","start","range","group_ids",
     *                                                    "performAll","search","searchNotes"
     *
     * @return string JSON formatted response
     */
    public function resendInvoice($requestParams)
    {
        $options["headers"] = self::retrieveContentTypeHeader(self::CONTENT_TYPE_JSON);
        $requiredParams = array("objectID");
        return $this->client->request($requestParams, $this->_mainTransactionEndpoint . "/" . self::RESEND_INVOICE, "post", $requiredParams, $options);
    }

    /**
     * @brief Void one or more transactions
     *
     * @param mixed[] $requestParams Array of parameters to submit with PUT request.
     *                               Possible array keys: "objectID" (required),"ids","condition","start","range","group_ids",
     *                                                    "performAll","search","searchNotes"
     *
     * @return string JSON formatted response
     */
    public function void($requestParams)
    {
        $options["headers"] = self::retrieveContentTypeHeader(self::CONTENT_TYPE_JSON);
        $requiredParams = array("objectID");
        return $this->client->request($requestParams, $this->_mainTransactionEndpoint . "/" . self::VOID, "put", $requiredParams, $options);
    }

    /**
     * @brief Write off one or more transactions
     *
     * @param mixed[] $requestParams Array of parameters to submit with PUT request.
     *                               Possible array keys: "objectID" (required),"ids","condition","start","range","group_ids",
     *                                                    "performAll","search","searchNotes"
     *
     * @return string JSON formatted response
     */
    public function writeOff($requestParams)
    {
        $options["headers"] = self::retrieveContentTypeHeader(self::CONTENT_TYPE_JSON);
        $requiredParams = array("objectID");
        return $this->client->request($requestParams, $this->_mainTransactionEndpoint . "/" . self::WRITE_OFF, "put", $requiredParams, $options);
    }
}
