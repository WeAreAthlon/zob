<?php
    
namespace Zob\Adapters\MySql\Statements;

trait WhereTrait
{
    private $where;

    public function where($conditions)
    {
        if (!$this->where) {
            $this->where = new Where($conditions);
        } else {
            $this->where->add($conditions);
        }

        return $this;
    }
}

