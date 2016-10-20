<?php

namespace Zob\Adapter;

/**
 * Interface AdapterInterface
 * @author stefanov.kalin@gmail.com
 */
interface AdapterInterface
{
    public function getConnection();
    public function run();
}
