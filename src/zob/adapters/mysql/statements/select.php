<?php
/**
 * MySql select statement.
 *
 * @package    Zob
 * @subpackage Adapters\MySql\Statements
 * @author     Kalin Stefanov <kalin@athlonsofia.com>
 * @copyright  Copyright (c) 2015, Zob
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace Zob\Adapters\MySql\Statements;

/**
 * Select statement class
 */
class Select
{
    /**
     * List of fields to retrieve
     *
     * @var string/array
     * @access private
     */
    private $fields;

    /**
     * Whether to retrieve only the unique records
     *
     * @var bool
     * @access private
     */
    private $uniq = false;

    /**
     * Basic constructor
     *
     * @param string/array $fields List of fields
     * @param bool $uniq Return only unique records
     *
     * @access public
     */
    function __construct($fields = '*', $uniq)
    {
        $this->fields = $fields;
        $this->uniq = $uniq;
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
        $r = ['SELECT'];

        if($this->uniq) {
            $r[] = 'DISTINCT';
        }

        $fields = $this->fields;
        if(is_array($this->fields)) {
            $fields = implode(', ', $this->fields);
        } 

        $r[] = $fields;

        return [implode(' ', $r), []];
    }
}

