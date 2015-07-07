<?php

namespace Zob\Objects;

class Join implements JoinInterface
{
    protected $type;

    private $conditions;

    public function __construct(array $conditions = [])
    {
        $this->conditions = $conditions;
    }

    public function getType()
    {
        return $this->type;
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

