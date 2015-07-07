<?php
/**
 * ComputedTable
 *
 * @package    Zob
 * @subpackage Objects
 * @author     Kalin Stefanov <kalin@athlonsofia.com>
 * @copyright  Copyright (c) 2015, Zob
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace Zob\Objects;

/**
 * ComputedTable class
 */
class ComputedTable implements TableInterface
{
    private $table;

    private $foreignTable;

    private $join;

    /**
     * Basic constructor
     *
     * @param TableInterface $table Table object
     * @param TableInterface $foreignTable Table object
     * @param JoinInterface $join Join object
     *
     * @access public
     */
    public function __construct(TableInterface $table, TableInterface $foreignTable, $join)
    {
        $this->table = $table;
        $this->foreignTable = $foreignTable;
        $this->join = $join;
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
        return $this->table->getName();
    }

    /**
     * Returns the base table object
     *
     * @access public
     *
     * @return TableInterface Table object
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Returns the foreign table object
     *
     * @access public
     *
     * @return TableInterface Table object
     */
    public function getForeignTable()
    {
        return $this->foreignTable;
    }

    /**
     * Returns field object
     *
     * @param string $field Name of the field
     *
     * @access public
     *
     * @return False
     */
    public function getField($field)
    {
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
        return array_merge($this->table->getFields(), $this->foreignTable->getFields());
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
     * @return JoinInterface
     */
    public function getJoin()
    {
        return $this->join;
    }
}

