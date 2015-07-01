<?php
/**
 * MySql adapter.
 *
 * @package    Zob
 * @subpackage Adapters\MySql
 * @author     Kalin Stefanov <kalin@athlonsofia.com>
 * @copyright  Copyright (c) 2015, Zob
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace Zob\Adapters\MySql;

use Zob\Adapters\Connection;
use Zob\Adapters\MySql\Factories;

/**
 * Database management driver wrapping PDO_MYSQL extension.
 */
class MySql extends Connection
{
    /**
     * Create the DB connection and initialize a SQL builder
     *
     * @param array $dsn Database Service Name
     *
     * @access prublic
     */
    public function __construct(array $dsn)
    {
        parent::__construct();

        $connString = "mysql:host={$dsn['host']};";

        if(isset($dsn['name'])) {
            $connString .= "dbname={$dsn['name']}";
        }

        $this->conn = new \PDO($connString, $dsn['user'], $dsn['password']);

        $this->selectFactory = new Factories\SelectFactory();
        $this->insertFactory = new Factories\InsertFactory();
        $this->updateFactory = new Factories\UpdateFactory();
        $this->deleteFactory = new Factories\DeleteFactory();
    }
}

