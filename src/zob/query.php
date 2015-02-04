<?php
/**
 * Base class Query.
 *
 * @package    Zob
 * @author     Kalin Stefanov <kalin@athlonsofia.com>
 * @copyright  Copyright (c) 2015, Zob
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace Zob;

/**
 * Class Query definition.
 */
class Query implements \ArrayAccess, \Countable, \Iterator
{
    protected $connection,
              $service,
              $items = array(),
              $joins = array(),
              $order,
              $where;

    public function __construct($connection)
    {
        $this->connection = $connection;

        if(class_exists('Core\Modules\DB\Service\Query')) {
            $this->service = new DB\Service\Query();
        }
    }

    public function __get($val)
    {
        if(in_array($val, ['statement', 'from', 'joins', 'where', 'group', 'having', 'order', 'limit'])) {
            return $this->$val;
        }
    }

    public function select($fields = '*')
    {
        $this->statement = $this->connection->builder->create('select', $fields);

        return $this;
    }

    public function delete()
    {
        $this->statement = new Delete();

        return $this;
    }

    public function from($source, $vars = [])
    {
        $this->from = $this->connection->builder->create('from', $source, $vars);

        return $this;
    }

    public function joins($with, $on)
    {
        $this->joins[] = $this->connection->builder->create('join', $with, $on);

        return $this;
    }

    public function group()
    {
        $this->group[] = $this->connection->builder->create('group');
        return $this;
    }

    public function order($field, $dir = 'ASC')
    {
        if($this->order) {
            $this->order->add($field, $dir);
        } else {
            $this->order = $this->connection->builder->create('order', $field, $dir);
        }

        return $this;
    }

    public function where()
    {
        $args = func_get_args();

        $vars = [];
        if(is_array($args[0])) {
            /* ['name' => 'test', 'category' => 2] */
            $conditions = $args[0];
        } else {
            /* 'name = ?' */
            $conditions = array_shift($args);
            $vars = $args;
        }

        if($this->where) {
            $this->where->add($conditions, $vars);
        } else {
            $this->where = $this->connection->builder->create('where', $conditions, $vars);
        }

        return $this;
    }

    public function limit($limit, $offset = 0)
    {
        $this->limit = $this->connection->builder->create('limit', $limit, $offset);

        return $this;
    }

    public function uniq()
    {
        $this->statement->uniq(true);

        return $this;
    }

    public function clean($statement)
    {
        unset($this->components[$statement]);

        return $this;
    }

    public function run()
    {
        $this->items = $this->connection->query($this);

        return $this->items;
    }

    /* ArrayAccess methods */

    /**
     * Sets the offset.
     *
     * @param mixed $offset Offset count.
     * @param mixed $value  Value.
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
    }

    /**
     * Retrieves the current offset.
     *
     * @param mixed $offset Offset.
     *
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        $this->run();

        return isset($this->items[$offset]) ? $this->items[$offset] : null;
    }

    /**
     * Checks whether an offset exists.
     *
     * @param integer $offset Offset.
     *
     * @return boolean
     */
    public function offsetExists($offset)
    {
        $this->run();

        return isset($this->items[$offset]);
    }

    /**
     * Unsets offset.
     *
     * @param mixed $offset Offset.
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        $this->run();

        unset($this->items[$offset]);
    }

    /* Countable methods */

    /**
     * Get count.
     *
     * @return integer
     */
    public function count()
    {
        return count($this->run());
    }

    /* Iterator methods */

    /**
     * Rewind method.
     *
     * @return mixed|void
     */
    public function rewind()
    {
        $this->run();

        return reset($this->items);
    }

    /**
     * Get current element method.
     *
     * @return mixed
     */
    public function current()
    {
        $this->run();

        return current($this->items);
    }

    /**
     * Get key method.
     *
     * @return mixed
     */
    public function key()
    {
        $this->run();

        return key($this->items);
    }

    /**
     * Get next method.
     *
     * @return mixed|void
     */
    public function next()
    {
        $this->run();

        return next($this->items);
    }

    /**
     * Validates element method.
     *
     * @return boolean
     */
    public function valid()
    {
        $this->run();

        return key($this->items) !== null;
    }
}

