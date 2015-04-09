<?php
/**
 * MySql insert statement.
 *
 * @package    Zob
 * @subpackage Adapters\MySql\Statements
 * @author     Kalin Stefanov <kalin@athlonsofia.com>
 * @copyright  Copyright (c) 2015, Zob
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace Zob\Adapters\MySql\Statements;

/**
 * Insert statement class
 */
class Insert
{
    /**
     * Table name to insert into
     *
     * @var string
     *
     * @access private
     */
    private $into;

    /**
     * Which fields to set
     *
     * @var array
     *
     * @access private
     */
    private $fields;

    /**
     * Values to be inserted
     *
     * @var array
     *
     * @access private
     */
    private $values;

    /**
     * Basic constructor
     *
     * @param string $into Table name
     * @param array $fields List of fields
     * @param array $values List of values
     *
     * @access public
     */
    function __construct($into, $fields = [], $values = [])
    {
        $this->into = $into;

        if((bool)count(array_filter(array_keys($fields), 'is_string'))) {
            $values = array_values($fields);
            $fields = array_keys($fields);
        }
        $this->fields = $fields;
        $this->values = $values;
    }

    /**
     * Build a SQL for the statement
     * Throws an UnexpectedValueException if the fields and values aren't with the same length
     *
     * @access public
     *
     * @return array($sql, $bindParams)
     */
    public function toSql()
    {
        if(count($this->fields) !== count($this->values)) {
            throw new \UnexpectedValueException("The number of values doesn't match the number of fields");
        }

        $r = ["INSERT INTO {$this->into}"];
        $f = implode(', ', $this->fields);
        $v = implode(', ', array_map(function() { return '?'; }, $this->values));
        $r[] = "({$f}) VALUES ({$v})";

        return [implode(' ', $r), $this->values];
    }
}

