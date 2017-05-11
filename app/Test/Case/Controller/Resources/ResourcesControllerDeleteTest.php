<?php
/**
 * Resources Controller Delete Tests
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

class ResourcesControllerDeleteTest extends ControllerTestCase
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

	public function setUp()
	{
		parent::setUp();
		$this->User = ClassRegistry::init('User');
		$this->Gpgkey = ClassRegistry::init('Gpgkey');
		$this->Resource = ClassRegistry::init('Resource');
		$user = $this->User->findById(Common::uuid('user.id.dame'));
		$this->User->setActive($user);
	}

/**
 * Test delete fails if resource id is missing
 */
	public function testDeleteResourceIdIsMissing() {
		$this->setExpectedException('BadRequestException', 'The resource id is missing.');
		$this->testAction("/resources.json", array(
			'method' => 'delete',
			'return' => 'contents'
		));
	}

/**
 * Test delete fails if resource id is not valid uuid
 */
	public function testDeleteResourceIdNotValid() {
		$this->setExpectedException('BadRequestException', 'The resource id is not valid.');
		$this->testAction("/resources/badid.json", array(
			'method' => 'delete',
			'return' => 'contents'
		));
	}

/**
 * Test delete fails if resource does not exist
 */
	public function testDeleteResourceDoesNotExist() {
		$id = Common::uuid('not-valid-reference');
		$this->setExpectedException('NotFoundException', 'The resource does not exist.');
		$this->testAction("/resources/{$id}.json", array(
			'method' => 'delete',
			'return' => 'contents'
		));
	}

/**
 * Test delete fails if user does not have the right to delete the resource
 */
	public function testDeleteNotAllowed() {
		$rsId = Common::uuid('resource.id.debian');

		// Login with a user who is not allowed
		$user = $this->User->findById(Common::uuid('user.id.marlyn'));
		$this->User->setActive($user);

		// Expect HttpException
		$this->setExpectedException('NotFoundException', 'The resource does not exist.');
		$this->testAction("/resources/{$rsId}.json", array(
			'method' => 'delete',
			'return' => 'contents'
		));
	}

/**
 * Test delete fails if user does not have the right to delete the resource (all matrix)
 */
	public function testDeleteAndPermission() {
		$matrixPath = TESTS . '/Data/view_users_resources_permissions.csv';
		$matrix = PermissionMatrix::importCsv($matrixPath, 'user');

		foreach ($matrix as $userAlias => $expectedPermissions) {
			// Reload fixture - cause content is altered in each loop.
			$this->loadFixtures('Resource', 'Permission');

			foreach ($expectedPermissions as $resourceAlias => $expectedPermission) {
				try{
					// Log the user
					$userId = Common::uuid("user.id.$userAlias");
					$user = $this->User->findById($userId);
					$this->User->setActive($user);

					// Try to delete the resource
					$rsId = Common::uuid("resource.id.$resourceAlias");
					$url = "/resources/$rsId.json";
					$this->testAction($url, array('return' => 'contents', 'method' => 'delete'));

					// If the user shouldn't be allowed
					if (intval($expectedPermission) < 7) {
						$this->assertTrue(false, "The user $userAlias shouldn't have the right to delete the resource $resourceAlias");
					}
					$this->assertTrue(true);
				}
				catch(HttpException $e) {
					// If the user should be allowed
					if (intval($expectedPermission) >=7) {
						$this->assertTrue(false, "The user $userAlias should have the right to delete the resource $resourceAlias");
					}
					$this->assertTrue(true);
				}
			}
		}
	}

/**
 * Test delete works
 */
	public function testDeleteSuccess() {
		$rsId = Common::uuid('resource.id.debian');

		$result = json_decode($this->testAction("/resources/$rsId.json", array(
			'method' => 'delete',
			'return' => 'contents'
		)), true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status'],
			"delete /resources/$rsId.json : The test should return a success but is returning {$result['header']['status']}");
	}

/**
 * Test delete fails if resource was already deleted
 */
	public function testDeleteAlreadyDeletedResource() {
		$rsId = Common::uuid('resource.id.debian');

		// delete the resource
		$result = json_decode($this->testAction("/resources/$rsId.json", array(
			'method' => 'delete',
			'return' => 'contents'
		)), true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status'],
			"delete /resources/$rsId.json : The test should return a success but is returning {$result['header']['status']}");

		// try to delete the resource a second time
		$this->setExpectedException('HttpException', 'The resource does not exist');
		$this->testAction("/resources/{$rsId}.json", array(
			'method' => 'delete',
			'return' => 'contents'
		));
	}
}
