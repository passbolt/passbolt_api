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

	public $fixtures = array('app.resource', 'app.category', 'app.categories_resource', 'app.user', 'app.group', 'app.groups_user', 'app.role', 'app.permission', 'app.authenticationBlacklist');

	public function setUp() {
		$this->User  = ClassRegistry::init('User');
		$this->Resource  = ClassRegistry::init('Resource');
		$this->Category  = ClassRegistry::init('Category');
		parent::setUp();
	}

	public function testViewAcoPermissionsBadParameters() {
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
		$srvResult = json_decode($this->testAction("/permissions/viewAcoPermissions/resource/$id.json", array(
			 'method' => 'get',
			 'return' => 'contents'
		)), true);
		$this->assertEqual(
			$srvResult['header']['message'], __('The id is missing'),
			'The action should return an error with a null id'
		);
		
		// Test not existing instance
		$id = '00000000-0000-0000-0000-000000000000';
		$srvResult = json_decode($this->testAction("/permissions/resource/$id.json", array(
			 'method' => 'get',
			 'return' => 'contents'
		)), true);
		$this->assertEqual(
			$srvResult['header']['message'], __('The instance doesn\'t exist'),
			'The action should return an error with a not existing instance id'
		);
		
		// Test invalid id
		$id = 'invalid-id-format';
		$srvResult = json_decode($this->testAction("/permissions/resource/$id.json", array(
			 'method' => 'get',
			 'return' => 'contents'
		)), true);
		$this->assertEqual(
			$srvResult['header']['message'], __('The id is invalid'),
			'The action should return an error with an invalid id'
		);
	}

	public function testViewAcoPermissionsOnResource() {
		$kk = $this->User->findByUsername('dark.vador@passbolt.com');
		$this->User->setActive($kk);
		
		// Just group permissions should be returned
		// Check permission on the resource op1-pwd1
		$expectedPermissions = array(
			'50e6b4ae-ea4c-4baf-aaf4-23a4d7a10fce', // group management (maybe not up to date : management access everything in aucr mode)
			'50e6b4af-a490-43f5-9cc9-23a4d7a10fce', // group developers team leads (maybe not up to date : developers team leads have create/modify rights on projects)
			'50e6b4af-b124-40e3-988e-23a4d7a10fce', // group freelancers (maybe not up to date : freelancers have readonly rights on projects > others)
			'50e6b4af-d4b0-43d8-947f-23a4d7a10fce', // group company a (company a can access o-project1 in read only, and o-project2 in modify)
		);
		
		$resource = $this->Resource->findByName('op1-pwd1');
		$id = $resource['Resource']['id'];
		$srvResult = json_decode($this->testAction("/permissions/resource/$id.json", array(
			 'method' => 'get',
			 'return' => 'contents'
		)), true);
		
		// We expect 4 results
		$this->assertEqual(
			count($srvResult['body']), 4, 
			'We expect 4 permissions for the resources op1-pwd1'
		);
		foreach($srvResult['body'] as $perm) {
			$this->assertTrue(in_array($perm['Permission']['id'], $expectedPermissions), 'The permission ' . $perm['Permission']['id'] . ' should be associated to the resource (by name) op1-pwd1'); 
		}
		
		// Check mix group and user
		// Check permission on the resource cpp1-pwd1
		$expectedPermissions = array(
			'50e6b4af-c390-4e5e-a8f8-23a4d7a10fce', // user jean rene (maybe not up to date : ean rené can access projects > cakephp > cp-project1 > cpp1-pwd1 in readonly)
			'50e6b4af-aa58-478c-804d-23a4d7a10fce', // user remy (maybe not up to date : remy bertot has admin rights on cakephp > cp-project1)
			'50e6b4ae-ea4c-4baf-aaf4-23a4d7a10fce', // group management (maybe not up to date : management access everything in aucr mode)
			'50e6b4af-a490-43f5-9cc9-23a4d7a10fce', // group developers team leads (maybe not up to date : developers team leads have create/modify rights on projects)
			'50e6b4af-8ab8-4533-a4b4-23a4d7a10fce', // group developers cakephp (maybe not up to date : developers cakephp can access projects > cakephp in readonly)
		);
		
		$resource = $this->Resource->findByName('cpp1-pwd1');
		$id = $resource['Resource']['id'];
		$srvResult = json_decode($this->testAction("/permissions/resource/$id.json", array(
			 'method' => 'get',
			 'return' => 'contents'
		)), true);
		
		// We expect 5 results
		$this->assertEqual(
			count($srvResult['body']), 5, 
			'We expect 5 permissions for the resources cpp1-pwd1'
		);
		foreach($srvResult['body'] as $perm) {
			$this->assertTrue(in_array($perm['Permission']['id'], $expectedPermissions), 'The permission ' . $perm['Permission']['id'] . ' should be associated to the resource (by name) cpp1-pwd1'); 
		}
	}

	public function testViewAcoPermissionsOnCategory() {
		$kk = $this->User->findByUsername('dark.vador@passbolt.com');
		$this->User->setActive($kk);
				
		// Just group permissions should be returned
		// Check permission on the resource op1-pwd1
		$catName = 'cp-project1';
		$expectedPermissions = array(
			'50e6b4af-aa58-478c-804d-23a4d7a10fce', // user remy (maybe not up to date : remy bertot has admin rights on cakephp > cp-project1)
			'50e6b4ae-ea4c-4baf-aaf4-23a4d7a10fce', // group management (maybe not up to date : management access everything in aucr mode)
			'50e6b4af-a490-43f5-9cc9-23a4d7a10fce', // group developers team leads (maybe not up to date : developers team leads have create/modify rights on projects)
			'50e6b4af-8ab8-4533-a4b4-23a4d7a10fce', // group developers cakephp (maybe not up to date : developers cakephp can access projects > cakephp in readonly)
		);
		
		$category = $this->Category->findByName($catName);
		$id = $category['Category']['id'];
		$srvResult = json_decode($this->testAction("/permissions/category/$id.json", array(
			 'method' => 'get',
			 'return' => 'contents'
		)), true);
		
		// We expect 4 results
		$this->assertEqual(
			count($srvResult['body']), 4, 
			'We expect 4 permissions for the category ' . $catName
		);
		foreach($srvResult['body'] as $perm) {
			$this->assertTrue(in_array($perm['Permission']['id'], $expectedPermissions), 'The permission ' . $perm['Permission']['id'] . ' should be associated to the category (by name) ' . $catName); 
		}
	}
}
