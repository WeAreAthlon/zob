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

use Zob\Adapters\StatementInterface;
use Zob\Objects\TableInterface;
use Zob\Helpers\Sql;

/**
 * Select statement class
 */
class Select implements StatementInterface
{
    use WhereTrait;
    use OrderTrait;
    use LimitTrait;

    /**
     * List of fields to retrieve
     *
     * @var string/array
     * @access private
     */
    private $fields;

    private $uniq = false;

    /**
     * Basic constructor
     *
     * @param TableInterface $table Table object
     *
     * @access public
     */
    function __construct(TableInterface $table)
    {
        $this->table = $table;
    }

    /**
     * Set the uniq option
     *
     * @param bool $value Uniq option
     *
     * @access public
     */
    public function uniq($value)
    {
        $this->uniq = $value;
    }

    /**
     * Build a SQL for the statement
     *
     * @access public
     *
     * @return array($sql, array())
     */
    public function toSql()
    {
        $r = ['SELECT'];

        if($this->uniq) {
            $r[] = 'DISTINCT';
        }

        $fields = [];
        foreach ($this->table->getFields() as $field) {
            $fields[] = "{$this->table->getName()}.{$field->getName()}";
        }

        $r[] = implode(', ', $fields);
        $r[] = "FROM {$this->table->getName()}";

        foreach ($this->table->getJoins() as $join) {
            $r[] = "{$join->type} JOIN {$join->table->getName()} ON ({parseConditions($join->getConditions())})";
        }

        foreach (['where', 'order', 'limit'] as $clause) {
            if ($this->{$clause}) {
                $r[] = $this->{$clause}->toSql();
            }
        }

        return [implode(' ', $r), []];
    }
}

