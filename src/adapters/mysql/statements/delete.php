<?php
/**
 * MySql delete statement.
 *
 * @package    Zob
 * @subpackage Adapters\MySql\Statements
 * @author     Kalin Stefanov <kalin@athlonsofia.com>
 * @copyright  Copyright (c) 2015, Zob
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace Zob\Adapters\MySql\Statements;

/**
 * Delete statement class
 */
class Delete
{
    /**
     * Table name
     *
     * @var string
     *
     * @access private
     */
    private $table;

    /**
     * Ignore errors if the record doesn't exists
     *
     * @var string
     * @access private
     */
    private $ignore;

    /**
     * Basic constructor
     *
     * @param string $table Table name
     * @param bool $ignore Ignore errors
     *
     * @access public
     */
    function __construct($table, $ignore = false)
    {
        $this->table = $table;
        $this->ignore = $ignore;
    }

    /**
     * Build a SQL for the statement
     *
     * @access public
     *
     * @return string
     */
    public function toSql()
    {
        $r = ['DELETE'];

        if($this->ignore) {
            $r[] = 'IGNORE';
        }

        $r[] = "FROM {$this->table}";

        return [implode(' ', $r), []];
    }
}

