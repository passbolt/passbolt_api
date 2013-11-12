<?php
/**
 * CategoryResourcesController Tests
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @package      app.Test.Case.Controller.CategoryResourcesController
 * @since        version 2.12.7
 * @license      http://www.passbolt.com/license
 */
App::uses('AppController', 'Controller');
App::uses('CategoriesResourcesController', 'Controller');
App::uses('CategoryResource', 'Model');
App::uses('Category', 'Model');
App::uses('Resource', 'Model');
App::uses('User', 'Model');
App::uses('Role', 'Model');

if (!class_exists('CakeSession')) {
	require CAKE . 'Model/Datasource/CakeSession.php';
}

class CategoriesResourcesControllerTest extends ControllerTestCase {

	public $fixtures = array(
		'app.category', 'app.resource', 'app.categories_resource',
		'app.group', 'app.user', 'app.groups_user', 'app.role',
		'app.permission', 'app.permissions_type', 'app.permission_view',
		'app.authenticationBlacklist');

	public function setUp() {
		$this->Category = new Category();
		$this->User = new User();
		$this->Resource = new Resource();
		$this->CategoryResource = new CategoryResource();
		$this->CategoryResource->useDbConfig = 'test';
		$this->Category->useDbConfig = 'test';
		$this->Resource->useDbConfig = 'test';
		$this->User->useDbConfig = 'test';
		parent::setUp();
		
		// log the user as a manager to be able to access all categories
		$kk = $this->User->findByUsername('dark.vador@passbolt.com');
		$this->User->setActive($kk);
	}

	public function testViewCatResIdIsMissing() {
		$this->expectException('HttpException', 'The categoryResource id is missing');
		$this->testAction("/categoriesResources.json", array('method' => 'get', 'return' => 'contents'));
	}

	public function testViewCatResIdNotValid() {
		$this->expectException('HttpException', 'The categoryResource id is invalid');
		$this->testAction("/categoriesResources/badid.json", array('method' => 'get', 'return' => 'contents'));
	}

	public function testViewCatResDoesNotExist() {
		$this->expectException('HttpException', 'The categoryResource does not exist');
		$this->testAction("/categoriesResources/4ff6111b-efb8-4a26-aab4-2184cbdd56ca.json", array('method' => 'get', 'return' => 'contents'));
	}

	public function testView() {
		$id = '50d77ffa-45fc-423c-a5b1-1b63d7a10fce';

		// test when no parameters are provided
		$result = json_decode($this->testAction("/categoriesResources/$id.json", array('method' => 'get', 'return' => 'contents')), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "/categoriesResources.json : The test should return success but is returning {$result['header']['status']}");
	}

	public function testAddNoDataProvided() {
		$this->expectException('HttpException', 'No data were provided');
		$this->testAction('/categoriesResources.json', array(
			 'method' => 'post',
			 'return' => 'contents'
		));
	}

	public function testWrongDataProvided() {
		$data = array(
			'CategoryResource' => array(
				'category_id' => '4ff6111b-efb8-4a26-aab4-2184cbdd56ca',
				'resource_id' => '4ff6111b-efb8-4a26-aab4-2184cbdd56ca',
			)
		);
		$this->expectException('HttpException', 'Could not validate data');
		$this->testAction('/categoriesResources.json', array(
			'data' => $data,
			'method' => 'post',
			'return' => 'contents'
		));
	}
	
	public function testAdd() {
		$cat = $this->Category->findByName('Bolt Softwares Pvt. Ltd.');
		$res = $this->Resource->findByName('facebook account');
		$data = array(
			'CategoryResource' => array(
				'category_id' => $cat['Category']['id'],
				'resource_id' => $res['Resource']['id']
			)
		);

		$result = json_decode($this->testAction('/categoriesResources.json', array(
			'data' => $data,
			 'method' => 'post',
			 'return' => 'contents'
		)), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "Add : /categoriesResources.json : The test should return sucess but is returning " . print_r($result, true));
		// check that Categories were properly saved
		$cr = $this->CategoryResource->find('all', array(
			'conditions' => array(
				'category_id' => $cat['Category']['id'],
				'resource_id' => $res['Resource']['id']
			)
		));
		$this->assertEquals(1, count($cr), "Add : /categoriesResources.json : The number of categoriesResources returned should be 1, but actually is " . count($cr));
	}

	public function testDeleteCatResIdIsMissing() {
		$this->expectException('HttpException', 'The categoryResource id is missing');
		$this->testAction("/categoriesResources.json", array('method' => 'delete', 'return' => 'contents'));
	}

	public function testDeleteCatResIdNotValid() {
		$this->expectException('HttpException', 'The categoryResource id is invalid');
		$this->testAction("/categoriesResources/badid.json", array('method' => 'delete', 'return' => 'contents'));
	}

	public function testDeleteCatResDoesNotExist() {
		$this->expectException('HttpException', 'The categoryResource does not exist');
		$this->testAction("/categoriesResources/4ff6111b-efb8-4a26-aab4-2184cbdd56ca.json", array('method' => 'delete', 'return' => 'contents'));
	}

	public function testDelete() {
		$id = '50d77ffa-45fc-423c-a5b1-1b63d7a10fce';
		$result = json_decode($this->testAction("/categoriesResources/$id.json", array(
			 'method' => 'delete',
			 'return' => 'contents'
		)), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "delete /categoriesResources/$id.json : The test should return a success but is returning {$result['header']['status']}");

		$found = $this->CategoryResource->findById($id);
		$this->assertEqual(
			count($found), 0,
			"delete /categoriesResources/$id.json : This test should have fetched 0 elements from the database but it is not the case. Is the element properly deleted ?"
		);
	}
}
