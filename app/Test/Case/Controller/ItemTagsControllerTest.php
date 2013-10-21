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
App::uses('Resource', 'Model');
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
		$this->User->useDbConfig = 'test';
		$this->Tag = new Tag();
		$this->Tag->useDbConfig = 'test';
		$this->ItemTag = new ItemTag();
		$this->ItemTag->useDbConfig = 'test';
		$this->Resource = new Resource();
		$this->Resource->useDbConfig = 'test';
		$this->session = new CakeSession();
		$this->session->init();
	}

	public function tearDown() {
		parent::tearDown();
	}

	public function testUpdateBulk() {
		// make sure there is no active session
		$result = $this->testAction('/logout',array('return' => 'contents'), true);

		// test with normal user
		$kk = $this->User->findByUsername('admin@passbolt.com');
		$this->User->setActive($kk);

		$items = $this->Resource->find('all');

		$tagList = array("tag1", "tag2", "tag3");
		$tagListStr = implode(',', $tagList);
		$id = '509bb871-5168-49d4-a676-fb098cebc04d';
		$result = json_decode($this->testAction("/itemTags/updateBulk/Resource/$id.json", array(
					'data' => array(
						'ItemTag' => array(
							'foreign_model' => 'test1',
							'foreign_id' => $id,
							'tag_list' => $tagListStr
						),
					),
					'method' => 'post',
					'return' => 'contents'
				)), true
		);


		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "updateBulk : function should have returned success");

		$tag1 = $this->Tag->findByName('tag1');
		$tagsCount = $this->Tag->find('count');

		$tagList = array("tag1", "tag2", "tag3", "newTag");
		$tagListStr = implode(',', $tagList);
		$id = '509bb871-5168-49d4-a676-fb098cebc04d';
		$result = json_decode($this->testAction("/itemTags/updateBulk/Resource/$id.json", array(
					'data' => array(
						'ItemTag' => array(
							'foreign_model' => 'test1',
							'foreign_id' => $id,
							'tag_list' => $tagListStr
						),
					),
					'method' => 'post',
					'return' => 'contents'
				)), true
		);


		$tagsCountAfter = $this->Tag->find('count');
		$this->assertEqual($tagsCount + 1, $tagsCountAfter);

		// Check that id of tag1 didn't change
		$oldTag1Id = $tag1['Tag']['id'];
		$newTag1Id = null;

		$itemTags = $result['body'];
		foreach($itemTags as $it) {
			if($it['Tag']['name'] == 'tag1') {
				$newTag1Id = $it['Tag']['id'];
				break;
			}
		}
		$this->assertEqual($oldTag1Id, $newTag1Id, "The old tag id and new tag id should be same");
	}

}