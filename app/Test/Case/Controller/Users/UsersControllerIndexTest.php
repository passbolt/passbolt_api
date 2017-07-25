<?php
/**
 * Users Controller Index Actions Tests
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

class UsersControllerIndexTest extends ControllerTestCase {

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

		// Filter with multiple groups but no users are in all groups
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
		$this->assertEquals(count($result->body), 1);
		$this->assertEquals($result->body[0]->User->username, 'betty@passbolt.com');
	}

/**
 * Test a call to index filtered by is-active as LU
 *
 * @return void
 */
	public function testLUIndexFilteredByIsActive() {
		$user = $this->User->findById(Common::uuid('user.id.user'));
		$this->User->setActive($user);

		$data = array(
			'filter' => [
				'is-active' => false
			]
		);
		$result = json_decode($this->testAction('/users.json', array('return' => 'contents', 'method' => 'GET', 'data' => $data), true));
		$this->assertEquals($result->header->status, Status::SUCCESS, '/users return something');
		$this->assertNotEmpty($result->body);
		$usersIds = Hash::extract($result->body, "{n}.User.id");
		$this->assertContains(Common::uuid('user.id.ada'), $usersIds);
		$this->assertNotContains(Common::uuid('user.id.orna'), $usersIds);
	}

/**
 * Test a call to index filtered by is-active as AD
 *
 * @return void
 */
	public function testADIndexFilteredByIsActive() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		// Retrieve inactive users
		$data = array(
			'filter' => [
				'is-active' => false
			]
		);
		$result = json_decode($this->testAction('/users.json', array('return' => 'contents', 'method' => 'GET', 'data' => $data), true));
		$this->assertEquals($result->header->status, Status::SUCCESS, '/users return something');
		$this->assertNotEmpty($result->body);
		$usersIds = Hash::extract($result->body, "{n}.User.id");
		$this->assertNotContains(Common::uuid('user.id.ada'), $usersIds);
		$this->assertContains(Common::uuid('user.id.orna'), $usersIds);

		// Retrieve active users
		$data = array(
			'filter' => [
				'is-active' => true
			]
		);
		$result = json_decode($this->testAction('/users.json', array('return' => 'contents', 'method' => 'GET', 'data' => $data), true));
		$this->assertEquals($result->header->status, Status::SUCCESS, '/users return something');
		$this->assertNotEmpty($result->body);
		$usersIds = Hash::extract($result->body, "{n}.User.id");
		$this->assertContains(Common::uuid('user.id.ada'), $usersIds);
		$this->assertNotContains(Common::uuid('user.id.orna'), $usersIds);
	}

}
