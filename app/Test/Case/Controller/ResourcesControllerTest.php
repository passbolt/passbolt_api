<?php
/**
 * Resources Controller Tests
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @package       app.Test.Case.Controller.ResourcesControllerTest
 * @since         version 2.12.9
 */
App::uses('CakeSession', 'Model');
App::uses('CakeSession', 'Model/Datasource');
App::uses('AppController', 'Controller');
App::uses('ResourcesController', 'Controller');
App::uses('Resource', 'Model');
App::uses('User', 'Model');
App::uses('Role', 'Model');
App::uses('PermissionMatrix', 'Test/Data');

// Uses Gpg Utility.
if (!class_exists('\Passbolt\Gpg')) {
	App::import( 'Model/Utility', 'Gpg' );
}

class ResourcesControllerTest extends ControllerTestCase
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
		'app.authenticationBlacklist',
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

	private function _encryptSecretFor($usersUuid, $resourceId, $text) {
		$gpg = new \Passbolt\Gpg();
		$secretData = array();

		foreach ($usersUuid as $userUuid) {
			$gpgKey = $this->Gpgkey->findByUserId($userUuid);
			$gpg->setEncryptKey($gpgKey['Gpgkey']['key']);
			$armoredSecret = $gpg->encrypt($text);
			$secretData[] = array(
				'user_id' => $usersUuid,
				'resource_id' => $resourceId,
				'data' => $armoredSecret
			);
		}

		return $secretData;
	}

/******************************************************
 * VIEW TESTS
 ******************************************************/

	public function testViewResourceIdIsMissing()
	{
		// Unable to test missing id param because of route
	}

	public function testViewResourceIdNotValid()
	{
		// test an error bad id
		$this->setExpectedException('HttpException', 'The resource id is invalid');
		$this->testAction("/resources/badid.json?children=true", array(
			'method' => 'get',
			'return' => 'contents'
		));
	}

	public function testViewResourceDoesNotExist()
	{
		$id = Common::uuid('not-valid-reference');
		// test when a wrong id is provided
		$this->setExpectedException('HttpException', 'The resource does not exist');
		$result = json_decode($this->testAction("/resources/{$id}.json", array(
			'method' => 'get',
			'return' => 'contents'
		)), true);
	}

	public function testViewAndPermission()
	{
		$resId = Common::uuid('resource.id.apache');

		// Looking at the matrix of permission Irene should not be able to read the resource cpp1-pwd1
		$user = $this->User->findById(Common::uuid('user.id.irene'));
		$this->User->setActive($user);

		$this->setExpectedException('HttpException', 'You are not authorized to access this resource');
		$this->testAction("/resources/{$resId}.json", array(
			'method' => 'Get',
			'return' => 'contents'
		));
	}

	public function testViewDeletedResource()
	{
		$rsId = Common::uuid('resource.id.debian');

		// delete the resource
		$result = json_decode($this->testAction("/resources/$rsId.json", array(
			'method' => 'delete',
			'return' => 'contents'
		)), true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status'],
			"delete /resources/$rsId.json : The test should return a success but is returning {$result['header']['status']}");

		// View the resource
		$this->setExpectedException('HttpException', 'The resource does not exist');
		$this->testAction("/resources/view/$rsId.json", array('return' => 'contents'));
	}

	public function testView()
	{
		$resId = Common::uuid('resource.id.apache');

		// test if the object returned is a success one
		$result = json_decode($this->testAction("/resources/view/$resId.json", array('return' => 'contents')), true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status']);
		$this->assertEquals('apache', $result['body']['Resource']['name']);
	}

/******************************************************
 * INDEX TESTS
 ******************************************************/

	public function testIndexAndPermission()
	{
		$matrix = PermissionMatrix::importCsv(TESTS . '/Data/view_users_resources_permissions.csv', 'user');

		foreach ($matrix as $userAlias => $userPermissions) {
			$userId = Common::uuid('user.id.' . $userAlias);
			$user = $this->User->findById($userId);
			$this->User->setActive($user);

			$url = '/resources/index.json';
			$result = json_decode($this->testAction($url, array('return' => 'contents', 'method' => 'get')), true);
			$this->assertEquals(Status::SUCCESS, $result['header']['status'],
				"{$url} : The test should return a success but is returning {$result['header']['status']}");

			foreach ($userPermissions as $resourceAlias => $resourcePermission) {
				$resourceId = Common::uuid('resource.id.' . $resourceAlias);
				$path = $this->Resource->inNestedArray($resourceId, $result['body'], 'id');
				if ($resourcePermission == PermissionType::DENY) {
					$this->assertTrue(empty($path),
						"{$url} : test should not contain '{$resourceAlias}' resource with user '{$userAlias}'");
				} else {
					$this->assertTrue(!empty($path),
						"{$url} : test should contain '{$resourceAlias}' resource with user '{$userAlias}'");
				}
			}
		}
	}

	public function testIndex()
	{
		$url = '/resources/index.json';
		$result = json_decode($this->testAction($url, array('return' => 'contents', 'method' => 'get')), true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status'],
			"{$url} : The test should return a success but is returning {$result['header']['status']}");
		$this->assertTrue(!empty($result['body']), "{$url} : should contain result");
	}

/******************************************************
 * ADD TESTS
 ******************************************************/

	public function testAdd() {
		$result = json_decode($this->testAction('/resources.json', array(
			'data' => array(
				'Resource' => array(
					'name' => 'test1',
					'username' => 'test1',
					'uri' => 'http://www.google.com',
					'description' => 'this is a description'
				),
				'Secret' => $this->_encryptSecretFor(array(Common::uuid('user.id.dame')), '', 'testAdd secret'),
			),
			'method' => 'post',
			'return' => 'contents'
		)), true);

		$this->assertEquals(Status::SUCCESS, $result['header']['status'],
			"Add : /resources.json : The test should return sucess but is returning {$result['header']['status']} : " . print_r($result, true));

		// check that the resource has been saved has been saved
		$resource = $this->Resource->findByName("test1");
		$this->assertNotEmpty($resource);
	}

	/**
	 * Test adding a resource with a secret
	 */
	public function testAddWithSecret()
	{
		$result = json_decode($this->testAction('/resources.json', array(
			'data' => array(
				'Resource' => array(
					'name' => 'test3',
					'username' => 'test3',
					'uri' => 'http://www.google.com',
					'description' => 'this is a description'
				),
				'Secret' => $this->_encryptSecretFor(array(Common::uuid('user.id.dame')), '', 'testAddWithSecret secret'),
			),
			'method' => 'post',
			'return' => 'contents'
		)), true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status'],
			"Add : /resources.json : The test should return success but is returning " . print_r($result, true));

		// check that Secret has been saved
		$secret = $this->Resource->Secret->findByResourceId($result['body']['Resource']['id']);
		$this->assertTrue(!empty($secret), "Add : /resources.json : Secret should have been inserted but is not");
	}

	/**
	 * Test adding a resource with a wrong secret, and test that the rollback was effective.
	 */
	public function testAddWithWrongSecretRollback()
	{
		$user = $this->User->findById(Common::uuid('user.id.marlyn'));
		$this->User->setActive($user);

		// Error : name is empty
		try {
			$this->testAction('/resources/add.json', array(
				'data' => array(
					'Resource' => array(
						'name' => 'testAddSecretRollback',
						'username' => 'test1',
						'uri' => 'http://www.google.com',
						'description' => 'this is a description'
					),
					'Secret' => array(
						array(
							'data' => 'wrong secret'
						),
					)
				),
				'method' => 'Post',
				'return' => 'contents'
			));
		} catch (Exception $e) {
			$this->assertEquals($e->getMessage(), 'Could not validate secret model');
			// Get the resource again, and assert it's the exact same
			$resourceAfterCreate = $this->Resource->find('first', [
				'conditions' => [
					'name' => 'testAddSecretRollback'
				],
				'contain' => ['Secret']
			]);

			$this->assertEmpty($resourceAfterCreate,
				'After a secret validation error, the resource should not exist in the database');
		}
	}

/******************************************************
 * EDIT TESTS
 ******************************************************/

	public function testEditAndPermission()
	{
		$matrix = PermissionMatrix::importCsv(TESTS . '/Data/view_users_resources_permissions.csv', 'user');

		foreach ($matrix as $userAlias => $userPermissions) {
			foreach ($userPermissions as $resourceAlias => $resourcePermission) {
				try{
					// Log the user
					$userId = Common::uuid("user.id.$userAlias");
					$user = $this->User->findById($userId);
					$this->User->setActive($user);

					// Try to edit the resource
					$rsId = Common::uuid("resource.id.$resourceAlias");
					$url = "/resources/$rsId.json";
					$this->testAction($url, array('return' => 'contents', 'method' => 'put'));

					// If we are the user must have the right, check its permission
					if (intval($resourcePermission) < 7) {
						$this->assertTrue(false, "The user $userAlias shouldn't have the right to edit the resource $resourceAlias");
					}
					$this->assertTrue(true);
				}
				catch(HttpException $e) {
					// If we are the user must not have the right, check its permission
					if (intval($resourcePermission) >=7) {
						$this->assertTrue(false, "The user $userAlias should have the right to edit the resource $resourceAlias");
					}
					$this->assertTrue(true);
				}
			}
		}
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
			$result = $this->testAction("/resources/{$resource['Resource']['id']}.json", array(
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
					'id' => $resource['Resource']['id']
				],
				'contain' => ['Secret']
			]);
			$this->assertEquals($resourceAfterUpdate, $resource,
				$e->getMessage() . 'After an error, the update should have performed a rollback but did not' . print_r($resourceAfterUpdate,
					true) . print_r($resource, true));
		}
	}

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
		$this->setExpectedException('HttpException', 'The resource does not exist');
		$this->testAction("/resources/$rsId.json", array(
			'data' => $data,
			'method' => 'put',
			'return' => 'contents'
		));
	}

	/**
	 * Test a normal edit operation.
	 */
	public function testEdit()
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

/******************************************************
 * DELETE TESTS
 ******************************************************/

	public function testDeleteResourceIdIsMissing()
	{
		$this->setExpectedException('HttpException', 'The resource id is missing');
		$this->testAction("/resources.json", array(
			'method' => 'delete',
			'return' => 'contents'
		));
	}

	public function testDeleteResourceIdNotValid()
	{
		$this->setExpectedException('HttpException', 'The resource id is invalid');
		$this->testAction("/resources/badid.json", array(
			'method' => 'delete',
			'return' => 'contents'
		));
	}

	public function testDeleteResourceDoesNotExist()
	{
		$id = Common::uuid('not-valid-reference');
		$this->setExpectedException('HttpException', 'The resource does not exist');
		$this->testAction("/resources/{$id}.json", array(
			'method' => 'delete',
			'return' => 'contents'
		));
	}

	public function testDeleteAndPermission()
	{
		$rsId = Common::uuid('resource.id.debian');

		// Looking at the matrix of permission marlyn should be able to read but not to delete the resource facebook
		$user = $this->User->findById(Common::uuid('user.id.marlyn'));
		$this->User->setActive($user);

		// Error : name is empty
		$this->setExpectedException('HttpException', 'You are not authorized to delete this resource');
		$this->testAction("/resources/{$rsId}.json", array(
			'method' => 'delete',
			'return' => 'contents'
		));
	}

	public function testDelete()
	{
		$rsId = Common::uuid('resource.id.debian');

		$result = json_decode($this->testAction("/resources/$rsId.json", array(
			'method' => 'delete',
			'return' => 'contents'
		)), true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status'],
			"delete /resources/$rsId.json : The test should return a success but is returning {$result['header']['status']}");
	}

	public function testDeleteAlreadyDeletedResource()
	{
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

/******************************************************
 * USERS (shared users) TESTS
 ******************************************************/

	public function testUsersResourceIdNotValid()
	{
		// test an error bad id
		$this->setExpectedException('HttpException', 'The resource id is invalid');
		$this->testAction("/resources/badid/users.json", array(
			'method' => 'get',
			'return' => 'contents'
		));
	}

	public function testUsersResourceDoesNotExist()
	{
		$id = Common::uuid('not-valid-reference');
		// test when a wrong id is provided
		$this->setExpectedException('HttpException', 'The resource does not exist');
		$this->testAction("/resources/{$id}/users.json", array(
			'method' => 'get',
			'return' => 'contents'
		));
	}

	public function testUsersForbiddenAccess()
	{
		$resId = Common::uuid('resource.id.apache');

		// Looking at the matrix of permission Irene should not be able to read the resource cpp1-pwd1
		$user = $this->User->findById(Common::uuid('user.id.irene'));
		$this->User->setActive($user);

		$this->setExpectedException('HttpException', 'You are not authorized to access this resource');
		$this->testAction("/resources/{$resId}/users.json", array(
			'method' => 'Get',
			'return' => 'contents'
		));
	}

	public function testUsersViewDeletedResource()
	{
		$rsId = Common::uuid('resource.id.debian');

		// delete the resource
		$result = json_decode($this->testAction("/resources/$rsId.json", array(
			'method' => 'delete',
			'return' => 'contents'
		)), true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status'],
			"delete /resources/$rsId.json : The test should return a success but is returning {$result['header']['status']}");

		// View the resource
		$this->setExpectedException('HttpException', 'The resource does not exist');
		$this->testAction("/resources/$rsId/users.json", array('method' => 'get', 'return' => 'contents'));
	}

	public function testUsers()
	{
		$rsId = Common::uuid('resource.id.apache');
		// test if the object returned is a success one
		$result = json_decode($this->testAction("/resources/$rsId/users.json", array('method' => 'get', 'return' => 'contents')), true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status']);
		$this->assertNotEmpty($result['body'][0]['User']['username']);
	}

	public function testUsersAndPermission()
	{
		$matrixExpectedUsersIds = array();

		$matrix = PermissionMatrix::importCsv(TESTS . '/Data/view_users_resources_permissions.csv', 'resource');
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
