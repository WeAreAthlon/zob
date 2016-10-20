<?php

namespace Zob\Query;

/**
 * Class QueryResult
 * @author stefanov.kalin@gmail.com
 */
class QueryResult
{
    private $collection = [];

    /**
     * @param mixed array $resultset
     */
    public function __construct(array $resultSet, $returnType = null)
    {
        $this->collection = $this->transformResults(
            $resultSet,
            $returnType ?? \stdClass::class
        );
    }

    /**
     * Crete objects from a resultset
     *
     * @return void
     */
    private function transformResults($elements, $class)
    {
        return array_map(function ($element) use ($class) {
            return new $class($element);
        }, $elements);
    }
}
