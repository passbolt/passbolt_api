<?php
/**
 * Dictionaries Controller Tests
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @package      app.Test.Case.Controller.DictionariesControllerTest
 * @since        version 2.12.7
 * @license      http://www.passbolt.com/license
 */
App::uses('AppController', 'Controller');
App::uses('DictionariesController', 'Controller');
App::uses('Dictionary', 'Model');
App::uses('User', 'Model');
App::uses('Role', 'Model');
App::uses('CakeSession', 'Model');
App::uses('CakeSession', 'Model/Datasource');

class DictionariesControllerTest extends ControllerTestCase {

	public $fixtures = array(
		'app.user',
		'app.profile',
		'app.file_storage',
		'app.group',
		'app.groupsUser',
		'app.role',
		'app.authenticationBlacklist',
		'app.gpgkey',
		'core.cakeSession',
	);

	public $user;

	public $session;

	public function setUp() {
		parent::setUp();
		$this->User = Common::getModel('User');
		$user = $this->User->findByUsername('user@passbolt.com');
		$this->User->setActive($user);
	}

	public function testViewNoAllowed() {
		// Anonymous user should not be able to access
		$this->User->setInactive();
		$this->setExpectedException('HttpException', 'You need to login to access this location');
		$result = json_decode($this->testAction('/dictionaries/en-EN.json', array(
			'return' => 'contents',
			'method' => 'GET'
		), true));
	}

	public function testViewDictionnaryDoesNotExist() {
		$this->setExpectedException('HttpException', 'Sorry the dictory could not be found');
		$result = json_decode($this->testAction('/dictionaries/00-00.json', array(
			'return' => 'contents',
			'method' => 'GET'
		), true));
	}

	public function testView() {
		// test english dictionary
		$result = json_decode($this->testAction('/dictionaries/en-EN.json', array(
			'return' => 'contents',
			'method' => 'GET'
		), true));
		$this->assertEquals($result->header->status, Message::SUCCESS, '/dictionaries/en-EN.json should return something');

		// test french dictionary
		$result = json_decode($this->testAction('/dictionaries/fr-FR.json', array(
			'return' => 'contents',
			'method' => 'GET'
		), true));
		$this->assertEquals($result->header->status, Message::SUCCESS, '/dictionaries/fr-FR.json should return something');

		// clear cache and test if cache writting works
		Cache::clear();
		$result = json_decode($this->testAction('/dictionaries/fr-FR.json', array(
			'return' => 'contents',
			'method' => 'GET'
		), true));
		$c = Cache::read('dictionary_fr-FR', '_cake_model_');
		$this->assertEquals(!empty($c), true, 'Cache should return something');
	}
}
