<?php
use Zob\Adapters\MySql\Statements\Update;

/**
 * @covers Zob\Adapters\MySql\Statements\Update
 */
class UpdateTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers Zob\Adapters\MySql\Statements\Update::toSql
     */
    public function testToSql()
    {
        $update = new Update('users', ['name', 'email', 'phone'], ['test', 'test@test.com', '123123123']);
        list($sql, $vars) = $update->toSql();
        $this->assertEquals('UPDATE users SET name = ?, email = ?, phone = ?', $sql);
        $this->assertEquals(['test', 'test@test.com', '123123123'], $vars);

        $update = new Update('users', ['name' => 'test', 'email' => 'test@test.com', 'phone' => '123123123']);
        list($sql, $vars) = $update->toSql();
        $this->assertEquals('UPDATE users SET name = ?, email = ?, phone = ?', $sql);
        $this->assertEquals(['test', 'test@test.com', '123123123'], $vars);
    }

    /**
     * @covers Zob\Adapters\MySql\Statements\Update::toSql
     * @expectedException UnexpectedValueException
     */
    public function testToSqlWrongValues()
    {
        $update = new Update('users', ['name', 'email', 'phone'], ['test', '123123123']);
        list($sql, $vars) = $update->toSql();
        $this->assertEquals('UPDATE users SET name = ?, email = ?, phone = ?', $sql);
        $this->assertEquals(['test', '123123123'], $vars);
    }

    /**
     * @covers Zob\Adapters\MySql\Statements\Update::toSql
     * @expectedException UnexpectedValueException
     */
    public function testToSqlWrongFields()
    {
        $update = new Update('users', ['name', 'email'], ['test', 'test@test.com', '123123123']);
        list($sql, $vars) = $update->toSql();
        $this->assertEquals('UPDATE users SET name = ?, email = ?', $sql);
        $this->assertEquals(['test', 'test@test.com', '123123123'], $vars);
    }
}

