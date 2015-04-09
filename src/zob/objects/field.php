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
class Field
{
    /**
     * Field name
     *
     * @var string
     * @access public
     */
    public $name;

    /**
     * Field data type
     *
     * @var string
     * @access public
     */
    public $type;

    /**
     * Field data length
     *
     * @var int/string
     * @access public
     */
    public $length;

    /**
     * If true, the field must have a value
     *
     * @var bool
     * @access public
     */
    public $required = false;

    /**
     * Default value of the field
     *
     * @var mixed
     * @access public
     */
    public $default = null;

    /**
     * Autoincrement the field
     *
     * @var bool
     * @access public
     */
    public $ai = false;

    /**
     * Mark the field as Primary Key
     *
     * @var bool
     * @access public
     */
    public $pk = false;

    /**
     * Basic contructor
     *
     * @param array $options Options to initialize the field with
     *
     * @access public
     */
    public function __construct(array $options)
    {
        foreach($options as $key=>$option)
        {
            $this->{$key} = $option;
        }

        if($this->pk) {
            $this->required = true;
        }
    }

    /**
     * Checks if the passed value is valid for the field
     *
     * @param mised $value Value to be validated
     *
     * @access public
     *
     * @return array
     */
    public function validate($value)
    {
        $errors = [];
        /*@TODO write validation code */

        return $errors;
    }
}

