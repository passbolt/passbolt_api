<?php
/**
 * Users Controller Tests
 *
 * @copyright   Copyright 2012, Passbolt.com
 * @license     http://www.passbolt.com/license
 * @package     app.Test.Case.Controller.UsersControllerTest
 * @since       version 2.12.9
 */
App::uses('AppController', 'Controller');
App::uses('UsersController', 'Controller');
App::uses('User', 'Model');
App::uses('Group', 'Model');
App::uses('Role', 'Model');
App::uses('CakeSession', 'Model');
App::uses('CakeSession', 'Model/Datasource');

class GroupsControllerTest extends ControllerTestCase {

	public $fixtures = array('app.user', 'app.role', 'app.group', 'app.authenticationLog', 'app.authenticationBlacklist');

	public $user;

	public $session;

	public function setUp() {
		parent::setUp();
		$this->User = new User();
		$this->Group = new Group();
		$this->Group->useDbConfig = 'test';
		$this->session = new CakeSession();
		$this->session->init();
	}

	public function tearDown() {
		parent::tearDown();
	}

	/**
	 * Index entry point
	 */
	public function testIndex() {
		// make sure there is no active session
		$result = $this->testAction('/logout', array('return' => 'contents'), true);

		// test with anonymous user
		$result = json_decode($this->testAction('/groups.json', array('return' => 'contents', 'method' => 'GET'), true));
		$this->assertEqual(
			$result->header->status,
			Message::ERROR,
			'/groups.json should not be accessible without being logged in'
		);

		// test with normal user
		$kk = $this->User->findByUsername('user@passbolt.com');
		$this->User->setActive($kk);

		$result = json_decode($this->testAction('/groups.json', array('return' => 'contents', 'method' => 'GET'), true));
		$this->assertEqual($result->header->status, Message::SUCCESS, '/groups.json return something');

		$this->Group->deleteAll(array('id <>' => null));
		$result = json_decode($this->testAction('/groups.json', array('return' => 'contents', 'method' => 'GET'), true));
		$this->assertEqual($result->header->status, Message::NOTICE, '/groups.json return a warning');
	}

	public function testView() {
		// make sure there is no active session
		$result = $this->testAction('/logout',array('return' => 'contents'), true);

		$id = "20ce3d3a-0468-433b-b59f-3053d7a10fce";

		// test with anonymous user
		$result = json_decode($this->testAction("/groups/$id.json",array('return' => 'contents','method' => 'GET'), true));
		$this->assertEqual($result->header->status, Message::ERROR, "/groups/$id.json should not be accessible without being logged in");

		// test with normal user
		$kk = $this->User->findByUsername('user@passbolt.com');
		$this->User->setActive($kk);

		$result = json_decode($this->testAction('/groups/view/0000-0000-0000-000000000000.json',array('return' => 'contents','method' => 'GET'), true));
		$this->assertEqual($result->header->status, Message::ERROR,'/groups/view with wrong UUID format should return an error');

		$result = json_decode($this->testAction('/groups/bbd56042-0000-0000-0000-000000000000.json',array('return' => 'contents','method' => 'GET'), true));
		$this->assertEqual($result->header->status, Message::ERROR,'/group/view with group that does not exit should return an error');

		$result = json_decode($this->testAction("/groups/$id.json",array('return' => 'contents','method' => 'GET'), true));
		$this->assertEqual($result->header->status, Message::SUCCESS,'/groups return something');
	}

	public function testAdd() {
		// make sure there is no active session
		$result = $this->testAction('/logout',array('return' => 'contents'), true);

		// test with normal user
		$kk = $this->User->findByUsername('user@passbolt.com');
		$this->User->setActive($kk);

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

		$this->assertEquals(Message::ERROR, $result['header']['status'], "Add : /groups.json : The test should return an error but is returning " . print_r($result, true));

		$kk = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($kk);
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
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "Add : /groups.json : The test should return sucess but is returning " . print_r($result, true));

		// check that User was properly saved
		$group = $this->Group->findByName("test1");
		$this->assertEquals(1, count($group), "Add : /groups.json : The number of groups returned should be 1, but actually is " . count($group));
		$this->assertEquals($group['Group']['name'], $result['body']['Group']['name'], "Add : /groups.json : the name of the group should be test1 but is {$result['body']['Group']['name']}");
	}

	public function testUpdate() {
		// test with normal user
		$kk = $this->User->findByUsername('user@passbolt.com');
		$this->User->setActive($kk);

		$group = $this->Group->findByName('accounting dpt');

		$id = $group['Group']['id'];

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

		$this->assertEquals(Message::ERROR, $result['header']['status'], "Edit : /groups.json : The test should return an error but is returning " . print_r($result, true));

		$ad = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($ad);

		$group['Group']['name'] = 'modified name';
		$result = json_decode($this->testAction("/groups/$id.json", array(
					'data' => $group,
					'method' => 'put',
					'return' => 'contents'
				)), true
		);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "Edit : /groups.json : The test should return sucess but is returning " . print_r($result, true));

		// check that User was properly saved
		$gr = $this->Group->findByName("modified name");
		$this->assertEquals(1, count($gr), "Edit : /groups.json : The number of groups returned should be 1, but actually is " . count($group));
		$this->assertEquals($gr['Group']['id'], $group['Group']['id'], "Edit : /groups.json : the id of the retrieved group  should be {$group['Group']['id']} but is {$gr['Group']['id']}");
	}


	public function testDelete() {
		$u = $this->User->findByUsername('User@passbolt.com');
		$this->User->setActive($u);

		$group = $this->Group->findByName('accounting dpt');

		$id = $group['Group']['id'];
		$result = json_decode($this->testAction("/groups/$id.json", array(
					'method' => 'delete',
					'return' => 'contents'
				)), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "delete /groups/$id.json : The test should return a success but is returning {$result['header']['status']}");

		$id = 1;
		$result = json_decode($this->testAction("/groups/$id.json", array(
					'method' => 'delete',
					'return' => 'contents'
				)), true);
		$this->assertEquals(Message::ERROR, $result['header']['status'], "delete /groups/$id.json : The test should return a error but is returning {$result['header']['status']}");


		$adm = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($adm);
		$result = json_decode($this->testAction("/groups/{$group['Group']['id']}.json", array(
					'method' => 'delete',
					'return' => 'contents'
				)), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "delete /groups/$id.json : The test should return a success but is returning {$result['header']['status']}");

		$deleted = $this->Group->findByName('accounting dpt');
		$this->assertEqual(1, $deleted['Group']['deleted'], "delete /groups/{$group['Group']['id']}.json : after delete, the value of the field deleted should be 1 but is {$deleted['Group']['deleted']}");

		$result = json_decode($this->testAction('/groups/' . $deleted['Group']['deleted'] . '.json',array('return' => 'contents','method' => 'GET'), true));
		$this->assertEqual($result->header->status, Message::ERROR,'/groups/delete after delete, no result should be returned');
	}
}