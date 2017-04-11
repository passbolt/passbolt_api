<?php
/**
 * Users Controller Tests
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
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

/******************************************************
 * INDEX TESTS
 ******************************************************/

/**
 * Test a call to index without being logged in.
 *
 * @return void
 */
	public function testIndexNoAllowed() {
		$this->setExpectedException('HttpException', 'You need to login to access this location');
		// test with anonymous user
		$this->testAction('/users.json', array('return' => 'contents', 'method' => 'GET'), true);
	}

/**
 * Test a call to index After logging in.
 *
 * @return void
 */
	public function testIndex() {
		// test with normal user
		$user = $this->User->findById(Common::uuid('user.id.user'));
		$this->User->setActive($user);

		$result = json_decode($this->testAction('/users.json', array('return' => 'contents', 'method' => 'GET'), true));
		$this->assertEquals($result->header->status, Status::SUCCESS, '/users return something');
	}

/**
 * Test a call to index filtered by groups
 *
 * @return void
 */
	public function testIndexFilterWithGroups() {
		// test with normal user
		$user = $this->User->findById(Common::uuid('user.id.user'));
		$this->User->setActive($user);

		// Filter with one group
		$data = array(
			'filter' => ['has-groups' => Common::uuid('group.id.accounting')],
		);
		$result = json_decode($this->testAction('/users.json', array('return' => 'contents', 'method' => 'GET', 'data' => $data)), true);
		$this->assertEquals($result['header']['status'], Status::SUCCESS, '/users return something');
		$this->assertEquals(2, count($result['body']));
		$resultUsers = Hash::extract($result['body'], '{n}.User.id');
		$this->assertContains(Common::uuid('user.id.frances'), $resultUsers);
		$this->assertContains(Common::uuid('user.id.grace'), $resultUsers);

		// Filter with multiple groups but no one in all
		$data = array(
			'filter' => ['has-groups' => Common::uuid('group.id.creative') . ',' . Common::uuid('group.id.administration')],
		);
		$result = json_decode($this->testAction('/users.json', array('return' => 'contents', 'method' => 'GET', 'data' => $data)), true);
		$this->assertEquals($result['header']['status'], Status::SUCCESS, '/users return something');
		$this->assertEquals(0, count($result['body']));

		// Filter with multiple groups with one user in all of each
		$data = array(
			'filter' => ['has-groups' => Common::uuid('group.id.creative') . ',' . Common::uuid('group.id.ergonom')],
		);
		$result = json_decode($this->testAction('/users.json', array('return' => 'contents', 'method' => 'GET', 'data' => $data)), true);
		$this->assertEquals($result['header']['status'], Status::SUCCESS, '/users return something');
		$this->assertEquals(1, count($result['body']));
		$resultUsers = Hash::extract($result['body'], '{n}.User.id');
		$this->assertContains(Common::uuid('user.id.irene'), $resultUsers);
	}

/**
 * Test a call to index filtered by keywords
 *
 * @return void
 */
	public function testIndexFilteredByKeywords() {
		// test with normal user
		$user = $this->User->findById(Common::uuid('user.id.user'));
		$this->User->setActive($user);

		$data = array(
			'filter' => [
				'keywords' => 'Betty'
			]
		);
		$result = json_decode($this->testAction('/users.json', array('return' => 'contents', 'method' => 'GET', 'data' => $data), true));
		$this->assertEquals($result->header->status, Status::SUCCESS, '/users return something');
		$this->assertNotEmpty($result->body);
		$this->assertEqual(count($result->body), 1);
		$this->assertEquals($result->body[0]->User->username, 'betty@passbolt.com');
	}

/******************************************************
 * View TESTS
 ******************************************************/

/**
 * Test view function when not logged in.
 *
 * @return void
 */
	public function testViewNoAllowed() {
		$this->setExpectedException('HttpException', 'You need to login to access this location');
		// test with anonymous user
		$this->testAction(
			'/users/' . Common::uuid('user.id.user') . '.json',
			array('return' => 'contents', 'method' => 'GET'),
			true
		);
	}

/**
 * Test view function with a missing user id
 *
 * @return void
 */
	public function testViewUserIdIsMissing() {
		// Unable to test missing id param because of route
	}

/**
 * Test view function with an invalid user id.
 *
 * @return void
 */
	public function testViewUserIdNotValid() {
		// test with normal user
		$user = $this->User->findById(Common::uuid('user.id.user'));
		$this->User->setActive($user);

		$this->setExpectedException('HttpException', 'The user id is invalid');
		$this->testAction('/users/badId.json', array('return' => 'contents', 'method' => 'GET'), true);
	}

/**
 * Test view with a non existing user.
 *
 * @return void
 */
	public function testViewUserDoesNotExist() {
		// test with normal user
		$user = $this->User->findById(Common::uuid('user.id.user'));
		$this->User->setActive($user);

		$id = Common::uuid('not-valid-reference');
		$this->setExpectedException('HttpException', 'The user does not exist');
		$this->testAction( "/users/{$id}.json", array('return' => 'contents', 'method' => 'GET'), true);
	}

/**
 * Test view current user
 *
 * @return void
 */
	public function testViewCurrentUser() {
		// test with normal user
		$user = $this->User->findById(Common::uuid('user.id.user'));
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
 * Test that the field last_logged_in is present if the user has already logged in.
 *
 * @return void
 */
	public function testViewLastLoggedIn() {
		$ControllerLog = Common::getModel('ControllerLog');
		$user = $this->User->findById(Common::uuid('user.id.user'));
		$this->User->setActive($user);

		// Call user view for ada.
		$result = json_decode(
			$this->testAction(
				'/users/' . Common::uuid('user.id.ada') . '.json',
				array('return' => 'contents', 'method' => 'GET'),
				true
			)
		);

		// Assert that last logged is empty (since never logged in).
		$this->assertEmpty($result->body->User->last_logged_in);

		// Create a login log entry in controller log.
		$ControllerLog->create();
		$ControllerLog->save([
				'user_id' => Common::uuid('user.id.ada'),
				'role_id' => Role::USER,
				'level' => 'success',
				'method' => 'POST',
				'controller' => 'auth',
				'action' => 'login',
				'user_agent_id' => '7762a026-0f79-3982-abe3-d3d488119d28',
				'ip' => '127.0.0.1',
				'request_data' => null,
				'message' => 'login_success',
				'scope' => '',
			], false);

		// Call again user view for ada.
		$result = json_decode(
			$this->testAction(
				'/users/' . Common::uuid('user.id.ada') . '.json',
				array('return' => 'contents', 'method' => 'GET'),
				true
			)
		);

		// Assert that last_logged_in is not empty anymore.
		$this->assertNotEmpty($result->body->User->last_logged_in);
	}

/**
 * Test view in a normal scenario.
 *
 * @return void
 */
	public function testView() {
		// test with normal user
		$user = $this->User->findById(Common::uuid('user.id.user'));
		$this->User->setActive($user);

		$result = json_decode(
			$this->testAction(
				'/users/' . Common::uuid('user.id.ada') . '.json',
				array('return' => 'contents', 'method' => 'GET'),
				true
			)
		);
		$this->assertEquals($result->header->status, Status::SUCCESS, '/users return something');
		$this->assertNotEmpty($result->body);
		$this->assertEquals($result->body->User->username, 'ada@passbolt.com');
	}

/******************************************************
 * ADD TESTS
 ******************************************************/

/**
 * Test add for a non admin user.
 *
 * @return void
 */
	public function testAddNoAllowed() {
		// normal user don't have the right to add user
		$user = $this->User->findById(Common::uuid('user.id.user'));
		$this->User->setActive($user);

		$this->setExpectedException('HttpException', 'You are not authorized to access that location');
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

		$this->setExpectedException('HttpException', 'Could not validate profile');

		// Assert that adding an undeleted existing user will return an exception.
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

/******************************************************
 * REGISTER TESTS
 ******************************************************/

/**
 * Test registration with GET
 *
 * @return void
 */
	public function testRegisterGET() {
		$data = array(
			'Profile' => array(
				'first_name' => 'Mary',
				'last_name' => 'Anning'
			),
			'User' => array(
				'username' => 'mary.anning@passbolt.com'
			)
		);
		$view = $this->testAction('/register', array('data' => $data, 'method' => 'get', 'return' => 'view'));
		$this->assertContains('Please enter your username and password', $view);
	}

/**
 * Test registration with POST and empty data
 *
 * @return void
 */
	public function testRegisterNoData() {
		$data = array();
		$view = $this->testAction('/register', array('data' => $data, 'method' => 'get', 'return' => 'view'));
		$this->assertContains('Please enter your username and password', $view);
	}

/**
 * Test registration with invalid email
 *
 * @return void
 */
	public function testRegisterInvalidUsername() {
		$data = array(
			'Profile' => array(
				'first_name' => 'z',
				'last_name' => 'z'
			),
			'User' => array(
				'username' => 'bogus'
			)
		);
		$view = $this->testAction('/register', array('data' => $data, 'method' => 'post', 'return' => 'view'));
		$this->assertContains('The username should be a valid email address', $view);
	}

/**
 * Test registration with invalid firstname / lastname
 *
 * @return void
 */
	public function testRegisterInvalidNames() {
		$data = array(
			'Profile' => array(
				'first_name' => 'z',
				'last_name' => '>>>'
			),
			'User' => array(
				'username' => 'testRegisterInvalidNames@passbolt.com'
			)
		);
		$view = $this->testAction('/register', array('data' => $data, 'method' => 'post', 'return' => 'view'));
		$this->assertContains('First name should be between', $view);
		$this->assertContains('Last name should only contain', $view);
	}

/**
 * Test registration with already registered user
 *
 * @return void
 */
	public function testRegisterExistingUser() {
		$data = array(
			'Profile' => array(
				'first_name' => 'Ada',
				'last_name' => 'Lovelace'
			),
			'User' => array(
				'username' => 'ada@passbolt.com'
			)
		);
		$view = $this->testAction('/register', array('data' => $data, 'method' => 'post', 'return' => 'view'));
		$this->assertContains('The username has already been taken', $view);
	}

/**
 * Test registration validation error in JSON request context
 * It should thrown an exception
 *
 * @return void
 */
	public function testRegisterValidationErrorJSON() {
		$data = array(
			'Profile' => array(
				'first_name' => 'Ada',
				'last_name' => 'Lovelace'
			),
			'User' => array(
				'username' => '@passbolt.com'
			)
		);
		$this->setExpectedException('ValidationException', 'Could not validate user data');
		$this->testAction('/register.json', array('data' => $data, 'method' => 'post', 'return' => 'content'));
	}

/**
 * Test registration thank you redirect
 *
 * @return void
 */
	public function testRegisterThankYouRedirect() {
		$data = array(
			'Profile' => array(
				'first_name' => 'Mary',
				'last_name' => 'Anning'
			),
			'User' => array(
				'username' => 'mary.anning@passbolt.com'
			)
		);
		$this->testAction('/register', array('data' => $data, 'method' => 'post'));
		$this->assertContains('/register/thankyou', $this->headers['Location']);
	}

/**
 * Test registration thank you without referer
 *
 * @return void
 */
	public function testRegisterThankYouNoReferer() {
		$this->testAction('/register/thankyou', array('method' => 'get'));
		$this->assertContains('/register', $this->headers['Location']);
	}

/******************************************************
 * UPDATE TESTS
 ******************************************************/

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

		$this->setExpectedException('HttpException', 'The user id is missing');
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

		$this->setExpectedException('HttpException', 'The user id is invalid');
		$this->testAction('/users/badId.json', array('return' => 'contents', 'method' => 'put'), true);
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
		$this->setExpectedException('HttpException', 'The user does not exist');
		$this->testAction(
			"/users/{$id}.json",
			array('return' => 'contents', 'method' => 'put'),
			true
		);
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

		$this->setExpectedException('HttpException', 'No data were provided');
		$this->testAction(
			"/users/$id.json",
			array('method' => 'put', 'return' => 'contents')
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

		$this->setExpectedException('HttpException', 'Could not validate User');
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

/**
 * Test update avatar by a non allowed user.
 *
 * @return void
 */
	public function testUpdateAvatarNoAllowed() {
		$user = $this->User->findById(Common::uuid('user.id.dame'));
		$this->User->setActive($user);

		$id = Common::uuid('user.id.user');

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
					'data' => array(),
					'method' => 'post',
					'return' => 'contents'
				)
			),
			true
		);
	}

/**
 * Test update avatar when user id is missing.
 *
 * @return void
 */
	public function testUpdateAvatarUserIdIsMissing() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$this->setExpectedException('HttpException', 'The user id is missing');
		$this->testAction('/users/avatar.json', array('return' => 'contents', 'method' => 'post'), true);
	}

/**
 * Test update avatar user id not valid.
 *
 * @return void
 */
	public function testUpdateAvatarUserIdNotValid() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$this->setExpectedException('HttpException', 'The user id is invalid');
		$this->testAction('/users/avatar/badId.json', array('return' => 'contents', 'method' => 'post'), true);
	}

/**
 * Test updateAvatar when the user does not exist.
 *
 * @return void
 */
	public function testUpdateAvatarUserDoesNotExist() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
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
 *
 * @return void
 */
	public function testUpdateAvatarNoDataProvided() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$id = Common::uuid('user.id.user');
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
 *
 * @return void
 */
	public function testUpdateAvatar() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$id = Common::uuid('user.id.user');
		$findConditions = array('User.id' => $id);
		$options = $this->User->getFindOptions('User::view', User::get('Role.name'), $findConditions);
		$user = $this->User->find('first', $options);

		// Get empty image url.
		$defaults = Configure::read('Media.imageDefaults.ProfileAvatar');
		$diff = Set::diff($user['Profile']['Avatar']['url'], $defaults);

		$this->assertEmpty($diff, "The user " . $user['User']['username'] . " should have the default avatar");

		$_FILES['file-0'] = array(
			'tmp_name' => APP . 'Test' . DS . 'Data' . DS . 'img' . DS . 'user.png'
		);
		$result = json_decode(
			$this->testAction(
				"/users/avatar/$id.json",
				array(
					'data' => array(),
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

/******************************************************
 * DELETE TESTS
 ******************************************************/

/**
 * Test delete for non admin user.
 *
 * @return void
 */
	public function testDeleteNoAllowed() {
		$id = Common::uuid('user.id.user');
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
 *
 * @return void
 */
	public function testDeleteUserIdIsMissing() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$this->setExpectedException('HttpException', 'The user id is missing');
		$this->testAction('/users.json', array('return' => 'contents', 'method' => 'delete'), true);
	}

/**
 * Test delete with an invalid user id.
 *
 * @return void
 */
	public function testDeleteUserIdNotValid() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$this->setExpectedException('HttpException', 'The user id is invalid');
		$this->testAction('/users/badId.json', array('return' => 'contents', 'method' => 'delete'), true);
	}

/**
 * Test delete yourself.
 *
 * @return void
 */
	public function testDeleteSelfNotAllowed() {
		$id = Common::uuid('user.id.admin');
		$user = $this->User->findById($id);
		$this->User->setActive($user);

		$this->setExpectedException('HttpException', 'You are not allowed to delete yourself');
		$this->testAction("/users/{$id}.json", array('return' => 'contents', 'method' => 'delete'), true);
	}

/**
 * Test delete for a non existing user.
 *
 * @return void
 */
	public function testDeleteUserDoesNotExist() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
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
 *
 * @return void
 */
	public function testDelete() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		// the user to delete
		$userId = Common::uuid('user.id.user');
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
 * Test retrieve see user in index that has been previously soft deleted.
 *
 * @return void
 */
	public function testDeleteAndIndex() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		// the user to delete
		$userId = Common::uuid('user.id.user');
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

		// Try to get all users
		$result = json_decode($this->testAction('/users.json', array('return' => 'contents', 'method' => 'GET')), true);
		$usersIds = Set::extract($result['body'], '{n}.User.id');

		// The user that has been deleted is not in the list of user
		$this->assertFalse(in_array($userId, $usersIds));
	}

/**
 * Test can't retrieve user that has been previously soft deleted.
 *
 * @return void
 */
	public function testDeleteAndView() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		// the user to delete
		$userId = Common::uuid('user.id.user');
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

		// Try to retrieve the user details
		$this->setExpectedException('HttpException', 'The user does not exist');
		$result = json_decode(
			$this->testAction(
				"/users/{$userId}.json",
				array('return' => 'contents', 'method' => 'GET'),
				true
			)
		);
	}

/**
 * Test can't update user that has been previously soft deleted.
 *
 * @return void
 */
	public function testDeleteAndUpdate() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		// the user to delete
		$userId = Common::uuid('user.id.user');
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

		// Try to update a user that has been deleted
		$this->setExpectedException('HttpException', 'The user does not exist');
		$data['Profile']['first_name'] = 'Update user name';
		$this->testAction(
			"/users/{$userId}.json",
			array(
				'data' => $data,
				'method' => 'put',
				'return' => 'contents'
			)
		);
	}

/**
 * Test can't update avatar user that has been previously soft deleted.
 *
 * @return void
 */
	public function testDeleteAndUpdateAvatar() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		// the user to delete
		$userId = Common::uuid('user.id.user');
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

		// Try to update a user that has been deleted
		$this->setExpectedException('HttpException', 'The user does not exist');
		$this->testAction(
			"/users/avatar/{$userId}.json",
			array(
				'data' => [],
				'method' => 'post',
				'return' => 'contents'
			)
		);
	}

/**
 * Test can't delete user that has been previously soft deleted.
 *
 * @return void
 */
	public function testDeleteAndDelete() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		// the user to delete
		$userId = Common::uuid('user.id.user');
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

		// Try to delete a user that has been deleted
		$this->setExpectedException('HttpException', 'The user does not exist');
		$this->testAction(
			"/users/{$userId}.json",
			array(
				'method' => 'delete',
				'return' => 'contents'
			)
		);
	}

/******************************************************
 * VALIDATE ACCOUNT TESTS
 ******************************************************/

/**
 * Test account validation when user id is missing.
 *
 * @return void
 */
	public function testAccountValidationUserIdIsMissing() {
		$this->User->setInactive();
		$this->setExpectedException('HttpException', 'The user id is missing');
		$this->testAction('/users/validateAccount.json', array('return' => 'contents', 'method' => 'put'), true);
	}

/**
 * Test account validation user id not valid.
 *
 * @return void
 */
	public function testAccountValidationUserIdNotValid() {
		$this->User->setInactive();
		$this->setExpectedException('HttpException', 'The user id is invalid');
		$this->testAction('/users/validateAccount/badId.json', array('return' => 'contents', 'method' => 'put'), true);
	}

/**
 * Test account validation when the user does not exist.
 *
 * @return void
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
 *
 * @return void
 */
	public function testAccountValidationNoDataProvided() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
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
 *
 * @return void
 */
	public function testAccountValidationInvalidToken() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$user = $this->__createAccount('jean-gabin@gmail.com');
		$userId = $user['User']['id'];
			$this->User->setInactive();

		$this->setExpectedException('HttpException', 'Invalid token');
		$url = "/users/validateAccount/{$userId}.json";
		$this->testAction($url, array(
			'data' => array (
				'AuthenticationToken' => array (
					'token' => Common::uuid(),
				),
			),
			'method' => 'put',
			'return' => 'contents'
		));
	}

/**
 * Test account validation when the authentication token is expired.
 *
 * @return void
 */
	public function testAccountValidationExpiredToken() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$user = $this->__createAccount('jean-gabin@gmail.com');
		$userId = $user['User']['id'];
		$this->User->setInactive();

		$AuthenticationToken = Common::getModel('AuthenticationToken');
		$at = $AuthenticationToken->findByUserId($user['User']['id']);

		// Reduce the token expiracy date to 1 second.
		Configure::write('Auth.tokenExpiracy', 0.016);
		sleep(1);

		$this->setExpectedException('HttpException', 'Expired token');
		$url = "/users/validateAccount/{$userId}.json";
		$this->testAction($url, array(
			'data' => array (
				'AuthenticationToken' => array (
					'token' => $at['AuthenticationToken']['token'],
				),
			),
			'method' => 'put',
			'return' => 'contents'
		));
	}

/**
 * Test account validation.
 *
 * @return void
 */
	public function testAccountValidation() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$user = $this->__createAccount('jean-gabin@gmail.com');
		$userId = $user['User']['id'];
			$this->User->setInactive();

		$AuthenticationToken = Common::getModel('AuthenticationToken');
		$at = $AuthenticationToken->findByUserId($user['User']['id']);

		$url = "/users/validateAccount/{$userId}.json";
		$validate = $this->testAction($url, array(
			'data' => array (
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
		$this->assertEquals($deactivatedUser['User']['active'], 1, 'The user should be activated after account validation, but is not');

		// Check Authentication Token is not active anymore.
		$at = $AuthenticationToken->findByUserId($userId);
		$this->assertEquals($at['AuthenticationToken']['active'], 0, 'The authentication token should be deactivated after account activation, but it is not');
	}

/**
 * Test account validation with profile edition.
 *
 * @return void
 */
	public function testAccountValidationWithProfileEdit() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$user = $this->__createAccount('jean-gabin@gmail.com');
		$userId = $user['User']['id'];
			$this->User->setInactive();

		$AuthenticationToken = Common::getModel('AuthenticationToken');
		$at = $AuthenticationToken->findByUserId($userId);

		$url = "/users/validateAccount/{$userId}.json";
		$validate = $this->testAction($url, array(
			'data' => array (
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
 *
 * @return void
 */
	public function testAccountValidationWithGpgkeyEdit() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
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
				'data' => array (
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
		$this->assertEquals($gpkey['Gpgkey']['uid'], 'ada lovelace &lt;ada@passbolt.com&gt;');
		$this->assertEquals($gpkey['Gpgkey']['type'], 'RSA');
		$this->assertEquals($gpkey['Gpgkey']['key_created'], '2015-10-29 14:48:41');
		$this->assertEquals($gpkey['Gpgkey']['fingerprint'], '051A166E300DAD845B255E37CF77639281B5479F');
		$this->assertEquals($gpkey['Gpgkey']['key_id'], '81B5479F');
	}

/**
 * Test account validation with a non unique Gpgkey.
 *
 * @return void
 */
	public function testAccountValidationWithNonUniqueGpgkey() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$user = $this->__createAccount('jean-gabin@gmail.com');
		$userId = $user['User']['id'];
		$this->User->setInactive();

		$AuthenticationToken = Common::getModel('AuthenticationToken');
		$at = $AuthenticationToken->findByUserId($userId);

		// Dummy key taken from one generated by pgpjs.

		// Get the previously used key
		$gpgkeyPath = Configure::read('GPG.testKeys.path');
		$adaPrivateKey = file_get_contents($gpgkeyPath . 'ada_public.key');
		$dummyKey = array(
			'key' => $adaPrivateKey
		);

		$this->setExpectedException('HttpException', 'Could not validate gpgkey data');
		$url = "/users/validateAccount/{$userId}.json";
		$this->testAction(
			$url,
			array(
				'data' => array (
					'AuthenticationToken' => array (
						'token' => $at['AuthenticationToken']['token'],
					),
					'Gpgkey' => $dummyKey
				),
				'method' => 'put',
				'return' => 'contents'
			)
		);
	}

/**
 * Test account validation with a non unique Gpgkey belonging to a deleted user.
 *
 * @return void
 */
	public function testAccountValidationWithNonUniqueGpgkeyBelongingToDeletedUser() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$user = $this->__createAccount('jean-gabin@gmail.com');
		$userId = $user['User']['id'];
		$this->User->setInactive();

		// Soft delete ada.
		$ada = $this->User->findByUsername('ada@passbolt.com');
		$this->User->id = $ada['User']['id'];
		$this->User->saveField('deleted', 1);

		$AuthenticationToken = Common::getModel('AuthenticationToken');
		$at = $AuthenticationToken->findByUserId($userId);

		// Get the previously used key
		$gpgkeyPath = Configure::read('GPG.testKeys.path');
		$key = file_get_contents($gpgkeyPath . 'ada_public.key');
		$dummyKey = array(
			'key' => $key
		);

		// Execute the action. We don't expect any exception.
		$url = "/users/validateAccount/{$userId}.json";
		$validate = $this->testAction(
			$url,
			array(
				'data' => array (
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
	}

/**
 * Test account validation with a wrong user id.
 *
 * @return void
 */
	public function testAccountValidationWrongUserId() {
		$id = Common::uuid('not-valid-reference');
		$this->setExpectedException('HttpException', 'The user does not exist');
		$this->testAction(
			"/users/validateAccount/{$id}.json",
			array(
				'data' => array (
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
 *
 * @return void
 */
	public function testAccountValidationWrongToken() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$user = $this->__createAccount('jean-gabin@gmail.com');
		$userId = $user['User']['id'];
		$this->User->setInactive();

		$this->setExpectedException('HttpException', 'Invalid token');
		$url = "/users/validateAccount/{$userId}.json";
		$this->testAction(
			$url,
			array(
				'data' => array (
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
 *
 * @return void
 */
	public function testAccountValidationExceptionRollback() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
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
					'data' => array (
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
			$this->assertEquals($notActivedUser['User']['active'], 0, 'Account validation : After exception, user should still be inactive but is not');

			// Assert that token is deactivated.
			$at = $AuthenticationToken->findById($at['AuthenticationToken']['id']);
			$this->assertEquals($at['AuthenticationToken']['active'], 1, 'Account validation : After exception, token should still be active but is not');
		}
	}

/******************************************************
 * MISC TESTS
 ******************************************************/

/**
 * Test that a user is logged out if his account is deactivated during his session.
 *
 * @return void
 */
	public function testUserIsLoggedOutIfAccountDeactivated() {
		$user = $this->User->findById(Common::uuid('user.id.ada'));
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
		$this->User->save(['active' => 0], false);

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
 *
 * @return void
 */
	public function testUserIsLoggedOutIfAccountDeleted() {
		$user = $this->User->findById(Common::uuid('user.id.ada'));
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
		$this->User->save(['deleted' => 1], false);

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
 *
 * @return void
 */
	public function testUserIsLoggedOutIfAccountPhysicallyDeleted() {
		$user = $this->User->findById(Common::uuid('user.id.ada'));
		$this->User->setActive($user);

		// Empty database and see if user is automatically logged out.
		$this->User->deleteAll(array('User.id' => $user['User']['id']));
		$this->setExpectedException('HttpException', 'You need to login to access this location');
		$this->testAction('/users.json', array('return' => 'contents', 'method' => 'GET'), true);
	}

/**
 * Test the noindex meta tag is present in the html or not depending on the config.
 *
 * @return void
 */
	public function testNoIndex() {
		// Log out user.
		$this->User->setInactive();
		Configure::write('App.meta.robots.index', false);
		$output = $this->testAction(
			"/",
			array('return' => 'contents', 'method' => 'get'),
			true
		);
		$this->assertContains('<meta name="robots" content="noindex">', $output);

		Configure::write('App.meta.robots.index', true);
		$output = $this->testAction(
			"/",
			array('return' => 'contents', 'method' => 'get'),
			true
		);
		$this->assertNotContains('<meta name="robots" content="noindex">', $output);
	}
}
