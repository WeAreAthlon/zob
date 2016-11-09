<?php

namespace Zob;

use Zob\Adapter\AdapterInterface;
use Zob\Schema\Schema;

/**
 * Class Model
 * @author stefanov.kalin@gmail.com
 */
class Model
{
    protected static $tableName;

    protected static $schema;

    /**
     * @param mixed $dependencies
     */
    public function __construct($dependencies)
    {
        $this->dependencies = $dependencies;
    }

    /**
     * Sets the DB adapter
     *
     * @return void
     */
    public static function setAdapter(AdapterInterface $adapter)
    {
        static::$adapter = $adapter;
    }

    /**
     * Returns model schema
     *
     * @return Schema
     */
    public static function getSchema() : Schema
    {
        if (!static::$schema) {
            static::$schema = new Schema(
                static::$adapter->getSchema(static::$tableName),
                static::fields()
            );
        }

        return static::$schema;  

    }

    /**
     * Finds record by primary key
     *
     * @return void
     */
    public static function find($key)
    {
        $query = new Query(static::$adapter);

        return $query->where([static::$primaryField => $key])->get();
    }
}
