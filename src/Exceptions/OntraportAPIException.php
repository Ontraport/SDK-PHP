<?php

namespace OntraportAPI\Exceptions;

/**
 * Class OntraportAPIException
 *
 * @brief Base class of custom exceptions for the ONTRAPORT API wrapper.
 *
 * @package OntraportAPI
 */
class OntraportAPIException extends \Exception
{
    public function __construct($message, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

/**
 * Class ArrayOperatorException
 *
 * @brief Thrown when using addCondition() to build a condition object comparing a value to an array with a relational
 *        operator other than IN.
 *
 * @package OntraportAPI
 */
class ArrayOperatorException extends OntraportAPIException
{
    public function __construct()
    {
        parent::__construct("Operator must be \"IN\" if condition object value is an array", 400);
    }
}

/**
 * Class ConditionOperatorException
 *
 * @brief Thrown when using addCondition() to build a condition object with a relational operator other than =, <>, >, <,
 *        >=, <=, IN, NOT IN, IS, or LIKE
 *
 * @package OntraportAPI
 */
class ConditionOperatorException extends OntraportAPIException
{
    public function __construct($operator)
    {
        parent::__construct($operator . " is not a supported logical operator for a condition object.", 400);
    }
}

/**
 * Class CustomObjectException
 *
 * @brief Thrown when attempting to create an instance of custom object API with an invalid object type ID.
 *
 * @package OntraportAPI
 */
class CustomObjectException extends OntraportAPIException
{
    public function __construct()
    {
        parent::__construct("Invalid object type ID passed to custom object API.", 400);
    }
}

/**
 * Class HttpMethodException
 *
 * @brief Thrown when using an HTTP method other than GET, POST, PUT, or DELETE
 *
 * @package OntraportAPI
 */
class HttpMethodException extends OntraportAPIException
{
    public function __construct($method)
    {
        parent::__construct($method . " is not a supported HTTP method.", 400);
    }
}

/**
 * Class RequiredParamsException
 *
 * @brief Thrown when attempting to make an HTTP request that is missing parameters required for that request.
 *
 * @package OntraportAPI
 */
class RequiredParamsException extends OntraportAPIException
{
    public function __construct($params)
    {
        parent::__construct("Invalid input: missing required parameter(s): $params", 400);
    }
}

/**
 * Class TypeException
 *
 * @brief Thrown when attempting to make an HTTP request passing a data type other than an array.
 *
 * @package OntraportAPI
 */
class TypeException extends OntraportAPIException
{
    public function __construct($type)
    {
        parent::__construct("Invalid input: expected array, received $type", 400);
    }
}