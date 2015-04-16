<?php
/**
 * MySql FROM clause.
 *
 * @package    Zob
 * @subpackage Adapters\MySql\Statements
 * @author     Kalin Stefanov <kalin@athlonsofia.com>
 * @copyright  Copyright (c) 2015, Zob
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace Zob\Adapters\MySql\Statements;

/**
 * From statement class
 */
class From
{
    /**
     * Table source
     * Can be either a table name or a subquery returning a dataset
     *
     * @var string
     * @access private
     */
    private $source;

    /**
     * Bind params
     *
     * @var array
     * @access private
     */
    private $vars = [];

    /**
     * Basic constructor
     *
     * @param string $source Table name or subquery
     * @param array $vars Binded params(if using a subquery)
     *
     * @access public
     */
    function __construct($source, $vars = [])
    {
        $this->source = $source;
        $this->vars = $vars;
    }

    /**
     * Build a SQL for the statement
     *
     * @access public
     *
     * @return array($sql, $bindParams)
     */
    public function toSql()
    {
        return ["FROM {$this->source}", $this->vars];
    }
}

