<?php
/**
 * Permissions Controller Tests
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @package      app.Test.Case.Controller.PermissionsController
 * @license      http://www.passbolt.com/license
 * @since        version 2.12.12
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

	public $fixtures = array(
		'app.resource', 'app.category', 'app.categories_resource', 
		'app.user', 'app.group', 'app.groups_user', 'app.role', 
		'app.permission', 'app.permissions_type', 'app.permission_view',
		'app.authenticationBlacklist');

	public function setUp() {
		$this->User = ClassRegistry::init('User');
		$this->Resource = ClassRegistry::init('Resource');
		$this->Category = ClassRegistry::init('Category');
		parent::setUp();
		
		// log the user as a manager to be able to access all categories
		$kk = $this->User->findByUsername('dark.vador@passbolt.com');
		$this->User->setActive($kk);
	}

	// test view aco permissions action parameters
	public function testViewAcoPermissionsParameters() {
		$getOptions = array(
			 'method' => 'get',
			 'return' => 'contents'
		);
		
		// Test model that are not permissionable and allowed to be used as acos foreign key by the permission model	
		// PERMISSIONABLE MODELS
		$model = 'Resource';
		$id = '509bb871-5168-49d4-a676-fb098cebc04d'; // has to exist
		$srvResult = json_decode($this->testAction("/permissions/viewAcoPermissions/$model/$id.json", $getOptions), true);
		$this->assertEquals(Message::SUCCESS, $srvResult['header']['status'], "/permissions/viewAcoPermissions/$model/$id.json : The test should return a success but is returning {$srvResult['header']['status']}");

		$model = 'Category';
		$id = '50d77ff7-5208-4dc2-94d1-1b63d7a10fce'; // has to exist
		$srvResult = json_decode($this->testAction("/permissions/viewAcoPermissions/$model/$id.json", $getOptions), true);
		$this->assertEquals(Message::SUCCESS, $srvResult['header']['status'], "/permissions/viewAcoPermissions/$model/$id.json : The test should return a success but is returning {$srvResult['header']['status']}");
		
		// NOT PERMISSIONABLE MODELS
		$model = 'User';
		$id = '50cdab9c-4380-4eb6-b4cc-2f4fd7a10fce'; // has to exist
		$srvResult = json_decode($this->testAction("/permissions/viewAcoPermissions/$model/$id.json", $getOptions), true);
		$this->assertEquals(Message::ERROR, $srvResult['header']['status'], "/permissions/viewAcoPermissions/$model/$id.json : The test should return an error but is returning {$srvResult['header']['status']}");

		// NOT EXISTING MODELS
		$model = 'NotExistingModel';
		$id = '50cdab9c-4380-4eb6-b4cc-2f4fd7a10fce'; // has to exist
		$srvResult = json_decode($this->testAction("/permissions/viewAcoPermissions/$model/$id.json", $getOptions), true);
		$this->assertEquals(Message::ERROR, $srvResult['header']['status'], "/permissions/viewAcoPermissions/$model/$id.json : The test should return an error but is returning {$srvResult['header']['status']}");

		// Test given model instance id
		// NULL ID
		$id = null;
		$srvResult = json_decode($this->testAction("/permissions/viewAcoPermissions/$model/$id.json", $getOptions), true);
		$this->assertEquals(Message::ERROR, $srvResult['header']['status'], "/permissions/resource/$id.json : The test should return an error but is returning {$srvResult['header']['status']}");
		
		// NOT EXISTING MODEL INSTANCE
		$id = '00000000-0000-0000-0000-000000000000';
		$srvResult = json_decode($this->testAction("/permissions/viewAcoPermissions/$model/$id.json", $getOptions), true);
		$this->assertEquals(Message::ERROR, $srvResult['header']['status'], "/permissions/resource/$id.json : The test should return an error but is returning {$srvResult['header']['status']}");
		
		// INVALID ID FORMAT
		$id = 'invalid-id-format';
		$srvResult = json_decode($this->testAction("/permissions/viewAcoPermissions/$model/$id.json", $getOptions), true);
		$this->assertEquals(Message::ERROR, $srvResult['header']['status'], "/permissions/resource/$id.json : The test should return an error but is returning {$srvResult['header']['status']}");
	}

	// test view aco permissions action with a not allowed user
	// not allowed => Permission.type < PermissionType::READ 
	public function testViewAcoPermissionsUserNotAllowed() {	
		$getOptions = array(
			 'method' => 'get',
			 'return' => 'contents'
		);
		
		// try to get permissions on a Resource with a not allowed user
		$categoryName = 'o-project1';
		$category = $this->Category->findByName($categoryName);
		$id = $category['Category']['id'];
		
		// log the user who is not allowed to access the category
		$kk = $this->User->findByUsername('test@passbolt.com');
		$this->User->setActive($kk);
		
		$srvResult = json_decode($this->testAction("/permissions/category/$id.json", $getOptions), true);
		$this->assertEquals(Message::ERROR, $srvResult['header']['status'], "/permissions/category/$id.json : The test should return an error but is returning {$srvResult['header']['status']}");
	}
	
	// test view aco permissions on Resource Aco
	public function testViewAcoPermissionsOnResource() {
		$getOptions = array(
			 'method' => 'get',
			 'return' => 'contents'
		);
		
		// Just group permissions should be returned
		// Check permission on the resource op1-pwd1
		$expectedPermissions = array(
			'50f6b4af-a491-43f5-fac9-23a4d7a10fce', // franck zappa has deny rights on projects
			'50e6b4ae-ea4c-4baf-aaf4-23a4d7a1a09f', // user manager.nogroup (manager with no group should have access to everything in aucr mode)
			'50e6b4af-ad14-4659-a60d-23a4d7a10fce', // user remy (remy bertot have admin rights on others)
			'50e6b4ae-ea4c-4baf-aaf4-23a4d7a10fce', // group management (management access everything in aucr mode)
			'50e6b4af-a490-43f5-9cc9-23a4d7a10fce', // group developers team leads (developers team leads have create/modify rights on projects)
			'50e6b4af-b124-40e3-988e-23a4d7a10fce', // group freelancers (freelancers have readonly rights on projects > others)
			'50e6b4af-d4b0-43d8-947f-23a4d7a10fce', // group company a (company a can access o-project1 in read only, and o-project2 in modify)
		);
		$expectedCount = count($expectedPermissions);
		$resourceName = 'op1-pwd1';
		$resource = $this->Resource->findByName($resourceName);
		$id = $resource['Resource']['id'];
		$srvResult = json_decode($this->testAction("/permissions/resource/$id.json", $getOptions), true);
		// How many results we expect
		$this->assertNotNull(count($srvResult['body']), "We expect permissions for the resources {$resourceName}");
		// All expected permissions are in the server answer
		foreach($srvResult['body'] as $perm) {
			$this->assertTrue(in_array($perm['Permission']['id'], $expectedPermissions), "The permission {$perm['Permission']['id']} should be associated to the resource $resourceName"); 
		}
		
		// Check mix group and user
		// Check permission on the resource cpp1-pwd1
		$expectedPermissions = array(
			'50f6b4af-a491-43f5-fac9-23a4d7a10fce', // franck zappa has deny rights on projects
			'50e6b4ae-ea4c-4baf-aaf4-23a4d7a1a09f', // user manager.nogroup (manager with no group should have access to everything in aucr mode)
			'50e6b4af-c390-4e5e-a8f8-23a4d7a10fce', // user jean rene (ean rené can access projects > cakephp > cp-project1 > cpp1-pwd1 in readonly)
			'50e6b4af-aa58-478c-804d-23a4d7a10fce', // user remy (remy bertot has admin rights on cakephp > cp-project1)
			'50e6b4ae-ea4c-4baf-aaf4-23a4d7a10fce', // group management (management access everything in aucr mode)
			'50e6b4af-a490-43f5-9cc9-23a4d7a10fce', // group developers team leads (developers team leads have create/modify rights on projects)
			'50e6b4af-8ab8-4533-a4b4-23a4d7a10fce', // group developers cakephp (developers cakephp can access projects > cakephp in readonly)
		);
		$expectedCount = count($expectedPermissions);
		$resourceName = 'cpp1-pwd1';
		$resource = $this->Resource->findByName($resourceName);
		$id = $resource['Resource']['id'];
		$srvResult = json_decode($this->testAction("/permissions/resource/$id.json", $getOptions), true);
		// How many results we expect
		$this->assertNotNull(count($srvResult['body']), "We expect permissions for the resources {$resourceName}");
		// All expected permissions are in the server answer
		foreach($srvResult['body'] as $perm) {
			$this->assertTrue(in_array($perm['Permission']['id'], $expectedPermissions), "The permission {$perm['Permission']['id']} should be associated to the resource {$resourceName}"); 
		}
	}

	public function testViewAcoPermissionsOnCategory() {
		$getOptions = array(
			 'method' => 'get',
			 'return' => 'contents'
		);
				
		// Just group permissions should be returned
		// Check permission on the resource op1-pwd1
		$expectedPermissions = array(
			'50f6b4af-a491-43f5-fac9-23a4d7a10fce', // franck zappa has deny rights on projects
			'50e6b4ae-ea4c-4baf-aaf4-23a4d7a1a09f', // user manager.nogroup (manager with no group should have access to everything in aucr mode)
			'50e6b4af-aa58-478c-804d-23a4d7a10fce', // user remy (remy bertot has admin rights on cakephp > cp-project1)
			'50e6b4ae-ea4c-4baf-aaf4-23a4d7a10fce', // group management (management access everything in aucr mode)
			'50e6b4af-a490-43f5-9cc9-23a4d7a10fce', // group developers team leads (developers team leads have create/modify rights on projects)
			'50e6b4af-8ab8-4533-a4b4-23a4d7a10fce', // group developers cakephp (developers cakephp can access projects > cakephp in readonly)
		);
		$catName = 'cp-project1';
		$expectedCount = count($expectedPermissions);
		$category = $this->Category->findByName($catName);
		$id = $category['Category']['id'];
		$srvResult = json_decode($this->testAction("/permissions/category/$id.json", $getOptions), true);
		
		// How many results we expect
		$this->assertNotEmpty($srvResult['body'], $expectedCount, 'We expect permissions for the category ' . $catName);
		// All expected permissions are in the server answer
		foreach($srvResult['body'] as $perm) {
			$this->assertTrue(in_array($perm['Permission']['id'], $expectedPermissions), "The permission {$perm['Permission']['id']} should be associated to the category {$catName}"); 
		}
	}

	// test add aco permissions action parameters
	public function testAddAcoPermissionsParameters() {
		$postOptions = array(
			'method' => 'post',
			 'return' => 'contents',
			 'data'=> array(
				 'Permission' => array(
					'type' => PermissionType::READ
				),
				'User' => array(
					'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4e' // test@passbolt.com
				)
			 )
		);
		
		// Test model that are not permissionable and allowed to be used as acos foreign key by the permission model	
		// NOT PERMISSIONABLE MODELS
		$model = 'User';
		$id = '50cdab9c-4380-4eb6-b4cc-2f4fd7a10fce'; // has to exist
		$srvResult = json_decode($this->testAction("/permissions/addAcoPermissions/$model/$id.json", $postOptions), true);
		$this->assertEquals(Message::ERROR, $srvResult['header']['status'], "/permissions/addAcoPermissions/$model/$id.json : The test should return an error but is returning {$srvResult['header']['status']}");

		// NOT EXISTING MODELS
		$model = 'NotExistingModel';
		$id = '50cdab9c-4380-4eb6-b4cc-2f4fd7a10fce'; // has to exist
		$srvResult = json_decode($this->testAction("/permissions/addAcoPermissions/$model/$id.json", $postOptions), true);
		$this->assertEquals(Message::ERROR, $srvResult['header']['status'], "/permissions/addAcoPermissions/$model/$id.json : The test should return an error but is returning {$srvResult['header']['status']}");

		// Test given model instance id
		// NULL ID
		$model = 'Resource';
		$id = null;
		$srvResult = json_decode($this->testAction("/permissions/addAcoPermissions/$model/$id.json", $postOptions), true);
		$this->assertEquals(Message::ERROR, $srvResult['header']['status'], "/permissions/addAcoPermissions/$model/$id.json : The test should return an error but is returning {$srvResult['header']['status']}");
		
		// NOT EXISTING MODEL INSTANCE
		$model = 'resource';
		$id = '00000000-0111-0000-0000-000000000000';
		$srvResult = json_decode($this->testAction("/permissions/addAcoPermissions/$model/$id.json", $postOptions), true);
		$this->assertEquals(Message::ERROR, $srvResult['header']['status'], "/permissions/$model/$id.json : The test should return an error but is returning {$srvResult['header']['status']}");
		
		// INVALID ID FORMAT
		$model = 'resource';
		$id = 'invalid-id-format';
		$srvResult = json_decode($this->testAction("/permissions/addAcoPermissions/$model/$id.json", $postOptions), true);
		$this->assertEquals(Message::ERROR, $srvResult['header']['status'], "/permissions/$model/$id.json : The test should return an error but is returning {$srvResult['header']['status']}");
	}

	// test add aco permissions action user not allowed
	public function testAddAcoPermissionsUserNotAllowed() {	
		// try to get permissions on a Resource with a not allowed user
		$categoryName = 'o-project1';
		$category = $this->Category->findByName($categoryName);
		$id = $category['Category']['id'];
		
		// log the user who is not allowed to access the category
		$kk = $this->User->findByUsername('test@passbolt.com');
		$this->User->setActive($kk);
		
		$data = array(
			'Permission' => array(
				'type' => PermissionType::READ
			),
			'User' => array(
				'id' => User::get('id')
			)
		);
		$postOptions = array(
			 'method' => 'post',
			 'return' => 'contents',
			 'data' => $data
		);
		
		$srvResult = json_decode($this->testAction("/permissions/category/$id.json", $postOptions), true);
		$this->assertEquals(Message::ERROR, $srvResult['header']['status'], "/permissions/category/$id.json : The test should return an error but is returning {$srvResult['header']['status']}");
	}
	
	public function testAddAcoPermissionsOnResource() {	
		// log with a user who has right on the unit test sandbox category
		$kk = $this->User->findByUsername('utest@passbolt.com');
		$this->User->setActive($kk);
		
		// Add a permisision for a given user to a given category
		$model = 'resource';
		$resName = 'utest1-pwd1';
		$resource = $this->Resource->findByName($resName);
		$id = $resource['Resource']['id'];
		$data = array(
			'Permission' => array(
				'type' => PermissionType::READ
			),
			'User' => array(
				'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4e' // test@passbolt.com, but we can put any other users
			)
		);
		
		// check how many permissions are already existing before the new insertion
		$srvResult = json_decode($this->testAction("/permissions/$model/$id.json", array(
			 'method' => 'get',
			 'return' => 'contents'
		)), true);
		$expectedCount = count($srvResult['body']) + 1;
		// insert the new permission
		$srvResult = json_decode($this->testAction("/permissions/$model/$id.json", array(
			 'method' => 'post',
			 'return' => 'contents',
			 'data'=> $data
		)), true);
		
		$this->assertEquals(Message::SUCCESS, $srvResult['header']['status'], "/permissions/$model/$id.json : The test should return a success but is returning {$srvResult['header']['status']}");
		// check the permission has well been inserted
		$srvResult = json_decode($this->testAction("/permissions/$model/$id.json", array(
			 'method' => 'get',
			 'return' => 'contents'
		)), true);
		$this->assertEquals($expectedCount, count($srvResult['body']), "/permissions/$model/$id.json : The test should return {$expectedCount} permissions but is returning " . count($srvResult['body']));
		
		// try to insert a second time the same permission should return an error
		$srvResult = json_decode($this->testAction("/permissions/$model/$id.json", array(
			 'method' => 'post',
			 'return' => 'contents',
			 'data'=> $data
		)), true);
		$this->assertEquals(Message::ERROR, $srvResult['header']['status'], "/permissions/$model/$id.json : The test should return an error but is returning {$srvResult['header']['status']}");
	}
}
