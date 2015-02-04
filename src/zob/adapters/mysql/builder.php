<?php
/**
 * MySql builder.
 *
 * @package    Zob
 * @subpackage Adapters\MySql
 * @author     Kalin Stefanov <kalin@athlonsofia.com>
 * @copyright  Copyright (c) 2015, Zob
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace Zob\Adapters\MySql;

class Builder
{
    public function create($statement)
    {
        $statement = ucfirst($statement);
        $class = "Zob\Adapters\MySql\Statements\\{$statement}";
        if(!class_exists($class)) {
            throw new \DomainException("The Adapter doesn't support {$statement} statement.");
        }

        $ref = new \ReflectionClass($class);
        return $ref->newInstanceArgs(array_slice(func_get_args(), 1));
    }

    public function build($query)
    {
        $sql = []; $params = [];

        switch($query->statement) {
            case 'select': {
                $statements = ['statement', 'from', 'join', 'where', 'group', 'having', 'order', 'limit'];
                break;
            };

            case 'insert': {
                $statements = ['statement', 'values'];
                break;
            };

            case 'delete': {
                $statements = ['statement', 'where', 'order', 'limit'];
                break;
            };

            case 'update': {
                $statements = ['statement', 'set', 'where', 'order', 'limit'];
                break;
            };

            case 'create': {
                break;
            };
        }

        foreach($statements as $statement) {
            $ref = new ReflectionClass("Zob\Adapters\MySql\{$statement}");
            $st = $reflector->newInstanceArgs($query->$statement);
            list($s, $p) = $st->toSql();
            $sql[] = $s;

            if($p) {
                $params = array_merge($params, $p);
            }
        }

        return [implode(' ', $sql), $params];
    }
}

