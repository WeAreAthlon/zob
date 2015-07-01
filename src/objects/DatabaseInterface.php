<?php

namespace Zob\Objects;

interface DatabaseInterface
{
    public function getName();

    public function getTable($table);

    public function addTable(TableInterface $table);

    public function removeTable($table);

    public function getTables();
}
