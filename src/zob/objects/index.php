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
           $type,
           $length;

    public function __construct($name, $type = null, $length = null)
    {
        $this->name = $name;
        $this->type = $type;
        $this->length = $length;
    }
}

