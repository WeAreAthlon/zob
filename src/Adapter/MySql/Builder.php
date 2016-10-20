<?php

namespace Zob\Adapter\MySql;

/**
 * Class Builder
 * @author stefanov.kalin@gmail.com
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
     * Builds a SQL statement to retrieve database schema
     *
     * @return string
     */
    public function getSchema()
    {
        return 'SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = ?';
    }

    /**
     * Build a SQL statement to retrieve table schema
     *
     * @return string
     */
    public function getTableSchema()
    {
        return 'SELECT COLUMN_NAME, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH, IS_NULLABLE, EXTRA, COLUMN_DEFAULT, COLUMN_KEY, COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = ? AND table_schema = ?';
    }
}
