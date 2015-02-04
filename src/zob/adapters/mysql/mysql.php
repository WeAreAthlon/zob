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

    public function __construct(array $dsn)
    {
        parent::__construct(
            'mysql:host=' . $dsn['host'] . ';dbname=' . $dsn['name'],
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

    public function query($query)
    {
        list($sql, $params) = $this->builder->queryString($query);

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

    public function execute($query)
    {
    
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

