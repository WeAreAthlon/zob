<?php
use Zob\Adapters\MySql\MySql;
use Zob\Adapters\MySql\Statements;
use Zob\Objects\Table;

/**
 * @covers Zob\Adapters\MySql\MySql
 */
class MysqlTest extends PHPUnit_Extensions_Database_TestCase
{
    protected static $connection;
    protected static $users;
    protected static $tasks;

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

        self::$users = new Table(self::$connection, 'users', [
            [
                'name' => 'id',
                'type' => 'int',
                'length' => 10,
                'pk'    => true,
                'ai'    => true
            ],
            [
                'name' => 'name',
                'type' => 'varchar',
                'length' => 255
            ],
            [
                'name' => 'email',
                'type' => 'varchar',
                'length' => 255,
                'required' => true
            ],
            [
                'name' => 'created_at',
                'type' => 'datetime'
            ]
        ]);
        self::$users->create();

        self::$tasks = new Table(self::$connection, 'tasks', [
            [
                'name' => 'id',
                'type' => 'int',
                'length' => 10,
                'pk'    => true,
                'ai'    => true
            ],
            [
                'name' => 'title',
                'type' => 'varchar',
                'length' => 255
            ],
            [
                'name' => 'user_id',
                'type' => 'int',
                'length' => 10,
                'required' => true
            ],
            [
                'name' => 'description',
                'type' => 'text'
            ],
            [
                'name' => 'created_at',
                'type' => 'datetime'
            ]
        ]);
        self::$tasks->create();
    }

    public static function tearDownAfterClass()
    {
        self::$users->delete();
        self::$tasks->delete();
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

    /**
     * @covers Zob\Adapters\MySql\MySql::run
     */
    public function testSelectingAllRecords()
    {
        /* Select all fields */
        $options = [
            'statement'     => new Statements\Select(),
            'from'          => new Statements\From(self::$users->name)
        ];

        $result = self::$connection->run($options);
        $queryTable = $this->getConnection()->createQueryTable(
            'users', 'SELECT * FROM users'
        );

        $this->assertEquals($result, $this->getTableRows($queryTable));

        /* Select only the id and email */
        $options = [
            'statement'     => new Statements\Select('id, email'),
            'from'          => new Statements\From(self::$users->name)
        ];

        $result = self::$connection->run($options);
        $queryTable = $this->getConnection()->createQueryTable(
            'users', 'SELECT id, email FROM users'
        );

        $this->assertEquals($result, $this->getTableRows($queryTable));

        /* Select only the id and email passed as array */
        $options = [
            'statement'     => new Statements\Select(['id', 'email']),
            'from'          => new Statements\From(self::$users->name)
        ];

        $result = self::$connection->run($options);
        $queryTable = $this->getConnection()->createQueryTable(
            'users', 'SELECT id, email FROM users'
        );

        $this->assertEquals($result, $this->getTableRows($queryTable));
    }

    /**
     * @covers Zob\Adapters\MySql\MySql::run
     */
    public function testSelectingRecordsWithFilter()
    {
        $options = [
            'statement'     => new Statements\Select(),
            'from'          => new Statements\From(self::$users->name),
            'where'         => new Statements\Where('id < ?', [4])
        ];

        $result = self::$connection->run($options);
        $queryTable = $this->getConnection()->createQueryTable(
            'users', 'SELECT * FROM users WHERE id < 4'
        );

        $this->assertEquals($result, $this->getTableRows($queryTable));

        $options = [
            'statement'     => new Statements\Select(),
            'from'          => new Statements\From(self::$users->name),
            'where'         => new Statements\Where(['id' => [4, 6]])
        ];

        $result = self::$connection->run($options);
        $queryTable = $this->getConnection()->createQueryTable(
            'users', 'SELECT * FROM users WHERE id IN(4, 6)'
        );

        $this->assertEquals($result, $this->getTableRows($queryTable));
    }

    /**
     * @covers Zob\Adapters\MySql\MySql::run
     */
    public function testSelectingRecordsWithOrder()
    {
        $options = [
            'statement'     => new Statements\Select(),
            'from'          => new Statements\From(self::$users->name),
            'order'         => new Statements\Order('created_at', 'desc')
        ];

        $result = self::$connection->run($options);
        $queryTable = $this->getConnection()->createQueryTable(
            'users', 'SELECT * FROM users ORDER BY created_at desc'
        );

        $this->assertEquals($result, $this->getTableRows($queryTable));

        $options = [
            'statement'     => new Statements\Select(),
            'from'          => new Statements\From(self::$users->name),
            'order'         => new Statements\Order(['created_at' => 'desc', 'id' => 'asc'])
        ];

        $result = self::$connection->run($options);
        $queryTable = $this->getConnection()->createQueryTable(
            'users', 'SELECT * FROM users ORDER BY created_at desc, id asc'
        );

        $this->assertEquals($result, $this->getTableRows($queryTable));
    }

    /**
     * @covers Zob\Adapters\MySql\MySql::run
     */
    public function testSelectingRecordsWithLimit()
    {
        $options = [
            'statement'     => new Statements\Select(),
            'from'          => new Statements\From(self::$users->name),
            'limit'         => new Statements\Limit(4)
        ];

        $result = self::$connection->run($options);
        $queryTable = $this->getConnection()->createQueryTable(
            'users', 'SELECT * FROM users LIMIT 4'
        );

        $this->assertEquals($result, $this->getTableRows($queryTable));

        $options = [
            'statement'     => new Statements\Select(),
            'from'          => new Statements\From(self::$users->name),
            'limit'         => new Statements\Limit(3, 2)
        ];

        $result = self::$connection->run($options);
        $queryTable = $this->getConnection()->createQueryTable(
            'users', 'SELECT * FROM users LIMIT 3 OFFSET 2'
        );

        $this->assertEquals($result, $this->getTableRows($queryTable));
    }

    /**
     * @covers Zob\Adapters\MySql\MySql::run
     */
    public function testSelectingRecordsWithJoin()
    {
        $options = [
            'statement'     => new Statements\Select('users.*'),
            'from'          => new Statements\From(self::$users->name),
            'join'          => new Statements\Join(self::$tasks->name, "users.id = tasks.user_id")
        ];

        $result = self::$connection->run($options);
        $queryTable = $this->getConnection()->createQueryTable(
            'users', 'SELECT users.* FROM users LEFT JOIN tasks ON(users.id = tasks.user_id)'
        );

        $this->assertEquals($result, $this->getTableRows($queryTable));

        $options = [
            'statement'     => new Statements\Select('tasks.*'),
            'from'          => new Statements\From(self::$users->name),
            'join'          => new Statements\Join(self::$tasks->name, "users.id = tasks.user_id"),
            'where'         => new Statements\Where('users.id = 2')
        ];

        $result = self::$connection->run($options);
        $queryTable = $this->getConnection()->createQueryTable(
            'users', 'SELECT tasks.* FROM users LEFT JOIN tasks ON(users.id = tasks.user_id) WHERE users.id = 2'
        );

        $this->assertEquals($result, $this->getTableRows($queryTable));

        $options = [
            'statement'     => new Statements\Select('users.id, users.email, tasks.title, tasks.user_id'),
            'from'          => new Statements\From(self::$users->name),
            'join'          => new Statements\Join(self::$tasks->name, "users.id = tasks.user_id"),
        ];

        $result = self::$connection->run($options);
        $queryTable = $this->getConnection()->createQueryTable(
            'users', 'SELECT users.id, users.email, tasks.title, tasks.user_id FROM users LEFT JOIN tasks ON(users.id = tasks.user_id)'
        );

        $this->assertEquals($result, $this->getTableRows($queryTable));

        $options = [
            'statement'     => new Statements\Select('users.id, users.email, tasks.title, tasks.user_id'),
            'from'          => new Statements\From(self::$users->name),
            'join'          => new Statements\Join(self::$tasks->name, "users.id = tasks.user_id", 'INNER'),
        ];

        $result = self::$connection->run($options);
        $queryTable = $this->getConnection()->createQueryTable(
            'users', 'SELECT users.id, users.email, tasks.title, tasks.user_id FROM users INNER JOIN tasks ON(users.id = tasks.user_id)'
        );

        $this->assertEquals($result, $this->getTableRows($queryTable));
    }

    /**
     * @covers Zob\Adapters\MySql\MySql::run
     */
    public function testRecordInsertion()
    {
        $options = [
            'statement'     => new Statements\Insert(self::$users->name, ['name' => 'User7', 'email' => 'user7@test.com', 'created_at' => '2015-02-24 18:15:23']),
        ];

        $result = self::$connection->run($options);
        $queryTable = $this->getConnection()->createQueryTable(
            'users', 'SELECT * FROM users WHERE id = 7'
        );

        $this->assertEquals(1, $queryTable->getRowCount());
    }

    /**
     * @covers Zob\Adapters\MySql\MySql::run
     * @expectedException DomainException
     */
    public function testRecordInsertionWithMissingRequiredFeld()
    {
        $options = [
            'statement'     => new Statements\Insert(self::$users->name, ['name' => 'User7', 'created_at' => '2015-02-24 18:15:23']),
        ];

        $result = self::$connection->run($options);
    }

    /**
     * @covers Zob\Adapters\MySql\MySql::run
     */
    public function testRecordUpdate()
    {
        $options = [
            'statement'     => new Statements\Update(self::$users->name, ['email' => 'user5updated@test.com']),
            'where'         => new Statements\Where(['email' => 'user5@test.com']),
        ];

        $result = self::$connection->run($options);
        $queryTable = $this->getConnection()->createQueryTable(
            'users', "SELECT * FROM users WHERE email = 'user5updated@test.com'"
        );

        $this->assertEquals(1, $queryTable->getRowCount());

        $options = [
            'statement'     => new Statements\Update(self::$users->name, ['name' => 'UpdatedName']),
            'where'         => new Statements\Where(['id' => ['$lte' => 3]])
        ];

        $result = self::$connection->run($options);
        $queryTable = $this->getConnection()->createQueryTable(
            'users', "SELECT * FROM users WHERE name = 'UpdatedName'"
        );

        $this->assertEquals(3, $queryTable->getRowCount());

        $options = [
            'statement'     => new Statements\Update(self::$users->name, ['name' => 'GT3']),
            'where'         => new Statements\Where(['id' => ['$gt' => 4]]),
            'limit'         => new Statements\Limit(2)
        ];

        $result = self::$connection->run($options);
        $queryTable = $this->getConnection()->createQueryTable(
            'users', "SELECT * FROM users WHERE name = 'GT3'"
        );

        $this->assertEquals(2, $queryTable->getRowCount());
    }

    /**
     * @covers Zob\Adapters\MySql\MySql::run
     * @expectedException DomainException
     */
    public function testRecordUpdateWithMissingRequiredFeld()
    {
        $options = [
            'statement'     => new Statements\Update(self::$users->name, ['email' => '']),
            'where'         => new Statements\Where(['email' => 'user5@test.com'])
        ];

        $result = self::$connection->run($options);
    }

    /**
     * @covers Zob\Adapters\MySql\MySql::run
     */
    public function testRecordDeletion()
    {
        $options = [
            'statement'     => new Statements\Delete(self::$users->name),
            'where'         => new Statements\Where(['email' => 'user2@test.com'])
        ];

        $result = self::$connection->run($options);
        $queryTable = $this->getConnection()->createQueryTable(
            'users', "SELECT * FROM users WHERE email = 'user2@test.com'"
        );

        $this->assertEquals(0, $queryTable->getRowCount());

        $options = [
            'statement'     => new Statements\Delete(self::$users->name),
            'where'         => new Statements\Where(['id' => ['$gt' => 3]]),
            'limit'         => new Statements\Limit(2)
        ];

        $result = self::$connection->run($options);
        $queryTable = $this->getConnection()->createQueryTable(
            'users', "SELECT * FROM users"
        );

        $this->assertEquals(3, $queryTable->getRowCount());
    }

    /**
     * @covers Zob\Adapters\MySql\MySql::transaction
     */
    public function testTransaction()
    {
        self::$connection->transaction(function() {
            /*@TODO execute queries */
        });
    }

    /**
     * @covers Zob\Adapters\MySql\MySql::transaction
     */
    public function testNestedTransactions()
    {
        self::$connection->transaction(function() {
            /*@TODO execute queries */

            self::$connection->transaction(function() {
                /*@TODO execute queries */
            });
        });
    }

    /**
     * @covers Zob\Adapters\MySql\MySql::rollback
     */
    public function testRollback()
    {
        self::$connection->transaction(function() {
            /*@TODO execute queries */

            self::$connection->rollback();
        });
    }

    /**
     * @covers Zob\Adapters\MySql\MySql::rollback
     */
    public function testNestedRollback()
    {
        self::$connection->transaction(function() {
            /*@TODO execute queries */

            self::$connection->transaction(function() {
                /*@TODO execute queries */

                self::$connection->rollback();
            });

            self::$connection->rollback();
        });
    }
}

