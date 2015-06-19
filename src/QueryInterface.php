<?php

namespace Zob;

interface QueryInterface
{
    public function select();

    public function insert();

    public function update();

    public function delete();

    public function from();

    public function joins();

    public function group();

    public function order();

    public function limit();

    public function where();

    public function prepare();
}

