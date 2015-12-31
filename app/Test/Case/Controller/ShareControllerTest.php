<?php
/**
 * Share Controller Tests
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @package      app.Test.Case.Controller.ShareController
 * @license      http://www.passbolt.com/license
 * @since        version 2.12.12
 */
App::uses('AppController', 'Controller');
App::uses('PermissionsController', 'Controller');
App::uses('UsersController', 'Controller');
App::uses('User', 'Model');
App::uses('Role', 'Model');
App::uses('Resource', 'Model');
App::uses('Category', 'Model');
App::uses('UserResourcePermission', 'Model');
App::uses('GroupResourcePermission', 'Model');
App::uses('UserCategoryPermission', 'Model');
App::uses('GroupCategoryPermission', 'Model');
App::uses('CakeSession', 'Model');
App::uses('CakeSession', 'Model/Datasource');

class ShareControllerTest extends ControllerTestCase {

	public $fixtures = array(
			'app.resource',
			'app.secret',
			'app.category',
			'app.categories_resource',
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
		);

	public $user;

	public $session;

	public function setUp() {
		parent::setUp();

		$this->User = Common::getModel('User');
		$u = $this->User->get();
		$this->Resource = Common::getModel('Resource');
		$this->Category = Common::getModel('Category');
		$this->Permission = Common::getModel('Permission');
		$this->UserResourcePermission = Common::getModel('UserResourcePermission');
		$this->GroupResourcePermission = Common::getModel('GroupResourcePermission');
		$this->UserCategoryPermission = Common::getModel('UserCategoryPermission');
		$this->GroupCategoryPermission = Common::getModel('GroupCategoryPermission');

		$this->session = new CakeSession();
		$this->session->init();

		// log the user as a manager to be able to access all categories
		$steve = $this->User->findByUsername('dame@passbolt.com');
		$this->User->setActive($steve);
	}

	public function tearDown() {
		// Make sure there is no session active after each test
		parent::tearDown();
		$this->User->setInactive();
	}

	private function _updateCall($resourceName = '', $data = array(), $aco = '') {
		$resourceId = '';
		if ($resourceName == 'fakeidrs') {
			$resourceId = Common::uuid('role.id.anonymous'); // Non existing id.
		}
		elseif ($resourceName == 'wrongidrs') {
			$resourceId = '0208f3a4-c5cd-11e1-a0c5-080Y27796c4c'; // Non existing id.
		}
		else {
			$resource = $this->Resource->findByName($resourceName);
			if (!$resource) {
				echo "Could not get resource";
				die();
			}
		}

		$aco = ($aco != '' ? $aco : 'Resource');
		$acoInstanceId = isset($resource['Resource']['id']) ? $resource['Resource']['id'] : $resourceId;


		// check how many permissions are already existing before the new insertion
		$res = $this->testAction("/share/$aco/$acoInstanceId.json", array(
				'method' => 'put',
				'return' => 'contents',
				'data' => $data
			), true);
		return $res;
	}

	public function testUpdateAcoNotValid() {
		$this->setExpectedException('HttpException', 'The call to entry point with parameter User is not allowed');
		$res = $this->_updateCall('tetris license', array(), 'User');
	}

	public function testUpdateNoPermissions() {
		$this->setExpectedException('HttpException', 'No permissions were provided');
		$res = $this->_updateCall('tetris license', array(), 'Resource');
	}

	public function testUpdateWrongIdProvided() {
		$this->setExpectedException('HttpException', 'The Resource id is invalid');
		$data = array(
			'Permissions' => array(
				array('Permission' => array())
			),
		);
		$res = $this->_updateCall('wrongidrs', $data, 'Resource');
	}

	public function testUpdateFakeIdProvided() {
		$this->setExpectedException('HttpException', 'The Resource id is invalid');
		$data = array(
			'Permissions' => array(
				array('Permission' => array())
			),
		);
		$res = $this->_updateCall('fakeidrs', $data, 'Resource');
	}

	public function testUpdateNoPermissionsProvided() {
		$this->setExpectedException('HttpException', 'No permissions were provided');
		$data = array(
			'Permissions' => array(

			),
		);
		$res = $this->_updateCall('facebook account', $data, 'Resource');
	}

	public function testUpdateNotAllowed() {
		$resource = $this->Resource->findByName('facebook account');
		$acoInstanceId = $resource['Resource']['id'];

		$user = $this->User->findByUsername('user@passbolt.com');
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
		$res = $this->testAction("/share/$aco/$acoInstanceId.json", array(
				'method' => 'put',
				'return' => 'contents',
				'data' => $data
			), true);
		return $res;
	}

	public function testUpdateDeleteNonExistingResource() {
		$fakeResourceId = '0208f3a4-c5cd-11e1-a0c5-080027796c4c';
		$data = array(
			'Permissions' => array(
				array(
					'Permission' => array (
						'id' => $fakeResourceId,
						'delete' => '1',
					)
				)
			),
		);
		$this->setExpectedException('HttpException', 'The permission with id 0208f3a4-c5cd-11e1-a0c5-080027796c4c does not exist');
		$this->_updateCall('facebook account', $data, 'Resource');
	}

	public function testUpdateDelete() {
		// Get a random direct perm.
		$directPerm = $this->Permission->find('first', array(
				'conditions' => array(
					'aco' => 'Resource',
					'aro' => 'User'
				)
			));

		$data = array(
			'Permissions' => array(
				array(
					'Permission' => array (
						'id' => $directPerm['Permission']['id'],
						'delete' => '1',
					)
				)
			),
		);
		$res = json_decode($this->_updateCall('facebook account', $data, 'Resource'), true);
		$this->assertEquals(
			Message::SUCCESS,
			$res['header']['status'],
			"Deleting a permission should have returned a success, but returned {$res['header']['status']}"
		);

		// Observe that the permission is deleted.
		$exist = $this->Permission->exists($directPerm['Permission']['id']);
		$this->assertFalse(
			$exist,
			"Deleting a permission should have actually deleted the permission, but the permission still exists."
		);
	}

	public function testUpdateAddSecretsNotProvided() {
		$user = $this->User->findByUsername('user@passbolt.com');

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
		$res = json_decode($this->_updateCall('facebook account', $data, 'Resource'), true);
	}

	public function testUpdateAddAlreadyExist() {
		// Get a direct permission that already exist.
		$directPerm = $this->Permission->find('first', array(
				'conditions' => array(
					'aco' => 'Resource',
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
		$this->_updateCall($resource['Resource']['name'], $data, 'Resource');
	}

	// Test update add with wrong secrets data provided. (not matching the user ids).
	public function testUpdateAddSecretForWrongUserProvided() {

		$ada = $this->User->findByUsername('ada@passbolt.com');
		$user = $this->User->findByUsername('betty@passbolt.com');
		$ce = $this->User->findByUsername('carol@passbolt.com');

		$fbRs = $this->Resource->findByName('facebook account');

		$data = array(
			'Permissions' => array(
				array(
					'Permission' => array (
						'aro_foreign_key' => $user['User']['id'],
						'type' => PermissionType::OWNER,
					),
				),
				array(
					'Permission' => array (
						'aro_foreign_key' => $ce['User']['id'],
						'type' => PermissionType::OWNER,
					),
				)
			),
			'Secrets' => array(
				array(
					'Secret' => array (
						'user_id' => $ada['User']['id'],
						'resource_id' => $fbRs['Resource']['id'],
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
						'user_id' =>$user['User']['id'],
						'resource_id' => $fbRs['Resource']['id'],
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
		$this->setExpectedException('HttpException', "The secret for user id {$ce['User']['id']} is not provided");
		$this->_updateCall('facebook account', $data, 'Resource');
	}

	public function testUpdateAddValid() {
		$user = $this->User->findByUsername('user@passbolt.com');
		$fbRs = $this->Resource->findByName('facebook account');

		$data = array(
			'Permissions' => array(
				array(
					'Permission' => array (
						'aro_foreign_key' => $user['User']['id'],
						'type' => PermissionType::OWNER,
					),
				),
			),
			'Secrets' => array(
				array(
					'Secret' => array (
						'user_id' =>$user['User']['id'],
						'resource_id' => $fbRs['Resource']['id'],
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
		$res = json_decode($this->_updateCall('facebook account', $data, 'Resource'), true);
		$this->assertEquals(
			Message::SUCCESS,
			$res['header']['status'],
			"Adding a permission should have returned a success, but returned {$res['header']['status']}"
		);

		// Observe that the permission is deleted.
		$exist = $this->Permission->find('first', array(
				'conditions' => array(
					'aco_foreign_key' => $fbRs['Resource']['id'],
					'aro_foreign_key' => $user['User']['id'],
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

		$resource = $this->Resource->findById($directPerm['Permission']['aco_foreign_key']);
		$res = json_decode($this->_updateCall($resource['Resource']['name'], $data, 'Resource'), true);
		$this->assertEquals(
			Message::SUCCESS,
			$res['header']['status'],
			"Updating a permission should have returned a success, but returned {$res['header']['status']}"
		);
	}

	public function testSimulate() {
		$user = $this->User->findByUsername('user@passbolt.com');
		$fbRs = $this->Resource->findByName('facebook account');
		$acoInstanceId = $fbRs['Resource']['id'];

		$data = array(
			'Permissions' => array(
				array(
					'Permission' => array (
						'aro_foreign_key' => $user['User']['id'],
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
			Message::SUCCESS,
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
		$srvResult = json_decode($this->testAction("/share/searchUsers/resource/", array('method' => 'get', 'return' => 'contents')), true);
	}

	public function testSearchUsersToGrantIdIsInvalid() {
		$id = 'badId';
		$this->setExpectedException('HttpException', "The resource id is invalid");
		$srvResult = json_decode($this->testAction("/share/search-users/resource/$id.json", array('method' => 'get', 'return' => 'contents')), true);
	}

	public function testSearchUsersToGrantDoesNotExist() {
		$id = '00000000-0000-0000-0000-000000000000';
		$this->setExpectedException('HttpException', "The resource does not exist");
		$srvResult = json_decode($this->testAction("/share/search-users/resource/$id.json", array('method' => 'get', 'return' => 'contents')), true);
	}

	public function testSearchUsersToGrantWithoutOwnerAccess() {
		$id = Common::uuid('resource.id.cpp2-pwd2');
		$user = $this->User->findByUsername('ada@passbolt.com');
		$this->User->setActive($user);

		$this->setExpectedException('HttpException', "You are not authorized to share this resource");
		$srvResult = json_decode($this->testAction("/share/search-users/resource/$id.json", array('method' => 'get', 'return' => 'contents')), true);
	}

	// test search users available to receive a new direct permission : owner should be excluded
	public function testSearchUsersToGrantOwnerExcluded() {
		$id = Common::uuid('resource.id.facebook-account');
		$getOptions = array(
			'method' => 'get',
			'return' => 'contents',
		);
		$srvResult = json_decode($this->testAction("/share/search-users/resource/$id.json", $getOptions), true);
		$usersIds = Hash::extract($srvResult['body'], '{n}.User.id');

		// The owner is not in the list of users who can receive a direct permission
		$this->assertFalse(in_array(Common::uuid('user.id.irene'), $usersIds));
	}

	// test search users available to receive a new direct permission : filter by keywords
	public function testSearchUsersToGrantFilterByKeywords() {
		$id = Common::uuid('resource.id.facebook-account');
		$getOptions = array(
			'method' => 'get',
			'return' => 'contents',
			'data' => array(
				'keywords' => 'betty'
			)
		);
		$srvResult = json_decode($this->testAction("/share/search-users/resource/$id.json", $getOptions), true);
		$usersIds = Hash::extract($srvResult['body'], '{n}.User.id');

		// The user Betty is in the list of retrieved users.
		$this->assertTrue(in_array(Common::uuid('user.id.betty'), $usersIds));
	}

	// test search users available to receive a new direct permission : excluding users
	public function testSearchUsersToGrantFilterExcludingUsers() {
		$id = Common::uuid('resource.id.facebook-account');
		$getOptions = array(
			'method' => 'get',
			'return' => 'contents',
			'data' => array(
				'excludedUsers' => json_encode(array(Common::uuid('user.id.betty')))
			)
		);
		$srvResult = json_decode($this->testAction("/share/search-users/resource/$id.json", $getOptions), true);
		$usersIds = Hash::extract($srvResult['body'], '{n}.User.id');

		// The request found some users.
		$this->assertNotEmpty($usersIds);

		// The users with excluded in the request parameters is not in the request results.
		$this->assertTrue(!in_array(Common::uuid('user.id.betty'), $usersIds));
	}

}