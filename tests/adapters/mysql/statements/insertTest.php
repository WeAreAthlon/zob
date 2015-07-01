<?php

use Zob\Adapters\MySql\Statements\Insert;
use Zob\Objects;

/**
 * @covers Zob\Adapters\MySql\Statements\Insert
 */
class InsertTest extends PHPUnit_Framework_TestCase
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
                'name' => 'phone',
                'type' => 'varchar',
                'length' => 20
            ])
        ]);
    }

    /**
     * @covers Zob\Adapters\MySql\Statements\Insert::toSql
     */
    public function testToSql()
    {
        $insert = new Insert(self::$users, ['name' => 'test', 'email' => 'test@test.com', 'phone' => '123123123']);
        list($sql, $vars) = $insert->toSql();
        $this->assertEquals('INSERT INTO users (name, email, phone) VALUES (?, ?, ?)', $sql);
        $this->assertEquals(['test', 'test@test.com', '123123123'], $vars);

        $insert = new Insert(self::$users, ['id' => 33, 'name' => 'test', 'email' => 'test@test.com', 'phone' => '123123123']);
        list($sql, $vars) = $insert->toSql();
        $this->assertEquals('INSERT INTO users (name, email, phone) VALUES (?, ?, ?)', $sql);
        $this->assertEquals(['test', 'test@test.com', '123123123'], $vars);

        $insert = new Insert(self::$users, ['id' => 33, 'name' => 'test', 'phone' => '123123123']);
        list($sql, $vars) = $insert->toSql();
        $this->assertEquals('INSERT INTO users (name, email, phone) VALUES (?, ?, ?)', $sql);
        $this->assertEquals(['test', null, '123123123'], $vars);
    }
}

