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
App::uses('User', 'Model');
App::uses('Tag', 'Model');
App::uses('ItemTag', 'Model');
App::uses('CakeSession', 'Model');
App::uses('CakeSession', 'Model/Datasource');

class GroupsControllerTest extends ControllerTestCase {

	public $fixtures = array(
		'app.resource', 'app.category', 'app.categories_resource',
		'app.user', 'app.group', 'app.groups_user', 'app.role',
		'app.permission', 'app.permissions_type', 'app.permission_view',
		'app.authenticationBlacklist', 'app.tag', 'app.itemsTag'
	);

	public $user;

	public $session;

	public function setUp() {
		parent::setUp();
		$this->User = new User();
		$this->Tag = new Tag();
		$this->Tag->useDbConfig = 'test';
		$this->ItemTag = new ItemTag();
		$this->ItemTag->useDbConfig = 'test';
		$this->session = new CakeSession();
		$this->session->init();
	}

	public function tearDown() {
		parent::tearDown();
	}

	public function testAdd() {
		// make sure there is no active session
		$result = $this->testAction('/logout',array('return' => 'contents'), true);

		// test with normal user
		$kk = $this->User->findByUsername('user@passbolt.com');
		$this->User->setActive($kk);
		$id = '509bb871-5168-49d4-a676-fb098cebc04d';
		$result = json_decode($this->testAction("/itemTags/updateBulk/Resource/$id.json", array(
					'data' => array(
						'ItemTag' => array(
							'foreign_model' => 'test1',
							'foreign_id' => '408bb871-5168-49d4-a676-fb098cebc04d',
							'tag_list' => 'tag1, tag2, tag3'
						),
					),
					'method' => 'post',
					'return' => 'contents'
				)), true
		);


		print_r($result);

		/*$this->assertEquals(Message::ERROR, $result['header']['status'], "Add : /groups.json : The test should return an error but is returning " . print_r($result, true));

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
		$this->assertEquals($group['Group']['name'], $result['body']['Group']['name'], "Add : /groups.json : the name of the group should be test1 but is {$result['body']['Group']['name']}");*/
	}

}