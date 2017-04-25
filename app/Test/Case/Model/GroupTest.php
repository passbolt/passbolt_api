<?php
/**
 * Group Model Test
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @package       app.Test.Case.Model.GroupTest
 * @since         version 2.12.7
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('Group', 'Model');

class GroupTest extends CakeTestCase {

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
		$this->Group = ClassRegistry::init('Group');
	}

/**
 * Test Name Validation
 * @return void
 */
	public function testNameValidation() {
		$testcases = array(
			'' => false,
			'?!#' => false,
			'test' => true,
			'test@test.com' => false,
			'1test' => true
		);

		foreach ($testcases as $testcase => $result) {
			$group = array('Group' => array('name' => $testcase));
			$this->Group->set($group);
			if ($result) {
				$msg = 'validation of the group name with "' . $testcase . '" should validate';
			} else {
				$msg = 'validation of the group name with "' . $testcase . '" should not validate';
			}
			$this->assertEquals($this->Group->validates(array('fieldList' => array('name'))), $result, $msg);
		}
	}

/**
 * Test Name Validation where a similar name already exists.
 * @return void
 */
	public function testNameAlreadyExistValidation() {
		$group = [
			'name' => 'testgroup',
		];

		$this->Group->create();
		$this->Group->save($group);

		$this->Group->create();
		$this->Group->set($group);
		$validates = $this->Group->validates(array('fieldList' => array('name')));
		$this->assertFalse($validates, 'A group with the same name as an existing one shouldn\'t validate');
	}

/**
 * Test the function filterGroupWithAllUsers.
 *
 * Assert that the function will remove all the groups that don't contain all the users requested.
 *
 * @return void
 */
	public function testFilterGroupWithAllUsers() {
		$options = $this->Group->getFindOptions(
			'Group::index',
			null,
			['contain' => ['user']]
		);
		// Get all groups.
		$groups = $this->Group->find('all', $options);

		$groupsFiltered = $this->Group->filterGroupWithAllUsers($groups, [Common::uuid('user.id.ping'), Common::uuid('user.id.thelma')]);
		$this->assertTrue(count($groupsFiltered) === 2, "After filtering all users, there should be only one group remaining.");

	}


/**
 * Test soft delete.
 */
	public function testSoftDelete() {
		$groupId = Common::uuid('group.id.developer');
		$perms = $this->Group->GroupResourcePermission->find('all', ['conditions' => ['aro_foreign_key' => $groupId]]);
		$groupUsers = $this->Group->GroupUser->find('all', ['conditions' => ['group_id' => $groupId]]);
		$this->assertNotEmpty($perms);
		$this->assertNotEmpty($groupUsers);

		// Soft delete group
		$this->Group->softDelete($groupId);

		// Now check that the related permissions and group users don't exist anymore.
		$permsAfterDelete = $this->Group->GroupResourcePermission->find('all', ['conditions' => ['aro_foreign_key' => $groupId]]);
		$groupUsersAfterDelete = $this->Group->GroupUser->find('all', ['conditions' => ['group_id' => $groupId]]);
		$this->assertEmpty($permsAfterDelete);
		$this->assertEmpty($groupUsersAfterDelete);
	}

}

