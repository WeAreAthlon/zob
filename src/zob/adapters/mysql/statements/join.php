<?php
/**
 * MySql Join clause.
 *
 * @package    Zob
 * @subpackage Adapters\MySql\Statements
 * @author     Kalin Stefanov <kalin@athlonsofia.com>
 * @copyright  Copyright (c) 2015, Zob
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace Zob\Adapters\MySql\Statements;

class Join
{
    function __construct($with, $on, $type = 'LEFT')
    {
        $this->with = $with;
        $this->on = $on;
        $this->type = $type;
    }

    public function toSql()
    {
        return ["{$this->type} JOIN {$this->with} ON({$this->on})", []];
    }
}

