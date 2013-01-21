<?php
/**
 * Comments Controller Tests
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @package      app.Test.Case.Controller.CommentsController
 * @license      http://www.passbolt.com/license
 * @since        version 2.13.3
 */
App::uses('AppController', 'Controller');
App::uses('CommentsController', 'Controller');
App::uses('Comment', 'Model');
App::uses('User', 'Model');
App::uses('Role', 'Model');

// Uses sessions
// App::uses('CakeSession', 'Model/Datasource'); // doesn't work here
if (!class_exists('CakeSession')) {
	require CAKE . 'Model/Datasource/CakeSession.php';
}

class CommentsControllerTest extends ControllerTestCase {

	public $fixtures = array('app.comment', 'app.resource', 'app.category', 'app.categories_resource', 'app.user', 'app.group', 'app.groups_user', 'app.role', 'app.permission', 'app.permissions_type', 'app.authenticationBlacklist');

	public function setUp() {
		$this->User = ClassRegistry::init('User');
		$this->Comment = ClassRegistry::init('Comment');
		parent::setUp();
		
		// log the user as a manager to be able to access all categories
		$kk = $this->User->findByUsername('dark.vador@passbolt.com');
		$this->User->setActive($kk);
	}

	// test view foreign comments parameters
	public function testViewForeignCommentsParams() {
		$getOptions = array(
			 'method' => 'get',
			 'return' => 'contents'
		);
		
		// Test model that are not commentable and allowed to be used as foreign model by the comment model	
		// COMMENTABLE MODELS
		$model = 'resource';
		$id = '509bb871-5168-49d4-a676-fb098cebc04d'; // has to exist
		$srvResult = json_decode($this->testAction("/comments/viewForeignComments/$model/$id.json", $getOptions), true);
		$this->assertEquals(Message::SUCCESS, $srvResult['header']['status'], "/comments/viewForeignComments/$model/$id.json : The test should return a success but is returning {$srvResult['header']['status']}");

		// NOT COMMENTABLE MODELS
		$model = 'User';
		$id = '50cdab9c-4380-4eb6-b4cc-2f4fd7a10fce'; // has to exist
		$srvResult = json_decode($this->testAction("/comments/viewForeignComments/$model/$id.json", $getOptions), true);
		$this->assertEquals(Message::ERROR, $srvResult['header']['status'], "/comments/$model/$id.json : The test should return an error but is returning {$srvResult['header']['status']}");

		// NOT EXISTING MODELS => NOT COMMENTABLE MODELS
		$model = 'NotExistingModel';
		$id = '50cdab9c-4380-4eb6-b4cc-2f4fd7a10fce'; // has to exist
		$srvResult = json_decode($this->testAction("/comments/viewForeignComments/$model/$id.json", $getOptions), true);
		$this->assertEquals(Message::ERROR, $srvResult['header']['status'], "/comments/viewForeignComments/$model/$id.json : The test should return an error but is returning {$srvResult['header']['status']}");

		// Test given model instance id
		// NULL ID
		$model = 'resource';
		$id = null;
		$srvResult = json_decode($this->testAction("/comments/viewForeignComments/$model/$id.json", $getOptions), true);
		$this->assertEquals(Message::ERROR, $srvResult['header']['status'], "/comments/viewForeignComments/$model/$id.json : The test should return an error but is returning {$srvResult['header']['status']}");
		
		// NOT EXISTING MODEL INSTANCE
		$model = 'resource';
		$id = '00000000-1111-1111-1111-000000000000';
		$srvResult = json_decode($this->testAction("/comments/viewForeignComments/$model/$id.json", $getOptions), true);
		$this->assertEquals(Message::ERROR, $srvResult['header']['status'], "/comments/viewForeignComments/$model/$id.json : The test should return an error but is returning {$srvResult['header']['status']}");
		
		// INVALID ID FORMAT
		$model = 'resource';
		$id = 'invalid-id-format';
		$srvResult = json_decode($this->testAction("/comments/viewForeignComments/$model/$id.json", $getOptions), true);
		$this->assertEquals(Message::ERROR, $srvResult['header']['status'], "/comments/viewForeignComments/$id.json : The test should return an error but is returning {$srvResult['header']['status']}");
	}

	public function testViewForeignComments() {
		$getOptions = array(
			 'method' => 'get',
			 'return' => 'contents'
		);
		
		// Test model that are not commentable and allowed to be used as foreign model by the comment model	
		// COMMENTABLE MODELS
		$model = 'resource';
		$id = '50d77ff9-c358-4dfb-be34-1b63d7a10fce'; // when written the ressource has two comments
		$srvResult = json_decode($this->testAction("/comments/viewForeignComments/$model/$id.json", $getOptions), true);
		$this->assertEquals(Message::SUCCESS, $srvResult['header']['status'], "/comments/viewForeignComments/$model/$id.json : The test should return a success but is returning {$srvResult['header']['status']}");
		
		$path = $this->Comment->inNestedArray('aaa00000-cccc-11d1-a0c5-080027796c4c', $srvResult['body']);
		$this->assertTrue(!empty($path), 'The result should contain the permission aaa00000-cccc-11d1-a0c5-080027796c4c, but it is not found.');
		
		$path = $this->Comment->inNestedArray('aaa00001-cccc-11d1-a0c5-080027796c4c', $srvResult['body']);
		$this->assertTrue(!empty($path), 'The result should contain the permission aaa00001-cccc-11d1-a0c5-080027796c4c, but it is not found.');
		$this->assertEqual($path[0], 'aaa00000-cccc-11d1-a0c5-080027796c4c', 'The comment aaa00001-cccc-11d1-a0c5-080027796c4c should be a child of the comment aaa00000-cccc-11d1-a0c5-080027796c4c');
	}

	// test add
	public function testAddForeignCommentParams() {
		$postOptions = array(
			 'method' => 'post',
			 'return' => 'contents',
			 'data' => array('Comment' => array(
				'content' => 'this is a short comment'
			))
		);
		
		// Test model that are not commentable and allowed to be used as foreign model by the comment model	
		// COMMENTABLE MODELS
		$model = 'resource';
		$id = '509bb871-5168-49d4-a676-fb098cebc04d'; // has to exist
		$srvResult = json_decode($this->testAction("/comments/addForeignComment/$model/$id.json", $postOptions), true);
		$this->assertEquals(Message::SUCCESS, $srvResult['header']['status'], "/comments/addForeignComment/$model/$id.json : The test should return a success but is returning {$srvResult['header']['status']}");

		// NOT COMMENTABLE MODELS
		$model = 'User';
		$id = '50cdab9c-4380-4eb6-b4cc-2f4fd7a10fce'; // has to exist
		$srvResult = json_decode($this->testAction("/comments/addForeignComment/$model/$id.json", $postOptions), true);
		$this->assertEquals(Message::ERROR, $srvResult['header']['status'], "/comments/addForeignComment/$model/$id.json : The test should return an error but is returning {$srvResult['header']['status']}");

		// NOT EXISTING MODELS => NOT COMMENTABLE MODELS
		$model = 'NotExistingModel';
		$id = '50cdab9c-4380-4eb6-b4cc-2f4fd7a10fce'; // has to exist
		$srvResult = json_decode($this->testAction("/comments/addForeignComment/$model/$id.json", $postOptions), true);
		$this->assertEquals(Message::ERROR, $srvResult['header']['status'], "/comments/addForeignComment/$model/$id.json : The test should return an error but is returning {$srvResult['header']['status']}");

		// Test given model instance id
		// NULL ID
		$model = 'resource';
		$id = null;
		$srvResult = json_decode($this->testAction("/comments/addForeignComment/$model/$id.json", $postOptions), true);
		$this->assertEquals(Message::ERROR, $srvResult['header']['status'], "/comments/addForeignComment/$model/$id.json : The test should return an error but is returning {$srvResult['header']['status']}");
		
		// NOT EXISTING MODEL INSTANCE
		$model = 'resource';
		$id = '00000000-0000-0000-0000-000000000000'; // has to exist
		$srvResult = json_decode($this->testAction("/comments/addForeignComment/$model/$id.json", $postOptions), true);
		$this->assertEquals(Message::ERROR, $srvResult['header']['status'], "/comments/addForeignComment/$model/$id.json : The test should return an error but is returning {$srvResult['header']['status']}");
		
		// INVALID ID FORMAT
		$model = 'resource';
		$id = 'invalid-id-format';
		$srvResult = json_decode($this->testAction("/comments/addForeignComment/$model/$id.json", $postOptions), true);
		$this->assertEquals(Message::ERROR, $srvResult['header']['status'], "/comments/addForeignComment/$model/$id.json : The test should return an error but is returning {$srvResult['header']['status']}");
	}

	// test add
	public function testAddForeignComment() {
		$postOptions = array(
			 'method' => 'post',
			 'return' => 'contents',
			 'data' => array('Comment' => array(
				'content' => 'this is a short comment'
			))
		);
		
		// Test model that are not commentable and allowed to be used as foreign model by the comment model	
		$model = 'resource';
		$id = '50d77ff9-c358-4dfb-be34-1b63d7a10fce'; // when written the ressource has two comments
		$postOptionsCopy = $postOptions;
		$postOptionsCopy['data']['Comment']['parent_id'] = 'aaa00001-cccc-11d1-a0c5-080027796c4c';
		$srvResult = json_decode($this->testAction("/comments/addForeignComment/$model/$id.json", $postOptionsCopy), true);
		$this->assertEquals(Message::SUCCESS, $srvResult['header']['status'], "/comments/addForeignComment/$model/$id.json : The test should return a success but is returning {$srvResult['header']['status']}");
		$this->assertEquals($postOptions['data']['Comment']['content'], $srvResult['body']['Comment']['content'], "/comments/addForeignComment/$model/$id.json : The server should return a comment which has same content than the posted value");
		
		$findData = array(
			'Comment' => array(
				'foreign_id' => $id
			)
		);
		$findOptions = $this->Comment->getFindOptions('viewByForeignModel', User::get('Role.name'), $findData);
		$result = $this->Comment->find('threaded', $findOptions);
		$path = $this->Comment->inNestedArray($srvResult['body']['Comment']['id'], $result);
		$this->assertTrue(!empty($path), "The result should contain the permission {$srvResult['body']['Comment']['id']}, but it is not found.");
		$this->assertEqual($path[1], $postOptionsCopy['data']['Comment']['parent_id'], "The comment {$srvResult['body']['Comment']['id']} should be a child of the comment {$postOptionsCopy['data']['Comment']['parent_id']}");
	}

	// test edit params
	public function testEditParams() {
		$putOptions = array(
			 'method' => 'put',
			 'return' => 'contents',
			 'data' => array('Comment' => array(
				'content' => 'this is an edited short comment'
			))
		);
		
		// Test given model instance id
		// NULL ID
		$id = null;
		$srvResult = json_decode($this->testAction("/comments/edit/$id.json", $putOptions), true);
		$this->assertEquals(Message::ERROR, $srvResult['header']['status'], "/comments/edit/$id.json : The test should return an error but is returning {$srvResult['header']['status']}");
		
		// NOT EXISTING MODEL INSTANCE
		$id = '00000000-0000-0000-0000-000000000000';
		$srvResult = json_decode($this->testAction("/comments/edit/$id.json", $putOptions), true);
		$this->assertEquals(Message::ERROR, $srvResult['header']['status'], "/comments/edit/$id.json : The test should return an error but is returning {$srvResult['header']['status']}");
		
		// INVALID ID FORMAT
		$id = 'invalid-id-format';
		$srvResult = json_decode($this->testAction("/comments/edit/$id.json", $putOptions), true);
		$this->assertEquals(Message::ERROR, $srvResult['header']['status'], "/comments/edit/$id.json : The test should return an error but is returning {$srvResult['header']['status']}");
	}

	// test edit
	public function testEdit() {
		$putOptions = array(
			 'method' => 'put',
			 'return' => 'contents',
			 'data' => array('Comment' => array(
				'content' => 'this is an edited short comment'
			))
		);

		// try to edit an existing comment
		$id = 'aaa00001-cccc-11d1-a0c5-080027796c4c'; // has to exist
		$srvResult = json_decode($this->testAction("/comments/$id.json", $putOptions), true);
		$this->assertEquals(Message::SUCCESS, $srvResult['header']['status'], "/comments/$id.json : The test should return a success but is returning {$srvResult['header']['status']}");
		$this->assertEquals($putOptions['data']['Comment']['content'], $srvResult['body']['Comment']['content'], "/comments/edit/$id.json : The server should return a comment which has same content than the posted value");
	}

	// test delete params
	public function testDeleteParams() {
		$deleteOptions = array(
			 'method' => 'delete',
			 'return' => 'contents'
		);
		
		// Test given model instance id
		// NULL ID
		$id = null;
		$srvResult = json_decode($this->testAction("/comments/delete/$id.json", $deleteOptions), true);
		$this->assertEquals(Message::ERROR, $srvResult['header']['status'], "/comments/delete/$id.json : The test should return an error but is returning {$srvResult['header']['status']}");
		
		// NOT EXISTING MODEL INSTANCE
		$id = '00000000-0000-0000-0000-000000000000';
		$srvResult = json_decode($this->testAction("/comments/delete/$id.json", $deleteOptions), true);
		$this->assertEquals(Message::ERROR, $srvResult['header']['status'], "/comments/delete/$id.json : The test should return an error but is returning {$srvResult['header']['status']}");
		
		// INVALID ID FORMAT
		$id = 'invalid-id-format';
		$srvResult = json_decode($this->testAction("/comments/delete/$id.json", $deleteOptions), true);
		$this->assertEquals(Message::ERROR, $srvResult['header']['status'], "/comments/delete/$id.json : The test should return an error but is returning {$srvResult['header']['status']}");
	}

	// test delete
	public function testDelete() {
		$deleteOptions = array(
			 'method' => 'delete',
			 'return' => 'contents'
		);

		// insert a comment
		$this->Comment->create();
		$this->Comment->set(array(
			'foreign_model' => 'Resource',
			'foreign_id' => '509bb871-5168-49d4-a676-fb098cebc04d',
			'content' => 'tmp comment',
			'parent_id' => 'aaa00001-cccc-11d1-a0c5-080027796c4c'		
		));
		$this->Comment->save();
		$tmpCommentId = $this->Comment->id;

		// try to delete a comment which has children
		$id = 'aaa00001-cccc-11d1-a0c5-080027796c4c'; // has to exist
		$srvResult = json_decode($this->testAction("/comments/$id.json", $deleteOptions), true);
		$this->assertEquals(Message::SUCCESS, $srvResult['header']['status'], "/comments/$id.json : The test should return a success but is returning {$srvResult['header']['status']}");
		
		// Check the comment has well been deleted
		$this->assertFalse($this->Comment->exists($id), "The comment {$id} should not exist");
		// Check if the children has well been deleted
		$this->assertFalse($this->Comment->exists($tmpCommentId), "The child comment should has been delete with its parent");
	}
}
