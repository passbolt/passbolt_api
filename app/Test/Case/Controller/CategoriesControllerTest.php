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
  

  /*public function testAdd() {
    // check the response when a category is added
    $result = $this->testAction('/categories/add', array(
      'data' => array(
        'Post' => array('name' => 'New Cat Controller'),
        array('return'=>'vars')
       )
    ));
    $this->assertEquals(MessageComponent::success, $this->controller->Message->messages[0]['status']);
    
    // check that the category has been added
    $category = new Category();
    $category->useDbConfig = 'test';
    $cat = $category->find('all');
    $this->assertTrue($cat != null);
  }*/
  
  public function testGet() {
    $id = '4ff6111b-efb8-4a26-aab4-2184cbdd56cb'; // Goa

    // test if the object returned is a success one
    $result = json_decode($this->testAction("/categories/get/$id/1", array('return'=>'contents')), true);
    $this->assertEquals('success', $result['status']);
    
    // test it is the expected format
    $result = $this->testAction("/categories/get/$id/1", array('return'=>'vars'));
    $this->assertInternalType('array', $result['data']);

    // test that data returned are correct (test anjuna)
    $result = json_decode($this->testAction("/categories/get/$id/1", array('return'=>'contents')), true);
    $this->assertEquals('4ff6111c-8534-4d17-869c-2184cbdd56cb', $result['data'][0]['children'][0]['children'][0]['Category']['id']);
    
    // TODO : test without children
    $result = $this->testAction("/categories/get/$id", array('return'=>'vars'));
    $this->assertFalse(isset($result['data'][0]));
    $this->assertEquals('Goa', $result['data']['Category']['name']);
    
    // test an error
    $result = json_decode($this->testAction("/categories/get/badid/1", array('return'=>'contents')), true);
    $this->assertEquals('error', $result['status']);
     
  }
}
