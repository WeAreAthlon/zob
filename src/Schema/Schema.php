<?php

namespace Zob\Schema;

/**
 * Class Schema
 * @author stefanov.kalin@gmail.com
 */
class Schema
{
    /**
     * @param array $schema
     */
    public function __construct(array $schema)
    {
        $this->schema = $schema;
    }
}
