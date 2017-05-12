<?php
/**
 * Permission Model Test
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @package       app.Test.Case.Model.ResourceTest
 * @since         version 2.12.7
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('Permission', 'Model');
App::uses('PermissionType', 'Model');
App::uses('UserResourcePermission', 'Model');
App::uses('PermissionMatrix', 'Test/Data');

if (!class_exists('CakeSession')) {
	require CAKE . 'Model/Datasource/CakeSession.php';
}

class UserResourcePermissionTest extends CakeTestCase
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
		$this->UserResourcePermission = ClassRegistry::init('UserResourcePermission');
		$this->PermissionType = ClassRegistry::init('PermissionType');
	}

/******************************************************
 * INTEGRITY TESTS
 ******************************************************/

	/**
	 * Check that the permissions defined in the matrix match the permissions returned by the view users_resources_permissions.
	 */
	public function testViewIntegrity()
	{
		$matrixPath = TESTS . '/Data/view_users_resources_permissions.csv';
		$matrix = PermissionMatrix::importCsv($matrixPath);

		foreach ($matrix as $resourceAlias => $usersExpectedPermissions) {
			$resourceId = Common::uuid('resource.id.' . $resourceAlias);

			foreach ($usersExpectedPermissions as $userAlias => $expectedPermissionType) {
				$userId = Common::uuid('user.id.' . $userAlias);

				// Get the permission
				$findData = ['UserResourcePermission' => [
					'user_id' => $userId,
					'resource_id' => $resourceId,
				]];
				$findOptions = $this->UserResourcePermission->getFindOptions('findByUserAndResource', User::get('Role.name'), $findData);
				$userResourcePermission = $this->UserResourcePermission->find('first', $findOptions);

				$permissionType = 0;
				if (!empty($userResourcePermission)) {
					$permissionType = $userResourcePermission['UserResourcePermission']['permission_type'];
				}

				// Check the user permission is as expected.
				$this->assertEquals($expectedPermissionType, $permissionType,
					"permissions for user {$userAlias}({$userId}) and resource {$resourceAlias}({$resourceId}) returned {$permissionType}
				but should have returned {$expectedPermissionType}"
				);
			}
		}
	}

}
