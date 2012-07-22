<?php
//App::uses('Controller', 'Controller');
App::uses('CategoriesController', 'Controller');
App::uses('Category', 'Model');

class CategoriesControllerTest extends ControllerTestCase {
	public $fixtures = array('app.category');

	public function setUp() {
    parent::setUp();
    $cat = new CategoriesController();
  }
  
  public function testGet() {
    $id = '4ff6111b-efb8-4a26-aab4-2184cbdd56cb'; // Goa

    // test when no parameters are provided
    $result = json_decode($this->testAction("/categories/get", array('return'=>'contents')), true);
    $this->assertEquals(MessageComponent::error, $result['status']);
    
    // test if the object returned is a success one
    $result = json_decode($this->testAction("/categories/get/$id/1", array('return'=>'contents')), true);
    $this->assertEquals(MessageComponent::success, $result['status']);
    
    // test it is the expected format
    $result = $this->testAction("/categories/get/$id/1", array('return'=>'vars'));
    $this->assertInternalType('array', $result['data']);

    // test that data returned are correct 
    $result = json_decode($this->testAction("/categories/get/$id/1", array('return'=>'contents')), true);
    // test Anjuna id
    $this->assertEquals('4ff6111c-8534-4d17-869c-2184cbdd56cb', $result['data'][0]['children'][0]['children'][0]['Category']['id']);
    
    // test without children
    $result = $this->testAction("/categories/get/$id", array('return'=>'vars'));
    $this->assertFalse(isset($result['data'][0]));
    $this->assertEquals('Goa', $result['data']['Category']['name']);
    
    // test an error bad id
    $result = json_decode($this->testAction("/categories/get/badid/1", array('return'=>'contents')), true);
    $this->assertEquals(MessageComponent::error, $result['status']);
    
  }

  public function testGetChildren() {
    $id = '4ff6111b-efb8-4a26-aab4-2184cbdd56cb'; // Goa
    
    // test when no parameters are provided
    $result = json_decode($this->testAction("/categories/getChildren", array('return'=>'contents')), true);
    $this->assertEquals(MessageComponent::error, $result['status']);

    // test if the object returned is a success one
    $result = json_decode($this->testAction("/categories/getChildren/$id", array('return'=>'contents')), true);
    $this->assertEquals(MessageComponent::success, $result['status']);
    
    // test it is the expected format
    $result = $this->testAction("/categories/getChildren/$id", array('return'=>'vars'));
    $this->assertInternalType('array', $result['data']);

    // test that data returned are correct 
    $result = json_decode($this->testAction("/categories/getChildren/$id", array('return'=>'contents')), true);
    // test Anjuna 
    $this->assertEquals('Anjuna', $result['data'][0]['children'][0]['Category']['name']);
    
    // test an error
    $result = json_decode($this->testAction("/categories/get/badid", array('return'=>'contents')), true);
    $this->assertEquals(MessageComponent::error, $result['status']);
  }

  public function testAdd() {
    // check the response when a category is added (without parent_id)
    $result = json_decode($this->testAction('/categories/add', array(
      'data' => array(
        'Category' => array('name' => 'Aramboooool')
       ),
       'method' => 'post',
       'return'=>'contents'
    )), true);
    $this->assertEquals(MessageComponent::success, $result['status']);
    
    // check that category detail is properly returned by the function
    $this->assertEquals('Aramboooool', $result['data']['Category']['name']);
    
    // check that the category has been added in the database
    $category = new Category();
    $category->useDbConfig = 'test';
    $cat = $category->findByName('Aramboooool');
    $this->assertTrue($cat['Category']['lft'] == 29);
    
    // test insertion with parameter parent_id, and position 1
    $parent = $category->findByName('Goa');
    $parent_id = $parent['Category']['id'];
    $result = json_decode($this->testAction('/categories/add', array(
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
    $catVagator = $category->findById($result['data']['Category']['id']);
    $this->assertEquals(MessageComponent::success, $result['status']);
    $this->assertEquals($parent['Category']['lft'] + 1, $catVagator['Category']['lft']);
    
     // test insertion with parameter parent_id, and position 2
    $result = json_decode($this->testAction('/categories/add', array(
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
    $catPorvorim = $category->findById($result['data']['Category']['id']);
    $this->assertEquals(MessageComponent::success, $result['status']);
    $this->assertEquals($catVagator['Category']['lft'] + 2, $catPorvorim['Category']['lft']);
    
    // test insertion with parameter parent_id, and position 50 (doesnt exist)
    $result = json_decode($this->testAction('/categories/add', array(
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
    $catPorvorim = $category->findById($result['data']['Category']['id']);
    $this->assertEquals(MessageComponent::success, $result['status']);
    $this->assertEquals(32, $catPorvorim['Category']['lft']);
    
    // Error : not a post request
    $result = json_decode($this->testAction('/categories/add', array(
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
    $this->assertEquals(MessageComponent::error, $result['status']);
    
    // Error : name is empty
    $result = json_decode($this->testAction('/categories/add', array(
      'data' => array(
        'Category' => array(
          'name' => ''
        )
       ),
       'method' => 'post',
       'return'=>'contents'
    )), true);
    $this->assertEquals(MessageComponent::error, $result['status']);
    
    // Error : no data provided
    $result = json_decode($this->testAction('/categories/add', array('return'=>'contents')), true);
    $this->assertEquals(MessageComponent::error, $result['status']);

  }

  public function testDelete() {
    // Error : no data provided
    $category = new Category();
    $category->useDbConfig = 'test';
    $cat = $category->findByName('Drug places');
    $id = $cat['Category']['id'];
    
    // without paramters
    $result = json_decode($this->testAction("/categories/delete", array('return'=>'contents')), true);
    $this->assertEquals(MessageComponent::error, $result['status']);
    
    $result = json_decode($this->testAction("/categories/delete/$id", array('return'=>'contents')), true);
    $this->assertEquals(MessageComponent::success, $result['status']);
    
    // check that the category was properly deleted
    $cat = $category->findByName('Drug places');
    $this->assertTrue(empty($cat));
    
    
    /*$id = '4ff6111b-efb8-4a26-aab4-2184cbdd56cb'; // Goa
    // test if the object returned is a success one
    $result = json_decode($this->testAction("/categories/get/$id/1", array('return'=>'contents')), true);
    
    pr($result); die();*/
  }
  
  public function testRename() {
    // Error : no data provided
    $category = new Category();
    $category->useDbConfig = 'test';
    $cat = $category->findByName('Drug places');
    $id = $cat['Category']['id'];
    $newName = "Booze Places";
    
    $result = json_decode($this->testAction("/categories/rename", array('return'=>'contents')), true);
    $this->assertEquals(MessageComponent::error, $result['status']);
    
    $result = json_decode($this->testAction("/categories/rename/$id/$newName", array('return'=>'contents')), true);
    $this->assertEquals(MessageComponent::success, $result['status']);
    
    // check that the previous name doesn't exist anymore
    $cat = $category->findByName('Drug places');
    $this->assertTrue(empty($cat));
    
     // check that the new name is there
    $cat = $category->findByName('Booze places');
    $this->assertFalse(empty($cat));
    
    
    /*$id = '4ff6111b-efb8-4a26-aab4-2184cbdd56cb'; // Goa
    // test if the object returned is a success one
    $result = json_decode($this->testAction("/categories/get/$id/1", array('return'=>'contents')), true);
    
    pr($result); die();*/
  }
  
  public function testMove(){
    $categoryModel = new Category();
    $categoryModel->useDbConfig = 'test';
    
    $mapusa = $categoryModel->findByName('Mapusa');
    $discoplaces = $categoryModel->findByName('Disco places');
    $drugplaces = $categoryModel->findByName('Drug places');
    
    $testCases = array(
      'firstPosition' => array('id' => $mapusa['Category']['id'], 'position' => '1'),
      'lastPosition' => array('id' => $mapusa['Category']['id'], 'position' => '4'),
      'positionMiddle' => array('id' => $mapusa['Category']['id'], 'position' => '3'),
      'minusPosition' => array('id' => $mapusa['Category']['id'], 'position' => '-1'),
      'differentParent' => array('id' => $mapusa['Category']['id'], 'position' => '1', 'parent_id' => $drugplaces['Category']['id']),
      'wrongId'=>array('id'=>'badid', 'position' => '1')
    );
    
    // insert 2 more categories for this specific test
    $categoryModel->create();
    $categoryModel->save(array('Category'=>array('name'=>'Panjim', 'parent_id'=>$discoplaces['Category']['id'])));
    $categoryModel->create();
    $categoryModel->save(array('Category'=>array('name'=>'Vaga', 'parent_id'=>$discoplaces['Category']['id'])));
    
    // test without parameters
    // test firstPosition
    $url = "/categories/move";
    $result = json_decode($this->testAction($url, array('return'=>'contents')), true);
    $this->assertEquals(MessageComponent::error, $result['status']); // test if response is a success
    
    // test firstPosition
    $url = "/categories/move/{$testCases['firstPosition']['id']}/{$testCases['firstPosition']['position']}";
    $result = json_decode($this->testAction($url, array('return'=>'contents')), true);
    $this->assertEquals(MessageComponent::success, $result['status']); // test if response is a success
    // test if the category is at the right place at present
    $afterSave = $categoryModel->findById($mapusa['Category']['id']);
    $this->assertEquals($afterSave['Category']['lft'], $discoplaces['Category']['lft'] + 1);
    
    // test lastPosition
    $url = "/categories/move/{$testCases['lastPosition']['id']}/{$testCases['lastPosition']['position']}";
    $result = json_decode($this->testAction($url, array('return'=>'contents')), true);
    $this->assertEquals(MessageComponent::success, $result['status']); // test if response is a success
    // test if the category is at the right place at present
    $afterSave = $categoryModel->findById($mapusa['Category']['id']);
    $this->assertEquals($afterSave['Category']['lft'], $discoplaces['Category']['lft'] + 7);
    
    // test positionMiddle
    $url = "/categories/move/{$testCases['positionMiddle']['id']}/{$testCases['positionMiddle']['position']}";
    $result = json_decode($this->testAction($url, array('return'=>'contents')), true);
    $this->assertEquals(MessageComponent::success, $result['status']); // test if response is a success
    // test if the category is at the right place at present
    $afterSave = $categoryModel->findById($mapusa['Category']['id']);
    $this->assertEquals($afterSave['Category']['lft'], $discoplaces['Category']['lft'] + 5);
    
    // test minusPosition
    $url = "/categories/move/{$testCases['minusPosition']['id']}/{$testCases['minusPosition']['position']}";
    $result = json_decode($this->testAction($url, array('return'=>'contents')), true);
    $this->assertEquals(MessageComponent::error, $result['status']); // test if response is an error

    // test differentParent
    $url = "/categories/move/{$testCases['differentParent']['id']}/{$testCases['differentParent']['position']}/{$testCases['differentParent']['parent_id']}";
    $result = json_decode($this->testAction($url, array('return'=>'contents')), true);
    $this->assertEquals(MessageComponent::success, $result['status']); // test if response is a success
    // test if the category is at the right place at present
    $afterSave = $categoryModel->findById($mapusa['Category']['id']);
    $this->assertEquals($afterSave['Category']['lft'], $drugplaces['Category']['lft'] + 1);
    
    // test minusPosition
    $url = "/categories/move/{$testCases['wrongId']['id']}/{$testCases['wrongId']['position']}";
    $result = json_decode($this->testAction($url, array('return'=>'contents')), true);
    $this->assertEquals(MessageComponent::error, $result['status']); // test if response is an error    
  }
  
}
