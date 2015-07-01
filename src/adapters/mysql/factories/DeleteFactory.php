<?php

namespace Zob\Adapters\MySql\Factories;

use Zob\Adapters\MySql\Statements;
use Zob\Objects\TableInterface;

class DeleteFactory
{
    public function create(TableInterface $table)
    {
        return new Statements\Delete($table);
    }
}

