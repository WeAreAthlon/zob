<?php

namespace Zob\Objects;

interface TableInterface
{
    public function getName();

    public function getField($fieldName);

    public function getFields();

    public function join(TableInterface $table, array $conditions, $type);

    public function getJoin();
}

