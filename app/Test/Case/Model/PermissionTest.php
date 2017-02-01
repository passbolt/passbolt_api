<?php
/**
 * Permission Model Test
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @package       app.Test.Case.Model.ResourceTest
 * @since         version 2.12.7
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('Permission', 'Model');
App::uses('PermissionType', 'Model');
App::uses('PermissionMatrix', 'Test/Data');

if (!class_exists('CakeSession')) {
	require CAKE . 'Model/Datasource/CakeSession.php';
}

class PermissionTest extends CakeTestCase
{
	public $fixtures = array(
		'app.resource',
		'app.user',
		'app.profile',
		'app.file_storage',
		'app.role',
		'app.group',
		'app.groupsUser',
		'app.permissionsType',
		'app.permission',
		'app.permission_view',
		'app.gpgkey',
		'core.cakeSession'
	);

	public function setUp()
	{
		parent::setUp();
		$this->Permission = ClassRegistry::init('Permission');
		$this->User = ClassRegistry::init('User');
		$this->Role = ClassRegistry::init('Role');
		$this->Resource = ClassRegistry::init('Resource');
		$this->Group = ClassRegistry::init('Group');
		$this->PermissionType = ClassRegistry::init('PermissionType');
		$this->PermissionCache = ClassRegistry::init('PermissionCache');

		$this->Resource->Behaviors->disable('Permissionable');
	}

	public function testMysqlFunctionGetUserResourcePermission()
	{
		$matrix = PermissionMatrix::importCsv(TESTS . '/Data/view_users_resources_permissions.csv');

		foreach ($matrix as $resourceAlias => $usersPermissions) {
			$resourceId = Common::uuid('resource.id.' . $resourceAlias);

			foreach ($usersPermissions as $userAlias => $userPermission) {
				$userId = Common::uuid('user.id.' . $userAlias);

				// Get the permission
				$query = "SELECT UserResourcePermission.permission_id
							FROM users_resources_permissions UserResourcePermission
							WHERE UserResourcePermission.user_id = \"$userId\"
							AND UserResourcePermission.resource_id = \"$resourceId\"";
				$res = $this->Permission->query($query);

				// If no permission found
				if (empty($res)) {
					$permissionType = 0;
				}
				// Load the permission
				else {
					$permission = $this->Permission->findById($res[0]['UserResourcePermission']['permission_id']);
					$permissionType = $permission ? $permission['Permission']['type'] : 0;
				}

				// Check the user permission is as expected.
				$this->assertTrue(
					$userPermission == $permissionType,
					"permissions for user {$userAlias}({$userId}) and resource {$resourceAlias}({$resourceId}) returned {$permissionType}
				but should have returned {$userPermission}"
				);
			}

		}
	}

	public function testRevokePermissionWhenSoftDeleteUser() {
		$user = $this->User->find('first', array('conditions' => array('username' => 'admin@passbolt.com')));
		$this->User->setActive($user);

		// Retrieve the user to soft delete
		$userA = $this->User->find('first', array('conditions' => array('username' => 'ada@passbolt.com')));
		// Ensure this user has already few direct permissions
		$permissions = $this->Permission->find('all', array('conditions' => array('aro_foreign_key' => $userA['User']['id'])));
		$this->assertNotEmpty($permissions);

		// Soft delete the user and check he has no permission anymore
		$this->User->softDelete($userA['User']['id']);
		$permissions = $this->Permission->find('all', array('conditions' => array('aro_foreign_key' => $userA['User']['id'])));
		$this->assertEmpty($permissions);
	}
}
