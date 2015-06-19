<?php

namespace Zob\Objects;

class Join implements JoinInterface
{
    protected $type;

    private $table;

    private $foreignTable;

    private $conditions;

    public function __construct(TableInterface $table, TableInterface $foreignTable, array $conditions = [])
    {
        $this->table = $table;
        $this->foreignTable = $foreignTable;
        $this->conditions = $conditions;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getTable()
    {
        return $this->table;
    }

    public function getForeignTable()
    {
        return $this->foreignTable;
    }

    public function getConditions()
    {
        return $this->conditions;
    }

    public function addCondition($condition)
    {
        $this->conditions[] = $condition;
    }
}

