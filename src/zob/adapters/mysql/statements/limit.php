<?php
/**
 * MySql Limit clause.
 *
 * @package    Zob
 * @subpackage Adapters\MySql\Statements
 * @author     Kalin Stefanov <kalin@athlonsofia.com>
 * @copyright  Copyright (c) 2015, Zob
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace Zob\Adapters\MySql\Statements;

class Limit
{
    private $limit;
    private $offset;

    function __construct($limit, $offset = 0)
    {
        $this->limit = $limit;
        $this->offset = $offset;
    }

    public function toSql()
    {
        if($this->offset) {
            return ["LIMIT ? OFFSET ?", [$this->limit, $this->offset]];
        }

        return ["LIMIT ?", [$this->limit]];
    }
}

