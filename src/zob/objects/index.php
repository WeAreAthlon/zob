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

/**
 * Object representation if a database table index
 */
class Index
{
    /**
     * Index name
     *
     * @var string
     * @access private
     */
    public $name;

    /**
     * The field for the index
     *
     * @var string
     * @access public
     */
    public $field;

    /**
     * Type of the index
     *
     * @var string
     * @access public
     */
    public $type = 'BTREE';

    /**
     * Sets the index as unique
     *
     * @var bool
     * @access public
     */
    public $unique = false;

    /**
     * Length of the index
     *
     * @var string
     * @access private
     */
    public $length;

    /**
     * Basic constructor
     *
     * @param array $options Options to initialize the index with
     *
     * @access public
     */
    public function __construct(array $options)
    {
        foreach($options as $key=>$option)
        {
            $this->{$key} = $option;
        }
    }
}

