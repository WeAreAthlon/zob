<?php
use Zob\Adapters\MySql\Statements\Delete;

/**
 * @covers Zob\Adapters\MySql\Statements\Delete
 */
class DeleteTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers Zob\Adapters\MySql\Statements\Delete::toSql
     */
    public function testToSql()
    {
        $delete = new Delete('users');
        list($sql, $vars) = $delete->toSql();
        $this->assertEquals('DELETE FROM users', $sql);
        $this->assertEquals([], $vars);

        $delete = new Delete('users', true);
        list($sql, $vars) = $delete->toSql();
        $this->assertEquals('DELETE IGNORE FROM users', $sql);
    }
}

