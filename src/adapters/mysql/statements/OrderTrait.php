<?php

namespace Zob\Adapters\MySql\Statements;

trait OrderTrait
{
    private $order;

    public function order($by, $direction)
    {
        if (!$this->order) {
            $this->order = new Order($by, $direction);
        } else {
            $this->order->add($by, $direction);
        }

        return $this;
    }

    public function removeOrder($by)
    {
        if ($by) {
            $this->order->remove($by);
        } else {
            $this->order = null;
        }

        return $this;
    }

    public function reorder($by, $direction)
    {
        $this->order = new Order($by, $direction);

        return $this;
    }
}

