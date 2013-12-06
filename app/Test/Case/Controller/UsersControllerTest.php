<?php
/**
 * Users Controller Tests
 *
 * @copyright       Copyright 2012, Passbolt.com
 * @license         http://www.passbolt.com/license
 * @package         app.Test.Case.Controller.UsersControllerTest
 * @since           version 2.12.9
 */
App::uses('AppController', 'Controller');
App::uses('UsersController', 'Controller');
App::uses('User', 'Model');
App::uses('Group', 'Model');
App::uses('Role', 'Model');
App::uses('CakeSession', 'Model');
App::uses('CakeSession', 'Model/Datasource');

class UsersControllerTest extends ControllerTestCase {

	public $fixtures
		= array(
			'app.user',
			'app.profile',
			'app.role',
			'app.authenticationLog',
			'app.authenticationBlacklist'
		);

	public $user;

	public $session;

	public function setUp() {
		parent::setUp();
		$this->User = new User();
		$this->User->useDbConfig = 'test';
		$u = $this->User->get();
		$this->session = new CakeSession();
		$this->session->init();
	}

	public function tearDown() {
		parent::tearDown();
		// Make sure there is no session active after each test
		$this->User->setInactive();
	}

	public function testLoginWrongUser() {
		// Make sure there is no session active after each test
		$this->User->setInactive();
		$data = array(
			'User' => array(
				'username' => 'biloute@passbolt.com',
				'password' => 'ouaich mec'
			)
		);
		$this->expectException('HttpException', 'Invalid username or password, try again');
		$result = $this->testAction(
			'/users/login',
			array('return' => 'view', 'method' => 'POST', 'data' => $data),
			true
		);
	}

	public function testLogin() {
		// check if we get form
		$result = $this->testAction('/login', array('return' => 'view', 'method' => 'GET'), true);
		$this->assertEqual(
			preg_match('/(<form)/', $result),
			true,
			'/users/login with no data sent should return a form'
		);

		// check logging in with a good user
		$data = array(
			'User' => array(
				'username' => 'test@passbolt.com',
				'password' => 'password'
			)
		);

		// Test that the user is returned properly in the session (authentication has done its job)
		$result = $this->testAction(
			'/users/login',
			array('return' => 'vars', 'method' => 'POST', 'data' => $data),
			true
		);
		$this->assertEqual(
			$this->User->get('User.username'),
			'test@passbolt.com',
			"login test should have returned test@passbolt.com but has returned {$this->User->get('User.username')}"
		);

		// Test that the redirection is there as it should
		$result = $this->testAction(
			'/users/login',
			array('return' => 'view', 'method' => 'POST', 'data' => $data),
			true
		);
		$this->assertEqual(
			$this->headers['Location'],
			Router::url('/', true),
			"Login should have redirected to / but has not"
		);
	}

	public function testIndexNoAllowed() {
		$this->expectException('HttpException', 'You need to login to access this location');
		// test with anonymous user
		$result = json_decode($this->testAction('/users.json', array('return' => 'contents', 'method' => 'GET'), true));
	}

	public function testIndex() {
		// test with normal user
		$kk = $this->User->findByUsername('user@passbolt.com');
		$this->User->setActive($kk);

		$result = json_decode($this->testAction('/users.json', array('return' => 'contents', 'method' => 'GET'), true));
		$this->assertEqual($result->header->status, Message::SUCCESS, '/users return something');

		// @todo empty database and test if index throws warning for no
		$this->User->deleteAll(array('active' => '1'));
		$this->User->deleteAll(array('active' => '0'));
		$result = json_decode($this->testAction('/users.json', array('return' => 'contents', 'method' => 'GET'), true));
		$this->assertEqual($result->header->status, Message::NOTICE, '/users return a warning');
	}

	public function testViewNoAllowed() {
		$this->expectException('HttpException', 'You need to login to access this location');
		// test with anonymous user
		$result = json_decode(
			$this->testAction(
				'/users/50cdab9c-4380-4eb6-b4cc-2f4fd7a10fce.json',
				array('return' => 'contents', 'method' => 'GET'),
				true
			)
		);
	}

	public function testViewUserIdIsMissing() {
		// Unable to test missing id param because of route
	}

	public function testViewUserIdNotValid() {
		// test with normal user
		$kk = $this->User->findByUsername('user@passbolt.com');
		$this->User->setActive($kk);

		$this->expectException('HttpException', 'The user id is invalid');
		$result = json_decode(
			$this->testAction('/users/badId.json', array('return' => 'contents', 'method' => 'GET'), true)
		);
	}

	public function testViewUserDoesNotExist() {
		// test with normal user
		$kk = $this->User->findByUsername('user@passbolt.com');
		$this->User->setActive($kk);

		$this->expectException('HttpException', 'The user does not exist');
		$result = json_decode(
			$this->testAction(
				'/users/4ff6111b-efb8-4a26-aab4-2184cbdd56ca.json',
				array('return' => 'contents', 'method' => 'GET'),
				true
			)
		);
	}

	public function testView() {
		// test with normal user
		$kk = $this->User->findByUsername('user@passbolt.com');
		$this->User->setActive($kk);

		$result = json_decode(
			$this->testAction(
				'/users/50cdab9c-4380-4eb6-b4cc-2f4fd7a10fce.json',
				array('return' => 'contents', 'method' => 'GET'),
				true
			)
		);
		$this->assertEqual($result->header->status, Message::SUCCESS, '/users return something');

		$result = json_decode(
			$this->testAction(
				'/users/' . User::get('id') . '.json',
				array('return' => 'contents', 'method' => 'GET'),
				true
			)
		);
		$this->assertEqual(
			$result->header->status,
			Message::SUCCESS,
			'/users/view asking for self should return something'
		);
	}

	public function testAddNoAllowed() {
		// normal user don't have the right to add user
		$kk = $this->User->findByUsername('user@passbolt.com');
		$this->User->setActive($kk);

		$this->expectException('HttpException', 'You are not authorized to access that location');
		// test with anonymous user
		$result = json_decode(
			$this->testAction(
				'/users.json',
				array(
					'data'   => array(
						'User' => array(
							'username' => 'testadd1@passbolt.com',
							'password' => 'test1',
							'role_id'  => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
							'active'   => 1
						),
					),
					'method' => 'post',
					'return' => 'contents'
				)
			),
			true
		);
	}

	public function testAdd() {
		$kk = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($kk);
		$result = json_decode(
			$this->testAction(
				'/users.json',
				array(
					'data'   => array(
						'User' => array(
							'username' => 'testadd1@passbolt.com',
							'password' => 'test1',
							'role_id'  => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
							'active'   => 1
						),
					),
					'method' => 'post',
					'return' => 'contents'
				)
			),
			true
		);
		$this->assertEquals(
			Message::SUCCESS,
			$result['header']['status'],
			"Add : /users.json : The test should return sucess but is returning " . print_r($result, true)
		);

		// check that User was properly saved
		$user = $this->User->findByUsername("testadd1@passbolt.com");
		$this->assertEquals(
			1,
			count($user),
			"Add : /users.json : The number of users returned should be 1, but actually is " . count($user)
		);
		$this->assertEquals(
			$user['User']['username'],
			$result['body']['User']['username'],
			"Add : /users.json : the email of the user inserted should be test1@passbolt.com but is {$result['body']['User']['username']}"
		);
	}

	public function testAddWithoutRoleId() {
		$kk = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($kk);
		$result = json_decode(
			$this->testAction(
				'/users.json',
				array(
					'data'   => array(
						'User' => array(
							'username' => 'testaddnoroleid@passbolt.com',
							'password' => 'test1',
							'active'   => 1
						),
					),
					'method' => 'post',
					'return' => 'contents'
				)
			),
			true
		);
		$this->assertEquals(
			Message::SUCCESS,
			$result['header']['status'],
			"Add : /users.json : The test should return sucess but is returning " . print_r($result, true)
		);

		// check that User was properly saved
		$user = $this->User->findByUsername("testaddnoroleid@passbolt.com");
		$this->assertEquals(
			1,
			count($user),
			"Add : /users.json : The number of users returned should be 1, but actually is " . count($user)
		);
		$this->assertEquals(
			$user['User']['username'],
			$result['body']['User']['username'],
			"Add : /users.json : the email of the user inserted should be testaddnoroleid@passbolt.com but is {$result['body']['User']['username']}"
		);

		// Check that the role user was properly assigned
		$Role = Common::GetModel('Role');
		$roleUserId = $Role->field('id', array('name' => Role::USER));
		$this->assertEquals(
			$roleUserId,
			$result['body']['User']['role_id'],
			"Add : /users.json : the role of the user should be $roleUserId but is {$result['body']['User']['role_id']}"
		);
	}

	public function testUpdateNoAllowed() {
		// normal user don't have the right to add user
		$kk = $this->User->findByUsername('user@passbolt.com');
		$this->User->setActive($kk);
		$id = $kk['User']['id'];

		$this->expectException('HttpException', 'You are not authorized to access that location');
		// test with anonymous user
		$result = json_decode(
			$this->testAction(
				"/users/$id.json",
				array(
					'data'   => array(
						'User' => array(
							'id'       => $kk['User']['id'],
							'username' => 'user-modified@passbolt.com',
							'password' => 'test1',
							'role_id'  => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
							'active'   => 1
						),
					),
					'method' => 'put',
					'return' => 'contents'
				)
			),
			true
		);
	}

	public function testUpdateUserIdIsMissing() {
		$ad = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($ad);

		$this->expectException('HttpException', 'The user id is missing');
		$result = json_decode($this->testAction('/users.json', array('return' => 'contents', 'method' => 'put'), true));
	}

	public function testUpdateUserIdNotValid() {
		$ad = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($ad);

		$this->expectException('HttpException', 'The user id is invalid');
		$result = json_decode(
			$this->testAction('/users/badId.json', array('return' => 'contents', 'method' => 'put'), true)
		);
	}

	public function testUpdateUserDoesNotExist() {
		$ad = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($ad);

		$this->expectException('HttpException', 'The user does not exist');
		$result = json_decode(
			$this->testAction(
				'/users/4ff6111b-efb8-4a26-aab4-2184cbdd56ca.json',
				array('return' => 'contents', 'method' => 'put'),
				true
			)
		);
	}

	public function testUpdateNoDataProvided() {
		$ad = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($ad);

		$this->expectException('HttpException', 'No data were provided');
		$kk = $this->User->findByUsername('user@passbolt.com');
		$id = $kk['User']['id'];

		$kk['User']['username'] = 'user-modified@passbolt.com';
		$result = json_decode(
			$this->testAction(
				"/users/$id.json",
				array(
					'method' => 'put',
					'return' => 'contents'
				)
			),
			true
		);
	}

	public function testUpdate() {
		$ad = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($ad);
		$kk = $this->User->findByUsername('user@passbolt.com');
		$id = $kk['User']['id'];

		$kk['User']['username'] = 'user-modified@passbolt.com';
		$result = json_decode(
			$this->testAction(
				"/users/$id.json",
				array(
					'data'   => $kk,
					'method' => 'put',
					'return' => 'contents'
				)
			),
			true
		);
		$this->assertEquals(
			Message::SUCCESS,
			$result['header']['status'],
			"Edit : /users.json : The test should return sucess but is returning " . print_r($result, true)
		);

		// check that User was properly saved
		$user = $this->User->findByUsername("user-modified@passbolt.com");
		$this->assertEquals(
			1,
			count($user),
			"Edit : /users.json : The number of users returned should be 1, but actually is " . count($user)
		);
		$this->assertEquals(
			$user['User']['id'],
			$kk['User']['id'],
			"Edit : /users.json : the id of the retrieved user  should be {$kk['User']['id']} but is {$user['User']['id']}"
		);
	}

	public function testDeleteNoAllowed() {
		// normal user don't have the right to delete user
		$u = $this->User->findByUsername('user@passbolt.com');
		$this->User->setActive($u);

		$this->expectException('HttpException', 'You are not authorized to access that location');
		// test with anonymous user
		$result = json_decode(
			$this->testAction(
				"/users/{$u['User']['id']}.json",
				array(
					'method' => 'delete',
					'return' => 'contents'
				)
			),
			true
		);
	}

	public function testDeleteUserIdIsMissing() {
		$ad = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($ad);

		$this->expectException('HttpException', 'The user id is missing');
		$result = json_decode(
			$this->testAction('/users.json', array('return' => 'contents', 'method' => 'delete'), true)
		);
	}

	public function testDeleteUserIdNotValid() {
		$ad = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($ad);

		$this->expectException('HttpException', 'The user id is invalid');
		$result = json_decode(
			$this->testAction('/users/badId.json', array('return' => 'contents', 'method' => 'delete'), true)
		);
	}

	public function testDeleteUserDoesNotExist() {
		$ad = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($ad);

		$this->expectException('HttpException', 'The user does not exist');
		$result = json_decode(
			$this->testAction(
				'/users/4ff6111b-efb8-4a26-aab4-2184cbdd56ca.json',
				array('return' => 'contents', 'method' => 'delete'),
				true
			)
		);
	}

	public function testDelete() {
		$adm = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($adm);
		$u = $this->User->findByUsername('user@passbolt.com');
		$result = json_decode(
			$this->testAction(
				"/users/{$u['User']['id']}.json",
				array(
					'method' => 'delete',
					'return' => 'contents'
				)
			),
			true
		);
		$this->assertEquals(
			Message::SUCCESS,
			$result['header']['status'],
			"delete /users/{$u['User']['id']}.json : The test should return a success but is returning {$result['header']['status']}"
		);

		$deleted = $this->User->findByUsername('user@passbolt.com');
		$this->assertEqual(
			1,
			$deleted['User']['deleted'],
			"delete /users/{$u['User']['id']}.json : after delete, the value of the field deleted should be 1 but is {$deleted['User']['deleted']}"
		);
	}

	public function testDelete2() {
		$adm = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($adm);
		$u = $this->User->findByUsername('user@passbolt.com');
		$result = json_decode(
			$this->testAction(
				"/users/{$u['User']['id']}.json",
				array(
					'method' => 'delete',
					'return' => 'contents'
				)
			),
			true
		);
		$this->assertEquals(
			Message::SUCCESS,
			$result['header']['status'],
			"delete /users/{$u['User']['id']}.json : The test should return a success but is returning {$result['header']['status']}"
		);

		$deleted = $this->User->findByUsername('user@passbolt.com');
		$this->assertEqual(
			1,
			$deleted['User']['deleted'],
			"delete /users/{$u['User']['id']}.json : after delete, the value of the field deleted should be 1 but is {$deleted['User']['deleted']}"
		);
	}
}
