<?php
/**
 * MySql Order clause.
 *
 * @package    Zob
 * @subpackage Adapters\MySql\Statements
 * @author     Kalin Stefanov <kalin@athlonsofia.com>
 * @copyright  Copyright (c) 2015, Zob
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace Zob\Adapters\MySql\Statements;

/**
 * Order statement class
 */
class Order
{
    /**
     * Stores ordering options
     *
     * @var array
     * @access private
     */
    private $fields = [];

    /**
     * Basic construct
     *
     * @param array/string $by Fields to order by with
     * @param string $direction ASC/DESC
     *
     * @access public
     */
    function __construct($by, $direction = null)
    {
        if(is_array($by)) {
            foreach($by as $key => $value) {
                $this->fields[$key] = $value;
            }
        } else {
            $this->fields[$by] = $direction;
        }
    }

    /**
     * Add another field to order by with
     *
     * @param string $by Field name
     * @param string $direction ASC/DESC
     *
     * @access public
     *
     * @return void
     */
    public function add($by, $direction)
    {
        $this->fields[$by] = $direction;
    }

    /**
     * Remove added field from the order statement
     *
     * @param string $by Field name
     *
     * @access public
     *
     * @return void
     */
    public function remove($by)
    {
        unset($this->fields[$by]);
    }

    /**
     * Build a SQL for the statement
     *
     * @access public
     *
     * @return array($sqli, array())
     */
    public function toSql()
    {
        $r = [];
        foreach($this->fields as $key => $value) {
            $r[] = "{$key} {$value}";
        }

        $fields = implode(', ', $r);

        return ["ORDER BY {$fields}", []];
    }
}

