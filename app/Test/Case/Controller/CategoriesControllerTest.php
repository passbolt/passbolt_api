<?php
//App::uses('Controller', 'Controller');
App::uses('CategoriesController', 'Controller');

class CategoriesControllerTest extends ControllerTestCase {
	public $fixtures = array('CategoriesController');
	
	public function setUp() {
		parent::setUp();
		$cat = new CategoriesController();
    }

    public function testAdd() {
        $result = $this->testAction('/categories/index');
        debug($result);
        //$this->assertTrue(true);
    }

}