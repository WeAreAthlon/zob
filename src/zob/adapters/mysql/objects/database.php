<?php
/**
 * MySql Database object.
 *
 * @package    Zob
 * @subpackage Adapters\MySql\Objects
 * @author     Kalin Stefanov <kalin@athlonsofia.com>
 * @copyright  Copyright (c) 2015, Zob
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace Zob\Adapters\MySql\Objects;

class Database
{
    private $name,
            $characterSet,
            $collation;

    function __construct($name, $characterSet = 'utf8', $collation = 'utf8_general_ci')
    {
        $this->name = $name;
        $this->characterSet = $characterSet;
        $this->collation = $collation;
    }

    public function create()
    {
        return "CREATE database {$this->name} CHARACTER SET = {$this->characterSet} COLLATE = {$this->collation}";
    }

    public function delete()
    {
        return "DROP DATABASE {$this->name}"; 
    }
}

