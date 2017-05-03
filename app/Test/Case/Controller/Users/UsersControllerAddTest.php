<?php
/**
 * Users Add Controller Tests
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

class UsersControllerAddTest extends ControllerTestCase {

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
 * Create a dummy account using a call to the controller.
 *
 * @param string $username requested.
 * @return array user data
 */
	private function __createAccount($username) {
		$userAdd = $this->testAction(
			'/users.json',
			array(
				'data' => array(
					'User' => array(
						'username' => $username,
						'role_id' => Common::uuid('role.id.user')
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
 * Test add for a non admin user.
 *
 * @return void
 */
	public function testAddNoAllowed() {
		// normal user don't have the right to add user
		$user = $this->User->findById(Common::uuid('user.id.user'));
		$this->User->setActive($user);

		$this->setExpectedException('ForbiddenException', 'You are not authorized to access that location.');
		json_decode(
			$this->testAction(
				'/users.json',
				array(
					'data' => array(
						'User' => array(
							'username' => 'testadd1@passbolt.com',
							'role_id' => Common::uuid('role.id.user'),
							'active' => 1
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
 * Test add without user information.
 *
 * @return void
 */
	public function testAddWithoutUserInfo() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$this->setExpectedException('BadRequestException', 'No user data was provided.');
		json_decode(
			$this->testAction(
				'/users.json',
				array(
					'data' => array(
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
 *
 * @return void
 */
	public function testAddWithoutProfileInfo() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$this->setExpectedException('HttpException', 'Profile data are missing');
		json_decode(
			$this->testAction(
				'/users.json',
				array(
					'data' => array(
						'User' => array(
							'username' => 'testprofile@passbolt.com',
							'role_id' => Common::uuid('role.id.user'),
							'active' => 1
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
 *
 * @return void
 */
	public function testAddWithoutRoleId() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$result = json_decode(
			$this->testAction(
				'/users.json',
				array(
					'data' => array(
						'User' => array(
							'username' => 'testaddnoroleid@passbolt.com',
							'active' => 1
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
 * Test adding an existing username for a undeleted user.
 * This should generate an exception.
 *
 * @return void
 */
	public function testAddExistingUser() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);
		$this->setExpectedException('ValidationException', 'Could not validate user profile data.');
		$this->testAction(
			'/users.json',
			array(
				'data' => array(
					'User' => array(
						'username' => 'ada@passbolt.com',
						'role_id' => Common::uuid('role.id.user'),
						'active' => 1
					),
					'Profile' => array(
						'first_name' => 'ada',
						'last_name' => 'ada'
					)
				),
				'method' => 'post',
				'return' => 'contents'
			)
		);
	}

/**
 * Test adding an existing username that belongs to a deleted user.
 *
 * @return void
 */
	public function testAddDeletedExistingUser() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		// Soft delete ada.
		$this->User->id = Common::uuid('user.id.ada');
		$this->User->saveField('deleted', true);

		// Recreate another user with username ada@passbolt.com.
		$result = json_decode(
			$this->testAction(
				'/users.json',
				array(
					'data' => array(
						'User' => array(
							'username' => 'ada@passbolt.com',
							'role_id' => Common::uuid('role.id.user'),
							'active' => 1
						),
						'Profile' => array(
							'first_name' => 'ada',
							'last_name' => 'ada'
						)
					),
					'method' => 'post',
					'return' => 'contents'
				)
			),
			true
		);

		// Assert that the request was a success.
		$this->assertEquals(
			Status::SUCCESS,
			$result['header']['status'],
			"Add : /users.json : The test should return sucess but is returning " . print_r($result, true)
		);

		// Assert that we now have 2 users with username ada@passbolt.com in the database.
		$user = $this->User->find('all', ['conditions' => ['username' => "ada@passbolt.com"]]);
		$this->assertEquals(
			2,
			count($user),
			"Add : /users.json : The number of users returned should be 1, but actually is " . count($user)
		);
	}

/**
 * Test add for a logged in admin.
 *
 * @return void
 */
	public function testAdd() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$result = json_decode(
			$this->testAction(
				'/users.json',
				array(
					'data' => array(
						'User' => array(
							'username' => 'testadd1@passbolt.com',
							'role_id' => Common::uuid('role.id.user'),
							'active' => 1
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
		$this->assertEquals(
			Common::uuid('user.id.admin'),
			$result['body']['User']['created_by'],
			"Add : /users.json : created by should be Admin id (" . Common::uuid('user.id.admin') . ") but is id: " . $result['body']['User']['created_by']
		);
		$this->assertEquals(
			Common::uuid('user.id.admin'),
			$result['body']['User']['modified_by'],
			"Add : /users.json : modified by should be Admin id (" . Common::uuid('user.id.admin') . ") but is id: " . $result['body']['User']['modified_by']
		);
	}

/**
 * Test account creation and token.
 *
 * @return void
 */
	public function testAccountCreateAndToken() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
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

}
