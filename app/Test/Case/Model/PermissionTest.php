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

	/*public function testMysqlFunctionGetGroupCategoryPermission() {
		$testcases = array(
			array(
				'aconame' => 'administration',
				'groupname' => 'management',
				'result' => '16'
			),
			array(
				'aconame' => 'accounts',
				'groupname' => 'management',
				'result' => '16'
			),
			array(
				'aconame' => 'cp-project1',
				'groupname' => 'management',
				'result' => '16'
			),
			array(
				'aconame' => 'cp-project1',
				'groupname' => 'developers',
				'result' => null
			),
			array(
				'aconame' => 'projects',
				'groupname' => 'developers team leads',
				'result' => '8'
			)
		);
		foreach ($testcases as $testcase) {
			$group = $this->Group->findByName($testcase['groupname']);
			$category = $this->Category->findByName($testcase['aconame']);
			//echo "===== trying with group {$group['Group']['name']} and category {$category['Category']['name']} grpid:{$group['Group']['id']} catid:{$category['Category']['id']} =====";
			$query = "SELECT `passbolt`.getGroupCategoryPermission('{$group['Group']['id']}', '{$category['Category']['id']}') AS permid";
			$res = $this->Permission->query($query);
			$permission = $this->Permission->findById($res[0][0]['permid']);
			if($permission)
				$permission = $permission['Permission']['permission_detail_id'];
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
				'result' => '16'
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
				'result' => '8'
			),
			// Test multi parents for resource
			array(
				'aconame' => 'shared resource',
				'groupname' => 'company a',
				'result' => '8'
			)
		);
		foreach ($testcases as $testcase) {
			$group = $this->Group->findByName($testcase['groupname']);
			$resource = $this->Resource->findByName($testcase['aconame']);
			//echo "<br/>===== trying with group {$group['Group']['name']} and category {$resource['Resource']['name']} grpid:{$group['Group']['id']} catid:{$resource['Resource']['id']} =====<br/>";
			$query = "SELECT `passbolt`.getGroupResourcePermission('{$group['Group']['id']}', '{$resource['Resource']['id']}') AS permid";
			$res = $this->Permission->query($query);
			$permission = $this->Permission->findById($res[0][0]['permid']);
			if($permission)
				$permission = $permission['Permission']['permission_detail_id'];
			else
				$permission = null;

			$this->assertTrue(
				$testcase['result'] == $permission,
				"permissions for group {$group['Group']['name']} and resource {$resource['Resource']['name']} returned {$permission}
				but should have returned {$testcase['result']}"
			);
		}
	}*/

	// TODO : manage case where user is owner of the resource
}