<?php
/**
 * Categories Controller Tests
 *
 * @copyright     Copyright 2012, Passbolt.com
 * @package       app.Test.Case.Controller.CategoriesController
 * @since         version 2.12.7
 * @license       http://www.passbolt.com/license
 */
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

	public $fixtures = array('app.category', 'app.category_type', 'app.category_resource', 'app.user', 'app.role');

	public function setUp() {
		parent::setUp();
	}

	public function testView() {
		$category = new Category();
		$user = new User();

		$user->useDbConfig = 'test';
		$kk = $user->findByUsername('kevin@passbolt.com');
		$user->setActive($kk);

		$category->useDbConfig = 'test';
		$goa = $category->findByName('Goa');
		$id = $goa['Category']['id'];
			
		// test when no parameters are provided
		$result = json_decode($this->testAction("/categories/view.json", array('return'=>'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "/categories/view.json : The test should return an error but is returning {$result['header']['status']}");

		// test when a wrong id is provided
		$result = json_decode($this->testAction("/categories/view/4ff6111b-efb8-4a26-aab4-2184cbdd56ca.json", array('return'=>'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "/categories/view/4ff6111b-efb8-4a26-aab4-2184cbdd56ca.json : The test should return an error but is returning {$result['header']['status']}");

		// test if the object returned is a success one
		$result = json_decode($this->testAction("/categories/view/$id/1.json", array('return'=>'contents')), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], 
			'categories/view/' . $id . '/1.json should return success'
		);

		// test it is the expected format
		$result = json_decode($this->testAction("/categories/view/$id/1.json", array('return'=>'contents')), true);
		$this->assertInternalType('array', $result['body'], 'The url categories/view/' . $id . '/1.json should return a json object');

		// test that content returned are correct 
		$result = json_decode($this->testAction("/categories/view/$id/1.json", array('return'=>'contents')), true);
		// test Anjuna id
		$this->assertEquals('UV Bar', $result['body'][0]['children'][0]['children'][0]['children'][0]['Category']['name'],
			'The test should return UVBar but is returning ' . $result['body'][0]['children'][0]['children'][0]['children'][0]['Category']['name']
		);

		// test without children
		$result = json_decode($this->testAction("/categories/view/$id.json", array('return'=>'contents')), true);
		$this->assertFalse(empty($result['body']));
		$this->assertEquals('Goa', $result['body']['Category']['name'], "Faileds testing that first child is Goa. It returned '{$result['body']['Category']['name']}'");

		// test an error bad id
		$result = json_decode($this->testAction("/categories/view/badid/1.json", array('return'=>'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "Failed testing that /categories/view/badid/1.json should return an error. It returned {$result['header']['status']}");

	}

	public function testViewChildren() {
		$category = new Category();
		$category->useDbConfig = 'test';
		$goa = $category->findByName('Goa');
		$id = $goa['Category']['id'];

		// test when no parameters are provided
		$result = json_decode($this->testAction("/categories/viewChildren.json", array('return'=>'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "/categories/viewChildren.json : The test should return error but is returning {$result['header']['status']}");
		
		// test when a wrong id is provided
		$result = json_decode($this->testAction("/categories/viewChildren/4ff6111b-efb8-4a26-aab4-2184cbdd56ca.json", array('return'=>'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "/categories/viewChildren/4ff6111b-efb8-4a26-aab4-2184cbdd56ca.json : The test should return error but is returning {$result['header']['status']}");

		// test if the object returned is a success one
		$result = json_decode($this->testAction("/categories/viewChildren/$id.json", array('return'=>'contents')), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "/categories/viewChildren/$id.json : The test should return success but is returning {$result['header']['status']}");

		// test it is the expected format
		$result = json_decode($this->testAction("/categories/viewChildren/$id.json", array('return'=>'contents')), true);
		$this->assertInternalType('array', $result['body'], "/categories/viewChildren/$id.json : The value returned should be an array but is ". var_dump($result['body']));

		// test that content returned are correct 
		$result = json_decode($this->testAction("/categories/viewChildren/$id.json", array('return'=>'contents')), true);
		$this->assertEquals('Anjuna', $result['body'][0]['children'][0]['Category']['name'], "/categories/viewChildren/$id.json : The test should return Anjuna but is returning {$result['body'][0]['children'][0]['Category']['name']}");

		// test an error
		$result = json_decode($this->testAction("/categories/viewChildren/badid.json", array('return'=>'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "/categories/viewChildren/badid.json : The test should return error but is returning {$result['header']['status']}");
	}

	public function testAdd() {
		// check the response when a category is added (without parent_id)
		$result = json_decode($this->testAction('/categories/add.json', array(
			'data' => array(
				'Category' => array('name' => 'Aramboooool')
			 ),
			 'method' => 'post',
			 'return'=>'contents'
		)), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "The test should return success but is returning {$result['header']['status']}");

		// check that category detail is properly returned by the function
		$this->assertEquals('Aramboooool', $result['body']['Category']['name'], "The test should return Aramboooool but is returning {$result['body']['Category']['name']}");
		
		// check the response when a category is added (without parent_id)
		$result = json_decode($this->testAction('/categories/add.json', array(
			 'method' => 'post',
			 'return'=>'contents'
		)), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "The test should return error but is returning {$result['header']['status']}");
		

		// check that the category has been added in the database
		$category = new Category();
		$category->useDbConfig = 'test';
		$cat = $category->findByName('Aramboooool');
		$this->assertTrue($cat['Category']['lft'] == 29, "Checking lft of added Category should see 29, but sees {$cat['Category']['lft']}");

		// test insertion with parameter parent_id, and position 1
		$parent = $category->findByName('Goa');
		$parent_id = $parent['Category']['id'];
		$result = json_decode($this->testAction('/categories/add.json', array(
			'data' => array(
				'Category' => array(
					'name' => 'Vagator', 
					'parent_id'=>$parent_id,
					'position'=>1
				)
			 ),
			 'method' => 'post',
			 'return'=>'contents'
		)), true);
		$catVagator = $category->findById($result['body']['Category']['id']);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "The test should return success but is returning {$result['header']['status']}");
		$this->assertEquals($parent['Category']['lft'] + 1, $catVagator['Category']['lft']);

		 // test insertion with parameter parent_id, and position 2
		$result = json_decode($this->testAction('/categories/add.json', array(
			'data' => array(
				'Category' => array(
					'name' => 'Porvorim', 
					'parent_id'=>$parent_id,
					'position'=>2
				)
			 ),
			 'method' => 'post',
			 'return'=>'contents'
		)), true);
		$catPorvorim = $category->findById($result['body']['Category']['id']);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "The test should return success but is returning {$result['header']['status']}");
		$this->assertEquals($catVagator['Category']['lft'] + 2, $catPorvorim['Category']['lft']);

		// test insertion with parameter parent_id, and position 50 (doesnt exist)
		$result = json_decode($this->testAction('/categories/add.json', array(
			'data' => array(
				'Category' => array(
					'name' => 'Salvador Do Mundo', 
					'parent_id'=>$parent_id,
					'position'=>50
				)
			 ),
			 'method' => 'post',
			 'return'=>'contents'
		)), true);
		$catPorvorim = $category->findById($result['body']['Category']['id']);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "The test should return success but is returning {$result['header']['status']}");
		$this->assertEquals(32, $catPorvorim['Category']['lft'], "Checking the lft attribute : should be 32 but is {$catPorvorim['Category']['lft']}");

		// Error : not a post request
		$result = json_decode($this->testAction('/categories/add.json', array(
			'data' => array(
				'Category' => array(
					'name' => 'Alto', 
					'parent_id'=>$parent_id,
					'position'=>1
				)
			 ),
			 'method' => 'get',
			 'return'=>'contents'
		)), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "The test should return error but is returning {$result['header']['status']}");

		// Error : name is empty
		$result = json_decode($this->testAction('/categories/add.json', array(
			'data' => array(
				'Category' => array(
					'name' => ''
				)
			 ),
			 'method' => 'Post',
			 'return'=>'contents'
		)), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "The test should return error but is returning {$result['header']['status']}");

		// Error : no data provided
		$result = json_decode($this->testAction('/categories/add.json', array('return'=>'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "The test should return error but is returning {$result['header']['status']}");

	}

	public function testDelete() {
		// Error : no data provided
		$category = new Category();
		$category->useDbConfig = 'test';
		$cat = $category->findByName('Drug places');
		$id = $cat['Category']['id'];

		// without paramters
		$result = json_decode($this->testAction("/categories/delete.json", array('method' => 'delete', 'return'=>'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "/categories/delete : The test should return success but is returning {$result['header']['status']}");

		$result = json_decode($this->testAction("/categories/delete/$id.json", array('method' => 'delete', 'return'=>'contents')), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "/categories/delete/$id : The test should return success but is returning {$result['header']['status']}");
		
		$result = json_decode($this->testAction("/categories/delete/badid.json", array('method' => 'delete', 'return'=>'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "/categories/delete/badid : The test should return error but is returning {$result['header']['status']}");

		// check that the category was properly deleted
		$cat = $category->findByName('Drug places');
		$this->assertTrue(empty($cat), "The category Drug places should have been deleted but is not");
	}
	
	public function testRename() {
		// Error : no data provided
		$category = new Category();
		$category->useDbConfig = 'test';
		$cat = $category->findByName('Drug places');
		$id = $cat['Category']['id'];
		$newName = "Booze Places";

		$result = json_decode($this->testAction("/categories/rename.json", array('method' => 'put', 'return'=>'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "/categories/rename : The test should return error but is returning {$result['header']['status']}");

		$result = json_decode($this->testAction("/categories/rename/$id/$newName.json", array('method' => 'put', 'return'=>'contents')), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "/categories/rename/$id/$newName.json : The test should return success but is returning {$result['header']['status']}");
		
		$result = json_decode($this->testAction("/categories/rename/badid/$newName.json", array('method' => 'put', 'return'=>'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "/categories/rename/badid/$newName.json : The test should return error but is returning {$result['header']['status']}");
		
		// test with id doesnt exist
		$result = json_decode($this->testAction("/categories/rename/4ff6111b-efb8-4a26-aab4-2184cbdd56ca/$newName.json", array('method' => 'put', 'return'=>'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "/categories/rename/4ff6111b-efb8-4a26-aab4-2184cbdd56ca/$newName.json : The test should return error but is returning {$result['header']['status']}");
		

		// check that the previous name doesn't exist anymore
		$cat = $category->findByName('Drug places');
		$this->assertTrue(empty($cat), "Failed to check that Drup places has been renamed into something else.");

		 // check that the new name is there
		$cat = $category->findByName('Booze places');
		$this->assertFalse(empty($cat), "Failed to test that Drug places has been renamed into Booze places");
	}
	
	public function testMove(){
		$categoryModel = new Category();
		$categoryModel->useDbConfig = 'test';

		$mapusa = $categoryModel->findByName('Mapusa');
		$discoplaces = $categoryModel->findByName('Disco places');
		$drugplaces = $categoryModel->findByName('Drug places');

		$testCases = array(
			'firstPosition' => array('id' => $mapusa['Category']['id'], 'position' => '1'),
			'moveDown'=>array('id' => $mapusa['Category']['id'], 'position' => '2'),
			'lastPosition' => array('id' => $mapusa['Category']['id'], 'position' => '4'),
			'positionMiddle' => array('id' => $mapusa['Category']['id'], 'position' => '3'),
			'minusPosition' => array('id' => $mapusa['Category']['id'], 'position' => '-1'),
			'differentParent' => array('id' => $mapusa['Category']['id'], 'position' => '1', 'parent_id' => $drugplaces['Category']['id']),
			'wrongId'=>array('id'=>'badid', 'position' => '1'), 
			'idNotExist'=>array('id'=>'4ff6111b-efb8-4a26-aab4-2184cbdd56ca', 'position'=>'1')
		);

		// insert 2 more categories for this specific test
		$categoryModel->create();
		$categoryModel->save(array('Category'=>array('name'=>'Panjim', 'parent_id'=>$discoplaces['Category']['id'])));
		$categoryModel->create();
		$categoryModel->save(array('Category'=>array('name'=>'Vaga', 'parent_id'=>$discoplaces['Category']['id'])));

		// test without parameters
		// test firstPosition
		$url = "/categories/move.json";
		$result = json_decode($this->testAction($url, array('method' => 'put', 'return'=>'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "$url : The test should return error but is returning {$result['header']['status']}"); // test if response is a success

		// test firstPosition
		$url = "/categories/move/{$testCases['firstPosition']['id']}/{$testCases['firstPosition']['position']}.json";
		$result = json_decode($this->testAction($url, array('method' => 'put', 'return'=>'contents')), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "$url : The test should return success but is returning {$result['header']['status']}"); // test if response is a success
		// test if the category is at the right place at present
		$afterSave = $categoryModel->findById($mapusa['Category']['id']);
		$this->assertEquals($afterSave['Category']['lft'], $discoplaces['Category']['lft'] + 1, "$url : Test failed to verify that Mapusa is first child of Disco Places");
		
		// test moving down
		$url = "/categories/move/{$testCases['moveDown']['id']}/{$testCases['moveDown']['position']}.json";
		$result = json_decode($this->testAction($url, array('method' => 'put', 'return'=>'contents')), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "The test should return success but is returning {$result['header']['status']}"); // test if response is an error	
		// test if the category is at the right place after moving down
		$afterSave = $categoryModel->findById($mapusa['Category']['id']);
		$this->assertEquals($afterSave['Category']['lft'], $discoplaces['Category']['lft'] + 3, "$url : Test failed to verify that Mapusa is now at the 2nd position from top");

		// test lastPosition
		$url = "/categories/move/{$testCases['lastPosition']['id']}/{$testCases['lastPosition']['position']}.json";
		$result = json_decode($this->testAction($url, array('method' => 'put', 'return'=>'contents')), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "$url : The test should return success but is returning {$result['header']['status']}"); // test if response is a success
		// test if the category is at the right place at present
		$afterSave = $categoryModel->findById($mapusa['Category']['id']);
		$this->assertEquals($afterSave['Category']['lft'], $discoplaces['Category']['lft'] + 7, "$url : Test failed to verify that Mapusa is now at the last position");

		// test positionMiddle
		$url = "/categories/move/{$testCases['positionMiddle']['id']}/{$testCases['positionMiddle']['position']}.json";
		$result = json_decode($this->testAction($url, array('method' => 'put', 'return'=>'contents')), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "$url : The test should return error but is returning {$result['header']['status']}"); // test if response is a success
		// test if the category is at the right place at present
		$afterSave = $categoryModel->findById($mapusa['Category']['id']);
		$this->assertEquals($afterSave['Category']['lft'], $discoplaces['Category']['lft'] + 5, "$url : The test failed to verify that Mapusa is now positionne the middle. lft should be " . ($discoplaces['Category']['lft'] + 5) . " but is {$afterSave['Category']['lft']}"); 

		// test minusPosition
		$url = "/categories/move/{$testCases['minusPosition']['id']}/{$testCases['minusPosition']['position']}.json";
		$result = json_decode($this->testAction($url, array('method' => 'put', 'return'=>'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "$url : The test should return error but is returning {$result['header']['status']}"); // test if response is an error

		// test differentParent
		$url = "/categories/move/{$testCases['differentParent']['id']}/{$testCases['differentParent']['position']}/{$testCases['differentParent']['parent_id']}.json";
		$result = json_decode($this->testAction($url, array('method' => 'put', 'return'=>'contents')), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "$url : The test should return success but is returning {$result['header']['status']}"); // test if response is a success
		// test if the category is at the right place at present
		$afterSave = $categoryModel->findById($mapusa['Category']['id']);
		$this->assertEquals($afterSave['Category']['lft'], $drugplaces['Category']['lft'] + 1, "$url : Failed to test that Mapusa is now first child of Drug places");

		// test bad uuid
		$url = "/categories/move/{$testCases['wrongId']['id']}/{$testCases['wrongId']['position']}.json";
		$result = json_decode($this->testAction($url, array('method' => 'put', 'return'=>'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "The test should return error but is returning {$result['header']['status']}"); // test if response is an error		
		
		// test id doesnt exist
		$url = "/categories/move/{$testCases['idNotExist']['id']}/{$testCases['idNotExist']['position']}.json";
		$result = json_decode($this->testAction($url, array('method' => 'put', 'return'=>'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "The test should return error but is returning {$result['header']['status']}"); // test if response is an error		
	}

 public function testSetType(){
 	$categoryModel = new Category();
		$categoryModel->useDbConfig = 'test';
		$categoryTypeModel = new CategoryType();
		$categoryTypeModel->useDbConfig = 'test';
		
		$goa = $categoryModel->findByName('Goa');
		$id = $goa['Category']['id'];
		

		$result = json_decode($this->testAction("/categories/setType/$id/default.json", array('method' => 'put', 'return'=>'contents')), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "/categories/setType/$id/default.json : The test should return success but returned {$result['header']['status']}");
		
		$goa = $categoryModel->findByName('Goa');
		$this->assertEquals("50152793-9efc-4a7f-b79e-1358b4e000c3", $goa['Category']['category_type_id'], "The category type id should be 50152793-9efc-4a7f-b79e-1358b4e000c3 but it is {$goa['Category']['category_type_id']}");
		
		$result = json_decode($this->testAction("/categories/setType/$id/namedoesntexist.json", array('method' => 'put', 'return'=>'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "/categories/setType/$id/namedoesntexist.json : The test should return error but has returned {$result['header']['status']}");
		
		$result = json_decode($this->testAction("/categories/setType/50152793-9efc-4a7f-b79e-1358b4e000c3/default.json", array('method' => 'put', 'return'=>'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "/categories/setType/50152793-9efc-4a7f-b79e-1358b4e000c3/default.json : The test should return error but has returned {$result['header']['status']}");
		
		$result = json_decode($this->testAction("/categories/setType/badid/default.json", array('method' => 'put', 'return'=>'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "/categories/setType/badid/default.json : The test should return error but has returned {$result['header']['status']}");
		
		$result = json_decode($this->testAction("/categories/setType.json", array('method' => 'put', 'return'=>'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "/categories/setType.json : The test should return error but has returned {$result['header']['status']}");
 }
	
}
