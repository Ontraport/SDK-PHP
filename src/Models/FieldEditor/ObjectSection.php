<?php

namespace OntraportAPI\Models\FieldEditor;

use OntraportAPI\Exceptions\FieldEditorException;
use OntraportAPI\Exceptions\InvalidColumnIndex;
use OntraportAPI\Exceptions\OntraportAPIException;
use OntraportAPI\Models\Request;

class ObjectSection implements Request
{

    /**
     * @var string
     */
    private $_name;
    /**
     * @var string
     */
    private $_description;
    /**
     * @var ObjectField[][]
     */
    private $_fields;

    /**
     * ObjectSection constructor.
     *
     * @param string $name
     * @param ObjectField[][] $fields
     */
    public function __construct($name, array $fields = array())
    {
        $this->_name = $name;
        $first = reset($fields);
        if ($first instanceof ObjectField)
        {
            // Case where they may have only sent a single column rather than an array of columns
            $this->_fields[] = $fields;
            return;
        }
        $this->_fields = $fields;
    }

    /**
     * @brief Adds a description
     *
     * @param string $description The description you want to add.
     */
    public function setDescription($description)
    {
        $this->_description = $description;
    }

    /**
     * @param mixed[] $data A pre-decoded array of section data
     *
     * @return ObjectSection A new section with the data from the response
     */
    public static function CreateFromResponse(array $data)
    {
        if (array_key_exists("data", $data) && is_array($data["data"]))
        {
            $data = $data["data"];
        }
        $object_section = new self($data["name"]);
        $object_section->setDescription($data["description"]);

        if (!is_array($data["fields"]))
        {
            return $object_section;
        }
        foreach ($data["fields"] as $column => $fields)
        {
            if (!is_array($fields))
            {
                continue;
            }
            foreach ($fields as $field)
            {
                try
                {
                    $object_section->putFieldsInColumn($column, array(ObjectField::CreateFromResponse($field)));
                }
                catch (OntraportAPIException $e)
                {
                    // ignore the field if the data is malformed.
                }
            }
        }
        return $object_section;
    }

    /**
     * @brief Converts the current Model to an array for use as request parameters.
     *
     * @return mixed[] Array of parameters to submit with an API request.
     */
    public function toRequestParams()
    {
        $ret = array(
            "name" => $this->_name,
            "description" => $this->_description
        );

        foreach ($this->_fields as $column => $fields)
        {
            foreach ($fields as $field)
            {
                if ($field instanceof ObjectField)
                {
                    $ret["fields"][$column][] = $field->toRequestParams();
                }
            }
        }
        // Convert from an assoc array to a numeric array.
        // This will move items from the 3rd column to the 2nd if the 2nd isn't populated.
        $ret["fields"] = array_values($ret["fields"]);
        return $ret;
    }

    /**
     * @brief Search the current Section for a field by it's alias
     *
     * @param string $alias The alias to search for.
     *
     * @return null|ObjectField The field if found, or null.
     */
    public function getFieldByAlias($alias)
    {
        foreach ($this->_fields as $column => $fields)
        {
            foreach ($fields as $field)
            {
                if ($field->getAlias() === $alias)
                {
                    return $field;
                }
            }
        }
        return null;
    }

    /**
     * @param int $column The column index
     * @param ObjectField[] $fields
     *
     * @throws InvalidColumnIndex
     */
    public function putFieldsInColumn($column, array $fields = array())
    {
        if ($column < 0 || $column > 2)
        {
            throw new InvalidColumnIndex($column);
        }
        if (!array_key_exists($column, $this->_fields))
        {
            $this->_fields[$column] = array();
        }
        $this->_fields[$column] = array_merge($this->_fields[$column], $fields);
    }

    /**
     * Given an ObjectField, find a matching one in this section to update
     *
     * @param ObjectField $field
     *
     * @throws FieldEditorException
     */
    public function updateField($field)
    {
        if (!$field instanceof ObjectField)
        {
            throw new FieldEditorException(print_r($field, true) . " is not of type ObjectField");
        }
        foreach ($this->_fields as $column => $fields)
        {
            foreach ($fields as $index => $f)
            {
                $existing_field = $f->getField();
                if ($existing_field !== null && $field->getField() === $existing_field)
                {
                    // If the field name (f1234) exists merge replace on that.
                    $this->_fields[$column][$index] = $field;
                    return;
                }
            }
        }
        throw new FieldEditorException("Could not find an existing field: {$field->getField()} in this Section");
    }

    public function __toString()
    {
        return json_encode($this->toRequestParams());
    }
}
