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
    const PAY_INVOICE = "payInvoice";
    const PROCESS_MANUAL = "processManual";
    const REFUND = "refund";
    const TOKENIZE_SESSION = "tokenizeSession";
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
     * @brief Pay an open invoice with one of the contact's saved credit cards.
     *
     * @param mixed[] $requestParams Array of parameters to submit with POST request.
     *                               Possible array keys: "invoice_id" (required), "cc_id",
     *                                                    "payer" (token form only: "pci_card_token" or "pci_cvc_token")
     *
     * @return string JSON formatted HTTP response
     */
    public function payInvoice($requestParams)
    {
        $options["headers"] = self::retrieveContentTypeHeader(self::CONTENT_TYPE_JSON);
        $requiredParams = array(
            "invoice_id"
        );
        return $this->client->request($requestParams, $this->_mainTransactionEndpoint . "/" . self::PAY_INVOICE, "post", $requiredParams, $options);
    }

    /**
     * @brief Create a card tokenization session: everything needed to send card data
     *        directly to Ontraport's PCI-secured domain instead of to this API.
     *        See tokenizeCard() for the whole flow in one call.
     *
     * @return string JSON formatted HTTP response whose data contains:
     *                "url" (where to POST the card fields), "aid", "hash" (single-use),
     *                and "expires_in" (seconds the hash is guaranteed valid)
     */
    public function tokenizeSession()
    {
        return $this->client->request(array(), $this->_mainTransactionEndpoint . "/" . self::TOKENIZE_SESSION, "post", array(), array());
    }

    /**
     * @brief Tokenize a card in one call: creates a tokenization session, sends the card
     *        fields directly to the PCI url from that session (never to the regular API),
     *        and returns the single-use token to pass as payer["pci_card_token"] --
     *        or payer["pci_cvc_token"] when using the "cvc_only" form -- to
     *        processManual() or payInvoice(). This is the recommended way to send card
     *        data; sending raw card numbers in charge calls is discouraged and will
     *        eventually be retired.
     *
     * @param mixed[] $card_fields Form fields for the tokenizer. Keys:
     *                             "payment_number", "payment_code" (optional CVV),
     *                             "payment_expire_month", "payment_expire_year",
     *                             "payment_name" (optional) -- or, to refresh only the
     *                             security code of a saved card: "cvc_only" => 1 with
     *                             just "payment_code" (this helper supplies the
     *                             tokenizer's required "cvconly" placeholder number
     *                             automatically).
     *
     * @throws \OntraportAPI\Exceptions\OntraportAPIException on session or tokenization failure
     *
     * @return string the single-use exchange token (valid for one use, for about 5 minutes)
     */
    public function tokenizeCard($card_fields)
    {
        $session_response = json_decode($this->tokenizeSession(), true);
        $session = (is_array($session_response) && isset($session_response["data"])) ? $session_response["data"] : null;
        if (empty($session["url"]) || empty($session["hash"]) || empty($session["aid"]))
        {
            throw new \OntraportAPI\Exceptions\OntraportAPIException("Unable to create a tokenization session.");
        }

        // The tokenizer's CVC-only form still requires a card-number field carrying
        // the literal "cvconly" placeholder; supply it so callers don't have to know.
        if (!empty($card_fields["cvc_only"]) && empty($card_fields["payment_number"]))
        {
            $card_fields["payment_number"] = "cvconly";
        }

        $post_fields = array_merge($card_fields, array(
            "aid" => $session["aid"],
            "hash" => $session["hash"]
        ));

        $curl_handle = curl_init($session["url"]);
        curl_setopt_array($curl_handle, array(
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($post_fields),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 40,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/x-www-form-urlencoded",
                "Accept: application/json"
            )
        ));
        $tokenize_response = curl_exec($curl_handle);
        curl_close($curl_handle);

        $tokenized = json_decode($tokenize_response, true);
        if (!is_array($tokenized) || !isset($tokenized["status"]) || $tokenized["status"] !== "OK" || empty($tokenized["token"]))
        {
            $message = (is_array($tokenized) && isset($tokenized["message"])) ? $tokenized["message"] : "Card tokenization failed.";
            throw new \OntraportAPI\Exceptions\OntraportAPIException($message);
        }

        return $tokenized["token"];
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
