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

App::uses('MigrationVersion', 'Migrations.Lib');
App::uses('CakeMigration', 'Migrations.Lib');

/**
 * TestPrecheckCakeMigration
 */
class TestPrecheckCakeMigration extends CakeMigration {

/**
 * Connection used
 *
 * @var string
 */
	public $connection = 'test';

/**
 * Initialize db connection
 */
	public function initDb() {
		$this->db = ConnectionManager::getDataSource($this->connection);
		$this->db->cacheSources = false;
	}

}

class PrecheckConditionTest extends CakeTestCase {

/**
 * Fixtures property
 *
 * @var array
 */
	public $fixtures = array(
		'core.user',
		'core.post');

/**
 * AutoFixtures property
 *
 * @var array
 */
	public $autoFixtures = false;

/**
 * @var DboSource
 */
	public $db;

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
 * TestCreateTable method
 *
 * @return void
 */
	public function testCreateDropTable() {
		$Migration = new TestPrecheckCakeMigration(array(
			'up' => array('create_table' => array(
				'migration_posts' => $this->tables['posts'],
				'migration_users' => $this->tables['users'])),
			'down' => array(
				'drop_table' => array('migration_posts', 'migration_users')),
			'precheck' => 'Migrations.PrecheckCondition'
		));
		$Migration->initDb();

		$this->assertTrue($Migration->Precheck->beforeAction($Migration, 'create_table', array('table' => $this->db->fullTableName('migration_posts', false, false))));

		$sources = $this->db->listSources();
		$this->assertFalse(in_array($this->db->fullTableName('migration_user', false, false), $sources));
		$this->assertFalse(in_array($this->db->fullTableName('migration_posts', false, false), $sources));
		$this->assertTrue($Migration->run('up'));
		$sources = $this->db->listSources();
		$this->assertTrue(in_array($this->db->fullTableName('migration_users', false, false), $sources));
		$this->assertTrue(in_array($this->db->fullTableName('migration_posts', false, false), $sources));

		// successful second run as precheck skip execution
		try {
			$Migration->run('up');
		} catch (MigrationException $e) {
			$this->fail('Exception triggered');
		}

		$this->assertFalse($Migration->Precheck->beforeAction($Migration, 'create_table', array('table' => $this->db->fullTableName('migration_posts', false, false))));
		$this->assertTrue($Migration->Precheck->beforeAction($Migration, 'drop_table', array('table' => $this->db->fullTableName('migration_posts', false, false))));

		$this->assertTrue($Migration->Precheck->beforeAction($Migration, 'create_table', array('table' => $this->db->fullTableName('no_table', false, false))));
		$this->assertFalse($Migration->Precheck->beforeAction($Migration, 'drop_table', array('table' => $this->db->fullTableName('no_table', false, false))));

		$this->assertTrue($Migration->run('down'));
		$sources = $this->db->listSources();
		$this->assertFalse(in_array($this->db->fullTableName('migration_users', false, false), $sources));
		$this->assertFalse(in_array($this->db->fullTableName('migration_posts', false, false), $sources));
	}

/**
 * TestRenameTable method
 *
 * @return void
 */
	public function testRenameTable() {
		$this->loadFixtures('User', 'Post');

		$Migration = new TestPrecheckCakeMigration(array(
			'up' => array(
				'rename_table' => array('posts' => 'renamed_posts')),
			'down' => array(
				'rename_table' => array('renamed_posts' => 'posts')),
			'precheck' => 'Migrations.PrecheckCondition'
		));
		$Migration->initDb();

		$this->assertTrue($Migration->Precheck->beforeAction($Migration, 'rename_table', array(
			'old_name' => $this->db->fullTableName('posts', false, false),
			'new_name' => $this->db->fullTableName('renamed_posts', false, false),
		)));

		$sources = $this->db->listSources();
		$this->assertTrue(in_array($this->db->fullTableName('posts', false, false), $sources));
		$this->assertFalse(in_array($this->db->fullTableName('renamed_posts', false, false), $sources));

		$this->assertTrue($Migration->run('up'));
		$sources = $this->db->listSources();
		$this->assertFalse(in_array($this->db->fullTableName('posts', false, false), $sources));
		$this->assertTrue(in_array($this->db->fullTableName('renamed_posts', false, false), $sources));

		$this->assertFalse($Migration->Precheck->beforeAction($Migration, 'rename_table', array(
			'old_name' => $this->db->fullTableName('posts', false, false),
			'new_name' => $this->db->fullTableName('renamed_posts', false, false),
		)));
		$this->assertTrue($Migration->Precheck->beforeAction($Migration, 'rename_table', array(
			'old_name' => $this->db->fullTableName('renamed_posts', false, false),
			'new_name' => $this->db->fullTableName('posts', false, false),
		)));

		try {
			$Migration->run('up');
		} catch (MigrationException $e) {
			$this->fail('Exception triggered: ' . $e->getMessage());
		}

		$this->assertTrue($Migration->run('down'));

		$this->assertFalse($Migration->Precheck->beforeAction($Migration, 'rename_table', array(
			'old_name' => $this->db->fullTableName('renamed_posts', false, false),
			'new_name' => $this->db->fullTableName('posts', false, false),
		)));
		$this->assertTrue($Migration->Precheck->beforeAction($Migration, 'rename_table', array(
			'old_name' => $this->db->fullTableName('posts', false, false),
			'new_name' => $this->db->fullTableName('renamed_posts', false, false),
		)));

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

		$Migration = new TestPrecheckCakeMigration(array(
			'up' => array(
				'create_field' => array(
					'posts' => array('views' => array('type' => 'integer', 'null' => false))
				)
			),
			'down' => array(
				'drop_field' => array('posts' => array('views'))
			),
			'precheck' => 'Migrations.PrecheckCondition'
		));
		$Migration->initDb();

		$this->assertTrue($Migration->Precheck->beforeAction($Migration, 'add_field', array(
			'table' => $this->db->fullTableName('posts', false, false),
			'field' => 'views')));

		$fields = $this->db->describe($model);
		$this->assertFalse(isset($fields['views']));

		$this->assertTrue($Migration->run('up'));
		$fields = $this->db->describe($model);
		$this->assertTrue(isset($fields['views']));

		$this->assertFalse($Migration->Precheck->beforeAction($Migration, 'add_field', array(
			'table' => $this->db->fullTableName('posts', false, false),
			'field' => 'views')));
		$this->assertTrue($Migration->Precheck->beforeAction($Migration, 'drop_field', array(
			'table' => $this->db->fullTableName('posts', false, false),
			'field' => 'views')));

		try {
			$Migration->run('up');
		} catch (MigrationException $e) {
			$this->fail('Exception triggered: ' . $e->getMessage());
		}

		$this->assertTrue($Migration->run('down'));
		$fields = $this->db->describe($model);
		$this->assertFalse(isset($fields['views']));

		$this->assertTrue($Migration->Precheck->beforeAction($Migration, 'add_field', array(
			'table' => $this->db->fullTableName('posts', false, false),
			'field' => 'views')));
		$this->assertFalse($Migration->Precheck->beforeAction($Migration, 'drop_field', array(
			'table' => $this->db->fullTableName('posts', false, false),
			'field' => 'views')));

		try {
			$Migration->run('down');
		} catch (MigrationException $e) {
			$this->fail('Exception triggered: ' . $e->getMessage());
		}
	}

/**
 * TestAlterField method
 * @return void
 */
	public function testAlterField() {
		$this->loadFixtures('User', 'Post');
		$Model = new Model(array('table' => 'posts', 'ds' => 'test'));

		$Migration = new TestPrecheckCakeMigration(array(
			'up' => array(
				'alter_field' => array(
					'posts' => array('published' => array('default' => 'Y'))
				)
			),
			'down' => array(
				'alter_field' => array(
					'posts' => array('published' => array('default' => 'N'))
				)
			),
			'precheck' => 'Migrations.PrecheckCondition'));
		$Migration->initDb();

		$fields = $this->db->describe($Model);
		$this->assertEquals($fields['published']['default'], 'N');

		$this->assertTrue($Migration->run('up'));
		$fields = $this->db->describe($Model);
		$this->assertEquals($fields['published']['default'], 'Y');

		try {
			$Migration->migration['up']['alter_field']['posts']['inexistent'] = array('default' => 'N');
			$Migration->run('up');
			$this->fail('No expectation triggered');
			$this->setExpectedException('MigrationException');
		} catch (MigrationException $e) {
			$this->assertEquals('Undefined index: inexistent', $e->getMessage());
		}

		$this->assertTrue($Migration->run('down'));
		$fields = $this->db->describe($Model);
		$this->assertEquals($fields['published']['default'], 'N');
	}

/**
 * TestRenameField method
 *
 * @return void
 */
	public function testRenameField() {
		$this->loadFixtures('User', 'Post');
		$Model = new Model(array('table' => 'posts', 'ds' => 'test'));

		$Migration = new TestPrecheckCakeMigration(array(
			'up' => array(
				'rename_field' => array(
					'posts' => array(
						'updated' => 'renamed_updated'))),
			'down' => array(
				'rename_field' => array(
					'posts' => array(
						'renamed_updated' => 'updated'))),
			'precheck' => 'Migrations.PrecheckCondition'));
		$Migration->initDb();

		$fields = $this->db->describe($Model);
		$this->assertTrue(isset($fields['updated']));
		$this->assertFalse(isset($fields['renamed_updated']));

		$this->assertTrue($Migration->Precheck->beforeAction($Migration, 'rename_field', array(
			'table' => $this->db->fullTableName('posts', false, false),
			'old_name' => 'updated',
			'new_name' => 'renamed_updated')));

		$this->assertTrue($Migration->run('up'));
		$fields = $this->db->describe($Model);
		$this->assertFalse(isset($fields['updated']));
		$this->assertTrue(isset($fields['renamed_updated']));

		$this->assertFalse($Migration->Precheck->beforeAction($Migration, 'rename_field', array(
			'table' => $this->db->fullTableName('posts', false, false),
			'old_name' => 'updated',
			'new_name' => 'renamed_updated')));
		$this->assertTrue($Migration->Precheck->beforeAction($Migration, 'rename_field', array(
			'table' => $this->db->fullTableName('posts', false, false),
			'old_name' => 'renamed_updated',
			'new_name' => 'updated')));

		try {
			$Migration->run('up');
		} catch (MigrationException $e) {
			$this->fail('Exception triggered ' . $e->getMessage());
		}

		$this->assertTrue($Migration->run('down'));
		$fields = $this->db->describe($Model);
		$this->assertTrue(isset($fields['updated']));
		$this->assertFalse(isset($fields['renamed_updated']));

		$this->assertTrue($Migration->Precheck->beforeAction($Migration, 'rename_field', array(
			'table' => $this->db->fullTableName('posts', false, false),
			'old_name' => 'updated',
			'new_name' => 'renamed_updated')));
		$this->assertFalse($Migration->Precheck->beforeAction($Migration, 'rename_field', array(
			'table' => $this->db->fullTableName('posts', false, false),
			'old_name' => 'renamed_updated',
			'new_name' => 'updated')));

		try {
			$Migration->run('down');
		} catch (MigrationException $e) {
			$this->fail('Exception triggered ' . $e->getMessage());
		}
	}

}
