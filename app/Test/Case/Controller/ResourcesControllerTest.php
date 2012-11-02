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

	public $fixtures = array('app.resource', 'app.category', 'app.category_resource', 'app.user', 'app.role');

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
		$festival = $this->Resource->findByName('festival du cinema');
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

		$this->assertEquals('festival du cinema', $result['body']['Resource']['name'],
			'resources/view/' . $id . ".json should a resource named 'festival du cinema' but returned {$result['body']['Resource']['name']} instead"
		);
	}

	public function testViewByCategory() {
 		$categoryModel = new Category();
		$categoryModel->useDbConfig = 'test';
		$goaCat = $categoryModel->findByName('Goa');

		$id = $goaCat['Category']['id'];

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
		$this->assertEquals('festival du cinema', $result['body'][1]['Resource']['name'],
			$url . " test should read 'festival du cinema' but is reading {$result['body'][1]['Resource']['name']}"
		);
		$this->assertEquals(2, count($result['body']),
			$url . " counting the number of elements should return '2' but is reading " . count($result['body'])
		);

		$url = "/resources/viewByCategory/" . $id . "/1.json";
		$result = json_decode($this->testAction($url, array('return' => 'contents')), true);
		$this->assertEquals('washroom', $result['body'][4]['Resource']['name'],
			$url . " test should read 'washroom' but is reading {$result['body'][4]['Resource']['name']}"
		);
		$this->assertEquals(2, count($result['body'][1]['CategoryResource']),
			$url . " counting the number of elements should return '2' but is reading " . count($result['body'][1]['CategoryResource'])
		);

		// Test when the category is empty
		$mapusaCat = $categoryModel->findByName('Mapusa');
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
		$goaCat = $categoryModel->findByName('Goa');

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
							'id' => $goaCat['Category']['id']
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
		$this->assertEquals($goaCat['Category']['id'], $catres['0']['CategoryResource']['category_id'], "Add : /resources.json : the category inserted should be {$goaCat['Category']['id']} but is {$catres['0']['CategoryResource']['category_id']}");

		// todo : Add more tests for add. Without categories, with wrong categories, etc...
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

	public function testEdit() {
		$categoryModel = new Category();
		$categoryModel->useDbConfig = 'test';
		$goaCat = $categoryModel->findByName('Goa');
		$resource = $this->Resource->findByName("washroom");
		$id = $resource['Resource']['id'];

		$resource['Resource']['name'] = "test";

		$result = json_decode($this->testAction("/resources/$id.json", array(
			'data' => array(
				'Resource' => $resource['Resource'],
				'Category' => array(
					 0 => array(
						'id' => $goaCat['Category']['id']
					 )
				)
			 ),
			 'method' => 'put',
			 'return' => 'contents'
		)), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "update /resources/$id.json : The test should return a success but is returning {$result['header']['status']}");
	}

	public function testDelete() {
		$res = $this->Resource->findByName('washroom');
		$id = $res['Resource']['id'];
		$result = json_decode($this->testAction("/resources/$id.json", array(
			 'method' => 'delete',
			 'return' => 'contents'
		)), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "delete /resources/$id.json : The test should return a success but is returning {$result['header']['status']}");
	}
}
