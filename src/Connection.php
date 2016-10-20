<?php

namespace Zob;

/**
 * Class Connection
 * @author stefanov.kalin@gmail.com
 */
class Connection implements ConnectionInterface
{
    private $connection;

    private $shemaName;

    private $dsn;

    private $fetchMode = \PDO::FETCH_ASSOC;

    private $charset = 'utf8mb4';

    /**
     * @param mixed $dsn
     */
    public function __construct(array $dsn)
    {
        $this->shemaName = $dsn['database'];
        $this->dsn = $dsn;
    }

    /**
     * Initiate a PDO connection
     *
     * @return void
     */
    private function setUpConnection()
    {
        $dsn = "mysql:dbname={$this->shemaName};host={$this->dsn['host']},charset={$this->charset}";

        try {
            $this->connection = new \PDO($dsn, $this->dsn['user'], $this->dsn['password']);
            $this->connection->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, $this->fetchMode);
            $this->connection->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
        } catch (\PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    /**
     * Returns the schema name
     *
     * @return string
     */
    public function getShemaName()
    {
        return $this->schemaName;
    }

    /**
     * Executes an SQL statement and returns the result
     *
     * @return mixed
     */
    public function query(string $sql, array $params = [])
    {
        if (!$this->connection) {
            $this->setUpConnection();
        }

        return null;
    }

    /**
     * Executes an SQL statement
     *
     * @return void
     */
    public function execute(string $sql, array $params = []) : bool
    {
        if (!$this->connection) {
            $this->setUpConnection();
        }
        return null;
    }

    /**
     * Preapres and execute a SQL statement
     *
     * @return void
     */
    public function prepare(string $sql, ...$params) : array
    {
        if (!$this->connection) {
            $this->setUpConnection();
        }

        $statement = $this->connection->prepare($sql);

        foreach ($params as $key => $value) {
            $statement->bindValue($key, $value, $this->getParamType($value));
        }

        $statement->execute();

        return $statement->fetchAll();
    }

    /**
     * Return the coresponding PDO param type
     *
     * @return integer
     */
    private function getParamType($param)
    {
        if (is_int($param)) {
            return \PDO::PARAM_INT;
        }

        if (is_bool($param)) {
            return \PDO::PARAM_BOOL;
        }

        if (is_null($param)) {
            return \PDO::PARAM_NULL;
        }

        return \PDO::PARAM_STR;
    }
}
