<?php

namespace Zob\Adapters\MySql\Factories;

use Zob\Adapters\MySql\Statements;
use Zob\Objects\TableInterface;

class UpdateFactory
{
    public function create(TableInterface $table, array $values)
    {
        return new Statements\Update($table, $values);
    }
}

