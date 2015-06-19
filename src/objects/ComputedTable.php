<?php

namespace Zob\Objects;

class ComputedTable implements TableInterface
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
        return array_keys($this->tables);
    }

    public function getTable($tableName)
    {
        return $this->tables[$tableName]; 
    }

    public function addTable(TableInterface $table)
    {
        $this->tables[$table->getName()] = $table;
    }

    public function addJoin(JoinInterface $join)
    {
        $this->joins[] = $join;
    }

    public function getFields()
    {
        
    }

    public function getJoins()
    {
        return $this->joins;
    }
}

