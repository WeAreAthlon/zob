<?php
/**
 * Index object.
 *
 * @package    Zob
 * @subpackage Objects
 * @author     Kalin Stefanov <kalin@athlonsofia.com>
 * @copyright  Copyright (c) 2015, Zob
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace Zob\Objects;

class Index
{
    public $name,
           $field,
           $type = 'BTREE',
           $unique = false,
           $length;

    public function __construct(array $options)
    {
        foreach($options as $key=>$option)
        {
            $this->{$key} = $option;
        }
    }
}

