<?php

use Zob\Adapters\MySql\Statements\Select;
use Zob\Adapters\MySql\Statements\Where;
use Zob\Objects;

/**
 * @covers Zob\Adapters\MySql\Statements\Select
 */
class SelectTest extends PHPUnit_Framework_TestCase
{
    protected static $users;

    public static function setUpBeforeClass()
    {
        self::$users = new Objects\Table('users', [
            new Objects\Field([
                'name' => 'id',
                'type' => 'int',
                'length' => 10,
                'pk'    => true,
                'ai'    => true
            ]),
            new Objects\Field([
                'name' => 'name',
                'type' => 'varchar',
                'length' => 255
            ]),
            new Objects\Field([
                'name' => 'email',
                'type' => 'varchar',
                'length' => 255,
                'required' => true
            ]),
            new Objects\Field([
                'name' => 'created_at',
                'type' => 'datetime'
            ])
        ]);
    }

    /**
     * @covers Zob\Adapters\MySql\Statements\Select::where
     */
    public function testWhere()
    {
        $select = new Select(self::$users);
        $cond = ['email = ? AND name = ?', ['test@email.com', 'test user']];
        $select->where($cond);

        $where = new Where($cond);
        $this->assertEquals($select->getWhere(), $where);

        $cond2 = ['id > ?', [4]];
        $select->where($cond2);
        $where->add($cond2);

        $this->assertEquals($select->getWhere(), $where);
    }

    /**
     * @covers Zob\Adapters\MySql\Statements\Select::toSql
     */
    public function testToSql()
    {
        $select = new Select(self::$users);
        list($sql) = $select->toSql();
        $this->assertEquals('SELECT users.id, users.name, users.email, users.created_at FROM users', $sql);
    }

    /**
     * @covers Zob\Adapters\MySql\Statements\Select::uniq
     */
    public function testUniq()
    {
        $select = new Select(self::$users);
        $select->uniq(true);
        list($sql) = $select->toSql();
        $this->assertEquals('SELECT DISTINCT users.id, users.name, users.email, users.created_at FROM users', $sql);
    }
}

