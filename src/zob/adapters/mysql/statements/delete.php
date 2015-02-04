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
    private $ignore;

    function __construct($ignore = false)
    {
        $this->ignore = $ignore;
    }

    public function toSql()
    {
        $r = ['DELETE'];

        if($this->ingore) {
            $r[] = 'IGNORE';
        }

        return [implode(' ', $r)];
    }
}

