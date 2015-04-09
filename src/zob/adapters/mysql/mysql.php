<?php
/**
 * PDO_MYSQL adapter.
 *
 * @package    Zob
 * @subpackage Adapters\MySql
 * @author     Kalin Stefanov <kalin@athlonsofia.com>
 * @copyright  Copyright (c) 2015, Zob
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace Zob\Adapters\MySql;

use Zob\Adapters\Adapter;

/**
 * Database management driver wrapping PDO_MYSQL extension.
 */
class MySql extends Adapter
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
        $connString = "mysql:host={$dsn['host']};";

        if(isset($dsn['name'])) {
            $connString .= "dbname={$dsn['name']}";
        }

        parent::__construct(
            $connString,
            $dsn['user'],
            $dsn['password']
        );

        $this->builder = new Builder();
    }
}

