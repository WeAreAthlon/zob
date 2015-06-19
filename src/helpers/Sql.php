<?php
    
namespace Zob\Helpers\Sql;

class Sql
{
    public static function fieldsToString($tableName, array $fields)
    {
        $result = [];

        foreach($fields as $field) {
            $result[] = "{$tableName}.{$field->getName()}";
        }

        return implode(', ', $result);
    }
}
