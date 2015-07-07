<?php

namespace Zob\Objects;

class ComputedTable implements TableInterface
{
    private $table;

    private $foreignTable;

    private $join;

    public function __construct(TableInterface $table, TableInterface $foreignTable, $join)
    {
        $this->table = $table;
        $this->foreignTable = $foreignTable;
        $this->join = $join;
    }

    public function getName()
    {
        return $this->table->getName();
    }

    public function getTable()
    {
        return $this->table;
    }

    public function getForeignTable()
    {
        return $this->foreignTable;
    }

    public function getFields()
    {
        return array_merge($this->table->getFields(), $this->foreignTable->getFields());
    }

    public function getField($field)
    {
        return false;
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
        return $this->join;
    }
}

