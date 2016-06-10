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

App::uses('CakeMigration', 'Migrations.Lib');
App::uses('MigrationVersion', 'Migrations.Lib');

/**
 * TestCakeMigration
 */
class TestCakeMigration extends CakeMigration {

/**
 * Connection used
 *
 * @var string
 */
	public $connection = 'test';
}

/**
 * TestCallbackCakeMigration
 */
class TestCallbackCakeMigration {

/**
 * Calls property
 *
 * @var array
 */
	public $calls = array();

/**
 * BeforeMigration method
 *
 * @param $Migration
 * @param $type
 * @return void
 */
	public function beforeMigration(&$Migration, $type) {
		$this->calls[$Migration->direction]['beforeMigration'] = $type;
	}

/**
 * AfterMigration method
 *
 * @param $Migration
 * @param $type
 * @return void
 */
	public function afterMigration(&$Migration, $type) {
		$this->calls[$Migration->direction]['afterMigration'] = $type;
	}

/**
 * BeforeAction method
 *
 * @param $Migration
 * @param $type
 * @param $data
 * @return void
 */
	public function beforeAction(&$Migration, $type, $data) {
		$this->calls[$Migration->direction]['beforeAction'][] = array('type' => $type, 'data' => $data);
	}

/**
 * AfterAction method
 *
 * @param $Migration
 * @param $type
 * @param $data
 * @return void
 */
	public function afterAction(&$Migration, $type, $data) {
		$this->calls[$Migration->direction]['afterAction'][] = array('type' => $type, 'data' => $data);
	}

}

/**
 * CakeMigrationTest
 */
class CakeMigrationTest extends CakeTestCase {

/**
 * Fixtures property
 *
 * @var array
 */
	public $fixtures = array(
		'core.user', 'core.post');

/**
 * AutoFixtures property
 *
 * @var array
 */
	public $autoFixtures = false;

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
 * @var DboSource
 */
	public $db;

/**
 * Start test
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		Configure::write('Config.language', 'en');
	}

/**
 * TestCreateTable method
 *
 * @return void
 */
	public function testCreateDropTable() {
		$migration = new TestCakeMigration(array(
			'up' => array('create_table' => array('migration_posts' => $this->tables['posts'], 'migration_users' => $this->tables['users'])),
			'down' => array('drop_table' => array('migration_posts', 'migration_users'))
		));

		$sources = $this->db->listSources();
		$this->assertFalse(in_array($this->db->fullTableName('migration_user', false, false), $sources));
		$this->assertFalse(in_array($this->db->fullTableName('migration_posts', false, false), $sources));
		$this->assertTrue($migration->run('up'));
		$sources = $this->db->listSources();
		$this->assertTrue(in_array($this->db->fullTableName('migration_users', false, false), $sources));
		$this->assertTrue(in_array($this->db->fullTableName('migration_posts', false, false), $sources));

		try {
			$migration->run('up');
			$this->fail('No exception triggered');
		} catch (MigrationException $e) {
			$this->assertEquals('Table "' . $this->db->fullTableName('migration_posts', false, false) . '" already exists in database.', $e->getMessage());
		}

		$this->assertTrue($migration->run('down'));
		$sources = $this->db->listSources();
		$this->assertFalse(in_array($this->db->fullTableName('migration_users', false, false), $sources));
		$this->assertFalse(in_array($this->db->fullTableName('migration_posts', false, false), $sources));
	}

/**
 * Test create and drop tables with a prefix
 *
 * @return void
 */
	public function testRunDownWithPrefix() {
		$options = ConnectionManager::getDataSource('test')->config;
		$prefix = 'prefix_';
		$options['prefix'] = $prefix;
		ConnectionManager::create('test2', $options);

		$migration = new TestCakeMigration(array(
			'up' => array('create_table' => array('migration_posts' => $this->tables['posts'], 'migration_users' => $this->tables['users'])),
			'down' => array('drop_table' => array('migration_posts', 'migration_users')),
			'connection' => 'test2'
		));

		$sources = $this->db->listSources();
		$this->assertFalse(in_array($this->db->fullTableName($prefix . 'migration_user', false, false), $sources));
		$this->assertFalse(in_array($this->db->fullTableName($prefix . 'migration_posts', false, false), $sources));
		$this->assertTrue($migration->run('up'));
		$sources = $this->db->listSources();
		$this->assertTrue(in_array($this->db->fullTableName($prefix . 'migration_users', false, false), $sources));
		$this->assertTrue(in_array($this->db->fullTableName($prefix . 'migration_posts', false, false), $sources));

		$this->assertTrue($migration->run('down'));
		$sources = $this->db->listSources();
		$this->assertFalse(in_array($this->db->fullTableName($prefix . 'migration_users', false, false), $sources));
		$this->assertFalse(in_array($this->db->fullTableName($prefix . 'migration_posts', false, false), $sources));
	}

/**
 * TestRenameTable method
 *
 * @return void
 */
	public function testRenameTable() {
		$this->loadFixtures('User', 'Post');

		$migration = new TestCakeMigration(array(
			'up' => array('rename_table' => array('posts' => 'renamed_posts')),
			'down' => array('rename_table' => array('renamed_posts' => 'posts'))
		));

		$sources = $this->db->listSources();
		$this->assertTrue(in_array($this->db->fullTableName('posts', false, false), $sources));
		$this->assertFalse(in_array($this->db->fullTableName('renamed_posts', false, false), $sources));

		$this->assertTrue($migration->run('up'));
		$sources = $this->db->listSources();
		$this->assertFalse(in_array($this->db->fullTableName('posts', false, false), $sources));
		$this->assertTrue(in_array($this->db->fullTableName('renamed_posts', false, false), $sources));

		try {
			$migration->run('up');
			$this->fail('No exception triggered');
		} catch (MigrationException $e) {
			$this->assertEquals($e->getMessage(), 'Table "' . $this->db->fullTableName('renamed_posts', false, false) . '" already exists in database.');
		}

		$this->assertTrue($migration->run('down'));
		$sources = $this->db->listSources();
		$this->assertTrue(in_array($this->db->fullTableName('posts', false, false), $sources));
		$this->assertFalse(in_array($this->db->fullTableName('renamed_posts', false, false), $sources));
	}

/**
 * TestCreateDropField method
 *
 * @return void
 */
	public function testCreateDropField() {
		$this->loadFixtures('User', 'Post');
		$model = new Model(array('table' => 'posts', 'ds' => 'test'));

		$migration = new TestCakeMigration(array(
			'up' => array(
				'create_field' => array(
					'posts' => array('views' => array('type' => 'integer', 'null' => false))
				)
			),
			'down' => array(
				'drop_field' => array('posts' => array('views'))
			)
		));

		$fields = $this->db->describe($model);
		$this->assertFalse(isset($fields['views']));

		$this->assertTrue($migration->run('up'));
		$fields = $this->db->describe($model);
		$this->assertTrue(isset($fields['views']));

		try {
			$migration->run('up');
			$this->fail('No exception triggered');
		} catch (MigrationException $e) {
			$this->assertEquals('Field "views" already exists in "posts".', $e->getMessage());
		}

		$this->assertTrue($migration->run('down'));
		$fields = $this->db->describe($model);
		$this->assertFalse(isset($fields['views']));

		try {
			$migration->run('down');
			$this->fail('No exception triggered');
		} catch (MigrationException $e) {
			$this->assertEquals('Field "views" does not exist in "posts".', $e->getMessage());
		}
	}

/**
 * TestCreateDropIndex method
 *
 * @return void
 */
	public function testCreateDropIndex() {
		$this->loadFixtures('User', 'Post');
		$model = new Model(array('table' => 'posts', 'ds' => 'test'));

		$migration = new TestCakeMigration(array(
			'up' => array(
				'create_field' => array(
					'posts' => array(
						'views' => array('type' => 'integer', 'null' => false),
						'indexes' => array(
							'VIEW_COUNT' => array('column' => 'views', 'unique' => false),
							'UNIQUE_AUTHOR_TITLE' => array('column' => array('author_id', 'title'), 'unique' => true)
						)
					)
				)
			),
			'down' => array(
				'drop_field' => array('posts' => array('views', 'indexes' => array('UNIQUE_AUTHOR_TITLE')))
			)
		));

		$fields = $this->db->describe($model);
		$this->assertFalse(isset($fields['views']));

		$this->assertTrue($migration->run('up'));
		$fields = $this->db->describe($model);
		$indexes = $this->db->index($model);
		$this->assertTrue(!empty($indexes));
		$uniqueAuthorTitle = array('column' => array(0 => 'author_id', 1 => 'title'), 'unique' => 1);
		$viewCount = array('column' => 'views', 'unique' => 0);
		$this->assertTrue(isset($indexes['UNIQUE_AUTHOR_TITLE']));
		$this->assertTrue(isset($indexes['VIEW_COUNT']));
		$this->assertSame($indexes['UNIQUE_AUTHOR_TITLE'], $uniqueAuthorTitle);
		$this->assertSame($indexes['VIEW_COUNT'], $viewCount);
		$this->assertTrue(isset($fields['views']));
		$this->assertEquals($fields['views']['key'], 'index');

		try {
			$migration->run('up');
			$this->fail('No exception triggered');
		} catch (MigrationException $e) {
			//$this->pass('Exception caught');
		}

		$this->assertTrue($migration->run('down'));
		$fields = $this->db->describe($model);
		$indexes = $this->db->index($model);
		$this->assertFalse(isset($indexes['UNIQUE_AUTHOR_TITLE']));
		$this->assertFalse(isset($indexes['VIEW_COUNT']));
		$this->assertFalse(isset($fields['views']));
	}

/**
 * Test alter index (changing column of an index). Issue #26
 * @return void
 */
	public function testAlterIndex() {
		$this->loadFixtures('Post');
		$model = new Model(array('table' => 'posts', 'ds' => 'test'));
		$fields = $this->db->describe($model);
		$indexes = $this->db->index($model);
		$this->assertTrue(!empty($indexes) && is_array($indexes));
		$this->assertEquals($indexes['PRIMARY']['column'], 'id');
		$this->assertFalse(array_key_exists('key', $fields['published']));
		$this->assertFalse(array_key_exists('key', $fields['title']));
		$migration = new TestCakeMigration(array(
			'up' => array(
				'drop_field' => array(
					'posts' => array('title')
				),
				'create_field' => array(
					'posts' => array(
						'title' => array('type' => 'string', 'null' => false, 'length' => 255),
						'indexes' => array('NEW_INDEX' => array('column' => 'title', 'unique' => false))
					)
				)
			),
			'down' => array(
				'drop_field' => array(
					'posts' => array('title', 'indexes' => array('NEW_INDEX'))
				),
				'create_field' => array(
					'posts' => array(
						'title' => array('type' => 'string', 'null' => false, 'length' => 255, 'after' => 'author_id'),
					)
				)
				)
			)
		);

		$this->assertTrue($migration->run('up'));
		$indexes = $this->db->index($model);
		$this->assertArrayHasKey('NEW_INDEX', $indexes);
		$this->assertEquals($indexes['NEW_INDEX']['column'], 'title');
		$this->assertEquals($indexes['NEW_INDEX']['unique'], 0);

		$migration2 = new TestCakeMigration(array(
			'up' => array(
				'drop_field' => array(
					'posts' => array('published', 'title', 'indexes' => array('NEW_INDEX'))
				),
				'create_field' => array(
					'posts' => array(
						'published' => array('type' => 'string', 'null' => true, 'length' => 1, 'default' => 'N'),
						'title' => array('type' => 'string', 'null' => false, 'length' => 255),
						'indexes' => array('NEW_INDEX' => array('column' => 'published', 'unique' => false))
					)
				)
			),
			'down' => array(
				'drop_field' => array(
					'posts' => array('published', 'title', 'indexes' => array('NEW_INDEX'))
				),
				'create_field' => array(
					'posts' => array(
						'title' => array('type' => 'string', 'null' => false, 'length' => 255),
						'published' => array('type' => 'string', 'null' => true, 'length' => 1, 'default' => 'N', 'after' => 'body'),
						'indexes' => array('NEW_INDEX' => array('column' => 'title', 'unique' => false))
					)
				)
				)
			)
		);

		$this->assertTrue($migration2->run('up'));
		$indexes = $this->db->index($model);
		$fields = $this->db->describe($model);
		$this->assertFalse(array_key_exists('key', $fields['title']));
		$this->assertTrue(array_key_exists('key', $fields['published']));
		$this->assertEquals($indexes['NEW_INDEX']['column'], 'published');
		$this->assertEquals($indexes['NEW_INDEX']['unique'], 0);

		$this->assertTrue($migration2->run('down'));
		$indexes = $this->db->index($model);
		$fields = $this->db->describe($model);
		$this->assertTrue(array_key_exists('key', $fields['title']));
		$this->assertFalse(array_key_exists('key', $fields['published']));
		$this->assertEquals($indexes['NEW_INDEX']['column'], 'title');
		$this->assertEquals($indexes['NEW_INDEX']['unique'], 0);

		$this->assertTrue($migration->run('down'));
		$fields = $this->db->describe($model);
		$indexes = $this->db->index($model);
		$this->assertTrue(!empty($indexes) && is_array($indexes));
		$this->assertEquals($indexes['PRIMARY']['column'], 'id');
		$this->assertFalse(array_key_exists('key', $fields['published']));
		$this->assertFalse(array_key_exists('key', $fields['title']));
	}

/**
 * TestAlterField method
 * @return void
 */
	public function testAlterField() {
		$this->loadFixtures('User', 'Post');
		$model = new Model(array('table' => 'posts', 'ds' => 'test'));

		$migration = new TestCakeMigration(array(
			'up' => array(
				'alter_field' => array(
					'posts' => array('published' => array('default' => 'Y'))
				)
			),
			'down' => array(
				'alter_field' => array(
					'posts' => array('published' => array('default' => 'N'))
				)
			)
		));

		$fields = $this->db->describe($model);
		$this->assertEquals($fields['published']['default'], 'N');

		$this->assertTrue($migration->run('up'));
		$fields = $this->db->describe($model);
		$this->assertEquals($fields['published']['default'], 'Y');

		try {
			$migration->migration['up']['alter_field']['posts']['inexistent'] = array('default' => 'N');
			$migration->run('up');
			$this->fail('No expection triggered');
			$this->setExpectedException('MigrationException');
		} catch (MigrationException $e) {
			$this->assertEquals('Undefined index: inexistent', $e->getMessage());
		}

		$this->assertTrue($migration->run('down'));
		$fields = $this->db->describe($model);
		$this->assertEquals($fields['published']['default'], 'N');
	}

/**
 * Test the change of fields type.
 */
	public function testAlterFieldType() {
		$this->loadFixtures('User', 'Post');
		$model = new Model(array('table' => 'posts', 'ds' => 'test'));
		$migration = new TestCakeMigration(array(
			'up' => array(
				'alter_field' => array(
					'posts' => array('published' => array('type' => 'integer'))
				)
			),
			'down' => array(
				'alter_field' => array(
					'posts' => array('published' => array('type' => 'string', 'length' => 1, 'default' => 'N'))
				)
			)
		));
		$fields = $this->db->describe($model);
		$this->assertEquals($fields['published']['type'], 'string');
		$this->assertTrue($migration->run('up'));
		$fields = $this->db->describe($model);
		$this->assertEquals($fields['published']['type'], 'integer');
		$this->assertTrue($migration->run('down'));
		$fields = $this->db->describe($model);
		$this->assertEquals($fields['published']['type'], 'string');
	}

/**
 * TestAlterFieldLength method
 *
 * @return void
 */
	public function testAlterFieldLength() {
		$this->loadFixtures('User', 'Post');
		$model = new Model(array('table' => 'posts', 'ds' => 'test'));

		$migration = new TestCakeMigration(array(
			'up' => array(
				'alter_field' => array(
					'posts' => array('created' => array('type' => 'integer', 'length' => 11))
				)
			),
			'down' => array(
				'alter_field' => array(
					'posts' => array('created' => array('type' => 'datetime'))
				)
			)
		));

		$fields = $this->db->describe($model);
		$this->assertEquals($fields['created']['type'], 'datetime');
		$this->assertNull($fields['created']['length']);

		$this->assertTrue($migration->run('up'));
		$fields = $this->db->describe($model);
		$this->assertEquals($fields['created']['type'], 'integer');
		$this->assertEquals($fields['created']['length'], 11);

		$this->assertTrue($migration->run('down'));
		$fields = $this->db->describe($model);
		$this->assertEquals($fields['created']['type'], 'datetime');
		$this->assertNull($fields['created']['length']);
	}

/**
 * TestAlterAndRenameField method
 *
 * @return void
 */
	public function testAlterAndRenameField() {
		$this->loadFixtures('User', 'Post');
		$model = new Model(array('table' => 'posts', 'ds' => 'test'));

		$migration = new TestCakeMigration(array(
			'up' => array(
				'alter_field' => array(
					'posts' => array('published' => array('name' => 'renamed_published', 'default' => 'Y'))
				)
			),
			'down' => array(
				'alter_field' => array(
					'posts' => array('renamed_published' => array('name' => 'published', 'default' => 'N'))
				)
			)
		));

		$fields = $this->db->describe($model);
		$this->assertTrue(isset($fields['published']));
		$this->assertFalse(isset($fields['renamed_published']));
		$this->assertEquals($fields['published']['default'], 'N');

		$this->assertTrue($migration->run('up'));
		$fields = $this->db->describe($model);
		$this->assertFalse(isset($fields['published']));
		$this->assertTrue(isset($fields['renamed_published']));
		$this->assertEquals($fields['renamed_published']['default'], 'Y');

		$this->assertTrue($migration->run('down'));
		$fields = $this->db->describe($model);
		$this->assertTrue(isset($fields['published']));
		$this->assertFalse(isset($fields['renamed_published']));
		$this->assertEquals($fields['published']['default'], 'N');
	}

/**
 * TestRenameField method
 *
 * @return void
 */
	public function testRenameField() {
		$this->loadFixtures('User', 'Post');
		$model = new Model(array('table' => 'posts', 'ds' => 'test'));

		$migration = new TestCakeMigration(array(
			'up' => array('rename_field' => array('posts' => array('updated' => 'renamed_updated'))),
			'down' => array('rename_field' => array('posts' => array('renamed_updated' => 'updated'))),
		));

		$fields = $this->db->describe($model);
		$this->assertTrue(isset($fields['updated']));
		$this->assertFalse(isset($fields['renamed_updated']));

		$this->assertTrue($migration->run('up'));
		$fields = $this->db->describe($model);
		$this->assertFalse(isset($fields['updated']));
		$this->assertTrue(isset($fields['renamed_updated']));

		try {
			$migration->run('up');
			$this->fail('No exception triggered');
			$this->setExpectedException('MigrationException');
		} catch (MigrationException $e) {
			$this->assertEquals('Field "renamed_updated" already exists in "posts".', $e->getMessage());
		}

		$this->assertTrue($migration->run('down'));
		$fields = $this->db->describe($model);
		$this->assertTrue(isset($fields['updated']));
		$this->assertFalse(isset($fields['renamed_updated']));

		try {
			$migration->run('down');
			$this->fail('No exception triggered');
			$this->setExpectedException('MigrationException');
		} catch (MigrationException $e) {
			$this->assertEquals('Field "updated" already exists in "posts".', $e->getMessage());
		}
	}

/**
 * TestCallbacks method
 *
 * @return void
 */
	public function testCallbacks() {
		$this->loadFixtures('User');

		$callback = new TestCallbackCakeMigration();
		$migration = new TestCakeMigration(array(
			'up' => array(
				'create_table' => array('migration_posts' => $this->tables['posts']),
				'create_field' => array(
					'users' => array(
						'email' => array('type' => 'string', 'null' => false),
						'indexes' => array('UNIQUE_USER' => array('column' => 'user', 'unique' => true))
					)
				),
			),
			'down' => array(
				'drop_table' => array('migration_posts'),
				'drop_field' => array('users' => array('email', 'indexes' => array('UNIQUE_USER')))
			),
			'callback' => $callback
		));

		$this->assertTrue($migration->run('up'));
		$this->assertTrue(isset($callback->calls['up']));
		$result = $callback->calls['up'];
		$expected = array(
			array('type' => 'create_table', 'data' => array('table' => 'migration_posts')),
			array('type' => 'add_field', 'data' => array('table' => 'users', 'field' => 'email')),
			array('type' => 'add_index', 'data' => array('table' => 'users', 'index' => 'UNIQUE_USER')));

		$this->assertEquals($result['afterMigration'], 'up');
		$this->assertEquals($result['beforeMigration'], 'up');
		$this->assertEquals($result['afterAction'], $expected);
		$this->assertEquals($result['beforeAction'], $expected);
		$this->assertEquals(array_keys($result), array('beforeMigration', 'beforeAction', 'afterAction', 'afterMigration'));

		$this->assertTrue($migration->run('down'));
		$this->assertTrue(isset($callback->calls['down']));
		$result = $callback->calls['down'];
		$expected = array(
			array('type' => 'drop_table', 'data' => array('table' => 'migration_posts')),
			array('type' => 'drop_index', 'data' => array('table' => 'users', 'index' => 'UNIQUE_USER')),
			array('type' => 'drop_field', 'data' => array('table' => 'users', 'field' => 'email')));

		$this->assertEquals($result['afterMigration'], 'down');
		$this->assertEquals($result['beforeMigration'], 'down');
		$this->assertEquals($result['afterAction'], $expected);
		$this->assertEquals($result['beforeAction'], $expected);
		$this->assertEquals(array_keys($result), array('beforeMigration', 'beforeAction', 'afterAction', 'afterMigration'));
	}

	public function testTruncateLongIndexKey() {
		$migration = new TestCakeMigration(array(
			'up' => array(
				'create_table' => array(
					'migration_categories' => array(
						'id' => array(
							'type' => 'string',
							'length ' => 36,
							'null' => false,
							'key' => 'primary'
						),
						'description' => array(
							'type' => 'string',
							'null' => false,
							'length' => 768,
							'default' => null
						),
						'info' => array(
							'type' => 'string',
							'length' => 256,
							'null' => false,
							'default' => null
						),
						'indexes' => array(
							'TESTING_INDEX' => array(
								'column' => array(
									'description',
									'info'
								),
								'unique' => 1
							)
						)
					)
				)
			),
			'down' => array(
				'drop_table' => array('migration_categories')
			)
		));
		$sources = $this->db->listSources();
		$this->assertFalse(in_array($this->db->fullTableName('migration_categories', false, false), $sources));
		try {
			$migration->run('up');
			$this->fail('No exception triggered');
		} catch (MigrationException $e) {
			$this->assertRegExp('/SQL Error/', $e->getMessage());
		}
		$this->assertFalse(in_array($this->db->fullTableName('migration_categories', false, false), $sources));
	}

/**
 * TestGenerateModel method
 *
 * @return void
 */
	public function testGenerateModel() {
		$migration = new TestCakeMigration();

		$return = $migration->generateModel('Post');
		$this->assertInstanceOf('AppModel', $return);
		$this->assertEquals($return->name, 'Post');
		$this->assertEquals($return->table, 'posts');

		$return = $migration->generateModel('Post', 'users');
		$this->assertInstanceOf('AppModel', $return);
		$this->assertEquals($return->name, 'Post');
		$this->assertEquals($return->table, 'users');
	}

/**
 * Test run method with invalid syntaxes
 *
 * @return void
 */
	public function testRunInvalidSyntaxes() {
		$migration = new TestCakeMigration(array(
			'up' => array('do_something' => array('posts' => array('updated' => 'renamed_updated'))),
			'down' => array('undo_something' => array('posts' => array('renamed_updated' => 'updated'))),
		));

		try {
			$migration->run('last');
			$this->fail('No exception triggered');
		} catch (MigrationException $e) {
			$this->assertEquals('Migration direction (last) is not one of valid directions.', $e->getMessage());
		}

		try {
			$migration->run('up');
			$this->fail('No exception triggered');
		} catch (MigrationException $e) {
			$this->assertEquals('Migration action type (do_something) is not one of valid actions type.', $e->getMessage());
		}
	}
}
