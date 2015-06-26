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
        }
        $this->indexes = $indexes;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getField($fieldName)
    {
        return $this->fields[$fieldName];
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function getIndex($indexName)
    {
        return $this->indexes[$indexName];
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

    public function join(TableInterface $table, $conditions, $type)
    {
        $this->joins[] = [
            'table'      => $table->getName(),
            'type'       => $type,
            'conditions' => $conditions
        ];
    }

    public function getJoins()
    {
        return $this->joins;
    }
}

