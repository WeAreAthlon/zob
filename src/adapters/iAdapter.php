<?php
/**
 * Adapter interface
 *
 * @package    Zob
 * @subpackage Adapters
 * @author     Kalin Stefanov <kalin@athlonsofia.com>
 * @copyright  Copyright (c) 2015, Zob
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace Zob\Adapters;

/**
 * Database management driver interface.
 */
interface iAdapter
{
    public function databaseExists($name);

    public function createDatabase($databaseName, $charSet, $collation);

    public function deleteDatabase($databaseName);

    public function tableExists($name);

    public function getTable($table);

    public function createTable($table);

    public function deleteTable($tableName);

    public function createField($tableName, $field);

    public function changeField($tableName, $fieldName, $field);

    public function deleteField($tableName, $fieldName);

    public function createIndex($tableName, $index);

    public function deleteIndex($tableName, $indexName);

    public function run($query);

    public function query($sql, $params = []);

    public function execute($sql, $params = []);

    public function transaction(callable $scope);

    public function rollback();
}

