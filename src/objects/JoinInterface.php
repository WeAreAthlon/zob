<?php
    
namespace Zob\Objects;

interface JoinInterface
{
    public function getType();

    public function getTable();

    public function getForeignTable();

    public function getConditions();

    public function addCondition($condition);
}

