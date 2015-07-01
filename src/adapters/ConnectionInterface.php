<?php
/**
 * Connection interface
 *
 * @package    Zob
 * @subpackage Adapters
 * @author     Kalin Stefanov <kalin@athlonsofia.com>
 * @copyright  Copyright (c) 2015, Zob
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace Zob\Adapters;

use Zob\Objects;

/**
 * Database connection interface.
 */
interface ConnectionInterface
{
    public function databaseExists($name);

    public function createDatabase(Objects\DatabaseInterface $database);

    public function deleteDatabase($database);

    public function tableExists($table);

    public function getTable($table);

    public function createTable(Objects\TableInterface $table);

    public function deleteTable($table);

    public function createField(Objects\FieldInterface $field, $table);

    public function changeField($table, $fieldName, Objects\FieldInterface $field);

    public function deleteField($field, $table);

    public function createIndex(Objects\IndexInterface $index, $table);

    public function deleteIndex($index, $table);

    public function query($sql, $params = []);

    public function execute($sql, $params = []);

    public function transaction(callable $scope);

    public function rollback();
}

