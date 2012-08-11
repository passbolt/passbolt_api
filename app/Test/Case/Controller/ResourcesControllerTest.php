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
	}
	
	public function testView()		{
		$this->Resource = new Resource();
		$this->Resource->useDbConfig = 'test';
		$festival = $this->Resource->findByName('festival du cinema');
		$id = $festival['Resource']['id'];
		
		// test when no parameters are provided
		$result = json_decode($this->testAction("/resources/view.json", array('return'=>'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "/resources/view.json : The test should return an error but is returning {$result['header']['status']}");

		// test when a wrong id is provided
		$result = json_decode($this->testAction("/categories/view/4ff6111b-efb8-4a26-aab4-2184cbdd56ca.json", array('return'=>'contents')), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "/resources/view/4ff6111b-efb8-4a26-aab4-2184cbdd56ca.json : The test should return an error but is returning {$result['header']['status']}");

		// test if the object returned is a success one
		$result = json_decode($this->testAction("/resources/view/$id.json", array('return'=>'contents')), true);
		pr($result); die();
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], 
			'resources/view/' . $id . '.json should return success'
		);
	}
}