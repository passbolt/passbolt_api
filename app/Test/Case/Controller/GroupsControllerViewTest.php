<?php
/**
 * Groups Controller View Tests
 *
 * @copyright (c) 2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('AppController', 'Controller');
App::uses('GroupsController', 'Controller');
App::uses('User', 'Model');
App::uses('Group', 'Model');
App::uses('Role', 'Model');
App::uses('CakeSession', 'Model');
App::uses('CakeSession', 'Model/Datasource');
App::uses('CakeSessionFixture', 'Test/Fixture');

/**
 * Class GroupsControllerTest
 */
class GroupsControllerViewTest extends ControllerTestCase {

	// Fixtures to be used.
	public $fixtures = array(
		'app.groups_user',
		'app.group',
		'app.user',
		'app.group',
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

	public function setUp() {
		parent::setUp();
		$this->User = Common::getModel('User');
		$this->Group = Common::getModel('Group');
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
	public function testViewNoAllowed() {
		// We expect an exception.
		$this->setExpectedException('ForbiddenException', 'You need to login to access this location');

		// Group Id.
		$groupId = Common::uuid('group.id.accounting');

		// Test with anonymous user
		$this->testAction("/groups/$groupId.json", array('return' => 'contents', 'method' => 'GET'), true);
	}

	/**
	 * Test a call to index without being logged in.
	 *
	 * @return void
	 */
	public function testViewWrongFormat() {
		$user = $this->User->findById(Common::uuid('user.id.frances'));
		$this->User->setActive($user);

		// We expect an exception.
		$this->setExpectedException('BadRequestException', 'The group id is invalid');

		// Test with anonymous user
		$this->testAction("/groups/aaa.json", array('return' => 'contents', 'method' => 'GET'), true);
	}

	/**
	 * Test a call to view.
	 *
	 * @return void
	 */
	public function testViewNormal() {
		$user = $this->User->findById(Common::uuid('user.id.frances'));
		$this->User->setActive($user);

		// Group Id.
		$groupId = Common::uuid('group.id.accounting');

		// Test with anonymous user
		$res = $this->testAction("/groups/$groupId.json", array('return' => 'contents', 'method' => 'GET'), true);

		$json = json_decode($res, true);

		$this->assertEquals($json['header']['status'], Status::SUCCESS, '/groups.json should return success');

		$keys = array_keys($json['body']);
		$this->assertEquals($keys, ['Group', 'GroupUser', 'User']);
	}

	/**
	 * Test a call to view a deleted group.
	 *
	 * It should return a 404.
	 *
	 * @return void
	 */
	public function testViewDeleted() {
		$user = $this->User->findById(Common::uuid('user.id.frances'));
		$this->User->setActive($user);

		// Make sure that the deleted group is not part of the list.
		$deletedGroup = $this->Group->findByDeleted(1);

		// We expect an exception.
		$this->setExpectedException('NotFoundException', 'The group doesn\'t exist');

		$groupId = $deletedGroup['Group']['id'];

		// Test with anonymous user
		$this->testAction("/groups/$groupId.json", array('return' => 'contents', 'method' => 'GET'), true);
	}


	/**
	 * Test view entry point with contain user parameter.
	 *
	 * Assert that when contain[user] is passed, the output contains for the group a User and GroupUser object
	 */
	public function testViewWithUserContain() {
		// test with normal user
		$user = $this->User->findById(Common::uuid('user.id.user'));
		$this->User->setActive($user);

		// Group Id.
		$groupId = Common::uuid('group.id.accounting');

		// Call to entry point with contain params.
		$params = [
			'contain' => ['user' => 1]
		];
		$res = $this->testAction(
			"/groups/$groupId.json", [
				'return' => 'contents',
				'method' => 'GET',
				'data' => $params
			],
			true
		);

		$json = json_decode($res, true);

		$this->assertEquals($json['header']['status'], Status::SUCCESS, '/groups.json should return success');

		$keys = array_keys($json['body']);
		$this->assertEquals($keys, ['Group', 'GroupUser', 'User']);
	}

	/**
	 * Test view entry point without contain user parameter.
	 *
	 * Assert that when contain[user] is removed, the output doesn't contain GroupUser nor User.
	 */
	public function testViewWithoutUserContain() {

		$user = $this->User->findById(Common::uuid('user.id.user'));
		$this->User->setActive($user);

		// Group Id.
		$groupId = Common::uuid('group.id.accounting');

		// Call to entry point with contain params.
		$params = [
			'contain' => ['user' => 0]
		];
		$res = $this->testAction(
				"/groups/$groupId.json", [
				'return' => 'contents',
				'method' => 'GET',
				'data' => $params
			],
			true
		);

		$json = json_decode($res, true);

		$this->assertEquals($json['header']['status'], Status::SUCCESS, '/groups.json should return success');

		$keys = array_keys($json['body']);
		$this->assertEquals($keys, ['Group']);
	}
}