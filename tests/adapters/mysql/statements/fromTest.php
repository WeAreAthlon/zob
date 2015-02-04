<?php
use Zob\Adapters\MySql\Statements\From;

/**
 * @covers Zob\Adapters\MySql\Statements\From
 */
class FromTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers Zob\Adapters\MySql\Statements\From::toSql
     */
    public function testToSql()
    {
        $from = new From('users');
        list($sql, $vars) = $from->toSql();
        $this->assertEquals('FROM users', $sql);
    }

    /**
     * @covers Zob\Adapters\MySql\Statements\From::toSql
     */
    public function testToSqlWithSubquery()
    {
        $from = new From('(SELECT * FROM tasks WHERE id > ?)', [10]);
        list($sql, $vars) = $from->toSql();
        $this->assertEquals('FROM (SELECT * FROM tasks WHERE id > ?)', $sql);
        $this->assertEquals([10], $vars);
    }
}

