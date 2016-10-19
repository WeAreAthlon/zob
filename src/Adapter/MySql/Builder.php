<?php

namespace Zob\Adapter\MySql;

/**
 * Class Builder
 * @author kalin.stefanov@gmail.com
 */
class Builder
{
    /**
     * Builds an SQL statement
     *
     * @return strung
     */
    public function build(QueryInterface $query) : string
    {
        return null;
    }

    /**
     * Builds an SQL statement to retrieve database schema
     *
     * @return void
     */
    public function getSchema()
    {
        return 'SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = ?';
    }
    
}
