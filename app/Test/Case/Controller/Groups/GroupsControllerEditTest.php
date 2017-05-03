<?php
/**
 * Groups Controller Edit Tests
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
		'app.secret',
		'app.permission',
		'app.permissions_type',
		'app.permission_view',
	);

/**
 * It consumes too many resources to encrypt a text x times
 * so we'll use the same message everywhere
 * @var string
 */
	public $dummyPgpMessage = '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf9HpfcNeuC5W/VAzEtAe8mTBUk1vcJENtGpMyRkVTC8KbQ
xaEr3+UG6h0ZVzfrMFYrYLolS3fie83cj4FnC3gg1uijo7zTf9QhJMdi7p/ASB6N
y7//8AriVqUAOJ2WCxAVseQx8qt2KqkQvS7F7iNUdHfhEhiHkczTlehyel7PEeas
SdM/kKEsYKk6i4KLPBrbWsflFOkfQGcPL07uRK3laFz8z4LNzvNQOoU7P/C1L0X3
tlK3vuq+r01zRwmflCaFXaHVifj3X74ljhlk5i/JKLoPRvbxlPTevMNag5e6QhPQ
kpj+TJD2frfGlLhyM50hQMdJ7YVypDllOBmnTRwZ0tJFAXm+F987ovAVLMXGJtGO
P+b3c493CfF0fQ1MBYFluVK/Wka8usg/b0pNkRGVWzBcZ1BOONYlOe/JmUyMutL5
hcciUFw5
=TcQF
-----END PGP MESSAGE-----';

/**
 * Setup.
 */
	public function setUp() {
		parent::setUp();
		$this->User = Common::getModel('User');
		$this->Group = Common::getModel('Group');
		$this->Secret = Common::getModel('Secret');
		$this->GroupUser = Common::getModel('GroupUser');
		$this->GroupResourcePermission = Common::getModel('GroupResourcePermission');
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
		$this->User->setInactive();
		$this->setExpectedException('ForbiddenException', 'You need to login to access this location');
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
		$this->setExpectedException('BadRequestException', 'The group id is invalid');
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

		$this->setExpectedException('ForbiddenException', 'You are not authorized to access to this group.');
		$this->testAction(
			"/groups/$id.json",
			array('method' => 'put', 'return' => 'contents')
		);
	}

/**
 * Test updating name as an admin.
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

		//$this->setExpectedException('HttpException', 'No data was provided');
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

/**
 * Test updating name as a group manager.
 */
	public function testUpdateNameAsGroupManager() {
		$user = $this->User->findById(Common::uuid('user.id.irene'));
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

		//$this->setExpectedException('HttpException', 'No data was provided');
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
		$this->assertEquals($json['body']['Group']['name'], 'Developer', "The name should not have been updated, but the response returned {$json['body']['Group']['name']}");
	}

	private function __buildGroupUsers($userIds, $adminUserIds = []) {
		$groupUsers = [];
		foreach ($userIds as $userId) {
			$groupUsers[] = [
				'GroupUser' => [
					'user_id' => $userId,
					'is_admin' => in_array($userId, $adminUserIds) ? '1' : '0'
				]
			];
		}

		return $groupUsers;
	}

	private function __buildSecrets($userIds) {
		$secrets = [];
		foreach ($userIds as $userId) {
			$secrets[] = [
				'Secret' => [
					'user_id' => $userId,
					'data' => $this->dummyPgpMessage,
				]
			];
		}

		return $secrets;
	}


/**
 * Test updating a group group users as an admin.
 *
 * Assert that no changes are done.
 * The admin is not allowed to change the group users unless he is a group manager himself.
 */
	public function testUpdateAddGroupUsersAsAdmin() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		// Group to edit.
		$groupId = Common::uuid('group.id.accounting');

		// List of users to add.
		$userIdsToAdd = [
			Common::uuid('user.id.irene')
		];

		// Data to send in the query.
		$data = [
			'GroupUsers' => $this->__buildGroupUsers($userIdsToAdd),
			'Secrets' => $this->__buildSecrets($userIdsToAdd),
		];

		$res = $this->testAction(
			"/groups/$groupId.json",
			[
				'method' => 'put',
				'data' => $data,
				'return' => 'contents'
			]
		);
		$json = json_decode($res, true);
		$this->assertEquals($json['body']['changes']['count'], 0);
		$this->assertEmpty($json['body']['changes']['updated']);
		$this->assertEmpty($json['body']['changes']['created']);
		$this->assertEmpty($json['body']['changes']['deleted']);
	}

/**
 * Test updating a group and delete group users as an admin.
 *
 * Assert that the changes are taken into account.
 */
	public function testUpdateDeleteGroupUsersAsAdmin() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		// Group to edit.
		$groupId = Common::uuid('group.id.freelancer');

		// User Id to delete as group admin.
		$userId = Common::uuid('user.id.marlyn');

		// Find GroupUser to assert that the user is a group member.
		$groupUser = $this->Group->GroupUser->find(
			'first', ['conditions' => [ 'group_id' => $groupId, 'user_id' => $userId, ]]
		);

		// Assert that Marlyn is a group member.
		$this->assertNotEmpty($groupUser, 'Marlyn should be part of the user groups at this stage');

		// Data to send in the query.
		$data = [
			'GroupUsers' => [
				[
					'GroupUser' => [
						'id' => $groupUser['GroupUser']['id'],
						'delete' => '1'
					]
				]
			],
		];

		$res = $this->testAction( "/groups/$groupId.json", [ 'method' => 'put', 'data' => $data, 'return' => 'contents' ]);
		$json = json_decode($res, true);

		$this->assertEquals($json['body']['changes']['count'], 1);
		$this->assertEmpty($json['body']['changes']['created']);
		$this->assertEmpty($json['body']['changes']['updated']);
		$this->assertNotEmpty($json['body']['changes']['deleted']);

		// Make sure the change is applied in db.
		$groupUser = $this->Group->GroupUser->find(
			'first',  ['conditions' => [ 'group_id' => $groupId, 'user_id' => $userId, ]]
		);

		// Assert that Marlyn is not an admin already.
		$this->assertEmpty($groupUser, 'Marlyn should not be a group member anymore at this stage');
	}

/**
 * Test updating a group and update group users as an admin.
 *
 * Assert that the changes are taken into account.
 */
	public function testUpdateUpdateGroupUsersAsAdmin() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		// Group to edit.
		$groupId = Common::uuid('group.id.freelancer');

		// User Id to update as group admin.
		$userId = Common::uuid('user.id.marlyn');

		// Find GroupUser to assert that the user is a group member.
		$groupUser = $this->Group->GroupUser->find(
			'first', ['conditions' => [ 'group_id' => $groupId, 'user_id' => $userId, ]]
		);

		// Assert that Marlyn is a group member.
		$this->assertNotEmpty($groupUser, 'Marlyn should be part of the user groups at this stage');

		// Data to send in the query.
		$data = [
			'GroupUsers' => [
				[
					'GroupUser' => [
						'id' => $groupUser['GroupUser']['id'],
						'is_admin' => '1'
					]
				]
			],
		];

		$res = $this->testAction( "/groups/$groupId.json", [ 'method' => 'put', 'data' => $data, 'return' => 'contents' ]);
		$json = json_decode($res, true);

		$this->assertEquals($json['body']['changes']['count'], 1);
		$this->assertEmpty($json['body']['changes']['created']);
		$this->assertNotEmpty($json['body']['changes']['updated']);
		$this->assertEmpty($json['body']['changes']['deleted']);


		// Make sure the change is applied in db.
		$groupUser = $this->Group->GroupUser->find(
			'first',  ['conditions' => [ 'group_id' => $groupId, 'user_id' => $userId, ]]
		);

		// Assert that Marlyn is now an admin.
		$this->assertTrue($groupUser['GroupUser']['is_admin'], 'Marlyn should be an admin at this stage');
	}

/**
 * Test updating a group and adding a group user who doesn't have access to some resources, as a group manager.
 *
 * Assert that the changes are taken into account.
 */
	public function testUpdateAddGroupUsersWithSecretsAsGroupManager() {
		$user = $this->User->findById(Common::uuid('user.id.hedy'));
		$this->User->setActive($user);

		// Group to edit.
		$groupId = Common::uuid('group.id.board');
		$userId = Common::uuid('user.id.jean');

		// List of users to add.
		$userIdsToAdd = [
			$userId
		];

		$secrets = [];
		$resources = $this->GroupResourcePermission->findUnauthorizedResourcesForUsers($groupId, $userIdsToAdd);

		// Assert that there are some resources needed.
		$this->assertTrue(sizeof($resources[$userId]) > 0);

		// Build secrets.
		foreach($resources[$userId] as $resource) {
			$secrets[] = [
				'Secret' => [
					'user_id' => $userId,
					'resource_id' => $resource['Resource']['id'],
					'data' => $this->dummyPgpMessage
				]
			];
		}

		// Data to send in the query.
		$data = [
			'GroupUsers' => $this->__buildGroupUsers($userIdsToAdd),
			'Secrets' => $secrets,
		];

		$res = $this->testAction(
			"/groups/$groupId.json",
			[
				'method' => 'put',
				'data' => $data,
				'return' => 'contents'
			]
		);
		$json = json_decode($res, true);
		$this->assertEquals($json['header']['status'], Status::SUCCESS, 'should return success');
		$this->assertEquals($json['body']['changes']['count'], 1);
		$this->assertNotEmpty($json['body']['changes']['created']);
		$this->assertEmpty($json['body']['changes']['updated']);
		$this->assertEmpty($json['body']['changes']['deleted']);
		$this->assertEquals($userId, $json['body']['changes']['created'][0]['GroupUser']['user_id']);

		// Now query the api with a get and make sure the user has been added.
		$json = json_decode($this->testAction("/groups/$groupId.json", ['return' => 'contents', 'method' => 'GET']), true);
		$userIdsReturned = Hash::extract($json['body'], 'GroupUser.{n}.User.id');

		$this->assertTrue(in_array($userId, $userIdsReturned));

		// Assert that the secrets have been saved in db.
		foreach($secrets as $secret) {
			$secretDb = $this->Secret->findByUserIdAndResourceId($secret['Secret']['user_id'], $secret['Secret']['resource_id']);
			$this->assertNotEmpty($secretDb);
		}
	}

/**
 * Test updating a group and adding a group user for a user who already has access to the group resources, as a group manager.
 *
 * Assert that the changes are taken into account.
 */
	public function testUpdateAddGroupUsersWithoutSecretsAsGroupManager() {
		$user = $this->User->findById(Common::uuid('user.id.frances'));
		$this->User->setActive($user);

		// Group to edit.
		$groupId = Common::uuid('group.id.accounting');

		// List of users to add.
		// Irene already has access to all the passwords of the group accounting.
		$userIdsToAdd = [
			Common::uuid('user.id.irene')
		];

		$secrets = [];
		$resources = $this->GroupResourcePermission->findUnauthorizedResourcesForUsers($groupId, $userIdsToAdd);
		$this->assertEquals(count($resources), 0);


		// Data to send in the query.
		$data = [
			'GroupUsers' => $this->__buildGroupUsers($userIdsToAdd),
		];

		$res = $this->testAction("/groups/$groupId.json", ['method' => 'put', 'data' => $data, 'return' => 'contents']);
		$json = json_decode($res, true);
		$this->assertEquals($json['header']['status'], Status::SUCCESS, 'should return success');
		$this->assertEquals($json['body']['changes']['count'], 1);
		$this->assertNotEmpty($json['body']['changes']['created']);
		$this->assertEmpty($json['body']['changes']['updated']);
		$this->assertEmpty($json['body']['changes']['deleted']);
		$this->assertEquals(Common::uuid('user.id.irene'), $json['body']['changes']['created'][0]['GroupUser']['user_id']);

		// Now query the api with a get and make sure the user has been added.
		$res = $this->testAction("/groups/$groupId.json", ['return' => 'contents', 'method' => 'GET']);
		$json = json_decode($res, true);
		$userIdsReturned = Hash::extract($json['body'], 'GroupUser.{n}.User.id');
		$this->assertTrue(in_array(Common::uuid('user.id.irene'), $userIdsReturned));
	}

/**
 * Test updating a group and adding a group user, as a group manager.
 *
 * Assert that the changes are taken into account.
 */
	public function testUpdateDeleteGroupUsersAsGroupManager() {
		$user = $this->User->findById(Common::uuid('user.id.jean'));
		$this->User->setActive($user);

		// Group to edit.
		$groupId = Common::uuid('group.id.freelancer');

		// User Id to delete as group admin.
		$userId = Common::uuid('user.id.marlyn');

		// Find GroupUser to assert that the user is a group member.
		$groupUser = $this->Group->GroupUser->find(
			'first',
			['conditions' => [
				'group_id' => $groupId,
				'user_id' => $userId,
			]]
		);

		// Assert that Marlyn is a group member.
		$this->assertNotEmpty($groupUser, 'Marlyn should be part of the user groups at this stage');

		// Data to send in the query.
		$data = [
			'GroupUsers' => [
				[
					'GroupUser' => [
						'id' => $groupUser['GroupUser']['id'],
						'delete' => '1'
					]
				]
			],
		];

		$res = $this->testAction(
			"/groups/$groupId.json",
			[
				'method' => 'put',
				'data' => $data,
				'return' => 'contents'
			]
		);
		$json = json_decode($res, true);

		$this->assertEquals($json['body']['changes']['count'], 1);
		$this->assertEmpty($json['body']['changes']['created']);
		$this->assertEmpty($json['body']['changes']['updated']);
		$this->assertNotEmpty($json['body']['changes']['deleted']);


		// Make sure the change is applied in db.
		$groupUser = $this->Group->GroupUser->find(
			'first',
			['conditions' => [
				'group_id' => $groupId,
				'user_id' => $userId,
			]]
		);

		// Assert that Marlyn is not an admin already.
		$this->assertEmpty($groupUser, 'Marlyn should not be a group member anymore at this stage');
	}

/**
 * Test updating a group and removing the last admin, as a group manager.
 *
 * Assert that I get an exception.
 */
	public function testUpdateDeleteGroupUsersLastManagerAsGroupManager() {
		$user = $this->User->findById(Common::uuid('user.id.frances'));
		$this->User->setActive($user);

		// Group to edit.
		$groupId = Common::uuid('group.id.accounting');

		// Last group admin Id to delete.
		$userId = Common::uuid('user.id.frances');

		// Find GroupUser to assert that the user is a group member.
		$groupUser = $this->Group->GroupUser->find(
			'first',
			['conditions' => [
				'group_id' => $groupId,
				'user_id' => $userId,
			]]
		);

		// Assert that Marlyn is a group member.
		$this->assertNotEmpty($groupUser, 'Frances should be part of the user groups at this stage');
		$this->assertTrue($groupUser['GroupUser']['is_admin'], 'Frances should be an admin of the group at this stage');

		// Data to send in the query.
		$data = [
			'GroupUsers' => [
				[
					'GroupUser' => [
						'id' => $groupUser['GroupUser']['id'],
						'delete' => '1'
					]
				]
			],
		];

		$this->setExpectedException('BadRequestException', 'A group requires at least one manager');
		$this->testAction(
			"/groups/$groupId.json",
			[
				'method' => 'put',
				'data' => $data,
				'return' => 'contents'
			]
		);
	}


/**
 * Test updating a group and updating the last admin to 0, as a group manager.
 *
 * Assert that I get an exception.
 */
	public function testUpdateUpdateGroupUsersLastManagerAsGroupManager() {
		$user = $this->User->findById(Common::uuid('user.id.frances'));
		$this->User->setActive($user);

		// Group to edit.
		$groupId = Common::uuid('group.id.accounting');

		// Last group admin Id to delete.
		$userId = Common::uuid('user.id.frances');

		// Find GroupUser to assert that the user is a group member.
		$groupUser = $this->Group->GroupUser->find(
			'first',
			['conditions' => [
				'group_id' => $groupId,
				'user_id' => $userId,
			]]
		);

		// Assert that Marlyn is a group member.
		$this->assertNotEmpty($groupUser, 'Frances should be part of the user groups at this stage');
		$this->assertTrue($groupUser['GroupUser']['is_admin'], 'Frances should be an admin of the group at this stage');

		// Data to send in the query.
		$data = [
			'GroupUsers' => [
				[
					'GroupUser' => [
						'id' => $groupUser['GroupUser']['id'],
						'is_admin' => '0'
					]
				]
			],
		];

		$this->setExpectedException('BadRequestException', 'A group requires at least one manager');
		$this->testAction(
			"/groups/$groupId.json",
			[
				'method' => 'put',
				'data' => $data,
				'return' => 'contents'
			]
		);
	}


/**
 * Test updating a group with an error, and verify that no data were altered. (rollback should work).
 *
 * Assert that no data are altered.
 */
	public function testUpdateRollbackOnError() {
		$user = $this->User->findById(Common::uuid('user.id.frances'));
		$this->User->setActive($user);

		// Group to edit.
		$groupId = Common::uuid('group.id.accounting');

		$groupInitialState = $this->Group->findById($groupId);
		$groupUsersInitialState = $this->Group->GroupUser->findAllByGroupId($groupId);

		// Data to send in the query.
		$data = [
			'Group' => [
				'name' => 'accounting-updated'
			],
			'GroupUsers' => [
				[
					'GroupUser' => [
						'user_id' => Common::uuid('user.id.ada'),
					]
				],
				// The groupUser below will generate an error since Frances is already part of the group.
				[
					'GroupUser' => [
						'user_id' => Common::uuid('user.id.frances'),
					]
				]
			],
		];

		try {
			// Test action.
			$this->testAction(
				"/groups/$groupId.json",
				[
					'method' => 'put',
					'data' => $data,
					'return' => 'contents'
				]
			);
		}
		catch (Exception $e) {
			// Do nothing.
		}

		$groupAfterChange = $this->Group->findById($groupId);
		$groupUsersAfterChange = $this->Group->GroupUser->findAllByGroupId($groupId);

		// Assert that none of the change has been taken into account.
		// The name change should have been rollbacked after the error on GroupUsers.
		$this->assertEquals($groupInitialState, $groupAfterChange);
		$this->assertEquals($groupUsersInitialState, $groupUsersAfterChange);
	}

/**
 * Test that the secrets are deleted.
 */
	public function testUpdateSecretsDeleted() {
		$user = $this->User->findById(Common::uuid('user.id.jean'));
		$this->User->setActive($user);

		$Secret = Common::getModel('Secret');
		$secretCountInitial = $Secret->find('count');

		// Data to send in the query.
		$data = [
			'GroupUsers' => [
				[
					'GroupUser' => [
						'id' => Common::uuid('group_user.id.freelancer-kathleen'),
						'delete' => '1'
					]
				],
			],
		];

		// Group to edit.
		$groupId = Common::uuid('group.id.freelancer');
		$this->testAction(
			"/groups/$groupId.json",
			[
				'method' => 'put',
				'data' => $data,
				'return' => 'contents'
			]
		);

		$secretCountAfterUpdate = $Secret->find('count');

		$this->assertGreaterThan($secretCountAfterUpdate, $secretCountInitial);
	}

/**
 * Test update in a normal case.
 *
 * We attempt to add one groupUser, delete one and update one.
 *
 * Assert that the changes have been done in db.
 */
	public function testUpdateNormalCase() {
		$user = $this->User->findById(Common::uuid('user.id.jean'));
		$this->User->setActive($user);

		// Group to edit.
		$groupId = Common::uuid('group.id.freelancer');

		$groupInitialState = $this->Group->findById($groupId);

		$userToAdd = Common::uuid('user.id.ada');

		$secrets = [];
		$resources = $this->GroupResourcePermission->findUnauthorizedResourcesForUsers($groupId, [$userToAdd]);

		// Build secrets.
		foreach($resources[$userToAdd] as $resource) {
			$secrets[] = [
				'Secret' => [
					'user_id' => $userToAdd,
					'resource_id' => $resource['Resource']['id'],
					'data' => $this->dummyPgpMessage
				]
			];
		}

		// Data to send in the query.
		$data = [
			'Group' => [
				'name' => 'freelancer-updated'
			],
			'GroupUsers' => [
				// Add Ada
				[
					'GroupUser' => [
						'user_id' => Common::uuid('user.id.ada'),
					]
				],
				// Delete Kathleen.
				[
					'GroupUser' => [
						'id' => Common::uuid('group_user.id.freelancer-kathleen'),
						'delete' => '1'
					]
				],
				// Promote Marlyn as admin.
				[
					'GroupUser' => [
						'id' => Common::uuid('group_user.id.freelancer-marlyn'),
						'is_admin' => '1'
					]
				],

			],
			'Secrets' => $secrets,
		];

		// Test action.
		$res = $this->testAction(
			"/groups/$groupId.json",
			[
				'method' => 'put',
				'data' => $data,
				'return' => 'contents'
			]
		);
		$json = json_decode($res, true);


		$groupAfterChange = $this->Group->findById($groupId);

		// Assert that the group name didn't change. (Group manager is not allowed to change the group name).
		$this->assertEquals($groupAfterChange['Group']['name'], $groupInitialState['Group']['name']);


		// Assert that Ada has been added.
		$groupUserAda = $this->Group->GroupUser->findByUserIdAndGroupId(Common::uuid('user.id.ada'), $groupId);
		$this->assertNotEmpty($groupUserAda);

		// Assert that Kathleen has been deleted.
		$groupUserKathleen = $this->Group->GroupUser->findByUserIdAndGroupId(Common::uuid('user.id.kathleen'), $groupId);
		$this->assertEmpty($groupUserKathleen);

		// Assert that Marlyn has been updated.
		$groupUserMarlyn = $this->Group->GroupUser->findByUserIdAndGroupId(Common::uuid('user.id.marlyn'), $groupId);
		$this->assertNotEmpty($groupUserMarlyn);
		$this->assertEquals($groupUserMarlyn['GroupUser']['is_admin'], '1');
	}
}

