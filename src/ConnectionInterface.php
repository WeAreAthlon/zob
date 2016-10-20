<?php

namespace Zob;

/**
 * Interface ConnectionInterface
 * @author stefanov.kalin@gmail.com
 */
interface ConnectionInterface
{
    public function getSchemaName();

    public function query(string $sql, array $params);

    public function execute(string $sql, array $params);
}
