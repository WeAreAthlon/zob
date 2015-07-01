<?php

use Zob\Adapters\MySql\Statements\Update;
use Zob\Objects;

/**
 * @covers Zob\Adapters\MySql\Statements\Update
 */
class UpdateTest extends PHPUnit_Framework_TestCase
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
     * @covers Zob\Adapters\MySql\Statements\Update::toSql
     */
    public function testToSql()
    {
        $update = new Update(self::$users, ['name' => 'test', 'email' => 'test@test.com', 'phone' => '123123123']);
        list($sql, $vars) = $update->toSql();
        $this->assertEquals('UPDATE users SET name = ?, email = ?, phone = ?', $sql);
        $this->assertEquals(['test', 'test@test.com', '123123123'], $vars);
    }
}

