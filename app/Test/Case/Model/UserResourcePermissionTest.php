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
App::uses('Resource', 'Model');
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
		$this->User = ClassRegistry::init('User');
		$this->Resource = ClassRegistry::init('Resource');
		$this->Permission = ClassRegistry::init('Permission');
		$this->UserResourcePermission = ClassRegistry::init('UserResourcePermission');
		$this->PermissionType = ClassRegistry::init('PermissionType');
	}

/******************************************************
 * Test that the view works as expected, based on the permission matrix.
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

/******************************************************
 * Test the function : findAuthorizedResourcesIds
 ******************************************************/

	/**
	 * Invalid $userId argument
	 */
	function testFindAuthorizedResourcesIdsInvalidUserId() {
		$this->setExpectedException('InvalidArgumentException', '$userId is not a valid uuid');
		$this->UserResourcePermission->findAuthorizedResourcesIds('wrong-user-id');
	}

	/**
	 * Invalid $permissionType argument
	 */
	function testFindAuthorizedResourcesIdsInvalidPermissionType() {
		$this->setExpectedException('InvalidArgumentException', '$permissionType is not a valid permission type');
		$this->UserResourcePermission->findAuthorizedResourcesIds(Common::uuid('user.id.user'), 'wrong-permission-type');
	}

	/**
	 * Check that the permissions defined in the matrix match the resources returned by the function.
	 */
	function testFindAuthorizedResourcesIds() {
		$PermissionType = Common::getModel('PermissionType');
		$permissionsTypes = [
			$PermissionType::READ,
			$PermissionType::UPDATE,
			$PermissionType::OWNER
		];

		$userId = Common::uuid('user.id.admin');
		$user = $this->User->findById($userId);
		$this->User->setActive($user);

		$matrixPath = TESTS . '/Data/view_users_resources_permissions.csv';
		$matrix = PermissionMatrix::importCsv($matrixPath, 'user');

		foreach ($matrix as $userAlias => $expectedPermissions) {
			$userId = Common::uuid('user.id.' . $userAlias);

			foreach ($permissionsTypes as $permissionsType) {
				// Expected resources for the permission type
				$expectedResourcesIds = [];
				foreach ($expectedPermissions as $resourceAlias => $expectedPermission) {
					if ($expectedPermission >= $permissionsType) {
						$expectedResourcesIds[] = Common::uuid('resource.id.' . $resourceAlias);
					}
				}
				$resourcesIds = $this->UserResourcePermission->findAuthorizedResourcesIds($userId, $permissionsType);
				$this->assertEquals(count($resourcesIds), count($expectedResourcesIds));
				$this->assertTrue(sort($resourcesIds), sort($expectedResourcesIds));
			}
		}
	}

/******************************************************
 * Test the function : findSoleOwnerSharedResourcesIds
 ******************************************************/

	/**
	 * Invalid $userId argument
	 */
	function testFindSoleOwnerSharedResourcesIdsInvalidUserId() {
		$this->setExpectedException('InvalidArgumentException', '$userId is not a valid uuid');
		$this->UserResourcePermission->findSoleOwnerSharedResourcesIds('wrong-user-id');
	}

	/**
	 * Check that functions return well the resources whose are shared and have only a sole owner
	 */
	function testFindSoleOwnerSharedResourcesIds() {
		$userId = Common::uuid('user.id.admin');
		$user = $this->User->findById($userId);
		$this->User->setActive($user);

		// Check the function return empty if the user is owner of no resources.
		$resourcesIds = $this->UserResourcePermission->findSoleOwnerSharedResourcesIds(Common::uuid('user.id.user'));
		$this->assertEmpty($resourcesIds);

		// Check the function return an empty array if the user is owner of a resources shared with nobody.
		$this->Resource->create();
		$this->Resource->save(['name' => 'resource-test']);
		$resources = $this->Resource->find('all');
		$this->assertCount(1, $resources);
		$resourcesIds = $this->UserResourcePermission->findSoleOwnerSharedResourcesIds(Common::uuid('user.id.user'));
		$this->assertEmpty($resourcesIds);

		// Check the function return the resources a user is the sole owner if shared with others.
		$resourcesIds = $this->UserResourcePermission->findSoleOwnerSharedResourcesIds(Common::uuid('user.id.ada'));
		$this->assertCount(1, $resourcesIds);
		$this->assertContains(Common::uuid('resource.id.apache'), $resourcesIds);
	}
}
