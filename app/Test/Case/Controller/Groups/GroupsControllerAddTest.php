<?php
/**
 * Groups Controller Add Tests
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
class GroupsControllerAddTest extends ControllerTestCase {

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
 * Test adding the group without being logged in.
 *
 * Expect a Forbidden exception
 */
	public function testAddNotLoggedIn() {
		$this->User->setInactive();
		$this->setExpectedException('ForbiddenException', 'You need to login to access this location');
		$this->testAction('/groups.json', array('return' => 'contents', 'method' => 'POST'), true);
	}

/**
 * Test adding the group without being an admin.
 *
 * Expect a Unauthorized exception
 */
	public function testAddNotAdmin() {
		// Test with normal user.
		$user = $this->User->findById(Common::uuid('user.id.user'));
		$this->User->setActive($user);

		// We expect an exception.
		$this->setExpectedException('ForbiddenException', 'You are not authorized to access this endpoint');
		// test with anonymous user, and expect a forbidden exception.
		$this->testAction('/groups.json', array('return' => 'contents', 'method' => 'POST'), true);

	}

/**
 * Test adding the group without providing a group manager.
 *
 * Expect a BadRequest exception.
 */
	public function testAddNoGroupManager() {
		// Test with admin user.
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$postData = [
			'Group' => [
				'name' => 'test1',
			],
			'GroupUsers' => [
				[
					'GroupUser' => [
						'user_id' => Common::uuid(Common::uuid('user.id.ada')),
					],
				],
				[
					'GroupUser' => [
						'user_id' => Common::uuid(Common::uuid('user.id.user')),
					],
				],
			],
		];

		// We expect an exception.
		$this->setExpectedException('BadRequestException', 'A group manager must be provided');

		// Test action.
		$this->testAction(
			'/groups.json',
			array(
				'data' => $postData,
				'method' => 'post',
				'return' => 'contents'
			)
		);
	}

/**
 * Test adding a group without a name
 *
 * Expect a BadRequest exception.
 */
	public function testAddNoGroupName() {
		// Test with admin user.
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$postData = [
			'GroupUsers' => [
				[
					'GroupUser' => [
						'user_id' => Common::uuid(Common::uuid('user.id.ada')),
						'is_admin' => 1,
					],
				],
				[
					'GroupUser' => [
						'user_id' => Common::uuid(Common::uuid('user.id.user')),
					],
				],
			],
		];

		// We expect an exception.
		$this->setExpectedException('ValidationException', 'Data validation error');

		// Test action.
		$this->testAction(
			'/groups.json',
			array(
				'data' => $postData,
				'method' => 'post',
				'return' => 'contents'
			)
		);
	}

/**
 * Test adding the group without providing a group manager, and make sure that no group has been added.
 * The purpose of this test is to make sure that the transactional query is working and that no data is inserted if a validation fails.
 *
 * Expect a BadRequest exception.
 */
	public function testAddNoGroupManagerTransactional() {
		// Test with admin user.
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		// Groups in database before any treatment.
		$groupsBefore = $this->Group->find('all');

		$postData = [
			'Group' => [
				'name' => 'test1',
			],
			'GroupUsers' => [
				[
					'GroupUser' => [
						'user_id' => Common::uuid(Common::uuid('user.id.ada')),
					],
				],
				[
					'GroupUser' => [
						'user_id' => Common::uuid(Common::uuid('user.id.user')),
					],
				],
			],
		];

		try {
			// Test action.
			$this->testAction(
				'/groups.json',
				array(
					'data' => $postData,
					'method' => 'post',
					'return' => 'contents'
				)
			);
		} catch(Exception $e) {
			// Do nothing.
		}

		// Groups in database after treatment.
		$groupsAfter = $this->Group->find('all');

		// Assert that the results are the same.
		$this->assertEquals(count($groupsBefore), count($groupsAfter));
	}

/**
 * Test adding a group with the right parameters.
 *
 * Assert that group creation returns a success.
 */
	public function testAdd() {
		// Test with admin user.
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$groupName = 'test_' . rand(1111, 9999);
		$postData = [
			'Group' => [
				'name' => $groupName,
			],
			'GroupUsers' => [
				[
					'GroupUser' => [
						'user_id' => Common::uuid('user.id.ada'),
						'is_admin' => 1,
					],
				],
				[
					'GroupUser' => [
						'user_id' => Common::uuid('user.id.user'),
					],
				],
			],
		];

		// Test action.
		$result = $this->testAction(
			'/groups.json',
			array(
				'data' => $postData,
				'method' => 'post',
				'return' => 'contents'
			)
		);

		$json = json_decode($result, true);

		// Assert that it's a success.
		$this->assertEquals($json['header']['status'], Status::SUCCESS, '/groups add should return success');

		// Assert that keys of the output are valid.
		$this->assertEquals(array_keys($json['body']), ['Group', 'GroupUser']);

		// Assert that the group exists in database.
		$group = $this->Group->findByName($groupName);

		$this->assertNotEmpty($group, "The group created should exist in database");

		// Assert that it has 2 users, ada and user.
		$groupUsers = $this->GroupUser->find('all', ['conditions' => ['group_id' => $group['Group']['id']]]);
		$this->assertEquals(sizeof($groupUsers), 2, "There should be 2 group users associated with the group created.");

		$userIds = Hash::extract($groupUsers, '{n}.GroupUser.user_id');
		$this->assertTrue(in_array(Common::uuid('user.id.ada'), $userIds), "The GroupUsers created should include ada");
		$this->assertTrue(in_array(Common::uuid('user.id.user'), $userIds), "The GroupUsers created should include user");

		// Assert that Ada is a group admin.
		foreach($groupUsers as $groupUser) {
			if ($groupUser['GroupUser']['user_id'] == Common::uuid('user.id.ada')) {
				$this->assertEquals($groupUser['GroupUser']['is_admin'], '1');
			}
		}
	}

/**
 * Test adding a group with a name that already exists.
 *
 * Assert that group creation returns an exception, with the validation errors.
 */
	public function testAddGroupNameAlreadyExist() {
		// Test with admin user.
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$group = $this->Group->find('first', [
			'conditions' => [
				'deleted' => false
			]
		]);


		$groupName = $group['Group']['name'];
		$postData = [
			'Group' => [
				'name' => $groupName,
			],
			'GroupUsers' => [
				[
					'GroupUser' => [
						'user_id' => Common::uuid('user.id.ada'),
						'is_admin' => 1,
					],
				],
				[
					'GroupUser' => [
						'user_id' => Common::uuid('user.id.user'),
					],
				],
			],
		];

		// We expect an exception.
		$this->setExpectedException('ValidationException', 'Data validation error');

		$result = $this->testAction(
			'/groups.json',
			array(
				'data' => $postData,
				'method' => 'post',
				'return' => 'contents'
			)
		);

		throw new Exception(print_r($result, true));
	}
}