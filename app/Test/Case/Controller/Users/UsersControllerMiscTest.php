<?php
/**
 * Users Controller Misc Action Tests
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

class UsersControllerMiscTest extends ControllerTestCase {

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
