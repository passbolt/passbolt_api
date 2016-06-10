<?php
/**
 * Copyright 2009 - 2014, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2009 - 2014, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('ShellDispatcher', 'Console');
App::uses('MigrationShell', 'Migrations.Console/Command');

/**
 * TestMigrationShell
 */
class TestMigrationShell extends MigrationShell {

/**
 * Output property
 *
 * @var string
 */
	public $output = '';

/**
 * Out method
 *
 * @param $string
 * @return void
 */
	public function out($message = null, $newlines = 1, $level = 1) {
		$this->output .= $message . "\n";
	}

/**
 * FromComparison method
 *
 * @param $migration
 * @param $comparison
 * @param $oldTables
 * @param $currentTables
 * @return void
 */
	public function fromComparison($migration, $comparison, $oldTables, $currentTables) {
		return $this->_fromComparison($migration, $comparison, $oldTables, $currentTables);
	}

/**
 * WriteMigration method
 *
 * @param $name
 * @param $class
 * @param $migration
 * @return void
 */
	public function writeMigration($name, $class, $migration) {
		return $this->_writeMigration($name, $class, $migration);
	}

}

/**
 * MigrationShellTest
 */
class MigrationShellTest extends CakeTestCase {

/**
 * Fixtures property
 *
 * @var array
 */
	public $fixtures = array('plugin.migrations.schema_migrations', 'core.article', 'core.post', 'core.user');

/**
 * SetUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$out = $this->getMock('ConsoleOutput', array(), array(), '', false);
		$in = $this->getMock('ConsoleInput', array(), array(), '', false);
		$this->Shell = $this->getMock(
			'TestMigrationShell',
			array('in', 'hr', 'createFile', 'error', 'err', '_stop', '_showInfo', 'dispatchShell'),
			array($out, $out, $in));

		$this->Shell->Version = $this->getMock(
			'MigrationVersion',
			array('getMapping', 'getVersion', 'run'),
			array(array('connection' => 'test')));

		$this->Shell->type = 'TestMigrationPlugin';
		$this->Shell->path = TMP . 'tests' . DS;
		$this->Shell->connection = 'test';

		$plugins = $this->plugins = App::path('plugins');
		$plugins[] = dirname(dirname(dirname(dirname(__FILE__)))) . DS . 'test_app' . DS . 'Plugin' . DS;

		App::build(array('Plugin' => $plugins), true);
		App::objects('plugins', null, false);
		CakePlugin::load('TestMigrationPlugin');
		CakePlugin::load('TestMigrationPlugin2');
		CakePlugin::load('TestMigrationPlugin3');
		CakePlugin::load('TestMigrationPlugin4');

		Configure::write('Config.language', 'en');
	}

/**
 * TearDown method
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();
		CakePlugin::unload('TestMigrationPlugin');
		CakePlugin::unload('TestMigrationPlugin2');
		CakePlugin::unload('TestMigrationPlugin3');
		CakePlugin::unload('TestMigrationPlugin4');
		App::build(array('Plugin' => $this->plugins), true);
		App::objects('plugins', null, false);
		unset($this->Dispatcher, $this->Shell, $this->plugins);
		$this->_unlink(glob(TMP . 'tests' . DS . '*.php'));
	}

/**
 * Tables property
 *
 * @var array
 */
	public $tables = array(
		'users' => array(
			'id' => array('type' => 'integer', 'key' => 'primary'),
			'user' => array('type' => 'string', 'null' => false),
			'password' => array('type' => 'string', 'null' => false),
			'created' => 'datetime',
			'updated' => 'datetime'
		),
		'posts' => array(
			'id' => array('type' => 'integer', 'key' => 'primary'),
			'author_id' => array('type' => 'integer', 'null' => false),
			'title' => array('type' => 'string', 'null' => false),
			'body' => 'text',
			'published' => array('type' => 'string', 'length' => 1, 'default' => 'N'),
			'created' => 'datetime',
			'updated' => 'datetime'
		)
	);

/**
 * TestStartup method
 *
 * @return void
 */
	public function testStartup() {
		$this->Shell->connection = 'default';
		$this->assertEquals($this->Shell->type, 'TestMigrationPlugin');
		$this->Shell->params = array(
			'connection' => 'test',
			'plugin' => 'Migrations',
			'no-auto-init' => false,
			'dry' => false,
			'precheck' => 'Migrations.PrecheckException'
		);
		$this->Shell->startup();
		$this->assertEquals($this->Shell->connection, 'test');
		$this->assertEquals($this->Shell->type, 'Migrations');

		$this->Shell->expects($this->any())->method('in')->will($this->returnValue('test'));
		$this->Shell->expects($this->any())->method('_startMigrationConnection')->will($this->returnValue('test'));
		$this->Shell->startup();
		$this->assertEquals($this->Shell->migrationConnection, 'test');
	}

/**
 * TestRun method
 *
 * @return void
 */
	public function testRun() {
		$mapping = array();
		for ($i = 1; $i <= 10; $i++) {
			$mapping[$i] = array(
				'version' => $i, 'name' => '001_schema_dump',
				'class' => 'M4af9d151e1484b74ad9d007058157726',
				'type' => $this->Shell->type, 'migrated' => null
			);
		}
		$this->Shell->expects($this->any())->method('_stop')->will($this->returnValue(false));

		// cake migration run - no mapping
		$this->Shell->Version->expects($this->at(0))->method('getMapping')->will($this->returnValue(false));
		$this->Shell->args = array();
		$this->assertFalse($this->Shell->run());

		// cake migration run up
		$this->Shell->Version->expects($this->any())->method('getMapping')->will($this->returnValue($mapping));
		$this->Shell->Version->expects($this->at(1))->method('getVersion')->will($this->returnValue(0));
		$this->Shell->Version->expects($this->at(2))->method('run')->with($this->equalTo(array(
			'type' => 'TestMigrationPlugin',
			'callback' => $this->Shell,
			'direction' => 'up',
			'version' => 1,
			'dry' => false,
			'precheck' => null)));
		$this->Shell->args = array('up');
		$this->assertTrue($this->Shell->run());

		// cake migration run up - on last version == stop
		$this->Shell->Version->expects($this->at(1))->method('getVersion')->will($this->returnValue(10));
		$this->Shell->args = array('up');
		$this->assertFalse($this->Shell->run());

		// cake migration run down - on version 0 == stop
		$this->Shell->Version->expects($this->at(1))->method('getVersion')->will($this->returnValue(0));
		$this->Shell->args = array('down');
		$this->assertFalse($this->Shell->run());

		// cake migration run down
		$this->Shell->Version->expects($this->at(1))->method('getVersion')->will($this->returnValue(1));
		$this->Shell->Version->expects($this->at(2))->method('run')->with($this->equalTo(array(
			'type' => 'TestMigrationPlugin',
			'callback' => $this->Shell,
			'direction' => 'down',
			'version' => 1,
			'dry' => false,
			'precheck' => null)));
		$this->Shell->args = array('down');
		$this->assertTrue($this->Shell->run());

		// cake migration run all
		$this->Shell->Version->expects($this->at(1))->method('getVersion')->will($this->returnValue(1));
		$this->Shell->Version->expects($this->at(2))->method('run')->with($this->equalTo(array(
			'type' => 'TestMigrationPlugin',
			'callback' => $this->Shell,
			'version' => 10,
			'direction' => 'up',
			'dry' => false,
			'precheck' => null)));
		$this->Shell->args = array('all');
		$this->assertTrue($this->Shell->run());

		// cake migration run reset
		$this->Shell->Version->expects($this->at(1))->method('getVersion')->will($this->returnValue(9));
		$this->Shell->Version->expects($this->at(2))->method('run')->with($this->equalTo(array(
			'type' => 'TestMigrationPlugin',
			'callback' => $this->Shell,
			'version' => 0,
			'direction' => 'down',
			'dry' => false,
			'precheck' => null)));
		$this->Shell->args = array('reset');
		$this->assertTrue($this->Shell->run());

		// cake migration run - answers 0, 11, 1
		$this->Shell->Version->expects($this->at(1))->method('getVersion')->will($this->returnValue(0));
		$this->Shell->Version->expects($this->at(2))->method('run')->with($this->equalTo(array(
			'type' => 'TestMigrationPlugin',
			'callback' => $this->Shell,
			'version' => 1,
			'direction' => 'up',
			'dry' => false)));
		$this->Shell->expects($this->at(2))->method('in')->will($this->returnValue(0));
		$this->Shell->expects($this->at(4))->method('in')->will($this->returnValue(11));
		$this->Shell->expects($this->at(6))->method('in')->will($this->returnValue(1));
		$this->Shell->args = array();
		$this->assertTrue($this->Shell->run());

		// cake migration run - answers 10
		$this->Shell->Version->expects($this->at(1))->method('getVersion')->will($this->returnValue(9));
		$this->Shell->Version->expects($this->at(2))->method('run')->with($this->equalTo(array(
			'type' => 'TestMigrationPlugin',
			'callback' => $this->Shell,
			'version' => 10,
			'direction' => 'up',
			'dry' => false)));
		$this->Shell->expects($this->at(2))->method('in')->will($this->returnValue(10));
		$this->Shell->args = array();
		$this->assertTrue($this->Shell->run());

		// cake migration run 1
		$this->Shell->Version->expects($this->at(1))->method('getVersion')->will($this->returnValue(0));
		$this->Shell->Version->expects($this->at(2))->method('run')->with($this->equalTo(array(
			'type' => 'TestMigrationPlugin',
			'callback' => $this->Shell,
			'version' => 1,
			'dry' => false)));
		$this->Shell->args = array('1');
		$this->assertTrue($this->Shell->run());

		// cake migration run 11
		$this->Shell->Version->expects($this->at(1))->method('getVersion')->will($this->returnValue(0));
		$this->Shell->args = array('11');
		$this->assertFalse($this->Shell->run());
	}

/**
 * TestRunWithFailuresOnce method
 *
 * @return void
 */
	public function testRunWithFailuresOnce() {
		$this->Shell->expects($this->any())->method('_stop')->will($this->returnValue(false));

		$mapping = array(1 => array(
			'version' => 1, 'name' => '001_schema_dump',
			'class' => 'M4af9d151e1484b74ad9d007058157726',
			'type' => $this->Shell->type, 'migrated' => null
		));

		$migration = new CakeMigration();
		$migration->info = $mapping[1];
		$exception = new MigrationException($migration, 'Exception message');

		$this->Shell->Version->expects($this->any())->method('getMapping')->will($this->returnValue($mapping));
		$this->Shell->Version->expects($this->any())->method('getVersion')->will($this->returnValue(0));
		$this->Shell->Version->expects($this->at(2))->method('run')->will($this->throwException($exception));
		$this->Shell->expects($this->at(1))->method('in')->will($this->returnValue('y'));
		$this->Shell->args = array('up');
		$this->assertTrue($this->Shell->run());

		$result = $this->Shell->output;
		$pattern = <<<TEXT
/Running migrations:
An error occurred when processing the migration:
  Migration: 001_schema_dump
  Error: Exception message
All migrations have completed./
TEXT;
		$this->assertRegExp(str_replace("\r\n", "\n", $pattern), str_replace("\r\n", "\n", $result));
	}

/**
 * TestRunWithFailuresNotOnce method
 *
 * @return void
 */
	public function testRunWithFailuresNotOnce() {
		$this->Shell->expects($this->any())->method('_stop')->will($this->returnValue(false));

		$mapping = array(
			1 => array(
				'version' => 1, 'name' => '001_schema_dump',
				'class' => 'M4af9d151e1484b74ad9d007058157726',
				'type' => $this->Shell->type, 'migrated' => null
			),
		);

		$migration = new CakeMigration();
		$migration->info = $mapping[1];
		$exception = new MigrationException($migration, 'Exception message');

		$this->Shell->Version->expects($this->any())->method('getMapping')->will($this->returnValue($mapping));
		$this->Shell->Version->expects($this->any())->method('getVersion')->will($this->returnValue(0));
		$this->Shell->Version->expects($this->at(2))->method('run')->will($this->throwException($exception));
		$this->Shell->Version->expects($this->at(3))->method('run')->will($this->returnValue(true));
		$this->Shell->expects($this->at(1))->method('in')->will($this->returnValue('y'));
		$this->Shell->args = array('all');
		$this->assertTrue($this->Shell->run());
		$result = $this->Shell->output;
		$pattern = <<<TEXT
/Running migrations:
All migrations have completed./
TEXT;
		$this->assertRegExp(str_replace("\r\n", "\n", $pattern), str_replace("\n\n", "\n", $result));
	}

/**
 * TestFromComparisonTableActions method
 *
 * @return void
 */
	public function testFromComparisonTableActions() {
		$comparison = array(
			'users' => array('add' => $this->tables['users']),
			'posts' => array('add' => $this->tables['posts'])
		);
		$oldTables = array();
		$result = $this->Shell->fromComparison(array(), $comparison, $oldTables, $this->tables);
		$expected = array(
			'up' => array('create_table' => $this->tables),
			'down' => array('drop_table' => array('users', 'posts'))
		);
		$this->assertEquals($result, $expected);

		$comparison = array('posts' => array('add' => $this->tables['posts']));
		$oldTables = array('users' => $this->tables['users']);
		$result = $this->Shell->fromComparison(array(), $comparison, $oldTables, $this->tables);
		$expected = array(
			'up' => array(
				'create_table' => array('posts' => $this->tables['posts'])
			),
			'down' => array(
				'drop_table' => array('posts')
			)
		);
		$this->assertEquals($result, $expected);

		$comparison = array();
		$oldTables = array('posts' => $this->tables['posts'], 'users' => $this->tables['users']);
		$currentTables = array('users' => $this->tables['users']);
		$result = $this->Shell->fromComparison(array(), $comparison, $oldTables, $currentTables);
		$expected = array(
			'up' => array(
				'drop_table' => array('posts')
			),
			'down' => array(
				'create_table' => array('posts' => $this->tables['posts'])
			)
		);
		$this->assertEquals($result, $expected);
	}

/**
 * TestFromComparisonFieldActions method
 *
 * @return void
 */
	public function testFromComparisonFieldActions() {
		// Add field/index
		$oldTables = array('posts' => $this->tables['posts']);
		$newTables = array('posts' => array());

		$comparison = array(
			'posts' => array('add' => array(
				'views' => array('type' => 'integer', 'null' => false)
			))
		);
		$result = $this->Shell->fromComparison(array(), $comparison, $oldTables, $newTables);
		$expected = array(
			'up' => array(
				'create_field' => array(
					'posts' => array('views' => array('type' => 'integer', 'null' => false))
				)
			),
			'down' => array(
				'drop_field' => array(
					'posts' => array('views')
				)
			)
		);
		$this->assertEquals($result, $expected);

		$comparison = array(
			'posts' => array('add' => array(
				'indexes' => array('VIEW_COUNT' => array('column' => 'views', 'unique' => false))
			))
		);
		$result = $this->Shell->fromComparison(array(), $comparison, $oldTables, $newTables);
		$expected = array(
			'up' => array(
				'create_field' => array(
					'posts' => array(
						'indexes' => array('VIEW_COUNT' => array('column' => 'views', 'unique' => false))
					)
				)
			),
			'down' => array(
				'drop_field' => array(
					'posts' => array('indexes' => array('VIEW_COUNT'))
				)
			)
		);
		$this->assertEquals($result, $expected);

		$comparison = array(
			'posts' => array('add' => array(
				'views' => array('type' => 'integer', 'null' => false),
				'indexes' => array('VIEW_COUNT' => array('column' => 'views', 'unique' => false))
			))
		);
		$result = $this->Shell->fromComparison(array(), $comparison, $oldTables, $newTables);
		$expected = array(
			'up' => array(
				'create_field' => array(
					'posts' => array(
						'views' => array('type' => 'integer', 'null' => false),
						'indexes' => array('VIEW_COUNT' => array('column' => 'views', 'unique' => false))
					)
				)
			),
			'down' => array(
				'drop_field' => array(
					'posts' => array('views', 'indexes' => array('VIEW_COUNT'))
				)
			)
		);
		$this->assertEquals($result, $expected);

		// Drop field/index
		$oldTables['posts']['views'] = array('type' => 'integer', 'null' => false);
		$oldTables['posts']['indexes'] = array('VIEW_COUNT' => array('column' => 'views', 'unique' => false));

		$comparison = array(
			'posts' => array('drop' => array(
				'views' => array('type' => 'integer', 'null' => false)
			))
		);
		$result = $this->Shell->fromComparison(array(), $comparison, $oldTables, $newTables);
		$expected = array(
			'up' => array(
				'drop_field' => array(
					'posts' => array('views')
				)
			),
			'down' => array(
				'create_field' => array(
					'posts' => array('views' => array('type' => 'integer', 'null' => false))
				)
			)
		);
		$this->assertEquals($result, $expected);

		$comparison = array(
			'posts' => array('drop' => array(
				'indexes' => array('VIEW_COUNT' => array('column' => 'views', 'unique' => false))
			))
		);
		$result = $this->Shell->fromComparison(array(), $comparison, $oldTables, $newTables);
		$expected = array(
			'up' => array(
				'drop_field' => array(
					'posts' => array('indexes' => array('VIEW_COUNT'))
				)
			),
			'down' => array(
				'create_field' => array(
					'posts' => array('indexes' => array('VIEW_COUNT' => array('column' => 'views', 'unique' => false)))
				)
			)
		);
		$this->assertEquals($result, $expected);

		$comparison = array(
			'posts' => array('drop' => array(
				'views' => array('type' => 'integer', 'null' => false),
				'indexes' => array('VIEW_COUNT' => array('column' => 'views', 'unique' => false))
			))
		);
		$result = $this->Shell->fromComparison(array(), $comparison, $oldTables, $newTables);
		$expected = array(
			'up' => array(
				'drop_field' => array(
					'posts' => array('views', 'indexes' => array('VIEW_COUNT'))
				)
			),
			'down' => array(
				'create_field' => array(
					'posts' => array(
						'views' => array('type' => 'integer', 'null' => false),
						'indexes' => array('VIEW_COUNT' => array('column' => 'views', 'unique' => false))
					)
				)
			)
		);
		$this->assertEquals($result, $expected);

		// Change field
		$comparison = array(
			'posts' => array('change' => array(
				'views' => array('type' => 'integer', 'null' => false, 'length' => 2),
			))
		);
		$result = $this->Shell->fromComparison(array(), $comparison, $oldTables, $newTables);
		$expected = array(
			'up' => array(
				'alter_field' => array(
					'posts' => array(
						'views' => array('type' => 'integer', 'null' => false, 'length' => 2)
					)
				)
			),
			'down' => array(
				'alter_field' => array(
					'posts' => array(
						'views' => array('type' => 'integer', 'null' => false)
					)
				)
			)
		);
		$this->assertEquals($result, $expected);

		// Change field with/out length
		$oldTables = array('users' => $this->tables['users']);
		$newTables = array('users' => array());
		$oldTables['users']['last_login'] = array('type' => 'integer', 'null' => false, 'length' => 11);

		$comparison = array(
			'users' => array('change' => array(
				'last_login' => array('type' => 'datetime', 'null' => false),
			))
		);
		$result = $this->Shell->fromComparison(array(), $comparison, $oldTables, $newTables);
		$expected = array(
			'up' => array(
				'alter_field' => array(
					'users' => array(
						'last_login' => array('type' => 'datetime', 'null' => false, 'length' => null)
					)
				)
			),
			'down' => array(
				'alter_field' => array(
					'users' => array(
						'last_login' => array('type' => 'integer', 'null' => false, 'length' => 11)
					)
				)
			)
		);
		$this->assertEquals($result, $expected);
	}

/**
 * TestWriteMigration method
 *
 * @return void
 */
	public function testWriteMigration() {
		// Remove if exists
		$this->_unlink(array(TMP . 'tests' . DS . '12345_migration_test_file.php'));

		$users = $this->tables['users'];
		$users['indexes'] = array('UNIQUE_USER' => array('column' => 'user', 'unique' => true));

		$migration = array(
			'up' => array(
				'create_table' => array('users' => $users),
				'create_field' => array(
					'posts' => array(
						'views' => array('type' => 'integer', 'null' => false),
						'indexes' => array('VIEW_COUNT' => array('column' => 'views', 'unique' => false))
					)
				)
			),
			'down' => array(
				'drop_table' => array('users'),
				'drop_field' => array(
					'posts' => array('views', 'indexes' => array('VIEW_COUNT'))
				)
			)
		);
		$this->assertFalse(file_exists(TMP . 'tests' . DS . '12345_migration_test_file.php'));
		$this->assertTrue($this->Shell->writeMigration('migration_test_file', 12345, $migration));
		$this->assertTrue(file_exists(TMP . 'tests' . DS . '12345_migration_test_file.php'));

		$result = $this->_getMigrationVariable(TMP . 'tests' . DS . '12345_migration_test_file.php');
		$expected = <<<TEXT
	public \$migration = array(
		'up' => array(
			'create_table' => array(
				'users' => array(
					'id' => array('type' => 'integer', 'key' => 'primary'),
					'user' => array('type' => 'string', 'null' => false),
					'password' => array('type' => 'string', 'null' => false),
					'created' => 'datetime',
					'updated' => 'datetime',
					'indexes' => array(
						'UNIQUE_USER' => array('column' => 'user', 'unique' => true),
					),
				),
			),
			'create_field' => array(
				'posts' => array(
					'views' => array('type' => 'integer', 'null' => false),
					'indexes' => array(
						'VIEW_COUNT' => array('column' => 'views', 'unique' => false),
					),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'users'
			),
			'drop_field' => array(
				'posts' => array('views', 'indexes' => array('VIEW_COUNT')),
			),
		),
	);
TEXT;
		$this->assertEquals($result, str_replace("\r\n", "\n", $expected));
		$this->_unlink(array(TMP . 'tests' . DS . '12345_migration_test_file.php'));
	}

/**
 * Test writing migration that only contains index changes
 *
 * @return void
 * @link https://github.com/CakeDC/migrations/issues/189
 */
	public function testWriteMigrationIndexesOnly() {
		$this->_unlink(array(TMP . 'tests' . DS . '12346_migration_test_file.php'));

		$users = $this->tables['users'];
		$users['indexes'] = array('UNIQUE_USER' => array('column' => 'user', 'unique' => true));
		$migration = array(
			'up' => array(
				'create_field' => array(
					'posts' => array(
						'indexes' => array(
							'USER_ID' => array('column' => 'user_id', 'unique' => false)
						)
					)
				)
			),
			'down' => array(
				'drop_field' => array(
					'posts' => array(
						'indexes' => array('USER_ID')
					)
				)
			)
		);

		$this->assertTrue($this->Shell->writeMigration('migration_test_file', 12346, $migration));
		$this->assertTrue(file_exists(TMP . 'tests' . DS . '12346_migration_test_file.php'));

		$result = $this->_getMigrationVariable(TMP . 'tests' . DS . '12346_migration_test_file.php');
		$expected = <<<TEXT
	public \$migration = array(
		'up' => array(
			'create_field' => array(
				'posts' => array(
					'indexes' => array(
						'USER_ID' => array('column' => 'user_id', 'unique' => false),
					),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'posts' => array('indexes' => array('USER_ID')),
			),
		),
	);
TEXT;
		$this->assertEquals($result, str_replace("\r\n", "\n", $expected));
		$this->_unlink(array(TMP . 'tests' . DS . '12346_migration_test_file.php'));
	}

/**
 * TestGenerate method
 *
 * @return void
 */
	public function testGenerate() {
		$this->Shell->expects($this->at(0))->method('in')->will($this->returnValue('n'));
		$this->Shell->expects($this->at(1))->method('in')->will($this->returnValue('n'));
		$this->Shell->expects($this->at(2))->method('in')->will($this->returnValue('n'));
		$this->Shell->expects($this->at(3))->method('in')->will($this->returnValue('Initial Schema'));

		$this->Shell->params['overwrite'] = false;
		$this->Shell->generate();

		$files = glob(TMP . 'tests' . DS . '*initial_schema.php');
		$this->_unlink($files);
		$this->assertNotEmpty(preg_grep('/([0-9])+_initial_schema\.php$/i', $files));
	}

/**
 * TestGenerate2 method
 *
 * @return void
 */
	public function testGenerate2() {
		$this->Shell->expects($this->atLeastOnce())->method('err');
		$this->Shell->expects($this->at(0))->method('in')->will($this->returnValue('n'));
		$this->Shell->expects($this->at(1))->method('in')->will($this->returnValue('n'));
		$this->Shell->expects($this->at(2))->method('in')->will($this->returnValue('n'));
		$this->Shell->expects($this->at(3))->method('in')->will($this->returnValue('002 invalid name'));
		$this->Shell->expects($this->at(5))->method('in')->will($this->returnValue('invalid-name'));
		$this->Shell->expects($this->at(7))->method('in')->will($this->returnValue('create some sample_data'));

		$this->Shell->params['overwrite'] = false;
		$this->Shell->generate();

		$files = glob(TMP . 'tests' . DS . '*create_some_sample_data.php');
		$this->_unlink($files);
		$this->assertNotEmpty(preg_grep('/([0-9])+_create_some_sample_data\.php$/i', $files));
	}

/**
 * TestGenerateComparison method
 *
 * @return void
 */
	public function testGenerateComparison() {
		$this->Shell->type = 'TestMigrationPlugin4';
		$this->Shell->expects($this->at(0))->method('in')->will($this->returnValue('y'));
		$this->Shell->expects($this->at(2))->method('in')->will($this->returnValue('n'));
		$this->Shell->expects($this->at(3))->method('in')->will($this->returnValue('drop slug field'));
		$this->Shell->expects($this->at(4))->method('in')->will($this->returnValue('y'));
		$this->Shell->expects($this->at(5))->method('dispatchShell')->with('schema generate --connection test --force --file schema.php --name TestMigrationPlugin4');

		$this->Shell->Version->expects($this->any())->method('getMapping')->will($this->returnCallback(array($this, 'returnMapping')));

		$this->assertEmpty(glob(TMP . 'tests' . DS . '*drop_slug_field.php'));
		$this->Shell->params = array(
			'force' => true,
			'overwrite' => false
		);
		$this->Shell->generate();
		$files = glob(TMP . 'tests' . DS . '*drop_slug_field.php');
		$this->assertNotEmpty($files);

		$result = $this->_getMigrationVariable(current($files));
		$this->_unlink($files);
		$this->assertNotRegExp('/\'schema_migrations\'/', $result);

		$pattern = <<<TEXT
/			'drop_field' => array\(
				'articles' => array\('slug'\),
			\),/
TEXT;
		$this->assertRegExp(str_replace("\r\n", "\n", $pattern), $result);

		$pattern = <<<TEXT
/			'create_field' => array\(
				'articles' => array\(
					'slug' => array\('type' => 'string', 'null' => false\),
				\),
			\),/
TEXT;
		$this->assertRegExp(str_replace("\r\n", "\n", $pattern), $result);
	}

	public function returnMapping() {
		return array(
			gmdate('U') => array('class' => 'M4af9d15154844819b7a0007058157726')
		);
	}

/**
 * TestGenerateInverseComparison method
 *
 * @return void
 */
	public function testGenerateInverseComparison() {
		$this->Shell->type = 'TestMigrationPlugin4';
		$this->Shell->expects($this->at(0))->method('in')->will($this->returnValue('n'));
		$this->Shell->expects($this->at(1))->method('in')->will($this->returnValue('y'));
		$this->Shell->expects($this->at(3))->method('in')->will($this->returnValue('n'));
		$this->Shell->expects($this->at(4))->method('in')->will($this->returnValue('create slug field'));

		$this->Shell->Version->expects($this->any())->method('getMapping')->will($this->returnCallback(array($this, 'returnMapping')));

		$this->assertEmpty(glob(TMP . 'tests' . DS . '*create_slug_field.php'));
		$this->Shell->params = array(
			'force' => true,
			'overwrite' => false
		);
		$this->Shell->generate();
		$files = glob(TMP . 'tests' . DS . '*create_slug_field.php');
		$this->assertNotEmpty($files);

		$result = $this->_getMigrationVariable(current($files));
		$this->_unlink($files);
		$this->assertNotRegExp('/\'schema_migrations\'/', $result);

		$pattern = <<<TEXT
/			'create_field' => array\(
				'articles' => array\(
					'slug' => array\('type' => 'string', 'null' => false, 'after' => 'title'\),
				\),
			\),/
TEXT;
		$this->assertRegExp(str_replace("\r\n", "\n", $pattern), $result);

		$pattern = <<<TEXT
/			'drop_field' => array\(
				'articles' => array\('slug'\),
			\),/
TEXT;
		$this->assertRegExp(str_replace("\r\n", "\n", $pattern), $result);
	}

/**
 * testGenerateFromCliParamsCreateTable method
 * test the case of using a command such as:
 * app/Console/cake Migrations.migration generate create_products id created modified name description:text in_stock:boolean price:float stock_count:integer
 *
 * @return void
 */
	public function testGenerateFromCliParamsCreateTable() {
		$this->Shell->expects($this->at(0))->method('in')->will($this->returnValue('n'));
		$this->assertEmpty(glob(TMP . 'tests' . DS . '*create_products.php'));

		$this->Shell->args = array('create_products', 'id', 'created', 'modified', 'name', 'description:text', 'in_stock:boolean', 'price:float', 'stock_count:integer');
		$this->Shell->params = array(
			'force' => true,
			'overwrite' => false
		);
		$this->Shell->generate();
		$files = glob(TMP . 'tests' . DS . '*create_products.php');
		$this->assertNotEmpty($files);
		$result = $this->_getMigrationVariable(current($files));
		$this->_unlink($files);

		$expected = file_get_contents(CakePlugin::path('Migrations') . '/Test/Fixture/test_migration_create_table_from_cli.txt');
		$this->assertEquals($expected, $result);
	}

/**
 * testGenerateFromCliParamsDropTable method
 * test the case of using a command such as:
 * app/Console/cake Migrations.migration generate drop_products
 *
 * @return void
 */
	public function testGenerateFromCliParamsDropTable() {
		$this->Shell->expects($this->at(0))->method('in')->will($this->returnValue('n'));
		$this->assertEmpty(glob(TMP . 'tests' . DS . '*drop_products.php'));

		$this->Shell->args = array('drop_products');
		$this->Shell->params = array(
			'force' => true,
			'overwrite' => false
		);
		$this->Shell->generate();
		$files = glob(TMP . 'tests' . DS . '*drop_products.php');
		$this->assertNotEmpty($files);
		$result = $this->_getMigrationVariable(current($files));
		$this->_unlink($files);

		$expected = file_get_contents(CakePlugin::path('Migrations') . '/Test/Fixture/test_migration_drop_table_from_cli.txt');
		$this->assertEquals($expected, $result);
	}

/**
 * testGenerateFromCliParamsAddFields method
 * test the case of using a command such as:
 * app/Console/cake Migrations.migration generate add_all_fields_to_products id created modified name description:text in_stock:boolean price:float stock_count:integer
 *
 * @return void
 */
	public function testGenerateFromCliParamsAddFields() {
		$this->Shell->expects($this->at(0))->method('in')->will($this->returnValue('n'));
		$this->assertEmpty(glob(TMP . 'tests' . DS . '*add_all_fields_to_products.php'));

		$this->Shell->args = array('add_all_fields_to_products', 'id', 'created', 'modified', 'name', 'description:text', 'in_stock:boolean', 'price:float', 'stock_count:integer');
		$this->Shell->params = array(
			'force' => true,
			'overwrite' => false
		);
		$this->Shell->generate();
		$files = glob(TMP . 'tests' . DS . '*add_all_fields_to_products.php');
		$this->assertNotEmpty($files);
		$result = $this->_getMigrationVariable(current($files));
		$this->_unlink($files);

		$expected = file_get_contents(CakePlugin::path('Migrations') . '/Test/Fixture/test_migration_add_fields_from_cli.txt');
		$this->assertEquals($expected, $result);
	}

/**
 * testGenerateFromCliParamsRemoveFields method
 * test the case of using a command such as:
 * app/Console/cake Migrations.migration generate remove_name_and_desc_from_products name description
 *
 * @return void
 */
	public function testGenerateFromCliParamsRemoveFields() {
		$this->Shell->expects($this->at(0))->method('in')->will($this->returnValue('n'));
		$this->assertEmpty(glob(TMP . 'tests' . DS . '*remove_name_and_desc_from_products.php'));

		$this->Shell->args = array('remove_name_and_desc_from_products', 'name', 'description');
		$this->Shell->params = array(
			'force' => true,
			'overwrite' => false
		);
		$this->Shell->generate();
		$files = glob(TMP . 'tests' . DS . '*remove_name_and_desc_from_products.php');
		$this->assertNotEmpty($files);
		$result = $this->_getMigrationVariable(current($files));
		$this->_unlink($files);

		$expected = file_get_contents(CakePlugin::path('Migrations') . '/Test/Fixture/test_migration_remove_fields_from_cli.txt');
		$this->assertEquals($expected, $result);
	}

/**
 * TestGenerateDump method
 *
 * @return void
 */
	public function testGenerateDump() {
		$this->Shell->expects($this->at(0))->method('in')->will($this->returnValue('y'));
		$this->Shell->expects($this->at(2))->method('in')->will($this->returnValue('n'));
		$this->Shell->expects($this->at(3))->method('in')->will($this->returnValue('schema dump'));

		$this->Shell->Version->expects($this->any())->method('getMapping')->will($this->returnCallback(array($this, 'returnMapping')));

		$this->assertEmpty(glob(TMP . 'tests' . DS . '*schema_dump.php'));
		$this->Shell->type = 'TestMigrationPlugin2';
		$this->Shell->params = array(
			'force' => true,
			'dry' => false,
			'precheck' => 'Migrations.PrecheckException',
			'overwrite' => false
		);
		$this->Shell->generate();
		$files = glob(TMP . 'tests' . DS . '*schema_dump.php');
		$this->assertNotEmpty($files);

		$result = $this->_getMigrationVariable(current($files));
		$this->_unlink($files);

		$expected = file_get_contents(CakePlugin::path('Migrations') . '/Test/Fixture/test_migration.txt');
		$expected = str_replace("\r\n", "\n", $expected);
		$this->assertEquals($expected, $result);
	}

/**
 * TestStatus method
 *
 * @return void
 */
	public function testMigrationStatus() {
		$this->Shell->Version = new MigrationVersion(array('connection' => 'test'));
		$this->Shell->status();
		$result = $this->Shell->output;
		$pattern = <<<TEXT
/Migrations Plugin

Current version:
  #003 003_increase_class_name_length
Latest version:
  #003 003_increase_class_name_length/
TEXT;
		$this->assertRegExp(str_replace("\r\n", "\n", $pattern), $result);
		$this->Shell->Version->setVersion(3, 'migrations', false);
		$this->Shell->output = '';
		$this->Shell->args = array('outdated');
		$this->Shell->status();
		$result = $this->Shell->output;
		$pattern = <<<TEXT
/Migrations Plugin

Current version:
  #002 002_convert_version_to_class_names
Latest version:
  #003 003_increase_class_name_length/
TEXT;
		$this->assertRegExp(str_replace("\r\n", "\n", $pattern), $result);
	}

/**
 * Strip all the content surrounding the $migration variable
 *
 * @param string $file
 * @return string
 */
	protected function _getMigrationVariable($file) {
		$result = array();
		$array = explode("\n", str_replace("\r\n", "\n", file_get_contents($file)));
		foreach ($array as $line) {
			if ($line === "\tpublic \$migration = array(") {
				$result[] = $line;
			} elseif (!empty($result) && $line === "\t);") {
				$result[] = $line;
				break;
			} elseif (!empty($result)) {
				$result[] = $line;
			}
		}
		return implode("\n", $result);
	}

/**
 * Unlink test files from filesystem
 *
 * @param array Absolute paths to unlink
 * @return void
 */
	protected function _unlink($files) {
		foreach ($files as $file) {
			@unlink($file);
		}
	}

}

