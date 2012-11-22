<?php
/**
 * Resources Controller Tests
 *
 * @copyright     Copyright 2012, Passbolt.com
 * @license       http://www.passbolt.com/license
 * @package       app.Test.Case.Controller.ResourcesControllerTest
 * @since         version 2.12.9
 */
App::uses('ResourcesController', 'Controller');
App::uses('Resource', 'Model');
App::uses('User', 'Model');
App::uses('Role', 'Model');

// uses sessions
if (!class_exists('CakeSession')) {
	require CAKE . 'Model/Datasource/CakeSession.php';
}

class ResourcesControllerTest extends ControllerTestCase {

	public $fixtures = array('app.resource', 'app.category', 'app.category_resource', 'app.secret', 'app.user', 'app.role');

	public function setUp() {
		parent::setUp();
		$user = new User();

		$user->useDbConfig = 'test';
		$kk = $user->findByUsername('user@passbolt.com');
		$user->setActive($kk);

		$this->Resource = new Resource();
		$this->Resource->useDbConfig = 'test';

		$this->CategoryResource = new CategoryResource();
		$this->CategoryResource->useDbConfig = 'test';
	}

	public function testView() {
		$festival = $this->Resource->findByName('facebook account');
		$id = $festival['Resource']['id'];

		// test when no parameters are provided
		$result = json_decode($this->testAction("/resources/view.json", array('return' => 'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "/resources/view.json : The test should return an error but is returning {$result['header']['status']}");

		// test when a wrong id is provided
		$result = json_decode($this->testAction("/resources/view/4ff6111b-efb8-4a26-aab4-2184cbdd56ca.json", array('return' => 'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "/resources/view/4ff6111b-efb8-4a26-aab4-2184cbdd56ca.json : The test should return an error but is returning {$result['header']['status']}");

		// test if the object returned is a success one
		$result = json_decode($this->testAction("/resources/view/$id.json", array('return' => 'contents')), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'],
			'resources/view/' . $id . '.json should return success'
		);

		$this->assertEquals('facebook account', $result['body']['Resource']['name'],
			'resources/view/' . $id . ".json should a resource named 'facebook account' but returned {$result['body']['Resource']['name']} instead"
		);
	}

	public function testViewByCategory() {
 		$categoryModel = new Category();
		$categoryModel->useDbConfig = 'test';
		$projectCat = $categoryModel->findByName('cp-project2');
		$rootCat = $categoryModel->findByName('Bolt Softwares Pvt. Ltd.');

		$id = $projectCat['Category']['id'];

		// test when no parameters are provided
		$result = json_decode($this->testAction("/resources/viewByCategory.json", array('return' => 'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "/resources/viewByCategory.json : The test should return an error but is returning {$result['header']['status']}");

		// test when a wrong id is provided
		$result = json_decode($this->testAction("/resources/viewByCategory/4ff6111b-efb8-4a26-aab4-2184cbdd56ca.json", array('return' => 'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "/resources/viewByCategory/4ff6111b-efb8-4a26-aab4-2184cbdd56ca.json : The test should return an error but is returning {$result['header']['status']}");

		// test if the object returned is a success one
		$url = "/resources/viewByCategory/" . $id . ".json";
		$result = json_decode($this->testAction($url, array('return' => 'contents')), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'],
			$url . " should return success but returned {$result['header']['status']}"
		);
		$this->assertEquals('cpp2-pwd2', $result['body'][1]['Resource']['name'],
			$url . " test should read 'festival du cinema' but is reading {$result['body'][1]['Resource']['name']}"
		);
		$this->assertEquals(2, count($result['body']),
			$url . " counting the number of elements should return '2' but is reading " . count($result['body'])
		);

		$id = $rootCat['Category']['id'];
		$url = "/resources/viewByCategory/" . $id . "/1.json";
		$result = json_decode($this->testAction($url, array('return' => 'contents')), true);
		$this->assertEquals('cpp2-pwd2', $result['body'][4]['Resource']['name'],
			$url . " test should read 'cpp2-pwd2' but is reading {$result['body'][4]['Resource']['name']}"
		);
		$this->assertEquals(11, count($result['body']),
			$url . " counting the number of elements should return '11' but is reading " . count($result['body'][1]['CategoryResource'])
		);

		// Test when the category is empty
		$mapusaCat = $categoryModel->findByName('cp-project3');
		$id = $mapusaCat['Category']['id'];
		// should return success
		$result = json_decode($this->testAction("/resources/viewByCategory/$id.json", array('return' => 'contents')), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "/resources/viewByCategory/$id.json : The test should return sucess but is returning {$result['header']['status']}");
		// should return an empty array
		$result = json_decode($this->testAction("/resources/viewByCategory/$id.json", array('return' => 'contents')), true);
		$this->assertEquals(0, count($result['body']), "/resources/viewByCategory/$id.json : The test should count 0 elements but is actually counting " . count($result['body']));
	}

	public function testAdd() {
		$categoryModel = new Category();
		$categoryModel->useDbConfig = 'test';
		$rootCat = $categoryModel->findByName('Bolt Softwares Pvt. Ltd.');

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
		$catres = $this->CategoryResource->find('all', array(
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

		// Test with a bad format of category
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
						'id' => '8u7'
					 )
				)
			 ),
			 'method' => 'post',
			 'return' => 'contents'
		)), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "Add : /resources.json : The test should return error but is returning {$result['header']['status']} : " . print_r($result, true));

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
		$this->assertEquals(Message::ERROR, $result['header']['status'], "Add : /resources.json : The test should return error but is returning {$result['header']['status']} : " . print_r($result, true));
	}

/**
 * Test adding a resource with a secret 
 */
	public function testAddWithSecret() {
		$categoryModel = new Category();
		$categoryModel->useDbConfig = 'test';
		$rootCat = $categoryModel->findByName('Bolt Softwares Pvt. Ltd.');

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

	public function testEdit() {
		$categoryModel = new Category();
		$categoryModel->useDbConfig = 'test';
		$rootCat = $categoryModel->findByName('Bolt Softwares Pvt. Ltd.');
		$accountCat = $categoryModel->findByName('accounts');
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
		$this->assertEquals($after[0]['CategoryResource']['category_id'], $accountCat['Category']['id'], "update /resources/$id.json : The resource should now belong to category id {$accountCat['Category']['id']} but belongs to {$after[0]['CategoryResource']['resource_id']} instead");

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

	public function testDelete() {
		$res = $this->Resource->findByName('facebook account');
		$id = $res['Resource']['id'];
		$result = json_decode($this->testAction("/resources/$id.json", array(
			 'method' => 'delete',
			 'return' => 'contents'
		)), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "delete /resources/$id.json : The test should return a success but is returning {$result['header']['status']}");

		$id = 1;
		$result = json_decode($this->testAction("/resources/$id.json", array(
			 'method' => 'delete',
			 'return' => 'contents'
		)), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "delete /resources/$id.json : The test should return a error but is returning {$result['header']['status']}");
	}
}
