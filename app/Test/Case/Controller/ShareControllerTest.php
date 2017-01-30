<?php
/**
 * Share Controller Tests
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @package      app.Test.Case.Controller.ShareController
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @since        version 2.12.12
 */
App::uses('AppController', 'Controller');
App::uses('PermissionsController', 'Controller');
App::uses('UsersController', 'Controller');
App::uses('User', 'Model');
App::uses('Role', 'Model');
App::uses('Resource', 'Model');
App::uses('UserResourcePermission', 'Model');;
App::uses('CakeSession', 'Model');
App::uses('CakeSession', 'Model/Datasource');

class ShareControllerTest extends ControllerTestCase {

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
		'app.authenticationLog',
		'app.authenticationBlacklist',
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

	private function _updateCall($aco = 'Resource', $acoId = '', $data = array()) {
		Common::uuid('not-valid-reference');

		// check how many permissions are already existing before the new insertion
		$res = $this->testAction("/share/$aco/$acoId.json", array(
				'method' => 'put',
				'return' => 'contents',
				'data' => $data
			), true);
		return $res;
	}

	public function testUpdateAcoNotValid() {
		$this->setExpectedException('HttpException', 'The call to entry point with parameter User is not allowed');
		$this->_updateCall('User', Common::uuid('tetris-license'), array());
	}

	public function testUpdateNoPermissions() {
		$this->setExpectedException('HttpException', 'No permissions were provided');
		$this->_updateCall('Resource', Common::uuid('tetris-license'), array());
	}

	public function testUpdateWrongIdProvided() {
		$this->setExpectedException('HttpException', 'The Resource id is invalid');
		$data = array(
			'Permissions' => array(
				array('Permission' => array())
			),
		);
		$this->_updateCall('Resource', 'badid', $data);
	}

	public function testUpdateFakeIdProvided() {
		$this->setExpectedException('HttpException', 'The Resource id is invalid');
		$data = array(
			'Permissions' => array(
				array('Permission' => array())
			),
		);
		$this->_updateCall('Resource', Common::uuid(), $data);
	}

	public function testUpdateNoPermissionsProvided() {
		$this->setExpectedException('HttpException', 'No permissions were provided');
		$data = array(
			'Permissions' => array(

			),
		);
		$this->_updateCall('Resource', Common::uuid('resource.id.debian'), $data);
	}

	public function testUpdateNotAllowed() {
		$acoInstanceId = Common::uuid('resource.id.debian');

		$user = $this->User->findById(Common::uuid('user.id.user'));
		$this->User->setActive($user);

		$aco = 'Resource';
		$this->setExpectedException('HttpException', 'Your are not allowed to add a permission to the Resource');
		$data = array(
			'Permissions' => array(
				array(
					'Permission' => array (
						'id' => '',
						'delete' => '1',
					)
				)
			),
		);

		// check how many permissions are already existing before the new insertion
		$this->_updateCall('Resource', $acoInstanceId, $data);
	}

	public function testUpdateDeleteNonExistingResource() {
		$acoInstanceId = Common::uuid('resource.id.debian');
		$permissionId = Common::uuid('not-valid-reference');

		$data = array(
			'Permissions' => array(
				array(
					'Permission' => array (
						'id' => $permissionId,
						'delete' => '1',
					)
				)
			),
		);
		$this->setExpectedException('HttpException', "The permission with id {$permissionId} does not exist");
		$this->_updateCall('Resource', $acoInstanceId, $data);
	}

	public function testUpdateDeletePermissionBelongsToOtherResource() {
		$acoInstanceId = Common::uuid('resource.id.debian');

		// Get a permission that doesn't belong to the resource.
		$directPerm = $this->Permission->find('first', array(
			'conditions' => array(
				'aco' => 'Resource',
				'aco_foreign_key' => Common::uuid('resource.id.apache'),
				'aro' => 'User',
			)
		));
		$permissionId = $directPerm['Permission']['id'];
		$data = array(
			'Permissions' => array(
				array(
					'Permission' => array (
						'id' => $permissionId,
						'delete' => '1',
					)
				)
			),
		);

		$this->setExpectedException('HttpException', "Could not delete permission id {$permissionId}");
		$this->_updateCall('Resource', $acoInstanceId, $data);
	}

	public function testUpdateDeletePermission() {
		$acoInstanceId = Common::uuid('resource.id.debian');

		// Get a permission that belongs to the resource
		$directPerm = $this->Permission->find('first', array(
			'conditions' => array(
				'aco' => 'Resource',
				'aco_foreign_key' => Common::uuid('resource.id.debian'),
				'aro' => 'User',
			)
		));
		$permissionId = $directPerm['Permission']['id'];
		$data = array(
			'Permissions' => array(
				array(
					'Permission' => array (
						'id' => $permissionId,
						'delete' => '1',
					)
				)
			),
		);
		$res = json_decode($this->_updateCall('Resource', $acoInstanceId, $data), true);
		$this->assertEquals(Status::SUCCESS, $res['header']['status'],
			"Deleting a permission should have returned a success, but returned {$res['header']['status']}"
		);

		// Observe that the permission is deleted.
		$exist = $this->Permission->exists($permissionId);
		$this->assertFalse($exist, "Deleting a permission should have actually deleted the permission, but the permission still exists.");
	}

	public function testUpdateAddSecretsNotProvided() {
		$acoInstanceId = Common::uuid('resource.id.debian');
		$user = $this->User->findById(Common::uuid('user.id.user'));

		$data = array(
			'Permissions' => array(
				array(
					'Permission' => array (
						'aro_foreign_key' => $user['User']['id'],
						'type' => PermissionType::OWNER,
					)
				)
			),
		);
		$this->setExpectedException('HttpException', 'The number of secrets provided doesn\'t match the 1 users who have now access to the resources');
		$this->_updateCall('Resource', $acoInstanceId, $data);
	}

	public function testUpdateAddAlreadyExist() {
		// Get a direct permission that already exist.
		$directPerm = $this->Permission->find('first', array(
				'conditions' => array(
					'aco' => 'Resource',
					'aco_foreign_key' => Common::uuid('resource.id.debian'),
					'aro' => 'User',
				)
			));
		$data = array(
			'Permissions' => array(
				array(
					'Permission' => array (
						'aro_foreign_key' => $directPerm['Permission']['aro_foreign_key'],
						'type' => $directPerm['Permission']['type'],
					),
				),
			),
			'Secrets' => array(
				array(
					'Secret' => array (
						'user_id' => $directPerm['Permission']['aro_foreign_key'],
						'resource_id' => $directPerm['Permission']['aco_foreign_key'],
						'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf9HpfcNeuC5W/VAzEtAe8mTBUk1vcJENtGpMyRkVTC8KbQ
xaEr3+UG6h0ZVzfrMFYrYLolS3fie83cj4FnC3gg1uijo7zTf9QhJMdi7p/ASB6N
y7//8AriVqUAOJ2WCxAVseQx8qt2KqkQvS7F7iNUdHfhEhiHkczTlehyel7PEeas
SdM/kKEsYKk6i4KLPBrbWsflFOkfQGcPL07uRK3laFz8z4LNzvNQOoU7P/C1L0X3
tlK3vuq+r01zRwmflCaFXaHVifj3X74ljhlk5i/JKLoPRvbxlPTevMNag5e6QhPQ
kpj+TJD2frfGlLhyM50hQMdJ7YVypDllOBmnTRwZ0tJFAXm+F987ovAVLMXGJtGO
P+b3c493CfF0fQ1MBYFluVK/Wka8usg/b0pNkRGVWzBcZ1BOONYlOe/JmUyMutL5
hcciUFw5
=TcQF
-----END PGP MESSAGE-----',
					),
				),
			),
		);

		$resource = $this->Resource->findById($directPerm['Permission']['aco_foreign_key']);
		$this->setExpectedException('HttpException', 'The permission to be created already exists');
		$this->_updateCall('Resource', $resource['Resource']['id'], $data);
	}

	// Test update add with wrong secrets data provided. (not matching the user ids).
	public function testUpdateAddSecretForWrongUserProvided() {
		$userAId = Common::uuid('user.id.ada');
		$userCId = Common::uuid('user.id.carol');
		$userFId = Common::uuid('user.id.frances');
		$rsId = Common::uuid('resource.id.debian');

		$data = array(
			'Permissions' => array(
				array(
					'Permission' => array (
						'aro_foreign_key' => $userCId,
						'type' => PermissionType::OWNER,
					),
				),
				array(
					'Permission' => array (
						'aro_foreign_key' => $userFId,
						'type' => PermissionType::OWNER,
					),
				)
			),
			'Secrets' => array(
				array(
					'Secret' => array (
						'user_id' =>$userCId,
						'resource_id' => $rsId,
						'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf9HpfcNeuC5W/VAzEtAe8mTBUk1vcJENtGpMyRkVTC8KbQ
xaEr3+UG6h0ZVzfrMFYrYLolS3fie83cj4FnC3gg1uijo7zTf9QhJMdi7p/ASB6N
y7//8AriVqUAOJ2WCxAVseQx8qt2KqkQvS7F7iNUdHfhEhiHkczTlehyel7PEeas
SdM/kKEsYKk6i4KLPBrbWsflFOkfQGcPL07uRK3laFz8z4LNzvNQOoU7P/C1L0X3
tlK3vuq+r01zRwmflCaFXaHVifj3X74ljhlk5i/JKLoPRvbxlPTevMNag5e6QhPQ
kpj+TJD2frfGlLhyM50hQMdJ7YVypDllOBmnTRwZ0tJFAXm+F987ovAVLMXGJtGO
P+b3c493CfF0fQ1MBYFluVK/Wka8usg/b0pNkRGVWzBcZ1BOONYlOe/JmUyMutL5
hcciUFw5
=TcQF
-----END PGP MESSAGE-----',
					),
				),
				array(
					'Secret' => array (
						'user_id' =>$userAId,
						'resource_id' => $rsId,
						'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf9HpfcNeuC5W/VAzEtAe8mTBUk1vcJENtGpMyRkVTC8KbQ
xaEr3+UG6h0ZVzfrMFYrYLolS3fie83cj4FnC3gg1uijo7zTf9QhJMdi7p/ASB6N
y7//8AriVqUAOJ2WCxAVseQx8qt2KqkQvS7F7iNUdHfhEhiHkczTlehyel7PEeas
SdM/kKEsYKk6i4KLPBrbWsflFOkfQGcPL07uRK3laFz8z4LNzvNQOoU7P/C1L0X3
tlK3vuq+r01zRwmflCaFXaHVifj3X74ljhlk5i/JKLoPRvbxlPTevMNag5e6QhPQ
kpj+TJD2frfGlLhyM50hQMdJ7YVypDllOBmnTRwZ0tJFAXm+F987ovAVLMXGJtGO
P+b3c493CfF0fQ1MBYFluVK/Wka8usg/b0pNkRGVWzBcZ1BOONYlOe/JmUyMutL5
hcciUFw5
=TcQF
-----END PGP MESSAGE-----',
					),
				),
			),
		);
		$this->setExpectedException('HttpException', "The secret for user id {$userFId} is not provided");
		$this->_updateCall('Resource', $rsId, $data);
	}

	public function testUpdateAddValid() {
		$userId = Common::uuid('user.id.user');
		$rsId = Common::uuid('resource.id.debian');

		$data = array(
			'Permissions' => array(
				array(
					'Permission' => array (
						'aro_foreign_key' => $userId,
						'type' => PermissionType::OWNER,
					),
				),
			),
			'Secrets' => array(
				array(
					'Secret' => array (
						'user_id' => $userId,
						'resource_id' => $rsId,
						'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf9HpfcNeuC5W/VAzEtAe8mTBUk1vcJENtGpMyRkVTC8KbQ
xaEr3+UG6h0ZVzfrMFYrYLolS3fie83cj4FnC3gg1uijo7zTf9QhJMdi7p/ASB6N
y7//8AriVqUAOJ2WCxAVseQx8qt2KqkQvS7F7iNUdHfhEhiHkczTlehyel7PEeas
SdM/kKEsYKk6i4KLPBrbWsflFOkfQGcPL07uRK3laFz8z4LNzvNQOoU7P/C1L0X3
tlK3vuq+r01zRwmflCaFXaHVifj3X74ljhlk5i/JKLoPRvbxlPTevMNag5e6QhPQ
kpj+TJD2frfGlLhyM50hQMdJ7YVypDllOBmnTRwZ0tJFAXm+F987ovAVLMXGJtGO
P+b3c493CfF0fQ1MBYFluVK/Wka8usg/b0pNkRGVWzBcZ1BOONYlOe/JmUyMutL5
hcciUFw5
=TcQF
-----END PGP MESSAGE-----',
					),
				),
			),
		);
		$res = json_decode($this->_updateCall('Resource', $rsId, $data), true);
		$this->assertEquals(
			Status::SUCCESS,
			$res['header']['status'],
			"Adding a permission should have returned a success, but returned {$res['header']['status']}"
		);

		// Observe that the permission is deleted.
		$exist = $this->Permission->find('first', array(
				'conditions' => array(
					'aco_foreign_key' => $rsId,
					'aro_foreign_key' => $userId,
					'type' => PermissionType::OWNER,
				)
			));

		$this->assertTrue(
			!empty($exist),
			"Adding a permission should have actually added the permission, but the permission doesn't exist."
		);
	}

	public function testUpdateUpdate() {
		// Get a direct permission that already exist.
		$directPerm = $this->Permission->find('first', array(
				'conditions' => array(
					'aco' => 'Resource',
					'aro' => 'User',
					'aco_foreign_key' => Common::uuid('resource.id.debian')
				)
			));
		$data = array(
			'Permissions' => array(
				array(
					'Permission' => array (
						'id' => $directPerm['Permission']['id'],
						'aro_foreign_key' => $directPerm['Permission']['aro_foreign_key'],
						'type' => PermissionType::CREATE,
					),
				),
			),
		);

		$res = json_decode($this->_updateCall('Resource', $directPerm['Permission']['aco_foreign_key'], $data), true);
		$this->assertEquals(
			Status::SUCCESS,
			$res['header']['status'],
			"Updating a permission should have returned a success, but returned {$res['header']['status']}"
		);
	}

	// Test adding permissions for a user that is not active (not completed the setup yet).
	public function testUpdateAddInactiveUser() {
		$userId = Common::uuid('user.id.user');
		$rsId = Common::uuid('resource.id.debian');

		$this->User->id = $userId;
		$this->User->save(['active' => 0], false, ['active']);

		$data = array(
			'Permissions' => array(
				array(
					'Permission' => array (
						'aro_foreign_key' => $userId,
						'type' => PermissionType::OWNER,
					),
				),
			),
			'Secrets' => array(
				array(
					'Secret' => array (
						'user_id' => $userId,
						'resource_id' => $rsId,
						'data' => '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf9HpfcNeuC5W/VAzEtAe8mTBUk1vcJENtGpMyRkVTC8KbQ
xaEr3+UG6h0ZVzfrMFYrYLolS3fie83cj4FnC3gg1uijo7zTf9QhJMdi7p/ASB6N
y7//8AriVqUAOJ2WCxAVseQx8qt2KqkQvS7F7iNUdHfhEhiHkczTlehyel7PEeas
SdM/kKEsYKk6i4KLPBrbWsflFOkfQGcPL07uRK3laFz8z4LNzvNQOoU7P/C1L0X3
tlK3vuq+r01zRwmflCaFXaHVifj3X74ljhlk5i/JKLoPRvbxlPTevMNag5e6QhPQ
kpj+TJD2frfGlLhyM50hQMdJ7YVypDllOBmnTRwZ0tJFAXm+F987ovAVLMXGJtGO
P+b3c493CfF0fQ1MBYFluVK/Wka8usg/b0pNkRGVWzBcZ1BOONYlOe/JmUyMutL5
hcciUFw5
=TcQF
-----END PGP MESSAGE-----',
					),
				),
			),
		);
		$this->setExpectedException('HttpException', "The ARO instance $userId for the model User doesn't exist or the user is not allowed to access it");
		$this->_updateCall('Resource', $rsId, $data);
	}

	public function testSimulate() {
		$userId = Common::uuid('user.id.user');
		$acoInstanceId = Common::uuid('resource.id.debian');

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
		// check how many permissions are already existing before the new insertion
		$res = $this->testAction("/share/simulate/Resource/$acoInstanceId.json", array(
				'method' => 'put',
				'return' => 'contents',
				'data' => $data
			), true);
		$json = json_decode($res, true);

		$this->assertEquals(
			Status::SUCCESS,
			$json['header']['status'],
			"Simulation of adding permissions should have returned success, but returned {$json['header']['status']}"
		);

		// Test that there is one more permissions returned by the simulation.
		$perms = $this->UserResourcePermission->find('all', array(
				'conditions' => array(
					'resource_id' => $acoInstanceId,
					'permission_type <>' => ''
				)
			));

		$this->assertEquals(
			count($perms) + 1,
			count($json['body']['UserResourcePermissions']),
			"Simulation of adding permissions should have returned " . (count($perms) + 1) . " permissions, but returned " . count($json['body']['UserResourcePermissions'])
		);

	}

	public function testSearchUsersToGrantIdIsMissing() {
		$this->setExpectedException('HttpException', "The resource id is missing");
		// go through the addAcoPermissions because of routes
		$this->testAction("/share/searchUsers/resource/", array('method' => 'get', 'return' => 'contents'));
	}

	public function testSearchUsersToGrantIdIsInvalid() {
		$id = 'badId';
		$this->setExpectedException('HttpException', "The resource id is invalid");
		$this->testAction("/share/search-users/resource/$id.json", array('method' => 'get', 'return' => 'contents'));
	}

	public function testSearchUsersToGrantDoesNotExist() {
		$id = Common::uuid('not-valid-reference');
		$this->setExpectedException('HttpException', "The resource does not exist");
		$this->testAction("/share/search-users/resource/$id.json", array('method' => 'get', 'return' => 'contents'));
	}

	public function testSearchUsersToGrantWithoutOwnerAccess() {
		$id = Common::uuid('resource.id.debian');
		$user = $this->User->findById(Common::uuid('user.id.ada'));
		$this->User->setActive($user);

		$this->setExpectedException('HttpException', "You are not authorized to share this resource");
		$this->testAction("/share/search-users/resource/$id.json", array('method' => 'get', 'return' => 'contents'));
	}

	// test search users available to receive a new direct permission : owner should be excluded
	public function testSearchUsersToGrantOwnerExcluded() {
		$id = Common::uuid('resource.id.debian');
		$getOptions = array(
			'method' => 'get',
			'return' => 'contents',
		);
		$srvResult = json_decode($this->testAction("/share/search-users/resource/$id.json", $getOptions), true);
		$usersIds = Hash::extract($srvResult['body'], '{n}.User.id');

		// The owner is not in the list of users who can receive a direct permission
		$this->assertFalse(in_array(Common::uuid('user.id.dame'), $usersIds));
	}

	// test search users available to receive a new direct permission : filter by keywords
	public function testSearchUsersToGrantFilterByKeywords() {
		$id = Common::uuid('resource.id.debian');
		$getOptions = array(
			'method' => 'get',
			'return' => 'contents',
			'data' => array(
				'keywords' => 'carol'
			)
		);
		$srvResult = json_decode($this->testAction("/share/search-users/resource/$id.json", $getOptions), true);
		$usersIds = Hash::extract($srvResult['body'], '{n}.User.id');
		$this->assertTrue(in_array(Common::uuid('user.id.carol'), $usersIds));
	}

	// test search users available to receive a new direct permission : excluding users
	public function testSearchUsersToGrantFilterExcludingUsers() {
		$id = Common::uuid('resource.id.debian');
		$getOptions = array(
			'method' => 'get',
			'return' => 'contents',
			'data' => array(
				'excludedUsers' => json_encode(array(Common::uuid('user.id.edith')))
			)
		);
		$srvResult = json_decode($this->testAction("/share/search-users/resource/$id.json", $getOptions), true);
		$usersIds = Hash::extract($srvResult['body'], '{n}.User.id');

		// The request found some users.
		$this->assertNotEmpty($usersIds);

		// The users with excluded in the request parameters is not in the request results.
		$this->assertTrue(!in_array(Common::uuid('user.id.edith'), $usersIds));
	}

	// test search users shouldn't return inactive users.
	public function testSearchUsersExcludeNonActive() {
		$this->User->id = Common::uuid('user.id.edit');
		$this->User->save(['active' => 0], false, ['active']);

		$id = Common::uuid('resource.id.debian');
		$getOptions = array(
			'method' => 'get',
			'return' => 'contents',
		);
		$srvResult = json_decode($this->testAction("/share/search-users/resource/$id.json", $getOptions), true);
		$usersIds = Hash::extract($srvResult['body'], '{n}.User.id');

		// Betty shouldn't be in the list of returned users.
		$this->assertFalse(in_array(Common::uuid('user.id.edith'), $usersIds));
	}

	// test search users shouldn't return inactive users in case of autocomplete.
	public function testSearchUsersAutocompleteExcludeNonActive() {
		$this->User->id = Common::uuid('user.id.edith');
		$this->User->save(['active' => 0], false, ['active']);

		$id = Common::uuid('resource.id.debian');
		$getOptions = array(
			'method' => 'get',
			'return' => 'contents',
			'data' => array(
				'keywords' => 'edith'
			)
		);
		$srvResult = json_decode($this->testAction("/share/search-users/resource/$id.json", $getOptions), true);
		$usersIds = Hash::extract($srvResult['body'], '{n}.User.id');

		// Betty shouldn't be in the list of returned users.
		$this->assertFalse(in_array(Common::uuid('user.id.edith'), $usersIds));
	}

	// test search users shouldn't return inactive users.
	public function testAdminSearchUsersExcludeNonActive() {
		$userD = $this->User->findById(Common::uuid('user.id.dame'));
		$this->User->id = $userD['User']['id'];
		$this->User->save(['role_id' => Common::uuid('role.id.admin')], false, ['role_id']);
		$userD['User']['role_id'] = Common::uuid('role.id.admin');
		$this->User->setActive($userD);

		$this->User->id = Common::uuid('user.id.carol');
		$this->User->save(['active' => 0], false, ['active']);

		$id = Common::uuid('resource.id.debian');
		$getOptions = array(
			'method' => 'get',
			'return' => 'contents',
		);
		$srvResult = json_decode($this->testAction("/share/search-users/resource/$id.json", $getOptions), true);
		$usersIds = Hash::extract($srvResult['body'], '{n}.User.id');

		// The user shouldn't be in the list of returned users.
		$this->assertFalse(in_array(Common::uuid('user.id.carol'), $usersIds));
	}

}
