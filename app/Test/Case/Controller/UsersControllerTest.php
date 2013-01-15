<?php
/**
 * Users Controller Tests
 *
 * @copyright	 Copyright 2012, Passbolt.com
 * @license		 http://www.passbolt.com/license
 * @package		 app.Test.Case.Controller.UsersControllerTest
 * @since		 version 2.12.9
 */
App::uses('AppController', 'Controller');
App::uses('UsersController', 'Controller');
App::uses('User', 'Model');
App::uses('Role', 'Model');
App::uses('CakeSession', 'Model');
App::uses('CakeSession', 'Model/Datasource');

class UsersControllerTest extends ControllerTestCase {

	public $fixtures = array('app.user', 'app.role', 'app.authenticationLog');

	public $user;

	public $session;

	public function setUp() {
		parent::setUp();
		$this->user = new User();
		$this->user->useDbConfig = 'test';
		$u = $this->user->get();
		$this->session = new CakeSession();
		$this->session->init();
	}

	public function tearDown() {
		parent::tearDown();
	}

	public function testLogin() {
		// make sure there is no active session
		$result = $this->testAction('/logout',array('return' => 'contents'), true);

		// check if we get form
		$result = $this->testAction('/users/login', array('return' => 'view','method' => 'GET'), true);
		$this->assertEqual(preg_match('/(<form)/',$result), true,'/users/login with no data sent should return a form');

		// check logging in with a good user
		$data = array( 'User' => array(
			'username' => 'test@passbolt.com',
			'password' => 'password'
		));

		// Test that the user is returned properly in the session (authentication has done its job)
		$result = $this->testAction('/users/login', array('return' => 'vars','method' => 'POST', 'data' => $data), true);
		$this->assertEqual($this->user->get('User.username'), 'test@passbolt.com', "login test should have returned test@passbolt.com but has returned {$this->user->get('User.username')}");

		// Test that the redirection is there as it should
		$result = $this->testAction('/users/login', array('return' => 'view','method' => 'POST', 'data' => $data), true);
		$this->assertEqual($this->headers['Location'], Router::url('/', true), "Login should have redirected to / but has not");

		// Log out
		$result = $this->testAction('/logout',array('return' => 'contents'), true);

		// check with bogus user
		$data = array( 'User' => array(
			'username' => 'biloute@passbolt.com',
			'password' => 'ouaich mec'
		));
		$result = $this->testAction('/users/login', array('return' => 'view','method' => 'POST', 'data' => $data), true);
		$this->assertEqual(preg_match('/(<form)/',$result), true,'/users/login with wrong data should have returned a form');
	}

	public function testIndex() {
		// make sure there is no active session
		$result = $this->testAction('/logout',array('return' => 'contents'), true);

		// test with anonymous user
		$result = json_decode($this->testAction('/users.json',array('return' => 'contents','method' => 'GET'), true));
		$this->assertEqual($result->header->status, Message::ERROR, '/users should not be accessible without being logged in');

		// test with normal user
		$kk = $this->user->findByUsername('user@passbolt.com');
 		$this->user->setActive($kk);

		$result = json_decode($this->testAction('/users.json',array('return' => 'contents','method' => 'GET'), true));
		$this->assertEqual($result->header->status, Message::SUCCESS,'/users return something');

		// @todo empty database and test if index throws warning for no
 		$this->user->deleteAll(array('active' => '1'));
 		$this->user->deleteAll(array('active' => '0'));
		$result = json_decode($this->testAction('/users.json',array('return' => 'contents','method' => 'GET'), true));
		$this->assertEqual($result->header->status, Message::NOTICE,'/users return a warning');
	}

	public function testView() {
		// make sure there is no active session
		$result = $this->testAction('/logout',array('return' => 'contents'), true);

		// test with anonymous user
		$result = json_decode($this->testAction('/users/50cdab9c-4380-4eb6-b4cc-2f4fd7a10fce.json',array('return' => 'contents','method' => 'GET'), true));
		$this->assertEqual($result->header->status, Message::ERROR, '/users/view should not be accessible without being logged in');

		// test with normal user
		$kk = $this->user->findByUsername('user@passbolt.com');
 		$this->user->setActive($kk);

		$result = json_decode($this->testAction('/users/view',array('return' => 'contents','method' => 'GET'), true));
		$this->assertEqual($result->header->status, Message::ERROR,'/users/view with missing UUID should return an error');

		$result = json_decode($this->testAction('/users/view/0000-0000-0000-000000000000.json',array('return' => 'contents','method' => 'GET'), true));
		$this->assertEqual($result->header->status, Message::ERROR,'/users/view with wrong UUID format should return an error');

		$result = json_decode($this->testAction('/users/bbd56042-0000-0000-0000-000000000000.json',array('return' => 'contents','method' => 'GET'), true));
		$this->assertEqual($result->header->status, Message::ERROR,'/users/view with user that does not exit should return an error');

		$result = json_decode($this->testAction('/users/50cdab9c-4380-4eb6-b4cc-2f4fd7a10fce.json',array('return' => 'contents','method' => 'GET'), true));
		$this->assertEqual($result->header->status, Message::SUCCESS,'/users return something');

		$result = json_decode($this->testAction('/users/' . User::get('id') . '.json',array('return' => 'contents','method' => 'GET'), true));
		$this->assertEqual($result->header->status, Message::SUCCESS,'/users/view asking for self should return something');
	}

	public function testLogout() {
		$result = $this->testAction('/logout',array('return' => 'contents'), true);
	}
}
