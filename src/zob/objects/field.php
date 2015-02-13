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

class Field
{
    public $name,
           $type,
           $length,
           $required = false,
           $default = null,
           $ai = false,
           $pk = false;

    public function __construct(array $options)
    {
        foreach($options as $key=>$option)
        {
            $this->{$key} = $option;
        }
    }

    public function validate($value)
    {
        $errors = [];
        /*@TODO write validation code */

        return $errors;
    }
}

