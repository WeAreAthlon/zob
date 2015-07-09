<?php

namespace Zob\Objects;

interface FieldInterface
{
    public function setTable(TableInterface $table);

    public function getTable();

    public function setAlias($alias);

    public function getAlias();

    public function getName();

    public function getType();

    public function getLength();

    public function validate($value);

    public function beforeWrite($value);

    public function afterRead($value);
}
