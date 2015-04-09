<?php
use Zob\Objects\Table;
use Zob\Objects\Field;
use Zob\Adapters\MySql\MySql;

/**
 * @covers Zob\Objects\Table
 */
class TableTest extends PHPUnit_Framework_TestCase
{
    protected static $connection;
    protected static $table;

    public static function setUpBeforeClass()
    {
        self::$connection = new MySql([
            'host' => 'localhost',
            'name' => 'silla_test',
            'user' => 'root',
            'password' => ''
        ]);

        self::$table = new Table(self::$connection, 'users', [
            [
                'name' => 'id',
                'type' => 'int',
                'length' => 10,
                'pk'    => true,
                'ai'    => true
            ],
            [
                'name' => 'name',
                'type' => 'varchar',
                'length' => 255
            ]
        ]);
    }

    /**
     * @covers Zob\Objects\Table::create
     * @covers Zob\Objects\Table::get
     */
    public function testTableCreation()
    {
        self::$table->create();
        $table = Table::get(self::$connection, 'users');

        $this->assertEquals(self::$table, $table);
    }

    /**
     * @covers Zob\Objects\Table::create
     * @covers Zob\Objects\Table::get
     * @expectedException LogicException
     */
    public function testTableCreationOfExistingTable()
    {
        self::$table->create();
    }

    /**
     * @covers Zob\Objects\Table::addField
     * @covers Zob\Objects\Table::get
     * @depends testTableCreation
     */
    public function testAddField()
    {
        $field = [
            'name' => 'email',
            'type' => 'varchar',
            'length' => 150,
            'required' => true
        ];

        self::$table->addField($field);

        $table = Table::get(self::$connection, 'users');
        $this->assertEquals(self::$table, $table);
    }

    /**
     * @covers Zob\Objects\Table::addField
     * @covers Zob\Objects\Table::get
     * @depends testAddField
     * @expectedException LogicException
     */
    public function testAddDuplicateField()
    {
        $field = [
            'name' => 'email',
            'type' => 'varchar',
            'length' => 150,
            'required' => true
        ];

        self::$table->addField($field);
    }

    /**
     * @covers Zob\Objects\Table::changeField
     * @covers Zob\Objects\Table::get
     * @depends testAddField
     */
    public function testChangeField()
    {
        $field = [
            'name' => 'email',
            'type' => 'varchar',
            'length' => 250,
            'required' => true
        ];

        self::$table->changeField($field['name'], $field);

        $table = Table::get(self::$connection, 'users');
        $this->assertEquals(self::$table, $table);
   }

    /**
     * @covers Zob\Objects\Table::deleteField
     * @covers Zob\Objects\Table::get
     * @depends testAddField
     */
    public function testDeleteField()
    {
        self::$table->deleteField('email');

        $table = Table::get(self::$connection, 'users');
        $this->assertEquals(self::$table, $table);
    }

    /**
     * @covers Zob\Objects\Table::addIndex
     * @covers Zob\Objects\Table::get
     * @depends testTableCreation
     */
    public function testAddIndex()
    {
        $index = [
            'name' => 'name_idx',
            'field' => 'name'
        ];

        self::$table->addIndex($index);

        $table = Table::get(self::$connection, 'users');
        $this->assertEquals(self::$table, $table);
    }

    /**
     * @covers Zob\Objects\Table::changeIndex
     * @covers Zob\Objects\Table::get
     * @depends testAddIndex
     */
    public function testChangeIndex()
    {
        $index = [
            'name' => 'name_2_idx',
            'field' => 'name',
            'unique' => true
        ];

        self::$table->changeIndex('name_idx', $index);

        $table = Table::get(self::$connection, 'users');
        $this->assertEquals(self::$table, $table);
    }

    /**
     * @covers Zob\Objects\Table::deleteIndex
     * @covers Zob\Objects\Table::get
     * @depends testChangeIndex
     */
    public function testDeleteIndex()
    {
        self::$table->deleteIndex('name_2_idx');

        $table = Table::get(self::$connection, 'users');
        $this->assertEquals(self::$table, $table);
    }

    /**
     * @covers Zob\Objects\Table::delete
     * @depends testTableCreation
     */
    public function testDelete()
    {
        self::$table->delete();

        $this->assertFalse(self::$connection->tableExists('users'));
    }
}

