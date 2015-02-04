<?php
/**
 * DB Class ORM.
 *
 * @package    Zob
 * @author     Kalin Stefanov <kalin@athlonsofia.com>
 * @copyright  Copyright (c) 2015, Zob
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace Zob;

final class DB {
    
    /**
     * Instance of the singleton.
     *
     * @var DB
     * @static
     * @access private
     */
    private static $instance = null;

    final public static function getInstance(array $dsn)
    {
        if (null === self::$instance) {
            try {
                $t = __NAMESPACE__ . "\Adapters\\$dsn[adapter]";
                self::$instance = new $t();
            } catch (\Exception $e) {
                throw new \LogicException('Cannot establish a database connection');
            }
        }

        return self::$instance;
    }
}

