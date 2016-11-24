<?php
/**
 * AnonymousStatistic Model Test
 *
 * @copyright		(c) 2015-present Bolt Softwares Pvt Ltd
 * @package			app.Test.Case.Model.AnonymousStatisticTest
 * @license			http://www.passbolt.com/license
 */
App::uses('AnonymousStatistic', 'Model');
App::uses('AppTestCase', 'Test');

class AnonymousStatisticTest extends AppTestCase {

	/**
	 * setUp().
	 */
	public function setUp() {
		parent::setUp();
		$this->AnonymousStatistic = ClassRegistry::init('AnonymousStatistic');
		$this->User = ClassRegistry::init('User');
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
				'passwords_count' => 13,
				'users_count'     => 21,
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
	public function testWriteConfigFile() {

		$config = Configure::read('AnonymousStatistics');

		$initialFileContent = file_get_contents(APP . 'Config' . DS . 'anonymous_statistics' .'.php');

		$this->AnonymousStatistic->writeConfigFile('aaa', true);

		$intermediateFileContent = file_get_contents(APP . 'Config' . DS . 'anonymous_statistics' .'.php');

		$this->assertNotEquals($initialFileContent, $intermediateFileContent);
		$this->assertContains('aaa', $intermediateFileContent);

		$this->AnonymousStatistic->writeConfigFile($config['instanceId'], $config['send']);

		$finalFileContent = file_get_contents(APP . 'Config' . DS . 'anonymous_statistics' .'.php');

		$this->assertNotEquals($finalFileContent, $intermediateFileContent);
		$this->assertEquals($initialFileContent, $finalFileContent);
		$this->assertContains($config['instanceId'], $finalFileContent);
	}

}