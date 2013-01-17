<?php
/**
 * Categories Controller Tests
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @package      app.Test.Case.Controller.CategoriesController
 * @since        version 2.12.7
 * @license      http://www.passbolt.com/license
 */
App::uses('AppController', 'Controller');
App::uses('CategoriesController', 'Controller');
App::uses('Category', 'Model');
App::uses('CategoryType', 'Model');
App::uses('User', 'Model');
App::uses('Role', 'Model');

// Uses sessions
// App::uses('CakeSession', 'Model/Datasource'); // doesn't work here
if (!class_exists('CakeSession')) {
	require CAKE . 'Model/Datasource/CakeSession.php';
}

class CategoriesControllerTest extends ControllerTestCase {

	public $fixtures = array('app.category', 'app.resource', 'app.category_type', 'app.categoriesResource', 'app.user', 'app.group', 'app.groupsUser', 'app.role', 'app.permission');

	public function setUp() {
		$this->Category = new Category();
		$this->User = new User();
		$this->Category->useDbConfig = 'test';
		$this->User->useDbConfig = 'test';
		parent::setUp();
		
		// log the user as a manager to be able to access all categories
		$kk = $this->User->findByUsername('dark.vador@passbolt.com');
		$this->User->setActive($kk);
	}

	public function testIndex() {		
		// test when no parameters are provided (default behaviour : children=false)
		$result = json_decode($this->testAction("/categories/index.json", array('method' => 'get', 'return' => 'contents')), true);
		$debug = print_r($result, true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "/categories/index.json : The test should return success but is returning {$result['header']['status']} debug : $debug");
		$this->assertEquals('Bolt Softwares Pvt. Ltd.', $result['body'][0]['Category']['name'], "/categories/index.json : \$result['body'][0]['Category']['name'] should return 'Bolt Softwares Pvt. Ltd.' but is returning {$result['body'][0]['Category']['name']}");

		// test with children = true
		$result = json_decode($this->testAction("/categories.json?children=true", array('method' => 'get', 'return' => 'contents')), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "/categories.json?children=true : The test should return success but is returning {$result['header']['status']}");
		$this->assertTrue($result['body'][0]['children'] > 0, "/categories.json?children=true : \$result['body'][0]['Category']['name'] should return 'Bolt Softwares Pvt. Ltd.' but is returning {$result['body'][0]['Category']['name']}");
	}

	public function testView() {
		$root = $this->Category->findByName('Bolt Softwares Pvt. Ltd.');
		$id = $root['Category']['id'];

		// test when no parameters are provided
		$result = json_decode($this->testAction("/categories.json", array('method' => 'get', 'return' => 'contents')), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "/categories.json : The test should return success but is returning {$result['header']['status']}");

		// test when a wrong id is provided
		$result = json_decode($this->testAction("/categories/4ff6111b-efb8-4a26-aab4-2184cbdd56ca.json", array('method' => 'get', 'return' => 'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "/categories/view/4ff6111b-efb8-4a26-aab4-2184cbdd56ca.json : The test should return an error but is returning {$result['header']['status']}");

		// // test if the object returned is a success one
		$result = json_decode($this->testAction("/categories/$id.json?children=true", array('method' => 'get', 'return' => 'contents')), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'],
			'categories/view/' . $id . '.json?children=true should return success'
		);

		// test it is the expected format
		$result = json_decode($this->testAction("/categories/$id.json?children=true", array('method' => 'get', 'return' => 'contents')), true);
		$this->assertInternalType('array', $result['body'], 'The url categories/view/' . $id . '.json?children=true should return a json object');

		// test that content returned is containing expect value
		$result = json_decode($this->testAction("/categories/$id.json?children=true", array('method' => 'get', 'return' => 'contents')), true);
		$accounts = $this->Category->findByName('accounts');
		$path = $this->Category->inNestedArray($accounts['Category']['id'], $result['body']);
		$this->assertTrue(!empty($path), 'The result should contain the category "accounts", but it is not found.');
		$this->assertEquals($path[0], $root['Category']['id']);
		$administration = $this->Category->findByName('administration');
		$this->assertEquals($path[1], $administration['Category']['id']);
		
		// test without children
		$result = json_decode($this->testAction("/categories/$id.json", array('method' => 'get', 'return' => 'contents')), true);
		$this->assertFalse(empty($result['body']));
		$this->assertEquals('Bolt Softwares Pvt. Ltd.', $result['body']['Category']['name'], "Faileds testing that first child is Bolt Softwares Pvt. Ltd.. It returned '{$result['body']['Category']['name']}'");

		// test an error bad id
		$result = json_decode($this->testAction("/categories/badid.json?children=true", array('method' => 'get', 'return' => 'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "Failed testing that /categories/view/badid.json?children=true should return an error. It returned {$result['header']['status']}");
	}

	public function testChildren() {
		$category = new Category();
		$category->useDbConfig = 'test';
		
		$root = $category->findByName('Bolt Softwares Pvt. Ltd.');
		$id = $root['Category']['id'];

		// test when no parameters are provided
		$result = json_decode($this->testAction("/categories/children.json", array('method' => 'get', 'return' => 'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "/categories/children.json : The test should return error but is returning {$result['header']['status']}");

		// test when a wrong id is provided
		$result = json_decode($this->testAction("/categories/children/4ff6111b-efb8-4a26-aab4-2184cbdd56ca.json", array('method' => 'get', 'return' => 'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "/categories/children/4ff6111b-efb8-4a26-aab4-2184cbdd56ca.json : The test should return error but is returning {$result['header']['status']}");

		// test if the object returned is a success one
		$result = json_decode($this->testAction("/categories/children/$id.json", array('method' => 'get', 'return' => 'contents')), true);
		//pr($result); die();
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "/categories/children/$id.json : The test should return success but is returning {$result['header']['status']}");

		// test it is the expected format
		$result = json_decode($this->testAction("/categories/children/$id.json", array('method' => 'get', 'return' => 'contents')), true);
		$this->assertInternalType('array', $result['body'], "/categories/children/$id.json : The value returned should be an array but is " . print_r($result['body'], true));

		// test that content returned are correct
		$result = json_decode($this->testAction("/categories/children/$id.json", array('method' => 'get', 'return' => 'contents')), true);
		$accounts = $this->Category->findByName('accounts');
		$path = $this->Category->inNestedArray($accounts['Category']['id'], $result['body']);
		$this->assertTrue(!empty($path), 'The result should contain the category "accounts", but it is not found.');

		// test an error
		$result = json_decode($this->testAction("/categories/children/badid.json", array('method' => 'get', 'return' => 'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "/categories/children/b adid.json : The test should return error but is returning {$result['header']['status']}");
	}

	public function testAdd() {
		// check the response when a category is added (without parent_id)
		$result = json_decode($this->testAction('/categories.json', array(
			'data' => array(
				'Category' => array('name' => 'Aramboooool')
			 ),
			 'method' => 'post',
			 'return' => 'contents'
		)), true);
		
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "The test should return success but is returning {$result['header']['status']}");

		// check that category detail is properly returned by the function
		$this->assertEquals('Aramboooool', $result['body']['Category']['name'], "The test should return Aramboooool but is returning {$result['body']['Category']['name']}");

		// check the response when a category is added (without parent_id)
		$result = json_decode($this->testAction('/categories.json', array(
			 'method' => 'post',
			 'return' => 'contents'
		)), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "The test should return error but is returning {$result['header']['status']}");

		// check that the category has been added in the database
		$cat = $this->Category->findByName('cp-project2');
		$this->assertTrue($cat['Category']['lft'] == 16, "Checking lft of added Category should see 16, but sees {$cat['Category']['lft']}");

		// test insertion with parameter parent_id, and position 1
		$parent = $this->Category->findByName('Bolt Softwares Pvt. Ltd.');
		$parentId = $parent['Category']['id'];
		$result = json_decode($this->testAction('/categories.json', array(
			'data' => array(
				'Category' => array(
					'name' => 'category-test',
					'parent_id' => $parentId,
					'position' => 1
				)
			 ),
			 'method' => 'post',
			 'return' => 'contents'
		)), true);
		
		$catTest = $this->Category->findById($result['body']['Category']['id']);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "The test should return success but is returning {$result['header']['status']}");
		$this->assertEquals($parent['Category']['lft'] + 1, $catTest['Category']['lft']);

		// test insertion with parameter parent_id, and position 2
		$result = json_decode($this->testAction('/categories.json', array(
			'data' => array(
				'Category' => array(
					'name' => 'category-test2',
					'parent_id' => $parentId,
					'position' => 2
				)
			 ),
			 'method' => 'post',
			 'return' => 'contents'
		)), true);
		$catTest2 = $this->Category->findById($result['body']['Category']['id']);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "The test should return success but is returning {$result['header']['status']}");
		$this->assertEquals($catTest['Category']['lft'] + 2, $catTest2['Category']['lft']);

		// test insertion with parameter parent_id, and position 50 (doesnt exist)
		$result = json_decode($this->testAction('/categories.json', array(
			'data' => array(
				'Category' => array(
					'name' => 'Salvador Do Mundo',
					'parent_id' => $parentId,
					'position' => 50
				)
			),
			'method' => 'post',
			'return' => 'contents'
		)), true);
		$catTest2 = $this->Category->findById($result['body']['Category']['id']);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "The test should return success but is returning {$result['header']['status']}");
		$this->assertEquals(38, $catTest2['Category']['lft'], "Checking the lft attribute : should be 38 but is {$catTest2['Category']['lft']}");

		// Error : name is empty
		$result = json_decode($this->testAction('/categories/add.json', array(
			'data' => array(
				'Category' => array(
					'name' => ''
				)
			 ),
			 'method' => 'Post',
			 'return' => 'contents'
		)), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "The test should return error but is returning {$result['header']['status']}");

		// Error : no data provided
		$result = json_decode($this->testAction('/categories.json', array('method' => 'Post', 'return' => 'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "add without data should return error but is returning {$result['header']['status']}");
	}

/**
 * Test update function
 */
	public function testEdit() {		
		// Error : no data provided
		$cat = $this->Category->findByName('o-project1');
		$id = $cat['Category']['id'];

		// without id
		$result = json_decode($this->testAction("/categories.json", array('method' => 'put', 'return' => 'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "/categories.json : update without id should return error but is returning {$result['header']['status']}");

		// without parameters
		$result = json_decode($this->testAction("/categories/$id.json", array('method' => 'put', 'return' => 'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "/categories/$id.json : update without put parameters should return error but is returning {$result['header']['status']}");

		// with parameters
		$newName = 'o-project1-transformed';
		$cat['Category']['name'] = $newName;
		$params = array(
			'data' => $cat,
			 'method' => 'put',
			 'return' => 'contents'
		);
		$result = json_decode($this->testAction("/categories/$id.json", $params), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "test edit with data should return success but is returning {$result['header']['status']}");

		// test that the category has been modified properly in db
		$cat = $this->Category->findById($id);
		$this->assertEquals($cat['Category']['name'], $newName, "test edit : name should be updated to $newName but it returned {$cat['Category']['name']}");
	}

	public function testDelete() {
		$catName = 'cp-project2';
		$cat = $this->Category->findByName($catName);
		$id = $cat['Category']['id'];

		// without parameters
		$result = json_decode($this->testAction("/categories.json", array('method' => 'delete', 'return' => 'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "/categories/delete : The test should return success but is returning {$result['header']['status']}");

		$result = json_decode($this->testAction("/categories/$id.json", array('method' => 'delete', 'return' => 'contents')), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "/categories/delete/$id : The test should return success but is returning {$result['header']['status']}");

		$result = json_decode($this->testAction("/categories/badid.json", array('method' => 'delete', 'return' => 'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "/categories/delete/badid : The test should return error but is returning {$result['header']['status']}");

		// check that the category was properly deleted
		$cat = $this->Category->findByName($catName);
		$this->assertTrue(empty($cat), "The category Drug places should have been deleted but is not");
	}

	public function testRename() {
		// Error : no data provided
		$category = new Category();
		$category->useDbConfig = 'test';
		$cat = $category->findByName('cp-project2');
		$id = $cat['Category']['id'];
		$newName = 'cp-project2-renamed';

		$result = json_decode($this->testAction("/categories/rename.json", array('method' => 'put', 'return' => 'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "/categories/rename : The test should return error but is returning {$result['header']['status']}");

		$result = json_decode($this->testAction("/categories/rename/$id/$newName.json", array('method' => 'put', 'return' => 'contents')), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "/categories/rename/$id/$newName.json : The test should return success but is returning {$result['header']['status']}");

		$result = json_decode($this->testAction("/categories/rename/badid/$newName.json", array('method' => 'put', 'return' => 'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "/categories/rename/badid/$newName.json : The test should return error but is returning {$result['header']['status']}");

		// test with id doesnt exist
		$result = json_decode($this->testAction("/categories/rename/4ff6111b-efb8-4a26-aab4-2184cbdd56ca/$newName.json", array('method' => 'put', 'return' => 'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "/categories/rename/4ff6111b-efb8-4a26-aab4-2184cbdd56ca/$newName.json : The test should return error but is returning {$result['header']['status']}");

		// check that the previous name doesn't exist anymore
		$cat = $category->findByName('cp-project2');
		$this->assertTrue(empty($cat), "Failed to check that cp-project2 has been renamed into something else.");

		 // check that the new name is there
		$cat = $category->findByName('cp-project2-renamed');
		$this->assertFalse(empty($cat), "Failed to test that cp-project2 has been renamed into cp-project2-renamed");
	}

	public function testMove() {		
		$hr = $this->Category->findByName('human resource');
		$administration = $this->Category->findByName('administration');
		$cakephp = $this->Category->findByName('cakephp');

		$testCases = array(
			'firstPosition' => array('id' => $hr['Category']['id'], 'position' => '1'),
			'moveDown' => array('id' => $hr['Category']['id'], 'position' => '2'),
			'lastPosition' => array('id' => $hr['Category']['id'], 'position' => '4'),
			'positionMiddle' => array('id' => $hr['Category']['id'], 'position' => '3'),
			'minusPosition' => array('id' => $hr['Category']['id'], 'position' => '-1'),
			'differentParent' => array('id' => $hr['Category']['id'], 'position' => '1', 'parent_id' => $cakephp['Category']['id']),
			'wrongId' => array('id' => 'badid', 'position' => '1'),
			'idNotExist' => array('id' => '4ff6111b-efb8-4a26-aab4-2184cbdd56ca', 'position' => '1')
		);

		$this->Category->Behaviors->disable('Permissionable');
		// insert 2 more categories for this specific test
		$this->Category->create();
		$this->Category->save(array('Category' => array('name' => 'cat-test1', 'parent_id' => $administration['Category']['id'])));
		$this->Category->create();
		$this->Category->save(array('Category' => array('name' => 'cat-test2', 'parent_id' => $administration['Category']['id'])));
		// $this->Category->Behaviors->enable('Permissionable');

		// test without parameters
		// test firstPosition
		$url = "/categories/move.json";
		$result = json_decode($this->testAction($url, array('method' => 'put', 'return' => 'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "$url : The test should return error but is returning {$result['header']['status']}"); // test if response is a success

		// test firstPosition
		$url = "/categories/move/{$testCases['firstPosition']['id']}/{$testCases['firstPosition']['position']}.json";
		$result = json_decode($this->testAction($url, array('method' => 'put', 'return' => 'contents')), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "$url : The test should return success but is returning {$result['header']['status']}"); // test if response is a success
		// test if the category is at the right place at present
		$afterSave = $this->Category->findById($hr['Category']['id']);
		$this->assertEquals($afterSave['Category']['lft'], $administration['Category']['lft'] + 1, "$url : Test failed to verify that Mapusa is first child of Disco Places");

		// test moving down
		$url = "/categories/move/{$testCases['moveDown']['id']}/{$testCases['moveDown']['position']}.json";
		$result = json_decode($this->testAction($url, array('method' => 'put', 'return' => 'contents')), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "The test should return success but is returning {$result['header']['status']}"); // test if response is an error
		// test if the category is at the right place after moving down
		$afterSave = $this->Category->findById($hr['Category']['id']);
		$this->assertEquals($afterSave['Category']['lft'], $administration['Category']['lft'] + 3, "$url : Test failed to verify that Mapusa is now at the 2nd position from top");

		// test lastPosition
		$url = "/categories/move/{$testCases['lastPosition']['id']}/{$testCases['lastPosition']['position']}.json";
		$result = json_decode($this->testAction($url, array('method' => 'put', 'return' => 'contents')), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "$url : The test should return success but is returning {$result['header']['status']}"); // test if response is a success
		// test if the category is at the right place at present
		$afterSave = $this->Category->findById($hr['Category']['id']);
		$this->assertEquals($afterSave['Category']['lft'], $administration['Category']['lft'] + 7, "$url : Test failed to verify that Mapusa is now at the last position");

		// test positionMiddle
		$url = "/categories/move/{$testCases['positionMiddle']['id']}/{$testCases['positionMiddle']['position']}.json";
		$result = json_decode($this->testAction($url, array('method' => 'put', 'return' => 'contents')), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "$url : The test should return error but is returning {$result['header']['status']}"); // test if response is a success
		// test if the category is at the right place at present
		$afterSave = $this->Category->findById($hr['Category']['id']);
		$this->assertEquals($afterSave['Category']['lft'], $administration['Category']['lft'] + 5, "$url : The test failed to verify that Mapusa is now positionne the middle. lft should be " . ($administration['Category']['lft'] + 5) . " but is {$afterSave['Category']['lft']}");

		// test minusPosition
		$url = "/categories/move/{$testCases['minusPosition']['id']}/{$testCases['minusPosition']['position']}.json";
		$result = json_decode($this->testAction($url, array('method' => 'put', 'return' => 'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "$url : The test should return error but is returning {$result['header']['status']}"); // test if response is an error

		// test differentParent
		$url = "/categories/move/{$testCases['differentParent']['id']}/{$testCases['differentParent']['position']}/{$testCases['differentParent']['parent_id']}.json";
		$result = json_decode($this->testAction($url, array('method' => 'put', 'return' => 'contents')), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "$url : The test should return success but is returning {$result['header']['status']}"); // test if response is a success
		// test if the category is at the right place at present
		$afterSave = $this->Category->findById($hr['Category']['id']);
		$cakephp = $this->Category->findById($cakephp['Category']['id']);
		$this->assertEquals($afterSave['Category']['lft'], $cakephp['Category']['lft'] + 1, "$url : Failed to test that 'hr' is now first child of 'cakephp'");

		// test bad uuid
		$url = "/categories/move/{$testCases['wrongId']['id']}/{$testCases['wrongId']['position']}.json";
		$result = json_decode($this->testAction($url, array('method' => 'put', 'return' => 'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "The test should return error but is returning {$result['header']['status']}"); // test if response is an error

		// test id doesnt exist
		$url = "/categories/move/{$testCases['idNotExist']['id']}/{$testCases['idNotExist']['position']}.json";
		$result = json_decode($this->testAction($url, array('method' => 'put', 'return' => 'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "The test should return error but is returning {$result['header']['status']}"); // test if response is an error
	}

	public function testType() {
		$categoryModel = new Category();
		$categoryModel->useDbConfig = 'test';
		$categoryTypeModel = new CategoryType();
		$categoryTypeModel->useDbConfig = 'test';

		$root = $categoryModel->findByName('Bolt Softwares Pvt. Ltd.');
		$id = $root['Category']['id'];

		$result = json_decode($this->testAction("/categories/type/$id/default.json", array('method' => 'put', 'return' => 'contents')), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "/categories/type/$id/default.json : The test should return success but returned {$result['header']['status']}");

		$root = $categoryModel->findByName('Bolt Softwares Pvt. Ltd.');
		$this->assertEquals("0234f3a4-c5cd-11e1-a0c5-080027796c4c", $root['Category']['category_type_id'], "The category type id should be 50bda570-9364-4c41-9504-a7c58cebc04d but it is {$root['Category']['category_type_id']}");

		$result = json_decode($this->testAction("/categories/type/$id/namedoesntexist.json", array('method' => 'put', 'return' => 'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "/categories/type/$id/namedoesntexist.json : The test should return error but has returned {$result['header']['status']}");

		$result = json_decode($this->testAction("/categories/type/50152793-9efc-4a7f-b79e-1358b4e000c3/default.json", array('method' => 'put', 'return' => 'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "/categories/type/50152793-9efc-4a7f-b79e-1358b4e000c3/default.json : The test should return error but has returned {$result['header']['status']}");

		$result = json_decode($this->testAction("/categories/type/badid/default.json", array('method' => 'put', 'return' => 'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "/categories/type/badid/default.json : The test should return error but has returned {$result['header']['status']}");

		$result = json_decode($this->testAction("/categories/type.json", array('method' => 'put', 'return' => 'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "/categories/type.json : The test should return error but has returned {$result['header']['status']}");
	}

	public function testXSS() {
		// check the response when a category is added (without parent_id)
		$result = json_decode($this->testAction('/categories.json', array(
			'data' => array(
				'Category' => array('name' => '<script>alert("xss");</script>')
			 ),
			 'method' => 'post',
			 'return' => 'contents'
		)), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "The test should return success but is returning {$result['header']['status']}");

		$categoryModel = new Category();
		$categoryModel->useDbConfig = 'test';
		$lastCreated = $categoryModel->find('first', array(
			'order' => array('Category.created' => 'desc')
		));
		$this->assertEquals($lastCreated['Category']['name'],'&lt;script&gt;alert(&quot;xss&quot;);&lt;/script&gt;',"Html should be striped down");
	}
}
