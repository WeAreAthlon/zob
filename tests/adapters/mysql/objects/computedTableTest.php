<?php
use Zob\Objects\ComputedTable;
use Zob\Objects\Table;
use Zob\Objects\Field;
use Zob\Objects\LeftJoin;
use Zob\Objects\Condition;

/**
 * @covers Zob\Objects\ComputedTable
 */
class ComputedTableTest extends PHPUnit_Framework_TestCase
{
    protected static $computedTable;

    protected static $table1;

    protected static $table2;

    public static function setUpBeforeClass()
    {
        $fields1 = [
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

        self::$table1 = new Table('users', $fields1);

        $fields2 = [
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
            ]),
            new Field([
                'name' => 'user_id',
                'type' => 'int',
                'length' => 10
            ])
        ];

        self::$table2 = new Table('tasks', $fields2);

        self::$computedTable = new ComputedTable([self::$table1]);
    }

    /**
     * @covers Zob\Objects\ComputedTable::getTable
     */
    public function testGetTable()
    {
        $this->assertEquals(self::$computedTable->getTable(self::$table1->getName()), self::$table1);
    }

    /**
     * @covers Zob\Objects\ComputedTable::addTable
     * @depends testGetTable
     */
    public function testAddTable()
    {
        self::$computedTable->addTable(self::$table2);

        $this->assertEquals(self::$computedTable->getTable(self::$table2->getName()), self::$table2);
    }

    /**
     * @covers Zob\Objects\ComputedTable::addJoin
     */
    public function testAddJoin()
    {
        $join = new LeftJoin(self::$table1, self::$table2, [new Condition(self::$table1->getField('id'), '=' , self::$table2->getField('user_id'))]);

        self::$computedTable->addJoin($join);

        $this->assertEquals(self::$computedTable->getJoins()[0], $join);
    }
}

