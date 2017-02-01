<?php
/**
 * Permissions Controller Tests
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @package      app.Test.Case.Controller.PermissionsController
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @since        version 2.12.12
 */
App::uses('AppController', 'Controller');
App::uses('PermissionsController', 'Controller');
App::uses('UsersController', 'Controller');
App::uses('User', 'Model');
App::uses('Role', 'Model');
App::uses('Resource', 'Model');
App::uses('UserResourcePermission', 'Model');
App::uses('CakeSession', 'Model');
App::uses('CakeSession', 'Model/Datasource');

class PermissionsControllerTest extends ControllerTestCase {

	public $fixtures = array(
		'app.resource',
		'app.user',
		'app.group',
		'app.groups_user',
		'app.role',
		'app.profile',
		'app.file_storage',
		'app.gpgkey',
		'app.permission',
		'app.permissions_type',
		'app.permission_view',
		'app.authenticationLog',
		'app.authenticationBlacklist',
		'core.cakeSession',
		'app.user_agent',
		'app.controller_log'
	);

	public $user;

	public $session;
	
	public function setUp() {
		parent::setUp();
		
		$this->User = Common::getModel('User');
		$this->Resource = Common::getModel('Resource');
		$this->Permission = Common::getModel('Permission');
		$this->UserResourcePermission = Common::getModel('UserResourcePermission');
		
		$this->session = new CakeSession();
		$this->session->init();

		$user = $this->User->findById(Common::uuid('user.id.dame'));
		$this->User->setActive($user);
	}

	public function tearDown() {
		// Make sure there is no session active after each test
		parent::tearDown();
		$this->User->setInactive();
	}

	public function testViewAcoPermissionsNotExistingModel() {
		$model = 'NotExistingModel';
		$id = Common::uuid('user.id.user');
		$this->setExpectedException('HttpException', "The model {$model} is not permissionable");
		$this->testAction("/permissions/viewAcoPermissions/$model/$id.json", array('method' => 'get', 'return' => 'contents'));
	}

	public function testViewAcoPermissionsNotPermissionableModel() {
		$model = 'User';
		$id = Common::uuid('user.id.user');
		$this->setExpectedException('HttpException', "The model {$model} is not permissionable");
		$this->testAction("/permissions/viewAcoPermissions/$model/$id.json", array('method' => 'get', 'return' => 'contents'));
	}

	public function testViewAcoPermissionsModelIdIsMissing() {
		$model = 'Resource';
		$this->setExpectedException('HttpException', "The {$model} id is missing");
		$this->testAction("/permissions/viewAcoPermissions/$model.json", array('method' => 'get', 'return' => 'contents'));
	}

	public function testViewAcoPermissionsModelIdIsInvalid() {
		$model = 'Resource';
		$id = 'badId';
		$this->setExpectedException('HttpException', "The {$model} id is invalid");
		$this->testAction("/permissions/viewAcoPermissions/$model/$id.json", array('method' => 'get', 'return' => 'contents'));
	}

	public function testViewAcoPermissionsModelInstanceDoesNotExist() {
		$model = 'Resource';
		$id = Common::uuid('not-valid-reference');
		$this->setExpectedException('HttpException', "The {$model} does not exist");
		$this->testAction("/permissions/viewAcoPermissions/$model/$id.json", array('method' => 'get', 'return' => 'contents'));
	}

	// test view aco permissions on Resource Aco
	public function testViewAcoPermissionsOnResource() {
		$expectedUsersPermissions = array();
		$expectedGroupsPermissions = array();

		$matrix = PermissionMatrix::importCsv(TESTS . '/Data/users_resources_permissions.csv', 'resource');
		foreach ($matrix as $resourceAlias => $userPermissions) {
			// Retrieve the direct users permissions defined for the resource
			$expectedUsersPermissions[$resourceAlias] = array();
			foreach($userPermissions as $userAlias => $permission) {
				if ($permission != '0') {
					$expectedUsersPermissions[$resourceAlias][$userAlias] = Common::uuid('user.id.' . $userAlias);
				}
			}
		}

		$matrix = PermissionMatrix::importCsv(TESTS . '/Data/groups_resources_permissions.csv', 'resource');
		foreach ($matrix as $resourceAlias => $groupPermissions) {
			// Retrieve the direct users permissions defined for the resource
			$expectedGroupsPermissions[$resourceAlias] = [];
			foreach ($groupPermissions as $groupAlias => $permission) {
				if ($permission != '0') {
					$expectedGroupsPermissions[$resourceAlias][$groupAlias] = Common::uuid('group.id.' . $groupAlias);
				}
			}
		}

		$getOptions = array(
			'method' => 'get',
			'return' => 'contents'
		);
		foreach($expectedUsersPermissions as $resourceAlias => $none) {
			// Login with an authorized user.
			$userId = Common::uuid('user.id.' . key($expectedUsersPermissions[$resourceAlias]));
			$user = $this->User->findById($userId);
			$this->User->setActive($user);

			// Get the resources permissions.
			$rsId = Common::uuid('resource.id.' . $resourceAlias);
			$srvResult = json_decode($this->testAction("/permissions/resource/$rsId.json", $getOptions), true);

			// Check that all the permissions are expected.
			foreach($srvResult['body'] as $perm) {
				$this->assertTrue(in_array($perm['Permission']['aro_foreign_key'], $expectedUsersPermissions[$resourceAlias]) ||
					in_array($perm['Permission']['aro_foreign_key'], $expectedGroupsPermissions[$resourceAlias]), "The permission {$perm['Permission']['id']} should be associated to the resource $resourceAlias");
			}
			$this->assertEqual(count($srvResult['body']),
				count($expectedUsersPermissions[$resourceAlias]) + count($expectedGroupsPermissions[$resourceAlias]));
		}
	}

	public function testAddAcoPermissionsNotExistingModel() {
		$model = 'notExistingModel';
		$id = Common::uuid('user.id.user');
		$this->setExpectedException('HttpException', "The model " . ucfirst($model) . " is not permissionable");
		// go through the addAcoPermissions because of routes
		$this->testAction("/permissions/addAcoPermissions/$model/$id.json", array('method' => 'post', 'return' => 'contents'));
	}

	public function testAddAcoPermissionsNotPermissionableModel() {
		$model = 'user';
		$id = Common::uuid('user.id.user');
		$this->setExpectedException('HttpException', "The model " . ucfirst($model) . " is not permissionable");
		// go through the addAcoPermissions because of routes
		$this->testAction("/permissions/addAcoPermissions/$model/$id.json", array('method' => 'post', 'return' => 'contents'));
	}

	public function testAddAcoPermissionsModelIdIsMissing() {
		$model = 'resource';
		$this->setExpectedException('HttpException', "The " . ucfirst($model) . " id is missing");
		// go through the addAcoPermissions because of routes
		$this->testAction("/permissions/addAcoPermissions/$model.json", array('method' => 'post', 'return' => 'contents'));
	}

	public function testAddAcoPermissionsModelIdIsInvalid() {
		$model = 'resource';
		$id = 'badId';
		$this->setExpectedException('HttpException', "The " . ucfirst($model) . " id is invalid");
		$this->testAction("/permissions/$model/$id.json", array('method' => 'post', 'return' => 'contents'));
	}

	public function testAddAcoPermissionsModelInstanceDoesNotExist() {
		$model = 'resource';
		$id = Common::uuid('not-valid-reference');
		$this->setExpectedException('HttpException', "Your are not allowed to add a permission to the Resource");
		$this->testAction("/permissions/$model/$id.json", array('method' => 'post', 'return' => 'contents'));
	}

	public function testAddAcoPermissionsOnResource() {
		$user = $this->User->findById(Common::uuid('user.id.dame'));
		$this->User->setActive($user);

		// Define the permission
		$model = 'resource';
		$rsId = Common::uuid('resource.id.debian');
		$data = array(
			'Permission' => array(
				'type' => PermissionType::READ
			),
			'User' => array(
				'id' => Common::uuid('user.id.carol')
			)
		);

		// check how many permissions are already existing before the new insertion
		$srvResult = json_decode($this->testAction("/permissions/$model/$rsId.json", array(
			 'method' => 'get',
			 'return' => 'contents'
		)), true);

		$expectedCount = count($srvResult['body']) + 1;
		// insert the new permission
		$srvResult = json_decode($this->testAction("/permissions/$model/$rsId.json", array(
			 'method' => 'post',
			 'return' => 'contents',
			 'data'=> $data
		)), true);

		$this->assertEquals(
			Status::SUCCESS,
			$srvResult['header']['status'],
			"/permissions/$model/$rsId.json : The test should return a success but is returning {$srvResult['header']['status']}"
		);
		// check the permission has well been inserted
		$srvResult = json_decode($this->testAction("/permissions/$model/$rsId.json", array(
			 'method' => 'get',
			 'return' => 'contents'
		)), true);
		$this->assertEquals(
			$expectedCount,
			count($srvResult['body']),
			"/permissions/$model/$rsId.json : The test should return {$expectedCount} permissions but is returning " . count($srvResult['body'])
		);
	}

	public function testAddAcoPermissionsOnResourceExistingPermission() {
		$user = $this->User->findById(Common::uuid('user.id.dame'));
		$this->User->setActive($user);

		// Define the permission
		$model = 'resource';
		$rsId = Common::uuid('resource.id.debian');

		$data = array(
			'Permission' => array(
				'type' => PermissionType::READ
			),
			'User' => array(
				'id' => Common::uuid('user.id.carol')
			)
		);

		// insert the new permission
		$srvResult = json_decode($this->testAction("/permissions/$model/$rsId.json", array(
			 'method' => 'post',
			 'return' => 'contents',
			 'data'=> $data
		)), true);
		$this->assertEquals(Status::SUCCESS, $srvResult['header']['status'], "/permissions/$model/$rsId.json : The test should return a success but is returning {$srvResult['header']['status']}");


		$this->setExpectedException('HttpException', "A direct permission already exists");
		// try to insert a second time the same permission should return an error
		$this->testAction("/permissions/$model/$rsId.json", array(
			 'method' => 'post',
			 'return' => 'contents',
			 'data'=> $data
		));
	}

	public function testSimulateAcoPermissionsOnResource() {
		$user = $this->User->findById(Common::uuid('user.id.dame'));
		$this->User->setActive($user);

		$model = 'resource';
		$rsId = Common::uuid('resource.id.debian');
		$data = array(
			'Permission' => array(
				'type' => PermissionType::READ
			),
			'User' => array(
				'id' => Common::uuid('user.id.carol')
			)
		);

		// check how many permissions are already existing before the new insertion
		$srvResult = json_decode($this->testAction("/permissions/$model/$rsId.json", array(
					'method' => 'get',
					'return' => 'contents'
				)), true);

		$realCount = count($srvResult['body']);
		// insert the new permission
		$srvSimulatedResult = json_decode($this->testAction("/permissions/simulate/$model/$rsId.json", array(
					'method' => 'post',
					'return' => 'contents',
					'data'=> $data
				)), true);
		$simulatedCount = count($srvSimulatedResult['body']);

		$this->assertEquals(
			Status::SUCCESS,
			$srvResult['header']['status'],
			"/permissions/$model/$rsId.json : The test should return a success but is returning {$srvResult['header']['status']}"
		);

		$this->assertEquals(
			$simulatedCount,
			count($srvResult['body']) + 1,
			"/permissions/$model/$rsId.json : The test should return {$realCount} permissions but is returning " . count($srvResult['body'])
		);

		// check the permission was not actually inserted (was only a simulation).
		$srvResult = json_decode($this->testAction("/permissions/$model/$rsId.json", array(
					'method' => 'get',
					'return' => 'contents'
				)), true);
		$this->assertEquals(
			$realCount,
			count($srvResult['body']),
			"/permissions/$model/$rsId.json : The test should return {$realCount} permissions but is returning " . count($srvResult['body'])
		);
	}

	public function testEditPermissionIdIsMissing() {
		$this->setExpectedException('HttpException', "The permission id is missing");
		// go through the addAcoPermissions because of routes
		$this->testAction("/permissions.json", array('method' => 'put', 'return' => 'contents'));
	}

	public function testEditPermissionIdIsInvalid() {
		$id = 'badId';
		$this->setExpectedException('HttpException', "The permission id is invalid");
		$this->testAction("/permissions/$id.json", array('method' => 'put', 'return' => 'contents'));
	}

	public function testEditPermissionDoesNotExist() {
		$id = Common::uuid('not-valid-reference');
		$this->setExpectedException('HttpException', "The permission does not exist");
		$this->testAction("/permissions/$id.json", array('method' => 'put', 'return' => 'contents'));
	}

	// test edit aco permissions action with a not allowed user
	// not allowed => Permission.type < PermissionType::UPDATE
	public function testEditUserNotAllowed() {
		// try to get permissions on a Resource with a not allowed user
		$id = Common::uuid('permission.id.' . Common::uuid('resource.id.debian') . '-' . Common::uuid('user.id.ada'));

		// Expect not allowed exception
		$this->setExpectedException('HttpException', "You are not allowed to edit this permission");

		// Log as not allowed user
		$user = $this->User->findById(Common::uuid('user.id.carol'));
		$this->User->setActive($user);

		$postOptions = array(
			'method' => 'put',
			'return' => 'contents',
			'data'=> array(
				'Permission' => array(
					'type' => PermissionType::DENY
				)
			)
		);
		$this->testAction("/permissions/$id.json", $postOptions);
	}

	public function testEdit() {
		$rsId = Common::uuid('resource.id.debian');
		$userId = Common::uuid('user.id.ada');
		$id = Common::uuid('permission.id.' . $rsId . '-' . $userId );
		$postOptions = array(
			'method' => 'put',
			'return' => 'contents',
			'data'=> array(
				'Permission' => array(
					'type' => PermissionType::OWNER
				)
			)
		);

		// Edit the permission
		$srvResult = json_decode($this->testAction("/permissions/$id.json", $postOptions), true);
		$this->assertEquals(Status::SUCCESS, $srvResult['header']['status'], "/permissions/$id.json : The test should return a success but is returning {$srvResult['header']['status']}");

		// Retrieve the permission that has been update for the user.
		$getOptions = array(
			'method' => 'get',
			'return' => 'contents'
		);
		$srvResult = json_decode($this->testAction("/permissions/resource/$rsId.json", $getOptions), true);

		// Search for a permission
		$found = false;
		foreach ($srvResult['body'] as $permission) {
			if($permission['User']['id'] == $userId) {
				$found = true;
				$this->assertEquals(PermissionType::OWNER, $permission['Permission']['type']);
			}
		}
		$this->assertTrue($found);
	}

	public function testDeletePermissionIdIsMissing() {
		$this->setExpectedException('HttpException', "The permission id is missing");
		// go through the addAcoPermissions because of routes
		$this->testAction("/permissions.json", array('method' => 'delete', 'return' => 'contents'));
	}

	public function testDeletePermissionIdIsInvalid() {
		$id = 'badId';
		$this->setExpectedException('HttpException', "The permission id is invalid");
		$this->testAction("/permissions/$id.json", array('method' => 'delete', 'return' => 'contents'));
	}

	public function testDeletePermissionDoesNotExist() {
		$id = Common::uuid('not-valid-reference');
		$this->setExpectedException('HttpException', "The permission does not exist");
		$this->testAction("/permissions/$id.json", array('method' => 'delete', 'return' => 'contents'));
	}

	// test edit aco permissions action with a not allowed user
	// not allowed => Permission.type < PermissionType::OWNER
	public function testDeletePermissionNotAllowed() {
		// try to get permissions on a Resource with a not allowed user
		$id = Common::uuid('permission.id.' . Common::uuid('resource.id.debian') . '-' . Common::uuid('user.id.ada'));

		// Expect not allowed exception
		$this->setExpectedException('HttpException', "You are not allowed to delete this permission");

		// log as not allowed user
		$user = $this->User->findById(Common::uuid('user.id.ada'));
		$this->User->setActive($user);

		$postOptions = array(
			'method' => 'delete',
			'return' => 'contents',
			'data'=> array(
				'Permission' => array(
					'type' => PermissionType::DENY
				)
			)
		);
		$this->testAction("/permissions/$id.json", $postOptions);
	}
}
