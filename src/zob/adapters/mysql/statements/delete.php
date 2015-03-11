<?php
/**
 * MySql delete statement.
 *
 * @package    Zob
 * @subpackage Adapters\MySql\Statements
 * @author     Kalin Stefanov <kalin@athlonsofia.com>
 * @copyright  Copyright (c) 2015, Zob
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace Zob\Adapters\MySql\Statements;

class Delete
{
    private $table,
            $ignore;

    function __construct($table, $ignore = false)
    {
        $this->table = $table;
        $this->ignore = $ignore;
    }

    public function toSql()
    {
        $r = ['DELETE'];

        if($this->ignore) {
            $r[] = 'IGNORE';
        }

        $r[] = "FROM {$this->table}";

        return [implode(' ', $r), []];
    }
}

