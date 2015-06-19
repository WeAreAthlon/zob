<?php

namespace Zob\Objects;

interface FieldInterface
{
    public function getName();

    public function getType();

    public function getLength();

    public function validate($value);
}
