<?php
/**
 * Share Controller Tests
 *
 * @copyright (c) 2015-2016 Bolt Softwares Pvt Ltd
 * 				  2017-present Passbolt SARL
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

class ShareControllerSimulateTest extends ControllerTestCase {

	public $fixtures = array(
		'app.resource',
		'app.secret',
		'app.favorite',
		'app.user',
		'app.group',
		'app.groups_user',
		'app.role',
		'app.profile',
		'app.file_storage',
		'app.permission',
		'app.permissions_type',
		'app.permission_view',
		'app.gpgkey',
		'app.emailQueue',
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

	private function _simulateCall($aco = 'Resource', $acoId = '', $data = array()) {
		// check how many permissions are already existing before the new insertion
		$res = $this->testAction("/share/simulate/$aco/$acoId.json", array(
			'method' => 'post',
			'return' => 'contents',
			'data' => $data
		), true);
		return $res;
	}

	public function testSimulateAcoNotValid() {
		$this->setExpectedException('HttpException', 'The call to entry point with parameter User is not allowed');
		$aco = 'User';
		$acoId = Common::uuid();
		$this->_simulateCall($aco, $acoId);
	}

	public function testSimulateWrongIdProvided() {
		$this->setExpectedException('HttpException', 'The aco id is invalid');

		$userId = Common::uuid('user.id.user');
		$data = array(
			'Permissions' => array(
				array(
					'Permission' => array (
						'aro_foreign_key' => $userId,
						'type' => PermissionType::OWNER,
					),
				),
			),
		);
		$this->_simulateCall('Resource', 'bad-id', $data);
	}

	public function testSimulateNotAllowed() {
		$this->setExpectedException('HttpException', 'Your are not allowed to add a permission to the Resource');

		$acoId = Common::uuid('resource.id.debian');
		$user = $this->User->findById(Common::uuid('user.id.user'));
		$this->User->setActive($user);

		$data = array(
			'Permissions' => array(
				array(
					'Permission' => array (
						'aro_foreign_key' => Common::uuid('user.id.nancy'),
						'type' => PermissionType::OWNER,
					),
				),
			),
		);
		$this->_simulateCall('Resource', $acoId, $data);
	}

	public function testSimulateNoPermissions() {
		$this->setExpectedException('HttpException', 'No permissions were provided');
		$this->_simulateCall('Resource', Common::uuid('resource.id.debian'), array());
	}

	public function testSimulateUserPermission() {
		$userId = Common::uuid('user.id.user');
		$acoInstanceId = Common::uuid('resource.id.debian');

		$data = array(
			'Permissions' => array(
				array(
					'Permission' => array (
						'aro' => 'User',
						'aro_foreign_key' => $userId,
						'type' => PermissionType::OWNER,
					),
				),
			),
		);
		// check how many permissions are already existing before the new insertion
		$res = $this->_simulateCall('Resource', $acoInstanceId, $data);
		$json = json_decode($res, true);

		$this->assertEquals(
			Status::SUCCESS,
			$json['header']['status'],
			"Simulation of adding permissions should have returned success, but returned {$json['header']['status']}"
		);

		// Test that the user is added to the list of added user.
		$addedUsers = $json['body']['changes']['added'];
		$addedUsersIds = Hash::extract($addedUsers, '{n}.User.id');
		$this->assertTrue(in_array($userId, $addedUsersIds));
	}

	public function testSimulateGroupPermission() {
		$groupId = Common::uuid('group.id.board');
		$userId = Common::uuid('user.id.hedy');
		$acoInstanceId = Common::uuid('resource.id.centos');

		$data = array(
			'Permissions' => array(
				array(
					'Permission' => array (
						'aro' => 'Group',
						'aro_foreign_key' => $groupId,
						'type' => PermissionType::OWNER,
					),
				),
			),
		);
		// check how many permissions are already existing before the new insertion
		$res = $this->_simulateCall('Resource', $acoInstanceId, $data);
		$json = json_decode($res, true);

		$this->assertEquals(
			Status::SUCCESS,
			$json['header']['status'],
			"Simulation of adding permissions should have returned success, but returned {$json['header']['status']}"
		);

		// Test that the group's users are added to the list of added user.
		$addedUsers = $json['body']['changes']['added'];
		$addedUsersIds = Hash::extract($addedUsers, '{n}.User.id');
		$this->assertTrue(in_array($userId, $addedUsersIds));
	}

}
