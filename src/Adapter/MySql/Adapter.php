<?php

namespace Zob\Adapter\MySql;

use Zob\ConnectionInterface;
use Zob\Adapter\AdapterInterface;
use Zob\Query\QueryInterface;

/**
 * Class Adapter
 * @author stefanov.kalin@gmail.com
 */
class Adapter implements AdapterInterface
{
    private $schema = [];
    private $builder;

    /**
     * @param ConnectionInterface $connection
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
        $this->queryBuilder = new Builder();
    }

    /**
     * Set builder instance
     *
     * @return void
     */
    public function setBuilder($builder)
    {
        $this->builder = $builder;
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
     * Returns database schema
     *
     * @return array
     */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * Runs a Query
     *
     * @return array
     */
    public function run(QueryInterface $query) : array
    {
        $statement = $this->builder->build($query);

        return $this->connection->prepare(
            $statement,
            ...$query->getParams()
        );
    }

    /**
     * Run everything inside the closure in a transaction
     *
     * @return void
     */
    public function transaction(callable $closure)
    {
        return null;
    }

    /**
     * Returns DB Schema
     *
     * @return void
     */
    public function retrieveSchema()
    {
        $result = [];
        $schemaName = $this->connection->getSchemaName();
        $schema = $this->connection->prepare(
            $this->builder->getSchema(),
            $schemaName
        );

        foreach ($schema as $tableName) {
            $tableSchema = $this->connection->prepare(
                $this->builder->getTableSchema(),
                $tableName,
                $schemaName
            );

            $result[$tableName] = $this->normalizeField($tableSchema);
        }

        return $result;
    }

    /**
     * Normalize db field infromation
     *
     * @return array
     */
    private function normalizeField(array $schema) : array
    {
        return [
            'name'              => $schema['COLUMN_NAME'],
            'type'              => $schema['DATA_TYPE'],
            'length'            => $schema['CHARACTER_MAXIMUM_LENGTH'],
            'notNull'           => ($schema['IS_NULLABLE'] === 'NO') ? true : false,
            'isPrimary'         => ($schema['COLUMN_KEY'] === 'PRI') ? true : false,
            'isUnique'          => ($schema['COLUMN_KEY'] === 'UNI') ? true : false,
            'isAutoIncrement'   => ($schema['EXTRA'] === 'auto_increment') ? true : false,
        ];
    }
}
