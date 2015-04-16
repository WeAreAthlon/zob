<?php
/**
 * MySql Limit clause.
 *
 * @package    Zob
 * @subpackage Adapters\MySql\Statements
 * @author     Kalin Stefanov <kalin@athlonsofia.com>
 * @copyright  Copyright (c) 2015, Zob
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace Zob\Adapters\MySql\Statements;

/**
 * Limit Statement class
 */
class Limit
{
    /**
     * Number of record to show
     *
     * @var string/int
     * @access private
     */
    private $limit;

    /**
     * Record offset
     *
     * @var string/number
     * @access private
     */
    private $offset;

    /**
     * Basic constructor
     *
     * @param string/int $limit Limit number
     * @param string/int $offset Offset number
     *
     * @access public
     */
    function __construct($limit, $offset = 0)
    {
        $this->limit = $limit;
        $this->offset = $offset;
    }

    /**
     * Build a SQL for the statement
     *
     * @access public
     */
    public function toSql()
    {
        if($this->offset) {
            return ["LIMIT ? OFFSET ?", [$this->limit, $this->offset]];
        }

        return ["LIMIT ?", [$this->limit]];
    }
}

