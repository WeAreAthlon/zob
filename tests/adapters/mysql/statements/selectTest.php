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

    protected static $tasks;

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

        self::$tasks = new Objects\Table('tasks', [
            new Objects\Field([
                'name' => 'id',
                'type' => 'int',
                'length' => 10,
                'pk'    => true,
                'ai'    => true
            ]),
            new Objects\Field([
                'name' => 'title',
                'type' => 'varchar',
                'length' => 255
            ]),
            new Objects\Field([
                'name' => 'user_id',
                'type' => 'int',
                'length' => 10,
                'required' => true
            ]),
            new Objects\Field([
                'name' => 'description',
                'type' => 'text'
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
     * @covers Zob\Adapters\MySql\Statements\Select::toSql
     */
    public function testToSqlWithJoin()
    {
        $table = self::$users->join(self::$tasks->getPartial(['title', 'user_id', 'description']), [new Objects\Condition(self::$users->getField('id'), '=', self::$tasks->getField('user_id'))], 'left');
        $select = new Select($table);

        list($sql) = $select->toSql();
        $this->assertEquals('SELECT users.id, users.name, users.email, users.created_at, tasks.title, tasks.user_id, tasks.description FROM users LEFT JOIN tasks ON (users.id = tasks.user_id)', $sql);

        $table = self::$users->join(self::$tasks->getPartial(['tasks_id' => 'id' ,'title', 'user_id', 'description']), [new Objects\Condition(self::$users->getField('id'), '=', self::$tasks->getField('user_id'))], 'left');
        $select = new Select($table);

        list($sql) = $select->toSql();
        $this->assertEquals('SELECT users.id, users.name, users.email, users.created_at, tasks.id AS tasks_id, tasks.title, tasks.user_id, tasks.description FROM users LEFT JOIN tasks ON (users.id = tasks.user_id)', $sql);

        $table = self::$users->getPartial(['name', 'email'])->join(self::$tasks->getPartial(['tasks_id' => 'id' ,'title', 'user_id', 'description']), [new Objects\Condition(self::$users->getField('id'), '=', self::$tasks->getField('user_id'))], 'left');
        $select = new Select($table);

        list($sql) = $select->toSql();
        $this->assertEquals('SELECT users.name, users.email, tasks.id AS tasks_id, tasks.title, tasks.user_id, tasks.description FROM users LEFT JOIN tasks ON (users.id = tasks.user_id)', $sql);
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

