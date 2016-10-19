<?php

namespace Zob\Adapter\MySql;

use Zob\ConnectionInterface;
use Zob\Adapter\AdapterInterface;
use Zob\Query\QueryInterface;

/**
 * Class Adapter
 * @author kalin.stefanov@gmail.com
 */
class Adapter implements AdapterInterface
{
    private $schema;

    /**
     * @param mixed ConnectionInterface $connection
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
        $this->builder = new Builder();
    }

    /**
     * Returns the DB connection
     *
     * @return ConnectionInterface
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Returns DB Schema
     *
     * @return void
     */
    public function getSchema()
    {
        $tables = $this->connection->query($this->builder->getSchema());
    }

    /**
     * Runs a Query
     *
     * @return void
     */
    public function run(QueryInterface $query)
    {
        $statement = $this->builder->build($query);

        $result = $this->connection->query($statement, $query->getParams());

        return null;
    }

    /**
     * Run everything inside the closure in a transaction
     *
     * @return void
     */
    public function transaction(callabe $closure)
    {
        return null;
    }
}
