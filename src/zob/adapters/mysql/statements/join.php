<?php
/**
 * MySql Join clause.
 *
 * @package    Zob
 * @subpackage Adapters\MySql\Statements
 * @author     Kalin Stefanov <kalin@athlonsofia.com>
 * @copyright  Copyright (c) 2015, Zob
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace Zob\Adapters\MySql\Statements;

/**
 * Join statement class
 */
class Join
{
    /**
     * Table name to join with
     *
     * @var string
     * @access private
     */
    private $width;

    /**
     * Join conditions
     *
     * @var string
     * @access private
     */
    private $on;

    /**
     * Type of the join
     *
     * @var string
     * @access private
     */
    private $type;

    /**
     * Basic constructor
     *
     * @param string $with Table name
     * @param string $on Join conditions
     * @param string $type Join type
     *
     * @access public
     */
    function __construct($with, $on, $type = 'LEFT')
    {
        $this->with = $with;
        $this->on = $on;
        $this->type = $type;
    }

    /**
     * Build a SQL for the statement
     *
     * @access public
     *
     * @return array($sql, array())
     */
    public function toSql()
    {
        return ["{$this->type} JOIN {$this->with} ON({$this->on})", []];
    }
}

