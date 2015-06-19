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
class Index implements IndexInterface
{
    /**
     * Index name
     *
     * @var string
     * @access private
     */
    private $name;

    /**
     * The field for the index
     *
     * @var string
     * @access private
     */
    private $field;

    /**
     * Type of the index
     *
     * @var string
     * @access private
     */
    private $type = 'BTREE';

    /**
     * Sets the index as unique
     *
     * @var bool
     * @access private
     */
    private $unique = false;

    /**
     * Length of the index
     *
     * @var string
     * @access private
     */
    private $length;

    /**
     * Basic constructor
     *
     * @param array $options Options to initialize the index with
     *
     * @access public
     */
    public function __construct(array $options)
    {
        foreach (array_intersect_key($options, array_flip(['name', 'field', 'type', 'length', 'unique'])) as $key=>$value) {
            $this->{$key} = $value;
        }

        if (!$this->name) {
            $this->name = "{$this->field}_idx";
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function getField()
    {
        return $this->field;
    }

    public function getType()
    {
        return $this->type;
    }

    public function isUnique()
    {
        return $this->unique;
    }

    public function getLength()
    {
        return $this->length;
    }
}

