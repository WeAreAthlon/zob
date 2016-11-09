<?php

namespace Zob\Schema;

/**
 * Interface FieldInterface
 * @author stefanov.kalin@gmail.com
 */
interface FieldInterface
{
    public function isPrimaryKey();
    public function isUnique();
    public function isAutoIncrement();
    public function isNotNull();
}
