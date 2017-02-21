<?php
/**
 * Groups Controller Tests
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
class GroupsControllerTest extends ControllerTestCase {

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

	/******************************************************
	 * INDEX TESTS
	 ******************************************************/

	/**
	 * Test a call to index without being logged in.
	 *
	 * @return void
	 */
	public function testIndexNoAllowed() {
		// We expect an exception.
		$this->setExpectedException('ForbiddenException', 'You need to login to access this location');

		// Test with anonymous user
		$this->testAction('/groups.json', array('return' => 'contents', 'method' => 'GET'), true);
	}

	/**
	 * Test a call to index.
	 *
	 * Assert that the call is a success.
	 * Assert that the deleted groups are not returned.
	 * Assert that each element contains the element Group, UserGroup and User by default.
	 *
	 * @return void
	 */
	public function testIndex() {
		// test with normal user
		$user = $this->User->findById(Common::uuid('user.id.user'));
		$this->User->setActive($user);

		$result = $this->testAction('/groups.json', array('return' => 'contents', 'method' => 'GET'), true);
		$json = json_decode($result, true);
		$this->assertEquals($json['header']['status'], Status::SUCCESS, '/groups.json should return success');

		// Extract list of groups.
		$groupIds = Hash::extract($json['body'], '{n}.Group.id');

		// Make sure that the deleted group is not part of the list.
		$deletedGroup = $this->Group->findByDeleted(1);

		// Assert that the deleted group is not visible in the list of groups that are returned.
		$this->assertFalse(in_array($deletedGroup['Group']['id'], $groupIds));

		// Assert that each element includes a UserGroup and a User entry by default.
		foreach($json['body'] as $entry) {
			$keys = array_keys($entry);
			$this->assertEquals($keys, ['Group', 'GroupUser', 'User']);
		}

	}

	/**
	 * Test index entry point with contain parameters.
	 */
	public function testIndexWithUserContain() {
		// test with normal user
		$user = $this->User->findById(Common::uuid('user.id.user'));
		$this->User->setActive($user);

		// Call to entry point with contain params.
		$params = [
			'contain[user]' => 1
		];
		$res = $this->testAction(
			"/groups.json", [
				'return' => 'contents',
				'method' => 'GET',
				'data' => $params
			],
			true
		);

		$json = json_decode($res, true);
		// Assert that each element includes a Group, UserGroup and a User.
		foreach($json['body'] as $entry) {
			$keys = array_keys($entry);
			$this->assertEquals($keys, ['Group', 'GroupUser', 'User']);
		}
	}

	/**
	 * Test index entry point with contain parameters.
	 */
	public function testIndexWithoutUserContain() {
		// test with normal user
		$user = $this->User->findById(Common::uuid('user.id.user'));
		$this->User->setActive($user);

		// Call to entry point with contain params.
		$params = [
			'contain' => ['user' => 0]
		];
		$res = $this->testAction(
			"/groups.json", [
			'return' => 'contents',
			'method' => 'GET',
			'data' => $params
		],
			true
		);

		$json = json_decode($res, true);
		// Assert that each element includes a Group, UserGroup and a User.
		foreach($json['body'] as $entry) {
			$keys = array_keys($entry);
			$this->assertEquals($keys, ['Group']);
		}
	}

	/**
	 * Test index entry point with filter parameters.
	 */
	public function testIndexWithFilter() {

	}

}