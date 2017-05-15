<?php
/**
 * GroupUser Model Test
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @package       app.Test.Case.Model.GroupUserTest
 * @since         version 2.12.7
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('GroupUser', 'Model');
App::uses('User', 'Model');
App::uses('CakeSession', 'Model');
App::uses('CakeSession', 'Model/Datasource');
App::uses('CakeSessionFixture', 'Test/Fixture');

class GroupUserTest extends CakeTestCase {

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
	 * It consumes too much resources to encrypt a text x times
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

	public function setUp() {
		parent::setUp();
		$this->GroupUser = ClassRegistry::init('GroupUser');
		$this->GroupUser->useDb = 'test';
		$this->Secret = ClassRegistry::init('Secret');
		$this->User = ClassRegistry::init('User');

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
	 * Test GroupId Validation
	 * @return void
	 */
	public function testGroupIdValidation() {
		$testcases = array(
			'' => false,
			'?!#' => false,
			'test' => false,
			'4ff6111b-efb8-4a26-aab4-2184cbdd56c' => false,
			Common::uuid('group.id.accounting') => true
		);

		foreach ($testcases as $testcase => $result) {
			$cr = array(
				'GroupUser' => array(
					'group_id' => $testcase,
					'user_id' => Common::uuid('user.id.carol') // user_id is passed here because when we don't pass it test fails for obscure reasons
				)
			);
			$this->GroupUser->create();
			$this->GroupUser->set($cr);
			if ($result) {
				$msg = 'validation of the group_user "group id" with "' . $testcase . '" should validate';
			} else {
				$msg = 'validation of the group_user "group id" with "' . $testcase . '" should not validate';
			}
			$validation = $this->GroupUser->validates(array('fieldList' => array('group_id')));
			if (!$validation) {
				$msg .= print_r($this->GroupUser->validationErrors, true);
			}
			$this->assertEquals($validation, $result, "$msg");
		}
	}

	/**
	 * Test UserId Validation
	 * @return void
	 */
	public function testUserIdValidation() {
		$testcases = array(
			'' => false,
			'?!#' => false,
			'test' => false,
			'4ff6111b-efb8-4a26-aab4-2184cbdd56c' => false,
			Common::uuid('user.id.carol') => true
		);
		foreach ($testcases as $testcase => $result) {
			$cr = array(
				'GroupUser' => array(
					'user_id' => $testcase,
					'group_id' => Common::uuid('group.id.accounting')
				)
			);
			$this->GroupUser->create();
			$this->GroupUser->set($cr);
			if ($result) {
				$msg = 'validation of the group_user "user id" with "' . $testcase . '" should validate';
			} else {
				$msg = 'validation of the group_user "user id" with "' . $testcase . '" should not validate';
			}
			$validation = $this->GroupUser->validates(array('fieldList' => array('user_id')));
			if (!$validation) {
				$msg .= print_r($this->GroupUser->validationErrors, true);
			}
			$this->assertEquals($validation, $result, $msg);
		}
	}

	/**
	 * Test Duplicates
	 * @return void
	 */
	public function testDuplicatesValidation() {
		$cr = $this->GroupUser->find('first');
		$cr['GroupUser']['id'] = '';
		// test duplicates
		$this->GroupUser->create();
		$this->GroupUser->set($cr);
		$validation = $this->GroupUser->validates(array('fieldList' => array('group_id', 'user_id')));
		$this->assertEquals($validation, false, print_r($this->GroupUser->validationErrors, true));
	}

	/**
	 * Test with deleted user
	 * @return void
	 */
	public function testDeletedUserValidation() {
		// Fetch a user that is deleted, but active.
		$u = $this->GroupUser->User->find('first', ['conditions' => ['User.deleted' => true, 'User.active' => true]]);
		// Fetch a group that is not deleted.
		$g = $this->GroupUser->Group->find('first', ['conditions' => ['Group.deleted' => false]]);

		$this->assertTrue(!empty($u));
		$this->assertTrue(!empty($g));

		$this->GroupUser->create();
		$this->GroupUser->set([
			'group_id' => $u['User']['id'],
			'user_id' => $g['Group']['id'],
		]);
		$validation = $this->GroupUser->validates(array('fieldList' => array('group_id', 'user_id')));
		$this->assertEquals($validation, false, print_r($this->GroupUser->validationErrors, true));
	}

	/**
	 * Test with non active user
	 * @return void
	 */
	public function testNonActiveUserValidation() {
		// Fetch a user that is not deleted, but not active.
		$u = $this->GroupUser->User->find('first', ['conditions' => ['User.active' => false, 'User.deleted' => false]]);
		// Fetch a group that is not deleted.
		$g = $this->GroupUser->Group->find('first', ['conditions' => ['Group.deleted' => false]]);

		$this->assertTrue(!empty($u));
		$this->assertTrue(!empty($g));

		$this->GroupUser->create();
		$this->GroupUser->set([
			'group_id' => $u['User']['id'],
			'user_id' => $g['Group']['id'],
		]);
		$validation = $this->GroupUser->validates(array('fieldList' => array('group_id', 'user_id')));
		$this->assertEquals($validation, false, print_r($this->GroupUser->validationErrors, true));
	}

	/**
	 * Test with deleted group
	 * @return void
	 */
	public function testDeletedGroupValidation() {
		// Fetch a user that is active and not deleted.
		$u = $this->GroupUser->User->find('first', ['conditions' => ['User.active' => true, 'User.deleted' => false]]);
		// Fetch a group that is deleted.
		$g = $this->GroupUser->Group->find('first', ['conditions' => ['Group.deleted' => true]]);

		$this->assertTrue(!empty($u));
		$this->assertTrue(!empty($g));

		$this->GroupUser->create();
		$this->GroupUser->set([
			'group_id' => $u['User']['id'],
			'user_id' => $g['Group']['id'],
		]);
		$validation = $this->GroupUser->validates(array('fieldList' => array('group_id', 'user_id')));
		$this->assertEquals($validation, false, print_r($this->GroupUser->validationErrors, true));
	}

	public function testIsAdminValidation() {
		$testcases = array(
			'' => true,
			'1' => true,
			'0' => true,
			'a' => false,
			'aaa' => false,
			'true' => false,

		);
		foreach ($testcases as $testcase => $result) {
			$cr = array(
				'GroupUser' => array(
					'is_admin' => $testcase
				)
			);
			$this->GroupUser->create();
			$this->GroupUser->set($cr);
			if ($result) {
				$msg = 'validation of the group_user "is_admin" with "' . $testcase . '" should validate';
			} else {
				$msg = 'validation of the group_user "is_admin" with "' . $testcase . '" should not validate';
			}
			$validation = $this->GroupUser->validates(array('fieldList' => array('is_admin')));
			if (!$validation) {
				$msg .= print_r($this->GroupUser->validationErrors, true);
			}
			$this->assertEquals($validation, $result, $msg);
		}
	}

	/**
	 * Test bulk update for a groupUser with invalid uuid.
	 */
	public function testPrepareBulkUpdateInvalidGroupUser() {
		$gu = $this->GroupUser->find('first');
		$groupUsers = [
			[
				'GroupUser' => [
					'id' => 'aaa',
					'delete' => 1
				]
			]
		];

		$this->setExpectedException('Exception', 'The groupUser with id aaa is invalid');
		$res = $this->GroupUser->prepareBulkUpdate($gu['GroupUser']['group_id'], $groupUsers);
	}

	/**
	 * Test bulk update for a groupUser that doesn't exist.
	 */
	public function testPrepareBulkUpdateGroupUserDoesntExist() {
		$gu = $this->GroupUser->find('first');
		$groupUsers = [
			[
				'GroupUser' => [
					'id' => Common::uuid('doesntexist'),
					'delete' => 1
				]
			]
		];

		$this->setExpectedException('Exception', 'The groupUser with id ' . Common::uuid('doesntexist') . ' does not exist');
		$res = $this->GroupUser->prepareBulkUpdate($gu['GroupUser']['group_id'], $groupUsers);
	}

	/**
	 * Test bulk update for a groupUser that doesn't belong to the edited group.
	 */
	public function testPrepareBulkUpdateGroupUserDoesntBelongToEditedGroup() {
		$gId = Common::uuid('group.id.creative');
		$groupUsers = [
			[
				'GroupUser' => [
					'id' => Common::uuid('group_user.id.accounting-frances'),
					'delete' => 1
				]
			]
		];

		$this->setExpectedException('Exception', 'The GroupUser provided doesn\'t belong to the edited group ' . $gId);
		$res = $this->GroupUser->prepareBulkUpdate($gId, $groupUsers);
	}

	/**
	 * Test bulk update with an unknown operation.
	 */
	public function testPrepareBulkUpdateUnknownOperation() {
		$gId = Common::uuid('group.id.creative');
		$groupUsers = [
			[
				'GroupUser' => [
					'id' => Common::uuid('group_user.id.accounting-frances'),
				]
			]
		];

		$this->setExpectedException('Exception', 'Unknown groupUser update operation');
		$res = $this->GroupUser->prepareBulkUpdate($gId, $groupUsers);
	}


	/**
	 * Test bulk update for an isolated delete operation.
	 */
	public function testPrepareBulkUpdateDelete() {
		$gu = $this->GroupUser->find('first');
		$groupUsers = [
			[
				'GroupUser' => [
					'id' => $gu['GroupUser']['id'],
					'delete' => 1
				]
			]
		];

		$res = $this->GroupUser->prepareBulkUpdate($gu['GroupUser']['group_id'], $groupUsers);
		$this->assertEquals($res['count'], 1);
		$this->assertEquals(count($res['delete']), 1);
		$this->assertEquals($res['delete'][0]['GroupUser']['id'], $groupUsers[0]['GroupUser']['id']);
	}

	/**
	 * Test bulk update for an isolated update operation.
	 */
	public function testPrepareBulkUpdateUpdate() {
		$gu = $this->GroupUser->find('first', [
			'conditions' => ['is_admin' => 0]
		]);
		$groupUsers = [
			[
				'GroupUser' => [
					'id' => $gu['GroupUser']['id'],
					'is_admin' => 1
				]
			]
		];

		$res = $this->GroupUser->prepareBulkUpdate($gu['GroupUser']['group_id'], $groupUsers);
		$this->assertEquals($res['count'], 1);
		$this->assertEquals(count($res['update']), 1);
		$this->assertEquals($res['update'][0]['GroupUser']['id'], $groupUsers[0]['GroupUser']['id']);
	}

	/**
	 * Test bulk update for an isolated create operation.
	 */
	public function testPrepareBulkUpdateCreate() {
		$group = $this->GroupUser->Group->findByName('Sales');
		$user = $this->GroupUser->User->findByUsername('thelma@passbolt.com');
		$groupUsers = [
			[
				'GroupUser' => [
					'user_id' => $user['User']['id'],
					'is_admin' => 1
				]
			]
		];

		$res = $this->GroupUser->prepareBulkUpdate($group['Group']['id'], $groupUsers);
		$this->assertEquals($res['count'], 1);
		$this->assertEquals(count($res['create']), 1);
	}

	/**
	 * Test a bulk update with 3 alterations, one of each type.
	 */
	public function testBulkUpdateBulk() {
		$groupId = Common::uuid('group.id.freelancer');

		$groupUserAdmin = $this->GroupUser->find('first', [
			'conditions' => [
				'group_id' => $groupId,
				'is_admin' => true
			]
		]);
		$groupUserNonAdmin = $this->GroupUser->find('first', [
			'conditions' => [
				'group_id' => $groupId,
				'is_admin' => false
			]
		]);

		// Group users to alter.
		$groupUsersData = [
			// The one we update.
			[
				'GroupUser' => [
					'id' => $groupUserAdmin['GroupUser']['id'],
					'is_admin' => 0
				],
			],
			// The one we delete.
			[
				'GroupUser' => [
					'id' => $groupUserNonAdmin['GroupUser']['id'],
					'delete' => 1
				],
			],
			// The one we create.
			[
				'GroupUser' => [
					'user_id' => Common::uuid('user.id.ada'),
				],
			]
		];

		$res = $this->GroupUser->prepareBulkUpdate($groupId, $groupUsersData);
		$this->assertEquals($res['count'], 3);
		$this->assertEquals(count($res['create']), 1);
		$this->assertEquals(count($res['update']), 1);
		$this->assertEquals(count($res['delete']), 1);
	}


/**
 * Build an array of secrets.
 */
	private function __buildSecrets($userIds, $resourceIds) {
		$secrets = [];
		foreach ($userIds as $userId) {
			foreach($resourceIds as $resourceId) {
				$secrets[] = [
					'Secret' => [
						'user_id' => $userId,
						'resource_id' => $resourceId,
						'data' => $this->dummyPgpMessage,
					]
				];
			}
		}

		return $secrets;
	}

/**
 * Test the function saveGroupUser() in a case where the count of secrets provided is not right.
 *
 * Assert that there is an exception.
 */
	public function testSaveGroupUserNotEnoughSecrets() {
		$user = $this->User->findById(Common::uuid('user.id.hedy'));
		$this->User->setActive($user);

		$groupId = Common::uuid('group.id.board');
		$userId = Common::uuid('user.id.frances');
		$groupUser = [
			'GroupUser' => [
				'user_id' => $userId,
				'group_id' => $groupId,
			],
		];

		$secrets = [
			[
				'Secret' => [
					'user_id' => $userId,
					'resource_id' => Common::uuid('resource.id.cakephp'),
					'data' => $this->dummyPgpMessage,
				]
			]
		];

		$this->setExpectedException('Exception', 'Mismatch: 12 secrets are expected for user ' . Common::uuid('user.id.frances'));
		$this->GroupUser->createGroupUser($groupUser, $secrets);
	}

/**
 * Test the function saveGroupUser() in a case where one secret is not associated to the right resource id.
 *
 * Assert that there is an exception.
 */
	public function testCreateGroupUserOneHasBadResourceId() {
		$user = $this->User->findById(Common::uuid('user.id.hedy'));
		$this->User->setActive($user);

		$GroupResourcePermission = ClassRegistry::init('GroupResourcePermission');

		$groupId = Common::uuid('group.id.board');
		$userIds = [
			Common::uuid('user.id.frances'),
		];

		$resources = $GroupResourcePermission->findAuthorizedResources($groupId);
		$resourceIds = Hash::extract($resources, '{n}.Resource.id');

		$secrets = $this->__buildSecrets($userIds, $resourceIds);
		// Set a wrong id to the last secret (apache is not among the list).
		$secrets[count($secrets) - 1]['Secret']['resource_id'] = Common::uuid('resource.id.inkscape');

		$groupUser = [
			'GroupUser' => [
				'user_id' => $userIds[0],
				'group_id' => $groupId,
			]
		];

		$this->setExpectedException('Exception', 'The secret provided is not required');
		$this->GroupUser->createGroupUser($groupUser, $secrets);
	}

/**
 * Test the function saveGroupUser() in a case where two secrets are provided for the same resource_id.
 *
 * Assert that there is an exception.
 */
	public function testCreateGroupUserDuplicateResourceId() {
		$user = $this->User->findById(Common::uuid('user.id.hedy'));
		$this->User->setActive($user);


		$GroupResourcePermission = ClassRegistry::init('GroupResourcePermission');

		$groupId = Common::uuid('group.id.board');
		$userIds = [
			Common::uuid('user.id.frances'),
		];

		$resources = $GroupResourcePermission->findAuthorizedResources($groupId);
		$resourceIds = Hash::extract($resources, '{n}.Resource.id');

		$secrets = $this->__buildSecrets($userIds, $resourceIds);
		// Set a wrong id to the last secret (apache is not among the list).
		$secrets[count($secrets) - 1]['Secret']['resource_id'] = $secrets[count($secrets) - 2]['Secret']['resource_id'];

		$groupUser = [
			'GroupUser' => [
				'user_id' => $userIds[0],
				'group_id' => $groupId,
			]
		];

		$this->setExpectedException('Exception', 'The secret provided is not required');
		$this->GroupUser->createGroupUser($groupUser, $secrets);
	}


/**
 * Test the function createGroupUser() in a normal case.
 *
 * Assert that the secrets have been added in the database after the call.
 */
	public function testCreateGroupUserNormalCase() {
		$user = $this->User->findById(Common::uuid('user.id.hedy'));
		$this->User->setActive($user);

		$countSecretsBefore = $this->Secret->find('count');

		$GroupResourcePermission = ClassRegistry::init('GroupResourcePermission');

		$groupId = Common::uuid('group.id.board');
		$userIds = [
			Common::uuid('user.id.frances'),
		];

		$resources = $GroupResourcePermission->findAuthorizedResources($groupId);
		$resourceIds = Hash::extract($resources, '{n}.Resource.id');

		$secrets = $this->__buildSecrets($userIds, $resourceIds);

		$groupUser = [
			'GroupUser' => [
				'user_id' => $userIds[0],
				'group_id' => $groupId,
			]
		];
		$this->GroupUser->createGroupUser($groupUser, $secrets);
		$countSecretsAfter = $this->Secret->find('count');

		$this->assertEquals($countSecretsAfter - $countSecretsBefore, count($secrets));
	}

/**
 * Test the function updateGroupUser() in a validation error case.
 *
 * Assert that an exception is returned.
 */
	public function testUpdateGroupUserValidationError() {
		$user = $this->User->findById(Common::uuid('user.id.hedy'));
		$this->User->setActive($user);

		$groupUser = [
			'GroupUser' => [
				'id' => Common::uuid('group_user.id.creative-irene'),
				'is_admin' => 'aaa'
			]
		];

		$this->setExpectedException('Exception', 'Could not validate GroupUser');
		$this->GroupUser->updateGroupUser($groupUser);
	}

/**
 * Test the function updateGroupUser() in a validation error case.
 *
 * Assert that an exception is returned.
 */
	public function testUpdateGroupUserCantRemoveLastAdmin() {
		$user = $this->User->findById(Common::uuid('user.id.irene'));
		$this->User->setActive($user);

		$groupUser = [
			'GroupUser' => [
				'id' => Common::uuid('group_user.id.creative-irene'),
				'group_id' => Common::uuid('group.id.creative'),
				'user_id' => Common::uuid('user.id.irene'),
				'is_admin' => '0'
			]
		];

		$this->setExpectedException('Exception', 'A group requires at least one manager');
		$this->GroupUser->updateGroupUser($groupUser);
	}

/**
 * Test the function updateGroupUser() in a normal scenario.
 *
 * Assert that the changes reflect in the db.
 */
	public function testUpdateGroupUserNormalCase() {
		$user = $this->User->findById(Common::uuid('user.id.jean'));
		$this->User->setActive($user);

		// Get initial value of the field.
		$groupUser = $this->GroupUser->findById(Common::uuid('group_user.id.freelancer-kathleen'));
		$this->assertEquals($groupUser['GroupUser']['is_admin'], 1);

		$groupUser = [
			'GroupUser' => [
				'id' => Common::uuid('group_user.id.freelancer-kathleen'),
				'group_id' => Common::uuid('group.id.freelancer'),
				'user_id' => Common::uuid('user.id.kathleen'),
				'is_admin' => '0'
			]
		];

		$this->GroupUser->updateGroupUser($groupUser);

		// Make sure that the field has been updated in the db.
		$groupUser = $this->GroupUser->findById(Common::uuid('group_user.id.freelancer-kathleen'));
		$this->assertEquals($groupUser['GroupUser']['is_admin'], 0);
	}

/**
 * Test the function deleteGroupUser() when trying to delete the last admin.
 *
 * Assert that we get an exception.
 */
	public function testDeleteGroupAdminLastAdmin() {
		$user = $this->User->findById(Common::uuid('user.id.irene'));
		$this->User->setActive($user);

		$groupUser = [
			'GroupUser' => [
				'id' => Common::uuid('group_user.id.creative-irene'),
				'group_id' => Common::uuid('group.id.creative'),
				'user_id' => Common::uuid('user.id.irene'),
				'is_admin' => '1'
			]
		];

		$this->setExpectedException('Exception', 'A group requires at least one manager');
		$this->GroupUser->deleteGroupUser($groupUser);
	}

/**
 * Test the function deleteGroupUser() in a normal scenario.
 *
 * Assert that the changes reflect in the db, and that the secrets have been deleted.
 */
	public function testDeleteGroupUserNormalCase() {
		$user = $this->User->findById(Common::uuid('user.id.jean'));
		$this->User->setActive($user);

		// Get initial value of the field.
		$groupUser = $this->GroupUser->findById(Common::uuid('group_user.id.freelancer-kathleen'));
		$this->assertEquals($groupUser['GroupUser']['is_admin'], 1);

		$groupUser = [
			'GroupUser' => [
				'id' => Common::uuid('group_user.id.freelancer-kathleen'),
				'group_id' => Common::uuid('group.id.freelancer'),
				'user_id' => Common::uuid('user.id.kathleen'),
				'is_admin' => '0'
			]
		];

		$secrets = $this->Secret->findAllByUserId(Common::uuid('user.id.kathleen'));
		$this->assertTrue(count($secrets) > 1);

		$this->GroupUser->deleteGroupUser($groupUser);

		// Make sure that the field has been deleted from the db.
		$groupUser = $this->GroupUser->findById(Common::uuid('group_user.id.freelancer-kathleen'));
		$this->assertEmpty($groupUser);

		// Make sure that the secrets have been deleted.
		// After removal, Kathleen shouldn't have access to any secret. (she has no other permission).
		$secrets = $this->Secret->findAllByUserId(Common::uuid('user.id.kathleen'));
		$this->assertTrue(count($secrets) == 0);
	}

/**
 * Test the function findUsersIdsMemberOfGroups()
 * Retrieve all the users who are member of a multiple groups
 */
	public function testFindUsersIdsMemberOfGroups() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		// If no groups given, it should return an empty array.
		$groupsIds = [];
		$usersIds = $this->GroupUser->findUsersIdsMemberOfGroups($groupsIds);
		$this->assertEmpty($usersIds);

		// A user member of one group should be returned when looking for the group he is member of
		$groupsIds = [Common::uuid('group.id.board')];
		$usersIds = $this->GroupUser->findUsersIdsMemberOfGroups($groupsIds);
		$this->assertCount(1, $usersIds);
		$this->assertContains(Common::uuid('user.id.hedy'), $usersIds);

		// A user member of multiple groups should be returned when looking for a group he is member of
		$groupsIds = [Common::uuid('group.id.developer')];
		$usersIds = $this->GroupUser->findUsersIdsMemberOfGroups($groupsIds);
		$this->assertCount(1, $usersIds);
		$this->assertContains(Common::uuid('user.id.irene'), $usersIds);

		// A user member of multiple groups should be returned when looking for multiple groups he is member of
		$groupsIds = [Common::uuid('group.id.developer'), Common::uuid('group.id.ergonom')];
		$usersIds = $this->GroupUser->findUsersIdsMemberOfGroups($groupsIds);
		$this->assertCount(1, $usersIds);
		$this->assertContains(Common::uuid('user.id.irene'), $usersIds);

		// Multiple members of multiple groups should be returned when looking for multiple groups they are member of
		// Remove wang from one of the requested groups to ensure the function return well users that are members of both groups.
		$this->GroupUser->deleteAll([
			'group_id' => Common::uuid('group.id.it_support'),
			'user_id' => Common::uuid('user.id.wang')
		]);
		$groupsIds = [Common::uuid('group.id.human_resource'), Common::uuid('group.id.it_support')];
		$usersIds = $this->GroupUser->findUsersIdsMemberOfGroups($groupsIds);
		$this->assertCount(3, $usersIds);
		$this->assertContains(Common::uuid('user.id.ping'), $usersIds);
		$this->assertContains(Common::uuid('user.id.thelma'), $usersIds);
		$this->assertContains(Common::uuid('user.id.ursula'), $usersIds);
		$this->assertNotContains(Common::uuid('user.id.wang'), $usersIds);
	}

/**
 * Test the function findGroupsIdsHavingMembers()
 * Retrieve all the groups having all the members
 */
	public function testFindGroupsIdsHavingMembers() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		// If no users given, it should return an empty array.
		$usersIds = [];
		$groupsIds = $this->GroupUser->findGroupsIdsHavingMembers($usersIds);
		$this->assertEmpty($groupsIds);

		// A group having one member should be returned when looking for one of its members
		$usersIds = [Common::uuid('user.id.hedy')];
		$groupsIds = $this->GroupUser->findGroupsIdsHavingMembers($usersIds);
		$this->assertCount(1, $groupsIds);
		$this->assertContains(Common::uuid('group.id.board'), $groupsIds);

		// A group having multiple members should be returned when looking for several of them
		$usersIds = [Common::uuid('user.id.frances'), Common::uuid('user.id.grace')];
		$groupsIds = $this->GroupUser->findGroupsIdsHavingMembers($usersIds);
		$this->assertCount(1, $groupsIds);
		$this->assertContains(Common::uuid('group.id.accounting'), $groupsIds);

		// Multiple groups having the same member should be returned when looking for it
		$usersIds = [Common::uuid('user.id.irene')];
		$groupsIds = $this->GroupUser->findGroupsIdsHavingMembers($usersIds);
		$this->assertCount(3, $groupsIds);
		$this->assertContains(Common::uuid('group.id.creative'), $groupsIds);
		$this->assertContains(Common::uuid('group.id.developer'), $groupsIds);
		$this->assertContains(Common::uuid('group.id.ergonom'), $groupsIds);

		// Multiple groups having the same requested members should be returned when looking for them
		$usersIds = [Common::uuid('user.id.ping'), Common::uuid('user.id.thelma')];
		$groupsIds = $this->GroupUser->findGroupsIdsHavingMembers($usersIds);
		$this->assertCount(2, $groupsIds);
		$this->assertContains(Common::uuid('group.id.human_resource'), $groupsIds);
		$this->assertContains(Common::uuid('group.id.it_support'), $groupsIds);
	}


	/**
	 * Test the function findGroupsIdsHavingManagers()
	 * Retrieve all the groups having all the managers
	 */
	public function testFindGroupsIdsHavingManagers() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		// If no users given, it should return an empty array.
		$usersIds = [];
		$groupsIds = $this->GroupUser->findGroupsIdsHavingManagers($usersIds);
		$this->assertEmpty($groupsIds);

		// A group having one member should be returned when looking for one of its manager
		$usersIds = [Common::uuid('user.id.frances')];
		$groupsIds = $this->GroupUser->findGroupsIdsHavingManagers($usersIds);
		$this->assertCount(1, $groupsIds);
		$this->assertContains(Common::uuid('group.id.accounting'), $groupsIds);

		// A group having multiple managers should be returned when looking for several of them
		$usersIds = [Common::uuid('user.id.ping'), Common::uuid('user.id.thelma')];
		$groupsIds = $this->GroupUser->findGroupsIdsHavingManagers($usersIds);
		$this->assertCount(1, $groupsIds);
		$this->assertContains(Common::uuid('group.id.human_resource'), $groupsIds);

		// Multiple groups having the same manager should be returned when looking for it
		$usersIds = [Common::uuid('user.id.irene')];
		$groupsIds = $this->GroupUser->findGroupsIdsHavingManagers($usersIds);
		$this->assertCount(3, $groupsIds);
		$this->assertContains(Common::uuid('group.id.creative'), $groupsIds);
		$this->assertContains(Common::uuid('group.id.developer'), $groupsIds);
		$this->assertContains(Common::uuid('group.id.ergonom'), $groupsIds);
	}
}
