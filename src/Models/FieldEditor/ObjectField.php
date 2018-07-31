<?php

namespace OntraportAPI\Models\FieldEditor;

use OntraportAPI\Exceptions\FieldTypeException;
use OntraportAPI\Models\Request;

class ObjectField implements Request
{
    const TYPE_CHECK = "check";
    const TYPE_COUNTRY = "country";
    const TYPE_FULLDATE = "fulldate";
    const TYPE_LIST = "list";
    const TYPE_LONGTEXT = "longtext";
    const TYPE_NUMERIC = "numeric";
    const TYPE_PRICE = "price";
    const TYPE_PHONE = "phone";
    const TYPE_STATE = "state";
    const TYPE_DROP = "drop";
    const TYPE_TEXT = "text";
    const TYPE_EMAIL = "email";
    const TYPE_SMS = "sms";
    const TYPE_ADDRESS = "address";

    private $_id;
    private $_alias;
    private $_field;
    private $_type;
    private $_required;
    private $_unique;
    private $_options;

    /**
     * ObjectField constructor.
     *
     * @param $alias
     * @param $type
     *
     * @param array $options
     * @param int $required
     * @param int $unique
     *
     * @throws FieldTypeException
     */
    public function __construct($alias, $type, $options = array(), $required = 0, $unique = 0)
    {
        $this->_alias = $alias;
        $this->_required = $required;
        $this->_unique = $unique;
        if (is_array($options))
        {
            $this->_options = $options;
        }
        if ($this->isTypeDefined($type))
        {
            $this->_type = $type;
        }
    }

    /**
     * @param array $data A pre-decoded array of field data
     *
     * @return ObjectField A new field with the data from the response
     * @throws FieldTypeException
     */
    public static function CreateFromResponse(array $data)
    {
        if (array_key_exists("data", $data) && is_array($data["data"]))
        {
            $data = $data["data"];
        }

        $object_field = new self($data["alias"], $data["type"], $data["options"], $data["required"], $data["unique"]);
        if ($data["field"])
        {
            $object_field->setField($data["field"]);
        }
        if ($data["id"])
        {
            $object_field->setId($data["id"]);
        }
        return $object_field;
    }

    /**
     * @brief Converts the current Model to an array for use as request parameters.
     *
     * @return mixed[] Array of parameters to submit with an API request.
     */
    public function toRequestParams()
    {
        $ret = array(
            "alias" => $this->_alias,
            "required" => $this->_required,
            "unique" => $this->_unique,
            "type" => $this->_type,
        );
        if (count($this->_options))
        {
            $ret["options"] = $this->_options;
        }
        if ($this->_id > 0)
        {
            $ret["id"] = $this->_id;
        }
        if ($this->_field !== null)
        {
            $ret["field"] = $this->_field;
        }
        return $ret;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param int
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return string
     */
    public function getField()
    {
        return $this->_field;
    }

    /**
     * @param string $field
     */
    public function setField($field)
    {
        $this->_field = $field;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->_alias;
    }

    /**
     * @param string $alias
     */
    public function setAlias($alias)
    {
        $this->_alias = $alias;
    }

    /**
     * @brief Converts Text fields to Long text. Providing more storage.
     *
     * @throws FieldTypeException If the existing field isn't a Text type.
     */
    public function expandTextType()
    {
        if ($this->_type !== self::TYPE_TEXT)
        {
            throw new FieldTypeException("Cannot change {$this->_type} to " . self::TYPE_LONGTEXT . ".");
        }
        $this->_type = self::TYPE_LONGTEXT;
    }

    /**
     * @param string[] List of named values to add to the dropdown or list options
     */
    public function addDropOptions(array $values)
    {
        $this->_options = array("add" => $values);
    }

    /**
     * @param string[] List of named values to add to the dropdown or list options
     */
    public function removeDropOptions(array $values)
    {
        $this->_options = array("remove" => $values);
    }

    /**
     * @param string[] List of named values to add to the dropdown or list options
     */
    public function replaceDropOptions(array $values)
    {
        $this->_options = array("replace" => $values);
    }

    /**
     * @param $type
     *
     * @return bool
     * @throws FieldTypeException
     */
    private function isTypeDefined($type)
    {
        $type = strtoupper($type);
        if (!defined("self::TYPE_$type"))
        {
            throw new FieldTypeException($type);
        }
        return true;
    }

    public function __toString()
    {
        return json_encode($this->toRequestParams());
    }
}
