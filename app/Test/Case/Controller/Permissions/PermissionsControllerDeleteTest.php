<?php
/**
 * Permissions Controller Delete Tests
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

class PermissionsControllerDeleteTest extends ControllerTestCase {

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

	public function testDeletePermissionIdIsMissing() {
		$this->setExpectedException('BadRequestException', "The permission id is missing");
		// go through the addAcoPermissions because of routes
		$this->testAction("/permissions.json", array('method' => 'delete', 'return' => 'contents'));
	}

	public function testDeletePermissionIdIsInvalid() {
		$id = 'badId';
		$this->setExpectedException('BadRequestException', 'The permission id is not valid.');
		$this->testAction("/permissions/$id.json", array('method' => 'delete', 'return' => 'contents'));
	}

	public function testDeletePermissionDoesNotExist() {
		$id = Common::uuid('not-valid-reference');
		$this->setExpectedException('NotFoundException', "The permission does not exist");
		$this->testAction("/permissions/$id.json", array('method' => 'delete', 'return' => 'contents'));
	}

	// test edit aco permissions action with a not allowed user
	// not allowed => Permission.type < PermissionType::OWNER
	public function testDeletePermissionNotAllowed() {
		// try to get permissions on a Resource with a not allowed user
		$id = Common::uuid('permission.id.' . Common::uuid('resource.id.debian') . '-' . Common::uuid('user.id.ada'));

		// Expect not allowed exception
		$this->setExpectedException('ForbiddenException', "You are not allowed to delete this permission");

		// log as not allowed user
		$user = $this->User->findById(Common::uuid('user.id.ada'));
		$this->User->setActive($user);

		$postOptions = array(
			'method' => 'delete',
			'return' => 'contents'
		);
		$this->testAction("/permissions/$id.json", $postOptions);
	}
}
