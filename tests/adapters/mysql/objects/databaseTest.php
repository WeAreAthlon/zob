<?php
use Zob\Objects\Database;
use Zob\Objects\Table;
use Zob\Objects\Field;
use Zob\Adapters\MySql\MySql;

/**
 * @covers Zob\Objects\Database
 */
class DatabaseTest extends PHPUnit_Framework_TestCase
{
    protected static $database;
    protected static $usersTable;

    public static function setUpBeforeClass()
    {
        self::$usersTable = new Table('users', [
            new Field([
                'name' => 'id',
                'type' => 'int',
                'length' => 10,
                'pk'    => true,
                'ai'    => true
            ]),
            new Field([
                'name' => 'name',
                'type' => 'varchar',
                'length' => 255
            ])
        ]);

        self::$database = new Database('test', [self::$usersTable]);
    }

    /**
     * @covers Zob\Objects\Database::getName
     */
    public function testGetName()
    {
        $this->assertEquals(self::$database->getName(), 'test');
    }

    /**
     * @covers Zob\Objects\Database::getCharacterSet
     */
    public function testGetCharacterSet()
    {
        $this->assertEquals(self::$database->getCharacterSet(), 'utf8');
    }

    /**
     * @covers Zob\Objects\Database::setCharacterSet
     */
    public function testSetCharacterSet()
    {
        self::$database->setCharacterSet('cp1251');

        $this->assertEquals(self::$database->getCharacterSet(), 'cp1251');
    }

    /**
     * @covers Zob\Objects\Database::getCollation
     */
    public function testGetCollation()
    {
        $this->assertEquals(self::$database->getCollation(), 'utf8_general_ci');
    }

    /**
     * @covers Zob\Objects\Database::setCollation
     */
    public function testSetCollation()
    {
        self::$database->setCollation('cp1251_general_ci');

        $this->assertEquals(self::$database->getCollation(), 'cp1251_general_ci');
    }

    /**
     * @covers Zob\Objects\Database::getTable
     */
    public function testGetTable()
    {
        $this->assertEquals(self::$database->getTable('users'), self::$usersTable);
    }

    /**
     * @covers Zob\Objects\Database::addTable
     * @depends testGetTable
     */
    public function testAddTable()
    {
        $table = new Table('users2', [
            new Field([
                'name' => 'id',
                'type' => 'int',
                'length' => 10,
                'pk'    => true,
                'ai'    => true
            ]),
            new Field([
                'name' => 'name',
                'type' => 'varchar',
                'length' => 255
            ])
        ]);

        self::$database->addTable($table);

        $this->assertEquals(self::$database->getTable('users2'), $table);
    }

    /**
     * @covers Zob\Objects\Database::removeTable
     * @depends testAddTable
     */
    public function testRemoveTable()
    {
        $this->assertTrue(self::$database->removeTable('users2'));
        $this->assertFalse(self::$database->removeTable('users22'));
    }

    /**
     * @covers Zob\Objects\Database::getTables
     */
    public function testGetTables()
    {
    }
}

