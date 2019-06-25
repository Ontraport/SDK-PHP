<?php

namespace OntraportAPI\Models\Rules;
/**
* Class Events
*
* @author ONTRAPORT
*
* @package OntraportAPI
*/

class Events
{
    /**
    * Events (i.e. Triggers)
    */
    // Customer Relationship Management (CRM)
    const OBJECT_CREATED = "Contact_added_to_my_database";
    const FIELD_UPDATED	= "field_is_updated";
    const OBJECT_ADDED_TO_SEQUENCE = "Contact_added_to_campaign";
    const OBJECT_REMOVED_FROM_SEQUENCE = "Contact_leaves_campaign";
    const SUBSCRIPTION_TO_SEQUENCE_PAUSED = "Subscription_to_drip_is_paused";
    const SUBSCRIPTION_TO_SEQUENCE_RESUMED = "Subscription_to_drip_is_unpaused";
    const OBJECT_PAUSED_ON_CAMPAIGN = "pause_campaign";
    const OBJECT_RESUMED_ON_CAMPAIGN = "unpause_campaign";
    const OBJECT_ADDED_TO_TAG = "Contact_added_to_category";
    const OBJECT_REMOVED_FROM_TAG = "Contact_removed_from_category";
    const OBJECT_ADDED_TO_FULFILLMENT = "Contact_subscribed_to_fulfillment";
    const OBJECT_REMOVED_FROM_FULFILLMENT = "Contact_unsubscribed_from_fulfillment";
    const TODAY_MATCHES_OBJECT_DATE = "on_date_field";
    const TODAY_RELATIVE_TO_OBJECT_DATE = "relative_date_field";
    const OBJECT_ADDED_OR_REMOVED_FROM_CAMPAIGN = "campaign_builder_object_change";
    const TASK_COMPLETED = "object_completed_task";
    const OBJECT_SUBMITS_FORM = "object_submits_form";
    const CALL_IS_LOGGED = "call_is_logged";
    // External Events
    const OBJECT_OPENS_EMAIL = "Contact_opens_email";
    const OBJECT_CLICKS_EMAIL_LINK = "Contact_clicks_emailanchor";
    const OBJECT_SENDS_YOU_EMAIL = "Contact_sends_Email";
    const SMS_RECEIVED = "sms_message_received";
    // Sales
    const OBJECT_PURCHASES_PRODUCT = "Contact_purchases_product";
    const OBJECT_RECEIVES_REFUND = "Contact_receives_refund_on_product";
    const OBJECT_VISITS_LANDINGPAGE = "Contact_visits_landingpage_splittest";
    const OBJECT_VISITS_PURL = "Contact_visits_purl_splittest";
    const OPEN_ORDER_CREATED = "Contact_is_subscribed_to_productsub";
    const OPEN_ORDER_CHARGED_UPDATED = "Contact_subscription_to_productsub_is_subaction";
    const CARD_CHARGED_DECLINED = "Contact_credit_card_is_ccstatus";
    // Sites/Pages
    const OBJECT_VISITS_URL = "contact_visits_url";
    const TRACKED_LINKED_CLICKED = "clicks_tracked_link";
    const ACCESS_TO_WPMEMBERSHIPLVL_GIVEN = "Contact_given_access_to_wpintmembershiplevel";
    const LOSES_ACCESS_TO_WPMEMBERSHIPLVL = "Contact_removed_from_access_to_wpintmembershiplevel";

    public static function GetRequiredParams($rule)
    {
        $requiredParams = array(
            // Events (i.e. Triggers)
            self::OBJECT_CREATED => array(),
            self::FIELD_UPDATED	=> array('field_id'),
            self::OBJECT_ADDED_TO_SEQUENCE => array('sequence_id'),
            self::OBJECT_REMOVED_FROM_SEQUENCE => array('sequence_id'),
            self::SUBSCRIPTION_TO_SEQUENCE_PAUSED => array('sequence_id'),
            self::SUBSCRIPTION_TO_SEQUENCE_RESUMED => array('sequence_id'),
            self::OBJECT_PAUSED_ON_CAMPAIGN => array('campaign_id'),
            self::OBJECT_RESUMED_ON_CAMPAIGN => array('campaign_id'),
            self::OBJECT_ADDED_TO_TAG => array('tag_id'),
            self::OBJECT_REMOVED_FROM_TAG => array('tag_id'),
            self::OBJECT_ADDED_TO_FULFILLMENT => array('fulfillment_id'),
            self::OBJECT_REMOVED_FROM_FULFILLMENT => array('fulfillment_id'),
            self::TODAY_MATCHES_OBJECT_DATE => array('date_field'),
            self::TODAY_RELATIVE_TO_OBJECT_DATE => array('numberOf', 'units', 'option', 'date_field'),
            self::OBJECT_ADDED_OR_REMOVED_FROM_CAMPAIGN => array('option', 'campaign_id'),
            self::TASK_COMPLETED => array('task_id'),
            self::OBJECT_SUBMITS_FORM => array('form_id', 'outcome'),
            // External Events
            self::OBJECT_OPENS_EMAIL => array('email_id'),
            self::OBJECT_CLICKS_EMAIL_LINK => array('email_id', 'link_num'),
            self::OBJECT_SENDS_YOU_EMAIL => array(),
            self::SMS_RECEIVED => array('number_id'),
            // Sales
            self::OBJECT_PURCHASES_PRODUCT => array('product_id'),
            self::OBJECT_RECEIVES_REFUND => array('product_id'),
            self::OBJECT_VISITS_LANDINGPAGE => array('landingPage_id'),
            self::OBJECT_VISITS_PURL => array('PURL_id'),
            self::OPEN_ORDER_CREATED => array('product_id'),
            self::OPEN_ORDER_CHARGED_UPDATED => array('order_id', 'option'),
            self::CARD_CHARGED_DECLINED => array('option'),
            // Sites/Pages
            self::OBJECT_VISITS_URL => array('url'),
            self::TRACKED_LINKED_CLICKED => array('trackedLink_id'),
            self::ACCESS_TO_WPMEMBERSHIPLVL_GIVEN => array('wpMembership_id'),
            self::LOSES_ACCESS_TO_WPMEMBERSHIPLVL => array('wpMembership_id')
        );
        return $requiredParams[$rule];
    }

    public static function CheckRestricted($rule)
    {
        $restrictedRules = array(
            // events
            self::OBJECT_PURCHASES_PRODUCT,
            self::OBJECT_RECEIVES_REFUND,
            self::OBJECT_VISITS_LANDINGPAGE,
            self::OBJECT_VISITS_PURL,
            self::OPEN_ORDER_CREATED,
            self::OPEN_ORDER_CHARGED_UPDATED,
            self::CARD_CHARGED_DECLINED,
            // Sites/Pages
            self::OBJECT_VISITS_URL,
            self::TRACKED_LINKED_CLICKED,
            self::ACCESS_TO_WPMEMBERSHIPLVL_GIVEN,
            self::LOSES_ACCESS_TO_WPMEMBERSHIPLVL
        );
        if (in_array($rule, $restrictedRules))
        {
            return true;
        }
        return false;
    }


}
