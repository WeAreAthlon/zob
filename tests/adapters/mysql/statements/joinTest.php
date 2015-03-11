<?php
use Zob\Adapters\MySql\Statements\Join;

/**
 * @covers Zob\Adapters\MySql\Statements\Join
 */
class JoinTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers Zob\Adapters\MySql\Statements\Join::toSql
     */
    public function testToSql()
    {
        $join = new Join('users', 'tasks.id = users.id');
        list($sql) = $join->toSql();
        $this->assertEquals('LEFT JOIN users ON(tasks.id = users.id)', $sql);
    }
}

