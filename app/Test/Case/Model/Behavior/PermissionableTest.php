<?php
/**
 * Permissionable Behavior Test
 *
 * @copyright    Copyright 2014, Passbolt.com
 * @package      app.Test.Case.Model.PermossionableTest
 * @since        version 2.14.6
 * @license      http://www.passbolt.com/license
 */
App::uses('Category', 'Model');
App::uses('User', 'Model');
App::uses('Resource', 'Model');
App::uses('Category', 'Model');
App::uses('PermissionType', 'Model');
App::uses('PermissionMatrix', 'Test/Data');

class PermissionnableTest extends CakeTestCase {

	//public $autoFixtures = true;

	public $fixtures = array(
		'app.resource',
		'app.user',
		'app.role',
		'app.profile',
		'app.gpgkey',
		'app.file_storage',
		'app.group',
		'app.groups_user',
		'app.category_type',
		'app.category',
		'app.categories_resource',
		'app.permissions_type',
		'app.permission',
		'app.permission_view',
		'core.cakeSession'
	);

	public function setUp() {
		parent::setUp();
		$this->User = ClassRegistry::init('User');
		$this->Group = ClassRegistry::init('Group');
		$this->Category = ClassRegistry::init('Category');
		$this->Resource = ClassRegistry::init('Resource');
	}

	public function testGetResourcesPermission() {
		$matrix = PermissionMatrix::importCsv(TESTS . '/Data/resources_users_permissions.csv');

		foreach ($matrix as $resourceAlias => $usersPermissions) {
			$resourceId = Common::uuid('resource.id.' . $resourceAlias);

			foreach ($usersPermissions as $userAlias => $userPermission) {
				$userId = Common::uuid('user.id.' . $userAlias);
				$user = $this->User->findById($userId);
				$this->User->setActive($user);

				$permission = $this->Resource->getPermission($resourceId, $userId, 'User');
				$privilege = 0;
				if (isset($permission['Permission']['type'])) {
					$privilege = $permission['Permission']['type'];
				}

				$this->assertTrue($userPermission == $privilege,
					"permissions for the User {$userAlias} and Resource {$resourceAlias} returned {$privilege} but should have returned {$userPermission}"
				);
			}
		}
	}

	public function testGetCategoriesPermission() {
		$matrix = PermissionMatrix::importCsv(TESTS . '/Data/categories_users_permissions.csv');

		foreach ($matrix as $categoryAlias => $usersPermissions) {
			$categoryId = Common::uuid('category.id.' . $categoryAlias);

			foreach ($usersPermissions as $userAlias => $userPermission) {
				$userId = Common::uuid('user.id.' . $userAlias);
				$user = $this->User->findById($userId);
				$this->User->setActive($user);

				$permission = $this->Category->getPermission($categoryId, $userId, 'User');
				$privilege = 0;
				if (isset($permission['Permission']['type'])) {
					$privilege = $permission['Permission']['type'];
				}

				$this->assertTrue($userPermission == $privilege,
					"permissions for the User {$userAlias} and Category {$categoryAlias} returned {$privilege} but should have returned {$userPermission}"
				);
			}
		}
	}

	public function testUserIsAuthorizedToPerformOperationOnResource() {
		$matrix = PermissionMatrix::importCsv(TESTS . '/Data/resources_users_permissions.csv');

		foreach ($matrix as $resourceAlias => $usersPermissions) {
			$resourceId = Common::uuid('resource.id.' . $resourceAlias);

			foreach ($usersPermissions as $userAlias => $userPermission) {
				$userId = Common::uuid('user.id.' . $userAlias);
				$user = $this->User->findById($userId);
				$this->User->setActive($user);

				foreach (PermissionType::getAll() as $permissionName => $permissionType) {
					$isAuthorized = $this->Resource->isAuthorized($resourceId, $permissionType, $userId, 'User');
					if ($permissionType == PermissionType::DENY) {
						$isAuthorized = true;
					}
					$operationAllowed = $userPermission >= $permissionType;
					$not = $operationAllowed ? '' : 'not ';
					$this->assertTrue($operationAllowed == $isAuthorized,
						"User {$userAlias} should {$not}be authorized to {$permissionName} the Resource {$resourceAlias}"
					);
				}
			}
		}
	}

	public function testUserIsAuthorizedToPerformOperationOnCategory() {
		$matrix = PermissionMatrix::importCsv(TESTS . '/Data/categories_users_permissions.csv');

		foreach ($matrix as $categoryAlias => $usersPermissions) {
			$categoryId = Common::uuid('category.id.' . $categoryAlias);

			foreach ($usersPermissions as $userAlias => $userPermission) {
				$userId = Common::uuid('user.id.' . $userAlias);
				$user = $this->User->findById($userId);
				$this->User->setActive($user);

				foreach (PermissionType::getAll() as $permissionName => $permissionType) {
					$isAuthorized = $this->Category->isAuthorized($categoryId, $permissionType, $userId, 'User');
					if ($permissionType == PermissionType::DENY) {
						$isAuthorized = true;
					}
					$operationAllowed = $userPermission >= $permissionType;
					$not = $operationAllowed ? '' : 'not ';
					$this->assertTrue($operationAllowed == $isAuthorized,
						"User {$userAlias} should {$not}be authorized to {$permissionName} the Category {$categoryAlias}"
					);
				}
			}
		}
	}

	public function testAutomaticResourceFindFiltering() {
		$matrix = PermissionMatrix::importCsv(TESTS . '/Data/resources_users_permissions.csv');

		foreach ($matrix as $resourceAlias => $usersPermissions) {
			$resourceId = Common::uuid('resource.id.' . $resourceAlias);

			foreach ($usersPermissions as $userAlias => $userPermission) {
				$userId = Common::uuid('user.id.' . $userAlias);
				$user = $this->User->findById($userId);
				$this->User->setActive($user);

				$resource = $this->Resource->findById($resourceId);
				$isAuthorized = $userPermission >= PermissionType::READ;
				$not = $isAuthorized ? '' : 'not ';
				$this->assertTrue($isAuthorized == !empty($resource),
					"User {$userAlias} should {$not}be authorized to read the Resource {$resourceAlias}"
				);
			}
		}
	}

	public function testAutomaticCategoryFindFiltering() {
		$matrix = PermissionMatrix::importCsv(TESTS . '/Data/categories_users_permissions.csv');

		foreach ($matrix as $categoryAlias => $usersPermissions) {
			$categoryId = Common::uuid('category.id.' . $categoryAlias);

			foreach ($usersPermissions as $userAlias => $userPermission) {
				$userId = Common::uuid('user.id.' . $userAlias);
				$user = $this->User->findById($userId);
				$this->User->setActive($user);

				$category = $this->Category->findById($categoryId);
				$isAuthorized = $userPermission >= PermissionType::READ;
				$not = $isAuthorized ? '' : 'not ';
				$this->assertTrue($isAuthorized == !empty($category),
					"User {$userAlias} should {$not}be authorized to read the Category {$categoryAlias}"
				);
			}
		}
	}

}
