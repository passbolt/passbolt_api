<?php
/**
 * Permissions Controller Tests
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @package      app.Test.Case.Controller.PermissionsController
 * @license      http://www.passbolt.com/license
 * @since        version 2.12.11
 */
App::uses('AppController', 'Controller');
App::uses('PermissionsController', 'Controller');
App::uses('Resource', 'Model');
App::uses('User', 'Model');
App::uses('Role', 'Model');

// Uses sessions
// App::uses('CakeSession', 'Model/Datasource'); // doesn't work here
if (!class_exists('CakeSession')) {
	require CAKE . 'Model/Datasource/CakeSession.php';
}

class PermissionsControllerTest extends ControllerTestCase {

	public $fixtures = array('app.resource', 'app.category', 'app.categories_resource', 'app.user', 'app.group', 'app.groups_user', 'app.role', 'app.permission');

	public function setUp() {
		$this->User = new User();
		$this->User->useDbConfig = 'test';
		parent::setUp();
	}

	// Check the target model is permissionnable with the viewPermission
	public function testViewPermissionsBadParameters() {
		$kk = $this->User->findByUsername('dark.vador@passbolt.com');
		$this->User->setActive($kk);
		
		// Test model which are not allowed to be used as acos foreign key by the permission model
		$id = '509bb871-5168-49d4-a676-fb098cebc04d';
		$testCases = array(
			'resource'=>true, 'category'=>true,
			'user'=>false, 'role'=>false, 'permission'=>false,
			'not_existing_model'=>false, 'bad_ass_model'=>false
		);
		
		foreach ($testCases as $testcase => $result) {
			$srvResult = json_decode($this->testAction("/permissions/viewAcoPermissions/$testcase/$id.json", array(
				 'method' => 'get',
				 'return' => 'contents'
			)), true);
			
			// Model should not be permissionable
			if(!$result) {
				$this->assertEqual(
					$srvResult['header']['message'], __('The model is not permissionable'), 
					'the model ' . $testcase . ' should not be permissionnable'
				);
			} 
			// Model should be permissionable
			else {
				$this->assertNotEqual(
					$srvResult['header']['message'], __('The model is not permissionable'), 
					'the model ' . $testcase . ' should be permissionnable'
				);
			}
		}
		
		// Test null id
		$id = null;
		$srvResult = json_decode($this->testAction("/permissions/viewAcoPermissions/Resource/$id.json", array(
			 'method' => 'get',
			 'return' => 'contents'
		)), true);
		$this->assertEqual(
			$srvResult['header']['message'], __('The id is missing'),
			'The action should return an error with a null id'
		);
		
		// Test not existing instance
		$id = '00000000-0000-0000-0000-000000000000';
		$srvResult = json_decode($this->testAction("/permissions/viewAcoPermissions/Resource/$id.json", array(
			 'method' => 'get',
			 'return' => 'contents'
		)), true);
		$this->assertEqual(
			$srvResult['header']['message'], __('The instance doesn\'t exist'),
			'The action should return an error with a not existing instance id'
		);
		
		// Test invalid id
		$id = 'invalid-id-format';
		$srvResult = json_decode($this->testAction("/permissions/viewAcoPermissions/Resource/$id.json", array(
			 'method' => 'get',
			 'return' => 'contents'
		)), true);
		$this->assertEqual(
			$srvResult['header']['message'], __('The id is invalid'),
			'The action should return an error with an invalid id'
		);
	}
	
}
