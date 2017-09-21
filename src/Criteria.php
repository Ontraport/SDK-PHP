<?php

namespace OntraportAPI;

/**
 * Class Criteria
 *
 * @author ONTRAPORT
 *
 * @package OntraportAPI
 */
class Criteria
{
    /**
     * @var array of condition data
     */
    protected $_condition = array();

    public function __construct($field, $relational_operator, $value)
    {
        $this->_condition[] = $this->buildCondition($field, $relational_operator, $value);
    }

    /**
     * @brief Add another condition that must be met in addition to existing condition(s)
     *
     * @param string $field The field subject to the condition.
     * @param string $relational_operator The comparison operator. May be "=","<>",">","<",">=","<=","IN","NOT IN", or "IS".
     * @param string|int|null $value The value to compare the field against.
     */
    public function andCondition($field, $relational_operator, $value)
    {
        if ($this->_condition)
        {
            $this->_condition[] = "AND";
        }
        $this->_condition[] = $this->buildCondition($field, $relational_operator, $value);
    }

    /**
     * @brief Add another condition that may be met in addition to existing condition(s)
     *
     * @param string $field The field subject to the condition.
     * @param string $relational_operator The comparison operator. May be "=","<>",">","<",">=","<=","IN","NOT IN", or "IS".
     * @param string|int|null $value The value to compare the field against.
     */
    public function orCondition($field, $relational_operator, $value)
    {
        if ($this->_condition)
        {
            $this->_condition[] = "OR";
        }
        $this->_condition[] = $this->buildCondition($field, $relational_operator, $value);
    }

    /**
     * @brief Return JSON-encoded criteria object from conditions array
     *
     * @return string JSON
     */
    public function fromArray()
    {
        return json_encode($this->_condition);
    }

    /**
     * @brief Assembles a single condition
     *
     * @param string $field
     * @param string $relational_operator
     * @param string|int|null $value
     *
     * @return array
     */
    private function buildCondition($field, $relational_operator, $value)
    {
        $this->_validateCondition($relational_operator, $value);

        $condition["field"] = array("field" => $field);
        $condition["op"] = $relational_operator;

        if (is_array($value))
        {
            $list = array();
            foreach ($value as $item)
            {
                $list[] = array("value" => $item);
            }
            $condition["value"] = array("list" => $list);
        }
        else
        {
            $condition["value"] = array("value" => $value);
        }

        return $condition;
    }

    /**
     * @brief Validate condition data
     *
     * @param string $relational_operator
     * @param string|int|null $value
     *
     * @throws Exceptions\ArrayOperatorException
     * @throws Exceptions\ConditionOperatorException
     */
    private function _validateCondition($relational_operator, $value)
    {
        $relational_operators = array("=","<>",">","<",">=","<=","IN","NOT IN","IS","LIKE");

        if (!in_array($relational_operator, $relational_operators))
        {
            throw new Exceptions\ConditionOperatorException($relational_operator);
        }

        if (is_array($value))
        {
            if ($relational_operator !== "IN")
            {
                throw new Exceptions\ArrayOperatorException();
            }
        }
    }
}