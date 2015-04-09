<?php
/**
 * Base adapter.
 *
 * @package    Zob
 * @subpackage Adapters
 * @author     Kalin Stefanov <kalin@athlonsofia.com>
 * @copyright  Copyright (c) 2015, Zob
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace Zob\Adapters;

use Zob\Adapters\iAdapter;

/**
 * Database management driver wrapping PDO extension.
 */
abstract class Adapter extends \PDO implements iAdapter
{
    /**
     * Instance to a SQL buider
     *
     * @var Builder
     * @access public
     */
    public $builder;

    /**
     * Flag for tracking if there is a running transaction
     *
     * @var bool
     * @access private
     */
    private $transactionRunning = false;

    /**
     * Flag for triggering a transaction rollback
     *
     * @var bool
     * @access private
     */
    private $rollbackTransaction = false;

    /**
     * Checks if a database is created/exists
     *
     * @param string $name Database name
     *
     * @access public
     *
     * @return bool
     */
    public function databaseExists($name)
    {
        $sql = $this->builder->databaseExists($name);

        return parent::query($sql)->rowCount() > 0;
    }

    /**
     * Creates a database
     *
     * @param string $databaseName Name of the database
     * @param string $charSet Default DB characterset
     * @param string $collation Default DB collation
     *
     * @access public
     *
     * @return bool
     */
    public function createDatabase($databaseName, $charSet, $collation)
    {
        $sql = $this->builder->createDatabase($databaseName, $charSet, $collation);

        return $this->execute($sql);
    }

    /**
     * Delete a database by name
     *
     * @param string $databaseName Name of the database to be deleted
     *
     * @access public
     *
     * @return bool
     */
    public function deleteDatabase($databaseName)
    {
        $sql = $this->builder->deleteDatabase($databaseName);

        return $this->execute($sql);
    }

    /**
     * Checks if a table exists
     *
     * @param string $name Name of the table
     *
     * @access public
     *
     * @return bool
     */
    public function tableExists($name)
    {
        $sql = $this->builder->tableExists($name);

        return parent::query($sql)->rowCount() > 0;
    }

    /**
     * Retrieve data definition information for a table
     *
     * @param string $table Name of the table
     *
     * @access public
     *
     * @return array
     */
    public function getTable($table)
    {
        $fields = $this->query($this->builder->getTable($table));
        $indexes = $this->query($this->builder->getIndexes($table));

        return [
            'fields' => array_map(function($item) {
                preg_match('/(\w+)\((\d+)\)/', $item['Type'], $type);
                return [
                    'name'      => $item['Field'],
                    'type'      => $type[1],
                    'length'    => $type[2],
                    'required'  => $item['Null'] == 'NO',
                    'default'   => $item['Default'],
                    'pk'        => !!($item['Key'] == 'PRI'),
                    'ai'        => !!($item['Extra'] == 'auto_increment')
                ];
            }, $fields),
            'indexes' => array_map(function($item) {
                return [
                    'name'      => $item['Key_name'],
                    'field'     => $item['Column_name'],
                    'type'      => $item['Index_type'],
                    'unique'    => !$item['Non_unique'],
                    'length'    => $item['Sub_part']
                ];
            }, $indexes)
        ];
    }

    /**
     * Creates a table
     *
     * @param Zob\Objects\Table $table A table object
     *
     * @access public
     *
     * @return bool
     */
    public function createTable($table)
    {
        $sql = $this->builder->createTable($table);

        return $this->execute($sql);
    }

    /**
     * Delete a table
     *
     * @param string $tableName Name of the table
     *
     * @access public
     *
     * @return bool
     */
    public function deleteTable($tableName)
    {
        $sql = $this->builder->deleteTable($tableName);

        return $this->execute($sql);
    }

    /**
     * Create a field
     *
     * @param string $tableName Name of the table
     * @param Zob\Objects\Field $field A field object
     *
     * @access public
     *
     * @return bool
     */
    public function createField($tableName, $field)
    {
        $sql = $this->builder->createField($tableName, $field);

        return $this->execute($sql);
    }

    /**
     * Change field definition
     *
     * @param string $tableName Name of the table
     * @param string $fieldName Name of the field to be changed
     * @param Zob\Objects\Field $field The new field definition
     *
     * @access public
     *
     * @return bool
     */
    public function changeField($tableName, $fieldName, $field)
    {
        $sql = $this->builder->changeField($tableName, $fieldName, $field);

        return $this->execute($sql);
    }

    /**
     * Delete a field
     *
     * @param string $tableName Name of the table
     * @param string $fieldName Name of the field
     *
     * @access public
     *
     * @return bool
     */
    public function deleteField($tableName, $fieldName)
    {
        $sql = $this->builder->deleteField($tableName, $fieldName);

        return $this->execute($sql);
    }

    /**
     * Create an index
     *
     * @param string $tableName Name of the table
     * @param Zob\Objects\Index $index A index object
     *
     * @access public
     *
     * @return bool
     */
    public function createIndex($tableName, $index)
    {
        $sql = $this->builder->createIndex($tableName, $index);

        return $this->execute($sql);
    }

    /**
     * Delete an index
     *
     * @param string $tableName Name of the table
     * @param string $indexName Name of the index
     *
     * @access public
     *
     * @return bool
     */
    public function deleteIndex($tableName, $indexName)
    {
        $sql = $this->builder->deleteIndex($tableName, $indexName);

        return $this->execute($sql);
    }

    /**
     * Builds and executes a query
     *
     * @param array $options Query definition
     *
     * @access public
     *
     * @return array/bool
     */
    public function run($options)
    {
        list($sql, $params) = $this->builder->buildQuery($options);

        return $this->query($sql, $params);
    }

    /**
     * Executes a SQL statement and returns a bool status or data array
     * Throws an LogicException if there was na error
     *
     * @param string $sql SQL statement
     * @param array $params List of params to bind
     *
     * @access public
     *
     * @return array/bool
     */
    public function query($sql, $params = [])
    {
        $stmt = $this->prepare($sql);

        if(!empty($params)) {
            foreach($params as $i=>$v) {
                if($v === '') {
                    $stmt->bindValue($i+1, null, \PDO::PARAM_NULL);
                    continue;
                }

                $stmt->bindValue($i+1, $v, (is_int($v) ? \PDO::PARAM_INT : \PDO::PARAM_STR));
            }
        }

        if($stmt->execute()) {
            if($stmt->columnCount()) {
                return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            }

            return true;
        }

        throw new \LogicException($stmt->errorInfo()[2]);
    }

    /**
     * Executes a SQL statement and returns a bool status
     * Throws an LogicException if there was na error
     *
     * @param string $sql SQL statement
     * @param array $params List of params to bind
     *
     * @access public
     *
     * @return bool
     */
    public function execute($sql, $params = [])
    {
        $stmt = $this->prepare($sql);

        if(!empty($params)) {
            foreach($params as $i=>$v) {
                $stmt->bindValue($i+1, $v, (is_int($v) ? \PDO::PARAM_INT : \PDO::PARAM_STR));
            }
        }

        $result = $stmt->execute();

        if(!$result) {
            throw new \LogicException($stmt->errorInfo()[2]);
        }

        return $result;
    }

    /**
     * Start a transaction
     * All DML queries inside the $scope are in transaction.
     * A DML statements can't be in transaction due to DB Engine limits.
     * Nested transactions are supported
     * If an error occur inside the $scope, the transaction is rollbacked.
     *
     * @param callable $scope A callable function
     *
     * @access public
     *
     * @return true
     */
    public function transaction(callable $scope)
    {
        $initialState = $this->transactionRunning;
        if(!$this->transactionRunning) {
            $this->transactionRunning = true;
            parent::beginTransaction();
        }

        try {
            $scope();
        } catch(\Exception $e) {
            $this->rollbackTransaction = false;
            $this->transactionRunning = false;
            return parent::rollback();
        }

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

    /**
     * Rollback the existing transaction
     *
     * @access public
     *
     * @return true
     */
    public function rollback()
    {
        $this->rollbackTransaction = true;

        return true;
    }
}

