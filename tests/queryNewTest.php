<?php
use Zob\QueryNew;
use Zob\Adapters\MySql\MySql;

/**
 * @covers Zob\QueryNew
 */
class QueryNewTest extends PHPUnit_Framework_TestCase
{
    protected static $connection;

    public static function setUpBeforeClass()
    {
        self::$connection = new MySql([
            'host' => 'localhost',
            'name' => 'silla_test',
            'user' => 'root',
            'password' => ''
        ]);
    }

    protected function setUp()
    {
        $this->query = new Query(self::$connection);
    }
}

