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
    function __construct(TableInterface $table)
    {
        /* Select all table fields */
        $this->fields = Sql::fieldsToString($table->getName(), $fields);
        $this->from = $table->getName();
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

        $r[] = $this->fields;
        $r[] = 'FROM';
        $r[] = $this->from;

        return [implode(' ', $r), []];
    }
}

