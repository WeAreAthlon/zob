<?php
use Zob\Adapters\MySql\MySql;
use Zob\Adapters\MySql\Statements;
use Zob\Objects\Database;
use Zob\Objects\Table;
use Zob\Objects\Field;
use Zob\Objects\Index;

/**
 * @covers Zob\Adapters\MySql\MySql
 */
class MysqlTest extends PHPUnit_Extensions_Database_TestCase
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

    /**
     * @covers Zob\Adapters\MySql\MySql::databaseExists
     */
    public function testDatabaseExists()
    {
        $this->assertFalse(self::$connection->databaseExists('silla_test_missing'));
        $this->assertTrue(self::$connection->databaseExists('silla_test'));
    }

    /**
     * @covers Zob\Adapters\MySql\MySql::createDatabase
     * @depends testDatabaseExists
     */
    public function testCreateDatabase()
    {
        $database = new Database('silla_test_created');

        $this->assertFalse(self::$connection->databaseExists('silla_test_created'));

        self::$connection->createDatabase($database);

        $this->assertTrue(self::$connection->databaseExists('silla_test_created'));
    }

    /**
     * @covers Zob\Adapters\MySql\MySql::deleteDatabase
     * @depends testCreateDatabase
     */
    public function testDeleteDatabase()
    {
        self::$connection->deleteDatabase('silla_test_created');

        $this->assertFalse(self::$connection->databaseExists('silla_test_created'));
    }

    /**
     * @covers Zob\Adapters\MySql\MySql::tableExists
     */
    public function testTableExists()
    {
        $this->assertFalse(self::$connection->tableExists('users_missing'));
        $this->assertTrue(self::$connection->tableExists('users'));
    }

    /**
     * @covers Zob\Adapters\MySql\MySql::getTable
     */
    public function testGetTable()
    {
        $this->assertEquals(self::$connection->getTable('users'), self::$users);
    }

    /**
     * @covers Zob\Adapters\MySql\MySql::createTable
     * @depends testTableExists
     */
    public function testCreateTable()
    {
        $table = new Table('users_created', [
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
            ])
        ]);

        $this->assertFalse(self::$connection->tableExists('users_created'));

        self::$connection->createTable($table);

        $this->assertTrue(self::$connection->tableExists('users_created'));
    }

    /**
     * @covers Zob\Adapters\MySql\MySql::deleteTable
     * @depends testCreateTable
     */
    public function testDeleteTable()
    {
        self::$connection->deleteTable('users_created');

        $this->assertFalse(self::$connection->tableExists('users_created'));
    }

    /**
     * @covers Zob\Adapters\MySql\MySql::createField
     * @depends testGetTable
     */
    public function testCreateField()
    {
        $field = new Field([
            'name' => 'created_field',
            'type' => 'varchar',
            'length' => 255
        ]);

        self::$connection->createField($field, 'users');

        $table = self::$connection->getTable('users');

        $this->assertEquals($field, $table->getField('created_field'));
    }

    /**
     * @covers Zob\Adapters\MySql\MySql::changeField
     * @depends testCreateField
     */
    public function testChangeField()
    {
        $field = new Field([
            'name' => 'created_field_changed',
            'type' => 'int',
            'length' => 10
        ]);

        self::$connection->changeField('users', 'created_field', $field);

        $table = self::$connection->getTable('users');

        $this->assertFalse($table->getField('created_field'));
        $this->assertEquals($field, $table->getField('created_field_changed'));
    }

    /**
     * @covers Zob\Adapters\MySql\MySql::deleteField
     * @depends testChangeField
     */
    public function testDeleteField()
    {
        self::$connection->deleteField('created_field_changed', 'users');

        $table = self::$connection->getTable('users');

        $this->assertFalse($table->getField('created_field_changed'));
    }

    /**
     * @covers Zob\Adapters\MySql\MySql::createIndex
     * @depends testGetTable
     */
    public function testCreateIndex()
    {
        $index = new Index([
            'name' => 'name_idx',
            'field' => 'name'
        ]);

        self::$connection->createIndex($index, 'users');

        $table = self::$connection->getTable('users');

        $this->assertEquals($index, $table->getIndex('name_idx'));
    }

    /**
     * @covers Zob\Adapters\MySql\MySql::deleteIndex
     * @depends testCreateIndex
     */
    public function testDeleteIndex()
    {
        self::$connection->deleteIndex('name_idx', 'users');

        $table = self::$connection->getTable('users');

        $this->assertFalse($table->getIndex('name_idx'));
    }

    /**
     * @covers Zob\Adapters\MySql\MySql::transaction
     */
    public function testTransaction()
    {
        self::$connection->transaction(function() {
            $stmt = new Statements\Delete(self::$users);
            list($sql, $params) = $stmt->where(['email' => 'user2@test.com'])->toSql();
            self::$connection->execute($sql, $params);

            $stmt = new Statements\Update(self::$users, ['email' => 'user5updated@test.com']);
            list($sql, $params) = $stmt->where(['email' => 'user5@test.com'])->toSql();
            self::$connection->execute($sql, $params);
        });

        $queryTable = $this->getConnection()->createQueryTable(
            'users', "SELECT * FROM users WHERE email = 'user2@test.com'"
        );

        $this->assertEquals(0, $queryTable->getRowCount());

        $queryTable = $this->getConnection()->createQueryTable(
            'users', "SELECT * FROM users WHERE email = 'user5updated@test.com'"
        );

        $this->assertEquals(1, $queryTable->getRowCount());
    }

    /**
     * @covers Zob\Adapters\MySql\MySql::transaction
     */
    public function testTransactionWithException()
    {
        self::$connection->transaction(function() {
            $stmt = new Statements\Delete(self::$users);
            list($sql, $params) = $stmt->where(['email' => 'user2@test.com'])->toSql();
            self::$connection->execute($sql, $params);

            $stmt = new Statements\Update(self::$users->getPartial(['email']), ['email' => null]);
            list($sql, $params) = $stmt->where(['email' => 'user5@test.com'])->toSql();
            self::$connection->execute($sql, $params);
        });

        $queryTable = $this->getConnection()->createQueryTable(
            'users', "SELECT * FROM users WHERE email = 'user2@test.com'"
        );

        $this->assertEquals(1, $queryTable->getRowCount());

        $queryTable = $this->getConnection()->createQueryTable(
            'users', "SELECT * FROM users WHERE email = 'user5@test.com'"
        );

        $this->assertEquals(1, $queryTable->getRowCount());

        $queryTable = $this->getConnection()->createQueryTable(
            'users', "SELECT * FROM users WHERE email = ''"
        );

        $this->assertEquals(0, $queryTable->getRowCount());
    }

    /**
     * @covers Zob\Adapters\MySql\MySql::transaction
     */
    public function testNestedTransactions()
    {
        self::$connection->transaction(function() {
            $stmt = new Statements\Delete(self::$users);
            list($sql, $params) = $stmt->where(['email' => 'user2@test.com'])->toSql();
            self::$connection->execute($sql, $params);

            self::$connection->transaction(function() {
                $stmt = new Statements\Update(self::$users->getPartial(['email']), ['email' => 'user5updated@test.com']);
                list($sql, $params) = $stmt->where(['email' => 'user5@test.com'])->toSql();
                self::$connection->execute($sql, $params);
            });
        });

        $queryTable = $this->getConnection()->createQueryTable(
            'users', "SELECT * FROM users WHERE email = 'user2@test.com'"
        );

        $this->assertEquals(0, $queryTable->getRowCount());

        $queryTable = $this->getConnection()->createQueryTable(
            'users', "SELECT * FROM users WHERE email = 'user5updated@test.com'"
        );

        $this->assertEquals(1, $queryTable->getRowCount());
    }

    /**
     * @covers Zob\Adapters\MySql\MySql::rollback
     */
    public function testRollback()
    {
        self::$connection->transaction(function() {
            $stmt = new Statements\Delete(self::$users);
            list($sql, $params) = $stmt->where(['email' => 'user2@test.com'])->toSql();
            self::$connection->execute($sql, $params);

            self::$connection->rollback();

            $stmt = new Statements\Update(self::$users->getPartial(['email']), ['email' => 'user5updated@test.com']);
            list($sql, $params) = $stmt->where(['email' => 'user5@test.com'])->toSql();
            self::$connection->execute($sql, $params);
        });

        $queryTable = $this->getConnection()->createQueryTable(
            'users', "SELECT * FROM users WHERE email = 'user2@test.com'"
        );

        $this->assertEquals(1, $queryTable->getRowCount());

        $queryTable = $this->getConnection()->createQueryTable(
            'users', "SELECT * FROM users WHERE email = 'user5@test.com'"
        );

        $this->assertEquals(1, $queryTable->getRowCount());

        $queryTable = $this->getConnection()->createQueryTable(
            'users', "SELECT * FROM users WHERE email = 'user5updated@test.com'"
        );

        $this->assertEquals(0, $queryTable->getRowCount());
    }

    /**
     * @covers Zob\Adapters\MySql\MySql::rollback
     */
    public function testNestedRollback()
    {
        self::$connection->transaction(function() {
            $stmt = new Statements\Delete(self::$users);
            list($sql, $params) = $stmt->where(['email' => 'user2@test.com'])->toSql();
            self::$connection->execute($sql, $params);

            self::$connection->transaction(function() {
                $stmt = new Statements\Update(self::$users->getPartial(['email']), ['email' => 'user5updated@test.com']);
                list($sql, $params) = $stmt->where(['email' => 'user5@test.com'])->toSql();
                self::$connection->execute($sql, $params);

                self::$connection->rollback();
            });

            self::$connection->rollback();
        });

        $queryTable = $this->getConnection()->createQueryTable(
            'users', "SELECT * FROM users WHERE email = 'user2@test.com'"
        );

        $this->assertEquals(1, $queryTable->getRowCount());

        $queryTable = $this->getConnection()->createQueryTable(
            'users', "SELECT * FROM users WHERE email = 'user5@test.com'"
        );

        $this->assertEquals(1, $queryTable->getRowCount());

        $queryTable = $this->getConnection()->createQueryTable(
            'users', "SELECT * FROM users WHERE email = 'user5updated@test.com'"
        );

        $this->assertEquals(0, $queryTable->getRowCount());
    }
}

