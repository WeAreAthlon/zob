<?php

use Zob\Adapters\MySql\Statements\Delete;
use Zob\Objects;

/**
 * @covers Zob\Adapters\MySql\Statements\Delete
 */
class DeleteTest extends PHPUnit_Framework_TestCase
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
     * @covers Zob\Adapters\MySql\Statements\Delete::toSql
     */
    public function testToSql()
    {
        $delete = new Delete(self::$users);
        list($sql, $vars) = $delete->toSql();
        $this->assertEquals('DELETE FROM users', $sql);
        $this->assertEquals([], $vars);

        $delete = new Delete(self::$users, true);
        list($sql, $vars) = $delete->toSql();
        $this->assertEquals('DELETE IGNORE FROM users', $sql);
    }
}

