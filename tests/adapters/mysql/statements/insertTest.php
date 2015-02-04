<?php
use Zob\Adapters\MySql\Statements\Insert;

/**
 * @covers Zob\Adapters\MySql\Statements\Insert
 */
class InsertTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers Zob\Adapters\MySql\Statements\Insert::toSql
     */
    public function testToSql()
    {
        $insert = new Insert('users', ['name', 'email', 'phone'], ['test', 'test@test.com', '123123123']);
        list($sql, $vars) = $insert->toSql();
        $this->assertEquals('INSERT INTO users (name, email, phone) VALUES (?, ?, ?)', $sql);
        $this->assertEquals(['test', 'test@test.com', '123123123'], $vars);
    }

    /**
     * @covers Zob\Adapters\MySql\Statements\Insert::toSql
     * @expectedException UnexpectedValueException
     */
    public function testToSqlWrongValues()
    {
        $insert = new Insert('users', ['name', 'email', 'phone'], ['test', '123123123']);
        list($sql, $vars) = $insert->toSql();
        $this->assertEquals('INSERT INTO users (name, email, phone) VALUES (?, ?)', $sql);
        $this->assertEquals(['test', '123123123'], $vars);
    }

    /**
     * @covers Zob\Adapters\MySql\Statements\Insert::toSql
     * @expectedException UnexpectedValueException
     */
    public function testToSqlWrongFields()
    {
        $insert = new Insert('users', ['name', 'email'], ['test', 'test@test.com', '123123123']);
        list($sql, $vars) = $insert->toSql();
        $this->assertEquals('INSERT INTO users (name, email) VALUES (?, ?, ?)', $sql);
        $this->assertEquals(['test', 'test@test.com', '123123123'], $vars);
    }
}

