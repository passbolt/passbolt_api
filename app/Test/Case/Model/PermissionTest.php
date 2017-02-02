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

		// @TODO CHECK THAT
		$this->Resource->Behaviors->disable('Permissionable');
	}

/******************************************************
 * VALIDATION TESTS
 ******************************************************/

	public function testValidateAco() {
		$matrix = array(
			false => array('User', 'Group'),
			true => array('Resource'),
		);
		foreach ($matrix as $result => $testcases) {
			foreach ($testcases as $testcase) {
				$permission = array('Permission' => array('aco' => $testcase));
				$this->Permission->set($permission);
				if ($result) {
					$msg = 'validation with "' . $testcase . '" should validate';
				} else {
					$msg = 'validation with "' . $testcase . '" should not validate';
				}
				$this->assertEquals($this->Permission->validates(array('fieldList' => array('aco'))), $result, $msg);
			}
		}
	}

	public function testValidateAcoForeignKey() {
		$matrix = array(
			false => [
				array('aco' => 'Resource', 'aco_foreign_key' => 'not-valid-uuid'),
				array('aco' => 'Resource', 'aco_foreign_key' => Common::uuid('not-valid-reference')),
				array('aco' => 'WrongAcoModel', 'aco_foreign_key' => Common::uuid('resource.id.debian')),
				array('aco_foreign_key' => Common::uuid('resource.id.debian')),
			],
			true => array(
				array('aco' => 'Resource', 'aco_foreign_key' => Common::uuid('resource.id.debian')),
			)
		);
		foreach ($matrix as $result => $testcases) {
			foreach ($testcases as $testcase) {
				$permission = array('Permission' => $testcase);
				$this->Permission->set($permission);
				if ($result) {
					$msg = 'validation with "' . json_encode($testcase) . '" should validate';
				} else {
					$msg = 'validation with "' . json_encode($testcase) . '" should not validate';
				}
				$this->assertEquals($result, $this->Permission->validates(array('fieldList' => array('aco_foreign_key'))), $msg);
			}
		}
	}

	public function testValidateAro() {
		$matrix = array(
			false => array('Resource'),
			true => array('User', 'Group')
		);
		foreach ($matrix as $result => $testcases) {
			foreach ($testcases as $testcase) {
				$permission = array('Permission' => array('aro' => $testcase));
				$this->Permission->set($permission);
				if ($result) {
					$msg = 'validation with "' . $testcase . '" should validate';
				} else {
					$msg = 'validation with "' . $testcase . '" should not validate';
				}
				$this->assertEquals($this->Permission->validates(array('fieldList' => array('aro'))), $result, $msg);
			}
		}
	}

	public function testValidateAroForeignKey() {
		$matrix = array(
			false => [
				array('aro' => 'Resource', 'aro_foreign_key' => 'not-valid-uuid'),
				array('aro' => 'Resource', 'aro_foreign_key' => Common::uuid('not-valid-reference')),
				array('aro' => 'WrongAcoModel', 'aro_foreign_key' => Common::uuid('user.id.ada')),
				array('aro_foreign_key' => Common::uuid('user.id.ada')),
			],
			true => array(
				array('aro' => 'User', 'aro_foreign_key' => Common::uuid('user.id.ada')),
			)
		);
		foreach ($matrix as $result => $testcases) {
			foreach ($testcases as $testcase) {
				$permission = array('Permission' => $testcase);
				$this->Permission->set($permission);
				if ($result) {
					$msg = 'validation with "' . json_encode($testcase) . '" should validate';
				} else {
					$msg = 'validation with "' . json_encode($testcase) . '" should not validate';
				}
				$this->assertEquals($result, $this->Permission->validates(array('fieldList' => array('aro_foreign_key'))), $msg);
			}
		}
	}

	public function testValidatePermissionType() {
		$matrix = array(
			false => [
				'wrong-permission-type',
			],
			true => array(
				PermissionType::CREATE,
				PermissionType::READ,
				PermissionType::UPDATE,
				PermissionType::OWNER,
			)
		);
		foreach ($matrix as $result => $testcases) {
			foreach ($testcases as $testcase) {
				$permission = array('Permission' => array('type' => $testcase));
				$this->Permission->set($permission);
				if ($result) {
					$msg = 'validation with "' . $testcase . '" should validate';
				} else {
					$msg = 'validation with "' . $testcase . '" should not validate';
				}
				$this->assertEquals($result, $this->Permission->validates(array('fieldList' => array('type'))), $msg);
			}
		}
	}

/******************************************************
 * VALIDATION FUNCTIONS TESTS, some of them have been designed to be used by the validate
 * function as well as external calls.
 ******************************************************/

	public function testIsValidateAco() {
		$matrix = array(
			false => array('User', 'Group'),
			true => array('Resource'),
		);
		foreach ($matrix as $result => $testcases) {
			foreach ($testcases as $testcase) {
				if ($result) {
					$msg = 'validation with "' . $testcase . '" should validate';
				} else {
					$msg = 'validation with "' . $testcase . '" should not validate';
				}
				$this->assertEquals($result, $this->Permission->isValidAco($testcase), $msg);
			}
		}
	}

	public function testIsValidAcoForeignKey() {
		$matrix = array(
			false => [
				array('aco' => 'Resource', 'aco_foreign_key' => 'not-valid-uuid'),
				array('aco' => 'Resource', 'aco_foreign_key' => Common::uuid('not-valid-reference')),
				array('aco' => 'WrongAcoModel', 'aco_foreign_key' => Common::uuid('resource.id.debian')),
				array('aco' => null, 'aco_foreign_key' => Common::uuid('resource.id.debian')),
			],
			true => array(
				array('aco' => 'Resource', 'aco_foreign_key' => Common::uuid('resource.id.debian')),
			)
		);
		foreach ($matrix as $result => $testcases) {
			foreach ($testcases as $testcase) {
				if ($result) {
					$msg = 'validation with "' . json_encode($testcase) . '" should validate';
				} else {
					$msg = 'validation with "' . json_encode($testcase) . '" should not validate';
				}
				$this->assertEquals($this->Permission->isValidAcoForeignKey($testcase['aco'], $testcase['aco_foreign_key']), $result, $msg);
			}
		}
	}

	public function testIsValidateAro() {
		$matrix = array(
			false => array('Resource'),
			true => array('User', 'Group')
		);
		foreach ($matrix as $result => $testcases) {
			foreach ($testcases as $testcase) {
				if ($result) {
					$msg = 'validation with "' . $testcase . '" should validate';
				} else {
					$msg = 'validation with "' . $testcase . '" should not validate';
				}
				$this->assertEquals($result, $this->Permission->isValidAro($testcase), $msg);
			}
		}
	}

	public function testIsValidAroForeignKey() {
		$matrix = array(
			false => [
				array('aro' => 'Resource', 'aro_foreign_key' => 'not-valid-uuid'),
				array('aro' => 'Resource', 'aro_foreign_key' => Common::uuid('not-valid-reference')),
				array('aro' => 'WrongAcoModel', 'aro_foreign_key' => Common::uuid('user.id.ada')),
				array('aro' => null, 'aro_foreign_key' => Common::uuid('user.id.ada')),
			],
			true => array(
				array('aro' => 'User', 'aro_foreign_key' => Common::uuid('user.id.ada')),
			)
		);
		foreach ($matrix as $result => $testcases) {
			foreach ($testcases as $testcase) {
				if ($result) {
					$msg = 'validation with "' . json_encode($testcase) . '" should validate';
				} else {
					$msg = 'validation with "' . json_encode($testcase) . '" should not validate';
				}
				$this->assertEquals($this->Permission->isValidAroForeignKey($testcase['aro'], $testcase['aro_foreign_key']), $result, $msg);
			}
		}
	}

	public function testIsValidPermissionType() {
		$matrix = array(
			false => [
				'wrong-permission-type',
			],
			true => array(
				PermissionType::CREATE,
				PermissionType::READ,
				PermissionType::UPDATE,
				PermissionType::OWNER,
			)
		);
		foreach ($matrix as $result => $testcases) {
			foreach ($testcases as $testcase) {
				if ($result) {
					$msg = 'validation with "' . $testcase . '" should validate';
				} else {
					$msg = 'validation with "' . $testcase . '" should not validate';
				}
				$this->assertEquals($result, $this->Permission->isValidPermissionType($testcase), $msg);
			}
		}
	}

	public function testIsUniquePermission() {
		$matrix = array(
			false => [
				array('aco' => 'Resource', 'aco_foreign_key' => Common::uuid('resource.id.apache'),
					'aro' => 'User', 'aro_foreign_key' => Common::uuid('user.id.ada'), 'type' => PermissionType::OWNER)
			],
			true => array(
				array('aco' => 'Resource', 'aco_foreign_key' => Common::uuid('resource.id.apache'),
					'aro' => 'User', 'aro_foreign_key' => Common::uuid('user.id.ada'), 'type' => PermissionType::UPDATE)
			)
		);
		foreach ($matrix as $result => $testcases) {
			foreach ($testcases as $testcase) {
				if ($result) {
					$msg = 'validation with "' . json_encode($testcase) . '" should validate';
				} else {
					$msg = 'validation with "' . json_encode($testcase) . '" should not validate';
				}
				$this->assertEquals($result, $this->Permission->isUniquePermission($testcase['aco'], $testcase['aco_foreign_key'], $testcase['aro'], $testcase['aro_foreign_key'], $testcase['type']), $msg);
			}
		}
	}

/******************************************************
 *  TESTS
 ******************************************************/

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
