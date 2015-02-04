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

class Select
{
    private $fields;
    private $uniq = false;

    function __construct($fields = '*')
    {
        $this->fields = $fields;
    }

    public function uniq($value)
    {
        $this->uniq = $value;
    }

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

        return [implode(' ', $r)];
    }
}

