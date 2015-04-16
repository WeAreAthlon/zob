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
class Database
{
    /**
     * Connection to the DB
     *
     * @var Zob\Adapters\Adapter
     * @access private
     */
    private $connection;

    /**
     * Database name
     *
     * @var string
     * @access public
     */
    public $name;

    /**
     * Database character set
     *
     * @var string
     * @access public
     */
    public $characterSet;

    /**
     * Database collation
     *
     * @var string
     * @access public
     */
    public $collation;

    /**
     * Basic constructor
     *
     * @param Zob\Adapters\Adapter $connection Connection instance
     * @param string $name Database name
     * @param string $characterSet Database character set
     * @param string $collation Database collation
     *
     * @access public
     */
    function __construct($connection, $name, $characterSet = 'utf8', $collation = 'utf8_general_ci')
    {
        $this->connection = $connection;
        $this->name = $name;
        $this->characterSet = $characterSet;
        $this->collation = $collation;
    }

    /**
     * Create the database
     *
     * @access public
     *
     * @return bool
     */
    public function create()
    {
        return $this->connection->createDatabase($this->name, $this->characterSet, $this->collation);
    }

    /**
     * Delete the database
     *
     * @access public
     *
     * @return bool
     */
    public function delete()
    {
        return $this->connection->deleteDatabase($this->name);
    }
}

