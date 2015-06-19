<?php

namespace Zob\Objects;

interface TableInterface
{
    public function getName();

    public function getField($fieldName);

    public function getFields();

    public function addField(FieldInterface $field);

    public function removeField($fieldName);
}

