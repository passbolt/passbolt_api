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
			'app.groups_user',
			'app.group',
			'app.user',
			'app.gpgkey',
			'app.email_queue',
			'app.profile',
			'app.file_storage',
			'app.role',
			'app.authenticationToken',
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
		$result = $this->testAction(
			'/users/login',
			array('return' => 'view', 'method' => 'POST', 'data' => $data),
			true
		);
		$this->assertTextContains('Username', $result);
		$this->assertTextContains('Password', $result);
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
		$this->User->deleteAll(array('User.active' => '1'));
		$this->User->deleteAll(array('User.active' => '0'));
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
							'password' => 'abcedfghijk',
							'role_id'  => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
							'active'   => 1
						),
						'Profile' => array(
							'first_name' => 'jean',
							'last_name' => 'test'
						)
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

		$this->assertEquals(
			'jean',
			$result['body']['Profile']['first_name'],
			"Add : /users.json : the first name of the added user should be jean but is {$result['body']['Profile']['first_name']}"
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
							'password' => 'abcedfghijk',
							'active'   => 1
						),
						'Profile' => array(
							'first_name' => 'test',
							'last_name' => 'test'
						)
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

	public function testAddWithoutProfileInfo() {
		$kk = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($kk);
		$this->expectException('HttpException', 'Profile data are missing');
		$result = json_decode(
			$this->testAction(
				'/users.json',
				array(
					'data'   => array(
						'User' => array(
							'username' => 'testprofile@passbolt.com',
							'password' => 'abcedfghijk',
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

	public function testUpdateNoAllowed() {
		// normal user don't have the right to add user
		$dv = $this->User->findByUsername('darth.vader@passbolt.com');
		$this->User->setActive($dv);
		$kk = $this->User->findByUsername('user@passbolt.com');

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
							'password' => 'abcedfghijk',
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

	public function testUpdateUsernameNotValid() {
		$ad = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($ad);
		$kk = $this->User->findByUsername('user@passbolt.com');
		$id = $kk['User']['id'];
		$data['User']['username'] = 'user@34@passbolt.com';

		$this->expectException('HttpException', 'Could not validate User');
		$resRaw = $this->testAction(
			"/users/$id.json",
			array(
				'data'   => $data,
				'method' => 'put',
				'return' => 'contents'
			)
		);
		$result = json_decode($resRaw, true);
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

		$Role = Common::getModel('Role');
		$adminRole = $Role->findByName("admin");

		$data['User']['role_id'] = $adminRole['Role']['id'];
		$resRaw = $this->testAction(
			"/users/$id.json",
			array(
				'data'   => $data,
				'method' => 'put',
				'return' => 'contents'
			)
		);
		$result = json_decode($resRaw, true);
		$this->assertEquals(
			Message::SUCCESS,
			$result['header']['status'],
			"Edit : /users.json : The test should return sucess but is returning " . print_r($result, true)
		);

		// check that User was properly saved
		$user = $this->User->findByUsername("user@passbolt.com");
		$this->assertEquals(
			$user['User']['role_id'],
			$adminRole['Role']['id'],
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

	public function testUpdateAvatarNoAllowed() {
		// normal user don't have the right to add user
		$dv = $this->User->findByUsername('darth.vader@passbolt.com');
		$this->User->setActive($dv);
		$kk = $this->User->findByUsername('user@passbolt.com');

		$id = $kk['User']['id'];

		$this->expectException('HttpException', 'You are not authorized to access that location');
		// test with anonymous user
		$_FILES['file-0'] = array(
			'file' => array(
				'tmp_name' => APP . 'Test' . DS . 'Data' . DS . 'img' . DS . 'user.png'
			)
		);
		$result = json_decode(
			$this->testAction(
				"/users/avatar/$id.json",
				array(
					'data'   => array(),
					'method' => 'post',
					'return' => 'contents'
				)
			),
			true
		);
	}

	public function testUpdateAvatarUserIdIsMissing() {
		$ad = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($ad);

		$this->expectException('HttpException', 'The user id is missing');
		$result = json_decode($this->testAction('/users/avatar.json', array('return' => 'contents', 'method' => 'post'), true));
	}

	public function testUpdateAvatarUserIdNotValid() {
		$ad = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($ad);

		$this->expectException('HttpException', 'The user id is invalid');
		$result = json_decode(
			$this->testAction('/users/avatar/badId.json', array('return' => 'contents', 'method' => 'post'), true)
		);
	}

	public function testUpdateAvatarUserDoesNotExist() {
		$ad = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($ad);

		$this->expectException('HttpException', 'The user does not exist');
		$result = json_decode(
			$this->testAction(
				'/users/avatar/4ff6111b-efb8-4a26-aab4-2184cbdd56ca.json',
				array('return' => 'contents', 'method' => 'post'),
				true
			)
		);
	}

	public function testUpdateAvatarNoDataProvided() {
		$ad = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($ad);

		$this->expectException('HttpException', 'No data were provided');
		$kk = $this->User->findByUsername('user@passbolt.com');
		$id = $kk['User']['id'];

		$kk['User']['username'] = 'user-modified@passbolt.com';
		$result = json_decode(
			$this->testAction(
				"/users/avatar/$id.json",
				array(
					'method' => 'post',
					'return' => 'contents'
				)
			),
			true
		);
	}

	public function testUpdateAvatar() {
		$ad = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($ad);

		$kk = $this->User->findByUsername('user@passbolt.com');
		$id = $kk['User']['id'];

		$findConditions = array('User.id' => $id);
		$options = $this->User->getFindOptions('User::view', User::get('Role.name'), $findConditions);
		$users = $this->User->find('all', $options);
		$kk = reset($users);

		// Get empty image url.
		$defaults = Configure::read('Media.imageDefaults.ProfileAvatar');
		$diff = Hash::diff($kk['Profile']['Avatar']['url'], $defaults);

		$this->assertEmpty($diff, "The user " . $kk['User']['username'] . " should have the default avatar");

		$_FILES['file-0'] = array(
			'tmp_name' => APP . 'Test' . DS . 'Data' . DS . 'img' . DS . 'user.png'
		);
		$result = json_decode(
			$this->testAction(
				"/users/avatar/$id.json",
				array(
					'data'   => array(),
					'method' => 'post',
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

		$findConditions = array('User.id' => $id);
		$options = $this->User->getFindOptions('User::view', User::get('Role.name'), $findConditions);
		$users = $this->User->find('all', $options);
		$kk = reset($users);

		$this->assertNotEmpty($kk['Profile']['Avatar'], "The user " . $kk['User']['username'] . " should have an avatar");
	}

	private function __createAccount($username) {
		$userAdd = $this->testAction(
			'/users.json',
			array(
				'data'   => array(
					'User' => array(
						'username' => $username,
						'role_id'  => '0208f57a-c5cd-11e1-a0c5-080027796c4c'
					),
					'Profile' => array(
						'first_name' => 'Jean',
						'last_name' => 'Gabin'
					),
				),
				'method' => 'post',
				'return' => 'contents'
			)
		);
		$json = json_decode($userAdd, true);
		return $json['body'];
	}

	public function testAccountCreateAndToken() {
		$ad = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($ad);
		$user = $this->__createAccount('jean-gabin@gmail.com');

		// Verify that user is not active.
		$u = $this->User->findById($user['User']['id']);
		$this->assertEqual($u['User']['active'], 0, 'The user created should be inactive by default');

		// Check that there is an entry in the table authenticationToken.
		$AuthenticationToken = Common::getModel('AuthenticationToken');
		$at = $AuthenticationToken->findByUserId($user['User']['id']);
		$this->assertEqual((bool)$at, true, 'There should be an authentication token created for the user');
	}

	public function testAccountValidation() {
		$ad = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($ad);
		$user = $this->__createAccount('jean-gabin@gmail.com');

		$AuthenticationToken = Common::getModel('AuthenticationToken');
		$at = $AuthenticationToken->findByUserId($user['User']['id']);

		$url = '/users/validateAccount/' . $user['User']['id'] . '.json';
		$validate = $this->testAction(
			'/users/validateAccount/' . $user['User']['id'] . '.json',
			array(
				'data'   => array (
					'AuthenticationToken' => array (
						'token' => $at['AuthenticationToken']['token'],
					),
				),
				'method' => 'put',
				'return' => 'contents'
			)
		);
		$json = json_decode($validate, true);
		$this->assertEquals(
			Message::SUCCESS,
			$json['header']['status'],
			"validateAccount /users/validateAccount/{$user['User']['id']}.json : The test should return a success but is returning {$json['header']['status']}"
		);

		// Get user and check if deactivated.
		$u = $this->User->findById($user['User']['id']);
		$this->assertEqual($u['User']['active'], true, 'The user should be activated after account validation, but is not');

		// Check Authentication Token is not active anymore.
		$at = $AuthenticationToken->findByUserId($user['User']['id']);
		$this->assertEqual($at['AuthenticationToken']['active'], 0, 'The authentication token should be deactivated after account activation, but it is not');
	}

	public function testAccountValidationWithProfileEdit() {
		$ad = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($ad);
		$user = $this->__createAccount('jean-gabin@gmail.com');

		$AuthenticationToken = Common::getModel('AuthenticationToken');
		$at = $AuthenticationToken->findByUserId($user['User']['id']);

		$url = '/users/validateAccount/' . $user['User']['id'] . '.json';
		$validate = $this->testAction(
			$url,
			array(
				'data'   => array (
					'AuthenticationToken' => array (
						'token' => $at['AuthenticationToken']['token'],
					),
					'Profile' => array (
						'first_name' => 'Rene',
						'last_name' => 'Dupuit',
					),
				),
				'method' => 'put',
				'return' => 'contents'
			)
		);
		$json = json_decode($validate, true);
		$this->assertEquals(
			Message::SUCCESS,
			$json['header']['status'],
			"validateAccount /users/validateAccount/{$user['User']['id']}.json : The test should return a success but is returning {$json['header']['status']}"
		);

		// Get user and check if deactivated.
		$p = $this->User->Profile->findByUserId($user['User']['id']);
		$this->assertEqual($p['Profile']['first_name'], 'Rene', "After account validation the user first_name should be rene, but is {$p['Profile']['first_name']}");
	}

	public function testAccountValidationWrongUserId() {
		$this->expectException('HttpException', 'The user does not exist');
		$validate = $this->testAction(
			'/users/validateAccount/4ff6111b-efb8-4a26-aab4-2184cbdd56ca.json',
			array(
				'data'   => array (
					'AuthenticationToken' => array (
						'token' => 'tokentoken',
					),
				),
				'method' => 'put',
				'return' => 'contents'
			)
		);
	}

	public function testAccountValidationWrongToken() {
		$ad = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($ad);
		$user = $this->__createAccount('jean-gabin@gmail.com');
		$this->expectException('HttpException', 'Invalid token');
		$validate = $this->testAction(
			'/users/validateAccount/' . $user['User']['id'] . '.json',
			array(
				'data'   => array (
					'AuthenticationToken' => array (
						'token' => 'wrong token',
					),
				),
				'method' => 'put',
				'return' => 'contents'
			)
		);
	}


//	public function testUpdatePasswordNoAllowed() {
//		// normal user don't have the right to add user
//		$dv = $this->User->findByUsername('darth.vader@passbolt.com');
//		$this->User->setActive($dv);
//		$kk = $this->User->findByUsername('user@passbolt.com');
//
//		$id = $kk['User']['id'];
//
//		$this->expectException('HttpException', 'You are not authorized to access that location');
//		// test with anonymous user
//		$result = json_decode(
//			$this->testAction(
//				"/users/password/$id.json",
//				array(
//					'data'   => array(
//						'User' => array(
//							'password' => 'abcedfghijk',
//						),
//					),
//					'method' => 'put',
//					'return' => 'contents'
//				)
//			),
//			true
//		);
//	}
//
//	public function testUpdatePasswordUserIdIsMissing() {
//		$ad = $this->User->findByUsername('admin@passbolt.com');
//		$this->User->setActive($ad);
//
//		$this->expectException('HttpException', 'The user id is missing');
//		$result = json_decode($this->testAction('/users/password.json', array('return' => 'contents', 'method' => 'put'), true));
//	}
//
//	public function testUpdatePasswordUserIdNotValid() {
//		$ad = $this->User->findByUsername('admin@passbolt.com');
//		$this->User->setActive($ad);
//
//		$this->expectException('HttpException', 'The user id is invalid');
//		$result = json_decode(
//			$this->testAction('/users/password/badId.json', array('return' => 'contents', 'method' => 'put'), true)
//		);
//	}
//
//	public function testUpdatePasswordUserDoesNotExist() {
//		$ad = $this->User->findByUsername('admin@passbolt.com');
//		$this->User->setActive($ad);
//
//		$this->expectException('HttpException', 'The user does not exist');
//		$result = json_decode(
//			$this->testAction(
//				'/users/password/4ff6111b-efb8-4a26-aab4-2184cbdd56ca.json',
//				array('return' => 'contents', 'method' => 'put'),
//				true
//			)
//		);
//	}
//
//	public function testUpdatePasswordNoDataProvided() {
//		$ad = $this->User->findByUsername('admin@passbolt.com');
//		$this->User->setActive($ad);
//
//		$this->expectException('HttpException', 'No data were provided');
//		$kk = $this->User->findByUsername('user@passbolt.com');
//		$id = $kk['User']['id'];
//
//		$result = json_decode(
//			$this->testAction(
//				"/users/password/$id.json",
//				array(
//					'method' => 'put',
//					'return' => 'contents'
//				)
//			),
//			true
//		);
//	}
//
//	public function testUpdatePassword() {
//		$ad = $this->User->findByUsername('admin@passbolt.com');
//		$this->User->setActive($ad);
//		$kk = $this->User->findByUsername('user@passbolt.com');
//		$oldPwdHash = $kk['User']['password'];
//		$id = $kk['User']['id'];
//
//		$data['User']['password'] = '1234567890';
//		$result = json_decode(
//			$this->testAction(
//				"/users/password/$id.json",
//				array(
//					'data'   => $data,
//					'method' => 'put',
//					'return' => 'contents'
//				)
//			),
//			true
//		);
//		$this->assertEquals(
//			Message::SUCCESS,
//			$result['header']['status'],
//			"Edit : /users.json : The test should return sucess but is returning " . print_r($result, true)
//		);
//
//		$kk = $this->User->findByUsername('user@passbolt.com');
//		$this->assertNotEqual($oldPwdHash, $kk['User']['password'], "The user " . $kk['User']['username'] . " should'nt have the same password");
//	}
}
