<?php

namespace Zob\Adapters\MySql\Statements;

use Zob\Adapters\MySql\Statements;

class SelectFactory
{
    public function create(TableInterface $table)
    {
        return new Statements\Select($table);
    }
}

