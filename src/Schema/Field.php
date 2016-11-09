<?php

namespace Zob\Schema;

/**
 * Class Field
 * @author stefanov.kalin@gmail.com
 */
abstract class Field implements FieldInterface
{
    private $name;
    private $type;
    private $length;
    private $notNull;
    private $isPrimaryKey;
    private $isUnique;
    private $isAutcoIncrement;

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        foreach ($otions as $option => $value) {
            $this->option = $value;
        }
    }

    /**
     * Returns true if the field is a primary key
     *
     * @return bool
     */
    public function isPrimaryKey()
    {
        return $this->isPrimaryKey;
    }

    /**
     * Return true if the field value mus be an unique
     *
     * @return bool
     */
    public function isUnique()
    {
        return $this->isUnique;
    }

    /**
     * Return true if the field value is automatically incremented
     *
     * @return bool
     */
    public function isAutoIncrement()
    {
        return $this->isAutoIncrement;
    }

    /**
     * Return true if the field value can't be bull
     *
     * @return bool
     */
    public function isNotNull()
    {
        return $this->notNull;
    }
}
