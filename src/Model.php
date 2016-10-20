<?php

namespace Zob;

use Zob\Adapter\AdapterInterface;

/**
 * Class Model
 * @author stefanov.kalin@gmail.com
 */
class Model
{
    protected static $tableName;

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
     * Finds record by primary key
     *
     * @return void
     */
    public static function find($id)
    {
        $query = new Query(static::$adapter);

        return $query->where([static::$primaryField => $id])->get();
    }
}