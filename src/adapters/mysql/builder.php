<?php
/**
 * MySql builder.
 *
 * @package    Zob
 * @subpackage Adapters\MySql
 * @author     Kalin Stefanov <kalin@athlonsofia.com>
 * @copyright  Copyright (c) 2015, Zob
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace Zob\Adapters\MySql;

/**
 * SQL Builder class
 */
class Builder
{
    /**
     * Create Zob\Adapters\Statements object
     * Throws a DomainException if the adapter doesn't support the statement
     *
     * @param string $statement Statement name
     *
     * @access public
     *
     * @return Zob\Adapters\Statement
     */
    public function create($statement)
    {
        $statement = ucfirst($statement);
        $class = "Zob\Adapters\MySql\Statements\\{$statement}";
        if(!class_exists($class)) {
            throw new \DomainException("The Adapter doesn't support {$statement} statement.");
        }

        $ref = new \ReflectionClass($class);
        return $ref->newInstanceArgs(array_slice(func_get_args(), 1));
    }

    /**
     * Build SQL query string from list of options
     *
     * @param array $options Query options
     *
     * @access public
     *
     * @return array($sql, $bindParams)
     *
     * @TODO REFACTOR
     */
    public function buildQuery(array $options)
    {
        $sql = []; $params = [];

        $ref = new \ReflectionClass(get_class($options['statement']));
        switch($ref->getShortName()) {
            case 'Select': {
                $statements = ['statement', 'from', 'join', 'where', 'group', 'having', 'order', 'limit'];
                break;
            };

            case 'Insert': {
                $statements = ['statement'];
                break;
            };

            case 'Delete': {
                $statements = ['statement', 'from', 'where', 'order', 'limit'];
                break;
            };

            case 'Update': {
                $statements = ['statement', 'where', 'order', 'limit'];
                break;
            };
        }

        foreach($statements as $statement) {
            if(isset($options[$statement])) {
                list($s, $p) = $options[$statement]->toSql();
                $sql[] = $s;

                if($p) {
                    $params = array_merge($params, $p);
                }
            }
        }

        return [implode(' ', $sql), $params];
    }

    /**
     * SQL for checking if database exists
     *
     * @param string $name Database name
     *
     * @access public
     *
     * @return string
     */
    public function databaseExists($name)
    {
        return "SHOW DATABASES LIKE '{$name}'";
    }

    /**
     * SQL for creating a database
     *
     * @param string $name Database name
     * @param string $charSet Database character set
     * @param string $collation Database collation
     *
     * @access public
     *
     * @return string
     */
    public function createDatabase($name, $charSet, $collation)
    {
        return "CREATE database {$name} CHARACTER SET = {$charSet} COLLATE = {$collation}";
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
    public function deleteDatabase($name)
    {
        return "DROP DATABASE {$name}";
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
    public function tableExists($name)
    {
        return "SHOW TABLES LIKE '{$name}'";
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
    public function getTable($name)
    {
        return "DESCRIBE {$name}";
    }

    /**
     * SQL for creating a table
     *
     * @param Zob\Objvets\Table $table Table definition
     *
     * @access public
     *
     * @return string
     */
    public function createTable($table)
    {
        $fields = implode(',', array_map(function($item) { return $this->buildField($item); }, $table->fields));

        return "CREATE TABLE {$table->name} ({$fields})";
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
    public function deleteTable($name)
    {
        return "DROP TABLE {$name}";
    }

    /**
     * SQL for creating a field
     *
     * @param string $tableName Table name
     * @param Zob\Objects\Field $field Field definition
     *
     * @access public
     *
     * @return string
     */
    public function createField($tableName, $field)
    {
        $fieldSql = $this->buildField($field);

        return "ALTER TABLE {$tableName} ADD COLUMN {$fieldSql}";
    }

    /**
     * SQL for changing field definition
     *
     * @param string $tableName Table name
     * @param string $fieldName Field name
     * @param Zob\Objects\Field $field New field definition
     *
     * @access public
     *
     * @return string
     */
    public function changeField($tableName, $fieldName, $field)
    {
        $fieldSql = $this->buildField($field);

        return "ALTER TABLE {$tableName} CHANGE COLUMN {$fieldName} {$fieldSql}";
    }

    /**
     * SQL for deleting a field
     *
     * @param string $tableName Table name
     * @param string $fieldName Field name
     *
     * @access public
     *
     * @return string
     */
    public function deleteField($tableName, $fieldName)
    {
        return "ALTER TABLE {$tableName} DROP COLUMN {$fieldName}";
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
    public function getIndexes($name)
    {
        return "SHOW INDEXES IN {$name} WHERE Key_name != 'PRIMARY'";
    }

    /**
     * SQL for creating an index
     *
     * @param string $tableName Table name
     * @param Zob\Objects\Index $index Index definition
     *
     * @access public
     *
     * @return string
     */
    public function createIndex($tableName, $index)
    {
        $indexSql = $this->buildIndex($index);

        return "ALTER TABLE {$tableName} ADD" . ($index->unique ? ' UNIQUE': '') . " INDEX {$indexSql}";
    }

    /**
     * SQL for deleting an index
     *
     * @param string $tableName Table name
     * @param string $indexName Index name
     *
     * @access public
     *
     * @return string
     */
    public function deleteIndex($tableName, $indexName)
    {
        return "ALTER TABLE {$tableName} DROP INDEX {$indexName}";
    }

    /**
     * Partial SQL for field definition
     *
     * @param Zob|Objects\Field $field Field definition
     *
     * @access private
     *
     * @return string
     */
    private function buildField($field)
    {
        $p = [$field->name];

        $p[] = "{$field->type}" . ($field->length ? "({$field->length})" : '');
        $p[] = $field->required ? 'NOT NULL' : 'NULL';

        if($field->default) {
            $p[] = $field->default;
        }

        if($field->ai) {
            $p[] = 'AUTO_INCREMENT';
        }

        if($field->pk) {
            $p[] = 'PRIMARY KEY';
        }

        return implode(' ', $p);
    }

    /**
     * Partial SQL for index definition
     *
     * @param Zob\Objects\Index index Index definition
     *
     * @access private
     *
     * @return string
     */
    private function buildIndex($index)
    {
        $p = [$index->name];
        $p[] = "USING {$index->type}";

        if(is_array($index->field)) {
            $p[] = implode(',', $idnex->field);
        } else {
            $p[] = '(' . $index->field . ($index->length ? "({$index->length})" : '') . ')';
        }

        return implode(' ', $p);
    }
}

