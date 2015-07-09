<?php
use Zob\Query;
use Zob\Adapters\MySql\MySql;
use Zob\Objects\Table;
use Zob\Objects\Field;

/**
 * @covers Zob\Query
 */
class QueryTest extends PHPUnit_Extensions_Database_TestCase
{
    protected static $connection;
    protected static $users;

    protected function getTableRows($queryTable)
    {
        $result = [];
        for($i = 0; $i < $queryTable->getRowCount(); $i++) {
            $result[] = $queryTable->getRow($i);
        }

        return $result;
    }

    public static function setUpBeforeClass()
    {
        self::$connection = new MySql([
            'host' => 'localhost',
            'name' => 'silla_test',
            'user' => 'root',
            'password' => ''
        ]);

        self::$users = new Table('users', [
            new Field([
                'name' => 'id',
                'type' => 'int',
                'length' => 10,
                'pk'    => true,
                'ai'    => true
            ]),
            new Field([
                'name' => 'name',
                'type' => 'varchar',
                'length' => 255
            ]),
            new Field([
                'name' => 'email',
                'type' => 'varchar',
                'length' => 255,
                'required' => true
            ]),
            new Field([
                'name' => 'created_at',
                'type' => 'datetime'
            ])
        ]);
        self::$connection->createTable(self::$users);

        $tasks = new Table('tasks', [
            new Field([
                'name' => 'id',
                'type' => 'int',
                'length' => 10,
                'pk'    => true,
                'ai'    => true
            ]),
            new Field([
                'name' => 'name',
                'type' => 'varchar',
                'length' => 255
            ]),
            new Field([
                'name' => 'title',
                'type' => 'varchar',
                'length' => 255
            ]),
            new Field([
                'name' => 'user_id',
                'type' => 'int',
                'length' => 10,
                'required' => true
            ]),
            new Field([
                'name' => 'description',
                'type' => 'text'
            ]),
            new Field([
                'name' => 'created_at',
                'type' => 'datetime'
            ])
        ]);
        self::$connection->createTable($tasks);
    }

    public static function tearDownAfterClass()
    {
        self::$connection->deleteTable('users');
        self::$connection->deleteTable('tasks');
    }

    /**
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    public function getConnection()
    {
        $pdo = new PDO('mysql:host=localhost;dbname=silla_test', 'root');
        return $this->createDefaultDBConnection($pdo);
    }

    public function getDataSet()
    {
        return $this->createXmlDataSet('tests/dataset.xml');
    }

    protected function setUp()
    {
        parent::setUp();

        $this->query = new Query(self::$connection);
    }

    /**
     * @covers Zob\Query::select
     */
    public function testSelect()
    {
        $this->query->select(self::$users);

        $queryTable = $this->getConnection()->createQueryTable(
            'users', "SELECT * FROM users"
        );

        $this->assertEquals($this->query->run(), $this->getTableRows($queryTable));
    }

    /**
     * @covers Zob\Query::insert
     */
    public function testInsert()
    {
        $this->query->insert(self::$users, [
            'name' => 'test name',
            'email' => 'test email'
        ]);

        $queryTable = $this->getConnection()->createQueryTable(
            'users', "SELECT * FROM users WHERE name = 'test name'"
        );
        $this->query->run();

        $this->assertEquals('test name', $this->getTableRows($queryTable)[0]['name']);
        $this->assertEquals('test email', $this->getTableRows($queryTable)[0]['email']);
    }
}

