<?php
use Zob\Adapters\MySql\Builder;

/**
 * @covers Zob\Adapters\MySql\Builder
 */
class BuilderTest extends PHPUnit_Framework_TestCase
{
    protected $builder;
    
    protected function setUp()
    {
        $this->builder = new Builder();
    }

    /**
     * @covers Zob\Adapters\MySql\Builder::build
     */
    public function testBuild()
    {
    }
}

