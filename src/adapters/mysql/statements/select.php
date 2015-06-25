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
     * @param string/array $fields List of fields
     * @param bool $uniq Return only unique records
     *
     * @access public
     */
    function __construct($table)
    {
        $this->table = $table;
    }

    public function limit($limit, $offset = 0)
    {
        $this->limit = new Limit($limit, $offset);

        return $this;
    }

    /**
     * Set the uniq option
     *
     * @param bool $value Uniq option
     *
     * @access public
     */
    public function uniq(bool $value)
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
            $fields[] = "{$field->getTable()->getName()}.{$field->getName()}";
        }

        $r[] = implode(', ', $fields);
        $r[] = "FROM {$this->table->getName()}";

        if (!empty($this->table->getJoins())) {
            $join = $this->table->getJoins()[0];
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

