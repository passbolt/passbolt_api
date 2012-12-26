<?php
/**
 * Categories Controller Tests
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @package      app.Test.Case.Controller.CategoriesController
 * @since        version 2.12.7
 * @license      http://www.passbolt.com/license
 */
App::uses('AppController', 'Controller');
App::uses('CategoriesController', 'Controller');
App::uses('Category', 'Model');
App::uses('CategoryType', 'Model');
App::uses('User', 'Model');
App::uses('Role', 'Model');

// Uses sessions
// App::uses('CakeSession', 'Model/Datasource'); // doesn't work here
if (!class_exists('CakeSession')) {
	require CAKE . 'Model/Datasource/CakeSession.php';
}

class PermissionsControllerTest extends ControllerTestCase {

//	public $fixtures = array('app.category', 'app.resource', 'app.category_type', 'app.category_resource', 'app.user', 'app.group', 'app.group_user', 'app.role', 'app.permission');

	public function setUp() {
//		$this->Category = new Category();
//		$this->Category->hasOne = array();
//		$this->User = new User();
//		$this->Category->useDbConfig = 'test';
//		$this->User->useDbConfig = 'test';
//		parent::setUp();
	}

	public function testPermissions() {
		
	}
	
}
