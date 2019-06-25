<?php

namespace OntraportAPI\Models;

use OntraportAPI\Exceptions as Exceptions;
use OntraportAPI\Models\Offer as Offer;

class Transaction
{
    const CHARGE_NOW = "chargeNow";
    const LOG = "chargeLog";

    const SUPPRESS = -1;
    const DEFAULT_TEMP = 1;

    private $_contactId;
    private $_chargeNow;
    private $_gatewayId;
    private $_invoiceTemplate = 1;
    private $_recurringInvoice = false;
    private $_ccId;
    private $_payer = array();
    private $_billingAddress = array();
    private $_offer;

    /**
     * Transaction constructor
     *
     * @param int $contact_id The id of the contact this transaction is for.
     * @param int $charge_now Binary integer flag indicating if this transaction should be charged now or logged
     * @param int $gateway_id The id of the gateway this transaction is going through.
     *
     * @throws Exceptions\OntraportAPIException No gateway id provided for charge now
     *
     */
    public function __construct($contact_id, $charge_now, $gateway_id = null)
    {
        if ($charge_now == Transaction::CHARGE_NOW && $gateway_id == null)
        {
            throw new Exceptions\OntraportAPIException("Gateway ID needed for charge now transactions.");
        }
        $this->_contactId = $contact_id;
        $this->_chargeNow = $charge_now;
        $this->_gatewayId = $gateway_id;
    }

    /**
     * @brief Loads an existing Offer object into a transaction
     *
     * @param Offer $offer
     * @throws Exceptions\OntraportAPIException
     */
    public function loadOffer($offer)
    {
        // load Offer object into transaction
        $this->_offer = $offer;

        $data = $offer->toRequestParams();
        $offer_data = json_decode($data["data"], true);

        if (empty($offer_data["products"]))
        {
            throw new Exceptions\OntraportAPIException("Your offer must have a product.");
        }
        // add into transaction data
        $this->setInvoiceTemplate($offer_data["invoice_template"]);
    }

    /**
     * @brief Sets the billing address in the transaction if no default, or other is preferred
     *
     * @param array $address Array containing billing address info
     * note: different gateways require different billing payment details. This handles the most general usages in app.
     *
     * @throws Exceptions\OntraportAPIException
     *
     */
    public function setBillingAddress(Array $address)
    {
        $keys = array("address", "address2", "city", "state", "zip", "country");
        $given_keys = array_keys($address);

        $missing_keys = array();
        // allow extra keys for different gateway usages, but must have all $keys except "address2"
        foreach ($keys as $key)
        {
            if ($key != "address2" && !in_array($key, $given_keys))
            {
                $missing_keys[] = $key;
            }
        }
        if (!empty($missing_keys))
        {
            $missing_msg = implode(", ", $missing_keys);
            throw new Exceptions\OntraportAPIException("Invalid billing address array. Missing keys: " . $missing_msg . ".");
        }
        $this->_billingAddress = $address;
    }

    /**
     * @brief Sets the payer information in the transaction if no default, or other is preferred
     *
     * @param array/int $payer Array containing payer info
     * note: document different gateway requirements
     *
     * @throws Exceptions\OntraportAPIException
     *
     */
    public function setPayer($payer)
    {
        if (is_array($payer))
        {
            $keys = array("ccnumber", "code", "expire_month", "expire_year");
            $given_keys = array_keys($payer);
            $missing_keys = array();
            // allow "code" to be optional
            foreach ($keys as $key)
            {
                if ($key != "code" && !in_array($key, $given_keys))
                {
                    $missing_keys[] = $key;
                }
            }
            if (!empty($missing_keys))
            {
                $missing_msg = implode(", ", $missing_keys);
                throw new Exceptions\OntraportAPIException("Invalid Payer array. Missing keys: " . $missing_msg . ".");
            }
            else
            {
                $this->_ccId = null;
                $this->_payer = $payer;
            }
        }
        else if (is_numeric($payer))
        {
            // reset to empty payer array when using existing ccId
            $this->_payer = array();
            $this->_ccId = $payer;
        }
        else
        {
            throw new Exceptions\OntraportAPIException("Payer must be array or integer type.");
        }
    }

    /**
     * @brief Sets the invoice template in the transaction
     *
     * @param int $template_id The id of the invoice template to use
     * note: To suppress invoices, set the $template_id to const SUPPRESS
     *
     */
    public function setInvoiceTemplate($template_id)
    {
        $this->_invoiceTemplate = $template_id;
    }

    /**
     * @brief Sends an invoice for every recurring payment in this transaction
     *
     * @param bool $recurring True => send, False => do not send
     */
    public function sendRecurringInvoice($recurring)
    {
        $this->_recurringInvoice = $recurring;
    }

    /**
     * @brief Converts transaction object to data array for processManual endpoint
     *
     * @throws Exceptions\RequiredParamsException If no added product or default billing address/credit card
     */
    public function toRequestParams()
    {
        $data = array();

        // construct basic transaction data
        $data["contact_id"] = $this->_contactId;
        $data["chargeNow"] = $this->_chargeNow;
        $data["invoice_template"] = $this->_invoiceTemplate;
        $data["gateway_id"] = $this->_gatewayId;

        if ($this->_ccId)
        {
            $data["cc_id"] = $this->_ccId;
        }

        // construct offer array
        $array = $this->_offer->toRequestParams();
        $data["offer"] = json_decode($array["data"], true);
        if (empty($data["offer"]["products"]))
        {
            throw new Exceptions\RequiredParamsException("Products");
        }
        // abstract away irrelevant fields for transaction
        unset($data["offer"]["name"]);
        unset($data["offer"]["invoice_template"]);
        $data["offer"]["send_recurring_invoice"] = $this->_recurringInvoice;

        // construct transaction payment information
        if (!empty($this->_payer))
        {
            $data["payer"] = $this->_payer;
        }
        if (!empty($this->_billingAddress))
        {
            $data["billing_address"] = $this->_billingAddress;
        }

        return $data;
    }

}
