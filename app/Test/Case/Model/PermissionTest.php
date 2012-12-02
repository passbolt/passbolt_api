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

// TODO : stop using defaut schema and use fixtures instead
class PermissionTest extends CakeTestCase {

	//public $fixtures = array('app.resource', 'app.user', 'app.role');

	public function setUp() {
		parent::setUp();
		$this->Permission = ClassRegistry::init('Permission');
		$this->Permission->useDbConfig = 'default';
		$this->User = ClassRegistry::init('User');
		$this->User->useDbConfig = 'default';
		$this->Category = ClassRegistry::init('Category');
		$this->Category->useDbConfig = 'default';
		$this->Resource = ClassRegistry::init('Resource');
		$this->Resource->useDbConfig = 'default';
		$this->Group = ClassRegistry::init('Group');
		$this->Group->useDbConfig = 'default';
		$this->PermissionDetail = ClassRegistry::init('PermissionDetail');
		$this->PermissionDetail->useDbConfig = 'default';
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
			)
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
			// Test direct permission
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
				'username' => 'dark.vador@test.com',
				'result' => PermissionType::ADMIN
			),
			array(
				'aconame' => 'd-project2',
				'username' => 'cedric.alfonsi@test.com',
				'result' => PermissionType::READ
			),
			array(
				'aconame' => 'cp-project1',
				'username' => 'remy.bertot@test.com',
				'result' => PermissionType::ADMIN
			),
			array(
				'aconame' => 'cp-project2',
				'username' => 'remy.bertot@test.com',
				'result' => PermissionType::UPDATE
			),
			// Test null
			array(
				'aconame' => 'marketing',
				'username' => 'cedric.alfonsi@test.com',
				'result' => null
			)
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
				'username' => 'dark.vador@test.com',
				'result' => PermissionType::ADMIN
			),
			array(
				'aconame' => 'dp1-pwd1',
				'username' => 'cedric.alfonsi@test.com',
				'result' => PermissionType::READ
			),
			array(
				'aconame' => 'cpp1-pwd1',
				'username' => 'remy.bertot@test.com',
				'result' => PermissionType::ADMIN
			),
			array(
				'aconame' => 'cpp2-pwd1',
				'username' => 'remy.bertot@test.com',
				'result' => PermissionType::UPDATE
			),
			// Test null
			array(
				'aconame' => 'shared resource',
				'username' => 'a-user1@test.com',
				'result' => PermissionType::UPDATE
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