<?php
    
namespace Zob\Objects;

interface JoinInterface
{
    public function getType();

    public function getConditions();

    public function addCondition($condition);
}

