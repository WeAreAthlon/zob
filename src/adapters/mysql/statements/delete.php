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

use Zob\Objects\TableInterface;

/**
 * Delete statement class
 */
class Delete
{
    use WhereTrait;
    use OrderTrait;
    use LimitTrait;

    /**
     * Table name
     *
     * @var string
     *
     * @access private
     */
    private $table;

    /**
     * Ignore errors if the record doesn't exists
     *
     * @var string
     * @access private
     */
    private $ignore;

    /**
     * Basic constructor
     *
     * @param TableInterface $table Table object
     * @param bool $ignore Ignore errors
     *
     * @access public
     */
    function __construct(TableInterface $table, $ignore = false)
    {
        $this->table = $table;
        $this->ignore = $ignore;
    }

    /**
     * Build a SQL for the statement
     *
     * @access public
     *
     * @return string
     */
    public function toSql()
    {
        $r = ['DELETE'];
        $vars = [];

        if($this->ignore) {
            $r[] = 'IGNORE';
        }

        $r[] = "FROM {$this->table->getName()}";

        foreach (['where', 'order', 'limit'] as $clause) {
            if ($this->{$clause}) {
                list($s, $p) = $this->{$clause}->toSql();
                $r[] = $s;
                $vars = array_merge($vars, $p);
            }
        }

        return [implode(' ', $r), $vars];
    }
}

