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

if (!class_exists('CakeSession')) {
	require CAKE . 'Model/Datasource/CakeSession.php';
}

class PermissionTest extends CakeTestCase {

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
		$permissionsMatrix = require (dirname(__FILE__) . DS . '../../Data/permissionsMatrix.php');
		foreach ($permissionsMatrix['Group']['Category'] as $testcase) {
			// Get group & category
			$group = $this->Group->findByName($testcase['aroname']);
			$category = $this->Category->findByName($testcase['aconame']);
			// Get the permission
			$query = "SELECT getGroupCategoryPermission('{$group['Group']['id']}', '{$category['Category']['id']}') AS permid";
			$res = $this->Permission->query($query);
			// Load the permission
			$permission = $this->Permission->findById($res[0][0]['permid']);

			$permission = $permission ? $permission['Permission']['type'] : null;
			$this->assertTrue($testcase['result'] == $permission,
				"permissions for group {$group['Group']['name']} and category {$category['Category']['name']} returned {$permission} but should have returned {$testcase['result']}"
			);
		}
	}

	public function testMysqlFunctionGetGroupResourcePermission() {
		$permissionsMatrix = require (dirname(__FILE__) . DS . '../../Data/permissionsMatrix.php');
		foreach ($permissionsMatrix['Group']['Resource'] as $testcase) {
			// Get group & resource
			$group = $this->Group->findByName($testcase['aroname']);
			$resource = $this->Resource->findByName($testcase['aconame']);
			// Get the permission
			$query = "SELECT getGroupResourcePermission('{$group['Group']['id']}', '{$resource['Resource']['id']}') AS permid";
			$res = $this->Permission->query($query);
			// Load the permission
			$permission = $this->Permission->findById($res[0][0]['permid']);

			$permission = $permission ? $permission['Permission']['type'] : null;
			$this->assertTrue(
				$testcase['result'] == $permission,
				"permissions for group {$group['Group']['name']} and resource {$resource['Resource']['name']} returned {$permission} but should have returned {$testcase['result']}"
			);
		}
	}

	public function testMysqlFunctionGetUserCategoryPermission() {
		$permissionsMatrix = require (dirname(__FILE__) . DS . '../../Data/permissionsMatrix.php');
		foreach ($permissionsMatrix['User']['Category'] as $testcase) {
			// Get user & category
			$user = $this->User->findByUsername($testcase['aroname']);
			$category = $this->Category->findByName($testcase['aconame']);
			// Get the permission
			$query = "SELECT getUserCategoryPermission('{$user['User']['id']}', '{$category['Category']['id']}') AS permid";
			$res = $this->Permission->query($query);
			// Load the permission
			$permission = $this->Permission->findById($res[0][0]['permid']);

			$permission = $permission ? $permission['Permission']['type'] : null;
			$this->assertTrue(
				$testcase['result'] == $permission,
				"permissions for user {$user['User']['username']} and category {$category['Category']['name']} returned {$permission} but should have returned {$testcase['result']}"
			);
		}
	}

	public function testMysqlFunctionGetUserResourcePermission() {
		$permissionsMatrix = require (dirname(__FILE__) . DS . '../../Data/permissionsMatrix.php');
		foreach ($permissionsMatrix['User']['Resource'] as $testcase) {
			// Get group & resource
			$user = $this->User->findByUsername($testcase['aroname']);
			$resource = $this->Resource->findByName($testcase['aconame']);
			// Get the permission
			$query = "SELECT getUserResourcePermission('{$user['User']['id']}', '{$resource['Resource']['id']}') AS permid";
			$res = $this->Permission->query($query);
			// Load the permission
			$permission = $this->Permission->findById($res[0][0]['permid']);

			$permission = $permission ? $permission['Permission']['type'] : null;
			$this->assertTrue(
				$testcase['result'] == $permission,
				"permissions for user {$user['User']['username']} and resource {$resource['Resource']['name']} returned {$permission}
				but should have returned {$testcase['result']}"
			);
		}
	}
}