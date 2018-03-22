<?php

namespace OntraportAPI;

require_once("APIAutoloader.php");

/**
 * Class Ontraport
 *
 * @author ONTRAPORT
 *
 * @package OntraportAPI
 */
class Ontraport
{
    /**
     * @var string the base URL HTTP requests are made to
     */
    const REQUEST_URL = "https://api.ontraport.com";

    /**
     * @var int the API version number for this wrapper
     */
    const API_VERSION = 1;

    /**
     * @var string unique site id for API
     */
    private $_siteID;

    /**
     * @var int unique API key
     */
    private $_apiKey;

    /**
     * @var array contains all instantiated object APIs so multiple instances can be avoided
     */
    protected $_apiInstances = array();

    /**
     * @var array contains list of current custom objects so they don't need to be searched for more than once
     */
    protected $_customObjects = array();

    /**
     * @var CurlClient instance
     */
    protected $_httpClient = NULL;

    /**
     * @brief constructs an instance of OntraportAPI
     *
     * @param string $siteID
     * @param string $apiKey
     */
    public function __construct($siteID, $apiKey, $httpClient = null)
    {
        $this->setCredentials($apiKey, $siteID);
        $this->setHttpClient($httpClient);
    }

    /**
     * @brief sets API key credential
     *
     * @param string $apiKey
     */
    public function setCredentials($apiKey, $siteID)
    {
        $this->_apiKey = $apiKey;
        $this->_siteID = $siteID;
    }

    /**
     * @brief sets HTTP client
     */
    public function setHttpClient($httpClient = null)
    {
        if ($httpClient === null)
        {
            $this->_httpClient = new CurlClient($this->_apiKey, $this->_siteID);
            return;
        }
        $this->_httpClient = $httpClient;
    }

    /**
     * @brief gets HTTP client
     */
    public function getHttpClient()
    {
        return $this->_httpClient;
    }

    /**
     * @brief Make an HTTP request
     *
     * @param $requestParams
     * @param string $url
     * @param string $method
     * @param array $requiredParams
     * @param array $options
     *
     * @return mixed
     */
    public function request($requestParams, $url, $method, $requiredParams, $options)
    {
        $client = $this->getHttpClient();
        $url = $this->buildEndpoint($url);

        return $client->httpRequest($requestParams, $url, $method, $requiredParams, $options);
    }

    /**
     * @brief gets the last HTTP status code received by the HTTP Client
     *
     * @return int
     */
     public function getLastStatusCode()
     {
         return $this->getHttpClient()->getLastStatusCode();
     }

    /**
     * @brief constructs an api endpoint
     *
     * @param string $extendURL
     * @param string $function
     *
     * @return string
     */
    public function buildEndpoint($extendURL)
    {
        return self::REQUEST_URL . "/" . self::API_VERSION . "/" . $extendURL;
    }

    /**
     * @param integer $object
     *
     * @return CustomObjects instance
     *
     * @throws Exceptions\CustomObjectException
     */
    public function custom($object)
    {
        if (empty($this->_customObjects))
        {
            $this->_customObjects = $this->object()->retrieveCustomObjects();
        }

        if (array_key_exists($object, $this->_customObjects))
        {
            return $this->getApi("CustomObjects", $object);
        }
        throw new Exceptions\CustomObjectException();
    }

    /**
     * @return CampaignBuilderItems
     */
    public function campaignbuilder()
    {
        return $this->getApi("CampaignBuilderItems");
    }

    /**
     * @return Contacts
     */
    public function contact()
    {
        return $this->getApi("Contacts");
    }

    /**
     * @return CreditCards
     */
    public function creditcard()
    {
        return $this->getApi("CreditCards");
    }

    /**
     * @return Forms
     */
    public function form()
    {
        return $this->getApi("Forms");
    }

    /**
     * @return LandingPages
     */
    public function landingpage()
    {
        return $this->getApi("LandingPages");
    }

    /**
     * @return Messages
     */
    public function message()
    {
        return $this->getApi("Messages");
    }

    /**
     * @return Tasks
     */
    public function task()
    {
        return $this->getApi("Tasks");
    }

    /**
     * @return Transactions
     */
    public function transaction()
    {
        return $this->getApi("Transactions");
    }

    /**
     * @return Objects
     */
    public function object()
    {
        return $this->getApi("Objects");
    }

    /**
     * @return Webhooks
     */
    public function webhook()
    {
        return $this->getApi("Webhooks");
    }

    /**
     * @brief if requested API is already instantiated, grabs instance from an array, otherwise autoloads an instance of the API
     * @param string $class
     * @param integer|null $object
     *
     * @return mixed
     */
    public function getApi($class, $object = NULL)
    {
        $absoluteClass = "\\OntraportAPI\\" . $class;

        // For custom objects
        if ($object)
        {
            // Generate a unique name so that wrong object id is not stored with instantiated class
            $class = $class . "." . $object;
            if (!array_key_exists($class, $this->_apiInstances))
            {
                $this->_apiInstances[$class] = new $absoluteClass($this, $object);
            }
        }

        else
        {
            if (!array_key_exists($class, $this->_apiInstances))
            {
                $this->_apiInstances[$class] = new $absoluteClass($this);
            }
        }

        return $this->_apiInstances[$class];
    }
}
