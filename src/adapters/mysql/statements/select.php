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
use Zob\Objects\FieldInterface;
use Zob\Objects\Condition;
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
            $fieldName = "{$field->getTable()->getName()}.{$field->getName()}";

            if ($field->getAlias()) {
                $fieldName .= " AS {$field->getAlias()}";
            }
            $fields[] = $fieldName;
        }

        $r[] = implode(', ', $fields);
        $r[] = "FROM {$this->table->getName()}";

        $join = [];
        $this->joinTable($this->table, $join);
        $r = array_merge($r, $join);

        foreach (['where', 'order', 'limit'] as $clause) {
            if ($this->{$clause}) {
                $r[] = $this->{$clause}->toSql();
            }
        }

        return [implode(' ', $r), []];
    }

    private function joinTable(TableInterface $table, array &$result)
    {
        if ($table->getJoin()) {
            $join = $table->getJoin();
            $condition = $this->parseJoinCondition($join->getConditions());
            $result[] = "{$join->getType()} JOIN {$table->getForeignTable()->getName()} ON ({$condition})";

            $this->joinTable($table->getTable(), $result);
            $this->joinTable($table->getForeignTable(), $result);
        }
    }

    private function parseJoinCondition(array $conditions)
    {
        $r = [];

        foreach ($conditions as $condition) {
            if ($condition instanceof Condition) {
                $field = $condition->getField();
                $value = $condition->getValue();

                if ($value instanceof FieldInterface) {
                    $value = "{$value->getTable()->getName()}.{$value->getName()}";
                }

                if ($field instanceof FieldInterface) {
                    $field = "{$field->getTable()->getName()}.{$field->getName()}";
                }

                $r[] = "{$field} {$condition->getOperator()} {$value}";
            } else {
                $r[] = $condition;
            }
        }

        return implode(' AND ', $r);
    }
}

