<?php
/**
 * Base connection
 *
 * @package    Zob
 * @subpackage Adapters
 * @author     Kalin Stefanov <kalin@athlonsofia.com>
 * @copyright  Copyright (c) 2015, Zob
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 */
namespace Zob\Adapters;

use Zob\Objects;
use Zob\QueryInterface;

class Connection implements ConnectionInterface
{
    /**
     * Database connection instance
     *
     * @var object
     * @access protected
     */
    protected $conn;

    /**
     * SqlHelper instance
     *
     * @var object
     * @access private
     */
    private $sqlHelper;

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

    public function __construct()
    {
        $this->sqlHelper = new SqlHelper();
    }

    public function run(QueryInterface $query)
    {
        $query->prepare();

        return $this->query($query->sql, $query->params);      
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
        $stmt = $this->conn->prepare($sql);

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
        $stmt = $this->conn->prepare($sql);

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
     * Checks if a database is created/exists
     *
     * @param string $name Database name
     *
     * @access public
     *
     * @return bool
     */
    public function databaseExists($database)
    {
        return $this->conn->query($this->sqlHelper->databaseExists($database))->rowCount() > 0;
    }

    /**
     * Creates a database
     *
     * @param DatabaseInterface $database Database object
     *
     * @access public
     *
     * @return bool
     */
    public function createDatabase(Objects\DatabaseInterface $database)
    {
        return $this->execute($this->sqlHelper->createDatabase($database));
    }

    /**
     * Delete a database by name
     *
     * @param string $database Name of the database to be deleted
     *
     * @access public
     *
     * @return bool
     */
    public function deleteDatabase($database)
    {
        if($database instanceof Objects\DatabaseInterface) {
            $database = $database->getName();
        }

        return $this->execute($this->sqlHelper->deleteDatabase($database));
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
    public function tableExists($table)
    {
        if($table instanceof Objects\TableInterface) {
            $table = $table->getName();
        }

        return $this->conn->query($this->sqlHelper->tableExists($table))->rowCount() > 0;
    }

    /**
     * Retrieve data definition information for a table
     *
     * @param string $table Name of the table
     *
     * @access public
     *
     * @return TableInterface
     */
    public function getTable($table)
    {
        $fields = $this->query($this->sqlHelper->getTable($table));
        $indexes = $this->query($this->sqlHelper->getIndexes($table));

        $fields = array_map(function($item) {
            preg_match('/(\w+)\((\d+)\)/', $item['Type'], $type);
            $params = [
                'name'      => $item['Field'],
                'required'  => $item['Null'] == 'NO',
                'default'   => $item['Default'],
                'pk'        => !!($item['Key'] == 'PRI'),
                'ai'        => !!($item['Extra'] == 'auto_increment')
            ];
            
            if(empty($type)) {
                $params['type'] = $item['Type'];
            } else {
                $params['type']   = $type[1];
                $params['length'] = $type[2];
            }

            if($params['required'] && $params['pk'] && $params['ai']) {
                $params['required'] = false;
            }

            return new Objects\Field($params);
        }, $fields);

        $indexes = array_map(function($item) {
            return new Objects\Index([
                'name'      => $item['Key_name'],
                'field'     => $item['Column_name'],
                'type'      => $item['Index_type'],
                'unique'    => !$item['Non_unique'],
                'length'    => $item['Sub_part']
            ]);
        }, $indexes);

        return new Objects\Table($table, $fields, $indexes);
    }

    /**
     * Creates a table
     *
     * @param TableInterface $table A table object
     *
     * @access public
     *
     * @return bool
     */
    public function createTable(Objects\TableInterface $table)
    {
        return $this->execute($this->sqlHelper->createTable($table));
    }

    /**
     * Delete a table
     *
     * @param string $table Name of the table
     *
     * @access public
     *
     * @return bool
     */
    public function deleteTable($table)
    {
        if($table instanceof Objects\TableInterface) {
            $table = $table->getName();
        }

        return $this->execute($this->sqlHelper->deleteTable($table));
    }

    /**
     * Create a field
     *
     * @param FieldInterface $field A field object
     * @param string $table Name of the table
     *
     * @access public
     *
     * @return bool
     */
    public function createField(Objects\FieldInterface $field, $table)
    {
        return $this->execute($this->sqlHelper->createField($field, $table));
    }

    /**
     * Change field definition
     *
     * @param string $table Name of the table
     * @param string $fieldName Name of the field to be changed
     * @param FieldInterface $field The new field definition
     *
     * @access public
     *
     * @return bool
     */
    public function changeField($table, $fieldName, Objects\FieldInterface $field)
    {
        return $this->execute($this->sqlHelper->changeField($table, $fieldName, $field));
    }

    /**
     * Delete a field
     *
     * @param string $field Name of the field
     * @param string $table Name of the table
     *
     * @access public
     *
     * @return bool
     */
    public function deleteField($field, $table)
    {
        return $this->execute($this->sqlHelper->deleteField($field, $table));
    }

    /**
     * Create an index
     *
     * @param IndexInterface $index A index object
     * @param string $table Name of the table
     *
     * @access public
     *
     * @return bool
     */
    public function createIndex(Objects\IndexInterface $index, $table)
    {
        return $this->execute($this->sqlHelper->createIndex($index, $table));
    }

    /**
     * Delete an index
     *
     * @param string $index Name of the index
     * @param string $table Name of the table
     *
     * @access public
     *
     * @return bool
     */
    public function deleteIndex($index, $table)
    {
        return $this->execute($this->sqlHelper->deleteIndex($index, $table));
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
            $this->conn->beginTransaction();
        }

        try {
            $scope();
        } catch(\Exception $e) {
            $this->rollbackTransaction = false;
            $this->transactionRunning = false;
            return $this->conn->rollback();
        }

        if(!$initialState) {
            $this->transactionRunning = false;

            if(!$this->rollbackTransaction) {
                return $this->conn->commit();
            }

            $this->rollbackTransaction = false;
            return $this->conn->rollback();
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

