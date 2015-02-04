<?php
/**
 * MySql FROM clause.
 *
 * @package    Zob
 * @subpackage Adapters\MySql\Statements
 * @author     Kalin Stefanov <kalin@athlonsofia.com>
 * @copyright  Copyright (c) 2015, Zob
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace Zob\Adapters\MySql\Statements;

class From
{
    private $source;
    private $vars;

    function __construct($source, $vars = [])
    {
        $this->source = $source;
        $this->vars = $vars;
    }

    public function toSql()
    {
        return ["FROM {$this->source}", $this->vars];
    }
}

