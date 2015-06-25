<?php
use Zob\Adapters\MySql\Statements\Where;

/**
 * @covers Zob\Adapters\MySql\Statements\Where
 */
class WhereTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers Zob\Adapters\MySql\Statements\Where::toSql
     */
    public function testToSql()
    {
        $where = new Where('category = ? AND is_active = ?', [1, 0]);
        list($sql, $vars) = $where->toSql();
        $this->assertEquals('WHERE category = ? AND is_active = ?', $sql);
        $this->assertEquals([1, 0], $vars);
    }

    /**
     * @covers Zob\Adapters\MySql\Statements\Where::add
     */
    public function testAdd()
    {
        $where = new Where('category = ? AND is_active = ?', [1, 0]);
        $where->add([
            'id' => [
                '$gt' => 3
            ]
        ]);
        list($sql, $vars) = $where->toSql();
        $this->assertEquals('WHERE category = ? AND is_active = ? AND (id > ?)', $sql);
        $this->assertEquals([1, 0, 3], $vars);
    }

    /**
     * @covers Zob\Adapters\MySql\Statements\Where::toSql
     */
    public function testToSqlPassingAssociativeArray()
    {
        $where = new Where(['category' => 1, 'is_active' => 0]);
        list($sql, $vars) = $where->toSql();
        $this->assertEquals('WHERE category = ? AND is_active = ?', $sql);
        $this->assertEquals([1, 0], $vars);

        $where = new Where(['category' => 1, 'is_active' => 0, 'type' => ['free', 'promo']]);
        list($sql, $vars) = $where->toSql();
        $this->assertEquals('WHERE category = ? AND is_active = ? AND type IN(?, ?)', $sql);
        $this->assertEquals([1, 0, 'free', 'promo'], $vars);
    }

    /**
     * @covers Zob\Adapters\MySql\Statements\Where::toSql
     */
    public function testToSqlPassingComplexAssociativeArray()
    {
        $where = new Where([
            'id' => [
                '$gt' => 3
            ],
            'category' => 1,
            'is_active' => 0
        ]);

        list($sql, $vars) = $where->toSql();
        $this->assertEquals('WHERE (id > ?) AND category = ? AND is_active = ?', $sql);
        $this->assertEquals([3, 1, 0], $vars);

        $where = new Where([
            'id' => [
                '$gt' => 3,
                '$lte' => 10
            ],
            'category' => 1,
            'is_active' => 0
        ]);

        list($sql, $vars) = $where->toSql();
        $this->assertEquals('WHERE (id > ? AND id <= ?) AND category = ? AND is_active = ?', $sql);
        $this->assertEquals([3, 10, 1, 0], $vars);

        $where = new Where([
            'id' => [
                '$gte' => 5,
                '$neq' => 23
            ],
            'category' => [
                '$eq' => 1
            ],
            'is_active' => 0
        ]);

        list($sql, $vars) = $where->toSql();
        $this->assertEquals('WHERE (id >= ? AND id != ?) AND (category = ?) AND is_active = ?', $sql);
        $this->assertEquals([5, 23, 1, 0], $vars);
    }
}

