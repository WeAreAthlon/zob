<?php
/**
 * Table object.
 *
 * @package    Zob
 * @subpackage Objects
 * @author     Kalin Stefanov <kalin@athlonsofia.com>
 * @copyright  Copyright (c) 2015, Zob
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace Zob\Objects;

class Table
{
    private $connection;

    public $name,
           $fields = [],
           $indexes = [];

    function __construct($connection, $name, $definition = [], $indexes = [])
    {
        $this->connection = $connection;
        $this->name = $name;

        foreach($definition as $value) {
            if(!($value instanceof Field)) {
                $value = new Field($value);
            }

            $this->fields[$value->name] = $value;
        }

        foreach($indexes as $value) {
            if(!($value instanceof Index)) {
                $value = new Index($value);
            }

            $this->indexes[$value->name] = $value;
        }
    }

    public static function get($connection, $name)
    {
        $table = $connection->getTable($name);

        return new Table($connection, $name, $table['fields'], $table['indexes']);
    }

    public function create()
    {
        $this->connection->createTable($this);
    }

    public function delete()
    {
        $this->connection->deleteTable($this->name);
    }

    public function addField($definition)
    {
        if(!($definition instanceof Field)) {
            $definition = new Field($definition);
        }

        if($this->connection->createField($this->name, $definition)) {
            $this->fields[$definition->name] = $definition;
        }
    }

    public function deleteField($name)
    {
        if($this->connection->deleteField($this->name, $name)) {
            unset($this->fields[$name]);
        }
    }

    public function changeField($name, $definition)
    {
        if(!($definition instanceof Field)) {
            $definition = new Field($definition);
        }

        if($this->connection->changeField($this->name, $name, $definition)) {
            $this->fields[$name] = $definition;
        }
    }

    public function addIndex($definition)
    {
        if(!($definition instanceof Index)) {
            $definition = new Index($definition);
        }

        if($this->connection->createIndex($this->name, $definition)) {
            $this->indexes[$definition->name] = $definition;
        }
    }

    public function deleteIndex($name)
    {
        if($this->connection->deleteIndex($this->name, $name)) {
            unset($this->indexes[$name]);
        }
    }

    public function changeIndex($name, $definition)
    {
        if(!($definition instanceof Index)) {
            $definition = new Index($definition);
        }

        $this->connection->transaction(function() use ($name, $definition) {
            $this->deleteIndex($name);
            $this->addIndex($definition);
        });
    }
}

