<?php

namespace OntraportAPI\Models\Rules;

use OntraportAPI\Exceptions;
use OntraportAPI\Models\Request as Request;
use OntraportAPI\Models\Rules\Events;
use OntraportAPI\Models\Rules\Conditions;
use OntraportAPI\Models\Rules\Actions;

class RuleBuilder implements Request
{
    private $_object_type_id;
    private $_name;
    private $_id;
    private $_events;
    private $_conditions;
    private $_actions;

    /**
     * @var array Required parameters for rule declaration
     */
    protected static $_requiredParams = array(
        "events",
        "actions",
        "object_type_id"
    );

    /**
     * Units Parameters
     */
    const DAYS = "0";
    const WEEKS = "1";
    const MONTHS = "2";

    /**
     * Option Parameters
     */
    const BEFORE_FIELD = "0";
    const AFTER_FIELD = "1";
    const CHARGED_AND_SUCCESSFUL = "0";
    const CANCELED = "1";
    const COMPLETED = "2";
    const CHARGED_BUT_DECLINED = "3";
    const CHARGED = "0";
    const DECLINED = "1";
    const RESUMED = "0";
    const PAUSE = "1";
    const UNPAUSE = "0";
    const ADD = "0";
    const REMOVE = "1";
    const SUCCESSFUL = "0";
    const FAILURE = "1";

    /**
     * Conditional Parameters
     */
    const EQUAL_TO = "Equal To";
    const NOT_EQUAL_TO = "Not Equal To";
    const GREATER_THAN = "Greater Than";
    const LESS_THAN = "Less Than";
    const GREATER_OR_EQUAL_TO = "Greater Than or Equal To";
    const LESS_OR_EQUAL_TO = "Less Than or Equal To";
    const CONTAINS = "Contains";
    const DOES_NOT_CONTAIN = "Does Not Contain";
    const STARTS_WITH = "Starts With";
    const ENDS_WITH = "Ends With";
    const ON = "1";
    const BEFORE = "2";
    const AFTER = "3";

    /**
     * Relative Date Fields
     */
    const TODAY = "TODAY";
    const YESTERDAY = "YESTERDAY";
    const LAST_SUNDAY = "LSUNDAY";
    const LAST_TWO_SUNDAYS = "L2SUNDAY";
    const FIRST_DAY_THIS_MONTH = "FDTMONTH";
    const FIRST_DAY_LAST_MONTH = "FDLMONTH";
    const THIS_DAY_LAST_MONTH = "TDLMONTH";
    const FIRST_DAY_THIS_YEAR = "FDTYEAR";
    const THIS_DAY_LAST_YEAR = "TDLYEAR";
    const SEVEN_DAYS_AGO = "S7DAYS";
    const THIRTY_DAYS_AGO = "S30DAYS";
    const NINETY_DAYS_AGO = "S90DAYS";
    const HUNDRED_TWENTY_DAYS_AGO = "S120DAYS";
    const HUNDRED_EIGHTY_DAYS_AGO = "S180DAYS";
    const TOMORROW = "TOMORROW";
    const FIRST_DAY_NEXT_MONTH = "FDNMONTH";
    const THIS_DAY_NEXT_MONTH = "TDNMONTH";
    const FIRST_DAY_NEXT_YEAR = "FDNYEAR";
    const THIS_DAY_NEXT_YEAR = "TDNYEAR";
    const SEVEN_DAYS_FROM_NOW = "7DFNOW";
    const FOURTEEN_DAYS_FROM_NOW = "14DFNOW";
    const THIRTY_DAYS_FROM_NOW = "30DFNOW";
    const SIXTY_DAYS_FROM_NOW = "60DFNOW";
    const NINETY_DAYS_FROM_NOW = "90DFNOW";
    const HUNDRED_TWENTY_DAYS_FROM_NOW = "120DFNOW";
    const HUNDRED_EIGHTY_DAYS_FROM_NOW = "180DFNOW";

    /**
     * Card Type Parameters
     */
    const VISA = 1;
    const MASTERCARD = 2;
    const AMERICAN_EXPRESS = 3;
    const DISCOVER = 4;
    const PAYPAL = 5;
    const OTHER = 6;

    /**
     * Month Parameters
     */
    const JAN = 1;
    const FEB = 2;
    const MARCH = 3;
    const APRIL = 4;
    const MAY = 5;
    const JUNE = 6;
    const JULY = 7;
    const AUG = 8;
    const SEPT = 9;
    const OCT = 10;
    const NOV = 11;
    const DEC = 12;

    /**
     * @brief Creates a new rule builder to construct the rule's data
     *
     * @param string $name The name of the new rule
     * @param int $object_type_id The id of the object the rule applies to
     * @param int $id the id of an existing rule, if updating a rule's data
     */
    public function __construct($name, $object_type_id, $id = NULL)
    {
        $this->_name = $name;
        $this->_object_type_id = $object_type_id;
        $this->_id = $id;
    }

    /**
     * @brief Adds "event" with correct format and parameters to current object.
     *
     * @param string $event Event type
     * @param array $eventParams Event parameters
     *
     * @return array Array of currently stored events in object
     */
    public function addEvent($event, $eventParams)
    {
        // check if valid rule usage for object_type_id
        $this->validateRule("Events", $event);
        // get required parameters for specific rule
        $requiredParams = Events::GetRequiredParams($event);
        // checking for missing and invalid types for each parameter
        $check_params = $this->_checkParams($requiredParams, $eventParams);
        // if no missing or invalid rule parameters
        if ($check_params)
        {
            $value = $this->_formatParams($eventParams);
            $rule = $event . "(" . $value . ")";
            $this->_events[] = $rule;

            return $this->_events;
        }
        return false;
    }

    /**
     * @brief Adds "condition" with correct format and parameters to current object.
     *
     * @param string $condition Condition type
     * @param array $conditionParams Condition parameters
     * @param string $operator "AND" or "OR" relationship between last condition in
     *                           current conditions and given $condition
     *
     * @throws Exceptions\RequiredParamsException No operators are passed and builder has an existing condition
     * @throws Exceptions\OntraportAPIException Invalid operator value is passed in
     *
     * @return boolean False if unsuccessful
     * @return array Array with currently stored conditions in object
     */
    public function addCondition($condition, $conditionParams, $operator = NULL)
    {
        // check if valid rule usage for object_type_id
        $this->validateRule("Conditions", $condition);
        // get required parameters for specific rule
        $requiredParams = Conditions::GetRequiredParams($condition);
        // checking for missing and invalid parameters
        $check_params = $this->_checkParams($requiredParams, $conditionParams);

        // determine operator
        if (empty($this->_conditions))
        {
            $operator = NULL;
        }
        else if (!empty($this->_conditions))
        {
            if ($operator == "AND")
            {
                $operator = ";";
            }
            else if ($operator == "OR")
            {
                $operator = "|";
            }
            else if ($operator == NULL)
            {
                throw new Exceptions\RequiredParamsException(array("operator"));
            }
            else
            {
                throw new Exceptions\OntraportAPIException("Invalid operator. Must be AND or OR.");
            }
        }
        $rule = $operator . $condition;

        // if no missing or invalid rule parameters
        if ($check_params)
        {
            $value = $this->_formatParams($conditionParams);
            $formatted = "(" . $value . ")";
            $this->_conditions[] = $rule . $formatted;

            return $this->_conditions;
        }
        return false;
    }

    /**
     * @brief Adds "action" with correct format and parameters to current object.
     *
     * @param string $action Action type
     * @param array $actionParams Action parameters
     *
     * @return boolean False if unsuccessful
     * @return array Array of currently stored actions in object
     */
    public function addAction($action, $actionParams)
    {
        // check if valid rule usage for object_type_id
        $this->validateRule("Actions", $action);
        // get required parameters for specific rule
        $requiredParams = Actions::GetRequiredParams($action);
        // checking for missing and invalid parameters
        $exception = false;
        if ($action == Actions::PING_URL)
        {
            $exception = true;
        }
        $check_params = $this->_checkParams($requiredParams, $actionParams, $exception);
        // if no missing or invalid rule parameters
        if ($check_params)
        {
            $rule = $action;
            // special formatting for ping_url
            if ($action == Actions::PING_URL)
            {
                $formatted = $this->_formatParams($actionParams, "::");
                $this->_actions[] = $action . "(" . $formatted . ")";

                return $this->_actions;
            }
            // general formatting for actions
            $value = $this->_formatParams($actionParams);
            $formatted = "(" . $value . ")";
            $this->_actions[] = $rule . $formatted;

            return $this->_actions;
        }
        return false;
    }

    /**
     * @brief Clears all events.
     *
     * @return array
     */
    public function clearEvents()
    {
        $this->_events = array();
        return $this->_events;
    }

    /**
     * @brief Clears all conditions.
     *
     * @return array
     */
    public function clearConditions()
    {
        $this->_conditions = array();
        return $this->_conditions;
    }

    /**
     * @brief Clears all actions.
     *
     * @return array
     */
    public function clearActions()
    {
        $this->_actions = array();
        return $this->_actions;
    }

    /**
     * @brief Removes one event.
     *
     * @param string Name of event
     *
     * @return array Array of events after removal
     */
    public function removeEventByName($event_name)
    {
        foreach($this->_events as $key => $event)
        {
            if(strpos($event, $event_name) !== false)
            {
                array_splice($this->_events, $key, 1);
            }
        }
        return $this->_events;
    }

    /**
     * @brief Removes one condition.
     *
     * @param string Name of condition
     *
     * @return array Array of conditions after removal
     */
     public function removeConditionByName($condition_name)
     {
         foreach($this->_conditions as $key => $condition)
         {
             if(strpos($condition, $condition_name) !== false)
             {
                 array_splice($this->_conditions, $key, 1);
             }
         }
         return $this->_conditions;
     }

    /**
     * @brief Removes one action.
     *
     * @param string Name of action
     *
     * @return array Array of actions after removal
     */
    public function removeActionByName($action_name)
    {
        foreach($this->_actions as $key => $action)
        {
            if(strpos($action, $action_name) !== false)
            {
                array_splice($this->_actions, $key, 1);
            }
        }
        return $this->_actions;
    }

    /**
     * @brief Converts a response to a Request Object.
     * @param array $data A pre-decoded array of response data
     *
     * @return \OntraportAPI\Models\Request A new object with the data from the response
     */
    public static function CreateFromResponse(array $data)
    {
        $name = $data["name"];
        $object_type_id = $data["object_type_id"];
        $id = $data["id"];

        $builder = new RuleBuilder($name, $object_type_id, $id);

        $events = self::_splitRule($data["events"]);
        $conditions = array();
        if ($data["conditions"] != null)
        {
            $conditions = self::_splitRule($data["conditions"]);
        }
        $actions = self::_splitRule($data["actions"]);

        foreach($events as $event)
        {
            // separate rule and params
            $parsed = self::_parseParams($event);
            $builder->addEvent($parsed["name"], $parsed["params"]);
        }
        foreach($actions as $action)
        {
            // separate rule and params
            $parsed = self::_parseParams($action);
            $builder->addAction($parsed["name"], $parsed["params"]);
        }
        if (!empty($conditions))
        {
            foreach($conditions as $condition)
            {
                // determine operators
                $operators = self::_operatorClassifier($data["conditions"]);
                $or_rule = $operators["or_rules"];
                $and_rule = $operators["and_rules"];
                $first_rule = $operators["first_rule"];
                // separate rule and param
                $parsed = self::_parseParams($condition);

                if (in_array($condition, $first_rule))
                {
                    $builder->addCondition($parsed["name"], $parsed["params"]);
                }
                else if (in_array($condition, $or_rule))
                {
                    $builder->addCondition($parsed["name"], $parsed["params"], "OR");
                }
                else if (in_array($condition, $and_rule))
                {
                    $builder->addCondition($parsed["name"], $parsed["params"], "AND");
                }
            }
        }
        return $builder;
    }

    /**
     * @brief Converts current object to an array for use as request parameters.
     *
     * @throws Exceptions\OntraportAPIException if no event or action is added
     * @return array $requestParams Array of parameters for valid rule API request.
     */
    public function toRequestParams()
    {
        if (empty($this->_events) || empty($this->_actions))
        {
            throw new Exceptions\OntraportAPIException("Events and Actions must be added to create rule.");
        }

        $events = implode(";", $this->_events);
        $actions = implode(";", $this->_actions);

        if (!empty($this->_conditions))
        {
            $conditions = implode($this->_conditions);
            $conditions = trim($conditions, ";");
            $conditions = trim($conditions, "|");
        }
        else if (empty($this->_conditions))
        {
            $conditions = "";
        }
        $requestParams = array(
            "object_type_id" => $this->_object_type_id,
            "name" => $this->_name,
            "events" =>  $events,
            "conditions" => $conditions,
            "actions" => $actions
        );
        if (!empty($this->_id))
        {
            $requestParams["id"] = $this->_id;
        }
        return $requestParams;
    }

    /**
     * @brief Validates rule and checks if used for correct object type.
     *
     * @param string $rule
     *
     * @throws Exceptions\OntraportAPIException for invalid rule types and object type ids
     * @return boolean True
     */
    public function validateRule($type, $rule)
    {
        $requiredParams = call_user_func(__NAMESPACE__ . "\\" . $type . "::GetRequiredParams", $rule);
        // check if rule is valid
        if ($requiredParams == null && !is_array($requiredParams))
        {
            throw new Exceptions\OntraportAPIException($rule . " is not a valid rule type.");
        }
        // validate rule is used for correct object
        if (call_user_func(__NAMESPACE__ . "\\" . $type . "::CheckRestricted", $rule) && ($this->_object_type_id != 0))
        {
            throw new Exceptions\OntraportAPIException($rule . " can only be used with Contacts object.");
        }
        return true;
    }

    /**
     * @brief Checks if required parameters are missing and if parameters are valid
     *
     * @param array $requiredParams Array retrieved from the class specific to each rule
     * @param array $requestParams Array of user input rule parameters
     *
     * @throws Exceptions\OntraportAPIException for invalid number of parameters
     * @return boolean True if all required parameters are given and valid
     */
    private function _checkParams($requiredParams, $requestParams, $exception = false)
    {
        // exceptions for parameter length for ping url
        if ($exception == true)
        {
            if (count($requestParams) == 0)
            {
                throw new Exceptions\OntraportAPIException("Invalid number of parameters for rule. " .
                "Refer to the API Doc to make sure you have the correct inputs.");
                return false;
            }
        }
        else if (count($requiredParams) != count($requestParams))
        {
            throw new Exceptions\OntraportAPIException("Invalid number of parameters for rule. " .
            "Refer to the API Doc to make sure you have the correct inputs.");
            return false;
        }
        $invalid_params = array();
        $units = array(self::DAYS, self::WEEKS, self::MONTHS);
        $conditional = array(
            self::EQUAL_TO,
            self::NOT_EQUAL_TO,
            self::GREATER_THAN,
            self::LESS_THAN,
            self::GREATER_OR_EQUAL_TO,
            self::LESS_OR_EQUAL_TO,
            self::CONTAINS,
            self::DOES_NOT_CONTAIN,
            self::STARTS_WITH,
            self::ENDS_WITH,
            self::ON,
            self::BEFORE,
            self::AFTER
        );

        $i = 0;
        foreach($requiredParams as $param)
        {
            $value = $requestParams[$i];
            if(($param == "conditional") && !in_array($value, $conditional))
            {
                $invalid_params[] = $param;
            }
            if(($param == "units") && !in_array($value, $units))
            {
                $invalid_params[] = $param;
            }
            if(($param == "option") && (!is_numeric($value) || (($value < 0) || ($value > 3))))
            {
                $invalid_params[] = $param;
            }
            if(($param == "outcome") && (!is_numeric($value) || (($value < 0) || ($value > 1))))
            {
                $invalid_params[] = $param;
            }
            $i++;
        }
        if (!empty($invalid_params))
        {
            $invalid_params = implode(", ", $invalid_params);
            throw new Exceptions\OntraportAPIException("Invalid inputs for $invalid_params. " .
            "Refer to the API Doc to make sure your rule parameters are valid and in the correct order.");
        }
        return true;
    }

    /**
     * @brief Properly formats parameters for rule creation.
     *
     * @param string $requiredParams Required parameters for specific rule
     * @param array $requestParams Rule parameters
     * @param bool $delimiter Set to default "," but set to "::" for ping_url
     *
     * @return string Formatted rule parameters
     */
    private function _formatParams($requestParams, $delimiter = ",")
    {
        $formatted = "";
        foreach($requestParams as $param)
        {
            $formatted = $formatted . $param . $delimiter;
        }
        $formatted = rtrim($formatted, $delimiter);
        return $formatted;
    }

    /**
     * @brief Parses valid rule into rule type and rule parameters.
     *
     * @param string $rule Valid formatted rule
     * @return array $parsed Array with "name" => rule type, "params" => rule parameters
     */
    private function _parseParams($rule)
    {
        $parsed = array();

        $split = explode("(", $rule);
        $name = $split[0];
        $str_params = rtrim($split[1], ")");
        // if empty string
        if ($str_params == '')
        {
            $parsed_params = array();
        }
        else
        {
            $parsed_params = explode(",", $str_params);
        }
        $parsed["params"] = $parsed_params;
        $parsed["name"] = $name;

        return $parsed;
    }

    /**
     * @brief Parses rules.
     *
     * @param string $init_rule
     * @return array $rules
     */
    private function _splitRule($init_rule)
    {
        $rules = array();
        $init_rule = str_replace("|", ";", $init_rule);
        $rules = explode(";", $init_rule);
        foreach($rules as $key => $rule)
        {
            $rules[$key] = trim($rule);
        }
        return $rules;
    }

    /**
     * @brief classifies conditions into "and" or "or" conditions
     *
     * @param string $init_conditions Formatted conditions
     *
     * @return array Conditions classified
     */
    private function _operatorClassifier($init_conditions)
    {
        $or_rules = array();
        $and_rules = array();
        $first_rule = array();
        $strlen = strlen($init_conditions);
        $counter = $strlen - 1;

        for($i = $strlen - 1; $i >= 0; $i--)
        {
            $char = substr($init_conditions, $i, 1);
            if ($char == "|" || $char == ";"|| $i == 0)
            {
                $rule = substr($init_conditions, $i + 1, $counter - $i);
                $rule = trim($rule);
                if ($char == "|")
                {
                    array_unshift($or_rules, $rule);
                }
                else if ($char == ";")
                {
                    array_unshift($and_rules, $rule);
                }
                else if ($i == 0)
                {
                    $rule = substr($init_conditions, 0,  $counter + 1);
                    $first_rule[] = $rule;
                }
                $counter = $i - 1;
            }
        }
        $operators = array(
            "or_rules" => $or_rules,
            "and_rules" => $and_rules,
            "first_rule" => $first_rule
        );
        return $operators;
    }
}
