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
		$kk = $user->findByUsername('kevin@passbolt.com');
		$user->setActive($kk);
		
		$this->Resource = new Resource();
		$this->Resource->useDbConfig = 'test';
	}
	
	public function testView()		{
		$festival = $this->Resource->findByName('festival du cinema');
		$id = $festival['Resource']['id'];
		
		// test when no parameters are provided
		$result = json_decode($this->testAction("/resources/view.json", array('return'=>'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "/resources/view.json : The test should return an error but is returning {$result['header']['status']}");

		// test when a wrong id is provided
		$result = json_decode($this->testAction("/resources/view/4ff6111b-efb8-4a26-aab4-2184cbdd56ca.json", array('return'=>'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "/resources/view/4ff6111b-efb8-4a26-aab4-2184cbdd56ca.json : The test should return an error but is returning {$result['header']['status']}");

		// test if the object returned is a success one
		$result = json_decode($this->testAction("/resources/view/$id.json", array('return'=>'contents')), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], 
			'resources/view/' . $id . '.json should return success'
		);
		
		$this->assertEquals('festival du cinema', $result['body']['Resource']['name'], 
			'resources/view/' . $id . ".json should a resource named 'festival du cinema' but returned {$result['body']['Resource']['name']} instead"
		);
	}

 public function testViewByCategory(){
 	$categoryModel = new Category();
		$categoryModel->useDbConfig = 'test';
		$goaCat = $categoryModel->findByName('Goa');
		
		$id =  $goaCat['Category']['id'];
		
		// test when no parameters are provided
		$result = json_decode($this->testAction("/resources/viewByCategory.json", array('return'=>'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "/resources/viewByCategory.json : The test should return an error but is returning {$result['header']['status']}");
		
		// test when a wrong id is provided
		$result = json_decode($this->testAction("/resources/viewByCategory/4ff6111b-efb8-4a26-aab4-2184cbdd56ca.json", array('return'=>'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "/resources/viewByCategory/4ff6111b-efb8-4a26-aab4-2184cbdd56ca.json : The test should return an error but is returning {$result['header']['status']}");

		// test if the object returned is a success one
		$url = "/resources/viewByCategory/". $id .".json";
		$result = json_decode($this->testAction($url, array('return'=>'contents')), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], 
			$url . " should return success but returned {$result['header']['status']}"
		);
		$this->assertEquals('festival du cinema', $result['body'][0]['Resource'][1]['name'], 
			$url ." test should read 'festival du cinema' but is reading {$result['body'][0]['Resource'][1]['name']}"
		);
		$this->assertEquals(2, sizeof($result['body'][0]['Resource']), 
			$url ." counting the number of elements should return '2' but is reading ". sizeof($result['body'][0]['Resource'])
		);
		
		$url = "/resources/viewByCategory/". $id ."/1.json";
		$result = json_decode($this->testAction($url, array('return'=>'contents')), true);
		$this->assertEquals('washroom', $result['body'][5]['Resource'][0]['name'], 
			$url ." test should read 'washroom' but is reading {$result['body'][5]['Resource'][0]['name']}"
		);
		$this->assertEquals(14, sizeof($result['body']), 
			$url ." counting the number of elements should return '14' but is reading ". sizeof($result['body'])
		);
 } 


}