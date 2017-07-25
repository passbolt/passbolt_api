<?php
/**
 * Resources Controller Edit Tests
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

class ResourcesControllerEditTest extends ControllerTestCase
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
 * Test edit a resource with secrets, and providing an invalid number of secrets.
 */
	public function testEditWithSecretsInvalidNumberOfSecrets()
	{
		$resource = $this->Resource->find('first', [
			'conditions' => [
				'name' => "debian"
			],
			'contain' => ['Secret']
		]);
		$secretsData = [];
		foreach ($resource['Secret'] as $secret) {
			$secretsData[] = [
				'user_id' => $secret['user_id'],
				'data' => $secret['data']
			];
		}
		array_pop($secretsData);

		$this->setExpectedException('HttpException', 'The list of secrets provided is invalid');
		$this->testAction("/resources/{$resource['Resource']['id']}.json", array(
			'data' => array(
				'Resource' => array(
					'name' => 'testEditSecret'
				),
				'Secret' => $secretsData,
			),
			'method' => 'Put',
			'return' => 'contents'
		));
	}

/**
 * Test edit a resource with secrets, with one of the secrets having an invalid user.
 */
	public function testEditWithSecretsInvalidSecretUsers()
	{
		$user = $this->User->findById(Common::uuid('user.id.dame'));
		$this->User->setActive($user);

		$resource = $this->Resource->find('first', [
			'conditions' => [
				'name' => "debian"
			],
			'contain' => ['Secret']
		]);
		$secretsData = [];
		foreach ($resource['Secret'] as $secret) {
			$secretsData[] = [
				'user_id' => $secret['user_id'],
				'data' => $secret['data']
			];
		}
		$last = array_pop($secretsData);
		$last['user_id'] = 'randominvaliduserid';
		$secretsData[] = $last;

		$this->setExpectedException('HttpException', 'The list of secrets provided is invalid');
		$this->testAction("/resources/{$resource['Resource']['id']}.json", array(
			'data' => array(
				'Resource' => array(
					'name' => 'testEditSecret'
				),
				'Secret' => $secretsData,
			),
			'method' => 'Put',
			'return' => 'contents'
		));
	}

/**
 * Test edit a resource with secrets, with one of the secrets having an invalid user.
 */
	public function testEditWithSecrets()
	{
		$user = $this->User->findById(Common::uuid('user.id.dame'));
		$this->User->setActive($user);

		$resource = $this->Resource->find('first', [
			'conditions' => [
				'name' => "debian"
			],
			'contain' => ['Secret']
		]);
		$secretsData = [];
		foreach ($resource['Secret'] as $secret) {
			$secretsData[] = [
				'user_id' => $secret['user_id'],
				'data' => $secret['data']
			];
		}

		$result = $this->testAction("/resources/{$resource['Resource']['id']}.json", array(
			'data' => array(
				'Resource' => array(
					'name' => 'testEditSecret1'
				),
				'Secret' => $secretsData,
			),
			'method' => 'Put',
			'return' => 'contents'
		));
		$json = json_decode($result, true);
		$this->assertEquals(
			Status::SUCCESS,
			$json['header']['status'],
			"update /resources/{$resource['Resource']['id']}.json : The test should return a success but is returning {$json['header']['status']}"
		);

		$this->assertNotEmpty($json['body']['Secret'], "The update should return the updated secrets");
	}

/**
 * Test that rollback is effective.
 */
	public function testEditWithSecretsRollback()
	{
		$user = $this->User->findById(Common::uuid('user.id.dame'));
		$this->User->setActive($user);

		$resource = $this->Resource->find('first', [
			'conditions' => [
				'name' => "debian"
			],
			'contain' => ['Secret']
		]);

		$secretsData = [];
		foreach ($resource['Secret'] as $secret) {
			$secretsData[] = [
				'user_id' => $secret['user_id'],
				'data' => $secret['data']
			];
		}
		$last = array_pop($secretsData);
		$last['data'] = 'Wrongkey';
		$secretsData[] = $last;

		try {
			$this->testAction("/resources/{$resource['Resource']['id']}.json", array(
				'data' => array(
					'Resource' => array(
						'name' => 'testEditSecret',
						'description' => 'wtf'
					),
					'Secret' => $secretsData,
				),
				'method' => 'Put',
				'return' => 'contents'
			));
		} catch (Exception $e) {
			$this->assertEquals($e->getMessage(), 'Could not validate secret model',
				'Error message is not what is expected');
			// Get the resource again, and assert it's the exact same
			$resourceAfterUpdate = $this->Resource->find('first', [
				'conditions' => [
					'Resource.id' => $resource['Resource']['id']
				],
				'contain' => ['Secret']
			]);
			$this->assertEquals($resourceAfterUpdate, $resource,
				$e->getMessage() . 'After an error, the update should have performed a rollback but did not' . print_r($resourceAfterUpdate,
					true) . print_r($resource, true));
		}
	}

/**
 * Test edit fails if user try to edit a resource that was deleted.
 */
	public function testEditDeletedResource()
	{
		$rsId = Common::uuid('resource.id.debian');

		// delete the resource
		$result = json_decode($this->testAction("/resources/$rsId.json", array(
			'method' => 'delete',
			'return' => 'contents'
		)), true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status'],
			"delete /resources/$rsId.json : The test should return a success but is returning {$result['header']['status']}");

		// Update the resource
		$data = array(
			'Resource' => array(
				'name' => "Edit a deleted resource"
			)
		);
		$this->setExpectedException('NotFoundException', 'The resource does not exist');
		$this->testAction("/resources/$rsId.json", array(
			'data' => $data,
			'method' => 'put',
			'return' => 'contents'
		));
	}

/**
 * Test edit throws exception if user try to edit a resource without permission
 */
	public function testEditNotAllowed() {
		$rsId = Common::uuid('resource.id.debian');

		// Login with a user who is not allowed
		$user = $this->User->findById(Common::uuid('user.id.marlyn'));
		$this->User->setActive($user);

		// Expect HttpException
		$this->setExpectedException('ForbiddenException', 'You are not authorized to edit this resource');
		$this->testAction("/resources/{$rsId}.json", array(
			'method' => 'put',
			'return' => 'contents'
		));
	}

/**
 * Test edit throw an exception if user try to edit a resource without permission (test all matrix)
 */
	public function testEditAndPermission()
	{
		$matrix = PermissionMatrix::importCsv(TESTS . '/Data/view_users_resources_permissions.csv', 'user');

		foreach ($matrix as $userAlias => $expectedPermissions) {
			foreach ($expectedPermissions as $resourceAlias => $expectedPermission) {
				try{
					// Log the user
					$userId = Common::uuid("user.id.$userAlias");
					$user = $this->User->findById($userId);
					$this->User->setActive($user);

					// Try to edit the resource
					$rsId = Common::uuid("resource.id.$resourceAlias");
					$url = "/resources/$rsId.json";
					$this->testAction($url, array('return' => 'contents', 'method' => 'put'));

					// If the user shouldn't be allowed
					if (intval($expectedPermission) < 7) {
						$this->assertTrue(false, "The user $userAlias shouldn't have the right to edit the resource $resourceAlias");
					}
					$this->assertTrue(true);
				}
				catch(HttpException $e) {
					// If the user should be allowed
					if (intval($expectedPermission) >=7) {
						$this->assertTrue(false, "The user $userAlias should have the right to edit the resource $resourceAlias");
					}
					$this->assertTrue(true);
				}
			}
		}
	}

/**
 * Test a normal edit operation.
 */
	public function testEditSuccess()
	{
		$rsId = Common::uuid('resource.id.debian');
		$resource = $this->Resource->findById($rsId);

		// Update the resource
		$resource['Resource']['name'] = "test";
		$data = array(
			'Resource' => $resource['Resource']
		);
		$result = json_decode($this->testAction("/resources/$rsId.json", array(
			'data' => $data,
			'method' => 'put',
			'return' => 'contents'
		)), true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status'],
			"update /resources/$rsId.json : The test should return a success but is returning {$result['header']['status']}");

		// Retrieve the resource and ensure the changes have been applied
		$updatedResource = $this->Resource->findById($rsId);
		$this->assertEquals("test", $updatedResource['Resource']['name'],
			"update /resources/$rsId.json : The test should have modified the name into 'test', but name is still {$updatedResource['Resource']['name']}");
	}
}