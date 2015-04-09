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

/**
 * Object representaion of a database table
 */
class Table
{
    /**
     * Stores an active database connection
     *
     * @var Zob\Adapters\Adapter
     * @access private
     */
    private $connection;

    /**
     * Table name
     *
     * @var string
     * @access public
     */
    public $name;

    /**
     * List of table fields(Zob\Objects\Field)
     *
     * @var array
     * @access public
     */
    public $fields = [];

    /**
     * List of table indexes(Zob\Objects\Index)
     *
     * @var array
     * @access public
     */
    public $indexes = [];

    /**
     * Basic constructor
     *
     * @param Zob\Adapters\Adapter $connection Database connection
     * @param string $name Table name
     * @param array $definition List of fields
     * @param array $indexes List of indexes
     *
     * @access public
     */
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

    /**
     * Retrieve a table object
     *
     * @param Zob\Adapters\Adapter $connection Connection instance
     * @param string $name Table name
     *
     * @access public
     * @static
     *
     * @return Zob\Objects\Table
     */
    public static function get($connection, $name)
    {
        $table = $connection->getTable($name);

        return new Table($connection, $name, $table['fields'], $table['indexes']);
    }

    /**
     * Creates a database table from the object
     *
     * @access public
     *
     * @return bool
     */
    public function create()
    {
        return $this->connection->createTable($this);
    }

    /**
     * Deletes the table from the database
     *
     * @access public
     *
     * @return bool
     */
    public function delete()
    {
        return $this->connection->deleteTable($this->name);
    }

    /**
     * Add new field to the table
     *
     * @param Zob\Adapters\Field|array $definition Field definition
     *
     * @access public
     *
     * @return void
     */
    public function addField($definition)
    {
        if(!($definition instanceof Field)) {
            $definition = new Field($definition);
        }

        if($this->connection->createField($this->name, $definition)) {
            $this->fields[$definition->name] = $definition;
        }
    }

    /**
     * Removes a field from the table
     *
     * @param string $name Field name
     *
     * @access public
     *
     * @reutrn void
     */
    public function deleteField($name)
    {
        if($this->connection->deleteField($this->name, $name)) {
            unset($this->fields[$name]);
        }
    }

    /**
     * Change the definition of a field
     *
     * @param string $name Field to be changed
     * @param Zob\Adapters\Field|array $definition New field definition
     *
     * @access public
     *
     * @return void
     */
    public function changeField($name, $definition)
    {
        if(!($definition instanceof Field)) {
            $definition = new Field($definition);
        }

        if($this->connection->changeField($this->name, $name, $definition)) {
            $this->fields[$name] = $definition;
        }
    }

    /**
     * Add new index to the table
     *
     * @param Zob\Adapters\Index|array $definition Index definition
     *
     * @access public
     *
     * @return void
     */
    public function addIndex($definition)
    {
        if(!($definition instanceof Index)) {
            $definition = new Index($definition);
        }

        if($this->connection->createIndex($this->name, $definition)) {
            $this->indexes[$definition->name] = $definition;
        }
    }

    /**
     * Removes an index from the table
     *
     * @param string $name Index name
     *
     * @access public
     *
     * @return void
     */
    public function deleteIndex($name)
    {
        if($this->connection->deleteIndex($this->name, $name)) {
            unset($this->indexes[$name]);
        }
    }

    /**
     * Change the definition of an index
     *
     * @param string $name Index name
     * @param Zob\Adapters\Index|array $definition Index definition
     *
     * @access public
     */
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

