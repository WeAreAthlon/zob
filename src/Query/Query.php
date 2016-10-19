<?php

namespace Zob\Query;

use Zob\Adapter\AdapterInterface;

/**
 * Class Query
 * @author kalin.stefanov@gmail.com
 */
class Query implements QueryInterface
{
    private $adapter;

    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Executes the query
     *
     * @return void
     */
    private function run()
    {
        $this->adapter->run($this);
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
}
