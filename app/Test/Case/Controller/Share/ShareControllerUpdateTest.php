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
App::uses('UserResourcePermission', 'Model');;
App::uses('CakeSession', 'Model');
App::uses('CakeSession', 'Model/Datasource');

class ShareControllerUpdateTest extends ControllerTestCase {

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
		$this->Secret = Common::getModel('Secret');
		$this->Permission = Common::getModel('Permission');
		$this->UserResourcePermission = Common::getModel('UserResourcePermission');

		$this->session = new CakeSession();
		$this->session->init();

		$this->user = $this->User->findById(Common::uuid('user.id.dame'));
		$this->User->setActive($this->user);
	}

	public function tearDown() {
		// Make sure there is no session active after each test
		parent::tearDown();
		$this->User->setInactive();
	}

	private function _updateCall($aco = 'Resource', $acoId = '', $data = array()) {
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
		$this->_updateCall('User', Common::uuid('resource.id.debian'), array());
	}

	public function testUpdateNoPermissions() {
		$this->setExpectedException('HttpException', 'No permission data provided.');
		$this->_updateCall('Resource', Common::uuid('resource.id.debian'), array());
	}

	public function testUpdateWrongIdProvided() {
		$this->setExpectedException('HttpException', 'The aco id is not valid');
		$data = array(
			'Permissions' => array(
				array('Permission' => array())
			),
		);
		$this->_updateCall('Resource', 'badid', $data);
	}

	public function testUpdateFakeIdProvided() {
		$this->setExpectedException('HttpException', 'The aco id is not valid');
		$data = array(
			'Permissions' => array(
				array('Permission' => array())
			),
		);
		$this->_updateCall('Resource', Common::uuid(), $data);
	}

	public function testUpdateNoPermissionsProvided() {
		$this->setExpectedException('HttpException', 'No permission data provided');
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

	public function testUpdateDeleteUserPermission() {
		$resourceId = Common::uuid('resource.id.centos');
		$userId = Common::uuid('user.id.ada');
		$permissionId = Common::uuid('permission.id.' . $resourceId . '-' . $userId);

		// Check that the permission exists before deleting.
		$exist = $this->Permission->exists($permissionId);
		$this->assertTrue($exist);

		// Delete the permission
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
		$res = json_decode($this->_updateCall('Resource', $resourceId, $data), true);
		$this->assertEquals(Status::SUCCESS, $res['header']['status'],
			"Deleting a permission should have returned a success, but returned {$res['header']['status']}"
		);

		// Observe that the permission is deleted.
		$exist = $this->Permission->exists($permissionId);
		$this->assertFalse($exist, "Deleting a permission should have actually deleted the permission, but the permission still exists.");

		// Observe that the secret has been destroyed
		$secret = $this->Secret->find('first', array('conditions' => array(
			'resource_id' => $resourceId,
			'user_id' => $userId
		)));
		$this->assertEmpty($secret);
	}

	public function testUpdateDeleteGroupPermission() {
		$this->User->setInactive();
		$this->user = $this->User->findById(Common::uuid('user.id.ada'));
		$this->User->setActive($this->user);

		$resourceId = Common::uuid('resource.id.cakephp');
		$groupId = Common::uuid('group.id.freelancer');
		$permissionId = Common::uuid('permission.id.' . $resourceId . '-' . $groupId);

		// Check that the permission exists before deleting.
		$exist = $this->Permission->exists($permissionId);
		$this->assertTrue($exist);

		// Delete the permission
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
		$res = json_decode($this->_updateCall('Resource', $resourceId, $data), true);
		$this->assertEquals(Status::SUCCESS, $res['header']['status'],
			"Deleting a permission should have returned a success, but returned {$res['header']['status']}"
		);

		// Observe that the permission is deleted.
		$exist = $this->Permission->exists($permissionId);
		$this->assertFalse($exist);

		// Observe that the secret of a member of the group has also been destroyed
		$secret = $this->Secret->find('first', array('conditions' => array(
			'resource_id' => $resourceId,
			'user_id' => Common::uuid('user.id.jean')
		)));
		$this->assertEmpty($secret);
	}

	public function testUpdateAddSecretsNotProvided() {
		$acoInstanceId = Common::uuid('resource.id.centos');
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
		$this->setExpectedException('HttpException', 'The number of secrets provided does not match the 1 users who have now access to the resources');
		$this->_updateCall('Resource', $acoInstanceId, $data);
	}

	public function testUpdateAddAlreadyExist() {
		// Get a direct permission that already exist.
		$directPerm = $this->Permission->find('first', array(
				'conditions' => array(
					'aco' => 'Resource',
					'aco_foreign_key' => Common::uuid('resource.id.centos'),
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
		$userFId = Common::uuid('user.id.nancy');
		$rsId = Common::uuid('resource.id.centos');

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

	public function testUpdateAddUserPermission() {
		$userId = Common::uuid('user.id.user');
		$rsId = Common::uuid('resource.id.centos');

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
			"Adding a permission should have actually added the permission, but the permission does not exist."
		);
	}

	public function testUpdateAddGroupPermission() {
		$groupId = Common::uuid('group.id.board');
		$userId = Common::uuid('user.id.hedy');
		$rsId = Common::uuid('resource.id.centos');

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
				'aro_foreign_key' => $groupId,
				'type' => PermissionType::OWNER,
			)
		));

		$this->assertTrue(
			!empty($exist),
			"Adding a permission should have actually added the permission, but the permission doesn't exist."
		);
	}

	public function testUpdateUpdateExistingUserPermission() {
		// Get a direct permission that already exist.
		$directPerm = $this->Permission->find('first', array(
				'conditions' => array(
					'aco' => 'Resource',
					'aco_foreign_key' => Common::uuid('resource.id.centos'),
					'aro' => 'User',
					'aro_foreign_key' => Common::uuid('user.id.ada')
				)
			));
		$data = array(
			'Permissions' => array(
				array(
					'Permission' => array (
						'id' => $directPerm['Permission']['id'],
						'aro_foreign_key' => $directPerm['Permission']['aro_foreign_key'],
						'type' => PermissionType::UPDATE,
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
		$rsId = Common::uuid('resource.id.centos');

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
		$this->setExpectedException('HttpException', "The ARO instance $userId for the model User does not exist or the user is not allowed to access it");
		$this->_updateCall('Resource', $rsId, $data);
	}

}
