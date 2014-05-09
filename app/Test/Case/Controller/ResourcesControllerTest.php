<?php
/**
 * Resources Controller Tests
 *
 * @copyright     Copyright 2012, Passbolt.com
 * @license       http://www.passbolt.com/license
 * @package       app.Test.Case.Controller.ResourcesControllerTest
 * @since         version 2.12.9
 */
App::uses('AppController', 'Controller');
App::uses('ResourcesController', 'Controller');
App::uses('Resource', 'Model');
App::uses('CategoryResource', 'Model');
App::uses('Category', 'Model');
App::uses('User', 'Model');
App::uses('Role', 'Model');

// uses sessions
if (!class_exists('CakeSession')) {
	require CAKE . 'Model/Datasource/CakeSession.php';
}

class ResourcesControllerTest extends ControllerTestCase {

	public $fixtures = array(
		'app.category',
		'app.resource',
		'app.categoryType',
		'app.categoriesResource',
		'app.secret',
		'app.favorite',
		'app.log',
		'app.user',
		'app.profile',
		'app.group',
		'app.groupsUser',
		'app.role',
		'app.permission',
		'app.permissions_type',
		'app.permission_view',
		'app.authenticationBlacklist'
	);

	public function setUp() {
		$this->User = new User();
		$this->User->useDbConfig = 'test';
		$this->Resource = new Resource();
		$this->Resource->useDbConfig = 'test';
		$this->Category = new Category();
		$this->Category->useDbConfig = 'test';
		parent::setUp();

		$kk = $this->User->findByUsername('dark.vador@passbolt.com');
		$this->User->setActive($kk);
	}

	public function testViewResourceIdIsMissing() {
		// Unable to test missing id param because of route
	}

	public function testViewResourceIdNotValid() {
		// test an error bad id
		$this->expectException('HttpException', 'The resource id is invalid');
		$result = json_decode($this->testAction("/resources/badid.json?children=true", array(
			'method' => 'get',
			'return' => 'contents'
		)), true);
	}

	public function testViewResourceDoesNotExist() {
		// test when a wrong id is provided
		$this->expectException('HttpException', 'The resource does not exist');
		$result = json_decode($this->testAction("/resources/4ff6111b-efb8-4a26-aab4-2184cbdd56ca.json", array(
			'method' => 'get',
			'return' => 'contents'
		)), true);
	}

	public function testViewAndPermission() {
		// Error : name is empty
		$res = $this->Resource->findByName('cpp1-pwd1');

		// Looking at the matrix of permission Isma should not be able to read the resource cpp1-pwd1
		$user = $this->User->findByUsername('ismail@passbolt.com');
		$this->User->setActive($user);

		$this->expectException('HttpException', 'The resource does not exist');
		$result = json_decode($this->testAction("/resources/{$res['Resource']['id']}.json", array(
			'method' => 'Get',
			'return' => 'contents'
		)), true);
	}

	public function testView() {
		$festival = $this->Resource->findByName('facebook account');
		$id = $festival['Resource']['id'];

		// test if the object returned is a success one
		$result = json_decode($this->testAction("/resources/view/$id.json", array('return' => 'contents')), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], 'resources/view/' . $id . '.json should return success');

		$this->assertEquals('facebook account', $result['body']['Resource']['name'], 'resources/view/' . $id . ".json should a resource named 'facebook account' but returned {$result['body']['Resource']['name']} instead");
	}

	// TODO Test filter

	public function testIndex() {
		$rootCat = $this->Resource->CategoryResource->Category->findByName('Bolt Softwares Pvt. Ltd.');
		$cpCat2 = $this->Resource->CategoryResource->Category->findByName('cp-project2');
		$cpCat3 = $this->Resource->CategoryResource->Category->findByName('cp-project3');
		$emptyCat = $this->Resource->CategoryResource->Category->findByName('empty');

		// test when no parameters are provided
		$url = '/resources/index.json';
		$result = json_decode($this->testAction($url, array('return' => 'contents', 'method' => 'get')), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "{$url} : The test should return an error but is returning {$result['header']['status']}");
		$this->assertTrue(!empty($result['body']), "{$url} : should contain result");

		// test with category parameter specified which does not contain resources
		$url = '/resources/index.json';
		$result = json_decode(
			$this->testAction(
				$url,
				array(
					'method' => 'get',
					'return' => 'contents',
					'data' => array(
						'fltr_model_category' => $emptyCat['Category']['id']
					)
				)
			),
			true
		);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "{$url} : should return success but returned {$result['header']['status']}");
		$this->assertTrue(empty($result['body']), "{$url} : should not contain result");

		// test with category parameter specified which contains resources
		$url = "/resources/index.json";
		$result = json_decode(
			$this->testAction(
				$url,
				array(
					'method' => 'get',
					'return' => 'contents',
					'data' => array(
						'fltr_model_category' => $cpCat2['Category']['id']
					)
				)
			),
			true
		);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "{$url} : should return success but returned {$result['header']['status']}");
		$this->assertEquals(2, count($result['body']), "{$url} : counting the number of elements should return '2' but is reading " . count($result['body']));
		$path = $this->Resource->inNestedArray('cpp2-pwd2', $result['body'], 'name');
		$this->assertTrue(!empty($path), "{$url} : test should contain 'cpp2-pwd2' resource");

		// test with recursive parameter on the top category
		$url = "/resources/index.json";
		$result = json_decode(
			$this->testAction(
				$url,
				array(
					'method' => 'get',
					'return' => 'contents',
					'data' => array(
						'fltr_model_category' => $rootCat['Category']['id'],
						'recursive' => 'true'
					)
				)
			),
			true
		);
		$path = $this->Resource->inNestedArray('cpp2-pwd2', $result['body'], 'name');
		$this->assertTrue(!empty($path), "{$url} : test should contain 'cpp2-pwd2' resource");
		$this->assertEquals(14, count($result['body']), "{$url} : counting the number of elements should return '14' but is reading " . count($result['body']));
	}

	public function testAddWithCategoryBadId() {
		$this->expectException('HttpException', 'Could not validate CategoryResource');
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
						'id' => 'BadId'
					)
				)
			),
			'method' => 'post',
			'return' => 'contents'
		)), true);
	}

	public function testAddWithCategoryDoesNotExist() {
		$this->expectException('HttpException', 'Could not validate CategoryResource');
		// Test with wrong id for category
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
						'id' => '4ff6111b-efb8-4a26-aab4-2184cbdd56hg'
					)
				)
			),
			'method' => 'post',
			'return' => 'contents'
		)), true);
	}

	public function testAddAndPermission() {
		$cat = $this->Category->findByName('administration');

		// Looking at the matrix of permission Myriam should be able to read but not to create into the category marketing
		$user = $this->User->findByUsername('myriam@passbolt.com');
		$this->User->setActive($user);

		// Error : name is empty
		$this->expectException('HttpException', 'You are not authorized to create a resource into the category');
		$result = json_decode($this->testAction('/resources/add.json', array(
			'data' => array(
				'Resource' => array(
					'name' => 'testAddAndPermission',
					'username' => 'test1',
					'uri' => 'http://www.google.com',
					'description' => 'this is a description'
				),
				'Category' => array(
					0 => array(
						'id' => $cat['Category']['id']
					)
				)
			),
			'method' => 'Post',
			'return' => 'contents'
		)), true);
	}

	public function testAdd() {
		$rootCat = $this->Resource->CategoryResource->Category->findByName('Bolt Softwares Pvt. Ltd.');

		$result = json_decode($this->testAction('/resources.json', array(
			'data' => array(
				'Resource' => array(
					'name' => 'test1',
					'username' => 'test1',
					'uri' => 'http://www.google.com',
					'description' => 'this is a description'
				),
				'Category' => array(
					0 => array(
						'id' => $rootCat['Category']['id']
					)
				)
			),
			'method' => 'post',
			'return' => 'contents'
		)), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "Add : /resources.json : The test should return sucess but is returning " . print_r($result, true));
		// check that Categories were properly saved
		$resource = $this->Resource->findByName("test1");
		$catres = $this->Resource->CategoryResource->find('all', array(
			'conditions' => array(
				'resource_id' => $resource["Resource"]["id"]
			)
		));
		$this->assertEquals(1, count($catres), "Add : /resources.json : The number of categories returned should be 1, but actually is " . count($catres));
		$this->assertEquals($rootCat['Category']['id'], $catres['0']['CategoryResource']['category_id'], "Add : /resources.json : the category inserted should be {$rootCat['Category']['id']} but is {$catres['0']['CategoryResource']['category_id']}");

		// Add without Category
		$result = json_decode($this->testAction('/resources.json', array(
			'data' => array(
				'Resource' => array(
					'name' => 'test2',
					'username' => 'test2',
					'uri' => 'http://www.google.com',
					'description' => 'this is a description'
				)
			),
			'method' => 'post',
			'return' => 'contents'
		)), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "Add : /resources.json : The test should return sucess but is returning {$result['header']['status']} : " . print_r($result, true));
	}

/**
 * Test adding a resource with a secret
 */
	public function testAddWithSecret() {
		$rootCat = $this->Resource->CategoryResource->Category->findByName('Bolt Softwares Pvt. Ltd.');

		$result = json_decode($this->testAction('/resources.json', array(
			'data' => array(
				'Resource' => array(
					'name' => 'test1',
					'username' => 'test1',
					'uri' => 'http://www.google.com',
					'description' => 'this is a description'
				),
				'Category' => array(
					0 => array(
						'id' => $rootCat['Category']['id']
					)
				),
				'Secret' => array(
					'data' => 'This is a test'
				),
			),
			'method' => 'post',
			'return' => 'contents'
		)), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "Add : /resources.json : The test should return sucess but is returning " . print_r($result, true));
		// check that Categories were properly saved
		$secret = $this->Resource->Secret->findByResourceId($result['body']['Resource']['id']);
		$this->assertTrue(!empty($secret), "Add : /resources.json : Secret should have been inserted but is not");
	}

	public function testEditWithCategoryBadId() {
		$resource = $this->Resource->findByName("facebook account");
		$id = $resource['Resource']['id'];
		$this->expectException('HttpException', 'Could not validate CategoryResource');
		// Test with a bad format of category
		$result = json_decode($this->testAction("/resources/$id.json", array(
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
		$this->assertEquals(Message::ERROR, $result['header']['status'], "Add : /resources.json : The test should return error but is returning {$result['header']['status']} : " . print_r($result, true));
		$after = $this->Resource->CategoryResource->find('all', array('conditions' => array('resource_id' => $id)));
		$this->assertTrue(count($after) == 0, "update /resources/$id.json : After this test, there should be no categories associated to the resource anymore.");
	}

	public function testEditAndPermission() {
		$resource = $this->Resource->findByName("tetris license");

		// Looking at the matrix of permission Myriam should be able to read but not to update the resource tetris license
		$user = $this->User->findByUsername('myriam@passbolt.com');
		$this->User->setActive($user);

		// Error : name is empty
		$this->expectException('HttpException', 'You are not authorized to edit this resource');
		$result = json_decode($this->testAction("/resources/{$resource['Resource']['id']}.json", array(
			'data' => array(
				'Resource' => array(
					'name' => 'testEditAndPermission'
				)
			),
			'method' => 'Put',
			'return' => 'contents'
		)), true);
	}

	public function testEdit() {
		$rootCat = $this->Resource->CategoryResource->Category->findByName('Bolt Softwares Pvt. Ltd.');
		$accountCat = $this->Resource->CategoryResource->Category->findByName('accounts');
		$resource = $this->Resource->findByName("facebook account");
		$id = $resource['Resource']['id'];

		$resource['Resource']['name'] = "test";
		$before = $this->Resource->CategoryResource->find('all', array('conditions' => array('resource_id' => $id)));

		$result = json_decode($this->testAction("/resources/$id.json", array(
			'data' => array(
				'Resource' => $resource['Resource'],
				'Category' => array(
					0 => array(
						'id' => $accountCat['Category']['id']
					),
					1 => array(
						'id' => $rootCat['Category']['id']
					)
				)
			),
			'method' => 'put',
			'return' => 'contents'
		)), true);

		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "update /resources/$id.json : The test should return a success but is returning {$result['header']['status']}");
		$result = $this->Resource->findById($id);
		$this->assertEquals("test", $result['Resource']['name'], "update /resources/$id.json : The test should have modified the name into 'test', but name is still {$result['Resource']['name']}");

		$after = $this->Resource->CategoryResource->find('all', array('conditions' => array('resource_id' => $id)));

		$this->assertEquals(count($after), 2, "2 results are expected");
		$this->Resource->CategoryResource->create();
		$this->Resource->CategoryResource->set(array(
			'CategoryResource' => array(
				'resource_id' => $id,
				'category_id' => $rootCat['Category']['id']
			)
		));
		$this->assertEquals($this->Resource->CategoryResource->uniqueCombi(), false, "update /resources/$id.json : The resource should now belong to category id {$rootCat['Category']['id']}");
		$this->Resource->CategoryResource->create();
		$this->Resource->CategoryResource->set(array(
			'CategoryResource' => array(
				'resource_id' => $id,
				'category_id' => $accountCat['Category']['id']
			)
		));
		$this->assertEquals($this->Resource->CategoryResource->uniqueCombi(), false, "update /resources/$id.json : The resource should now belong to category id {$accountCat['Category']['id']}");
	}

	public function testDeleteResourceIdIsMissing() {
		$this->expectException('HttpException', 'The resource id is missing');
		$result = json_decode($this->testAction("/resources.json", array(
			'method' => 'delete',
			'return' => 'contents'
		)), true);
	}

	public function testDeleteResourceIdNotValid() {
		$this->expectException('HttpException', 'The resource id is invalid');
		$result = json_decode($this->testAction("/resources/badid.json", array(
			'method' => 'delete',
			'return' => 'contents'
		)), true);
	}

	public function testDeleteResourceDoesNotExist() {
		$this->expectException('HttpException', 'The resource does not exist');
		$result = json_decode($this->testAction("/resources/4ff6111b-efb8-4a26-aab4-2184cbdd56ca.json", array(
			'method' => 'delete',
			'return' => 'contents'
		)), true);
	}

	public function testDeleteAndPermission() {
		// Looking at the matrix of permission Myriam should be able to read but not to delete the resource facebook
		$user = $this->User->findByUsername('myriam@passbolt.com');
		$this->User->setActive($user);

		$res = $this->Resource->findByName('bank password');

		// Error : name is empty
		$this->expectException('HttpException', 'You are not authorized to delete this resource');
		$result = json_decode($this->testAction("/resources/{$res['Resource']['id']}.json", array(
			'method' => 'delete',
			'return' => 'contents'
		)), true);
	}

	public function testDelete() {
		$res = $this->Resource->findByName('facebook account');
		$id = $res['Resource']['id'];
		$result = json_decode($this->testAction("/resources/$id.json", array(
			'method' => 'delete',
			'return' => 'contents'
		)), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "delete /resources/$id.json : The test should return a success but is returning {$result['header']['status']}");
	}

}
