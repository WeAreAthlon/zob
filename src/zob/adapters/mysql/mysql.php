<?php
/**
 * PDO_MYSQL adapter.
 *
 * @package    Zob
 * @subpackage Adapters\MySql
 * @author     Kalin Stefanov <kalin@athlonsofia.com>
 * @copyright  Copyright (c) 2015, Zob
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace Zob\Adapters\MySql;

/**
 * Database management driver wrapping PDO_MYSQL extension.
 */
class MySql extends \PDO
{
    private $builder;
    private $transactionRunnig = false;
    private $rollbackTransaction = false;
    private $db;

    public function __construct(array $dsn)
    {
        $connString = "mysql:host={$dsn['host']};";

        if($dsn['name']) {
            $this->db = $dsn['name'];
            $connString .= "dbname={$dsn['name']}";
        }

        parent::__construct(
            $connString,
            $dsn['user'],
            $dsn['password']
        );

        $this->builder = new Builder();
    }

    public function __get($var)
    {
        if($var === 'builder') {
            return $this->builder;
        }
    }

    public function createDatabase($databaseName, $charSet, $collation)
    {
        $sql = $this->builder->creteDatabase($databaseName, $charSet, $collation);

        $this->execute($sql);
    }

    public function deleteDatabase($databaseName)
    {
        $sql = $this->builder->deleteDatabase($databaseName);

        $this->execute($sql);
    }

    public function getTable($table)
    {
        $sql = $this->builder->getTable($table);

        $this->query($sql);
    }

    public function createTable($table)
    {
        $sql = $this->builder->createTable($table);

        $this->execute($sql);
    }

    public function deleteTable($tableName)
    {
        $sql = $this->builder->deleteTable($tableName);

        $this->execute($sql);
    }

    public function createField($tableName, $field)
    {
        $sql = $this->builder->createField($tableName, $field);

        $this->execute($sql);
    }

    public function changeField($tableName, $fieldName, $field)
    {
        $sql = $this->builder->changeField($tableName, $fieldName, $field);

        $this->execute($sql);
    }

    public function deleteField($tableName, $fieldName)
    {
        $sql = $this->builder->deleteField($tableName, $fieldName);

        $this->execute($sql);
    }

    public function createIndex($tableName, $index)
    {
        $sql = $this->builder->createIndex($tableName, $index);

        $this->execute($sql);
    }

    public function deleteIndex($tableName, $indexName)
    {
        $sql = $this->builder->deleteIndex($tableName, $indexName);

        $this->execute($sql);
    }

    public function run($query)
    {
        list($sql, $params) = $this->builder->buildQuery($query);

        return $this->query($sql, $params);
    }

    public function query($sql, $params = [])
    {
        if (count($params) > 0) {
            $stmt = $this->prepare($sql);
            $stmt->execute($params);
        } else {
            $stmt = parent::query($sql);
        }

        if (!$stmt->columnCount()) {
            if ($stmt->rowCount()) {
                return true;
            }

            return false;
        }

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function execute($sql, $params = [])
    {
        if (count($params) > 0) {
            $stmt = $this->prepare($sql);
            $result = $stmt->execute($params);
        } else {
            $result = $this->exec($sql);
        }

        var_dump($sql);
        var_dump($result);

        return $result;
    }

    public function transaction(callable $scope)
    {
        $initialState = $this->transactionRunning;
        if(!$this->transactionRunning) {
            $this->transactionRunning = true;
            parent::beginTransaction();
        }

        $scope();

        if(!$initialState) {
            $this->transactionRunning = false;

            if(!$this->rollbackTransaction) {
                return parent::commit();
            }

            $this->rollbackTransaction = false;
            return parent::rollback();
        }

        return true;
    }

    public function rollback()
    {
        $this->rollbackTransaction = true;

        return true;
    }
}

