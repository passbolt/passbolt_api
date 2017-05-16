<?php
/**
 * AnonymousStatistic Model Test
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('AnonymousStatistic', 'Model');
App::uses('AppTestCase', 'Test');

class AnonymousStatisticTest extends AppTestCase {

	public $fixtures = array(
		'app.resource',
		'app.user',
		'app.role',
		'app.secret',
		'app.profile',
		'app.gpgkey',
		'app.file_storage',
		'app.groupsUser',
		'app.group',
		'app.permissionsType',
		'app.permission',
		'app.permission_view',
		'app.controller_log',
		'core.cakeSession',
	);

	/**
	 * setUp().
	 */
	public function setUp() {
		parent::setUp();
		$this->AnonymousStatistic = ClassRegistry::init('AnonymousStatistic');
		$this->User = ClassRegistry::init('User');
	}

	/**
	 * tearDown().
	 */
	public function tearDown() {
		parent::tearDown();
		// Make sure there is no session active after each test
		$this->User->setInactive();
	}

	/**
	 * Test findInstanceStatistics().
	 */
	public function testFindInstanceStatistics() {
		$user = $this->User->findById(Common::uuid('user.id.dame'));
		$this->User->setActive($user);

		$stats = $this->AnonymousStatistic->findInstanceStatistics();

		$this->assertEquals(
			$stats,
			[
				'passwords_count' => 16,
				'users_count'     => 25,
				'logs_count'      => 0,
				'version'         => Configure::read('App.version.number'),
			]
		);

		$this->User->setInactive($user);
	}

	/**
	 * Test reloadConfigFile().
	 */
	public function testReloadConfigFile() {
		$send = Configure::read('AnonymousStatistics.send');
		Configure::write('AnonymousStatistics.send', 'tmp');
		$this->assertEquals(Configure::read('AnonymousStatistics.send'), 'tmp');

		$this->AnonymousStatistic->reloadConfigFile();
		$this->assertEquals(Configure::read('AnonymousStatistics.send'), $send);

	}

	/**
	 * Test writeConfigFile().
	 */
//	public function testWriteConfigFile() {
//
//		$config = Configure::read('AnonymousStatistics');
//
//		$initialFileContent = file_get_contents(APP . 'Config' . DS . 'anonymous_statistics' .'.php');
//
//		$this->AnonymousStatistic->writeConfigFile('aaa', true);
//
//		$intermediateFileContent = file_get_contents(APP . 'Config' . DS . 'anonymous_statistics' .'.php');
//
//		$this->assertNotEquals($initialFileContent, $intermediateFileContent);
//		$this->assertContains('aaa', $intermediateFileContent);
//
//		$this->AnonymousStatistic->writeConfigFile($config['instanceId'], $config['send']);
//
//		$finalFileContent = file_get_contents(APP . 'Config' . DS . 'anonymous_statistics' .'.php');
//
//		$this->assertNotEquals($finalFileContent, $intermediateFileContent);
//		$this->assertEquals($initialFileContent, $finalFileContent);
//		$this->assertContains($config['instanceId'], $finalFileContent);
//	}

}