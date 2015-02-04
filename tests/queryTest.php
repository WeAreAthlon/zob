<?php
use Zob\Query;
use Zob\Adapters\MySql\MySql;

/**
 * @covers Zob\Query
 */
class QueryTest extends PHPUnit_Framework_TestCase
{
    protected static $connection;

    public static function setUpBeforeClass()
    {
        self::$connection = new MySql([
            'host' => 'localhost',
            'name' => 'silla_test',
            'user' => 'root',
            'password' => ''
        ]);
    }

    protected function setUp()
    {
        $this->query = new Query(self::$connection);
    }

    /**
     * @covers Core\Modules\DB\Base\Query::select
     */
    public function testSelect()
    {
        $this->query->select();
        
        $this->assertEquals(self::$connection->builder->create('select'), $this->query->statement);
    }

    /**
     * @covers Core\Modules\DB\Base\Query::from
     */
    public function testFrom()
    {
        $this->query->from('users');
        $this->assertEquals(self::$connection->builder->create('from', 'users'), $this->query->from);
    }

    /**
     * @covers Core\Modules\DB\Base\Query::joins
     */
    public function testJoins()
    {
        $this->query->joins('users', 'tasks.id = users.id');
        $this->assertEquals(self::$connection->builder->create('join', 'users', 'tasks.id = users.id'), $this->query->joins[0]);

        $this->query->joins('category', 'tasks.category_id = category.id');
        $this->assertCount(2, $this->query->joins);
        $this->assertEquals(self::$connection->builder->create('join', 'category', 'tasks.category_id = category.id'), $this->query->joins[1]);
    }

    /**
     * @covers Core\Modules\DB\Base\Query::order
     */
    public function testOrder()
    {
        $this->query->order('id', 'asc');
        $order = self::$connection->builder->create('order', 'id', 'asc');
        $this->assertEquals($order, $this->query->order);

        $this->query->order('start_time', 'desc');
        $order->add('start_time', 'desc');
        $this->assertEquals($order, $this->query->order);

        $this->query->order('start_time', 'asc');
        $order->add('start_time', 'asc');
        $this->assertEquals($order, $this->query->order);
        $this->assertEquals(['ORDER BY id asc, start_time asc'], $this->query->order->toSql());
    }

    /**
     * @covers Core\Modules\DB\Base\Query::where
     */
    public function testWhere()
    {
        $this->query->where('id = 4');
        $where = self::$connection->builder->create('where', 'id = 4');
        $this->assertEquals($where, $this->query->where);

        $this->query->where(['category' => 'cat1']);
        $where->add(['category' => 'cat1']);
        $this->assertequals($where, $this->query->where);

        $this->query->where(['start_time' => ['$gt' => 39]]);
        $where->add(['start_time' => ['$gt' => 39]]);
        $this->assertEquals($where, $this->query->where);
        $this->assertEquals(['WHERE id = 4 AND category = ? AND (start_time > ?)', ['cat1', 39]], $this->query->where->toSql());
    }

    /**
     * @covers Core\Modules\DB\Base\Query::limit
     */
    public function testLimit()
    {
        $this->query->limit(4);
        $limit = self::$connection->builder->create('limit', 4);
        $this->assertEquals($limit, $this->query->limit);

        $this->query->limit(6, 2);
        $limit = self::$connection->builder->create('limit', 6, 2);
        $this->assertEquals($limit, $this->query->limit);
        $this->assertEquals(['LIMIT ? OFFSET ?', [6, 2]], $this->query->limit->toSql());
    }

    /**
     * @covers Core\Modules\DB\Base\Query::uniq
     */
    public function testUniq()
    {
        $this->query->select('id, category')->uniq();
        $select = self::$connection->builder->create('select', 'id, category');
        $select->uniq(true);
        $this->assertEquals($select, $this->query->statement);
        $this->assertEquals(['SELECT DISTINCT id, category'], $this->query->statement->toSql());
    }

    /**
     * @covers Core\Modules\DB\Base\Query::clean
     */
    public function testClean()
    {
    }
}

