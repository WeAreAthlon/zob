<?php

namespace Zob\Objects;

class PartialTable implements TableInterface
{
    private $name;

    private $fields = [];

    private $join;

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
        return array_values($this->fields);
    }

    public function addField(FieldInterface $field)
    {
        $this->fields[$field->getName()] = $field;
    }

    public function join(TableInterface $table, $conditions, $type)
    {
        switch($type) {
            case 'left': $join = new LeftJoin($conditions); break;
        }

        return new ComputedTable($this, $table, $join);
    }

    public function getJoin()
    {
        return null;
    }
}

