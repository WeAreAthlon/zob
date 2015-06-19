<?php
use Zob\Objects\Index;

/**
 * @covers Zob\Objects\Index
 */
class IndexTest extends PHPUnit_Framework_TestCase
{
    protected static $index;

    public static function setUpBeforeClass()
    {
        self::$index = new Index([
            'name' => 'name_idx',
            'field' => 'name'
        ]);
    }

    /**
     * @covers Zob\Objects\Index::getName
     */
    public function testGetName()
    {
        $this->assertEquals(self::$index->getName(), 'name_idx');

        $index = new Index(['field' => 'email']);

        $this->assertEquals($index->getName(), 'email_idx');
    }

    /**
     * @covers Zob\Objects\Index::getField
     */
    public function testGetField()
    {
        $this->assertEquals(self::$index->getField(), 'name');
    }

    /**
     * @covers Zob\Objects\Index::getType
     */
    public function testGetType()
    {
        $this->assertEquals(self::$index->getType(), 'BTREE');
    }

    /**
     * @covers Zob\Objects\Index::isUnique
     */
    public function testIsUnique()
    {
        $this->assertFalse(self::$index->isUnique());
    }

    /**
     * @covers Zob\Objects\Index::getLength
     */
    public function testGetLength()
    {
        $this->assertNull(self::$index->getLength());
    }
}

