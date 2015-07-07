<?php

use Zob\Objects\Table;
use Zob\Objects\Field;
use Zob\Objects\Index;
use Zob\Objects\LeftJoin;
use Zob\Objects\Condition;

/**
 * @covers Zob\Objects\Table
 */
class TableTest extends PHPUnit_Framework_TestCase
{
    protected static $table;

    protected static $fields;

    public static function setUpBeforeClass()
    {
        self::$fields = [
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
        ];

        self::$table = new Table('users', self::$fields);
    }

    /**
     * @covers Zob\Objects\Table::getName
     */
    public function testGetName()
    {
        $this->assertEquals(self::$table->getName(), 'users');
    }

    /**
     * @covers Zob\Objects\Table::getFields
     */
    public function testGetFields()
    {
        $this->assertEquals(array_values(self::$table->getFields()), self::$fields);
    }

    /**
     * @covers Zob\Objects\Table::addField
     */
    public function testAddField()
    {
        $field = new Field([
            'name' => 'email',
            'type' => 'varchar',
            'length' => 150,
            'required' => true
        ]);

        self::$table->addField($field);

        $this->assertEquals(self::$table->getField('email'), $field);
    }

    /**
     * @covers Zob\Objects\Table::removeField
     * @depends testAddField
     */
    public function testRemoveField()
    {
        $this->assertTrue(self::$table->removeField('email'));
        $this->assertFalse(self::$table->removeField('email2'));
    }

    /**
     * @covers Zob\Objects\Table::addIndex
     */
    public function testAddIndex()
    {
        $index = new Index([
            'name' => 'name_idx',
            'field' => 'name'
        ]);

        self::$table->addIndex($index);

        $this->assertEquals(self::$table->getIndex('name_idx'), $index);
    }

    /**
     * @covers Zob\Objects\Table::removeIndex
     * @depends testAddIndex
     */
    public function testRemoveIndex()
    {
        $this->assertTrue(self::$table->removeIndex('name_idx'));
        $this->assertFalse(self::$table->removeIndex('name_idx_2'));
    }

    /**
     * @covers Zob\Objects\Table::removeIndex
     * @depends testAddIndex
     */
    public function testGetPartial()
    {
        $parital = self::$table->getPartial(['name' => 'tttt', 'description']);
    }

    /**
     * @covers Zob\Objects\Table::testJoin
     */
    public function testJoin()
    {
        $tasks = new Table('tasks', [
            new Field([
                'name' => 'id',
                'type' => 'int',
                'length' => 10,
                'pk'    => true,
                'ai'    => true
            ]),
            new Field([
                'name' => 'title',
                'type' => 'varchar',
                'length' => 255
            ]),
            new Field([
                'name' => 'user_id',
                'type' => 'int',
                'length' => 10,
                'required' => true
            ]),
            new Field([
                'name' => 'description',
                'type' => 'text'
            ]),
            new Field([
                'name' => 'created_at',
                'type' => 'datetime'
            ])
        ]);

        self::$table->join($tasks, [new Condition(self::$table->getField('id'), '=', $tasks->getField('user_id'))], 'left');
        $conditions = [new Condition(self::$table->getField('id'), '=', $tasks->getField('user_id'))];
        $join = new LeftJoin($conditions);
        
        $this->assertEquals($conditions, $join->getConditions());
    }
}

