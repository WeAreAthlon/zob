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

    function __construct($connection, $name, $definition = [])
    {
        $this->connection = $connection;
        $this->name = $name;

        foreach($definition as $value) {
            if(!($value instanceof Field)) {
                $value = new Field($value);
            }

            $this->fields[$value->name] = $value;
        }
    }

    public static function get($connection, $name)
    {
        $r = $connection->getTable($name);
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

        $this->connection->createField($this->name, $definition);
        $this->fields[$definition->name] = $definition;
    }

    public function deleteField($name)
    {
        $this->connection->deleteField($this->name, $name);

        unset($this->fields[$name]);
    }

    public function changeField($name, $definition)
    {
        if(!($definition instanceof Field)) {
            $definition = new Field($definition);
        }

        $this->connection->changeField($this->name, $name, $definition);
        $this->fields[$name] = $definition;
    }

    public function addIndex($definition)
    {
        if(!($definition instanceof Index)) {
            $definition = new Index($definition);
        }

        $this->connection->createIndex($this->name, $definition);
        $this->indexes[$definition->name] = $definition;
    }

    public function deleteIndex($name)
    {
        $this->connection->deleteIndex($this->name, $name);

        unset($this->indexes[$name]);
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

