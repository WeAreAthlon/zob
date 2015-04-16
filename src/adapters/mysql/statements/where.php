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

/**
 * Where statement class
 */
class Where
{
    /**
     * Query options
     *
     * @var array
     * @access private
     */
    private $options = [];

    /**
     * Basic constructor
     *
     * @param type name info
     * @access private
     */
    function __construct($conditions, $vars = [])
    {
        $this->add($conditions, $vars);
    }

    /**
     * Insert additional query option
     *
     * @param array $conditions Query condition
     * @param array $vars Any values to bind
     *
     * @access public
     *
     * @return void
     */
    public function add($conditions, $vars = [])
    {
        $this->options[] = [$conditions, $vars];
    }

    /**
     * Build SQL for the statement
     *
     * @access public
     *
     * @return array($sql, $bindParams)
     */
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

    /**
     * Parse query condition
     *
     * @param string $field Field name
     * @param array $values List if $key=>$value, where the $key is operator and $value is the actual value
     *
     * @access private
     *
     * @return array($sql, $bindParams)
     */
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

    /**
     * Parse a string to extract a logic operator
     *
     * @param string $param Operator
     *
     * @access private
     *
     * @return string
     */
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

