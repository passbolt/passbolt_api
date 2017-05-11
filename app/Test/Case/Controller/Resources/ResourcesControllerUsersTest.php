<?php
/**
 * Resources Controller Users Tests
 *
 * @copyright (c) 2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('CakeSession', 'Model');
App::uses('CakeSession', 'Model/Datasource');
App::uses('AppController', 'Controller');
App::uses('ResourcesController', 'Controller');
App::uses('Resource', 'Model');
App::uses('User', 'Model');
App::uses('Role', 'Model');
App::uses('PermissionMatrix', 'DataSeleniumTests.Data');

// Uses Gpg Utility.
if (!class_exists('\Passbolt\Gpg')) {
	App::import( 'Model/Utility', 'Gpg' );
}

class ResourcesControllerUsersTest extends ControllerTestCase
{

	public $fixtures = array(
		'app.resource',
		'app.secret',
		'app.favorite',
		'app.log',
		'app.user',
		'app.gpgkey',
		'app.profile',
		'app.file_storage',
		'app.group',
		'app.groupsUser',
		'app.role',
		'app.gpgkey',
		'app.email_queue',
		'app.permission',
		'app.permissions_type',
		'app.permission_view',
		'core.cakeSession',
		'app.user_agent',
		'app.controller_log'
	);

	public function setUp() {
		parent::setUp();
		$this->User = ClassRegistry::init('User');
		$this->Gpgkey = ClassRegistry::init('Gpgkey');
		$this->Resource = ClassRegistry::init('Resource');
		$user = $this->User->findById(Common::uuid('user.id.dame'));
		$this->User->setActive($user);
	}

/**
 * Test fails if resource id is not valid uuid
 */
	 public function testUsersResourceIdNotValid() {
		// test an error bad id
		$this->setExpectedException('BadRequestException', 'The resource id is not valid.');
		$this->testAction("/resources/badid/users.json", array(
			'method' => 'get',
			'return' => 'contents'
		));
	}

/**
 * Test fails if resource does not exist
 */
	public function testUsersResourceDoesNotExist() {
		$id = Common::uuid('not-valid-reference');
		// test when a wrong id is provided
		$this->setExpectedException('NotFoundException', 'The resource does not exist.');
		$this->testAction("/resources/{$id}/users.json", array(
			'method' => 'get',
			'return' => 'contents'
		));
	}

/**
 * Test view associated users request fails if the user does not have right to view
 */
	public function testUsersForbiddenAccess() {
		$resId = Common::uuid('resource.id.apache');

		// Looking at the matrix of permission Irene should not be able to read the resource cpp1-pwd1
		$user = $this->User->findById(Common::uuid('user.id.frances'));
		$this->User->setActive($user);

		$this->setExpectedException('NotFoundException', 'The resource does not exist.');
		$this->testAction("/resources/{$resId}/users.json", array(
			'method' => 'Get',
			'return' => 'contents'
		));
	}

/**
 * Test view associated users fails if the resource was deleted before
 */
	public function testUsersViewDeletedResource() {
		$rsId = Common::uuid('resource.id.debian');

		// delete the resource
		$result = json_decode($this->testAction("/resources/$rsId.json", array(
			'method' => 'delete',
			'return' => 'contents'
		)), true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status'],
			"delete /resources/$rsId.json : The test should return a success but is returning {$result['header']['status']}");

		// View the resource
		$this->setExpectedException('NotFoundException', 'The resource does not exist.');
		$this->testAction("/resources/$rsId/users.json", array('method' => 'get', 'return' => 'contents'));
	}

/**
 * Test view associated users success
 */
	public function testUsersSuccess() {
		$rsId = Common::uuid('resource.id.apache');
		// test if the object returned is a success one
		$result = json_decode($this->testAction("/resources/$rsId/users.json", array('method' => 'get', 'return' => 'contents')), true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status']);
		$this->assertNotEmpty($result['body'][0]['User']['username']);
	}

/**
 * Test view users fails if user does not have permission (all matrix)
 */
	public function testUsersAndPermission() {
		$matrixExpectedUsersIds = array();

		$matrixPath = TESTS . '/Data/view_users_resources_permissions.csv';
		$matrix = PermissionMatrix::importCsv($matrixPath, 'resource');
		foreach ($matrix as $resourceAlias => $userPermissions) {
			// Retrieve the direct users permissions defined for the resource
			$matrixExpectedUsersIds[$resourceAlias] = array();
			foreach($userPermissions as $userAlias => $permission) {
				if ($permission != '0') {
					$matrixExpectedUsersIds[$resourceAlias][] = Common::uuid('user.id.' . $userAlias);
				}
			}
		}

		$getOptions = array(
			'method' => 'get',
			'return' => 'contents'
		);
		foreach($matrixExpectedUsersIds as $resourceAlias => $expectedUsersIds) {
			// Login with an authorized user.
			$userId = $expectedUsersIds[0];
			$user = $this->User->findById($userId);
			$this->User->setActive($user);

			// Get the list of users the resource is shared with.
			$rsId = Common::uuid('resource.id.' . $resourceAlias);
			$srvResult = json_decode($this->testAction("/resources/$rsId/users.json", $getOptions), true);
			$usersIds = Hash::extract($srvResult['body'], '{n}.User.id');

			// Check that the resource is shared as expected.
			$this->assertEquals(sort($expectedUsersIds), sort($usersIds));
		}
	}

}
