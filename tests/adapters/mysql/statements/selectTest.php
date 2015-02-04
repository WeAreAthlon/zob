<?php
use Zob\Adapters\MySql\Statements\Select;

/**
 * @covers Zob\Adapters\MySql\Statements\Select
 */
class SelectTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers Zob\Adapters\MySql\Statements\Select::toSql
     */
    public function testToSql()
    {
        $select = new Select();
        list($sql) = $select->toSql();
        $this->assertEquals('SELECT *', $sql);

        $select = new Select(['id', 'type', 'start_time']);
        list($sql) = $select->toSql();
        $this->assertEquals('SELECT id, type, start_time', $sql);
    }

    /**
     * @covers Zob\Adapters\MySql\Statements\Select::uniq
     */
    public function testUniq()
    {
        $select = new Select();
        $select->uniq(true);
        list($sql) = $select->toSql();
        $this->assertEquals('SELECT DISTINCT *', $sql);
    }
}

