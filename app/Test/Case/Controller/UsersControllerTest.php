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
App::uses('CakeSessionFixture', 'Test/Fixture');

class UsersControllerTest extends ControllerTestCase {

	public $fixtures = array(
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
			'app.authenticationBlacklist',
			'core.cakeSession',
		);

	public $user;

	public $session;

	public function setUp() {
		parent::setUp();
		$this->User = Common::getModel('User');
		$this->Gpgkey = Common::getModel('Gpgkey');
		$u = $this->User->get();
		$this->session = new CakeSession();
		$this->session->init();
	}

	public function tearDown() {
		parent::tearDown();
		// Make sure there is no session active after each test
		$this->User->setInactive();
	}

	public function testIndexNoAllowed() {
		$this->setExpectedException('HttpException', 'You need to login to access this location');
		// test with anonymous user
		$result = json_decode($this->testAction('/users.json', array('return' => 'contents', 'method' => 'GET'), true));
	}

	public function testIndex() {
		// test with normal user
		$user = $this->User->findByUsername('user@passbolt.com');
		$this->User->setActive($user);

		$result = json_decode($this->testAction('/users.json', array('return' => 'contents', 'method' => 'GET'), true));
		$this->assertEquals($result->header->status, Message::SUCCESS, '/users return something');

		// @todo empty database and test if index throws warning for no
		$this->User->deleteAll(array('User.active' => '1'));
		$this->User->deleteAll(array('User.active' => '0'));
		$result = json_decode($this->testAction('/users.json', array('return' => 'contents', 'method' => 'GET'), true));
		$this->assertEquals($result->header->status, Message::NOTICE, '/users return a warning');
	}

	public function testViewNoAllowed() {
		$this->setExpectedException('HttpException', 'You need to login to access this location');
		// test with anonymous user
		$result = json_decode(
			$this->testAction(
				'/users/'. Common::uuid('user.id.user') . '.json',
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
		$user = $this->User->findByUsername('user@passbolt.com');
		$this->User->setActive($user);

		$this->setExpectedException('HttpException', 'The user id is invalid');
		$result = json_decode(
			$this->testAction('/users/badId.json', array('return' => 'contents', 'method' => 'GET'), true)
		);
	}

	public function testViewUserDoesNotExist() {
		// test with normal user
		$user = $this->User->findByUsername('user@passbolt.com');
		$this->User->setActive($user);

		$this->setExpectedException('HttpException', 'The user does not exist');
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
		$user = $this->User->findByUsername('user@passbolt.com');
		$this->User->setActive($user);

		$result = json_decode(
			$this->testAction(
				'/users/'. Common::uuid('user.id.user') . '.json',
				array('return' => 'contents', 'method' => 'GET'),
				true
			)
		);
		$this->assertEquals($result->header->status, Message::SUCCESS, '/users return something');

		$result = json_decode(
			$this->testAction(
				'/users/' . User::get('id') . '.json',
				array('return' => 'contents', 'method' => 'GET'),
				true
			)
		);
		$this->assertEquals(
			$result->header->status,
			Message::SUCCESS,
			'/users/view asking for self should return something'
		);
	}

	public function testAddNoAllowed() {
		// normal user don't have the right to add user
		$user = $this->User->findByUsername('user@passbolt.com');
		$this->User->setActive($user);

		$this->setExpectedException('HttpException', 'You are not authorized to access that location');
		// test with anonymous user
		$result = json_decode(
			$this->testAction(
				'/users.json',
				array(
					'data'   => array(
						'User' => array(
							'username' => 'testadd1@passbolt.com',
							'password' => 'test1',
							'role_id'  => Common::uuid('role.id.user'),
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
		$user = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($user);
		$result = json_decode(
			$this->testAction(
				'/users.json',
				array(
					'data'   => array(
						'User' => array(
							'username' => 'testadd1@passbolt.com',
							'password' => 'abcedfghijk',
							'role_id'  => Common::uuid('role.id.user'),
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
		$user = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($user);
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
		$user = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($user);
		$this->setExpectedException('HttpException', 'Profile data are missing');
		$result = json_decode(
			$this->testAction(
				'/users.json',
				array(
					'data'   => array(
						'User' => array(
							'username' => 'testprofile@passbolt.com',
							'password' => 'abcedfghijk',
							'role_id'  => Common::uuid('role.id.user'),
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
		$steve = $this->User->findByUsername('dame@passbolt.com');
		$this->User->setActive($steve);
		$user = $this->User->findByUsername('user@passbolt.com');

		$id = $user['User']['id'];

		$this->setExpectedException('HttpException', 'You are not authorized to access that location');
		// test with anonymous user
		$result = json_decode(
			$this->testAction(
				"/users/$id.json",
				array(
					'data'   => array(
						'User' => array(
							'id'       => $user['User']['id'],
							'username' => 'user-modified@passbolt.com',
							'password' => 'abcedfghijk',
							'role_id'  => Common::uuid('role.id.user'),
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

		$this->setExpectedException('HttpException', 'The user id is missing');
		$result = json_decode($this->testAction('/users.json', array('return' => 'contents', 'method' => 'put'), true));
	}

	public function testUpdateUserIdNotValid() {
		$ad = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($ad);

		$this->setExpectedException('HttpException', 'The user id is invalid');
		$result = json_decode(
			$this->testAction('/users/badId.json', array('return' => 'contents', 'method' => 'put'), true)
		);
	}

	public function testUpdateUserDoesNotExist() {
		$ad = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($ad);

		$this->setExpectedException('HttpException', 'The user does not exist');
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
		$user = $this->User->findByUsername('user@passbolt.com');
		$id = $user['User']['id'];
		$data['User']['username'] = 'user@34@passbolt.com';

		$this->setExpectedException('HttpException', 'Could not validate User');
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

		$this->setExpectedException('HttpException', 'No data were provided');
		$user = $this->User->findByUsername('user@passbolt.com');
		$id = $user['User']['id'];

		$user['User']['username'] = 'user-modified@passbolt.com';
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
		$user = $this->User->findByUsername('user@passbolt.com');
		$id = $user['User']['id'];

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
			$user['User']['id'],
			"Edit : /users.json : the id of the retrieved user  should be {$user['User']['id']} but is {$user['User']['id']}"
		);
	}

	public function testUpdatePasswordFromAdmin() {
		$ad = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($ad);
		$user = $this->User->findByUsername('user@passbolt.com');
		$id = $user['User']['id'];

		$data['User']['password'] = 'test12345678';
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
	}

	public function testUpdateOwnPasswordFromAdminNoCurrentPassword() {
		$ad = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($ad);
		$id = $ad['User']['id'];

		$this->setExpectedException('HttpException', 'Current Password must be provided');
		$data['User']['password'] = 'test12345678';
		$resRaw = $this->testAction(
			"/users/$id.json",
			array(
				'data'   => $data,
				'method' => 'put',
				'return' => 'contents'
			)
		);
	}

	public function testUpdateOwnPasswordFromLU() {
		$user = $this->User->findByUsername('user@passbolt.com');
		$this->User->setActive($user);
		$id = $user['User']['id'];

		$data['User']['current_password'] = 'password';
		$data['User']['password'] = 'test12345678';
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
			"Edit : /users.json : The test should return success but is returning " . print_r($result, true)
		);
	}

	public function testUpdateOwnPasswordFromLUWrongCurrentPassword() {
		$user = $this->User->findByUsername('user@passbolt.com');
		$this->User->setActive($user);
		$id = $user['User']['id'];

		$this->setExpectedException('HttpException', 'Could not validate User');
		$data['User']['current_password'] = 'wrongpassword';
		$data['User']['password'] = 'test12345678';
		$resRaw = $this->testAction(
			"/users/$id.json",
			array(
				'data'   => $data,
				'method' => 'put',
				'return' => 'contents'
			)
		);
	}

	public function testDeleteNoAllowed() {
		// normal user don't have the right to delete user
		$u = $this->User->findByUsername('user@passbolt.com');
		$this->User->setActive($u);

		$this->setExpectedException('HttpException', 'You are not authorized to access that location');
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

		$this->setExpectedException('HttpException', 'The user id is missing');
		$result = json_decode(
			$this->testAction('/users.json', array('return' => 'contents', 'method' => 'delete'), true)
		);
	}

	public function testDeleteUserIdNotValid() {
		$ad = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($ad);

		$this->setExpectedException('HttpException', 'The user id is invalid');
		$result = json_decode(
			$this->testAction('/users/badId.json', array('return' => 'contents', 'method' => 'delete'), true)
		);
	}

	public function testDeleteUserDoesNotExist() {
		$ad = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($ad);

		$this->setExpectedException('HttpException', 'The user does not exist');
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
		$this->assertEquals(
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
		$this->assertEquals(
			1,
			$deleted['User']['deleted'],
			"delete /users/{$u['User']['id']}.json : after delete, the value of the field deleted should be 1 but is {$deleted['User']['deleted']}"
		);
	}

	public function testUpdateAvatarNoAllowed() {
		// normal user don't have the right to add user
		$steve = $this->User->findByUsername('dame@passbolt.com');
		$this->User->setActive($steve);
		$user = $this->User->findByUsername('user@passbolt.com');

		$id = $user['User']['id'];

		$this->setExpectedException('HttpException', 'You are not authorized to access that location');
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

		$this->setExpectedException('HttpException', 'The user id is missing');
		$result = json_decode($this->testAction('/users/avatar.json', array('return' => 'contents', 'method' => 'post'), true));
	}

	public function testUpdateAvatarUserIdNotValid() {
		$ad = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($ad);

		$this->setExpectedException('HttpException', 'The user id is invalid');
		$result = json_decode(
			$this->testAction('/users/avatar/badId.json', array('return' => 'contents', 'method' => 'post'), true)
		);
	}

	public function testUpdateAvatarUserDoesNotExist() {
		$ad = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($ad);

		$this->setExpectedException('HttpException', 'The user does not exist');
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

		$this->setExpectedException('HttpException', 'No data were provided');
		$user = $this->User->findByUsername('user@passbolt.com');
		$id = $user['User']['id'];

		$user['User']['username'] = 'user-modified@passbolt.com';
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

		$user = $this->User->findByUsername('user@passbolt.com');
		$id = $user['User']['id'];

		$findConditions = array('User.id' => $id);
		$options = $this->User->getFindOptions('User::view', User::get('Role.name'), $findConditions);
		$users = $this->User->find('all', $options);
		$user = reset($users);

		// Get empty image url.
		$defaults = Configure::read('Media.imageDefaults.ProfileAvatar');
		$diff = Hash::diff($user['Profile']['Avatar']['url'], $defaults);

		$this->assertEmpty($diff, "The user " . $user['User']['username'] . " should have the default avatar");

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
		$user = reset($users);

		$this->assertNotEmpty($user['Profile']['Avatar'], "The user " . $user['User']['username'] . " should have an avatar");
	}

	private function __createAccount($username) {
		$userAdd = $this->testAction(
			'/users.json',
			array(
				'data'   => array(
					'User' => array(
						'username' => $username,
						'role_id'  => Common::uuid('role.id.user')
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
		$this->assertEquals($u['User']['active'], 0, 'The user created should be inactive by default');

		// Check that there is an entry in the table authenticationToken.
		$AuthenticationToken = Common::getModel('AuthenticationToken');
		$at = $AuthenticationToken->findByUserId($user['User']['id']);
		$this->assertEquals((bool)$at, true, 'There should be an authentication token created for the user');
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
		$this->assertEquals($u['User']['active'], true, 'The user should be activated after account validation, but is not');

		// Check Authentication Token is not active anymore.
		$at = $AuthenticationToken->findByUserId($user['User']['id']);
		$this->assertEquals($at['AuthenticationToken']['active'], 0, 'The authentication token should be deactivated after account activation, but it is not');

		// Check that the user returned is the right one.
		$this->assertEquals($json['body']['User']['username'], $user['User']['username'], 'The authentication token should be deactivated after account activation, but it is not');

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
		$this->assertEquals($p['Profile']['first_name'], 'Rene', "After account validation the user first_name should be rene, but is {$p['Profile']['first_name']}");
	}

	public function testAccountValidationWithGpgkeyEdit() {
		$ad = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($ad);
		$user = $this->__createAccount('jean-gabin@gmail.com');

		$AuthenticationToken = Common::getModel('AuthenticationToken');
		$at = $AuthenticationToken->findByUserId($user['User']['id']);

		// Dummy key taken from one generated by pgpjs.
		$dummyKey = array(
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----

Version: OpenPGP.js v0.7.2

Comment: http://openpgpjs.org

xsBNBFVDPCsBCACEoD8M8/OWckxRtN4dlB/LzDXQLKYtKprCSwXq9adTiTmI
S7QjVyL01j3e8mWw2rM8qQPf8Tcc2sXw6JoQD85Ul87W887ruSG9yeV/1rjh
m34q3ZMMtudwuZnwrFRyMHOonbllZ6nC+ikhW7yOtEjPjGU5IUAeOHZuGdB1
loCtJFrmL1yI5VqXa/5dCPMnuq3ivwG08AzKdDJj4FtDwtPqu8gwiiBMTIvc
ye3FNr1UzY8shCBeCM+c0rbeXmnymz/cnPsoaYmPk/wCE6jDQZvN7aqnYoXz
s5QBSIXhmjoF1R9pXJWFwWBWyiUUWd3+HtyqN39DE11Zufnmv/HL0RfDABEB
AAHNLWtldmluIG11bGxlciA8a2V2aW4ubXVsbGVyQGNsaWNrb25mcmVuY2gu
Y29tPsLAcgQQAQgAJgUCVUM8LQYLCQgHAwIJEBBHV2HtBkBqBBUIAgoDFgIB
AhsDAh4BAAA47gf/c7ImdYBOsQnptgtLnMpQxkvkMdeYPtBpF89QWwy6HIHP
pO9KfBDC44/K1+RT4UUGx5HsdUPGQGrDUp1RttCyykNsy2dduhkFTl6fap59
Zalk6jUkkJ7aVapgFhKhCsiIyhuR/DBEi+kTX4YE8OvPsAKHRc+tutUNX8hv
16CUKIoZpNbSiKeSlDHrUsE3tsYUo00n79Jmcudh/mMkul21B31tMXE4Kn3+
pVoRAuS16OVNgiA4B87Gowy9Ze2MR+f5c6M7vQSgY8L24itHTgmNsmewAHSQ
9XYjlQ8HeU2xUcG/tYklrI2bZ4Mtp26iKhGHb0ZLq8NoITtidm1A693tTc7A
TQRVQzwuAQgAjv2zqTpq4pg+46T+rQWhOTSZNtafslTgbMWmp8nZ0nXKo0xr
+Eui7SWtDKBszC6HpFiF/RWEgtpwzuuLP4OcF4a8+PLh2yUBBTqwJcn3NroU
baa8YgqYnfhgfDePWSJlXZlujnVJMT+E0W1zajvP8EYc0zhG5hgE0CO/U2K4
SBZV37fCDjKtIbHJd7jJtry/BPpyoLKIWDXmhw/PhLazL7iysFu+0QOt6eFK
/SUG3kyjJ7qo1e6kQ301U9ezGE87pHbS6/zAvtFPo2+5PWwAc/y/Ty8PYEKH
G6HYzpd6EqW0J0x1W6E9JzXGU/L2QWIc+KwfnDMVgZAnt40CsrnNrQARAQAB
wsBfBBgBCAATBQJVQzw0CRAQR1dh7QZAagIbDAAAp48H/jxI1rj9IEMYiWVR
KocPnXQ9BDkCX6Ty8tOn/e8i7Mxpiml1GX7pxigbSI1Si2uWayl7TH572M7Q
mnw1ThlvpMTjDdI1rdG6QgVEjzd3cHgH1yr2i+sduABTbfwxBSjzcItSs6yk
dV40FLmlknJY9iXjsVnnjK0k3V3TtfEL/f11EKTy4U58Fz+IupgTj7fOKbhj
tpWQFHMWY2wRxXGn+93Nrrv30nE59M7V8F4/mVTpH2buEKoukxgxoTGaSXeA
sJAhE5sAtWpAEh09OfArauZNXdvZ+RPscSU3X2MyYkh4uaT722knDHeqj/48
Y163Zeuqb7k4oayBB2o188VJy/E=

=nhkC

-----END PGP PUBLIC KEY BLOCK-----',

		    'fingerprint' => '5d6c70eb8d024c622cbfea9f10475761ed06406a',
		    'key_id' => '10475761ed06406a',
		    'bits' => '2048',
		    'type' => 'rsa',
		    'uid' => 'kevin muller <kevin.muller@clickonfrench.com>',
		    'key_created' => '2015-05-01T08:41:15.000Z',
		    'expires' => '',
		);

		$url = '/users/validateAccount/' . $user['User']['id'] . '.json';
		$validate = $this->testAction(
			$url,
			array(
				'data'   => array (
					'AuthenticationToken' => array (
						'token' => $at['AuthenticationToken']['token'],
					),
					'Gpgkey' => $dummyKey
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
		$p = $this->Gpgkey->findByUserId($user['User']['id']);
		$this->assertEquals($p['Gpgkey']['key'], $dummyKey['key'], "After account validation the key was supposed to be set, but is not");
	}

	public function testAccountValidationWrongUserId() {
		$this->setExpectedException('HttpException', 'The user does not exist');
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
		$this->setExpectedException('HttpException', 'Invalid token');
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
//		$steve = $this->User->findByUsername('dame@passbolt.com');
//		$this->User->setActive($steve);
//		$user = $this->User->findByUsername('user@passbolt.com');
//
//		$id = $user['User']['id'];
//
//		$this->setExpectedException('HttpException', 'You are not authorized to access that location');
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
//		$this->setExpectedException('HttpException', 'The user id is missing');
//		$result = json_decode($this->testAction('/users/password.json', array('return' => 'contents', 'method' => 'put'), true));
//	}
//
//	public function testUpdatePasswordUserIdNotValid() {
//		$ad = $this->User->findByUsername('admin@passbolt.com');
//		$this->User->setActive($ad);
//
//		$this->setExpectedException('HttpException', 'The user id is invalid');
//		$result = json_decode(
//			$this->testAction('/users/password/badId.json', array('return' => 'contents', 'method' => 'put'), true)
//		);
//	}
//
//	public function testUpdatePasswordUserDoesNotExist() {
//		$ad = $this->User->findByUsername('admin@passbolt.com');
//		$this->User->setActive($ad);
//
//		$this->setExpectedException('HttpException', 'The user does not exist');
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
//		$this->setExpectedException('HttpException', 'No data were provided');
//		$user = $this->User->findByUsername('user@passbolt.com');
//		$id = $user['User']['id'];
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
//		$user = $this->User->findByUsername('user@passbolt.com');
//		$oldPwdHash = $user['User']['password'];
//		$id = $user['User']['id'];
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
//		$user = $this->User->findByUsername('user@passbolt.com');
//		$this->assertNotEqual($oldPwdHash, $user['User']['password'], "The user " . $user['User']['username'] . " should'nt have the same password");
//	}
}
