<?php

namespace Zob\Schema;

/**
 * Class Table
 * @author stefanov.kalin@gmail.com
 */
class Table
{
    public $name;

    private $fields = [];

    /**
     * @param mixed
     */
    public function __construct()
    {
    }
}
