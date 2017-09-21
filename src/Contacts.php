<?php

namespace OntraportAPI;

/**
 * Class Contacts
 *
 * @author ONTRAPORT
 *
 * @package OntraportAPI
 */
class Contacts extends BaseApi
{
    /**
     * $var string endpoint for single contact
     */
    protected $_endpoint = "Contact";

    /**
     * $var string endpoint for plural contacts
     */
    protected $_endpointPlural = "Contacts";

    /**
     * @param Ontraport $client
     */
    public function __construct(Ontraport $client)
    {
        parent::__construct($client);
    }

    /**
     * @brief Retrieve a single specified contact
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
     * @brief Retrieve multiple contacts according to specific criteria, handle pagination
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
     * @brief Retrieve multiple contacts according to specific criteria
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
     * @brief Create a contact
     *
     * @param mixed[] $requestParams Array of parameters to submit with POST request.
     *                               Possible array keys: "firstname","lastname","email","freferrer","lreferrer","address",
     *                                                    "city","state","zip","birthday","aff_paypal","program_id",
     *                                                    "bulk_mail","office_phone","fax","company","address2","website",
     *                                                    "title","country","n_lead_source","n_campaign","n_content",
     *                                                    "n_content","n_medium","referral_page","num_purchased","owner",
     *                                                    "bulk_sms","sms_number",{customFields}(if you have custom contact
     *                                                    fields in your account, these can be included in the array keys)
     *
     * @return string JSON formatted response
     */
    public function create($requestParams)
    {
        return parent::_create($requestParams);
    }

    /**
     * @brief Delete a single specified contact
     *
     * @param mixed[] $requestParams Array of the parameters to submit with DELETE request.
     *                               Possible array keys: "id" (required)
     *
     * @return string JSON formatted response
     */
    public function deleteSingle($requestParams)
    {
        return parent::_deleteSingle($requestParams);
    }

    /**
     * @brief Delete multiple contacts according to specific criteria
     *
     * @param mixed[] $requestParams The parameters to submit with DELETE request. All parameters are optional but if "ids"
     *                               are not specified, all will be deleted.
     *                               Possible array keys: "ids","start","range","sort","sortDir","condition","search",
     *                                                    "searchNotes","group_ids","performAll","externs","listFields"
     *
     * @return string JSON formatted response
     */
    public function deleteMultiple($requestParams)
    {
        return parent::_deleteMultiple($requestParams);
    }

    /**
     * @brief Update a contact's data
     *
     * @param mixed[] $requestParams The parameters to submit with PUT request.
     *                               Possible array keys: "id" (required),"firstname","lastname","email","freferrer",
     *                                                    "lreferrer","address","city","state","zip","birthday","aff_paypal",
     *                                                    "program_id","bulk_mail","office_phone","fax","company","address2",
     *                                                    "website","title","country","n_lead_source","n_campaign","n_content",
     *                                                    "n_content","n_medium","referral_page","num_purchased","owner",
     *                                                    "bulk_sms","sms_number",{customFields}(if you have custom contact
     *                                                    fields in your account, these can be included in the array keys)
     *
     * @return string JSON formatted response
     */
    public function update($requestParams)
    {
        return parent::_update($requestParams);
    }

    /**
     * @brief Retrieve meta for a contact object
     *
     * @return string JSON formatted meta for contact object
     */
    public function retrieveMeta()
    {
        return parent::_retrieveMeta();
    }

    /**
     * @brief Either create a contact or merge with an existing contact on unique field
     *
     * @param mixed[] $requestParams The parameters to submit with PUT request.
     *                               Possible array keys: "firstname","lastname","email","freferrer","lreferrer","address",
     *                                                    "city","state","zip","birthday","aff_paypal","program_id",
     *                                                    "contact_cat","bulk_mail","office_phone","fax","company",
     *                                                    "address2","website","title","country","spent","n_lead_source",
     *                                                    "n_campaign","n_content","n_medium","referral_page","aff_sales",
     *                                                    "aff_amount","mriInvoiceNum","mriInvoiceTotal","mrcAmount",
     *                                                    "mrcUnpaid","mrcResult","grade","num_purchased","owner",
     *                                                    "bulk_sms","sms_number","updateSequence","n_term",{customFields}
     *                                                    (if you have custom contact fields in your account, these can be
     *                                                    included in the array keys)
     *
     * @return string JSON formatted response
     */
    public function saveOrUpdate($requestParams)
    {
        return parent::_saveOrUpdate($requestParams);
    }

    /**
     * @brief Retrieve information (such as number of contacts) about contact collection
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
}