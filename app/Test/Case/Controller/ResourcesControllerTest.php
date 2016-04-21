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
App::uses('CategoryResource', 'Model');
App::uses('Category', 'Model');
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
		'app.category',
		'app.resource',
		'app.categoryType',
		'app.categoriesResource',
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
		$this->Category = ClassRegistry::init('Category');
		$user = $this->User->findById(common::uuid('user.id.dame'));
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

	public function testViewResourceIdIsMissing()
	{
		// Unable to test missing id param because of route
	}

	public function testViewResourceIdNotValid()
	{
		// test an error bad id
		$this->setExpectedException('HttpException', 'The resource id is invalid');
		$result = json_decode($this->testAction("/resources/badid.json?children=true", array(
			'method' => 'get',
			'return' => 'contents'
		)), true);
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
		$resId = Common::uuid('resource.id.cpp1-pwd1');

		// Looking at the matrix of permission Irene should not be able to read the resource cpp1-pwd1
		$user = $this->User->findById(common::uuid('user.id.irene'));
		$this->User->setActive($user);

		$this->setExpectedException('HttpException', 'The resource does not exist');
		$result = json_decode($this->testAction("/resources/{$resId}.json", array(
			'method' => 'Get',
			'return' => 'contents'
		)), true);
	}

	public function testView()
	{
		$resId = Common::uuid('resource.id.facebook-account');

		// test if the object returned is a success one
		$result = json_decode($this->testAction("/resources/view/$resId.json", array('return' => 'contents')), true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status'],
			'resources/view/' . $resId . '.json should return success');

		$this->assertEquals('facebook account', $result['body']['Resource']['name'],
			'resources/view/' . $resId . ".json should a resource named 'facebook account' but returned {$result['body']['Resource']['name']} instead");
	}

	public function testIndexFilterCategoryDoesntExist()
	{
		$this->setExpectedException('HttpException', 'The category doesn\'t exist');
		$catId = Common::uuid('not-valid-reference');
		$url = '/resources.json?recursive=true&fltr_model_category=' . $catId;
		json_decode($this->testAction($url, array('return' => 'contents', 'method' => 'get')), true);
	}

	public function testIndexAndPermission()
	{
		$matrix = PermissionMatrix::importCsv(TESTS . '/Data/resources_users_permissions.csv', 'user');

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

	public function testIndexFilterByCategoryWhichIsEmpty()
	{
		$catEmptyId = Common::uuid('category.id.empty');

		// test with category parameter specified which does not contain resources
		$url = '/resources/index.json';
		$result = json_decode(
			$this->testAction(
				$url,
				array(
					'method' => 'get',
					'return' => 'contents',
					'data' => array(
						'fltr_model_category' => $catEmptyId
					)
				)
			),
			true
		);
		$this->assertEquals(Status::SUCCESS, $result['header']['status'],
			"{$url} : should return success but returned {$result['header']['status']}");
		$this->assertTrue(empty($result['body']), "{$url} : should not contain result");
	}

	public function testIndexFilterByCategory()
	{
		$catCp2Id = Common::uuid('category.id.cp-project2');

		// test with category parameter specified which contains resources
		$url = "/resources/index.json";
		$result = json_decode(
			$this->testAction(
				$url,
				array(
					'method' => 'get',
					'return' => 'contents',
					'data' => array(
						'fltr_model_category' => $catCp2Id
					)
				)
			),
			true
		);
		$this->assertEquals(Status::SUCCESS, $result['header']['status'],
			"{$url} : should return success but returned {$result['header']['status']}");
		$this->assertEquals(2, count($result['body']),
			"{$url} : counting the number of elements should return '2' but is reading " . count($result['body']));

		$path = $this->Resource->inNestedArray(Common::uuid('resource.id.cpp2-pwd1'), $result['body'], 'id');
		$this->assertTrue(!empty($path), "{$url} : test should contain the resource");
		$path = $this->Resource->inNestedArray(Common::uuid('resource.id.cpp2-pwd2'), $result['body'], 'id');
		$this->assertTrue(!empty($path), "{$url} : test should contain the resource");
	}

	public function testAddWithCategoryBadId()
	{
		$this->setExpectedException('HttpException', 'Could not validate CategoryResource');
		$this->testAction('/resources.json', array(
			'data' => array(
				'Resource' => array(
					'name' => 'test3',
					'username' => 'test3',
					'uri' => 'http://www.google.com',
					'description' => 'this is a description'
				),
				'Secret' => $this->_encryptSecretFor(array(common::uuid('user.id.dame')), '', 'testAddWithCategoryBadId secret'),
				'Category' => array(
					0 => array(
						'id' => 'BadId'
					)
				)
			),
			'method' => 'post',
			'return' => 'contents'
		));
	}

	public function testAddWithCategoryDoesNotExist()
	{
		$catId = Common::uuid('not-valid-reference');
		$this->setExpectedException('HttpException', 'Could not validate CategoryResource');
		// Test with wrong id for category
		$this->testAction('/resources.json', array(
			'data' => array(
				'Resource' => array(
					'name' => 'test3',
					'username' => 'test3',
					'uri' => 'http://www.google.com',
					'description' => 'this is a description'
				),
				'Secret' => $this->_encryptSecretFor(array(common::uuid('user.id.dame')), '', 'testAddWithCategoryDoesNotExist secret'),
				'Category' => array(
					0 => array(
						'id' => $catId
					)
				)
			),
			'method' => 'post',
			'return' => 'contents'
		));
	}

	public function testAddAndPermission()
	{
		$catId = Common::uuid('category.id.administration');

		// Looking at the matrix of permission marlyn should be able to read but not to create into the category marketing
		$user = $this->User->findById(common::uuid('user.id.marlyn'));
		$this->User->setActive($user);

		// Error : name is empty
		$this->setExpectedException('HttpException', 'You are not authorized to create a resource into the category');
		$this->testAction('/resources/add.json', array(
			'data' => array(
				'Resource' => array(
					'name' => 'testAddAndPermission',
					'username' => 'test1',
					'uri' => 'http://www.google.com',
					'description' => 'this is a description'
				),
				'Secret' => $this->_encryptSecretFor(array(common::uuid('user.id.marlyn')), '', 'testAddAndPermission secret'),
				'Category' => array(
					0 => array(
						'id' => $catId
					)
				)
			),
			'method' => 'Post',
			'return' => 'contents'
		));
	}

	public function testAdd() {
		// Add without Category
		$result = json_decode($this->testAction('/resources.json', array(
			'data' => array(
				'Resource' => array(
					'name' => 'test1',
					'username' => 'test1',
					'uri' => 'http://www.google.com',
					'description' => 'this is a description'
				),
				'Secret' => $this->_encryptSecretFor(array(common::uuid('user.id.dame')), '', 'testAdd secret'),
			),
			'method' => 'post',
			'return' => 'contents'
		)), true);

		$this->assertEquals(Status::SUCCESS, $result['header']['status'],
			"Add : /resources.json : The test should return sucess but is returning {$result['header']['status']} : " . print_r($result, true));

		// check that the resource has been saved has been saved
		$resource = $this->Resource->findByName("test1");
		$rsId = $resource["Resource"]["id"];
		$this->assertNotEmpty($resource);

		// check that the resource is not associated with any category
		$catRes = $this->Resource->CategoryResource->find('all', array(
			'conditions' => array(
				'resource_id' => $rsId
			)
		));
		$this->assertempty($catRes,
			"Add : /resources.json : The number of categories returned should be 0, but actually is " . count($catRes));
	}

	public function testAddInCategory()
	{
		$rootCatId = Common::uuid('category.id.bolt');

		$result = json_decode($this->testAction('/resources.json', array(
			'data' => array(
				'Resource' => array(
					'name' => 'test2',
					'username' => 'test2',
					'uri' => 'http://www.google.com',
					'description' => 'this is a description'
				),
				'Secret' => $this->_encryptSecretFor(array(common::uuid('user.id.dame')), '', 'testAddInCategory secret'),
				'Category' => array(
					0 => array(
						'id' => $rootCatId
					)
				)
			),
			'method' => 'post',
			'return' => 'contents'
		)), true);

		$this->assertEquals(Status::SUCCESS, $result['header']['status'],
			"Add : /resources.json : The test should return sucess but is returning " . print_r($result, true));

		// check that the resource has been saved has been saved
		$resource = $this->Resource->findByName("test2");
		$rsId = $resource["Resource"]["id"];
		$this->assertNotEmpty($resource);

		// check that the association with the category has been saved
		$catRes = $this->Resource->CategoryResource->find('all', array(
			'conditions' => array(
				'resource_id' => $rsId
			)
		));
		$this->assertEquals(1, count($catRes),
			"Add : /resources.json : The number of categories returned should be 1, but actually is " . count($catRes));
		$this->assertEquals($rootCatId, $catRes['0']['CategoryResource']['category_id'],
			"Add : /resources.json : the category inserted should be {$rootCatId} but is {$catRes['0']['CategoryResource']['category_id']}");
	}

	/**
	 * Test adding a resource with a secret
	 */
	public function testAddWithSecret()
	{
		$rootCat = $this->Resource->CategoryResource->Category->findByName('Bolt Softwares Pvt. Ltd.');

		$result = json_decode($this->testAction('/resources.json', array(
			'data' => array(
				'Resource' => array(
					'name' => 'test3',
					'username' => 'test3',
					'uri' => 'http://www.google.com',
					'description' => 'this is a description'
				),
				'Category' => array(
					0 => array(
						'id' => $rootCat['Category']['id']
					)
				),
				'Secret' => $this->_encryptSecretFor(array(common::uuid('user.id.dame')), '', 'testAddWithSecret secret'),
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
		$cat = $this->Category->findByName('administration');

		// Looking at the matrix of permission marlyn should be able to read but not to create into the category marketing
		$user = $this->User->findById(common::uuid('user.id.marlyn'));
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

	public function testEditWithCategoryBadId()
	{
		$rsId = Common::uuid("resource.id.facebook-account");

		$this->setExpectedException('HttpException', 'Could not validate CategoryResource');
		// Test with a bad format of category
		$result = json_decode($this->testAction("/resources/$rsId.json", array(
			'data' => array(
				'Category' => array(
					0 => array(
						'id' => '8u7'
					)
				)
			),
			'method' => 'put',
			'return' => 'contents'
		)), true);
	}

	public function testEditAndPermission()
	{
		$rsId = Common::uuid("resource.id.tetris-license");

		// Looking at the matrix of permission marlyn should be able to read but not to update the resource tetris license
		$user = $this->User->findById(common::uuid('user.id.marlyn'));
		$this->User->setActive($user);

		// Error : name is empty
		$this->setExpectedException('HttpException', 'You are not authorized to edit this resource');
		$result = json_decode($this->testAction("/resources/{$rsId}.json", array(
			'data' => array(
				'Resource' => array(
					'name' => 'testEditAndPermission'
				)
			),
			'method' => 'Put',
			'return' => 'contents'
		)), true);
	}

	/**
	 * Test edit a resource with secrets, and providing an invalid number of secrets.
	 */
	public function testEditWithSecretsInvalidNumberOfSecrets()
	{
		$user = $this->User->findById(common::uuid('user.id.marlyn'));
		$this->User->setActive($user);

		$resource = $this->Resource->find('first', [
			'conditions' => [
				'name' => "salesforce account"
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
		$result = $this->testAction("/resources/{$resource['Resource']['id']}.json", array(
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
		$user = $this->User->findById(common::uuid('user.id.marlyn'));
		$this->User->setActive($user);

		$resource = $this->Resource->find('first', [
			'conditions' => [
				'name' => "salesforce account"
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
		$result = $this->testAction("/resources/{$resource['Resource']['id']}.json", array(
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
		$user = $this->User->findById(common::uuid('user.id.marlyn'));
		$this->User->setActive($user);

		$resource = $this->Resource->find('first', [
			'conditions' => [
				'name' => "salesforce account"
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
		$user = $this->User->findById(common::uuid('user.id.marlyn'));
		$this->User->setActive($user);

		$resource = $this->Resource->find('first', [
			'conditions' => [
				'name' => "salesforce account"
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

	/**
	 * Test a normal edit operation.
	 */
	public function testEdit()
	{
		$rootCatId = Common::uuid('category.id.bolt');
		$catAccountId = Common::uuid('category.id.accounts');
		$rsId = Common::uuid('resource.id.facebook-account');
		$resource = $this->Resource->findById($rsId);
		$catRs = $this->Resource->CategoryResource->find('all', array('conditions' => array('resource_id' => $rsId)));

		// Update the resource
		$resource['Resource']['name'] = "test";
		$data = array(
			'Resource' => $resource['Resource'],
			'Category' => array(
				0 => array(
					'id' => $catAccountId
				),
				1 => array(
					'id' => $rootCatId
				)
			)
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
		$updatedRsCat = $this->Resource->CategoryResource->find('all', array('conditions' => array('resource_id' => $rsId)));
		$this->assertEquals("test", $updatedResource['Resource']['name'],
			"update /resources/$rsId.json : The test should have modified the name into 'test', but name is still {$updatedResource['Resource']['name']}");
		$this->assertEquals(count($updatedRsCat), 2, "2 results are expected");
	}

	public function testDeleteResourceIdIsMissing()
	{
		$this->setExpectedException('HttpException', 'The resource id is missing');
		$result = json_decode($this->testAction("/resources.json", array(
			'method' => 'delete',
			'return' => 'contents'
		)), true);
	}

	public function testDeleteResourceIdNotValid()
	{
		$this->setExpectedException('HttpException', 'The resource id is invalid');
		$result = json_decode($this->testAction("/resources/badid.json", array(
			'method' => 'delete',
			'return' => 'contents'
		)), true);
	}

	public function testDeleteResourceDoesNotExist()
	{
		$id = Common::uuid('not-valid-reference');
		$this->setExpectedException('HttpException', 'The resource does not exist');
		$result = json_decode($this->testAction("/resources/{$id}.json", array(
			'method' => 'delete',
			'return' => 'contents'
		)), true);
	}

	public function testDeleteAndPermission()
	{
		$rsId = Common::uuid('resource.id.bank-password');

		// Looking at the matrix of permission marlyn should be able to read but not to delete the resource facebook
		$user = $this->User->findById(common::uuid('user.id.marlyn'));
		$this->User->setActive($user);

		// Error : name is empty
		$this->setExpectedException('HttpException', 'You are not authorized to delete this resource');
		$result = json_decode($this->testAction("/resources/{$rsId}.json", array(
			'method' => 'delete',
			'return' => 'contents'
		)), true);
	}

	public function testDelete()
	{
		$rsId = Common::uuid('resource.id.facebook-account');

		$result = json_decode($this->testAction("/resources/$rsId.json", array(
			'method' => 'delete',
			'return' => 'contents'
		)), true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status'],
			"delete /resources/$rsId.json : The test should return a success but is returning {$result['header']['status']}");
	}

}
