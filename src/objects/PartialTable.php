<?php
/**
 * PartialTable
 *
 * @package    Zob
 * @subpackage Objects
 * @author     Kalin Stefanov <kalin@athlonsofia.com>
 * @copyright  Copyright (c) 2015, Zob
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace Zob\Objects;

/**
 * PartialTable class
 */
class PartialTable implements TableInterface
{
    private $name;

    private $fields = [];

    /**
     * Basic constructor
     *
     * @param string $table Table name
     *
     * @access public
     */
    public function __construct($name)
    {
        $this->name = $name;
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
     * Add field to the table
     *
     * @param FieldInterface $field Field to add
     *
     * @access public
     */
    public function addField(FieldInterface $field)
    {
        $this->fields[$field->getName()] = $field;
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
    public function join(TableInterface $table, array $conditions, $type)
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

