<?php
/**
 * Sql Helper
 *
 * @package    Zob
 * @subpackage Adapters
 * @author     Kalin Stefanov <kalin@athlonsofia.com>
 * @copyright  Copyright (c) 2015, Zob
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace Zob\Adapters;

/**
 * SQL Helper class
 */
class SqlHelper
{
    /**
     * SQL for checking if database exists
     *
     * @param string $name Database name
     *
     * @access public
     *
     * @return string
     */
    public function databaseExists($database)
    {
        return "SHOW DATABASES LIKE '{$database}'";
    }

    /**
     * SQL for creating a database
     *
     * @param DatabaseInterface $database Database object
     *
     * @access public
     *
     * @return string
     */
    public function createDatabase($database)
    {
        return "CREATE database {$database->getName()} CHARACTER SET = {$database->getCharSet()} COLLATE = {$database->getCollation()}";
    }

    /**
     * SQL for database deletion
     *
     * @param string $name Database name
     *
     * @access public
     *
     * @return string
     */
    public function deleteDatabase($database)
    {
        return "DROP DATABASE {$database}";
    }

    /**
     * SQL for checking if table exists
     *
     * @param string $name Table name
     *
     * @access public
     *
     * @return string
     */
    public function tableExists($table)
    {
        return "SHOW TABLES LIKE '{$table}'";
    }

    /**
     * SQL for getting table information
     *
     * @param string $name Table name
     *
     * @access public
     *
     * @return string
     */
    public function getTable($table)
    {
        return "DESCRIBE {$table}";
    }

    /**
     * SQL for creating a table
     *
     * @param TableInterface $table Table object
     *
     * @access public
     *
     * @return string
     */
    public function createTable(TableInterface $table)
    {
        $fields = implode(',', array_map(function($item) { return $this->buildField($item); }, $table->getFields()));

        return "CREATE TABLE {$table->getName()} ({$fields})";
    }

    /**
     * SQL for deleting a table
     *
     * @param string $name Table name
     *
     * @access public
     *
     * @return string
     */
    public function deleteTable($table)
    {
        return "DROP TABLE {$table}";
    }

    /**
     * SQL for creating a field
     *
     * @param FieldInterface $field Field object
     * @param string $tableName Table name
     *
     * @access public
     *
     * @return string
     */
    public function createField(FieldInterface $field, $table)
    {
        $fieldSql = $this->buildField($field);

        return "ALTER TABLE {$table} ADD COLUMN {$fieldSql}";
    }

    /**
     * SQL for changing field definition
     *
     * @param string $tableName Table name
     * @param string $fieldName Field name
     * @param FieldInterface $field New field definition
     *
     * @access public
     *
     * @return string
     */
    public function changeField($table, $fieldName, FieldInterface $field)
    {
        $fieldSql = $this->buildField($field);

        return "ALTER TABLE {$table} CHANGE COLUMN {$fieldName} {$fieldSql}";
    }

    /**
     * SQL for deleting a field
     *
     * @param string $fieldName Field name
     * @param string $tableName Table name
     *
     * @access public
     *
     * @return string
     */
    public function deleteField($field, $table)
    {
        return "ALTER TABLE {$table} DROP COLUMN {$field}";
    }

    /**
     * SQL for getting information about table indexes
     *
     * @param string $name Table name
     *
     * @access public
     *
     * @return string
     */
    public function getIndexes($table)
    {
        return "SHOW INDEXES IN {$table} WHERE Key_name != 'PRIMARY'";
    }

    /**
     * SQL for creating an index
     *
     * @param IndexInterface $index Index object
     * @param string $tableName Table name
     *
     * @access public
     *
     * @return string
     */
    public function createIndex(IndexInterface $index, $table)
    {
        $indexSql = $this->buildIndex($index);

        return "ALTER TABLE {$table} ADD" . ($index->isUnique() ? ' UNIQUE': '') . " INDEX {$indexSql}";
    }

    /**
     * SQL for deleting an index
     *
     * @param string $index Index name
     * @param string $table Table name
     *
     * @access public
     *
     * @return string
     */
    public function deleteIndex($index, $table)
    {
        return "ALTER TABLE {$table} DROP INDEX {$index}";
    }

    /**
     * Partial SQL for field definition
     *
     * @param FieldInterface $field Field object
     *
     * @access private
     *
     * @return string
     */
    private function buildField(FieldInterface $field)
    {
        $p = [$field->getName()];

        $p[] = "{$field->getType()}" . ($field->getLength() ? "({$field->getLength()})" : '');
        $p[] = $field->isRequired() ? 'NOT NULL' : 'NULL';

        if($field->getDefault()) {
            $p[] = $field->getDefault();
        }

        if($field->isAutoIncrement()) {
            $p[] = 'AUTO_INCREMENT';
        }

        if($field->isPrimaryKey()) {
            $p[] = 'PRIMARY KEY';
        }

        return implode(' ', $p);
    }

    /**
     * Partial SQL for index definition
     *
     * @param IndexInterface index Index object
     *
     * @access private
     *
     * @return string
     */
    private function buildIndex(IndexInterface $index)
    {
        $p = [$index->getName()];
        $p[] = "USING {$index->getType()}";

        if(is_array($index->getField())) {
            $p[] = implode(',', $idnex->getField());
        } else {
            $p[] = '(' . $index->getField() . ($index->getLength() ? "({$index->getLength()})" : '') . ')';
        }

        return implode(' ', $p);
    }
}

