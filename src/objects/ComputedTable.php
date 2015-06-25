<?php

namespace Zob\Objects;

class ComputedTable
{
    private $tables = [];

    private $joins;

    public function __construct(array $tables, array $joins = [])
    {
        foreach ($tables as $table) {
            $this->tables[$table->getName()] = $table;
        }

        $this->joins = $joins;
    }

    public function getName()
    {
        if(empty($this->joins)) {
            return implode(', ', array_keys($this->tables));
        }

        return $this->tables[0]->getName();
    }

    public function getFields()
    {
        $r = [];
        foreach ($this->tables as $table) {
            $r = array_merge($r, $table->getFields());
        }

        return $r;
    }

    public function getTable($tableName)
    {
        return $this->tables[$tableName];
    }

    public function addTable(TableInterface $table)
    {
        $this->tables[$table->getName()] = $table;
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

