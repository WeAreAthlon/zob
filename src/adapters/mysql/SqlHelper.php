<?php
    
namespace Zob\Adapters\MySql;

class SqlHelper
{
    public static function fieldsToString($tableName, array $fields)
    {
        $result = [];

        foreach ($fields as $field) {
            $result[] = "{$tableName}.{$field->getName()}";
        }

        return implode(', ', $result);
    }

    public static function joinTables(array $joins)
    {
        $result = [];

        foreach ($joins as $key=>$join) {
            $conditions = self::parseConditions($join->getConditions()); 
            $result[] = ($key === 0 ? "{$join->getTable()->getName()} " : '') . "{$join->getType()} JOIN {$join->getForeignTable()->getName()} ON ({$conditions})}";
        }

        return implode(' ', $result);
    }

    public static function parseConditions(array $conditions)
    {
        $result = [];

        foreach ($conditions as $condition) {
            $con = $condition->get();
            $value = $con['value'];

            if ($value instanceof FieldInterface) {
                $value = "{$value->getTable()->getName()}.{$value->getName()}";
            }

            $result[] = "{$con['field']->getTable()->getName()}.{$con['field']->getName()} {$con['operator']} {$value}";
        }

        return implode(' AND ', $result);
    }
}

