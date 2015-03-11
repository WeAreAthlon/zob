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

class Order
{
    private $fields = [];

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

    public function add($by, $direction)
    {
        $this->fields[$by] = $direction;
    }

    public function remove($by)
    {
        unset($this->fields[$by]);
    }

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

