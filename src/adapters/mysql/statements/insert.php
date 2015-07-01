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

use Zob\Objects\TableInterface;

/**
 * Insert statement class
 */
class Insert
{
    /**
     * Table name to insert into
     *
     * @var TableInterface
     *
     * @access private
     */
    private $table;

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
     * @param TableInterface $table Table instance
     * @param array $values List of values
     *
     * @access public
     */
    function __construct(TableInterface $table, $values = [])
    {
        $this->table = $table;
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
        $r = ["INSERT INTO {$this->table->getName()}"];
        $field = [];
        $values = [];
        $tableFields = $this->table->getFields();

        foreach ($tableFields as $field) {
            if (!$field->isAutoIncrement()) {
                $fields[] = $field->getName();
                $values[] = isset($this->values[$field->getName()]) ? $this->values[$field->getName()] : null;
            }
        }

        $f = implode(', ', $fields);
        $v = implode(', ', array_map(function() { return '?'; }, $values));
        $r[] = "({$f}) VALUES ({$v})";

        return [implode(' ', $r), $values];
    }
}

