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

	public $fixtures = array('app.user', 'app.role');

	public $user;

	public $session;

	public function setUp() {
		parent::setUp();
		$this->user = new User();
		$this->user->useDbConfig = 'test';
		$u = $this->user->GET();
		$this->session = new CakeSession();
		$this->session->init();
	}

	public function testView() {
		// make sure there is no active session
		$result = $this->testAction('/logout',array('return' => 'contents'), true);

		// test with anonymous user
		$result = json_decode($this->testAction('/dictionaries/en-EN.json',array('return' => 'contents', 'method' => 'GET'), true));
		$this->assertEqual($result->header->status, Message::ERROR, '/dictionaries should not be accessible without being logged in');

		// login with keke
		$kk = $this->user->findByUsername('user@passbolt.com');
		$this->user->setActive($kk);

		// test bogus dictionary
		$result = json_decode($this->testAction('/dictionaries/00-00.json',array('return' => 'contents', 'method' => 'GET'), true));
		$this->assertEqual($result->header->status, Message::ERROR, '/dictionaries/00-00.json should return an error');

		// test english dictionary
		$result = json_decode($this->testAction('/dictionaries/en-EN.json',array('return' => 'contents', 'method' => 'GET'), true));
		$this->assertEqual($result->header->status, Message::SUCCESS, '/dictionaries/en-EN.json should return something');

		// test french dictionary
		$result = json_decode($this->testAction('/dictionaries/fr-FR.json',array('return' => 'contents', 'method' => 'GET'), true));
		$this->assertEqual($result->header->status, Message::SUCCESS, '/dictionaries/fr-FR.json should return something');

		// clear cache and test if cache writting works
		Cache::clear();
		$result = json_decode($this->testAction('/dictionaries/fr-FR.json',array('return' => 'contents', 'method' => 'GET'), true));
		$c = Cache::read('dictionary_fr-FR','_cake_model_');
		$this->assertEqual(!empty($c), true, 'Cache should return something');
	}
}
