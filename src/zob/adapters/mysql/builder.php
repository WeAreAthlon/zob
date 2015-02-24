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

class Builder
{
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

    public function buildQuery($query)
    {
        $sql = []; $params = [];

        switch($query->statement) {
            case 'select': {
                $statements = ['statement', 'from', 'join', 'where', 'group', 'having', 'order', 'limit'];
                break;
            };

            case 'insert': {
                $statements = ['statement'];
                break;
            };

            case 'delete': {
                $statements = ['statement', 'from', 'where', 'order', 'limit'];
                break;
            };

            case 'update': {
                $statements = ['statement', 'where', 'order', 'limit'];
                break;
            };
        }

        foreach($statements as $statement) {
            if($query->$statement) {
                list($s, $p) = $query->$statement->toSql();
                $sql[] = $s;

                if($p) {
                    $params = array_merge($params, $p);
                }
            }
        }

        return [implode(' ', $sql), $params];
    }

    public function createDatabase($name, $charSet, $collation)
    {
        return "CREATE database {$name} CHARACTER SET = {$charSet} COLLATE = {$collation}";
    }

    public function deleteDatabase($name)
    {
        return "DROP DATABASE {$name}";
    }

    public function getTable($name)
    {
        return "DESCRIBE {$name}";
    }

    public function createTable($table)
    {
        $fields = implode(',', array_map(function($item) { return $this->buildField($item); }, $table->fields));

        return "CREATE TABLE {$table->name} ({$fields})";
    }

    public function deleteTable($name)
    {
        return "DROP TABLE {$name}";
    }

    public function createField($tableName, $field)
    {
        $fieldSql = $this->buildField($field);

        return "ALTER TABLE {$tableName} ADD COLUMN {$fieldSql}";
    }

    public function changeField($tableName, $fieldName, $field)
    {
        $fieldSql = $this->buildField($field);

        return "ALTER TABLE {$tableName} CHANGE COLUMN {$fieldName} {$fieldSql}";
    }

    public function deleteField($tableName, $fieldName)
    {
        return "ALTER TABLE {$tableName} DROP COLUMN {$fieldName}";
    }

    public function getIndexes($name)
    {
        return "SHOW INDEXES IN {$name} WHERE Key_name != 'PRIMARY'";
    }

    public function createIndex($tableName, $index)
    {
        $indexSql = $this->buildIndex($index);

        return "ALTER TABLE {$tableName} ADD" . ($index->unique ? ' UNIQUE': '') . " INDEX {$indexSql}";
    }

    public function deleteIndex($tableName, $indexName)
    {
        return "ALTER TABLE {$tableName} DROP INDEX {$indexName}";
    }

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

