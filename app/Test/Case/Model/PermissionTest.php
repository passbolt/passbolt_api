<?php
/**
 * Permission Model Test
 *
 * @copyright     Copyright 2012, Passbolt.com
 * @package       app.Test.Case.Model.ResourceTest
 * @since         version 2.12.7
 * @license       http://www.passbolt.com/license
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
		'app.categoryType',
		'app.category',
		'app.categoriesResource',
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
		$this->Category = ClassRegistry::init('Category');
		$this->Resource = ClassRegistry::init('Resource');
		$this->Group = ClassRegistry::init('Group');
		$this->PermissionType = ClassRegistry::init('PermissionType');
		$this->PermissionCache = ClassRegistry::init('PermissionCache');
		$this->UserCategoryPermission = ClassRegistry::init('UserCategoryPermission');

		$this->Category->Behaviors->disable('Permissionable');
		$this->Resource->Behaviors->disable('Permissionable');
	}

	public function testMysqlFunctionGetGroupCategoryPermission()
	{
		$matrix = PermissionMatrix::importCsv(TESTS . '/Data/categories_groups_permissions.csv');

		foreach ($matrix as $categoryAlias => $groupsPermissions) {
			$categoryId = Common::uuid('category.id.' . $categoryAlias);

			foreach ($groupsPermissions as $groupAlias => $groupPermission) {
				$groupId = Common::uuid('group.id.' . $groupAlias);

				// Get the permission
				$query = "SELECT getGroupCategoryPermission('{$groupId}', '{$categoryId}') AS permid";
				$res = $this->Permission->query($query);

				// Load the permission
				$permission = $this->Permission->findById($res[0][0]['permid']);
				$permissionType = $permission ? $permission['Permission']['type'] : 0;

				$this->assertTrue(
					$groupPermission == $permissionType,
					"permissions for group {$groupAlias}({$groupId}) and category {$categoryAlias}({$categoryId}) returned {$permissionType}
				but should have returned {$groupPermission}"
				);
			}
		}
	}

	public function testMysqlFunctionGetGroupResourcePermission()
	{
		$matrix = PermissionMatrix::importCsv(TESTS . '/Data/resources_groups_permissions.csv');

		foreach ($matrix as $resourceAlias => $groupsPermissions) {
			$resourceId = Common::uuid('resource.id.' . $resourceAlias);

			foreach ($groupsPermissions as $groupAlias => $groupPermission) {
				$groupId = Common::uuid('group.id.' . $groupAlias);

				// Get the permission
				$query = "SELECT getGroupResourcePermission('{$groupId}', '{$resourceId}') AS permid";
				$res = $this->Permission->query($query);

				// Load the permission
				$permission = $this->Permission->findById($res[0][0]['permid']);
				$permissionType = $permission ? $permission['Permission']['type'] : 0;

				$this->assertTrue(
					$groupPermission == $permissionType,
					"permissions for group {$groupAlias}({$groupId}) and resource {$resourceAlias}({$resourceId}) returned {$permissionType}
				but should have returned {$groupPermission}"
				);
			}
		}
	}

	public function testMysqlFunctionGetUserCategoryPermission()
	{
		$matrix = PermissionMatrix::importCsv(TESTS . '/Data/categories_users_permissions.csv');

		foreach ($matrix as $categoryAlias => $usersPermissions) {
			$categoryId = Common::uuid('category.id.' . $categoryAlias);

			foreach ($usersPermissions as $userAlias => $userPermission) {
				$userId = Common::uuid('user.id.' . $userAlias);

				// Get the permission
				$query = "SELECT getUserCategoryPermission('{$userId}', '{$categoryId}') AS permid";
				$res = $this->Permission->query($query);

				// Load the permission
				$permission = $this->Permission->findById($res[0][0]['permid']);
				$permissionType = $permission ? $permission['Permission']['type'] : 0;

				$this->assertTrue(
					$userPermission == $permissionType,
					"permissions for user {$userAlias}({$userId}) and category {$categoryAlias}({$categoryId}) returned {$permissionType}
				but should have returned {$userPermission}"
				);
			}
		}
	}

	public function testMysqlFunctionGetUserResourcePermission()
	{
		$matrix = PermissionMatrix::importCsv(TESTS . '/Data/resources_users_permissions.csv');

		foreach ($matrix as $resourceAlias => $usersPermissions) {
			$resourceId = Common::uuid('resource.id.' . $resourceAlias);

			foreach ($usersPermissions as $userAlias => $userPermission) {
				$userId = Common::uuid('user.id.' . $userAlias);

				// Get the permission
				$query = "SELECT getUserResourcePermission('{$userId}', '{$resourceId}') AS permid";
				$res = $this->Permission->query($query);

				// Load the permission
				$permission = $this->Permission->findById($res[0][0]['permid']);
				$permissionType = $permission ? $permission['Permission']['type'] : 0;

				$this->assertTrue(
					$userPermission == $permissionType,
					"permissions for user {$userAlias}({$userId}) and resource {$resourceAlias}({$resourceId}) returned {$permissionType}
				but should have returned {$userPermission}"
				);
			}

		}
	}
}
