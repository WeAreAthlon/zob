<?php
/**
 * Field object.
 *
 * @package    Zob
 * @subpackage Objects
 * @author     Kalin Stefanov <kalin@athlonsofia.com>
 * @copyright  Copyright (c) 2015, Zob
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace Zob\Objects;

/**
 * Object representation of a Database field
 */
class Field implements FieldInterface
{
    protected $table;

    /**
     * Field alias
     *
     * @var string
     * @access protected
     */

    protected $alias;
    /**
     * Field name
     *
     * @var string
     * @access protected
     */
    protected $name;

    /**
     * Field data type
     *
     * @var string
     * @access protected
     */
    protected $type;

    /**
     * Field data length
     *
     * @var int/string
     * @access protected
     */
    protected $length;

    /**
     * If true, the field must have a value
     *
     * @var bool
     * @access protected
     */
    protected $required = false;

    /**
     * Default value of the field
     *
     * @var mixed
     * @access protected
     */
    protected $default = null;

    /**
     * Autoincrement the field
     *
     * @var bool
     * @access protected
     */
    protected $ai = false;

    /**
     * Mark the field as Primary Key
     *
     * @var bool
     * @access protected
     */
    protected $pk = false;

    /**
     * Basic contructor
     *
     * @param array $options Options to initialize the field with
     *
     * @access public
     */
    public function __construct(array $options)
    {
        foreach(array_intersect_key($options, array_flip(['name', 'type', 'length', 'required', 'default', 'ai', 'pk'])) as $key=>$value) {
            if($key == 'length') {
                $value = (int) $value;
            }
            $this->{$key} = $value;
        }

        if($this->pk && !$this->ai) {
            $this->required = true;
        }
    }

    public function setTable(TableInterface $table)
    {
        $this->table = $table;
    }

    public function getTable()
    {
        return $this->table;
    }

    public function setAlias($alias)
    {
        $this->alias = $alias;
    }

    public function getAlias()
    {
        return $this->alias;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getLength()
    {
        return $this->length;
    }

    public function isRequired()
    {
        return $this->required;
    }

    public function getDefault()
    {
        return $this->default;
    }

    public function isAutoIncrement()
    {
        return $this->ai;
    }

    public function isPrimaryKey()
    {
        return $this->pk;
    }

    /**
     * Checks if the passed value is valid for the field
     *
     * @param mixed $value Value to be validated
     *
     * @access public
     *
     * @return false/string
     */
    public function validate($value)
    {
        if ($this->ai) {
            return false;
        }

        if ($this->required && strlen(trim($value)) === 0) {
            return 'NotEmpty';
        }

        if (strlen($value) > $this->length) {
            return 'TooLong';
        }
        
        return false;
    }

    public function beforeWrite($value) {}

    public function afterRead($value) {}
}

