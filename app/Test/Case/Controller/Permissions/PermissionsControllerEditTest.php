<?php
/**
 * Permissions Controller Edit Tests
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

class PermissionsControllerEditTest extends ControllerTestCase {

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

	public function testEditPermissionIdIsMissing() {
		$this->setExpectedException('BadRequestException', "The permission id is missing");
		// go through the addAcoPermissions because of routes
		$this->testAction("/permissions.json", array('method' => 'put', 'return' => 'contents'));
	}

	public function testEditPermissionIdIsInvalid() {
		$id = 'badId';
		$this->setExpectedException('BadRequestException', 'The permission id is not valid.');
		$this->testAction("/permissions/$id.json", array('method' => 'put', 'return' => 'contents'));
	}

	public function testEditPermissionDoesNotExist() {
		$id = Common::uuid('not-valid-reference');
		$this->setExpectedException('NotFoundException', "The permission does not exist");
		$this->testAction("/permissions/$id.json", array('method' => 'put', 'return' => 'contents'));
	}

	// test edit aco permissions action with a not allowed user
	// not allowed => Permission.type < PermissionType::UPDATE
	public function testEditUserNotAllowed() {
		// try to get permissions on a Resource with a not allowed user
		$id = Common::uuid('permission.id.' . Common::uuid('resource.id.debian') . '-' . Common::uuid('user.id.ada'));

		// Expect not allowed exception
		$this->setExpectedException('ForbiddenException', "You are not allowed to edit this permission");

		// Log as not allowed user
		$user = $this->User->findById(Common::uuid('user.id.carol'));
		$this->User->setActive($user);

		$postOptions = array(
			'method' => 'put',
			'return' => 'contents',
			'data'=> array(
				'Permission' => array(
					'type' => PermissionType::READ
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
}
