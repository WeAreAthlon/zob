<?php
use Zob\Objects\Table;
use Zob\Adapters\MySql\MySql;

/**
 * @covers Zob\Objects\Table
 */
class DatabaseTest extends PHPUnit_Framework_TestCase
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

    /**
     * @covers Zob\Objects\Table::get
     */
    public function testGet()
    {
        /*$db = Table::get(self::$connection, 'test');
        $db->create();*/
    }

    /**
     * @covers Zob\Objects\Table::create
     */
    public function testCreate()
    {
        $table = new Table(self::$connection, 'test', [
            [
                'name' => 'id',
                'type' => 'int',
                'length' => 10,
                'pk'    => true,
                'ai'    => true
            ],
            [
                'name' => 'title',
                'type' => 'varchar',
                'length' => 255
            ]
        ]);

        $table->create();
    }

    /**
     * @covers Zob\Objects\Table::delete
     */
    public function testDelete()
    {
        $db = new Table(self::$connection, 'test');
        $db->delete();
    }
}

