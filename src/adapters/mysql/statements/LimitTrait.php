<?php

namespace Zob\Adapters\MySql\Statements;

trait LimitTrait
{
    private $limit;

    public function limit($limit, $offset = 0)
    {
        $this->limit = new Limit($limit, $offset);

        return $this;
    }
}

