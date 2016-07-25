<?php
/**
 * Users Controller Tests
 *
 * @copyright   (c) 2015-present Bolt Softwares Pvt Ltd
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

class TagsControllerTest extends ControllerTestCase {

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
		'app.authenticationBlacklist',
		'app.tag',
		'app.itemsTag',
		'core.cakeSession',
		'app.user_agent',
		'app.controller_log'
	);

	public $user;

	public $session;

	public function setUp() {
		parent::setUp();
		$this->User = ClassRegistry::init('User');
		$this->Tag = ClassRegistry::init('Tag');
		$this->ItemTag = ClassRegistry::init('ItemTag');
		$this->Resource = ClassRegistry::init('Resource');

		// log the user as a manager to be able to access all categories
		$user = $this->User->findById(Common::uuid('user.id.dame'));
		$this->User->setActive($user);
	}

	public function tearDown() {
		parent::tearDown();
	}

	public function testUpdateBulkNotTaggable() {
		$model = 'User';
		$this->setExpectedException('HttpException', "The model {$model} is not taggable");
		$this->testAction("/itemTags/updateBulk//$model/badId.json", array('method' => 'post', 'return' => 'contents'));
	}

	public function testUpdateBulkModelIdIsMissing() {
		// Unable to test missing id param because of route
	}

	public function testUpdateBulkIdIsNotValid() {
		$model = 'Resource';
		$this->setExpectedException('HttpException', 'The Resource id is invalid');
		$this->testAction("/itemTags/updateBulk/$model/badId.json", array('method' => 'post', 'return' => 'contents'));

		$id = '00000000-1111-1111-1111-000000000000';
		$this->setExpectedException('HttpException', 'The Resource id is invalid');
		$this->testAction("/itemTags/updateBulk/$model/$id.json", array('method' => 'post', 'return' => 'contents'));
	}

	public function testUpdateBulkDoesNotExist() {
		$model = 'resource';
		$id = Common::uuid('not-valid-reference');

		$this->setExpectedException('HttpException', 'The Resource does not exist');
		$this->testAction("/itemTags/updateBulk//$model/$id.json", array('method' => 'post', 'return' => 'contents'));
	}

	public function testUpdateBulkAndPermission() {
		$rsId = Common::uuid('resource.id.dp1-pwd1');

		// Looking at the matrix of permission Carol should able to READ dp1-pwd1 but not update it
		$user = $this->User->findById(Common::uuid('user.id.carol'));
		$this->User->setActive($user);

		$this->setExpectedException('HttpException', 'You are not authorized to update item tags of this Resource');

		$tagList = array("tag1", "tag2", "tag3");
		$tagListStr = implode(',', $tagList);
		$postOptions = array(
			'data' => array(
				'ItemTag' => array(
					'tag_list' => $tagListStr
				),
			),
			'method' => 'post',
			'return' => 'contents'
		);
		$result = json_decode($this->testAction("/itemTags/updateBulk/Resource/$rsId.json", $postOptions), true);
	}

	public function testUpdateBulk() {
		$tagList = array("tag1", "tag2", "tag3");
		$tagListStr = implode(',', $tagList);
		$rsId = Common::uuid('resource.id.facebook-account');

		$result = json_decode($this->testAction("/itemTags/updateBulk/Resource/$rsId.json", array(
					'data' => array(
						'ItemTag' => array(
							'foreign_model' => 'test1',
							'foreign_id' => $rsId,
							'tag_list' => $tagListStr
						),
					),
					'method' => 'post',
					'return' => 'contents'
				)), true
		);


		$this->assertEquals(Status::SUCCESS, $result['header']['status'], "updateBulk : function should have returned success");

		$tag1 = $this->Tag->findByName('tag1');
		$tagsCount = $this->Tag->find('count');

		$tagList = array("tag1", "tag2", "tag3", "newTag");
		$tagListStr = implode(',', $tagList);

		$result = json_decode($this->testAction("/itemTags/updateBulk/Resource/$rsId.json", array(
					'data' => array(
						'ItemTag' => array(
							'foreign_model' => 'test1',
							'foreign_id' => $rsId,
							'tag_list' => $tagListStr
						),
					),
					'method' => 'post',
					'return' => 'contents'
				)), true
		);


		$tagsCountAfter = $this->Tag->find('count');
		$this->assertEquals($tagsCount + 1, $tagsCountAfter);

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
		$this->assertEquals($oldTag1Id, $newTag1Id, "The old tag id and new tag id should be same");
	}

	public function testViewNotTaggable() {
		$model = 'User';
		$this->setExpectedException('HttpException', "The model {$model} is not taggable");
		$this->testAction("/itemTags/$model/badId.json", array('method' => 'get', 'return' => 'contents'));
	}

	public function testViewModelIdIsMissing() {
		// Unable to test missing id param because of route
	}

	public function testViewIdIsNotValid() {
		$model = 'Resource';
		$this->setExpectedException('HttpException', 'The Resource id is invalid');
		$this->testAction("/itemTags/$model/badId.json", array('method' => 'get', 'return' => 'contents'));

		$id = '00000000-1111-1111-1111-000000000000';
		$this->setExpectedException('HttpException', 'The Resource id is invalid');
		$this->testAction("/itemTags/$model/$id.json", array('method' => 'get', 'return' => 'contents'));
	}

	public function testViewDoesNotExist() {
		$model = 'resource';
		$id = Common::uuid('not-valid-reference');

		$this->setExpectedException('HttpException', 'The Resource does not exist');
		$this->testAction("/itemTags/$model/$id.json", array('method' => 'get', 'return' => 'contents'));
	}

	public function testViewAndPermission() {
		$model = 'resource';
		$resId = Common::uuid('resource.id.cpp1-pwd1');

		// Looking at the matrix of permission Irene should not be able to read the resource cpp1-pwd1
		$user = $this->User->findById(Common::uuid('user.id.irene'));
		$this->User->setActive($user);

		$this->setExpectedException('HttpException', 'The Resource does not exist');
		$this->testAction("/itemTags/$model/$resId.json", array('method' => 'get', 'return' => 'contents'));
	}

	public function testView() {
		$getOptions = array(
			'method' => 'get',
			'return' => 'contents'
		);

		$model = 'resource';
		$resId = Common::uuid('resource.id.facebook-account');

		$result = json_decode($this->testAction("/itemTags/$model/{$resId}.json", $getOptions), true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status'], "/itemTags/$model/{$resId}.json : The test should return a success but is returning {$result['header']['status']}");

		// We expect 2 tags
		$this->assertEquals(count($result['body']), 2, "We expect 2 tags");
	}

	public function testAddNotTaggable() {
		$model = 'User';
		$this->setExpectedException('HttpException', "The model {$model} is not taggable");
		$this->testAction("/itemTags/$model/badId.json", array('method' => 'post', 'return' => 'contents'));
	}

	public function testAddModelIdIsMissing() {
		// Unable to test missing id param because of route
	}

	public function testAddIdIsNotValid() {
		$model = 'Resource';
		$this->setExpectedException('HttpException', 'The Resource id is invalid');
		$this->testAction("/itemTags/$model/badId.json", array('method' => 'post', 'return' => 'contents'));
	}

	public function testAddDoesNotExist() {
		$model = 'resource';
		$id = Common::uuid('not-valid-reference');

		$this->setExpectedException('HttpException', 'The Resource does not exist');
		$this->testAction("/itemTags/$model/$id.json", array('method' => 'post', 'return' => 'contents'));
	}

	public function testAddNoDataProvided() {
		$model = 'resource';
		$resId = Common::uuid('resource.id.salesforce-account');

		$this->setExpectedException('HttpException', 'No data were provided');
		$this->testAction("/itemTags/$model/{$resId}.json", array(
			'method' => 'post',
			'return' => 'contents'
		));
	}

	public function testAdd() {
		$model = 'resource';
		$resId = Common::uuid('resource.id.salesforce-account');
		$postOptions = array(
			'method' => 'post',
			'return' => 'contents',
			'data' => array('Tag' => array(
				'name' => 'UNIT TEST tag',
			))
		);

		// Tag a resource & ensure the server returned it.
		$addResult = json_decode($this->testAction("/itemTags/$model/{$resId}.json", $postOptions), true);
		$this->assertEquals(Status::SUCCESS, $addResult['header']['status'], "/itemTags/addForeignItemTag/$model/{$resId}.json : The test should return a success but is returning {$addResult['header']['status']}");
		$this->assertEquals($postOptions['data']['Tag']['name'], $addResult['body']['Tag']['name'], "/itemTags/addForeignItemTag/$model/{$resId}.json : The server should return an item tag which has same content than the posted value");

		// Ensure the item tag has well been inserted.
		$findData = array(
			'ItemTag' => array(
				'foreign_id' => $resId
			)
		);
		$findOptions = $this->ItemTag->getFindOptions('ItemTag.viewByForeignModel', User::get('Role.name'), $findData);
		$getResult = $this->ItemTag->find('first', $findOptions);
		$this->assertEquals($postOptions['data']['Tag']['name'], $getResult['Tag']['name'], "/itemTags/addForeignItemTag/$model/{$resId}.json : The server should return an item tag which has same content than the posted value");
	}

	public function testDeleteIdIsMissing() {
		$this->setExpectedException('HttpException', 'The item tag id is missing');
		$this->testAction("/itemTags.json", array('method' => 'delete', 'return' => 'contents'));
	}

	public function testDeleteIdNotValid() {
		$this->setExpectedException('HttpException', 'The item tag id is invalid');
		$this->testAction("/itemTags/badid.json", array('method' => 'delete', 'return' => 'contents'));
	}

	public function testDeletIdDoesNotExist() {
		$id = Common::uuid('not-valid-reference');

		$this->setExpectedException('HttpException', 'The item tag does not exist');
		$this->testAction("/itemTags/{$id}.json", array('method' => 'delete', 'return' => 'contents'));
	}

	public function testDeleteAndPermission() {
		// Looking at the matrix of permission Carol should able to READ dp1-pwd1 but not update it
		$user = $this->User->findById(Common::uuid('user.id.carol'));
		$this->User->setActive($user);

		$id = Common::uuid('item_tag.id.drupal-dp1-pwd1');

		$this->setExpectedException('HttpException', 'You are not authorized to delete item tags of this Resource');
		$result = json_decode($this->testAction("/itemTags/$id.json", array(
			'method' => 'Delete',
			'return' => 'contents'
		)), true);
	}

	public function testDelete() {
		$rsId = Common::uuid('resource.id.dp1-pwd1');
		$id = Common::uuid('item_tag.id.drupal-dp1-pwd1');
		$result = json_decode($this->testAction("/itemTags/$id.json", array(
			'method' => 'Delete',
			'return' => 'contents'
		)), true);

		$this->assertEquals(Status::SUCCESS, $result['header']['status'], "/itemTags/{$id}.json : The test should return a success but is returning {$result['header']['status']}");

		// The resource should not be tagged anymore.
		$getOptions = array(
			'method' => 'get',
			'return' => 'contents'
		);

		$model = 'resource';
		$result = json_decode($this->testAction("/itemTags/$model/{$rsId}.json", $getOptions), true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status'], "/itemTags/$model/{$rsId}.json : The test should return a success but is returning {$result['header']['status']}");

		// We expect no tag associated to the resource
		$this->assertEquals(count($result['body']), 0, "We expect 0 tags");
	}
}