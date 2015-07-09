<?php

namespace Zob\Adapters\MySql\Factories;

use Zob\Adapters\MySql\Statements;
use Zob\Objects\TableInterface;

class InsertFactory
{
    public function create(TableInterface $table, array $values)
    {
        return new Statements\Insert($table, $values);
    }
}

