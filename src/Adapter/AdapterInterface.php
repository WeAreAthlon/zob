<?php

namespace Zob\Adapter;

/**
 * Interface AdapterInterface
 * @author kalin.stefanov@gmail.com
 */
interface AdapterInterface
{
    public function getConnection();
    public function run();
}
