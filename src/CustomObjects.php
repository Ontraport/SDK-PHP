<?php

namespace OntraportAPI;

/**
 * Class CustomObjects
 *
 * @author ONTRAPORT
 *
 * @package OntraportAPI
 */
class CustomObjects extends Objects
{
    public function __construct(Ontraport $client, $object)
    {
        parent::__construct($client);

        $this->_objectID = $object;
    }

    private $_objectID = NULL;

    /**
     * @brief Retrieve a single specified object
     *
     * @param mixed[] $requestParams The parameters to submit with GET request.
     *                               Varies by object.
     *
     * @return string JSON formatted response
     */
    public function retrieveSingle($requestParams)
    {
        $requestParams["objectID"] = $this->_objectID;
        return parent::retrieveSingle($requestParams);
    }

    /**
     * @brief Retrieve multiple objects according to specific criteria
     *
     * @param mixed[] $requestParams Array of parameters to submit with GET request.
     *                               Varies by object.
     *
     * @return string JSON formatted response.
     */
    public function retrieveMultiple($requestParams)
    {
        $requestParams["objectID"] = $this->_objectID;
        return parent::retrieveMultiple($requestParams);
    }

    /**
     * @brief Create an object
     *
     * @param mixed[] $requestParams Array of parameters to submit with POST request.
     *                               Varies by object.
     *
     * @return string JSON formatted response
     */
    public function create($requestParams)
    {
        $requestParams["objectID"] = $this->_objectID;
        return parent::create($requestParams);
    }

    /**
     * @brief Delete a single specified object
     *
     * @param mixed[] $requestParams Array of the parameters to submit with DELETE request.
     *                               Varies by object.
     *
     * @return string JSON formatted response
     */
    public function deleteSingle($requestParams)
    {
        $requestParams["objectID"] = $this->_objectID;
        return parent::deleteSingle($requestParams);
    }

    /**
     * @brief Delete multiple objects according to specific criteria
     *
     * @param mixed[] $requestParams The parameters to submit with DELETE request.
     *                               Varies by object.
     *
     * @return string JSON formatted response
     */
    public function deleteMultiple($requestParams)
    {
        $requestParams["objectID"] = $this->_objectID;
        return parent::deleteMultiple($requestParams);
    }

    /**
     * @brief Update an object's data
     *
     * @param mixed[] $requestParams The parameters to submit with PUT request.
     *                               Varies by object.
     *
     * @return string JSON formatted response
     */
    public function update($requestParams)
    {
        $requestParams["objectID"] = $this->_objectID;
        return parent::update($requestParams);
    }

    /**
     * @brief Retrieve meta for a contact object
     *
     * @return string JSON formatted meta for contact object
     */
    public function retrieveMeta($requestParams = NULL)
    {
        $requestParams["objectID"] = $this->_objectID;
        return parent::retrieveMeta($requestParams);
    }

    /**
     * @brief Retrieve information (such as number of objects) about a collection
     *
     * @param mixed[] $requestParams Array of parameters to submit with GET request.
     *                               Varies by object.
     *
     * @return string JSON formatted response
     */
    public function retrieveCollectionInfo($requestParams)
    {
        $requestParams["objectID"] = $this->_objectID;
        return parent::retrieveCollectionInfo($requestParams);
    }
}