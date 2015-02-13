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

class Database
{
    private $connection;

    public $name,
           $characterSet,
           $collation;

    function __construct($connection, $name, $characterSet = 'utf8', $collation = 'utf8_general_ci')
    {
        $this->connection = $connection;
        $this->name = $name;
        $this->characterSet = $characterSet;
        $this->collation = $collation;
    }

    public function create()
    {
        $this->connection->createDatabase($this->name, $this->characterSet, $this->collation);
    }

    public function delete()
    {
        $this->connection->deleteDatabase($this->name);
    }
}

