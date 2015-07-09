<?php

namespace Zob;

use Zob\Objects\TableInterface;
use Zob\Adapters\ConnectionInterface;

class Query implements QueryInterface
{
    public $sql;

    public $params = [];

    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    public function select(TableInterface $table)
    {
        $this->statement = $this->connection->selectFactory->create($table);

        return $this->statement;
    }

    public function insert(TableInterface $table, array $values = [])
    {
        $this->statement = $this->connection->insertFactory->create($table, $values);

        return $this->statement;
    }

    public function update(TableInterface $table, array $values = [])
    {
        $this->statement = $this->connection->updateFactory->create($table, $values);

        return $this->statement;
    }

    public function delete(TableInterface $table)
    {
        $this->statement = $this->connection->deleteFactory->create($table);

        return $this->statement;
    }

    public function prepare()
    {
        list($sql, $params) = $this->statement->toSql();

        $this->sql = $sql;
        $this->params = $params;
    }

    public function run()
    {
        return $this->connection->run($this);
    }
}

