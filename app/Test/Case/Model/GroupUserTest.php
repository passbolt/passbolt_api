<?php
/**
 * GroupUser Model Test
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @package       app.Test.Case.Model.GroupUserTest
 * @since         version 2.12.7
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('GroupUser', 'Model');
App::uses('User', 'Model');

if (!class_exists('CakeSession')) {
	require CAKE . 'Model/Datasource/CakeSession.php';
}

class GroupUserTest extends CakeTestCase {

	public $fixtures = array(
		'app.resource',
		'app.user',
		'app.role',
		'app.group',
		'app.groups_user',
		'app.gpgkey',
		'core.cakeSession'
	);

	public function setUp() {
		parent::setUp();
		$this->GroupUser = ClassRegistry::init('GroupUser');
		$this->GroupUser->useDb = 'test';
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

	/**
	 * Test bulk update for an isolated delete operation.
	 */
	public function testBulkUpdateDelete() {
		$gu = $this->GroupUser->find('first');
		$groupUsers = [
			[
				'GroupUser' => [
					'id' => $gu['GroupUser']['id'],
					'delete' => 1
				]
			]
		];

		$res = $this->GroupUser->bulkUpdate($gu['GroupUser']['group_id'], $groupUsers);
		$this->assertEquals($res['count'], 1);
		$this->assertEquals(count($res['deleted']), 1);
		$this->assertEquals($res['deleted'][0]['GroupUser']['id'], $groupUsers[0]['GroupUser']['id']);

		// Make sure that the groupUser has been deleted.
		$gu = $this->GroupUser->findById($groupUsers[0]['GroupUser']['id']);
		$this->assertEmpty($gu, 'The group user should be deleted in the database');
	}

	/**
	 * Test bulk update for an isolated update operation.
	 */
	public function testBulkUpdateUpdate() {
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

		$res = $this->GroupUser->bulkUpdate($gu['GroupUser']['group_id'], $groupUsers);
		$this->assertEquals($res['count'], 1);
		$this->assertEquals(count($res['updated']), 1);
		$this->assertEquals($res['updated'][0]['GroupUser']['id'], $groupUsers[0]['GroupUser']['id']);

		// Make sure that the groupUser has been deleted.
		$gu = $this->GroupUser->findById($groupUsers[0]['GroupUser']['id']);
		$this->assertEquals($gu['GroupUser']['is_admin'], $groupUsers[0]['GroupUser']['is_admin'], 'The group user should have been updated to admin = 1');
	}

	/**
	 * Test bulk update for an isolated create operation.
	 */
	public function testBulkUpdateCreate() {
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

		$res = $this->GroupUser->bulkUpdate($group['Group']['id'], $groupUsers);
		$this->assertEquals($res['count'], 1);
		$this->assertEquals(count($res['created']), 1);

		// Make sure that the groupUser has been deleted.
		$gu = $this->GroupUser->find(
			'first',
			['conditions' =>
				[
					'group_id' => $group['Group']['id'],
					'user_id' => $user['User']['id'],
				]
			]);
		$this->assertNotEmpty($gu, 'The group user should have been created');
		$this->assertEquals($gu['GroupUser']['is_admin'], $groupUsers[0]['GroupUser']['is_admin'], 'The group user should have been created with admin = 1');
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

		$res = $this->GroupUser->bulkUpdate($groupId, $groupUsersData);
		$this->assertEquals($res['count'], 3);
		$this->assertEquals(count($res['created']), 1);
		$this->assertEquals(count($res['updated']), 1);
		$this->assertEquals(count($res['deleted']), 1);
	}


	/**
	 * Test a bulk update with trying to update the sole admin of a group to non admin.
	 *
	 * Expect an exception.
	 */
	public function testBulkUpdateCantDeleteAllAdminsOnUpdateSingleAdmin() {
		// Group users to alter.
		$groupUsersData = [
			// The one we update.
			[
				'GroupUser' => [
					'id' => Common::uuid('group_user.id.accounting-frances'),
					'is_admin' => 0
				],
			],
		];
		$this->setExpectedException('Exception', 'Unauthorized operation. It is not possible to remove all the managers of a group');
		$this->GroupUser->bulkUpdate(Common::uuid('group.id.accounting'), $groupUsersData);
	}

	/**
	 * Test a bulk update with trying to delete the sole admin of a group.
	 *
	 * Expect an exception.
	 */
	public function testBulkUpdateCantDeleteAllAdminsOnDeleteSingleAdmin() {
		// Group users to alter.
		$groupUsersData = [
			// The one we delete.
			[
				'GroupUser' => [
					'id' => Common::uuid('group_user.id.accounting-frances'),
					'delete' => 1
				],
			],
		];
		$this->setExpectedException('Exception', 'Unauthorized operation. It is not possible to remove all the managers of a group');
		$this->GroupUser->bulkUpdate(Common::uuid('group.id.accounting'), $groupUsersData);
	}

	/**
	 * Test a bulk update with trying to update the 2 sole admins:
	 * - one is updated to a non admin
	 * - the other one is deleted
	 *
	 * Expect an exception.
	 */
	public function testBulkUpdateCantDeleteAllAdminsOnUpdateDeleteMultipleAdmins() {
		// Group users to alter.
		$groupUsersData = [
			// The one we update.
			[
				'GroupUser' => [
					'id' => Common::uuid('group_user.id.human_resource-ping'),
					'is_admin' => 0
				],
			],
			[
				'GroupUser' => [
					'id' => Common::uuid('group_user.id.human_resource-thelma'),
					'delete' => 1
				],
			],
		];
		$this->setExpectedException('Exception', 'Unauthorized operation. It is not possible to remove all the managers of a group');
		$this->GroupUser->bulkUpdate(Common::uuid('group.id.accounting'), $groupUsersData);
	}
}
