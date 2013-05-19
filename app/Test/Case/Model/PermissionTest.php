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

if (!class_exists('CakeSession')) {
	require CAKE . 'Model/Datasource/CakeSession.php';
}

class PermissionTest extends CakeTestCase {

	public $fixtures = array('app.resource', 'app.user', 'app.role', 'app.group', 'app.groupsUser', 'app.categoryType', 'app.category', 'app.categoriesResource', 'app.permissionsType', 'app.permission', 'app.permission_view');

	public function setUp() {
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

	public function testMysqlFunctionGetGroupCategoryPermission() {
		$testcases = array(
			array(
				'aconame' => 'administration',
				'groupname' => 'management',
				'result' => PermissionType::ADMIN
			),
			array(
				'aconame' => 'accounts',
				'groupname' => 'management',
				'result' => PermissionType::ADMIN
			),
			array(
				'aconame' => 'cp-project1',
				'groupname' => 'management',
				'result' => PermissionType::ADMIN
			),
			array(
				'aconame' => 'cp-project1',
				'groupname' => 'developers',
				'result' => null
			),
			array(
				'aconame' => 'projects',
				'groupname' => 'developers team leads',
				'result' => PermissionType::UPDATE
			),
		);
		foreach ($testcases as $testcase) {
			$group = $this->Group->findByName($testcase['groupname']);
			$category = $this->Category->findByName($testcase['aconame']);
			//echo "===== trying with group {$group['Group']['name']} and category {$category['Category']['name']} grpid:{$group['Group']['id']} catid:{$category['Category']['id']} =====";
			$query = "SELECT getGroupCategoryPermission('{$group['Group']['id']}', '{$category['Category']['id']}') AS permid";
			$res = $this->Permission->query($query);
			$permission = $this->Permission->findById($res[0][0]['permid']);
			if($permission)
				$permission = $permission['Permission']['type'];
			else
				$permission = null;

			$this->assertTrue( $testcase['result'] == $permission,
				"permissions for group {$group['Group']['name']} and category {$category['Category']['name']} returned {$permission}
				but should have returned {$testcase['result']}"
			);
		}
	}

	public function testMysqlFunctionGetGroupResourcePermission() {
		$testcases = array(
			array(
				'aconame' => 'bank password',
				'groupname' => 'management',
				'result' => PermissionType::ADMIN
			),
			array(
				'aconame' => 'salesforce account',
				'groupname' => 'developers',
				'result' => null
			),
      // Test direct permission DENY
      array(
        'aconame' => 'facebook account',
        'groupname' => 'human resources',
        'result' => PermissionType::DENY
      ),
			// Test direct permission Update
			array(
				'aconame' => 'salesforce account',
				'groupname' => 'human resources',
				'result' => PermissionType::UPDATE
			),
			// Test multi parents for resource
			array(
				'aconame' => 'shared resource',
				'groupname' => 'company a',
				'result' => PermissionType::UPDATE
			)
		);
		foreach ($testcases as $testcase) {
			$group = $this->Group->findByName($testcase['groupname']);
			$resource = $this->Resource->findByName($testcase['aconame']);
			//echo "<br/>===== trying with group {$group['Group']['name']} and category {$resource['Resource']['name']} grpid:{$group['Group']['id']} catid:{$resource['Resource']['id']} =====<br/>";
			$query = "SELECT getGroupResourcePermission('{$group['Group']['id']}', '{$resource['Resource']['id']}') AS permid";
			$res = $this->Permission->query($query);
			$permission = $this->Permission->findById($res[0][0]['permid']);
			if($permission)
				$permission = $permission['Permission']['type'];
			else
				$permission = null;

			$this->assertTrue(
				$testcase['result'] == $permission,
				"permissions for group {$group['Group']['name']} and resource {$resource['Resource']['name']} returned {$permission}
				but should have returned {$testcase['result']}"
			);
		}
	}

	public function testMysqlFunctionGetUserCategoryPermission() {
		$testcases = array(
			array(
				'aconame' => 'marketing',
				'username' => 'dark.vador@passbolt.com',
				'result' => PermissionType::ADMIN
			),
			array(
				'aconame' => 'd-project2',
				'username' => 'cedric@passbolt.com',
				'result' => PermissionType::READ
			),
			array(
				'aconame' => 'cp-project1',
				'username' => 'remy@passbolt.com',
				'result' => PermissionType::ADMIN
			),
			array(
				'aconame' => 'cp-project1',
				'username' => 'manager.nogroup@passbolt.com',
				'result' => PermissionType::ADMIN
			),
			array(
				'aconame' => 'cp-project2',
				'username' => 'remy@passbolt.com',
				'result' => PermissionType::UPDATE
			),
			// Test null
			array(
				'aconame' => 'marketing',
				'username' => 'cedric@passbolt.com',
				'result' => null
			),
			array(
				'aconame' => 'cp-project2',
				'username' => 'frank@passbolt.com',
				'result' => PermissionType::DENY
			),
		);
		foreach ($testcases as $testcase) {
			$user = $this->User->findByUsername($testcase['username']);
			$category = $this->Category->findByName($testcase['aconame']);
			//echo "<br/>===== trying with group {$group['Group']['name']} and category {$resource['Resource']['name']} grpid:{$group['Group']['id']} catid:{$resource['Resource']['id']} =====<br/>";
			$query = "SELECT getUserCategoryPermission('{$user['User']['id']}', '{$category['Category']['id']}') AS permid";
			$res = $this->Permission->query($query);
			$permission = $this->Permission->findById($res[0][0]['permid']);
			if($permission)
				$permission = $permission['Permission']['type'];
			else
				$permission = null;

			$this->assertTrue(
				$testcase['result'] == $permission,
				"permissions for user {$user['User']['username']} and category {$category['Category']['name']} returned {$permission}
				but should have returned {$testcase['result']}"
			);
		}
	}

	public function testMysqlFunctionGetUserResourcePermission() {
		$testcases = array(
      array(
        'aconame' => 'facebook account',
        'username' => 'dark.vador@passbolt.com',
        'result' => PermissionType::ADMIN
      ),
      array(
        'aconame' => 'facebook account',
        'username' => 'myriam@passbolt.com',
        'result' => PermissionType::DENY
      ),
			array(
				'aconame' => 'dp1-pwd1',
				'username' => 'cedric@passbolt.com',
				'result' => PermissionType::READ
			),
			array(
				'aconame' => 'cpp1-pwd1',
				'username' => 'remy@passbolt.com',
				'result' => PermissionType::ADMIN
			),
			array(
				'aconame' => 'cpp1-pwd1',
				'username' => 'manager.nogroup@passbolt.com',
				'result' => PermissionType::ADMIN
			),
			array(
				'aconame' => 'cpp2-pwd1',
				'username' => 'remy@passbolt.com',
				'result' => PermissionType::UPDATE
			),
			// Test null
			array(
				'aconame' => 'shared resource',
				'username' => 'a-usr1@companya.com',
				'result' => PermissionType::UPDATE
			),
			array(
				'aconame' => 'dp2-pwd1',
				'username' => 'cedric@passbolt.com',
				'result' => PermissionType::DENY
			)
		);
		foreach ($testcases as $testcase) {
			$user = $this->User->findByUsername($testcase['username']);
			$resource = $this->Resource->findByName($testcase['aconame']);
			//echo "<br/>===== trying with group {$group['Group']['name']} and category {$resource['Resource']['name']} grpid:{$group['Group']['id']} catid:{$resource['Resource']['id']} =====<br/>";
			$query = "SELECT getUserResourcePermission('{$user['User']['id']}', '{$resource['Resource']['id']}') AS permid";
			$res = $this->Permission->query($query);
			$permission = $this->Permission->findById($res[0][0]['permid']);
			if($permission)
				$permission = $permission['Permission']['type'];
			else
				$permission = null;

			$this->assertTrue(
				$testcase['result'] == $permission,
				"permissions for user {$user['User']['username']} and resource {$resource['Resource']['name']} returned {$permission}
				but should have returned {$testcase['result']}"
			);
		}
	}
}