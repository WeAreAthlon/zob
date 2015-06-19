<?php
    
namespace Zob\Objects;

interface IndexInterface
{
    public function getName();

    public function getField();

    public function getType();

    public function isUnique();
}
