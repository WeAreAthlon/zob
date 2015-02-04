<?php
/**
 * MySql WHERE clause.
 *
 * @package    Zob
 * @subpackage Adapters\MySql\Statements
 * @author     Kalin Stefanov <kalin@athlonsofia.com>
 * @copyright  Copyright (c) 2015, Zob
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace Zob\Adapters\MySql\Statements;

class Where
{
    private $options = [];

    function __construct($conditions, $vars = [])
    {
        $this->add($conditions, $vars);
    }

    public function add($conditions, $vars = [])
    {
        $this->options[] = [$conditions, $vars];
    }

    public function toSql()
    {
        $r = []; $vars = [];

        foreach($this->options as $option) {
            if(is_string($option[0])) {
                $r[] = $option[0];
                $vars = array_merge($vars, $option[1]);
            } elseif(is_array($option[0])) {
                foreach($option[0] as $key => $value) {
                    if(is_array($value)) {
                        /* Check if the value is an associative array */
                        if((bool)count(array_filter(array_keys($value), 'is_string'))) {
                            list($c, $v) = $this->parseCondition($key, $value);
                            $r[] = "({$c})";
                            $vars = array_merge($vars, $v);
                        } else {
                            $v = implode(', ', array_map(function() { return '?'; }, $value));
                            $r[] = "{$key} IN({$v})"; 
                            $vars = array_merge($vars, $value);
                        }
                    } else {
                        $r[] = "{$key} = ?";
                        $vars[] = $value;
                    }
                }
            }
        }

        $r = implode(' AND ', $r);
        
        return ["WHERE {$r}", $vars];
    }

    private function parseCondition($field, $values)
    {
        $r = [];

        foreach($values as $key => $value) {
            $operator = $this->getOperator($key);
            $r[] = "{$field} {$operator} ?";
            $p[] = $value;
        }

        return [implode(' AND ', $r), $p];
    }

    private function getOperator($param)
    {
        switch($param) {
            case '$lt':     return '<';
            case '$lte':    return '<=';
            case '$gt':     return '>';
            case '$gte':    return '>=';
            case '$eq':     return '=';
            case '$neq':    return '!=';
        }
    }
}

