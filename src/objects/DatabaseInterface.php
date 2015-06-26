<?php

namespace Zob\Objects;

interface Database
{
    public function getName();

    public function getTable($table);

    public function addTable(TableInterface $table);

    public function removeTable($table);

    public function getTables();
}
