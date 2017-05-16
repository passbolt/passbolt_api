<?php
/**
 * Permissions Controller Add Tests
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

class PermissionsControllerAddTest extends ControllerTestCase {

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

	public function testAddAcoPermissionsNotExistingModel() {
		$model = 'notExistingModel';
		$id = Common::uuid('user.id.user');
		$this->setExpectedException('BadRequestException', "The model " . ucfirst($model) . " does not support permissions.");
		// go through the addAcoPermissions because of routes
		$this->testAction("/permissions/addAcoPermissions/$model/$id.json", array('method' => 'post', 'return' => 'contents'));
	}

	public function testAddAcoPermissionsNotPermissionableModel() {
		$model = 'user';
		$id = Common::uuid('user.id.user');
		$this->setExpectedException('BadRequestException', "The model " . ucfirst($model) . " does not support permissions.");
		// go through the addAcoPermissions because of routes
		$this->testAction("/permissions/addAcoPermissions/$model/$id.json", array('method' => 'post', 'return' => 'contents'));
	}

	public function testAddAcoPermissionsModelIdIsMissing() {
		$model = 'resource';
		$this->setExpectedException('BadRequestException','The id is missing for model Resource.');
		// go through the addAcoPermissions because of routes
		$this->testAction("/permissions/addAcoPermissions/$model.json", array('method' => 'post', 'return' => 'contents'));
	}

	public function testAddAcoPermissionsModelIdIsInvalid() {
		$model = 'resource';
		$id = 'badId';
		$this->setExpectedException('BadRequestException', 'The id is not valid for model Resource.');
		$this->testAction("/permissions/$model/$id.json", array('method' => 'post', 'return' => 'contents'));
	}

	public function testAddAcoPermissionsModelInstanceDoesNotExist() {
		$model = 'resource';
		$id = Common::uuid('not-valid-reference');
		$this->setExpectedException('ForbiddenException', "Your are not allowed to add a permission to the Resource");
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


		$this->setExpectedException('BadRequestException', "A direct permission already exists");
		// try to insert a second time the same permission should return an error
		$this->testAction("/permissions/$model/$rsId.json", array(
			 'method' => 'post',
			 'return' => 'contents',
			 'data'=> $data
		));
	}
}
