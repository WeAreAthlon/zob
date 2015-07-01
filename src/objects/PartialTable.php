<?php

namespace Zob\Objects;

class PartialTable implements TableInterface
{
    private $name;

    private $fields = [];

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getField($fieldName)
    {
        if (isset($this->fields[$fieldName])) {
            return $this->fields[$fieldName];
        }

        return false;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function addField(FieldInterface $field)
    {
        $this->fields[$field->getName()] = $field;
    }
}

