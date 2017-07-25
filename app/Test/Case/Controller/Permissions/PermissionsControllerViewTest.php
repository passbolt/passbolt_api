<?php
/**
 * Permissions Controller View Tests
 *
 * @copyright (c) 2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
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
App::uses('PermissionMatrix', 'DataSeleniumTests.Data');

class PermissionsControllerViewTest extends ControllerTestCase {

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
		$this->setExpectedException('BadRequestException', "The model {$model} does not support permissions.");
		$this->testAction("/permissions/viewAcoPermissions/$model/$id.json", array('method' => 'get', 'return' => 'contents'));
	}

	public function testViewAcoPermissionsNotPermissionableModel() {
		$model = 'User';
		$id = Common::uuid('user.id.user');
		$this->setExpectedException('BadRequestException', "The model {$model} does not support permissions.");
		$this->testAction("/permissions/viewAcoPermissions/$model/$id.json", array('method' => 'get', 'return' => 'contents'));
	}

	public function testViewAcoPermissionsModelIdIsMissing() {
		$model = 'Resource';
		$this->setExpectedException('BadRequestException', "The id is missing for model {$model}.");
		$this->testAction("/permissions/viewAcoPermissions/$model.json", array('method' => 'get', 'return' => 'contents'));
	}

	public function testViewAcoPermissionsModelIdIsInvalid() {
		$model = 'Resource';
		$id = 'badId';
		$this->setExpectedException('BadRequestException', "The id is not valid for model {$model}.");
		$this->testAction("/permissions/viewAcoPermissions/$model/$id.json", array('method' => 'get', 'return' => 'contents'));
	}

	public function testViewAcoPermissionsModelInstanceDoesNotExist() {
		$model = 'Resource';
		$id = Common::uuid('not-valid-reference');
		$this->setExpectedException('HttpException', "The model {$model} does not exist.");
		$this->testAction("/permissions/viewAcoPermissions/$model/$id.json", array('method' => 'get', 'return' => 'contents'));
	}

	// test view aco permissions on Resource Aco
	public function testViewAcoPermissionsOnResource() {
		$expectedUsersPermissions = array();
		$expectedGroupsPermissions = array();

		$matrixPath = CakePlugin::path('DataSeleniumTests') . '/Data/users_resources_permissions.csv';
		$matrix = PermissionMatrix::importCsv($matrixPath, 'resource');
		foreach ($matrix as $resourceAlias => $userPermissions) {
			// Retrieve the direct users permissions defined for the resource
			$expectedUsersPermissions[$resourceAlias] = array();
			foreach($userPermissions as $userAlias => $permission) {
				if ($permission != '0') {
					$expectedUsersPermissions[$resourceAlias][$userAlias] = Common::uuid('user.id.' . $userAlias);
				}
			}
		}

		$matrixPath = CakePlugin::path('DataSeleniumTests') . '/Data/groups_resources_permissions.csv';
		$matrix = PermissionMatrix::importCsv($matrixPath, 'resource');
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
		// Loop on each resource.
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
					in_array($perm['Permission']['aro_foreign_key'], $expectedGroupsPermissions[$resourceAlias]),
					"The permission {$perm['Permission']['id']} should be associated to the resource $resourceAlias");
			}
			$this->assertEqual(count($srvResult['body']),
				count($expectedUsersPermissions[$resourceAlias]) + count($expectedGroupsPermissions[$resourceAlias]));
		}
	}

}
