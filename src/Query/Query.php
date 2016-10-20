<?php

namespace Zob\Query;

use Zob\Adapter\AdapterInterface;
use Zob\Query\QueryResult;

/**
 * Class Query
 * @author stefanov.kalin@gmail.com
 */
class Query implements QueryInterface
{
    private $adapter;
    private $returnType;

    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter, $returnType = null)
    {
        $this->adapter = $adapter;
        $this->returnType = $returnType;
    }

    /**
     * Filter records
     *
     * @return Query
     */
    public function where(array $params)
    {
        $this->options['filter'] = array_merge($this->filter, $params);

        return $this;
    }

    /**
     * Sort records
     *
     * @return Query
     */
    public function order(string $field, string $by)
    {
        $this->options['order'] = [$field, $by];

        return $this;
    }

    /**
     * Executes the query and return a resultset
     *
     * @return QueryResult
     */
    public function get() : QueryResult
    {
        return new QueryResult($this->run(), $this->returnType);
    }

    /**
     * Returns all passed params to bind
     *
     * @return void
     */
    public function getParams()
    {
        return null;
    }

    /**
     * Executes the query
     *
     * @return void
     */
    private function run()
    {
        return $this->adapter->run($this);
    }
}
