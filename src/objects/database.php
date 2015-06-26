<?php
/**
 * Database object.
 *
 * @package    Zob
 * @subpackage Objects
 * @author     Kalin Stefanov <kalin@athlonsofia.com>
 * @copyright  Copyright (c) 2015, Zob
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace Zob\Objects;

/**
 * Object representation of a Database
 */
class Database implements DatabaseInterface
{
    private $tables = [];

    /**
     * Database name
     *
     * @var string
     * @access private
     */
    private $name;

    /**
     * Database character set
     *
     * @var string
     * @access private
     */
    private $characterSet = 'utf8';

    /**
     * Database collation
     *
     * @var string
     * @access private
     */
    private $collation = 'utf8_general_ci';

    /**
     * Basic constructor
     *
     * @param string $name Database name
     *
     * @access public
     */
    public function __construct($name, array $tables = [])
    {
        $this->name = $name;

        foreach($tables as $table) {
            $this->tables[$table->getName()] = $table;
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCharacterSet()
    {
        return $this->characterSet;
    }

    public function setCharacterSet($characterSet)
    {
        $this->characterSet = $characterSet;
    }

    public function getCollation()
    {
        return $this->collation;
    }

    public function setCollation($collation)
    {
        $this->collation = $collation;
    }

    public function getTable($table)
    {
        return $this->tables[$table];
    }

    public function addTable(TableInterface $table)
    {
        $this->tables[$table->getName()] = $table;
    }

    public function removeTable($table)
    {
        if(isset($this->tables[$table])) {
            unset($this->tables[$table]);

            return true;
        }

        return false;
    }

    public function getTables()
    {
        return $this->tables;
    }
}

