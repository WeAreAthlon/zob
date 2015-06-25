<?php

namespace Zob;

class Query implements QueryInterface
{
    public function __construct(ConnectionService $connectionService)
    {
        $this->connection = $connectionService->getConnection();
        $this->factories = $this->connection->getFactories();
    }

    public function select($table)
    {
        $this->statement = $this->factories->selectFactory->create($table);

        return $this->statement;
    }
}

