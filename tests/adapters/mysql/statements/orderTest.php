<?php
use Zob\Adapters\MySql\Statements\Order;

/**
 * @covers Zob\Adapters\MySql\Statements\Order
 */
class OrderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers Zob\Adapters\MySql\Statements\Order::toSql
     */
    public function testToSql()
    {
        $order = new Order('id', 'asc');
        list($sql) = $order->toSql();
        $this->assertEquals('ORDER BY id asc', $sql);

        $order = new Order(['id' =>'asc', 'start_time' => 'desc']);
        list($sql) = $order->toSql();
        $this->assertEquals('ORDER BY id asc, start_time desc', $sql);
    }

    /**
     * @covers Zob\Adapters\MySql\Statements\Order::add
     */
    public function testAdd()
    {
        $order = new Order('id', 'asc');
        $order->add('start_time', 'desc');
        list($sql) = $order->toSql();
        $this->assertEquals('ORDER BY id asc, start_time desc', $sql);
    }

    /**
     * @covers Zob\Adapters\MySql\Statements\Order::remove
     */
    public function testRemove()
    {
        $order = new Order(['id' =>'asc', 'start_time' => 'desc']);
        $order->remove('start_time');
        list($sql) = $order->toSql();
        $this->assertEquals('ORDER BY id asc', $sql);
    }
}

