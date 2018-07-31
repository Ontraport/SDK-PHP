<?php

namespace OntraportAPI\Models\Rules;
/**
* Class Conditions
*
* @author ONTRAPORT
*
* @package OntraportAPI
*/

class Conditions
{
    /**
    * Conditions
    */

    // Customer Relationship Management (CRM)
    const SUBSCRIBED_TO_CAMPAIGN_BEFORE_AFTER_DATE = "campaignbuilder_subscription_date_is_val";
    const BEEN_ON_CAMPAIGN_FOR_TIMEFRAME = "Been_on_campaign_for_timeframe";
    const OBJECT_PAUSED_RESUMED_ON_CAMPAIGN = "paused_or_active_on_camp";
    const BEEN_ON_SEQUENCE_FOR_TIMEFRAME = "been_on_campaignbuilder_for_timeframe";
    const SUBSCRIBED_TO_SEQUENCE_BEFORE_AFTER_DATE = "Date_of_subscription_to_drip_is_datecondition_datevalue";
    const OBJECT_SUBSCRIBED_SEQUENCE = "Is_subscribed_to_drip";
    const OBJECT_NOT_SUBSCRIBED_SEQUENCE = "Is_not_subscribed_to_drip";
    const SEQUENCE_SUBSCRIPTION_PAUSED = "Subscription_to_dripa_is_paused";
    const SEQUENCE_SUBSCRIPTION_RESUMED = "Subscription_to_dripa_is_not_paused";
    const FIELD_HAS_VALUE = "field_is_condition_fieldvalue";
    const OBJECT_HAS_TAG = "Is_in_category";
    const OBJECT_NO_TAG = "Is_not_in_category";
    const OBJECT_SUBSCRIBED_TO_FULFILLMENT = "Is_on_fulfillment";
    const OBJECT_NOT_SUBSCRIBED_TO_FULFILLMENT = "Is_not_on_fulfillment";
    // Messages
    const OPENED_EMAIL_N_TIMES = "Has_opened_email_condition_n_times";
    const CLICKED_EMAIL_LINK_N_TIMES = "Has_clicked_emailanchor_condition_n_times";
    const SMS_CONTAINS_EMAIL = "sms_contains_email";
    const SMS_CONTAINS_NO_EMAIL = "sms_does_not_contain_email";
    const SMS_CONTAINS_TEXT = "sms_contains_text";
    const SMS_CONTAINS_NO_TEXT = "sms_does_not_contain_text";
    // Sales
    const SPENT_N_AMOUNT_ON_PRODUCT = "Has_spent_condition_N_on_product";
    const ORDERED_N_AMOUNT_OF_PRODUCT = "Has_purchased_condition_n_product";
    const SUBSCRIBED_TO_PRODUCT = "Is_subscribed_to_productsub";
    const SUBSCRIBED_TO_PRODUCT_FOR_TIMEFRAME = "Has_been_subscribed_to_productsub_for_timeframe";
    // Sites/Pages
    const VISITED_WP_PAGE_N_TIMES = "Has_visited_website_condition_n_times";
    const VISITED_LANDING_PAGE_N_TIMES = "Has_visited_landingpage_splittest_condition_n_times";
    const HAS_ACCESS_TO_WPMEMBERSHIPLVL = "Contact_has_access_to_wpintmembershiplevel";
    const NO_ACCESS_TO_WPMEMBERSHIPLVL = "Contact_does_not_have_access_to_wpintmembershiplevel";

    public static function GetRequiredParams($rule)
    {
        $requiredParams = array(
            // Conditions
            self::SUBSCRIBED_TO_CAMPAIGN_BEFORE_AFTER_DATE => array('campaign_id', 'conditional', 'date'),
            self::BEEN_ON_CAMPAIGN_FOR_TIMEFRAME => array('campaign_id', 'number_units'),
            self::OBJECT_PAUSED_RESUMED_ON_CAMPAIGN => array('option', 'campaign_id'),
            self::BEEN_ON_SEQUENCE_FOR_TIMEFRAME => array('sequence_id', 'numberOf', 'units'),
            self::SUBSCRIBED_TO_SEQUENCE_BEFORE_AFTER_DATE => array('sequence_id', 'conditional', 'date'),
            self::OBJECT_SUBSCRIBED_SEQUENCE => array('sequence_id'),
            self::OBJECT_NOT_SUBSCRIBED_SEQUENCE => array('sequence_id'),
            self::SEQUENCE_SUBSCRIPTION_PAUSED => array('sequence_id'),
            self::SEQUENCE_SUBSCRIPTION_RESUMED => array('sequence_id'),
            self::FIELD_HAS_VALUE => array('field_id', 'conditional', 'value'),
            self::OBJECT_HAS_TAG => array('tag_id'),
            self::OBJECT_NO_TAG => array('tag_id'),
            self::OBJECT_SUBSCRIBED_TO_FULFILLMENT => array('fulfillment_id'),
            self::OBJECT_NOT_SUBSCRIBED_TO_FULFILLMENT => array('fulfillment_id'),
            // Messages
            self::OPENED_EMAIL_N_TIMES => array('email_id', 'conditional', 'number'),
            self::CLICKED_EMAIL_LINK_N_TIMES => array('email_id_link_num', 'conditional', 'number'),
            self::SMS_CONTAINS_EMAIL => array(),
            self::SMS_CONTAINS_NO_EMAIL => array(),
            self::SMS_CONTAINS_TEXT => array('text'),
            self::SMS_CONTAINS_NO_TEXT => array('text'),
            // Sales
            self::SPENT_N_AMOUNT_ON_PRODUCT => array('conditional', 'number', 'product_id'),
            self::ORDERED_N_AMOUNT_OF_PRODUCT => array('conditional', 'number', 'product_id'),
            self::SUBSCRIBED_TO_PRODUCT => array('product_id'),
            self::SUBSCRIBED_TO_PRODUCT_FOR_TIMEFRAME => array('product_id', 'number_units'),
            // Sites/Pages
            self::VISITED_WP_PAGE_N_TIMES => array('wordpress_id', 'conditional', 'number'),
            self::VISITED_LANDING_PAGE_N_TIMES => array('landingPage_id', 'object_type_id', 'conditional', 'number'),
            self::HAS_ACCESS_TO_WPMEMBERSHIPLVL => array('wpMembership_id'),
            self::NO_ACCESS_TO_WPMEMBERSHIPLVL => array('wpMembership_id')
        );
        return $requiredParams[$rule];

    }

    public static function CheckRestricted($rule)
    {
        $restrictedRules = array(
            // conditions
            self::SPENT_N_AMOUNT_ON_PRODUCT,
            self::ORDERED_N_AMOUNT_OF_PRODUCT,
            self::SUBSCRIBED_TO_PRODUCT,
            self::SUBSCRIBED_TO_PRODUCT_FOR_TIMEFRAME,
            // Sites/Pages
            self::VISITED_WP_PAGE_N_TIMES,
            self::VISITED_LANDING_PAGE_N_TIMES,
            self::HAS_ACCESS_TO_WPMEMBERSHIPLVL,
            self::NO_ACCESS_TO_WPMEMBERSHIPLVL
        );
        if (in_array($rule, $restrictedRules))
        {
            return true;
        }
        return false;
    }

}
