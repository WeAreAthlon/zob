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

use Zob\Adapters\iAdapter;

/**
 * Database management driver wrapping PDO_MYSQL extension.
 */
class MySql extends \PDO implements iAdapter
{
    private $builder;
    private $transactionRunnig = false;
    private $rollbackTransaction = false;
    private $db;

    public function __construct(array $dsn)
    {
        $connString = "mysql:host={$dsn['host']};";

        if(isset($dsn['name'])) {
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

    public function databaseExists($name)
    {
        return parent::query("SHOW DATABASES LIKE '{$name}'")->rowCount() > 0;
    }

    public function createDatabase($databaseName, $charSet, $collation)
    {
        $sql = $this->builder->createDatabase($databaseName, $charSet, $collation);

        $this->execute($sql);
    }

    public function deleteDatabase($databaseName)
    {
        $sql = $this->builder->deleteDatabase($databaseName);

        $this->execute($sql);
    }

    public function tableExists($name)
    {
        return parent::query("SHOW TABLES LIKE '{$name}'")->rowCount() > 0;
    }

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

    public function createTable($table)
    {
        $sql = $this->builder->createTable($table);

        return $this->execute($sql);
    }

    public function deleteTable($tableName)
    {
        $sql = $this->builder->deleteTable($tableName);

        return $this->execute($sql);
    }

    public function createField($tableName, $field)
    {
        $sql = $this->builder->createField($tableName, $field);

        return $this->execute($sql);
    }

    public function changeField($tableName, $fieldName, $field)
    {
        $sql = $this->builder->changeField($tableName, $fieldName, $field);

        return $this->execute($sql);
    }

    public function deleteField($tableName, $fieldName)
    {
        $sql = $this->builder->deleteField($tableName, $fieldName);

        return $this->execute($sql);
    }

    public function createIndex($tableName, $index)
    {
        $sql = $this->builder->createIndex($tableName, $index);

        return $this->execute($sql);
    }

    public function deleteIndex($tableName, $indexName)
    {
        $sql = $this->builder->deleteIndex($tableName, $indexName);

        return $this->execute($sql);
    }

    public function run($options)
    {
        list($sql, $params) = $this->builder->buildQuery($options);

        return $this->query($sql, $params);
    }

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

        throw new \DomainException($stmt->errorInfo()[2]);
    }

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
            throw new \DomainException($stmt->errorInfo()[2]);
        }

        return $result;
    }

    public function transaction(callable $scope)
    {
        $initialState = $this->transactionRunning;
        if(!$this->transactionRunning) {
            $this->transactionRunning = true;
            parent::beginTransaction();
        }

        try {
            $scope();
        } catch(\PDOException $e) {
            $this->rollbackTransaction = false;
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

    public function rollback()
    {
        $this->rollbackTransaction = true;

        return true;
    }
}

