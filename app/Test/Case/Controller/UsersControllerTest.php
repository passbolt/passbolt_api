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

	public $fixtures = array('app.user', 'app.role', 'app.authenticationLog', 'app.authenticationBlacklist');

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

    public function testAdd() {
      // test with normal user
      $kk = $this->user->findByUsername('user@passbolt.com');
      $this->user->setActive($kk);

      $result = json_decode($this->testAction('/users.json', array(
        'data' => array(
          'User' => array(
            'username' => 'testadd1@passbolt.com',
            'password' => 'test1',
            'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
            'active' => 1
            ),
          ),
          'method' => 'post',
          'return' => 'contents'
        )), true
      );

      $this->assertEquals(Message::ERROR, $result['header']['status'], "Add : /users.json : The test should return an error but is returning " . print_r($result, true));

      $kk = $this->user->findByUsername('admin@passbolt.com');
      $this->user->setActive($kk);
      $result = json_decode($this->testAction('/users.json', array(
            'data' => array(
              'User' => array(
                'username' => 'testadd1@passbolt.com',
                'password' => 'test1',
                'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
                'active' => 1
              ),
            ),
            'method' => 'post',
            'return' => 'contents'
          )), true
      );
      $this->assertEquals(Message::SUCCESS, $result['header']['status'], "Add : /users.json : The test should return sucess but is returning " . print_r($result, true));

      // check that User was properly saved
      $user = $this->user->findByUsername("testadd1@passbolt.com");
      $this->assertEquals(1, count($user), "Add : /users.json : The number of users returned should be 1, but actually is " . count($user));
      $this->assertEquals($user['User']['username'], $result['body']['User']['username'], "Add : /users.json : the email of the user inserted should be test1@passbolt.com but is {$result['body']['User']['username']}");
    }

    public function testUpdate() {
      // test with normal user
      $kk = $this->user->findByUsername('user@passbolt.com');
      $this->user->setActive($kk);

      $id = $kk['User']['id'];

      $result = json_decode($this->testAction("/users/$id.json", array(
            'data' => array(
              'User' => array(
                'id' => $kk['User']['id'],
                'username' => 'user-modified@passbolt.com',
                'password' => 'test1',
                'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
                'active' => 1
              ),
            ),
            'method' => 'put',
            'return' => 'contents'
          )), true
      );

      $this->assertEquals(Message::ERROR, $result['header']['status'], "Edit : /users.json : The test should return an error but is returning " . print_r($result, true));

      $ad = $this->user->findByUsername('admin@passbolt.com');
      $this->user->setActive($ad);

      $kk['User']['username'] = 'user-modified@passbolt.com';
      $result = json_decode($this->testAction("/users/$id.json", array(
            'data' => $kk,
            'method' => 'put',
            'return' => 'contents'
          )), true
      );
      $this->assertEquals(Message::SUCCESS, $result['header']['status'], "Edit : /users.json : The test should return sucess but is returning " . print_r($result, true));

      // check that User was properly saved
      $user = $this->user->findByUsername("user-modified@passbolt.com");
      $this->assertEquals(1, count($user), "Edit : /users.json : The number of users returned should be 1, but actually is " . count($user));
      $this->assertEquals($user['User']['id'], $kk['User']['id'], "Edit : /users.json : the id of the retrieved user  should be {$kk['User']['id']} but is {$user['User']['id']}");
    }

    public function testDelete() {
      $u = $this->user->findByUsername('user@passbolt.com');
      $this->user->setActive($u);
      $id = $u['User']['id'];
      $result = json_decode($this->testAction("/users/$id.json", array(
            'method' => 'delete',
            'return' => 'contents'
          )), true);
      $this->assertEquals(Message::ERROR, $result['header']['status'], "delete /users/$id.json : The test should return a success but is returning {$result['header']['status']}");

      $id = 1;
      $result = json_decode($this->testAction("/users/$id.json", array(
            'method' => 'delete',
            'return' => 'contents'
          )), true);
      $this->assertEquals(Message::ERROR, $result['header']['status'], "delete /users/$id.json : The test should return a error but is returning {$result['header']['status']}");


      $adm = $this->user->findByUsername('admin@passbolt.com');
      $this->user->setActive($adm);
      $result = json_decode($this->testAction("/users/{$u['User']['id']}.json", array(
            'method' => 'delete',
            'return' => 'contents'
          )), true);
      $this->assertEquals(Message::SUCCESS, $result['header']['status'], "delete /users/$id.json : The test should return a success but is returning {$result['header']['status']}");

      $deleted = $this->user->findByUsername('user@passbolt.com');
      $this->assertEqual(1, $deleted['User']['deleted'], "delete /users/{$u['User']['id']}.json : after delete, the value of the field deleted should be 1 but is {$deleted['User']['deleted']}");

      $result = json_decode($this->testAction('/users/' . $u['User']['id'] . '.json',array('return' => 'contents','method' => 'GET'), true));
      $this->assertEqual($result->header->status, Message::ERROR,'/users/delete after delete, no result should be returned');
    }
}
