<?php

namespace Zob\Schema;

/**
 * Class Schema
 * @author stefanov.kalin@gmail.com
 */
class Schema
{
    private $fields = [];

    /**
     * @param array $schema
     */
    public function __construct(array $schema, array $fieldTypes)
    {
        $this->fields = array_map(function($options) use ($fieldTypes) {
            return $this->createField(
                $fieldTypes[$options['name']] ?? $options['type'],
                $options
            );
        }, $schema);
    }

    /**
     * Field factory method
     *
     * @param string $type
     * @param array $options
     */
    private function createField(string $type, array $options) : FieldInterface
    {
        switch ($type) {
            case 'text'     : return new Field\Text($options); 
            case 'number'   : return new Field\Number($options); 
            case 'date'     : return new Field\Date($options); 

            default: return new $type($options);

        }
    }
}
