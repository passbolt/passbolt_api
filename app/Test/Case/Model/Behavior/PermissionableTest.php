<?php
/**
 * Permissionable Behavior Test
 *
 * @copyright (c) 2016-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('User', 'Model');
App::uses('Resource', 'Model');
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
		'app.secret',
		'app.file_storage',
		'app.group',
		'app.groups_user',
		'app.permissions_type',
		'app.permission',
		'app.permission_view',
		'core.cakeSession'
	);

	public function setUp() {
		parent::setUp();
		$this->User = ClassRegistry::init('User');
		$this->Group = ClassRegistry::init('Group');
		$this->Resource = ClassRegistry::init('Resource');
	}

	public function tearDown() {
		parent::tearDown();
		// Make sure there is no session active after each test
		$this->User->setInactive();
	}

	public function testGetResourcesPermission() {
		$matrix = PermissionMatrix::importCsv(TESTS . '/Data/view_users_resources_permissions.csv');

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

	public function testUserIsAuthorizedToPerformOperationOnResource() {
		$matrix = PermissionMatrix::importCsv(TESTS . '/Data/view_users_resources_permissions.csv');

		foreach ($matrix as $resourceAlias => $usersPermissions) {
			$resourceId = Common::uuid('resource.id.' . $resourceAlias);

			foreach ($usersPermissions as $userAlias => $userPermission) {
				$userId = Common::uuid('user.id.' . $userAlias);
				$user = $this->User->findById($userId);
				$this->User->setActive($user);

				foreach (PermissionType::getAll() as $permissionName => $permissionType) {
					if ($permissionType == PermissionType::DENY) {
						$isAuthorized = true;
					} else {
						$isAuthorized = $this->Resource->isAuthorized($resourceId, $permissionType, $userId, 'User');
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

	public function testAutomaticResourceFindFiltering() {
		$matrix = PermissionMatrix::importCsv(TESTS . '/Data/view_users_resources_permissions.csv');

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

	public function testGetAuthorizedUser() {
		// As Ada
		$user = $this->User->findById(Common::uuid('user.id.ada'));
		$this->User->setActive($user);

		// Expected list of authorized users
		$expected = [
			Common::uuid('user.id.ada'),
			Common::uuid('user.id.betty'),
			Common::uuid('user.id.dame'),
			Common::uuid('user.id.edith'),
		];
		$conditions = ['conditions' => ['name' => 'debian'], 'contain' => ['Secret']];
		$resource = $this->Resource->find('first', $conditions);
		$permsUsers = $this->Resource->getAuthorizedUsers($resource['Resource']['id']);
		$permsUsers = Hash::extract($permsUsers, '{n}.User.id');
		$this->assertEquals(sort($expected), sort($permsUsers));
	}

	public function testGetUsersWithAPermissionSet() {
		// As Ada
		$user = $this->User->findById(Common::uuid('user.id.ada'));
		$this->User->setActive($user);

		// Expected list of users with a permission set
		$expected = [
			Common::uuid('user.id.ada'),
			Common::uuid('user.id.betty'),
			Common::uuid('user.id.dame'),
			Common::uuid('user.id.edith'),
		];
		$conditions = ['conditions' => ['name' => 'debian'], 'contain' => ['Secret']];
		$resource = $this->Resource->find('first', $conditions);
		$permsUsers = $this->Resource->getUsersWithAPermissionSet($resource['Resource']['id']);
		$permsUsers = Hash::extract($permsUsers, '{n}.User.id');
		$this->assertEquals(sort($expected), sort($permsUsers));
	}
}
