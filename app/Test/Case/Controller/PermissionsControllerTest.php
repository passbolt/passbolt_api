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
App::uses('UsersController', 'Controller');
App::uses('User', 'Model');
App::uses('Role', 'Model');
App::uses('Resource', 'Model');
App::uses('Category', 'Model');
// App::uses('Group', 'Model');
App::uses('UserResourcePermission', 'Model');
App::uses('GroupResourcePermission', 'Model');
App::uses('UserCategoryPermission', 'Model');
App::uses('GroupCategoryPermission', 'Model');
App::uses('CakeSession', 'Model');
App::uses('CakeSession', 'Model/Datasource');

class PermissionsControllerTest extends ControllerTestCase {

	public $fixtures = array(
		'app.resource',
		'app.category',
		'app.categories_resource',
		'app.user',
		'app.group',
		'app.groups_user',
		'app.role',
		'app.profile',
		'app.file_storage',
		'app.gpgkey',
		'app.permission',
		'app.permissions_type',
		'app.permission_view',
		'app.authenticationLog',
		'app.authenticationBlacklist',
		'core.cakeSession',
	);

	public $user;

	public $session;
	
	public function setUp() {
		parent::setUp();
		
		$this->User = Common::getModel('User');
		$u = $this->User->get();
		$this->Resource = Common::getModel('Resource');
		$this->Category = Common::getModel('Category');
		$this->Permission = Common::getModel('Permission');
		$this->UserResourcePermission = Common::getModel('UserResourcePermission');
		$this->GroupResourcePermission = Common::getModel('GroupResourcePermission');
		$this->UserCategoryPermission = Common::getModel('UserCategoryPermission');
		$this->GroupCategoryPermission = Common::getModel('GroupCategoryPermission');
		
		$this->session = new CakeSession();
		$this->session->init();
		
		// log the user as a manager to be able to access all categories
		$user = $this->User->findByUsername('dame@passbolt.com');
		$this->User->setActive($user);
	}

	public function tearDown() {
		// Make sure there is no session active after each test
		parent::tearDown();
		$this->User->setInactive();
	}

	public function testViewAcoPermissionsNotExistingModel() {
		$model = 'NotExistingModel';
		$id = '50cdab9c-4380-4eb6-b4cc-2f4fd7a10fce';
		$this->setExpectedException('HttpException', "The model {$model} is not permissionable");
		$srvResult = json_decode($this->testAction("/permissions/viewAcoPermissions/$model/$id.json", array('method' => 'get', 'return' => 'contents')), true);
	}

	public function testViewAcoPermissionsNotPermissionableModel() {
		$model = 'User';
		$id = '50cdab9c-4380-4eb6-b4cc-2f4fd7a10fce';
		$this->setExpectedException('HttpException', "The model {$model} is not permissionable");
		$srvResult = json_decode($this->testAction("/permissions/viewAcoPermissions/$model/$id.json", array('method' => 'get', 'return' => 'contents')), true);
	}

	public function testViewAcoPermissionsModelIdIsMissing() {
		$model = 'Resource';
		$this->setExpectedException('HttpException', "The {$model} id is missing");
		$srvResult = json_decode($this->testAction("/permissions/viewAcoPermissions/$model.json", array('method' => 'get', 'return' => 'contents')), true);
	}

	public function testViewAcoPermissionsModelIdIsInvalid() {
		$model = 'Resource';
		$id = 'badId';
		$this->setExpectedException('HttpException', "The {$model} id is invalid");
		$srvResult = json_decode($this->testAction("/permissions/viewAcoPermissions/$model/$id.json", array('method' => 'get', 'return' => 'contents')), true);
	}

	public function testViewAcoPermissionsModelInstanceDoesNotExist() {
		$model = 'Resource';
		$id = '00000000-0000-0000-0000-000000000000';
		$this->setExpectedException('HttpException', "The {$model} does not exist");
		$srvResult = json_decode($this->testAction("/permissions/viewAcoPermissions/$model/$id.json", array('method' => 'get', 'return' => 'contents')), true);
	}

	// test view aco permissions action with a not allowed user
	// not allowed => Permission.type < PermissionType::READ (In other words 0)
	public function testViewAcoPermissionsUserNotAllowed() {	
		$getOptions = array(
			 'method' => 'get',
			 'return' => 'contents'
		);
		
		// try to get permissions on a Resource with a not allowed user
		$categoryName = 'o-project1';
		$category = $this->Category->findByName($categoryName);
		$id = $category['Category']['id'];
		
		// If the user is not allowed to access a category, this category is simply hidden to him
		$this->setExpectedException('HttpException', "The Category does not exist");
		
		// log the user who is not allowed to access the category
		$user = $this->User->findByUsername('edith@passbolt.com');
		$this->User->setActive($user);
		
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
			'50f6b4af-a491-43f5-fac9-23a4d7a10fce', // Frances Allen has deny rights on projects
			'533d2ecb-3ec8-4437-9ca5-0aafc0a895dc', // user kathleen (manager with no group should have access to everything in aucr mode)
			'50e6b4af-ad14-4659-a60d-23a4d7a10fce', // Ada lovelace have admin rights on others
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
			'50f6b4af-a491-43f5-fac9-23a4d7a10fce', // Frances Allen has deny rights on projects
			'533d2ecb-3ec8-4437-9ca5-0aafc0a895dc', // user kathleen (manager with no group should have access to everything in aucr mode)
			'50e6b4af-c390-4e5e-a8f8-23a4d7a10fce', // user jean rene (ean rené can access projects > cakephp > cp-project1 > cpp1-pwd1 in readonly)
			'50e6b4af-aa58-478c-804d-23a4d7a10fce', // Ada lovelace has admin rights on cakephp > cp-project1
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
			'50f6b4af-a491-43f5-fac9-23a4d7a10fce', // Frances Allen has deny rights on projects
			'533d2ecb-3ec8-4437-9ca5-0aafc0a895dc', // user kathleen (manager with no group should have access to everything in aucr mode)
			'50e6b4af-aa58-478c-804d-23a4d7a10fce', // Ada lovelace has admin rights on cakephp > cp-project1
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

	public function testAddAcoPermissionsNotExistingModel() {
		$model = 'notExistingModel';
		$id = '50cdab9c-4380-4eb6-b4cc-2f4fd7a10fce';
		$this->setExpectedException('HttpException', "The model " . ucfirst($model) . " is not permissionable");
		// go through the addAcoPermissions because of routes
		$srvResult = json_decode($this->testAction("/permissions/addAcoPermissions/$model/$id.json", array('method' => 'post', 'return' => 'contents')), true);
	}

	public function testAddAcoPermissionsNotPermissionableModel() {
		$model = 'user';
		$id = '50cdab9c-4380-4eb6-b4cc-2f4fd7a10fce';
		$this->setExpectedException('HttpException', "The model " . ucfirst($model) . " is not permissionable");
		// go through the addAcoPermissions because of routes
		$srvResult = json_decode($this->testAction("/permissions/addAcoPermissions/$model/$id.json", array('method' => 'post', 'return' => 'contents')), true);
	}

	public function testAddAcoPermissionsModelIdIsMissing() {
		$model = 'resource';
		$this->setExpectedException('HttpException', "The " . ucfirst($model) . " id is missing");
		// go through the addAcoPermissions because of routes
		$srvResult = json_decode($this->testAction("/permissions/addAcoPermissions/$model.json", array('method' => 'post', 'return' => 'contents')), true);
	}

	public function testAddAcoPermissionsModelIdIsInvalid() {
		$model = 'resource';
		$id = 'badId';
		$this->setExpectedException('HttpException', "The " . ucfirst($model) . " id is invalid");
		$srvResult = json_decode($this->testAction("/permissions/$model/$id.json", array('method' => 'post', 'return' => 'contents')), true);
	}

	public function testAddAcoPermissionsModelInstanceDoesNotExist() {
		$model = 'resource';
		$id = '00000000-0000-0000-0000-000000000000';
		$this->setExpectedException('HttpException', "Your are not allowed to add a permission to the Resource");
		$srvResult = json_decode($this->testAction("/permissions/$model/$id.json", array('method' => 'post', 'return' => 'contents')), true);
	}

	// test view aco permissions action with a not allowed user
	// not allowed => Permission.type < PermissionType::READ (In other words 0)
	public function testAddAcoPermissionsUserNotAllowed() {	
		// try to get permissions on a Resource with a not allowed user
		$categoryName = 'o-project1';
		$category = $this->Category->findByName($categoryName);
		$id = $category['Category']['id'];
		
		// If the user is not allowed to access a category, this category is simply hidden to him
		$this->setExpectedException('HttpException', "Your are not allowed to add a permission to the Category");
		
		// log the user who is not allowed to access the category
		$user = $this->User->findByUsername('edith@passbolt.com');
		$this->User->setActive($user);
		
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
	}
	
	public function testAddAcoPermissionsOnResource() {	
		// log with a user who has right on the unit test sandbox category
		$user = $this->User->findByUsername('kathleen@passbolt.com');
		$this->User->setActive($user);
		
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
				'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4e' // edith@passbolt.com, but we can put any other users
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

		$this->assertEquals(
			Message::SUCCESS,
			$srvResult['header']['status'],
			"/permissions/$model/$id.json : The test should return a success but is returning {$srvResult['header']['status']}"
		);
		// check the permission has well been inserted
		$srvResult = json_decode($this->testAction("/permissions/$model/$id.json", array(
			 'method' => 'get',
			 'return' => 'contents'
		)), true);
		$this->assertEquals(
			$expectedCount,
			count($srvResult['body']),
			"/permissions/$model/$id.json : The test should return {$expectedCount} permissions but is returning " . count($srvResult['body'])
		);
	}

	public function testAddAcoPermissionsOnResourceExistingPermission() {	
		// log with a user who has right on the unit test sandbox category
		$user = $this->User->findByUsername('kathleen@passbolt.com');
		$this->User->setActive($user);
		
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
		
		// insert the new permission
		$srvResult = json_decode($this->testAction("/permissions/$model/$id.json", array(
			 'method' => 'post',
			 'return' => 'contents',
			 'data'=> $data
		)), true);
		$this->assertEquals(Message::SUCCESS, $srvResult['header']['status'], "/permissions/$model/$id.json : The test should return a success but is returning {$srvResult['header']['status']}");


		$this->setExpectedException('HttpException', "A direct permission already exists");
		// try to insert a second time the same permission should return an error
		$srvResult = json_decode($this->testAction("/permissions/$model/$id.json", array(
			 'method' => 'post',
			 'return' => 'contents',
			 'data'=> $data
		)), true);
	}

	public function testSimulateAcoPermissionsOnResource() {
		// log with a user who has right on the unit test sandbox category
		$user = $this->User->findByUsername('kathleen@passbolt.com');
		$this->User->setActive($user);

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

		$realCount = count($srvResult['body']);
		// insert the new permission
		$srvSimulatedResult = json_decode($this->testAction("/permissions/simulate/$model/$id.json", array(
					'method' => 'post',
					'return' => 'contents',
					'data'=> $data
				)), true);
		$simulatedCount = count($srvSimulatedResult['body']);

		$this->assertEquals(
			Message::SUCCESS,
			$srvResult['header']['status'],
			"/permissions/$model/$id.json : The test should return a success but is returning {$srvResult['header']['status']}"
		);

		$this->assertEquals(
			$simulatedCount,
			count($srvResult['body']) + 1,
			"/permissions/$model/$id.json : The test should return {$realCount} permissions but is returning " . count($srvResult['body'])
		);

		// check the permission was not actually inserted (was only a simulation).
		$srvResult = json_decode($this->testAction("/permissions/$model/$id.json", array(
					'method' => 'get',
					'return' => 'contents'
				)), true);
		$this->assertEquals(
			$realCount,
			count($srvResult['body']),
			"/permissions/$model/$id.json : The test should return {$realCount} permissions but is returning " . count($srvResult['body'])
		);
	}

	public function testEditPermissionIdIsMissing() {
		$model = 'resource';
		$this->setExpectedException('HttpException', "The permission id is missing");
		// go through the addAcoPermissions because of routes
		$srvResult = json_decode($this->testAction("/permissions.json", array('method' => 'put', 'return' => 'contents')), true);
	}

	public function testEditPermissionIdIsInvalid() {
		$id = 'badId';
		$this->setExpectedException('HttpException', "The permission id is invalid");
		$srvResult = json_decode($this->testAction("/permissions/$id.json", array('method' => 'put', 'return' => 'contents')), true);
	}

	public function testEditPermissionDoesNotExist() {
		$id = '00000000-0000-0000-0000-000000000000';
		$this->setExpectedException('HttpException', "The permission does not exist");
		$srvResult = json_decode($this->testAction("/permissions/$id.json", array('method' => 'put', 'return' => 'contents')), true);
	}

	// test edit aco permissions action with a not allowed user
	// not allowed => Permission.type < PermissionType::UPDATE
	public function testEditUserNotAllowed() {	
		// try to get permissions on a Resource with a not allowed user
		$id = '50e6b4af-5fa4-493d-bad0-23a4d7a10fce'; // has to exist -> permission relative to human resource on the category administration
		
		// If the user is not allowed to access a category, this category is simply hidden to him
		$this->setExpectedException('HttpException', "You are not allowed to edit this permission");
		
		// log the user who is not allowed to access the category
		$user = $this->User->findByUsername('edith@passbolt.com');
		$this->User->setActive($user);
		
		$postOptions = array(
			'method' => 'put',
			'return' => 'contents',
			'data'=> array(
				'Permission' => array(
					'type' => PermissionType::DENY
				)
			)
		);		
		$srvResult = json_decode($this->testAction("/permissions/$id.json", $postOptions), true);
	}
	
	public function testEdit() {
		$id = '50e6b4af-5fa4-493d-bad0-23a4d7a10fce'; // has to exist -> permission relative to human resource on the category administration
		$postOptions = array(
			'method' => 'put',
			'return' => 'contents',
			'data'=> array(
				'Permission' => array(
					'type' => PermissionType::DENY
				)
			)
		);
		
		// switch the permission of human resource on the category administration to deny
		$srvResult = json_decode($this->testAction("/permissions/$id.json", $postOptions), true);
		$this->assertEquals(Message::SUCCESS, $srvResult['header']['status'], "/permissions/$id.json : The test should return a success but is returning {$srvResult['header']['status']}");
		
		// log the user with a user who belongs to the human resource group
		$user = $this->User->findByUsername('irene@passbolt.com');
		$this->User->setActive($user);
		
		// try to access to the category administration
		$category = $this->Category->findByName('administration');
		$this->assertEmpty($category, "The user " . User::get('name') . " should not be able to see the category administration");
	}

	public function testDeletePermissionIdIsMissing() {
		$model = 'resource';
		$this->setExpectedException('HttpException', "The permission id is missing");
		// go through the addAcoPermissions because of routes
		$srvResult = json_decode($this->testAction("/permissions.json", array('method' => 'delete', 'return' => 'contents')), true);
	}

	public function testDeletePermissionIdIsInvalid() {
		$id = 'badId';
		$this->setExpectedException('HttpException', "The permission id is invalid");
		$srvResult = json_decode($this->testAction("/permissions/$id.json", array('method' => 'delete', 'return' => 'contents')), true);
	}

	public function testDeletePermissionDoesNotExist() {
		$id = '00000000-0000-0000-0000-000000000000';
		$this->setExpectedException('HttpException', "The permission does not exist");
		$srvResult = json_decode($this->testAction("/permissions/$id.json", array('method' => 'delete', 'return' => 'contents')), true);
	}

	// test edit aco permissions action with a not allowed user
	// not allowed => Permission.type < PermissionType::ADMIN
	public function testDeletePermissionNotAllowed() {	
		// try to get permissions on a Resource with a not allowed user
		$id = '50e6b4af-5fa4-493d-bad0-23a4d7a10fce'; // has to exist -> permission relative to human resource on the category administration
		
		// If the user is not allowed to access a category, this category is simply hidden to him
		$this->setExpectedException('HttpException', "You are not allowed to delete this permission");
		
		// log the user who is not allowed to access the category
		$user = $this->User->findByUsername('edith@passbolt.com');
		$this->User->setActive($user);
		
		$postOptions = array(
			'method' => 'delete',
			'return' => 'contents',
			'data'=> array(
				'Permission' => array(
					'type' => PermissionType::DENY
				)
			)
		);		
		$srvResult = json_decode($this->testAction("/permissions/$id.json", $postOptions), true);
	}

	// // test delete aco permissions action user not allowed
	// public function testDeleteUserNotAllowed() {	
		// // try to get permissions on a Resource with a not allowed user
		// $id = '50e6b4af-5fa4-493d-bad0-23a4d7a10fce'; // has to exist -> permission relative to human resource on the category administration
// 		
		// // log the user who is not allowed to access the category
		// $user = $this->User->findByUsername('edith@passbolt.com');
		// $this->User->setActive($user);
// 		
		// $postOptions = array(
			// 'method' => 'delete',
			// 'return' => 'contents'
		// );	
		// $srvResult = json_decode($this->testAction("/permissions/$id.json", $postOptions), true);
		// // message should be : The user is not allowed to delete the permission
		// $this->assertEquals(Message::ERROR, $srvResult['header']['status'], "/permissions/$id.json : The test should return an error but is returning {$srvResult['header']['status']}");
	// }
// 	
	// // test delete
	// public function testDelete() {
		// $id = '50e6b4af-5fa4-493d-bad0-23a4d7a10fce'; // has to exist -> permission relative to human resource on the category administration
		// $postOptions = array(
			// 'method' => 'delete',
			// 'return' => 'contents'
		// );	
// 		
		// // switch the permission of human resource on the category administration to deny
		// $srvResult = json_decode($this->testAction("/permissions/$id.json", $postOptions), true);
		// $this->assertEquals(Message::SUCCESS, $srvResult['header']['status'], "/permissions/$id.json : The test should return a success but is returning {$srvResult['header']['status']}");
// 		
		// // log the user with a user who belongs to the human resource group
		// $user = $this->User->findByUsername('irene@passbolt.com');
		// $this->User->setActive($user);
// 		
		// // try to access to the category administration
		// $category = $this->Category->findByName('administration');
		// $this->assertEmpty($category, "The user " . User::get('name') . " should not be able to see the category administration");
	// }
}
