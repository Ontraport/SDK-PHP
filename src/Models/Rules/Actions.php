<?php

namespace OntraportAPI\Models\Rules;
/**
* Class Actions
*
* @author ONTRAPORT
*
* @package OntraportAPI
*/

class Actions
{
    /**
    * Actions
    */
    // Customer Relationship Management (CRM)
    const ADD_OBJECT_TO_TAG = "Add_contact_to_category";
    const REMOVE_OBJECT_FROM_TAG = "Remove_contact_from_category";
    const ADD_REMOVE_OBJECT_FROM_CAMPAIGN = "campaign_builder_action_change";
    const PAUSE_UNPAUSE_OBJECT_ON_CAMPAIGN = "pause_or_unpause_on_camp";
    const ADD_OBJECT_TO_SEQUENCE = "Assign_contact_to_drip";
    const REMOVE_OBJECT_FROM_SEQUENCE = "Remove_contact_from_drip";
    const REMOVE_OBJECT_FROM_ALL_SEQUENCES = "Remove_contact_from_all_sequences";
    const PAUSE_SUBSCRIPTION_TO_SEQUENCE = "Pause_subscription_to_dripa";
    const UNPAUSE_SUBSCRIPTION_TO_SEQUENCE = "Unpause_subscription_to_dripa";
    const CHANGE_FIELD_VALUE = "Change_field_to_fieldvalue";
    const ADD_LEAD_ROUTER = "Add_to_leadrouter";
    const ADD_OBJECT_TO_FULFILLMENT = "Assign_to_fulfillment";
    const REMOVE_OBJECT_FROM_FULFILLMENT = "Remove_from_fulfillment";
    const REMOVE_FROM_ALL_FULFILLMENTS = "Remove_from_all_fulfillments";
    // Sales
    const RECHARGE_ALL_TRANSACTIONS_IN_COLLECTIONS = "Recharge_all_declined_transactions";
    const ADD_PRODUCT_TO_PURCHASE_HISTORY = "Add_product_to_purchase_history";
    const CANCEL_OPEN_ORDER = "Cancel_open_orders_with_product";
    // Messages
    const NOTIFY_WITH_EMAIL = "Notify_someone_with_emailmbs";
    const SEND_EMAIL = "Send_contact_an_emailmbs";
    const SEND_POSTCARD = "Send_contact_a_postcard";
    const ADD_TASK = "Send_contact_a_task";
    const SEND_SMS = "send_contact_an_sms";
    // Sites/Pages
    const PING_URL = "Ping_APIURL";
    // only contacts
    const GIVE_WPMEMBERSHIPLVL_ACCESS = "Add_access_for_contact_to_wpintmembershiplevel";
    const REMOVE_WPMEMBERSHIPLVL_ACCESS = "Remove_access_for_contact_to_wpintmembershiplevel";
    // Social
    const UPDATE_FB_CUSTOM_AUDIENCE = "facebook_audience_action";
    const NOTIFY_WITH_SMS = "Notify_someone_with_sms";

    public static function GetRequiredParams($rule)
    {
        $requiredParams = array(
            // Actions
            self::ADD_OBJECT_TO_TAG => array('tag_id'),
            self::REMOVE_OBJECT_FROM_TAG => array('tag_id'),
            self::ADD_REMOVE_OBJECT_FROM_CAMPAIGN => array('option', 'campaign_id'),
            self::PAUSE_UNPAUSE_OBJECT_ON_CAMPAIGN => array('option', 'campaign_id'),
            self::ADD_OBJECT_TO_SEQUENCE => array('sequence_id'),
            self::REMOVE_OBJECT_FROM_SEQUENCE => array('sequence_id'),
            self::REMOVE_OBJECT_FROM_ALL_SEQUENCES => array(),
            self::PAUSE_SUBSCRIPTION_TO_SEQUENCE => array('sequence_id'),
            self::UNPAUSE_SUBSCRIPTION_TO_SEQUENCE => array('sequence_id'),
            self::CHANGE_FIELD_VALUE => array('field_id', 'value', 'field_option'),
            self::ADD_LEAD_ROUTER => array('leadRouter_id'),
            self::ADD_OBJECT_TO_FULFILLMENT => array('fulfillment_id'),
            self::REMOVE_OBJECT_FROM_FULFILLMENT => array('fulfillment_id'),
            self::REMOVE_FROM_ALL_FULFILLMENTS => array(),
            // Sales
            self::RECHARGE_ALL_TRANSACTIONS_IN_COLLECTIONS => array(),
            self::ADD_PRODUCT_TO_PURCHASE_HISTORY => array('product_id'),
            self::CANCEL_OPEN_ORDER => array('product_id'),
            // Messages
            self::NOTIFY_WITH_EMAIL => array('user_id', 'email_id'),
            self::SEND_EMAIL => array('email_id'),
            self::SEND_POSTCARD => array('postcard_id'),
            self::ADD_TASK => array('task_id'),
            self::SEND_SMS => array('sms_id', 'number_id'),
            self::PING_URL => array('url', 'post_data', 'json'),
            self::GIVE_WPMEMBERSHIPLVL_ACCESS => array('wpMembership_id'),
            self::REMOVE_WPMEMBERSHIPLVL_ACCESS => array('wpMembership_id'),
            self::UPDATE_FB_CUSTOM_AUDIENCE => array('add_remove', 'custom_audience_id'),
            self::NOTIFY_WITH_SMS => array('user_id', 'sms_id', 'number_id')
        );
        return $requiredParams[$rule];
    }

    public static function CheckRestricted($rule)
    {
        $restrictedRules = array(
            // actions
            self::ADD_LEAD_ROUTER,
            self::RECHARGE_ALL_TRANSACTIONS_IN_COLLECTIONS,
            self::ADD_PRODUCT_TO_PURCHASE_HISTORY,
            self::CANCEL_OPEN_ORDER,
            self::SEND_POSTCARD,
            self::GIVE_WPMEMBERSHIPLVL_ACCESS,
            self::REMOVE_WPMEMBERSHIPLVL_ACCESS
        );

        if (in_array($rule, $restrictedRules))
        {
            return true;
        }
        return false;
    }

}
