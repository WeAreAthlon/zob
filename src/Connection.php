<?php

namespace Zob;

/**
 * Class Connection
 * @author kalin.stefanov@gmail.com
 */
class Connection implements ConnectionInterface
{
    private $connection;

    private $dsn;

    /**
     * @param mixed $dsn
     */
    public function __construct(array $dsn)
    {
        $this->dsn = $dsn;
    }

    /**
     * Initiate a PDO connection
     *
     * @return void
     */
    private function setUpConnection()
    {
        $dbName = $this->dsn['database'];
        $dsn = "mysql:dbname={$dbName};host={$this->dsn['host']}";

        try {
            $this->connection = new \PDO($dsn, $this->dsn['user'], $this->dsn['password']);
        } catch (\PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    /**
     * Executes an SQL statement and returns the result
     *
     * @return mixed
     */
    public function query($param)
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
    public function execute($param)
    {
        if (!$this->connection) {
            $this->setUpConnection();
        }
        return null;
    }
}
