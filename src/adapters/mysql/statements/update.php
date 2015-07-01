<?php
/**
 * MySql update statement.
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
 * Update statement class
 */
class Update
{
    use WhereTrait;
    use OrderTrait;
    use LimitTrait;

    /**
     * Table name to update
     *
     * @var string
     * @access private
     */
    private $table;

    /**
     * Values to insert
     *
     * @var array
     * @access private
     */
    private $values;

    /**
     * Basic constructor
     *
     * @param TableInterface $table Table object
     * @param array $values Values to insert
     *
     * @access public
     */
    function __construct(TableInterface $table, $values = [])
    {
        $this->table = $table;
        $this->values = $values;
    }

    /**
     * Build SQL for the statement
     *
     * @access public
     *
     * @return array($sql, $bindParams)
     */
    public function toSql()
    {
        $r = ["UPDATE {$this->table->getName()} SET"];
        $field = [];
        $values = [];
        $tableFields = $this->table->getFields();

        foreach ($tableFields as $field) {
            if (!$field->isAutoIncrement()) {
                $fields[] = "{$field->getName()} = ?";
                $values[] = isset($this->values[$field->getName()]) ? $this->values[$field->getName()] : null;
            }
        }

        $r[] = implode(', ', $fields);

        foreach (['where', 'order', 'limit'] as $clause) {
            if ($this->{$clause}) {
                list($s, $p) = $this->{$clause}->toSql();
                $r[] = $s;
                $values = array_merge($values, $p);
            }
        }

        return [implode(' ', $r), $values];
    }
}

