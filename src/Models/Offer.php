<?php

namespace OntraportAPI\Models;

use OntraportAPI\Exceptions;
use OntraportAPI\Models\Request as Request;

class Offer implements Request
{
    const DAY = "day";
    const WEEK = "week";
    const MONTH = "month";
    const QUARTER = "quarter";
    const YEAR = "year";

    const SUPPRESS = -1;
    const DEFAULT_TEMP = 1;

    private $_name;
    private $_id;
    private $_products = array();
    private $_subscriptions = array();
    private $_paymentPlans = array();
    private $_trials = array();
    private $_taxes = array();
    private $_shipping = array();
    private $_invoiceTemplate = 1;
    private $_shipping_charge_reoccurring_order = false;

    public function __construct($name, $id = null)
    {
        $this->_name = $name;
        $this->_id = $id;
    }

    /**
     * @brief Converts a response to a Request Object.
     * @param array $data A pre-decoded array of response data
     *
     * @return Offer $offer New offer object with $data
     */
    public static function CreateFromResponse(Array $data)
    {
        $offer = new Offer($data["name"], $data["id"]);

        $offer_data = json_decode($data["data"], true);

        // individual fields
        $offer->setInvoiceTemplate($offer_data["invoice_template"]);

        if ($offer_data["shipping_charge_reoccurring_orders"] == true)
        {
            $offer->chargeShippingForReoccurringOrders(true);
        }

        if (!empty($offer_data["taxes"]))
        {
            foreach($offer_data["taxes"] as $tax)
            {
                $offer->addTax($tax["id"], $tax["rate"], $tax["tax_shipping"], $tax["name"]);
            }
        }

        if (!empty($offer_data["shipping"]))
        {
            $shipping = $offer_data["shipping"];
            $offer->setShipping($shipping["id"], $shipping["price"], $shipping["name"]);
        }
        // complex fields (products, price, subscriptions, payments, trials)
        $products = $offer_data["products"];

        foreach ($products as $product)
        {
            $product_id = $product["id"];

            $id = $product_id;
            $name = $product["name"];
            $quantity = $product["quantity"];
            $shipping = $product["shipping"];
            $tax = $product["tax"];
            $price = $product["price"][0]["price"];
            $payment_count = $product["price"][0]["payment_count"];
            $unit = $product["price"][0]["unit"];

            $prod = new Product($name, $price, $id);

            $offer->addProduct($prod, $quantity, $shipping, $tax);

            if ($product["type"] == "subscription")
            {
                $offer->addSubscription($product_id, $unit, $price);
            }
            else if ($product["type"] == "payment_plan")
            {
                $offer->addPaymentPlan($product_id, $price, $payment_count, $unit);
            }
            if (isset($product["trial"]))
            {
                $trial_data = $product["trial"];
                $price = $trial_data["price"];
                $payment_count = $trial_data["payment_count"];
                $unit = $trial_data["unit"];
                $offer->addTrial($product_id, $price, $payment_count, $unit);
            }
        }

        return $offer;
    }

    /**
     * @brief Adds an existing product in the account to the offer
     *
     * @param Product $product The product's id
     * @param int $quantity The product's quantity
     * @param boolean $shipping Value indicating if this product has a shipping cost
     * @param boolean $taxable Value indicating if this product will be included in the tax calculations
     *
     * @throws Exceptions\OntraportAPIException if invalid $product
     * @return array $_products
     */
    public function addProduct($product, $quantity, $shipping = false, $taxable = false)
    {
        if ($product instanceof Product)
        {
            $product_id = $product->getID();
            if (!$product_id)
            {
                throw new Exceptions\OntraportAPIException("Product ID not defined in passed Product object.");
            }
            $name = $product->getName();
            $price = $product->getPrice();
        }
        else
        {
            throw new Exceptions\OntraportAPIException("Product parameter must be of Product object type.");
        }
        $this->_products[$product_id] = array(
            "id" => $product_id,
            "name" => $name,
            "quantity" => $quantity,
            "price" => $price,
            "shipping" => $shipping,
            "tax" => $taxable
        );

        return $this->_products;
    }

    /**
     * @brief Adds a trial to a product in the transaction
     *
     * @param int $product_id The id of product to add trial to in transaction
     * @param double $price The price of the trial per unit
     * @param int $payment_count The amount of $unit for this trial
     * @param string $unit The incremental time unit for this trial
     *
     * @return array $_trials
     */
    public function addTrial($product_id, $price, $payment_count, $unit)
    {
        if (!isset($this->_products[$product_id]))
        {
            throw new Exceptions\OntraportAPIException("A product with that product id does not exist in this offer.");
        }
        $this->_trials[] = array(
            "price" => $price,
            "payment_count" => $payment_count,
            "unit" => $unit
        );

        $idx = count($this->_trials) - 1;
        $trial = array(
            "trial" => $idx
        );
        $init_product = $this->_products[$product_id];
        $this->_products[$product_id] = array_merge($init_product, $trial);

        return $this->_trials;
    }

    /**
     * @brief Adds a subscription to a product in the transaction
     *
     * @param int $product_id The id of product to add a subscription to in transaction
     * @param string $unit The incremental time unit
     * @param double $price The price of the product for each recurring subscription payment
     *
     * note: cannot have subscription and payment plan on the same product
     * @throws Exceptions\OntraportAPIException If a payment plan exists for the product id
     * @return array $_subscriptions
     */
    public function addSubscription($product_id, $unit, $price = null)
    {
        if (!isset($this->_products[$product_id]))
        {
            throw new Exceptions\OntraportAPIException("A product with that product id does not exist in this offer.");
        }
        $init_product = $this->_products[$product_id];

        if (array_key_exists("payment_plan", $init_product))
        {
            throw new Exceptions\OntraportAPIException("An existing payment plan is already associated with this"
                . " product. Delete the payment plan before adding this subscription.");
        }
        // Have to specify a price somewhere in transaction for subscriptions
        if (!isset($price) && !isset($this->_products[$product_id]["price"]))
        {
            throw new Exceptions\OntraportAPIException("A price must be indicated for this product to add a subscription.");
        }
        // if no price specified in subscription call, use product's price
        if (!isset($price))
        {
            $price = $this->_products[$product_id]["price"];
        }
        // if price is specified in subscription call, use that price
        $subscription_data = array(
            "price" => $price,
            "payment_count" => 1,
            "unit" => $unit
        );

        $this->_subscriptions[] = $subscription_data;

        $idx = count($this->_subscriptions) - 1;
        $subscription = array(
            "subscription" => $idx,
            "type" => "subscription"
        );
        // insert subscription info into corresponding product in $_products array
        $this->_products[$product_id] = array_merge($init_product, $subscription);
        return $this->_subscriptions;
    }

    /**
     * @brief Adds a payment plan to a product in the transaction
     *
     * @param int $product_id The id of product to add a payment plan to in transaction
     * @param double $price The price of the payment per unit
     * @param int $payment_count The amount of $unit for this payment
     * @param string $unit The incremental time unit for this payment
     *
     * note: cannot have subscription and payment plan on the same product
     * @throws Exceptions\OntraportAPIException If a subscription exists for the product id
     * @return array $_paymentPlans
     */
    public function addPaymentPlan($product_id, $price, $payment_count, $unit)
    {
        if (!isset($this->_products[$product_id]))
        {
            throw new Exceptions\OntraportAPIException("A product with that product id does not exist in this offer.");
        }
        $init_product = $this->_products[$product_id];

        if (array_key_exists("subscription", $init_product))
        {
            throw new Exceptions\OntraportAPIException("An existing subscription is already associated with this"
                . " product. Delete the subscription before adding this payment plan.");
        }

        $this->_paymentPlans[] = array(
            "price" => $price,
            "payment_count" => $payment_count,
            "unit" => $unit
        );

        $idx = count($this->_paymentPlans) - 1;
        $paymentPlan = array(
            "payment_plan" => $idx,
            "type" => "payment_plan"
        );
        // insert payment plan info into corresponding product in $_products array
        $this->_products[$product_id] = array_merge($init_product, $paymentPlan);

        return $this->_paymentPlans;
    }

    /**
     * @brief Deletes a product in the offer
     *
     * @param $product_id
     * @return array $_products Array of updated products
     */
    public function deleteProduct($product_id)
    {
        if (isset($this->_products[$product_id]["trial"]))
        {
            $this->deleteTrial($product_id);
        }
        if (isset($this->_products[$product_id]["subscription"]))
        {
            $this->deleteSubscription($product_id);
        }
        if (isset($this->_products[$product_id]["payment_plan"]))
        {
            $this->deletePaymentPlan($product_id);
        }
        unset($this->_products[$product_id]);

        return $this->_products;
    }
    /**
     * @brief Deletes a payment plan associated with a product
     *
     * @param int $product_id The ID of the product the payment plan is associated with
     * @return array $_paymentPlans Array of updated payment plans
     */
    public function deletePaymentPlan($product_id)
    {
        $idx = $this->_products[$product_id]["payment_plan"];
        unset($this->_products[$product_id]["payment_plan"]);
        unset($this->_products[$product_id]["type"]);
        $this->_paymentPlans[$idx] = null;

        return $this->_paymentPlans;
    }

    /**
     * @brief Deletes a subscription associated with a product
     * @param int $product_id The ID of the product the subscription is associated with
     * @return array $_subscriptions Array of updated subscriptions
     */
    public function deleteSubscription($product_id)
    {
        $idx = $this->_products[$product_id]["subscription"];
        unset($this->_products[$product_id]["subscription"]);
        unset($this->_products[$product_id]["type"]);
        $this->_subscriptions[$idx] = null;

        return $this->_subscriptions;
    }

    /**
     * @brief Deletes a trial associated with a product
     * @param int $product_id The ID of the product the trial is associated with
     * @return array $_trials Array of updated trials
     */
    public function deleteTrial($product_id)
    {
        $idx = $this->_products[$product_id]["trial"];
        unset($this->_products[$product_id]["trial"]);
        $this->_trials[$idx] = null;

        return $this->_trials;
    }

    /**
     * @brief Adds tax information to the offer
     *
     * @param int $tax_id The id of the tax
     * @param double $rate The rate of the tax
     * @param boolean $tax_shipping Whether or not the tax applies to shipping as well
     * @param string $name The name of the tax (optional, defaults to "Tax")
     *
     * @throws Exceptions\OntraportAPIException If duplicate tax is given
     * @return array $_taxes
     */
    public function addTax($tax_id, $rate, $tax_shipping = false, $name = "Tax")
    {
        if ($tax_id === 0)
        {
            throw new Exceptions\OntraportAPIException("Tax ID cannot be 0.");
        }
        $this->_taxes[] = array(
            "id" => $tax_id,
            "rate" => $rate,
            "name" => $name,
            "taxShipping" => $tax_shipping
        );

        return $this->_taxes;
    }

    /**
     * @brief Sets the shipping information in the offer
     *
     * @param int $shipping_id The id of the shipping on the account
     * @param double $price The price of the shipping
     * @param string $name The name of the shipping (optional, defaults to "Shipping")
     * note: calling this function a subsequent time will overwrite the shipping information in the offer
     */
    public function setShipping($shipping_id, $price, $name = "Shipping")
    {
        if ($shipping_id === 0)
        {
            throw new Exceptions\OntraportAPIException("Shipping ID cannot be 0.");
        }
        $this->_shipping = array(
            "id" => $shipping_id,
            "name" => $name,
            "price" => $price
        );

        return $this->_shipping;
    }

    /**
     * @brief Unsets the shipping information in the offer
     * note: no parameters required because each offer can only have one shipping selection
     */
    public function unsetShipping()
    {
        $this->_shipping = array();
    }

    /**
     * @brief Deletes a tax that has been previously added
     *
     * @param $tax_id
     * @return array $_taxes Array of updated taxes
     */
    public function deleteTax($tax_id)
    {
        $idx = 0;
        foreach ($this->_taxes as $tax)
        {
            if ($tax["id"] == $tax_id)
            {
                unset($this->_taxes[$idx]);
            }
            $idx++;
        }
        return $this->_taxes;
    }

    /**
     * @brief Charge shipping for each reoccurring order
     *
     * @param bool $charge Whether or not to charge shipping on each reoccurring order
     */
    public function chargeShippingForReoccurringOrders($charge)
    {
        $this->_shipping_charge_reoccurring_order = $charge;
    }

    /**
     * @brief Sets the name of the offer
     *
     * @param string $name The offer's new name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * @brief Sets the invoice template in the offer
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
     * @brief Converts current object to an array for use as request parameters.
     *
     * @return array $requestParams Array of parameters for valid rule API request.
     * @throws Exceptions\OntraportAPIException when $_products is empty
     */
    public function toRequestParams()
    {
        $requestParams = array();
        if (empty($this->_products))
        {
            throw new Exceptions\OntraportAPIException("Offer must have a product.");
        }
        $requestParams["name"] = $this->_name;
        $requestParams["public"] = 1;
        if ($this->_id)
        {
            $requestParams["id"] = $this->_id;
        }
        $data = array();

        foreach ($this->_products as $product)
        {
            $init_price = $product["price"];
            $idx = $product[$product["type"]];

            if ($product["type"] == "subscription")
            {
                $subscription_info = $this->_subscriptions[$idx];
                $price = $subscription_info;
            }
            else if ($product["type"] == "payment_plan")
            {
                $payment_info = $this->_paymentPlans[$idx];
                $price = $payment_info;
            }
            else
            {
                $price = null;
                if ($init_price !== null)
                {
                    $price = array("price" => $init_price);
                }
                $product["type"] = "single";
            }

            if (isset($product["trial"]))
            {
                $idx = $product["trial"];
                $trial_info = $this->_trials[$idx];
                unset($product["trial"]);
                $product["trial"] = $trial_info;
            }
            unset($product[$product["type"]]);

            if (isset($price))
            {
                unset($product["price"]);
                $product["price"][] = $price;
            }
            $data["products"][] = $product;
        }

        if (!empty($this->_shipping))
        {
            $data["shipping"] = $this->_shipping;
            $data["hasShipping"] = true;
        }
        if (!empty($this->_taxes))
        {
            $data["taxes"] = $this->_taxes;
            $data["hasTaxes"] = true;
        }

        $data["invoice_template"] = $this->_invoiceTemplate;
        $data["shipping_charge_reoccurring_orders"] = $this->_shipping_charge_reoccurring_order;
        $data["name"] = $this->_name;
        $requestParams["data"] = json_encode($data);

        return $requestParams;
    }

}
