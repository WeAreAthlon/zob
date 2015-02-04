<?php
use Zob\Adapters\MySql\MySql;

/**
 * @covers Zob\Adapters\MySql\MySql
 */
class MysqlTest extends PHPUnit_Framework_TestCase
{
    protected $adapter;
    protected $adapterName;
    protected $wrongAdapterName;

    protected function setUp()
    {
        $this->adapter = new MySql([
            'host' => 'localhost',
            'name' => 'silla_test',
            'user' => 'root',
            'password' => ''
        ]);
    }

    /**
     * @covers Zob\Adapters\MySql\MySql::transaction
     */
    public function testTransaction()
    {
        $this->adapter->transaction(function() {
            /*@TODO execute queries */
        });
    }

    /**
     * @covers Zob\Adapters\MySql\MySql::transaction
     */
    public function testNestedTransactions()
    {
        $this->adapter->transaction(function() {
            /*@TODO execute queries */

            $this->adapter->transaction(function() {
                /*@TODO execute queries */
            });
        });
    }

    /**
     * @covers Zob\Adapters\MySql\MySql::rollback
     */
    public function testRollback()
    {
        $this->adapter->transaction(function() {
            /*@TODO execute queries */

            $this->adapter->rollback();
        });
    }

    /**
     * @covers Zob\Adapters\MySql\MySql::rollback
     */
    public function testNestedRollback()
    {
        $this->adapter->transaction(function() {
            /*@TODO execute queries */

            $this->adapter->transaction(function() {
                /*@TODO execute queries */

                $this->adapter->rollback();
            });

            $this->adapter->rollback();
        });
    }
}

