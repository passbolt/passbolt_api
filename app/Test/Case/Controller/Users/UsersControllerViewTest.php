<?php
/**
 * Users Controller View Action Tests
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

class UsersControllerViewTest extends ControllerTestCase {

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

		$this->setExpectedException('BadRequestException', 'The user id is not valid.');
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
}
