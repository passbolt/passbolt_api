<?php
/**
 * Users Controller Edit Tests
 *
 * @copyright (c) 2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('AppController', 'Controller');
App::uses('UsersController', 'Controller');
App::uses('User', 'Model');
App::uses('Group', 'Model');
App::uses('Role', 'Model');
App::uses('CakeSession', 'Model');
App::uses('CakeSession', 'Model/Datasource');
App::uses('CakeSessionFixture', 'Test/Fixture');

class UsersControllerEditTest extends ControllerTestCase {

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
		'core.cakeSession',
		'app.user_agent',
		'app.controller_log',
		'app.resource',
		'app.permission',
		'app.permissions_type',
		'app.permission_view',
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
 * Test update by a non admin user.
 *
 * @return void
 */
	public function testUpdateNoAllowed() {
		// normal user don't have the right to add user
		$user = $this->User->findById(Common::uuid('user.id.dame'));
		$this->User->setActive($user);

		// the user to update
		$id = Common::uuid('user.id.user');

		$this->setExpectedException('HttpException', 'You are not authorized to access that location');
		json_decode(
			$this->testAction(
				"/users/$id.json",
				array(
					'data' => array(
						'User' => array(
							'id' => $user['User']['id'],
							'username' => 'user-modified@passbolt.com',
							'role_id' => Common::uuid('role.id.user'),
							'active' => 1
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
 *
 * @return void
 */
	public function testUpdateUserIdIsMissing() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);
		$this->setExpectedException('HttpException', 'The user id is missing.');
		$this->testAction('/users.json', array('return' => 'contents', 'method' => 'put'), true);
	}

/**
 * Test update with a wrong user id.
 *
 * @return void
 */
	public function testUpdateUserIdNotValid() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);
		$this->setExpectedException('HttpException', 'The user id is not valid.');
		$this->testAction('/users/badId.json', array('return' => 'contents', 'method' => 'put'), true);
	}

/**
 * Test update with no data provided.
 *
 * @return void
 */
	public function testUpdateNoDataProvided() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		// the user to update
		$id = Common::uuid('user.id.user');

		$this->setExpectedException('HttpException', 'No user data was provided.');
		$this->testAction(
			"/users/$id.json",
			array('method' => 'put', 'return' => 'contents')
		);
	}

/**
 * Test update with a non existing user.
 *
 * @return void
 */
	public function testUpdateUserDoesNotExist() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$id = Common::uuid('not-valid-reference');
		$data['User']['username'] = 'user@34@passbolt.com';
		$this->setExpectedException('HttpException', 'The user does not exist');
		$this->testAction(
			"/users/{$id}.json",
			array('return' => 'contents', 'method' => 'put', 'data' => $data),
			true
		);
	}

/**
 * Test update with providing an invalid username.
 *
 * @return void
 */
	public function testUpdateUsernameNotValid() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		// the user to update
		$id = Common::uuid('user.id.user');
		$data['User']['username'] = 'user@34@passbolt.com';

		$this->setExpectedException('ValidationException', 'Could not validate User');
		$this->testAction(
			"/users/$id.json",
			array(
				'data' => $data,
				'method' => 'put',
				'return' => 'contents'
			)
		);
	}

/**
 * Test update as an admin.
 *
 * @return void
 */
	public function testUpdateAsAdmin() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		// the user to update
		$id = Common::uuid('user.id.user');

		// update the user by changing its role
		$roleId = Common::uuid('role.id.admin');
		$data['User']['role_id'] = $roleId;
		$resRaw = $this->testAction(
			"/users/$id.json",
			array(
				'data' => $data,
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
 *
 * @return void
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
				'data' => $data,
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
 *
 * @return void
 */
	public function testUpdateAdminCanUpdateSomebodysRoleToAdmin() {
		// normal user don't have the right to add user
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		// the user to update
		$id = Common::uuid('user.id.dame');
		$adminRoleId = Common::uuid('role.id.admin');

		$resRaw = $this->testAction(
			"/users/$id.json",
			array(
				'data' => array(
					'User' => array(
						'id' => $id,
						'role_id' => $adminRoleId,
					),
				),
				'method' => 'put',
				'return' => 'contents'
			));
		json_decode($resRaw, true);

		$user = $this->User->findById(Common::uuid('user.id.dame'));
		$this->assertEquals(
			$user['User']['role_id'],
			$adminRoleId,
			"Edit : /users.json : After update the role of dame@passbolt.com should be admin, but is not"
		);
	}

/**
 * Test that an admin cannot update his own role.
 *
 * @return void
 */
	public function testUpdateAdminCantUpdateOwnRole() {
		$id = Common::uuid('user.id.admin');
		$user = $this->User->findById($id);
		$this->User->setActive($user);

		$userRoleId = Common::uuid('role.id.user');

		$this->testAction(
			"/users/$id.json",
			array(
				'data' => array(
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
 *
 * @return void
 */
	public function testUpdateAdminCantSetOwnInactive() {
		// the user to update
		$id = Common::uuid('user.id.admin');
		$user = $this->User->findById($id);
		$this->User->setActive($user);

		$this->testAction(
			"/users/$id.json",
			array(
				'data' => array(
					'User' => array(
						'id' => $id,
						'active' => 0,
					),
				),
				'method' => 'put',
				'return' => 'contents'
			));

		$user = $this->User->findById($id);
		$this->assertEquals($user['User']['active'], 1, 'After update the active field should still be set to 1');
	}

/**
 * Test that normal user cannot set himself as non active.
 *
 * @return void
 */
	public function testUpdateNonAdminCantSetOwnInactive() {
		// normal user don't have the right to add user
		$id = Common::uuid('user.id.dame');
		$user = $this->User->findById($id);
		$this->User->setActive($user);

		$this->testAction(
			"/users/$id.json",
			array(
				'data' => array(
					'User' => array(
						'id' => $id,
						'active' => 0,
					),
				),
				'method' => 'put',
				'return' => 'contents'
			));

		$admin = $this->User->findById($id);
		$this->assertEquals($admin['User']['active'], 1, 'After update the active field should still set to 1');
	}
}
