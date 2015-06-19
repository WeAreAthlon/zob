<?php

namespace Zob\Objects;

class Condition
{
    private $field;

    private $operator;

    private $value;

    public function __construct(FieldInterface $field, $operator, $value)
    {
        $this->field = $field;
        $this->operator = $operator;
        $this->value = $value;
    }
    
    public function get()
    {
        return [
            'field'     => $this->field,
            'operator'  => $this->operator,
            'value'     => $this->value
        ];
    }
}

