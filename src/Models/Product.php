<?php
namespace OntraportAPI\Models;

use OntraportAPI\Models\Request as Request;

class Product implements Request
{
    private $_name;
    private $_price;
    private $_id;

    public function __construct($name, $price, $id = null)
    {
        $this->_id = $id;
        $this->_name = $name;
        $this->_price = $price;
    }

    /**
     * @brief Converts a response to a Request Object.
     * @param array $data A pre-decoded array of response data
     *
     * @return Product A new object with the data from the response
     */
    public static function CreateFromResponse(Array $data)
    {
        $id = $data["id"];
        $name = $data["name"];
        $price = $data["price"];

        $product = new Product($name, $price, $id);

        return $product;
    }

    /**
     * @brief Converts current object to an array for use as request parameters.
     *
     * @return array $requestParams Array of parameters for valid rule API request.
     */
    public function toRequestParams()
    {
        $requestParams = array();
        $requestParams["name"] = $this->_name;
        $requestParams["price"] = $this->_price;
        if ($this->_id != null)
        {
            $requestParams["id"] = $this->_id;
        }

        return $requestParams;
    }

    /**
     * @brief Sets the price of the Product
     *
     * @param double $new_price The Product's new price
     */
    public function setPrice($new_price)
    {
        $this->_price = $new_price;
    }

    /**
     * @brief Sets the name of the Product
     *
     * @param string $new_name The Product's new name
     */
    public function setName($new_name)
    {
        $this->_name = $new_name;
    }

    /**
     * @brief Gets the price of the product
     * @return int $_price
     */
    public function getPrice()
    {
        return $this->_price;
    }

    /**
     * @brief Gets the name of the product
     * @return int $_name
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @brief Gets the ID of the product
     * @return int $_id
     */
    public function getID()
    {
        return $this->_id;
    }

}
