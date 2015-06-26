<?php

namespace Zob;

class Query implements QueryInterface
{
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    public function select($table)
    {
        $this->statement = $this->selectFactory->create($table);

        return $this->statement;
    }
}

