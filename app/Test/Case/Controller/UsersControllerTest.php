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
		'app.user_agent',
		'app.controller_log'
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

	/**
	 * Create a dummy account using a call to the controller.
	 *
	 * @param $username
	 *   username requested.
	 *
	 * @return mixed
	 *   user data
	 */
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

	/**
	 * Test a call to index without being logged in.
	 */
	public function testIndexNoAllowed() {
		$this->setExpectedException('HttpException', 'You need to login to access this location');
		// test with anonymous user
		$this->testAction('/users.json', array('return' => 'contents', 'method' => 'GET'), true);
	}

	/**
	 * Test a call to index After logging in.
	 */
	public function testIndex() {
		// test with normal user
		$user = $this->User->findById(common::uuid('user.id.user'));
		$this->User->setActive($user);

		$result = json_decode($this->testAction('/users.json', array('return' => 'contents', 'method' => 'GET'), true));
		$this->assertEquals($result->header->status, Status::SUCCESS, '/users return something');
	}

	/**
	 * Test a call to index filtered by group with a group which doesn't exist
	 */
	public function testIndexFilteredByGroupWhichDoesntExist() {
		// test with normal user
		$user = $this->User->findById(common::uuid('user.id.user'));
		$this->User->setActive($user);

		$this->setExpectedException('HttpException', 'The group doesn\'t exist');
		$groupId = Common::uuid('not-valid-reference');
		$url = '/users.json?fltr_model_group=' . $groupId;
		$this->testAction($url, array('return' => 'contents', 'method' => 'GET'), true);
	}

	/**
	 * Test a call to index filtered by group with a wrong group id
	 */
	public function testIndexFilteredByGroupWithWrongId() {
		// test with normal user
		$user = $this->User->findById(common::uuid('user.id.user'));
		$this->User->setActive($user);

		$this->setExpectedException('HttpException', 'The group id is invalid');
		$groupId = 'wrong_id';
		$url = '/users.json?fltr_model_group=' . $groupId;
		$this->testAction($url, array('return' => 'contents', 'method' => 'GET'), true);
	}

	/**
	 * Test a call to index filtered by group
	 */
	public function testIndexFilteredByGroup() {
		// test with normal user
		$user = $this->User->findById(common::uuid('user.id.user'));
		$this->User->setActive($user);

		$groupId = Common::uuid('group.id.management');
		$data = array(
			'fltr_model_group' => $groupId,
		);
		$result = json_decode($this->testAction('/users.json', array('return' => 'contents', 'method' => 'GET', 'data' => $data), true));
		$this->assertEquals($result->header->status, Status::SUCCESS, '/users return something');
		$this->assertNotEmpty($result->body);
		$this->assertEquals($result->body[0]->User->username, 'dame@passbolt.com');
	}

	/**
	 * Test a call to index filtered by group
	 */
	public function testIndexFilteredByKeywords() {
		// test with normal user
		$user = $this->User->findById(common::uuid('user.id.user'));
		$this->User->setActive($user);

		$data = array(
			'fltr_keywords' => 'Betty'
		);
		$result = json_decode($this->testAction('/users.json', array('return' => 'contents', 'method' => 'GET', 'data' => $data), true));
		$this->assertEquals($result->header->status, Status::SUCCESS, '/users return something');
		$this->assertNotEmpty($result->body);
		$this->assertEquals($result->body[0]->User->username, 'betty@passbolt.com');
	}

	/**
	 * Test view function when not logged in.
	 */
	public function testViewNoAllowed() {
		$this->setExpectedException('HttpException', 'You need to login to access this location');
		// test with anonymous user
		$this->testAction(
			'/users/'. Common::uuid('user.id.user') . '.json',
			array('return' => 'contents', 'method' => 'GET'),
			true
		);
	}

	/**
	 * Test view function with a missing user id
	 */
	public function testViewUserIdIsMissing() {
		// Unable to test missing id param because of route
	}

	/**
	 * Test view function with an invalid user id.
	 */
	public function testViewUserIdNotValid() {
		// test with normal user
		$user = $this->User->findById(common::uuid('user.id.user'));
		$this->User->setActive($user);

		$this->setExpectedException('HttpException', 'The user id is invalid');
		$this->testAction('/users/badId.json', array('return' => 'contents', 'method' => 'GET'), true);
	}

	/**
	 * Test view with a non existing user.
	 */
	public function testViewUserDoesNotExist() {
		// test with normal user
		$user = $this->User->findById(common::uuid('user.id.user'));
		$this->User->setActive($user);

		$id = Common::uuid('not-valid-reference');
		$this->setExpectedException('HttpException', 'The user does not exist');
		$this->testAction( "/users/{$id}.json", array('return' => 'contents', 'method' => 'GET'), true);
	}

	/**
	 * Test view current user
	 */
	public function testViewCurrentUser() {
		// test with normal user
		$user = $this->User->findById(common::uuid('user.id.user'));
		$this->User->setActive($user);

		$result = json_decode(
			$this->testAction(
				'/users/me.json',
				array('return' => 'contents', 'method' => 'GET'),
				true
			)
		);
		$this->assertEquals($result->header->status, Status::SUCCESS, '/user return something');
		$this->assertNotEmpty($result->body);
		$this->assertEquals($result->body->User->username, 'user@passbolt.com');
	}

	/**
	 * Test view in a normal scenario.
	 */
	public function testView() {
		// test with normal user
		$user = $this->User->findById(common::uuid('user.id.user'));
		$this->User->setActive($user);

		$result = json_decode(
			$this->testAction(
				'/users/'. Common::uuid('user.id.ada') . '.json',
				array('return' => 'contents', 'method' => 'GET'),
				true
			)
		);
		$this->assertEquals($result->header->status, Status::SUCCESS, '/users return something');
		$this->assertNotEmpty($result->body);
		$this->assertEquals($result->body->User->username, 'ada@passbolt.com');
	}

	/**
	 * Test add for a non admin user.
	 */
	public function testAddNoAllowed() {
		// normal user don't have the right to add user
		$user = $this->User->findById(common::uuid('user.id.user'));
		$this->User->setActive($user);

		$this->setExpectedException('HttpException', 'You are not authorized to access that location');
		json_decode(
			$this->testAction(
				'/users.json',
				array(
					'data'   => array(
						'User' => array(
							'username' => 'testadd1@passbolt.com',
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

	/**
	 * Test add without profile information.
	 */
	public function testAddWithoutProfileInfo() {
		$user = $this->User->findById(common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$this->setExpectedException('HttpException', 'Profile data are missing');
		json_decode(
			$this->testAction(
				'/users.json',
				array(
					'data'   => array(
						'User' => array(
							'username' => 'testprofile@passbolt.com',
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

	/**
	 * Test add with missing role id in data.
	 */
	public function testAddWithoutRoleId() {
		$user = $this->User->findById(common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$result = json_decode(
			$this->testAction(
				'/users.json',
				array(
					'data'   => array(
						'User' => array(
							'username' => 'testaddnoroleid@passbolt.com',
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
			Status::SUCCESS,
			$result['header']['status'],
			"Add : /users.json : The test should return success but is returning " . print_r($result, true)
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

	/**
	 * Test add for a logged in admin.
	 */
	public function testAdd() {
		$user = $this->User->findById(common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$result = json_decode(
			$this->testAction(
				'/users.json',
				array(
					'data'   => array(
						'User' => array(
							'username' => 'testadd1@passbolt.com',
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
			Status::SUCCESS,
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

	/**
	 * Test update by a non admin user.
	 */
	public function testUpdateNoAllowed() {
		// normal user don't have the right to add user
		$user = $this->User->findById(common::uuid('user.id.dame'));
		$this->User->setActive($user);

		// the user to update
		$id = common::uuid('user.id.user');

		$this->setExpectedException('HttpException', 'You are not authorized to access that location');
		json_decode(
			$this->testAction(
				"/users/$id.json",
				array(
					'data'   => array(
						'User' => array(
							'id'       => $user['User']['id'],
							'username' => 'user-modified@passbolt.com',
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

	/**
	 * Test update with a missing user id.
	 */
	public function testUpdateUserIdIsMissing() {
		$user = $this->User->findById(common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$this->setExpectedException('HttpException', 'The user id is missing');
		$this->testAction('/users.json', array('return' => 'contents', 'method' => 'put'), true);
	}

	/**
	 * Test update with a wrong user id.
	 */
	public function testUpdateUserIdNotValid() {
		$user = $this->User->findById(common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$this->setExpectedException('HttpException', 'The user id is invalid');
		$this->testAction('/users/badId.json', array('return' => 'contents', 'method' => 'put'), true);
	}

	/**
	 * Test update with a non existing user.
	 */
	public function testUpdateUserDoesNotExist() {
		$user = $this->User->findById(common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$id = Common::uuid('not-valid-reference');
		$this->setExpectedException('HttpException', 'The user does not exist');
		$this->testAction(
			"/users/{$id}.json",
			array('return' => 'contents', 'method' => 'put'),
			true
		);
	}

	/**
	 * Test update with no data provided.
	 */
	public function testUpdateNoDataProvided() {
		$user = $this->User->findById(common::uuid('user.id.admin'));
		$this->User->setActive($user);

		// the user to update
		$id = common::uuid('user.id.user');

		$this->setExpectedException('HttpException', 'No data were provided');
		$this->testAction(
			"/users/$id.json",
			array('method' => 'put', 'return' => 'contents')
		);
	}

	/**
	 * Test update with providing an invalid username.
	 */
	public function testUpdateUsernameNotValid() {
		$user = $this->User->findById(common::uuid('user.id.admin'));
		$this->User->setActive($user);

		// the user to update
		$id = common::uuid('user.id.user');
		$data['User']['username'] = 'user@34@passbolt.com';

		$this->setExpectedException('HttpException', 'Could not validate User');
		$this->testAction(
			"/users/$id.json",
			array(
				'data'   => $data,
				'method' => 'put',
				'return' => 'contents'
			)
		);
	}

	/**
	 * Test update as an admin.
	 */
	public function testUpdateAsAdmin() {
		$user = $this->User->findById(common::uuid('user.id.admin'));
		$this->User->setActive($user);

		// the user to update
		$id = common::uuid('user.id.user');

		// update the user by changing its role
		$roleId = Common::uuid('role.id.admin');
		$data['User']['role_id'] = $roleId;
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
			Status::SUCCESS,
			$result['header']['status'],
			"Edit : /users.json : The test should return sucess but is returning " . print_r($result, true)
		);

		// check that User was properly saved
		$userUpdated = $this->User->findByUsername("user@passbolt.com");
		$this->assertEquals(
			$userUpdated['User']['role_id'],
			$roleId,
			"Edit : /users.json : the role of the retrieved user should be {$roleId} but is {$userUpdated['User']['role_id']}"
		);
		$this->assertEquals(
			$userUpdated['User']['id'],
			$id,
			"Edit : /users.json : the id of the retrieved user should be {$id} but is {$userUpdated['User']['id']}"
		);
	}

	/**
	 * Test update as an admin.
	 */
	public function testUpdateOwnAccount() {
		$findData = array('User.id' => Common::uuid('user.id.user'));
		$findOptions = $this->User->getFindOptions('User::view', User::get('Role.name'), $findData);
		$user = $this->User->find('first', $findOptions);
		$this->User->setActive($user);

		// The user data to update
		$data = $user;
		unset ($data['User']['role_id']);
		unset ($data['Profile']['date_of_birth']);
		$id = $user['User']['id'];
		$userFirstNameUpdated = $user['Profile']['first_name'] . ' updated';
		$data['Profile']['first_name'] = $userFirstNameUpdated;

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
			Status::SUCCESS,
			$result['header']['status'],
			"Edit : /users.json : The test should return success but is returning " . print_r($result, true)
		);

		// check that User was properly saved
		$findData = array('User.id' => Common::uuid('user.id.user'));
		$findOptions = $this->User->getFindOptions('User::view', User::get('Role.name'), $findData);
		$userUpdated = $this->User->find('first', $findOptions);
		$this->assertEquals(
			$userUpdated['Profile']['first_name'],
			$userFirstNameUpdated,
			"Edit : /users.json : the first name of the retrieved user should be {$userFirstNameUpdated} but is {$userUpdated['Profile']['first_name']}"
		);
		$this->assertEquals(
			$userUpdated['User']['id'],
			$user['User']['id'],
			"Edit : /users.json : the id of the retrieved user should be {$user['User']['id']} but is {$userUpdated['User']['id']}"
		);
	}

	/**
	 * Test that an admin can update a user's role to admin
	 */
	public function testUpdateAdminCanUpdateSomebodysRoleToAdmin() {
		// normal user don't have the right to add user
		$user = $this->User->findById(common::uuid('user.id.admin'));
		$this->User->setActive($user);

		// the user to update
		$id = common::uuid('user.id.dame');
		$adminRoleId = common::uuid('role.id.admin');

		$resRaw = $this->testAction(
			"/users/$id.json",
			array(
				'data'   => array(
					'User' => array(
						'id' => $id,
						'role_id' => $adminRoleId,
					),
				),
				'method' => 'put',
				'return' => 'contents'
			));
		json_decode($resRaw, true);

		$user = $this->User->findById(common::uuid('user.id.dame'));
		$this->assertEquals(
			$user['User']['role_id'],
			$adminRoleId,
			"Edit : /users.json : After update the role of dame@passbolt.com should be admin, but is not"
		);
	}

	/**
	 * Test that an admin cannot update his own role.
	 */
	public function testUpdateAdminCantUpdateOwnRole() {
		$id = common::uuid('user.id.admin');
		$user = $this->User->findById($id);
		$this->User->setActive($user);

    	$userRoleId = Common::uuid('role.id.user');

		$this->testAction(
			"/users/$id.json",
			array(
				'data'   => array(
					'User' => array(
						'id' => $id,
						'role_id' => $userRoleId,
					),
				),
				'method' => 'put',
				'return' => 'contents'
			));

		$userUpdated = $this->User->findByUsername('admin@passbolt.com');
		$this->assertNotEquals($userUpdated['User']['role_id'], $userRoleId);
		$this->assertEquals($user['User']['role_id'], $userUpdated['User']['role_id']);
	}

  /**
   * Test that admin cannot set himself as non active.
   */
  public function testUpdateAdminCantSetOwnInactive() {
    // the user to update
	  $id = common::uuid('user.id.admin');
	  $user = $this->User->findById($id);
	  $this->User->setActive($user);

    $this->testAction(
      "/users/$id.json",
      array(
        'data'   => array(
          'User' => array(
            'id' => $id,
            'active' => 0,
          ),
        ),
        'method' => 'put',
        'return' => 'contents'
      ));

	  $user = $this->User->findById($id);
    $this->assertEquals($user['User']['active'], '1', 'After update the actived field should still be true');
  }

  /**
   * Test that normal user cannot set himself as non active.
   */
  public function testUpdateNonAdminCantSetOwnInactive() {
    // normal user don't have the right to add user
	  $id = common::uuid('user.id.dame');
	  $user = $this->User->findById($id);
	  $this->User->setActive($user);

    $this->testAction(
      "/users/$id.json",
      array(
        'data'   => array(
          'User' => array(
            'id' => $id,
            'active' => 0,
          ),
        ),
        'method' => 'put',
        'return' => 'contents'
      ));

    $admin = $this->User->findById($id);
    $this->assertEquals($admin['User']['active'], '1', 'After update the actived field should still be true');
  }

	/**
	 * Test delete for non admin user.
	 */
	public function testDeleteNoAllowed() {
		$id = common::uuid('user.id.user');
		$user = $this->User->findById($id);
		$this->User->setActive($user);

		$this->setExpectedException('HttpException', 'You are not authorized to access that location');
		json_decode(
			$this->testAction(
				"/users/{$id}.json",
				array(
					'method' => 'delete',
					'return' => 'contents'
				)
			),
			true
		);
	}

	/**
	 * Test delete with missing user id.
	 */
	public function testDeleteUserIdIsMissing() {
		$user = $this->User->findById(common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$this->setExpectedException('HttpException', 'The user id is missing');
		$this->testAction('/users.json', array('return' => 'contents', 'method' => 'delete'), true);
	}

	/**
	 * Test delete with an invalid user id.
	 */
	public function testDeleteUserIdNotValid() {
		$user = $this->User->findById(common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$this->setExpectedException('HttpException', 'The user id is invalid');
		$this->testAction('/users/badId.json', array('return' => 'contents', 'method' => 'delete'), true);
	}


	/**
	 * Test delete yourself.
	 */
	public function testDeleteSelfNotAllowed() {
		$id = common::uuid('user.id.admin');
		$user = $this->User->findById($id);
		$this->User->setActive($user);

		$this->setExpectedException('HttpException', 'You are not allowed to delete yourself');
		$this->testAction("/users/{$id}.json", array('return' => 'contents', 'method' => 'delete'), true);
	}

	/**
	 * Test delete for a non existing user.
	 */
	public function testDeleteUserDoesNotExist() {
		$user = $this->User->findById(common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$id = Common::uuid('not-valid-reference');
		$this->setExpectedException('HttpException', 'The user does not exist');
		$this->testAction(
			"/users/{$id}.json",
			array('return' => 'contents', 'method' => 'delete'),
			true
		);
	}

	/**
	 * Test delete in a normal scenario.
	 */
	public function testDelete() {
		$user = $this->User->findById(common::uuid('user.id.admin'));
		$this->User->setActive($user);

		// the user to delete
		$userId = common::uuid('user.id.user');
		$result = json_decode(
			$this->testAction(
				"/users/{$userId}.json",
				array(
					'method' => 'delete',
					'return' => 'contents'
				)
			),
			true
		);
		$this->assertEquals(
			Status::SUCCESS,
			$result['header']['status'],
			"delete /users/{$userId}.json : The test should return a success but is returning {$result['header']['status']}"
		);

		$deleted = $this->User->findById($userId);
		$this->assertEquals(
			1,
			$deleted['User']['deleted'],
			"delete /users/{$userId}.json : after delete, the value of the field deleted should be 1 but is {$deleted['User']['deleted']}"
		);
	}

	/**
	 * Test update avatar by a non allowed user.
	 */
	public function testUpdateAvatarNoAllowed() {
		$user = $this->User->findById(common::uuid('user.id.dame'));
		$this->User->setActive($user);

		$id = common::uuid('user.id.user');

		$this->setExpectedException('HttpException', 'You are not authorized to access that location');
		// test with anonymous user
		$_FILES['file-0'] = array(
			'file' => array(
				'tmp_name' => APP . 'Test' . DS . 'Data' . DS . 'img' . DS . 'user.png'
			)
		);
		json_decode(
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

	/**
	 * Test update avatar when user id is missing.
	 */
	public function testUpdateAvatarUserIdIsMissing() {
		$user = $this->User->findById(common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$this->setExpectedException('HttpException', 'The user id is missing');
		$this->testAction('/users/avatar.json', array('return' => 'contents', 'method' => 'post'), true);
	}

	/**
	 * Test update avatar user id not valid.
	 */
	public function testUpdateAvatarUserIdNotValid() {
		$user = $this->User->findById(common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$this->setExpectedException('HttpException', 'The user id is invalid');
		$this->testAction('/users/avatar/badId.json', array('return' => 'contents', 'method' => 'post'), true);
	}

	/**
	 * Test updateAvatar when the user does not exist.
	 */
	public function testUpdateAvatarUserDoesNotExist() {
		$user = $this->User->findById(common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$id = Common::uuid('not-valid-reference');
		$this->setExpectedException('HttpException', 'The user does not exist');
		$this->testAction(
			"/users/avatar/{$id}.json",
			array('return' => 'contents', 'method' => 'post'),
			true
		);
	}

	/**
	 * Test updateAvatar when no data is provided.
	 */
	public function testUpdateAvatarNoDataProvided() {
		$user = $this->User->findById(common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$id = common::uuid('user.id.user');
		$this->setExpectedException('HttpException', 'No data were provided');
		$this->testAction(
			"/users/avatar/$id.json",
			array(
				'method' => 'post',
				'return' => 'contents'
			)
		);
	}

	/**
	 * Test updateAvatar in normal scenario.
	 */
	public function testUpdateAvatar() {
		$user = $this->User->findById(common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$id = common::uuid('user.id.user');
		$findConditions = array('User.id' => $id);
		$options = $this->User->getFindOptions('User::view', User::get('Role.name'), $findConditions);
		$user = $this->User->find('first', $options);

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
			Status::SUCCESS,
			$result['header']['status'],
			"Edit : /users.json : The test should return sucess but is returning " . print_r($result, true)
		);

		$findConditions = array('User.id' => $id);
		$options = $this->User->getFindOptions('User::view', User::get('Role.name'), $findConditions);
		$user = $this->User->find('first', $options);

		$this->assertNotEmpty($user['Profile']['Avatar'], "The user " . $user['User']['username'] . " should have an avatar");
	}

	/**
	 * Test account creation and token.
	 */
	public function testAccountCreateAndToken() {
		$user = $this->User->findById(common::uuid('user.id.admin'));
		$this->User->setActive($user);

		// Create an account for jean gabin
		$createdUser = $this->__createAccount('jean-gabin@gmail.com');
		$userId = $createdUser['User']['id'];

		// Verify that user is not active.
		$user = $this->User->findById($userId);
		$this->assertEquals($user['User']['active'], 0, 'The user created should be inactive by default');

		// Check that there is an entry in the table authenticationToken.
		$AuthenticationToken = Common::getModel('AuthenticationToken');
		$at = $AuthenticationToken->findByUserId($userId);
		$this->assertEquals((bool)$at, true, 'There should be an authentication token created for the user');
	}

	/**
	 * Test account validation when user id is missing.
	 */
	public function testAccountValidationUserIdIsMissing() {
    $this->User->setInactive();
		$this->setExpectedException('HttpException', 'The user id is missing');
		$this->testAction('/users/validateAccount.json', array('return' => 'contents', 'method' => 'put'), true);
	}

	/**
	 * Test accoutn validation user id not valid.
	 */
	public function testAccountValidationUserIdNotValid() {
    $this->User->setInactive();
		$this->setExpectedException('HttpException', 'The user id is invalid');
		$this->testAction('/users/validateAccount/badId.json', array('return' => 'contents', 'method' => 'put'), true);
	}

	/**
	 * Test account validation when the user does not exist.
	 */
	public function testAccountValidationUserDoesNotExist() {
    $this->User->setInactive();
		$id = Common::uuid('not-valid-reference');
		$this->setExpectedException('HttpException', 'The user does not exist');
		$this->testAction(
			"/users/validateAccount/{$id}.json",
			array('return' => 'contents', 'method' => 'put'),
			true
		);
	}

	/**
	 * Test account validation.
	 */
	public function testAccountValidationNoDataProvided() {
		$user = $this->User->findById(common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$user = $this->__createAccount('jean-gabin@gmail.com');
		$userId = $user['User']['id'];
    	$this->User->setInactive();

		$this->setExpectedException('HttpException', 'No data were provided');
		$url = "/users/validateAccount/{$userId}.json";
		$this->testAction($url, array(
			'method' => 'put',
			'return' => 'contents'
		));
	}

	/**
	 * Test account validation when the authentication token is invalid.
	 */
	public function testAccountValidationInvalidToken() {
		$user = $this->User->findById(common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$user = $this->__createAccount('jean-gabin@gmail.com');
		$userId = $user['User']['id'];
    	$this->User->setInactive();

		$this->setExpectedException('HttpException', 'Invalid token');
		$url = "/users/validateAccount/{$userId}.json";
		$this->testAction($url, array(
			'data'   => array (
				'AuthenticationToken' => array (
					'token' => Common::uuid(),
				),
			),
			'method' => 'put',
			'return' => 'contents'
		));
	}

	/**
	 * Test account validation.
	 */
	public function testAccountValidation() {
		$user = $this->User->findById(common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$user = $this->__createAccount('jean-gabin@gmail.com');
		$userId = $user['User']['id'];
    	$this->User->setInactive();

		$AuthenticationToken = Common::getModel('AuthenticationToken');
		$at = $AuthenticationToken->findByUserId($user['User']['id']);

		$url = "/users/validateAccount/{$userId}.json";
		$validate = $this->testAction($url, array(
			'data'   => array (
				'AuthenticationToken' => array (
					'token' => $at['AuthenticationToken']['token'],
				),
			),
			'method' => 'put',
			'return' => 'contents'
		));
		$json = json_decode($validate, true);
		$this->assertEquals(
			Status::SUCCESS,
			$json['header']['status'],
			"validateAccount /users/validateAccount/{$userId}.json : The test should return a success but is returning {$json['header']['status']}"
		);

		// Check that the user returned is the right one.
		$this->assertEquals($json['body']['User']['username'], $user['User']['username'], 'The authentication token should be deactivated after account activation, but it is not');

		// Get user and check if deactivated.
		$deactivatedUser = $this->User->findById($userId);
		$this->assertEquals($deactivatedUser['User']['active'], true, 'The user should be activated after account validation, but is not');

		// Check Authentication Token is not active anymore.
		$at = $AuthenticationToken->findByUserId($userId);
		$this->assertEquals($at['AuthenticationToken']['active'], 0, 'The authentication token should be deactivated after account activation, but it is not');
	}

	/**
	 * Test account validation with profile edition.
	 */
	public function testAccountValidationWithProfileEdit() {
		$user = $this->User->findById(common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$user = $this->__createAccount('jean-gabin@gmail.com');
		$userId = $user['User']['id'];
    	$this->User->setInactive();

		$AuthenticationToken = Common::getModel('AuthenticationToken');
		$at = $AuthenticationToken->findByUserId($userId);

		$url = "/users/validateAccount/{$userId}.json";
		$validate = $this->testAction($url, array(
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
		));
		$json = json_decode($validate, true);
		$this->assertEquals(
			Status::SUCCESS,
			$json['header']['status'],
			"validateAccount /users/validateAccount/{$userId}.json : The test should return a success but is returning {$json['header']['status']}"
		);

		// Get user and check if deactivated.
		$profile = $this->User->Profile->findByUserId($userId);
		$this->assertEquals($profile['Profile']['first_name'], 'Rene', "After account validation the user first_name should be rene, but is {$profile['Profile']['first_name']}");
	}

	/**
	 * Test account validation with a Gpgkey.
	 */
	public function testAccountValidationWithGpgkeyEdit() {
		$user = $this->User->findById(common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$user = $this->__createAccount('jean-gabin@gmail.com');
		$userId = $user['User']['id'];
		$this->User->setInactive();

		$AuthenticationToken = Common::getModel('AuthenticationToken');
		$at = $AuthenticationToken->findByUserId($userId);

		// Dummy key taken from one generated by pgpjs.
		$dummyKey = array(
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Version: OpenPGP.js v0.7.2
Comment: http://openpgpjs.org

xsBNBFYyMckBCACJQrcRTd5PTrGY0KnJErmONKutJyoIF3KcTWa5glH5BLT6
htzYS5cSyuB4nTUwHXFh6EV+iX2fsfU+K4jNr4JmDYcolOsXmNM0V8Z5Di+d
IElqJRnINpQWjDOZ11KpqaqoChzGklji/TMv39Iic0yY2UO4tmexVI7lJTTa
3Omk/P6O6zjgq262JAjTcQLTFeuVLKYqhdiuWIyqVTS+zfQgqeoGDQ1c6UWr
wkHEDW966IOrOvG4w6ekRmXJQEHjqAK8N7x39z6jNxLYnKUlXSMAB3+Fm8b6
QhHHw2VJHjVtxe6ywSvyzbn7gh4gysg9vP7YOjSXeLjOlSoFjKzTiCpnABEB
AAHNH2FkYSBsb3ZlbGFjZSA8YWRhQHBhc3Nib2x0LmNvbT7CwHIEEAEIACYF
AlYyMcwGCwkIBwMCCRDPd2OSgbVHnwQVCAIKAxYCAQIbAwIeAQAAvzcIAIWL
BuGxx8RqYA5UDn2IDvqweKP6/ltBt65RYxHv/khp/daNyZVl17ovupsQ3x00
Ve8GqqDDz3w5+Qi84Ab5Fw9yAtgawhAqvME+HAjlBo762Wy1+wE9yJDN/633
UaSp+dRgnaEXNpWr285SVwj4UVxnPqkogGhOjR1oIqTWTCb5sx+sBU+HiJNL
RCZCtE/RhOmcUv8VMuDPeyu7Mjq0G+v3SVJeDvmhkEqyZpZYW3DnBTjHufIH
u/JOdt8vbbG9rtNZELfrFA4GtXwqskbkSGl0q1mi6ojT590RLInoDLle1BtG
KIGGi1k0Iru/UWtzFILegDelMf48aanUZJYw8U3OwE0EVjIxzAEH/1ijwZSF
gK4Sua0yMYYQfuw7Lf9wYIDUegFoyJrbhbYn0abjxCqcauE/EkAFJOyeRjnW
8ucOPfUB+MyMBUGmATktpbE1s7OLTaoq+ebK0QeYFyuvZfxLenZRVAYeA4UI
o+fkW4HuPs811wcnUeiHjXQ95ccKeG6F47lX8C/RyssMZd3SDrlw3qi4/2T5
LzAGSEidBro4P8v8okWkgo7Uzy5HVswb4PIr7mWGEw8qT/LO0CeWFDXdcneS
AXng7utbTlPZpqVNOamymsbNUHZCAUHg2zn356CVtAab/yBT/1bH0m08mkgQ
nPo79zs/81QL+UnTEqnuiuAz9JruOqu2Sh0AEQEAAcLAXwQYAQgAEwUCVjIx
0gkQz3djkoG1R58CGwwAACLiB/4xuU+mcn4nhY6t0A5tGtSNSIZ7u/mBgUS1
Z7TNcGiuJp6lpw3yrBXDB7pbVDRFWRhVVrTZJVbZzcwxjFbrQ8K8ahFkP3Fc
IUA9j8HFz2iONvuj3OqcD6GnBgP4tmDDaddEGdjdykumshZ9WU14VJ62AOvr
X7J0P1erI9FGiyN9x/Cr8wdsI2GcRapF0HENXkKnFu0iSnQRWICJP2KBtNw3
v14lYNb1TSfV2D0TUTnq03kh5GjXpudUoQGBChEXouUa+IJUVTDQWRQ4rgd0
0EWkG+mMPwNRAaDSDOgXVSr8po1xiKsdf/Ewj47y5u8HcEVEG2E68o215lUH
qGyky3/L
=CpLW
-----END PGP PUBLIC KEY BLOCK-----'
		);

		$url = "/users/validateAccount/{$userId}.json";
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
			Status::SUCCESS,
			$json['header']['status'],
			"validateAccount /users/validateAccount/{$userId}.json : The test should return a success but is returning {$json['header']['status']}"
		);

		// Get user and check if deactivated.
		$gpkey = $this->Gpgkey->findByUserId($userId);
		$this->assertEquals($gpkey['Gpgkey']['key'], $dummyKey['key'], "After account validation the key was supposed to be set, but is not");
		$this->assertEquals($gpkey['Gpgkey']['bits'], 2048);
		$this->assertEquals($gpkey['Gpgkey']['uid'], 'ada lovelace <ada@passbolt.com>');
		$this->assertEquals($gpkey['Gpgkey']['type'], 'RSA');
		$this->assertEquals($gpkey['Gpgkey']['key_created'], '2015-10-29 14:48:41');
		$this->assertEquals($gpkey['Gpgkey']['fingerprint'], '051A166E300DAD845B255E37CF77639281B5479F');
		$this->assertEquals($gpkey['Gpgkey']['key_id'], '81B5479F');
	}

	/**
	 * Test account validation with a wrong user id.
	 */
	public function testAccountValidationWrongUserId() {
		$id = Common::uuid('not-valid-reference');
		$this->setExpectedException('HttpException', 'The user does not exist');
		$this->testAction(
			"/users/validateAccount/{$id}.json",
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

	/**
	 * Test account validation with a wrong token.
	 */
	public function testAccountValidationWrongToken() {
		$user = $this->User->findById(common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$user = $this->__createAccount('jean-gabin@gmail.com');
		$userId = $user['User']['id'];
		$this->User->setInactive();

		$this->setExpectedException('HttpException', 'Invalid token');
		$url = "/users/validateAccount/{$userId}.json";
		$this->testAction(
			$url,
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

	/**
	 * Test that the rollback works correctly in case of exception.
	 * basically test that the active fields in user and authenticationToken are back to their normal state.
	 */
	public function testAccountValidationExceptionRollback() {
		$user = $this->User->findById(common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$user = $this->__createAccount('jean-gabin@gmail.com');
		$userId = $user['User']['id'];
		$this->User->setInactive();

		$AuthenticationToken = Common::getModel('AuthenticationToken');
		$at = $AuthenticationToken->findByUserId($userId);

		// Dummy key taken from one generated by pgpjs.
		$dummyKey = array(
			'key' => 'wrongKey'
		);

		try {
			$url = "/users/validateAccount/{$userId}.json";
			$this->testAction(
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
		} catch(Exception $e) {
			// Assert that user is not active.
			$notActivedUser = $this->User->findById($userId);
			$this->assertEquals($notActivedUser['User']['active'], '0', 'Account validation : After exception, user should still be inactive but is not');

			// Assert that token is deactivated.
			$at = $AuthenticationToken->findById($at['AuthenticationToken']['id']);
			$this->assertEquals($at['AuthenticationToken']['active'], '1', 'Account validation : After exception, token should still be active but is not');
		}
	}

	/**
	 * Test that a user is logged out if his account is deactivated during his session.
	 */
	public function testUserIsLoggedOutIfAccountDeactivated() {
		$user = $this->User->findById(common::uuid('user.id.ada'));
		$this->User->setActive($user);

		$json = json_decode(
			$this->testAction(
				'/users.json',
				[
					'method' => 'get',
					'return' => 'contents',
				]
			),
			true
		);
		$this->assertEquals($json['header']['status'], Status::SUCCESS);

		// Set active to zero.
		$this->User->id = $user['User']['id'];
		$this->User->save(['active' => false], false);

		// Try to perform the same query on resources and observe I am logged out automatically.
		$this->setExpectedException('HttpException', 'You need to login to access this location');
		$this->testAction(
			'/users.json',
			[
				'method' => 'get',
				'return' => 'contents',
			]
		);
	}

	/**
	 * Test that a user is logged out if his account is softdeleted during his session.
	 */
	public function testUserIsLoggedOutIfAccountDeleted() {
		$user = $this->User->findById(common::uuid('user.id.ada'));
		$this->User->setActive($user);

		$json = json_decode(
			$this->testAction(
				'/users.json',
				[
					'method' => 'get',
					'return' => 'contents',
				]
			),
			true
		);
		$this->assertEquals($json['header']['status'], Status::SUCCESS);

		// Set active to zero.
		$this->User->id = $user['User']['id'];
		$this->User->save(['deleted' => true], false);

		// Try to perform the same query on resources and observe I am logged out automatically.
		$this->setExpectedException('HttpException', 'You need to login to access this location');
		$this->testAction(
			'/users.json',
			[
				'method' => 'get',
				'return' => 'contents',
			]
		);
	}

	/**
	 * Test that the user is logged out if account is physically deleted.
	 */
	public function testUserIsLoggedOutIfAccountPhysicallyDeleted() {
		$user = $this->User->findById(common::uuid('user.id.ada'));
		$this->User->setActive($user);

		// Empty database and see if user is automatically logged out.
		$this->User->deleteAll(array('User.id' => $user['User']['id']));
		$this->setExpectedException('HttpException', 'You need to login to access this location');
		$this->testAction('/users.json', array('return' => 'contents', 'method' => 'GET'), true);
	}
}
