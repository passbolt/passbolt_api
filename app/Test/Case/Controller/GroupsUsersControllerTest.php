<?php
/**
 * GroupsUsersController Tests
 *
 * @copyright    Copyright 2014, Passbolt.com
 * @package      app.Test.Case.Controller.GroupsUsersControllerTest
 * @since        version 2.12.7
 * @license      http://www.passbolt.com/license
 */
App::uses('AppController', 'Controller');
App::uses('GroupsUsersController', 'Controller');
App::uses('GroupUser', 'Model');
App::uses('Category', 'Model');
App::uses('Resource', 'Model');
App::uses('User', 'Model');
App::uses('Group', 'Model');
App::uses('Role', 'Model');

if (!class_exists('CakeSession')) {
	require CAKE . 'Model/Datasource/CakeSession.php';
}

class GroupsUsersControllerTest extends ControllerTestCase {

	public $fixtures = array(
		'app.category',
		'app.resource',
		'app.categories_resource',
		'app.group',
		'app.user',
		'app.groups_user',
		'app.role',
		'app.permission',
		'app.permissions_type',
		'app.permission_view',
		'app.authenticationBlacklist',
		'core.cakeSession',
	);

	public $uses = array(
		'Group'
	);

	public function setUp() {
		$this->User = ClassRegistry::init('User');
		$this->Group = ClassRegistry::init('Group');
		$this->GroupUser = ClassRegistry::init('GroupUser');
		parent::setUp();

		// log the user as a manager to be able to access all categories
		$user = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($user);
	}

	public function testViewGroUsrIdIsMissing() {
		$this->setExpectedException('HttpException', 'The groupUser id is missing');
		$this->testAction("/groupsUsers.json", array('method' => 'get', 'return' => 'contents'));
	}

	public function testViewGroUsrIdNotValid() {
		$this->setExpectedException('HttpException', 'The groupUser id is invalid');
		$this->testAction("/groupsUsers/badid.json", array('method' => 'get', 'return' => 'contents'));
	}

	public function testViewGroUsrDoesNotExist() {
		$this->setExpectedException('HttpException', 'The groupUser does not exist');
		$this->testAction("/groupsUsers/4ff6111b-efb8-4a26-aab4-2184cbdd56ca.json", array('method' => 'get', 'return' => 'contents'));
	}

	public function testView() {
		$id = '53865f1f-230c-448b-b911-2173c0a895dc';

		// test when no parameters are provided
		$result = json_decode($this->testAction("/groupsUsers/$id.json", array('method' => 'get', 'return' => 'contents')), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "/groupsUsers.json : The test should return success but is returning {$result['header']['status']}");
	}

	public function testAddNoDataProvided() {
		$this->setExpectedException('HttpException', 'No data were provided');
		$this->testAction('/categoriesResources.json', array(
				'method' => 'post',
				'return' => 'contents'
			));
	}

	/*public function testWrongDataProvided() {
		$data = array(
			'GroupUser' => array(
				'group_id' => '4ff6111b-efb8-4a26-aab4-2184cbdd56ca',
				'user_id' => '4ff6111b-efb8-4a26-aab4-2184cbdd56ca',
			)
		);
		$this->setExpectedException('HttpException', 'Could not validate data');
		$this->testAction('/groupsUsers.json', array(
				'data' => $data,
				'method' => 'post',
				'return' => 'contents'
			));
	}*/

	public function testAdd() {
		$gro = $this->Group->findByName('developers');
		$usr = $this->User->findByUsername('grace@passbolt.com');
		$data = array(
			'GroupUser' => array(
				'group_id' => $gro['Group']['id'],
				'user_id' => $usr['User']['id']
			)
		);

		$result = json_decode($this->testAction('/groupsUsers.json', array(
					'data' => $data,
					'method' => 'post',
					'return' => 'contents'
				)), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "Add : /groupsUsers.json : The test should return sucess but is returning " . print_r($result, true));

		// check that Categories were properly saved
		$gu = $this->GroupUser->find('all', array(
				'conditions' => array(
					'group_id' => $gro['Group']['id'],
					'user_id' => $usr['User']['id']
				)
			));
		$this->assertEquals(1, count($gu), "Add : /groupsUsers.json : The number of groupsUsers returned should be 1, but actually is " . count($gu));
	}

	public function testDeleteCatResIdIsMissing() {
		$this->setExpectedException('HttpException', 'The groupUser id is missing');
		$this->testAction("/groupsUsers.json", array('method' => 'delete', 'return' => 'contents'));
	}

	public function testDeleteCatResIdNotValid() {
		$this->setExpectedException('HttpException', 'The groupUser id is invalid');
		$this->testAction("/groupsUsers/badid.json", array('method' => 'delete', 'return' => 'contents'));
	}

	public function testDeleteCatResDoesNotExist() {
		$this->setExpectedException('HttpException', 'The groupUser does not exist');
		$this->testAction("/groupsUsers/4ff6111b-efb8-4a26-aab4-2184cbdd56ca.json", array('method' => 'delete', 'return' => 'contents'));
	}

	public function testDelete() {
		$id = '53865f1f-311c-4535-8a72-2173c0a895dc';
		$result = json_decode($this->testAction("/groupsUsers/$id.json", array(
					'method' => 'delete',
					'return' => 'contents'
				)), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "delete /groupsUsers/$id.json : The test should return a success but is returning {$result['header']['status']}");

		$found = $this->GroupUser->findById($id);
		$this->assertEquals(
			count($found), 0,
			"delete /groupsUsers/$id.json : This test should have fetched 0 elements from the database but it is not the case. Is the element properly deleted ?"
		);
	}
}
