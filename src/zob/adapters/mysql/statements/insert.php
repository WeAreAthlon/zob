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

class Insert
{
    private $into,
            $fields = [],
            $values = [];

    function __construct($into, $fields = [], $values = [])
    {
        $this->into = $into;
        $this->fields = $fields;
        $this->values = $values;
    }

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

