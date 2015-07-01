<?php

namespace Zob;

use Zob\Objects\TableInterface;

interface QueryInterface
{
    public function select(TableInterface $table);

    public function insert(TableInterface $table);

    public function update(TableInterface $table);

    public function delete(TableInterface $table);

    public function prepare();

    public function run();
}

