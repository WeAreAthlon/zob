<?php
use Zob\Objects\Database;
use Zob\Adapters\MySql\MySql;

/**
 * @covers Zob\Objects\Database
 */
class DatabaseTest extends PHPUnit_Framework_TestCase
{
    protected static $connection;

    public static function setUpBeforeClass()
    {
        self::$connection = new MySql([
            'host' => 'localhost',
            'user' => 'root',
            'password' => ''
        ]);
    }

    /**
     * @covers Zob\Objects\Database::create
     */
    public function testCreate()
    {
        $db = new Database(self::$connection, 'test');
        $db->create();

        $this->assertTrue(self::$connection->databaseExists('test'));
    }

    /**
     * @covers Zob\Objects\Database::delete
     */
    public function testDelete()
    {
        $db = new Database(self::$connection, 'test');
        $db->delete();

        $this->assertFalse(self::$connection->databaseExists('test'));
    }
}

