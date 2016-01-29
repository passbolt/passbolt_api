<?php
/**
 * Groups Controller Tests
 *
 * @copyright   Copyright 2012, Passbolt.com
 * @license     http://www.passbolt.com/license
 * @package     app.Test.Case.Controller.UsersControllerTest
 * @since       version 2.12.9
 */
App::uses('AppController', 'Controller');
App::uses('GroupsController', 'Controller');
App::uses('Group', 'Model');
App::uses('User', 'Model');
App::uses('Role', 'Model');
App::uses('CakeSession', 'Model');
App::uses('CakeSession', 'Model/Datasource');

class GroupsControllerTest extends ControllerTestCase {

	public $fixtures = array(
		'app.user',
		'app.role',
		'app.group',
		'app.profile',
		'app.gpgkey',
		'app.groupsUser',
		'app.file_storage',
		'app.authenticationLog',
		'app.authenticationBlacklist',
		'core.cakeSession',
		'app.user_agent',
		'app.controller_log'
	);

	public $user;

	public $session;

	public function setUp() {
		parent::setUp();
		$this->User = Common::getModel('User');
		$u = $this->User->get();
		$this->Group = Common::getModel('Group');
		$this->session = new CakeSession();
		$this->session->init();
		
		// log the user as a manager to be able to access all features
		$user = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($user);
	}

	public function tearDown() {
		parent::tearDown();
	}

	/**
	 * Index entry point
	 */
	public function testIndex() {
		$result = json_decode($this->testAction('/groups.json', array('return' => 'contents', 'method' => 'GET'), true));
		$this->assertEquals($result->header->status, Status::SUCCESS, '/groups.json return something');

		$this->Group->deleteAll(array('id <>' => null));
		$result = json_decode($this->testAction('/groups.json', array('return' => 'contents', 'method' => 'GET'), true));
		$this->assertEquals($result->header->status, Status::NOTICE, '/groups.json return a warning');
	}

	public function testViewGroupIdIsMissing() {
		// Unable to test missing id param because of route
	}

	public function testViewGroupIdNotValid() {
		$this->setExpectedException('HttpException', 'The group id is invalid');
		$this->testAction("/groups/badid.json", array('method' => 'get', 'return' => 'contents'));
	}

	public function testViewGroupDoesNotExist() {
		$this->setExpectedException('HttpException', 'The group does not exist');
		$this->testAction("/groups/4ff6111b-efb8-4a26-aab4-2184cbdd56ca.json", array('method' => 'get', 'return' => 'contents'));
	}

	public function testView() {
		$group = $this->Group->findByName('accounting dpt');
		$result = json_decode($this->testAction("/groups/{$group['Group']['id']}.json",array('return' => 'contents','method' => 'GET'), true));
		$this->assertEquals($result->header->status, Status::SUCCESS,'/groups return something');
	}

	public function testAddNoAllowed() {
		// test with a not allowed user
		$u = $this->User->findByUsername('user@passbolt.com');
		$this->User->setActive($u);

		$this->setExpectedException('HttpException', 'You are not authorized to access that location');
		$result = json_decode($this->testAction('/groups.json', array(
					'data' => array(
						'Group' => array(
							'name' => 'test1'
						),
					),
					'method' => 'post',
					'return' => 'contents'
				)), true
		);
	}

	public function testAdd() {
		// log the user as a manager to be able to access all features
		$user = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($user);
		$result = json_decode($this->testAction('/groups.json', array(
					'data' => array(
						'Group' => array(
							'name' => 'test1'
						),
					),
					'method' => 'post',
					'return' => 'contents'
				)), true
		);
		$this->assertEquals(Status::SUCCESS, $result['header']['status'], "Add : /groups.json : The test should return sucess but is returning " . print_r($result, true));

		// check that User was properly saved
		$group = $this->Group->findByName("test1");
		$this->assertEquals(1, count($group), "Add : /groups.json : The number of groups returned should be 1, but actually is " . count($group));
		$this->assertEquals($group['Group']['name'], $result['body']['Group']['name'], "Add : /groups.json : the name of the group should be test1 but is {$result['body']['Group']['name']}");
	}

	public function testUpdateNoAllowed() {
		// test with a not allowed user
		$u = $this->User->findByUsername('user@passbolt.com');
		$this->User->setActive($u);

		$group = $this->Group->findByName('accounting dpt');
		$id = $group['Group']['id'];

		$this->setExpectedException('HttpException', 'You are not authorized to access that location');
		$result = json_decode($this->testAction("/groups/$id.json", array(
					'data' => array(
						'Group' => array(
							'name' => 'modified name'
						),
					),
					'method' => 'put',
					'return' => 'contents'
				)), true
		);
	}

	public function testUpdateGroupIdIsMissing() {
		$this->setExpectedException('HttpException', 'The group id is missing');
		$this->testAction("/groups.json", array('method' => 'put', 'return' => 'contents'));
	}

	public function testUpdateGroupIdIsNotValid() {
		$this->setExpectedException('HttpException', 'The group id is invalid');
		$this->testAction("/groups/badId.json", array('method' => 'put', 'return' => 'contents'));
	}

	public function testUpdateGroupDoesNotExist() {
		$id = '534a914c-4f63-4e61-ba36-12c1c0a895dc';

		$this->setExpectedException('HttpException', 'The group does not exist');
		$this->testAction("/groups/$id.json", array('method' => 'put', 'return' => 'contents'));
	}

	public function testUpdateNoDataProvided() {
		$model = 'resource';
		$group = $this->Group->findByName('accounting dpt');
		$this->setExpectedException('HttpException', 'No data were provided');
		$this->testAction("/groups/{$group['Group']['id']}.json", array(
			 'method' => 'put',
			 'return' => 'contents'
		));
	}

	public function testUpdate() {
		$group = $this->Group->findByName('accounting dpt');
		$id = $group['Group']['id'];
		$group['Group']['name'] = 'modified name';
		$result = json_decode($this->testAction("/groups/$id.json", array(
			'data' => $group,
			'method' => 'put',
			'return' => 'contents'
		)), true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status'], "Edit : /groups.json : The test should return sucess but is returning " . print_r($result, true));

		// check that User was properly saved
		$gr = $this->Group->findByName("modified name");
		$this->assertEquals(1, count($gr), "Edit : /groups.json : The number of groups returned should be 1, but actually is " . count($group));
		$this->assertEquals($gr['Group']['id'], $group['Group']['id'], "Edit : /groups.json : the id of the retrieved group  should be {$group['Group']['id']} but is {$gr['Group']['id']}");
	}

	public function testDeleteNoAllowed() {
		// test with a not allowed user
		$u = $this->User->findByUsername('user@passbolt.com');
		$this->User->setActive($u);

		$group = $this->Group->findByName('accounting dpt');

		$this->setExpectedException('HttpException', 'You are not authorized to access that location');
		$result = json_decode($this->testAction("/groups/{$group['Group']['id']}.json", array('method' => 'delete','return' => 'contents')), true);
	}

	public function testDeleteGroupIdIsMissing() {
		$this->setExpectedException('HttpException', 'The group id is missing');
		$this->testAction("/groups.json", array('method' => 'delete', 'return' => 'contents'));
	}

	public function testDeleteGroupIdIsNotValid() {
		$this->setExpectedException('HttpException', 'The group id is invalid');
		$this->testAction("/groups/badId.json", array('method' => 'delete', 'return' => 'contents'));
	}

	public function testDeleteGroupDoesNotExist() {
		$id = '534a914c-4f63-4e61-ba36-12c1c0a895dc';

		$this->setExpectedException('HttpException', 'The group does not exist');
		$this->testAction("/groups/$id.json", array('method' => 'delete', 'return' => 'contents'));
	}
	
	public function testDelete() {
		$group = $this->Group->findByName('accounting dpt');
		$id = $group['Group']['id'];

		$result = json_decode($this->testAction("/groups/{$group['Group']['id']}.json", array('method' => 'delete','return' => 'contents')), true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status'], "delete /groups/$id.json : The test should return a success but is returning {$result['header']['status']}");

		$deleted = $this->Group->findByName('accounting dpt');
		$this->assertEquals(1, $deleted['Group']['deleted'], "delete /groups/{$group['Group']['id']}.json : after delete, the value of the field deleted should be 1 but is {$deleted['Group']['deleted']}");
	}
}