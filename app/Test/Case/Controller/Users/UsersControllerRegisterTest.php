<?php
/**
 * Users Controller Register Action Tests
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

class UsersControllerRegisterTest extends ControllerTestCase {

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

}
