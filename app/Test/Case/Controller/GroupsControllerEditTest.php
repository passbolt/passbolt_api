<?php
/**
 * Groups Controller Edit Tests
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

/**
 * Class GroupsControllerTest
 */
class GroupsControllerEditTest extends ControllerTestCase {

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


	/**
	 * Setup.
	 */
	public function setUp() {
		parent::setUp();
		$this->User = Common::getModel('User');
		$this->Group = Common::getModel('Group');
		$this->GroupUser = Common::getModel('GroupUser');
		$this->session = new CakeSession();
		$this->session->init();
	}

	/**
	 * Teardown.
	 */
	public function tearDown() {
		parent::tearDown();
		// Make sure there is no session active after each test
		$this->User->setInactive();
	}


	/**
	 * Test editing the group without being logged in.
	 *
	 * Expect a Forbidden exception
	 */
	public function testUpdateNotLoggedIn() {
		// We expect an exception.
		$this->setExpectedException('ForbiddenException', 'You need to login to access this location');
		// test with anonymous user, and expect a forbidden exception.
		$this->testAction('/groups.json', array('return' => 'contents', 'method' => 'PUT'), true);
	}

	/**
	 * Test editing with bad uuid.
	 *
	 * Expect a Forbidden exception
	 */
	public function testUpdateBadId() {
		$user = $this->User->findById(Common::uuid('user.id.ada'));
		$this->User->setActive($user);

		// We expect an exception.
		$this->setExpectedException('BadRequestException', 'The group id is invalid');
		// test with anonymous user, and expect a forbidden exception.
		$this->testAction('/groups/aaa.json', array('return' => 'contents', 'method' => 'PUT'), true);
	}

	/**
	 * Test editing with bad uuid.
	 *
	 * Expect a Forbidden exception
	 */
	public function testUpdateNoId() {
		$user = $this->User->findById(Common::uuid('user.id.ada'));
		$this->User->setActive($user);

		// We expect an exception.
		$this->setExpectedException('BadRequestException', 'The group id is missing');
		// test with anonymous user, and expect a forbidden exception.
		$this->testAction('/groups.json', array('return' => 'contents', 'method' => 'PUT'), true);
	}


	/**
	 * Test updating a group while not being nor a group admin, nor an administrator.
	 */
	public function testUpdateNotAdminNotGroupManager() {
		$user = $this->User->findById(Common::uuid('user.id.ada'));
		$this->User->setActive($user);

		// the user to update
		$id = Common::uuid('group.id.developer');

		$this->setExpectedException('UnauthorizedException', 'You are not authorized to access to this group');
		$this->testAction(
			"/groups/$id.json",
			array('method' => 'put', 'return' => 'contents')
		);
	}

	/**
	 * Test updating
	 */
	public function testUpdateNameAsAdmin() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		// The group to update.
		$id = Common::uuid('group.id.developer');

		$newName = 'developer-updated';

		// Data to send in the query.
		$data = [
			'Group' => [
				'name' => 'developer-updated'
			]
		];

		//$this->setExpectedException('HttpException', 'No data were provided');
		$res = $this->testAction(
			"/groups/$id.json",
			[
				'method' => 'put',
				'data' => $data,
				'return' => 'contents'
			]
		);
		$json = json_decode($res, true);

		$this->assertEquals($json['header']['status'], Status::SUCCESS, "The test should have returned success");
		$this->assertEquals($json['body']['Group']['name'], $newName, "The name should have been updated, but the response returned {$json['body']['Group']['name']}");
	}

	public function testUpdateGroupUsersAsAdmin() {

	}
}

