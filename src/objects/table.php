<?php
/**
 * Table
 *
 * @package    Zob
 * @subpackage Objects
 * @author     Kalin Stefanov <kalin@athlonsofia.com>
 * @copyright  Copyright (c) 2015, Zob
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace Zob\Objects;

/**
 * Table class
 */
class Table implements TableInterface
{
    private $name;

    private $fields = [];

    private $indexes = [];

    private $joins = [];

    /**
     * Basic constructor
     *
     * @param string $name Table name
     * @param array $field List of fields
     * @param array $indexes List of indexes
     *
     * @access public
     */
    public function __construct($name, array $fields = [], array $indexes = [])
    {
        $this->name = $name;
        foreach ($fields as $field) {
            $this->fields[$field->getName()] = $field;
            $field->setTable($this);
        }

        foreach ($indexes as $index) {
            $this->indexes[$index->getName()] = $index;
        }
    }

    /**
     * Returns the name of the table
     *
     * @access public
     *
     * @return string Table name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns field object or false
     *
     * @param string $fieldName Name of the field
     *
     * @access public
     *
     * @return FieldInterface/False
     */
    public function getField($fieldName)
    {
        if (isset($this->fields[$fieldName])) {
            return $this->fields[$fieldName];
        }

        return false;
    }

    /**
     * Returns all table fields
     *
     * @access public
     *
     * @return array
     */
    public function getFields()
    {
        return array_values($this->fields);
    }

    /**
     * Returns index object or false
     *
     * @param string $indexName Name of the index
     *
     * @access public
     *
     * @return IndexInterface/False
     */
    public function getIndex($indexName)
    {
        if (isset($this->indexes[$indexName])) {
            return $this->indexes[$indexName];
        }

        return false;
    }

    /**
     * Returns all table indexes
     *
     * @access public
     *
     * @return array
     */
    public function getIndexes()
    {
        return $this->indexes;
    }

    /**
     * Returns the primary key of the table or null
     *
     * @access public
     *
     * @return FieldInterface/Null
     */
    public function getPrimaryKey()
    {
        foreach ($this->fields as $field) {
            if ($field->isPrimaryKey()) {
                return $field;
            }
        }

        return null;
    }

    /**
     * Add field to the table
     *
     * @param FieldInterface $field Field to add
     *
     * @access public
     */
    public function addField(FieldInterface $field)
    {
        $this->fields[$field->getName()] = $field;
        $field->setTable($this);
    }

    /**
     * Remove field of the table
     *
     * @param string $name Field name to remove
     *
     * @access public
     *
     * @return bool
     */
    public function removeField($name)
    {
        if (isset($this->fields[$name])) {
            unset($this->fields[$name]);

            return true;
        }

        return false;
    }

    /**
     * Add index to the table
     *
     * @param IndexInterface $index Index to add
     *
     * @access public
     */
    public function addIndex(IndexInterface $index)
    {
        $this->indexes[$index->getName()] = $index;
    }

    /**
     * Remove index of the table
     *
     * @param string $name Index name to remove
     *
     * @access public
     *
     * @return bool
     */
    public function removeIndex($name)
    {
        if (isset($this->indexes[$name])) {
            unset($this->indexes[$name]);

            return true;
        }

        return false;
    }

    /**
     * Returns a subset of the table
     *
     * @param array $field Name's of the fields to return
     *
     * @access public
     *
     * @return PartialTable
     */
    public function getPartial(array $fields)
    {
        $partialTable = new PartialTable($this->name);

        foreach ($fields as $key=>$fieldName) {
            if($this->getField($fieldName)) {
                $field = clone $this->getField($fieldName);

                if (is_string($key)) {
                    $field->setAlias($key);
                }

                $partialTable->addField($field);
            }
        }

        return $partialTable;
    }

    /**
     * Join with table
     *
     * @param TableInteface $table Table to join with
     * @param array $conditions Join conditions
     * @param string $type Type of the join
     *
     * @access public
     *
     * @return ConputedTable
     */
    public function join(TableInterface $table, $conditions, $type)
    {
        switch($type) {
            case 'left': $join = new LeftJoin($conditions); break;
        }

        return new ComputedTable($this, $table, $join);
    }

    /**
     * Return the join object
     *
     * @access public
     *
     * @return null
     */
    public function getJoin()
    {
        return null;
    }
}

