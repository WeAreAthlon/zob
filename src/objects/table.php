<?php

namespace Zob\Objects;

class Table implements TableInterface
{
    private $name;

    private $fields = [];

    private $indexes = [];

    private $joins = [];

    public function __construct($name, $fields = [], $indexes = [])
    {
        $this->name = $name;
        foreach ($fields as $field) {
            $this->fields[$field->getName()] = $field;
            $field->setTable($this);
        }

        foreach ($indexes as $index) {
            $this->indexes[$index->getName()] = $index;
        }
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

    public function getIndex($indexName)
    {
        if (isset($this->indexes[$indexName])) {
            return $this->indexes[$indexName];
        }

        return false;
    }

    public function getIndexes()
    {
        return $this->indexes;
    }

    public function getPrimaryKey()
    {
        foreach ($this->fields as $field) {
            if ($field->isPrimaryKey()) {
                return $field;
            }
        }

        return null;
    }

    public function addField(FieldInterface $field)
    {
        $this->fields[$field->getName()] = $field;
        $field->setTable($this);
    }

    public function removeField($name)
    {
        if (isset($this->fields[$name])) {
            unset($this->fields[$name]);

            return true;
        }

        return false;
    }

    public function addIndex(IndexInterface $index)
    {
        $this->indexes[$index->getName()] = $index;
    }

    public function removeIndex($name)
    {
        if (isset($this->indexes[$name])) {
            unset($this->indexes[$name]);

            return true;
        }

        return false;
    }

    public function getPartial(array $fields)
    {
        $partialTable = new PartialTable($this->name);

        foreach ($fields as $key=>$fieldName) {
            if($this->getField($fieldName)) {
                $field = clone $this->getField($fieldName);

                if (is_string($key)) {
                    $field->setAlias($key);
                }

                $partialTable->addField($field);
            }
        }

        return $partialTable;
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

