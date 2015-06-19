<?php
use Zob\Objects\Field;

/**
 * @covers Zob\Objects\Field
 */
class FieldTest extends PHPUnit_Framework_TestCase
{
    protected static $field;

    public static function setUpBeforeClass()
    {
        self::$field = new Field([
            'name' => 'id',
            'type' => 'int',
            'length' => 10,
            'pk'    => true,
            'ai'    => true
        ]);

    }

    /**
     * @covers Zob\Objects\Table::getName
     */
    public function testGetName()
    {
        $this->assertEquals(self::$field->getName(), 'id');
    }

    /**
     * @covers Zob\Objects\Table::getType
     */
    public function testGetType()
    {
        $this->assertEquals(self::$field->getType(), 'int');
    }

    /**
     * @covers Zob\Objects\Table::getLength
     */
    public function testGetLength()
    {
        $this->assertEquals(self::$field->getLength(), 10);
    }

    /**
     * @covers Zob\Objects\Table::isRequired
     */
    public function testIsRequired()
    {
        $this->assertFalse(self::$field->isRequired());
    }
    
    /**
     * @covers Zob\Objects\Table::getDefault
     */
    public function testGetDefault()
    {
        $this->assertNull(self::$field->getDefault());
    }

    /**
     * @covers Zob\Objects\Table::isAutoIncrement
     */
    public function testIsAutoIncrement()
    {
        $this->assertTrue(self::$field->isAutoIncrement());
    }

    /**
     * @covers Zob\Objects\Table::isPrimaryKey
     */
    public function testIsPrimaryKey()
    {
        $this->assertTrue(self::$field->isPrimaryKey());
    }

    /**
     * @covers Zob\Objects\Table::validate
     */
    public function testValidate()
    {
    }
}

