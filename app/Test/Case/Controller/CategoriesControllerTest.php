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

	public $fixtures = array(
		'app.category',
		'app.resource',
		'app.category_type',
		'app.categories_resource',
		'app.user',
		'app.profile',
		'app.file_storage',
		'app.group',
		'app.groupsUser',
		'app.role',
		'app.permission',
		'app.permissions_type',
		'app.permission_view',
		'app.authenticationBlacklist',
		'core.cakeSession',
		'app.user_agent',
		'app.controller_log'
	);

	public function setUp() {
		parent::setUp();
		$this->User = ClassRegistry::init('User');
		$this->Category = ClassRegistry::init('Category');

		// log the user as a manager to be able to access all categories
		$user = $this->User->findById(common::uuid('user.id.dame'));
		$this->User->setActive($user);
	}

	public function testIndexNoChildrenPermission() {
		// Test that users won't get top categories if they're not allowed.
		// Looking at the matrix of permission Jean Bartik should not be able to read the category 'Bolt Softwares Pvt. Ltd'
		$user = $this->User->findById(Common::uuid('user.id.jean'));
		$this->User->setActive($user);

		// test when no parameters are provided (default behaviour : children=false)
		$result = json_decode($this->testAction("/categories/index.json", array(
			'method' => 'get',
			'return' => 'contents'
		)), true);
		$debug = print_r($result, true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status'], "/categories/index.json : The test should return success but is returning {$result['header']['status']} debug : $debug");
		$this->assertEmpty($this->Category->inNestedArray('Bolt Softwares Pvt. Ltd.', $result['body'], 'name'), '/categories/index.json : The server result should not contain Bolt Softwares Pvt. Ltd.');
		$this->assertNotEmpty($this->Category->inNestedArray('pv-jean_bartik', $result['body'], 'name'), '/categories/index.json : The server result should contain pv-jean_bartik');
	}

	public function testIndex() {
		// test when no parameters are provided (default behaviour : children=false)
		$result = json_decode($this->testAction("/categories/index.json", array(
			'method' => 'get',
			'return' => 'contents'
		)), true);
		$debug = print_r($result, true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status'], "/categories/index.json : The test should return success but is returning {$result['header']['status']} debug : $debug");
		$this->assertNotEmpty($this->Category->inNestedArray('Bolt Softwares Pvt. Ltd.', $result['body'], 'name'), '/categories/index.json : The server result should contain Bolt Softwares Pvt. Ltd.');

		// test with children = true
		$result = json_decode($this->testAction("/categories.json", array(
			'method' => 'get',
			'return' => 'contents',
			'data' => array(
				'children' => 'true'
			)
		)), true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status'], "/categories.json?children=true : The test should return success but is returning {$result['header']['status']}");
		$this->assertTrue($result['body'][0]['children'] > 0, "/categories.json?children=true : \$result['body'][0]['Category']['name'] should return 'Bolt Softwares Pvt. Ltd.' but is returning {$result['body'][0]['Category']['name']}");
	}

	public function testViewCategoryIdIsMissing() {
		// @todo? Unable to test missing id param because of route
	}

	public function testViewCategoryIdNotValid() {
		// test an error bad id
		$catId = 'badId';
		$this->setExpectedException('HttpException', 'The category id is invalid');
		$result = json_decode($this->testAction("/categories/{$catId}.json?children=true", array(
			'method' => 'get',
			'return' => 'contents'
		)), true);
	}

	public function testViewCategoryDoesNotExist() {
		// test when a wrong id is provided
		$catId = Common::uuid();
		$this->setExpectedException('HttpException', 'The category does not exist');
		$result = json_decode($this->testAction("/categories/{$catId}.json", array(
			'method' => 'get',
			'return' => 'contents'
		)), true);
	}

	public function testViewAndPermission() {
		// Error : name is empty
		$catId = Common::uuid('category.id.d-project1');

		// Looking at the matrix of permission Irene should not be able to read the category d-project1
		$user = $this->User->findById(Common::uuid('user.id.irene'));
		$this->User->setActive($user);

		$this->setExpectedException('HttpException', 'The category does not exist');
		$result = json_decode($this->testAction("/categories/{$catId}.json", array(
			'method' => 'Get',
			'return' => 'contents'
		)), true);
	}

	public function testView() {
		$rootCatId = Common::uuid('category.id.bolt');

		// test when no parameters are provided
		$result = json_decode($this->testAction("/categories.json", array(
			'method' => 'get',
			'return' => 'contents'
		)), true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status'], "/categories.json : The test should return success but is returning {$result['header']['status']}");

		// test if the object returned is a success one
		$result = json_decode($this->testAction("/categories/$rootCatId.json", array(
			'method' => 'get',
			'return' => 'contents',
			'data' => array(
				'children' => 'true'
			)
		)), true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status'], 'categories/view/' . $rootCatId . '.json?children=true should return success');

		// test it is the expected format
		$result = json_decode($this->testAction("/categories/$rootCatId.json", array(
			'method' => 'get',
			'return' => 'contents',
			'data' => array(
				'children' => 'true'
			)
		)), true);
		$this->assertInternalType('array', $result['body'], 'The url categories/view/' . $rootCatId . '.json?children=true should return a json object');

		// test that content returned is containing expect value
		$result = json_decode($this->testAction("/categories/$rootCatId.json", array(
			'method' => 'get',
			'return' => 'contents',
			'data' => array(
				'children' => 'true'
			)
		)), true);
		$accountsCatId = Common::uuid('category.id.accounts');
		$path = $this->Category->inNestedArray($accountsCatId, $result['body']);
		$this->assertTrue(!empty($path), 'The result should contain the category "accounts", but it is not found.');
		$this->assertEquals($path[0], $rootCatId);

		$adminCatId = Common::uuid('category.id.administration');
		$this->assertEquals($path[1], $adminCatId);

		// test without children
		$result = json_decode($this->testAction("/categories/$rootCatId.json", array(
			'method' => 'get',
			'return' => 'contents'
		)), true);
		$this->assertFalse(empty($result['body']));
		$this->assertEquals('Bolt Softwares Pvt. Ltd.', $result['body']['Category']['name'], "Faileds testing that first child is Bolt Softwares Pvt. Ltd.. It returned '{$result['body']['Category']['name']}'");
	}

	public function testChildrenCategoryIdIsMissing() {
		$this->setExpectedException('HttpException', 'The category id is missing');
		$this->testAction("/categories/children.json", array('method' => 'get', 'return' => 'contents'));
	}

	public function testChildrenCategoryDoesNotExist() {
		$id = Common::uuid('not-valid-reference');
		$this->setExpectedException('HttpException', 'The category does not exist');
		$this->testAction("/categories/children/{$id}.json", array(
			'method' => 'get',
			'return' => 'contents'
		));
	}

	public function testChildrenCategoryIdNotValid() {
		$this->setExpectedException('HttpException', 'The category id is invalid');
		$this->testAction("/categories/children/badid.json", array('method' => 'get', 'return' => 'contents'));
	}

	public function testChildren() {
		$rootCatId = Common::uuid('category.id.bolt');

		// test if the object returned is a success one
		$result = json_decode($this->testAction("/categories/children/$rootCatId.json", array(
			'method' => 'get',
			'return' => 'contents'
		)), true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status'], "/categories/children/$rootCatId.json : The test should return success but is returning {$result['header']['status']}");

		// test it is the expected format
		$result = json_decode($this->testAction("/categories/children/$rootCatId.json", array(
			'method' => 'get',
			'return' => 'contents'
		)), true);
		$this->assertInternalType('array', $result['body'], "/categories/children/$rootCatId.json : The value returned should be an array but is " . print_r($result['body'], true));

		// test that content returned are correct
		$result = json_decode($this->testAction("/categories/children/$rootCatId.json", array(
			'method' => 'get',
			'return' => 'contents'
		)), true);

		$accountCatId = Common::uuid('category.id.accounts');
		$path = $this->Category->inNestedArray($accountCatId, $result['body']);
		$this->assertTrue(!empty($path), 'The result should contain the category "accounts", but it is not found.');
	}

	public function testAddNoDataProvided() {
		$this->setExpectedException('HttpException', 'No data were provided');
		$this->testAction('/categories.json', array(
			'method' => 'post',
			'return' => 'contents'
		));
	}

	public function testAddInvalidDataProvided() {
		// Error : name is empty
		$this->setExpectedException('HttpException', 'Could not validate category data');
		$result = json_decode($this->testAction('/categories/add.json', array(
			'data' => array(
				'Category' => array(
					'name' => 'x'
				)
			),
			'method' => 'Post',
			'return' => 'contents'
		)), true);
	}

	public function testAddAndPermission() {
		// Looking at the matrix of permission Carol should be able to read but not to create into the category d-project1
		$user = $this->User->findById(Common::uuid('user.id.carol'));
		$this->User->setActive($user);

		// Error : name is empty
		$this->setExpectedException('HttpException', 'You are not authorized to create a category into the given parent category');
		$catId = Common::uuid('category.id.d-project1');
		$result = json_decode($this->testAction('/categories/add.json', array(
			'data' => array(
				'Category' => array(
					'name' => 'testAddAndPermission Category',
					'parent_id' => $catId
				)
			),
			'method' => 'Post',
			'return' => 'contents'
		)), true);
	}

	/**
	 * Test adding a category
	 */
	public function testAddComplete() {
		// check the response when a category is added (without parent_id)
		$data = array(
			'Category' => array('name' => 'Goa')
		);
		$result = json_decode($this->testAction('/categories.json', array(
			'data' => $data,
			'method' => 'post',
			'return' => 'contents'
		)), true);

		$this->assertEquals(Status::SUCCESS, $result['header']['status'], "The test should return success but is returning {$result['header']['status']}");
		$this->assertEquals($data['Category']['name'], $result['body']['Category']['name'], "The test should return {$data['Category']['name']} but is returning {$result['body']['Category']['name']}");

		// test insertion with parameter parent_id, and position 1
		$parentId = Common::uuid('category.id.bolt');
		$parentCat = $this->Category->findById($parentId);
		$result = json_decode($this->testAction('/categories.json', array(
			'data' => array(
				'Category' => array(
					'name' => 'category-test',
					'parent_id' => $parentId,
					'position' => '1'
				)
			),
			'method' => 'post',
			'return' => 'contents'
		)), true);

		$catTest = $this->Category->findById($result['body']['Category']['id']);
		$this->assertEquals(Status::SUCCESS, $result['header']['status'], "The test should return success but is returning {$result['header']['status']}");
		$this->assertEquals($parentCat['Category']['lft'] + 1, $catTest['Category']['lft']);

		// test insertion with parameter parent_id, and position 2
		$result = json_decode($this->testAction('/categories.json', array(
			'data' => array(
				'Category' => array(
					'name' => 'category-test2',
					'parent_id' => $parentId,
					'position' => '2'
				)
			),
			'method' => 'post',
			'return' => 'contents'
		)), true);

		$catTest2 = $this->Category->findById($result['body']['Category']['id']);
		$this->assertEquals(Status::SUCCESS, $result['header']['status'], "The test should return success but is returning {$result['header']['status']}");
		$this->assertEquals($catTest['Category']['lft'] + 2, $catTest2['Category']['lft']);

		// test insertion with parameter parent_id, and position 50 (doesnt exist)
		$result = json_decode($this->testAction('/categories.json', array(
			'data' => array(
				'Category' => array(
					'name' => 'Salvador Do Mundo',
					'parent_id' => $parentId,
					'position' => '50'
				)
			),
			'method' => 'post',
			'return' => 'contents'
		)), true);
		$catTest2 = $this->Category->findById($result['body']['Category']['id']);
		$this->assertEquals(Status::SUCCESS, $result['header']['status'], "The test should return success but is returning {$result['header']['status']}");
		// $this->assertEquals(38, $catTest2['Category']['lft'], "Checking the lft attribute : should be 38 but is {$catTest2['Category']['lft']}");
	}

	public function testEditCategoryIdIsMissing() {
		$this->setExpectedException('HttpException', 'The category id is missing');
		$this->testAction("/categories.json", array('method' => 'put', 'return' => 'contents'));
	}

	public function testEditCategoryIdNotValid() {
		$this->setExpectedException('HttpException', 'The category id is invalid');
		$this->testAction("/categories/badid.json", array('method' => 'put', 'return' => 'contents'));
	}

	public function testEditNoDataProvided() {
		// Error : no data provided
		$catId = Common::uuid('category.id.o-project1');

		// without parameters
		$this->setExpectedException('HttpException', 'No data were provided');
		$this->testAction("/categories/$catId.json", array('method' => 'put', 'return' => 'contents'));
	}

	public function testEditCategoryDoesNotExist() {
		$id = Common::uuid('not-valid-reference');
		$this->setExpectedException('HttpException', 'The category does not exist');
		$this->testAction("/categories/{$id}.json", array(
			'method' => 'put',
			'return' => 'contents'
		));
	}

	public function testEditCategoryDoesNotExistAndPermission() {
		// Looking at the matrix of permission should not be able to READ administration
		$user = $this->User->findById(Common::uuid('user.id.ada'));
		$this->User->setActive($user);

		$this->setExpectedException('HttpException', 'The category does not exist');

		$catId = Common::uuid('category.id.administration');
		$result = json_decode($this->testAction("/categories/$catId.json", array(
			'data' => array(
				'Category' => array(
					'name' => 'testEditCategoryDoesNotExistAndPermission Category'
				)
			),
			'method' => 'Put',
			'return' => 'contents'
		)), true);
	}

	public function testEditAndPermission() {
		// Looking at the matrix of permission Carol should be able to read but not to edit the category d-project1
		$user = $this->User->findById(Common::uuid('user.id.carol'));
		$this->User->setActive($user);

		$this->setExpectedException('HttpException', 'You are not authorized to edit this category');

		$catId = Common::uuid('category.id.d-project1');
		$result = json_decode($this->testAction("/categories/$catId.json", array(
			'data' => array(
				'Category' => array(
					'name' => 'testEditAndPermission Category'
				)
			),
			'method' => 'Put',
			'return' => 'contents'
		)), true);
	}

	public function testEditAndParentCategoryPermission() {
		// Looking at the matrix of permission ada should be able to update "projects" but has no CREATE right for "administration"
		$user = $this->User->findById(Common::uuid('user.id.ada'));
		$this->User->setActive($user);

		$parentCatId = Common::uuid('category.id.administration');
		$catId = Common::uuid('category.id.projects');

		$this->setExpectedException('HttpException', 'You are not authorized to create a category into the given parent category');
		$result = json_decode($this->testAction("/categories/$catId.json", array(
			'data' => array(
				'Category' => array(
					'name' => 'testEditAndPermission Category',
					'parent_id' => $parentCatId,
				)
			),
			'method' => 'Put',
			'return' => 'contents'
		)), true);
	}

	public function testEdit() {
		$catId = Common::uuid('category.id.o-project1');
		$cat = $this->Category->findById($catId);

		// Modify the name of the category
		$catNewName = 'o-project1-transformed';
		$cat['Category']['name'] = $catNewName;

		$params = array(
			'data' => $cat,
			'method' => 'put',
			'return' => 'contents'
		);
		$result = json_decode($this->testAction("/categories/$catId.json", $params), true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status'], "test edit with data should return success but is returning {$result['header']['status']}");

		// test that the category has been modified properly in db
		$cat = $this->Category->findById($catId);
		$this->assertEquals($cat['Category']['name'], $catNewName, "test edit : name should be updated to $catNewName but it returned {$cat['Category']['name']}");
	}

	public function testDeleteCategoryIdIsMissing() {
		$this->setExpectedException('HttpException', 'The category id is missing');
		$this->testAction("/categories.json", array('method' => 'delete', 'return' => 'contents'));
	}

	public function testDeleteCategoryIdNotValid() {
		$this->setExpectedException('HttpException', 'The category id is invalid');
		$this->testAction("/categories/badid.json", array('method' => 'delete', 'return' => 'contents'));
	}

	public function testDeleteCategoryDoesNotExist() {
		$this->setExpectedException('HttpException', 'The category does not exist');

		$catId = Common::uuid();
		$result = json_decode($this->testAction("/categories/$catId.json", array(
			'method' => 'Delete',
			'return' => 'contents'
		)), true);
	}

	public function testDeleteCategoryDoesNotExistAndPermission() {
		$catId = Common::uuid('category.id.administration');

		// Looking at the matrix of permission should not be able to READ administration
		$user = $this->User->findById(Common::uuid('user.id.ada'));
		$this->User->setActive($user);

		$this->setExpectedException('HttpException', 'The category does not exist');

		$result = json_decode($this->testAction("/categories/$catId.json", array(
			'method' => 'Delete',
			'return' => 'contents'
		)), true);
	}

	public function testDeleteAndPermission() {
		// Looking at the matrix of permission Jean Bartik should be able to create but not delete the category Jean private
		$user = $this->User->findById(Common::uuid('user.id.jean'));
		$this->User->setActive($user);

		$this->setExpectedException('HttpException', 'You are not authorized to delete this category');

		$catId = Common::uuid('category.id.pv-jean_bartik');
		$result = json_decode($this->testAction("/categories/$catId.json", array(
			'method' => 'Delete',
			'return' => 'contents'
		)), true);
	}

	public function testDelete() {
		$catId = Common::uuid('category.id.cp-project2');

		$result = json_decode($this->testAction("/categories/$catId.json", array(
			'method' => 'delete',
			'return' => 'contents'
		)), true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status'], "/categories/delete/$catId : The test should return success but is returning {$result['header']['status']}");

		// check that the category was properly deleted
		$cat = $this->Category->findById($catId);
		$this->assertTrue(empty($cat), "The category cp-project2 should have been deleted but is not");
	}

	public function testMoveCategoryIdIsMissing() {
		$this->setExpectedException('HttpException', 'The category id is missing');
		$this->testAction("/categories/move.json", array('method' => 'put', 'return' => 'contents'));
	}

	public function testMoveCategoryIdNotValid() {
		$this->setExpectedException('HttpException', 'The category id is invalid');
		$this->testAction("/categories/move/badid.json", array('method' => 'put', 'return' => 'contents'));
	}

	public function testMoveCategoryDoesNotExist() {
		$id = Common::uuid('not-valid-reference');
		$this->setExpectedException('HttpException', 'The category does not exist');
		$this->testAction("/categories/move/{$id}.json", array(
			'method' => 'put',
			'return' => 'contents'
		));
	}

	public function testMoveParentCategoryIdNotValid() {
		$catHrId = Common::uuid('category.id.human');
		$parentCatId = 'badParentId';
		$this->setExpectedException('HttpException', 'The parent category id invalid');
		$this->testAction("/categories/move/{$catHrId}/1/{$parentCatId}.json", array(
			'method' => 'put',
			'return' => 'contents'
		));
	}

	public function testMoveParentCategoryDoesNotExist() {
		$catHrId = Common::uuid('category.id.human');
		$parentCatId = Common::uuid();
		$this->setExpectedException('HttpException', 'The parent category does not exist');
		$this->testAction("/categories/move/{$catHrId}/1/{$parentCatId}.json", array(
			'method' => 'put',
			'return' => 'contents'
		));
	}

	public function testMove() {
		$catHrId = Common::uuid('category.id.human');
		$catAdmId = Common::uuid('category.id.administration');
		$catCakeId = Common::uuid('category.id.cakephp');

		$testCases = array(
			'firstPosition' => array('id' => $catHrId, 'position' => '1'),
			'moveDown' => array('id' => $catHrId, 'position' => '2'),
			'lastPosition' => array('id' => $catHrId, 'position' => '4'),
			'positionMiddle' => array('id' => $catHrId, 'position' => '3'),
			'minusPosition' => array('id' => $catHrId, 'position' => '-1'),
			'differentParent' => array(
				'id' => $catHrId,
				'position' => '1',
				'parent_id' => $catCakeId
			)
		);

		$this->Category->Behaviors->disable('Permissionable');
		// insert 2 more categories for this specific test
		$this->Category->create();
		$this->Category->save(array(
			'Category' => array(
				'name' => 'cat-test1',
				'parent_id' => $catAdmId
			)
		));
		$this->Category->create();
		$this->Category->save(array(
			'Category' => array(
				'name' => 'cat-test2',
				'parent_id' => $catAdmId
			)
		));
		// $this->Category->Behaviors->enable('Permissionable');

		//		// test firstPosition
		//		$url = "/categories/move/{$testCases['firstPosition']['id']}/{$testCases['firstPosition']['position']}.json";
		//		$result = json_decode($this->testAction($url, array('method' => 'put', 'return' => 'contents')), true);
		//		$this->assertEquals(Status::SUCCESS, $result['header']['status'], "$url : The test should return success but is returning {$result['header']['status']}"); // test if response is a success
		//		// test if the category is at the right place at present
		//		$afterSave = $this->Category->findById($hr['Category']['id']);
		//		$this->assertEquals($afterSave['Category']['lft'], $administration['Category']['lft'] + 1, "$url : Test failed to verify that Mapusa is first child of Disco Places");
		//
		//		// test moving down
		//		$url = "/categories/move/{$testCases['moveDown']['id']}/{$testCases['moveDown']['position']}.json";
		//		$result = json_decode($this->testAction($url, array('method' => 'put', 'return' => 'contents')), true);
		//		$this->assertEquals(Status::SUCCESS, $result['header']['status'], "The test should return success but is returning {$result['header']['status']}"); // test if response is an error
		//		// test if the category is at the right place after moving down
		//		$afterSave = $this->Category->findById($hr['Category']['id']);
		//		$this->assertEquals($afterSave['Category']['lft'], $administration['Category']['lft'] + 3, "$url : Test failed to verify that Mapusa is now at the 2nd position from top");
		//
		//		// test lastPosition
		//		$url = "/categories/move/{$testCases['lastPosition']['id']}/{$testCases['lastPosition']['position']}.json";
		//		$result = json_decode($this->testAction($url, array('method' => 'put', 'return' => 'contents')), true);
		//		$this->assertEquals(Status::SUCCESS, $result['header']['status'], "$url : The test should return success but is returning {$result['header']['status']}"); // test if response is a success
		//		// test if the category is at the right place at present
		//		$afterSave = $this->Category->findById($hr['Category']['id']);
		//		$this->assertEquals($afterSave['Category']['lft'], $administration['Category']['lft'] + 7, "$url : Test failed to verify that Mapusa is now at the last position");
		//
		//		// test positionMiddle
		//		$url = "/categories/move/{$testCases['positionMiddle']['id']}/{$testCases['positionMiddle']['position']}.json";
		//		$result = json_decode($this->testAction($url, array('method' => 'put', 'return' => 'contents')), true);
		//		$this->assertEquals(Status::SUCCESS, $result['header']['status'], "$url : The test should return error but is returning {$result['header']['status']}"); // test if response is a success
		//		// test if the category is at the right place at present
		//		$afterSave = $this->Category->findById($hr['Category']['id']);
		//		$this->assertEquals($afterSave['Category']['lft'], $administration['Category']['lft'] + 5, "$url : The test failed to verify that Mapusa is now positionne the middle. lft should be " . ($administration['Category']['lft'] + 5) . " but is {$afterSave['Category']['lft']}");
		//
		//		// test differentParent
		//		$url = "/categories/move/{$testCases['differentParent']['id']}/{$testCases['differentParent']['position']}/{$testCases['differentParent']['parent_id']}.json";
		//		$result = json_decode($this->testAction($url, array('method' => 'put', 'return' => 'contents')), true);
		//		$this->assertEquals(Status::SUCCESS, $result['header']['status'], "$url : The test should return success but is returning {$result['header']['status']}"); // test if response is a success
		//		// test if the category is at the right place at present
		//		$afterSave = $this->Category->findById($hr['Category']['id']);
		//		$cakephp = $this->Category->findById($cakephp['Category']['id']);
		//		$this->assertEquals($afterSave['Category']['lft'], $cakephp['Category']['lft'] + 1, "$url : Failed to test that 'hr' is now first child of 'cakephp'");
	}

	public function testTypeCategoryIdIsMissing() {
		$this->setExpectedException('HttpException', 'The category id is missing');
		$this->testAction("/categories/type.json", array('method' => 'put', 'return' => 'contents'));
	}

	public function testTypeCategoryIdNotValid() {
		$this->setExpectedException('HttpException', 'The category id is invalid');
		$this->testAction("/categories/type/badid.json", array('method' => 'put', 'return' => 'contents'));
	}

	public function testTypeCategoryDoesNotExist() {
		$catId = Common::uuid();
		$this->setExpectedException('HttpException', 'The category does not exist');
		$this->testAction("/categories/type/{$catId}.json", array(
			'method' => 'put',
			'return' => 'contents'
		));
	}

	public function testTypeNameDoesNotExist() {
		$catId = Common::uuid('category.id.bolt');
		$this->setExpectedException('HttpException', 'The type does not exist');
		$this->testAction("/categories/type/{$catId}/badname.json", array(
			'method' => 'put',
			'return' => 'contents'
		));
	}

	public function testTypeAndPermission() {
		// Looking at the matrix of permission Jean Bartik should be able to create but not update the category Jean private
		$user = $this->User->findById(Common::uuid('user.id.jean'));
		$this->User->setActive($user);

		$this->setExpectedException('HttpException', 'You are not authorized to change the type of this category');

		$catId = Common::uuid('category.id.pv-jean_bartik');
		$result = json_decode($this->testAction("/categories/type/$catId/default.json", array(
			'method' => 'put',
			'return' => 'contents'
		)), true);
	}

	public function testType() {
		$catId = Common::uuid('category.id.bolt');

		$result = json_decode($this->testAction("/categories/type/$catId/default.json", array(
			'method' => 'put',
			'return' => 'contents'
		)), true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status'], "/categories/type/$catId/default.json : The test should return success but returned {$result['header']['status']}");

		$root = $this->Category->findById($catId);
		$this->assertEquals(Common::uuid('category_type.id.default'), $root['Category']['category_type_id'], "The category type id should be 50bda570-9364-4c41-9504-a7c58cebc04d but it is {$root['Category']['category_type_id']}");
	}

	public function testXSS() {
		$result = json_decode($this->testAction('/categories.json', array(
			'data' => array(
				'Category' => array('name' => '<script>alert("xss");</script>XSS')
			),
			'method' => 'post',
			'return' => 'contents'
		)), true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status'], "The test should return success but is returning {$result['header']['status']}");
		$this->assertEquals($result['body']['Category']['name'], 'XSS', "Html should be striped down");
	}
}
