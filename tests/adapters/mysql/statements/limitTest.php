<?php
use Zob\Adapters\MySql\Statements\Limit;

/**
 * @covers Zob\Adapters\MySql\Statements\Limit
 */
class LimitTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers Zob\Adapters\MySql\Statements\Limit::toSql
     */
    public function testToSql()
    {
        $limit = new Limit(5);
        list($sql, $vars) = $limit->toSql();
        $this->assertEquals('LIMIT ?', $sql);
        $this->assertEquals([5], $vars);

        $limit = new Limit(5, 10);
        list($sql, $vars) = $limit->toSql();
        $this->assertEquals('LIMIT ? OFFSET ?', $sql);
        $this->assertEquals([5, 10], $vars);
    }
}

