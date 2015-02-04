<?php
/**
 * MySql update statement.
 *
 * @package    Zob
 * @subpackage Adapters\MySql\Statements
 * @author     Kalin Stefanov <kalin@athlonsofia.com>
 * @copyright  Copyright (c) 2015, Zob
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace Zob\Adapters\MySql\Statements;

class Update
{
    private $table,
            $fields,
            $values;

    function __construct($table, $fields = [], $values = [])
    {
        $this->table = $table;
        if((bool)count(array_filter(array_keys($fields), 'is_string'))) {
            $values = array_values($fields);
            $fields = array_keys($fields);
        }
        $this->fields = $fields;
        $this->values = $values;
    }

    public function toSql()
    {
        if(count($this->fields) !== count($this->values)) {
            throw new \UnexpectedValueException("The number of values doesn't match the number of fields");
        }

        $r = ["UPDATE {$this->table} SET"];
        $r[] = implode(', ', array_map(function($item) { return "{$item} = ?"; }, $this->fields));

        return [implode(' ', $r), $this->values];
    }
}

