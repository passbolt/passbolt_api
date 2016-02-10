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

	public $fixtures = array(
		'app.comment',
		'app.resource',
		'app.category',
		'app.categories_resource',
		'app.user',
		'app.group',
		'app.groups_user',
		'app.role',
		'app.profile',
		'app.file_storage',
		'app.permission',
		'app.permissions_type',
		'app.permission_view',
		'app.authenticationBlacklist',
		'app.gpgkey',
		'core.cakeSession',
		'app.user_agent',
		'app.controller_log'
	);

	public function setUp() {
		parent::setUp();
		$this->User = ClassRegistry::init('User');
		$this->Comment = ClassRegistry::init('Comment');
		$this->Resource = ClassRegistry::init('Resource');

		// log the user as a manager to be able to access all categories
		$user = $this->User->findById(common::uuid('user.id.dame'));
		$this->User->setActive($user);
	}

	public function testViewNotCommentable() {
		$model = 'User';
		$this->setExpectedException('HttpException', "The model {$model} is not commentable");
		$this->testAction("/comments/$model/badId.json", array('method' => 'get', 'return' => 'contents'));
	}

	public function testViewModelIdIsMissing() {
		// Unable to test missing id param because of route
	}

	/**
	 * Test that invalid uuid are not accepted.
	 */
	public function testViewIdIsNotValid() {
		$model = 'Resource';
		$this->setExpectedException('HttpException', 'The Resource id is invalid');
		$this->testAction("/comments/$model/badId.json", array('method' => 'get', 'return' => 'contents'));

		$id = '00000000-1111-1111-1111-000000000000';
		$this->setExpectedException('HttpException', 'The Resource id is invalid');
		$this->testAction("/comments/$model/$id.json", array('method' => 'get', 'return' => 'contents'));
	}

	/**
	 * Test a non existing uuid.
	 */
	public function testViewDoesNotExist() {
		$model = 'resource';
		$id = Common::uuid('not-valid-reference');

		$this->setExpectedException('HttpException', 'The Resource does not exist');
		$this->testAction("/comments/$model/$id.json", array('method' => 'get', 'return' => 'contents'));
	}

	public function testViewAndPermission() {
		$model = 'resource';
		$id = Common::uuid('resource.id.cpp1-pwd1');

		// Looking at the matrix of permission Irene should not be able to read the resource cpp1-pwd1
		$user = $this->User->findById(common::uuid('user.id.irene'));
		$this->User->setActive($user);

		$this->setExpectedException('HttpException', 'The Resource does not exist');
		$this->testAction("/comments/$model/$id.json", array('method' => 'get', 'return' => 'contents'));
	}

	// test view foreign comments parameters
	public function testView() {
		$getOptions = array(
			 'method' => 'get',
			 'return' => 'contents'
		);

		$model = 'resource';
		$rsId = Common::uuid('resource.id.salesforce-account');
		$result = json_decode($this->testAction("/comments/$model/{$rsId}.json", $getOptions), true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status'], "/comments/viewForeignComments/$model/{$rsId}.json : The test should return a success but is returning {$result['header']['status']}");

		// We expect 1 root comment
		$this->assertEquals(count($result['body']), 1, "We expect 1 root comment");
		// We expect the root comment as an answer
		$this->assertEquals(count($result['body'][0]['children']), 1, "We expect 1 root comment");
	}

	public function testAddNotCommentable() {
		$model = 'User';
		$this->setExpectedException('HttpException', "The model {$model} is not commentable");
		$this->testAction("/comments/$model/badId.json", array('method' => 'post', 'return' => 'contents'));
	}

	public function testAddModelIdIsMissing() {
		// Unable to test missing id param because of route
	}

	public function testAddIdIsNotValid() {
		$model = 'Resource';
		$this->setExpectedException('HttpException', 'The Resource id is invalid');
		$this->testAction("/comments/$model/badId.json", array('method' => 'post', 'return' => 'contents'));
	}

	public function testAddDoesNotExist() {
		$model = 'resource';
		$id = Common::uuid('not-valid-reference');

		$this->setExpectedException('HttpException', 'The Resource does not exist');
		$this->testAction("/comments/$model/$id.json", array('method' => 'post', 'return' => 'contents'));
	}

	public function testAddNoDataProvided() {
		$model = 'resource';
		$rsId = Common::uuid('resource.id.salesforce-account');
		$this->setExpectedException('HttpException', 'No data were provided');
		$this->testAction("/comments/$model/{$rsId}.json", array(
			 'method' => 'post',
			 'return' => 'contents'
		));
	}

	public function testAddAndPermission() {
		$model = 'resource';
		$rsId = Common::uuid('resource.id.cpp1-pwd1');

		// Looking at the matrix of permission Irene should not be able to read the resource cpp1-pwd1
		$user = $this->User->findById(common::uuid('user.id.irene'));
		$this->User->setActive($user);

		$postOptions = array(
			'method' => 'post',
			'return' => 'contents',
			'data' => array('Comment' => array(
				'content' => 'UNIT TEST comment',
			))
		);

		$this->setExpectedException('HttpException', 'The Resource does not exist');
		$srvResult = json_decode($this->testAction("/comments/$model/$rsId.json", $postOptions), true);
	}

	public function testAdd() {
		$model = 'resource';
		$rsId = Common::uuid('resource.id.salesforce-account');
		$postOptions = array(
			 'method' => 'post',
			 'return' => 'contents',
			 'data' => array('Comment' => array(
				'content' => 'UNIT TEST comment',
			))
		);

		// Add a comment to the resource
		$srvResult = json_decode($this->testAction("/comments/$model/{$rsId}.json", $postOptions), true);
		$this->assertEquals(Status::SUCCESS, $srvResult['header']['status'], "/comments/addForeignComment/$model/{$rsId}.json : The test should return a success but is returning {$srvResult['header']['status']}");
		$this->assertEquals($postOptions['data']['Comment']['content'], $srvResult['body']['Comment']['content'], "/comments/addForeignComment/$model/{$rsId}.json : The server should return a comment which has same content than the posted value");

		$findData = array(
			'Comment' => array(
				'foreign_id' => $rsId
			)
		);
		$findOptions = $this->Comment->getFindOptions('viewByForeignModel', User::get('Role.name'), $findData);
		$result = $this->Comment->find('threaded', $findOptions);
		$path = $this->Comment->inNestedArray($srvResult['body']['Comment']['id'], $result);
		$commentId = $path[0];
		$this->assertTrue(!empty($path), "The result should contain the comment {$srvResult['body']['Comment']['id']}, but it is not found.");

		// Add an answer to the comment we previously inserted
		// @TODO Check why the test is not working it seems that the system still support this feature
		// $postOptions = array(
			 // 'method' => 'post',
			 // 'return' => 'contents',
			 // 'data' => array('Comment' => array(
				// 'content' => 'UNIT TEST children comment',
				// 'Comment' => array(
					// 'parent_id' => $commentId
				// )
			// ))
		// );
		// $srvResult = json_decode($this->testAction("/comments/$model/{$rs['Resource']['id']}.json", $postOptions), true);
		// $this->assertEquals(Status::SUCCESS, $srvResult['header']['status'], "/comments/addForeignComment/$model/{$rs['Resource']['id']}.json : The test should return a success but is returning {$srvResult['header']['status']}");
		// $this->assertEquals($postOptions['data']['Comment']['content'], $srvResult['body']['Comment']['content'], "/comments/addForeignComment/$model/{$rs['Resource']['id']}.json : The server should return a comment which has same content than the posted value");
//
		// $findData = array(
			// 'Comment' => array(
				// 'foreign_id' => $rs['Resource']['id']
			// )
		// );
		// $findOptions = $this->Comment->getFindOptions('viewByForeignModel', User::get('Role.name'), $findData);
		// $result = $this->Comment->find('threaded', $findOptions);
		// $path = $this->Comment->inNestedArray($srvResult['body']['Comment']['id'], $result);
		// $this->assertTrue(!empty($path), "The result should contain the comment {$srvResult['body']['Comment']['id']}, but it is not found.");
		// $this->assertEquals($path[0], $postOptionsCopy['data']['Comment']['parent_id'], "The comment {$srvResult['body']['Comment']['id']} should be a child of the comment {$postOptionsCopy['data']['Comment']['parent_id']}");
	}

	public function testEditCommentIdIsMissing() {
		$this->setExpectedException('HttpException', 'The comment id is missing');
		$this->testAction("/comments.json", array('method' => 'put', 'return' => 'contents'));
	}

	public function testEditCommentIdNotValid() {
		$this->setExpectedException('HttpException', 'The comment id is invalid');
		$this->testAction("/comments/badid.json", array('method' => 'put', 'return' => 'contents'));
	}

	public function testEditCommentIdDoesNotExist() {
		$id = Common::uuid('not-valid-reference');
		$this->setExpectedException('HttpException', 'The comment does not exist');
		$this->testAction("/comments/{$id}.json", array('method' => 'put', 'return' => 'contents'));
	}

	public function testEditNotOwner() {
		$putOptions = array(
			 'method' => 'put',
			 'return' => 'contents',
			 'data' => array('Comment' => array(
				'content' => 'this is an edited short comment'
			))
		);
		$id = Common::uuid('comment.id.salesforce-account-1'); // has to exist, and user has not to be owner
		$this->setExpectedException('HttpException', 'Your are not allowed to edit this comment');
		$this->testAction("/comments/$id.json", $putOptions);
	}

	// test edit
	public function testEdit() {
		$commentParentId = Common::uuid('comment.id.salesforce-account-1');
		$resourceId = Common::uuid('resource.id.facebook-account');
		$commentContent = 'new comment';
		$commentId = null;

		// insert a comment
		$this->Comment->create();
		$this->Comment->set(array(
			'foreign_model' => 'Resource',
			'foreign_id' => $resourceId,
			'content' => $commentContent,
			'parent_id' => $commentParentId
		));
		$comment = $this->Comment->save();
		$commentId = $this->Comment->id;

		// try to edit an existing comment whose the user is not the owner
		$putOptions = array(
			 'method' => 'put',
			 'return' => 'contents',
			 'data' => array('Comment' => array(
				'content' => 'new comment edited'
			))
		);
		$srvResult = json_decode($this->testAction("/comments/$commentId.json", $putOptions), true);
		$this->assertEquals(Status::SUCCESS, $srvResult['header']['status'], "/comments/$commentId.json : The test should return a success but is returning {$srvResult['header']['status']}");
		$this->assertEquals($putOptions['data']['Comment']['content'], $srvResult['body']['Comment']['content'], "/comments/edit/$commentId.json : The server should return a comment which has same content than the posted value");
	}

	public function testDeleteCommentIdIsMissing() {
		$this->setExpectedException('HttpException', 'The comment id is missing');
		$this->testAction("/comments.json", array('method' => 'delete', 'return' => 'contents'));
	}

	public function testDeleteCommentIdNotValid() {
		$this->setExpectedException('HttpException', 'The comment id is invalid');
		$this->testAction("/comments/badid.json", array('method' => 'delete', 'return' => 'contents'));
	}

	public function testDeleteCommentIdDoesNotExist() {
		$id = Common::uuid('not-valid-reference');
		$this->setExpectedException('HttpException', 'The comment does not exist');
		$this->testAction("/comments/{$id}.json", array('method' => 'delete', 'return' => 'contents'));
	}

	public function testDeleteNotOwner() {
		$putOptions = array(
			 'method' => 'delete',
			 'return' => 'contents'
		);

		$id = Common::uuid('comment.id.salesforce-account-1'); // has to exist, and user has not to be owner
		$this->setExpectedException('HttpException', 'Your are not allowed to delete this comment');
		$this->testAction("/comments/$id.json", $putOptions);
	}

	// test delete
	public function testDelete() {
		$commentParentId = Common::uuid('comment.id.salesforce-account-1');
		$resourceId = Common::uuid('resource.id.facebook-account');
		$commentContent = 'new comment';
		$commentId = null;

		// insert a comment
		$this->Comment->create();
		$this->Comment->set(array(
			'foreign_model' => 'Resource',
			'foreign_id' => $resourceId,
			'content' => $commentContent,
			'parent_id' => $commentParentId
		));
		$this->Comment->save();
		$commentId = $this->Comment->id;

		// insert a child to the just inserted comment
		$this->Comment->create();
		$this->Comment->set(array(
			'foreign_model' => 'Resource',
			'foreign_id' => $resourceId,
			'content' => $commentContent,
			'parent_id' => $commentId
		));
		$this->Comment->save();
		$childCommentId = $this->Comment->id;

		// try to delete a comment which has children
		$deleteOptions = array(
			 'method' => 'delete',
			 'return' => 'contents'
		);
		$srvResult = json_decode($this->testAction("/comments/$commentId.json", $deleteOptions), true);
		$this->assertEquals(Status::SUCCESS, $srvResult['header']['status'], "/comments/$commentId.json : The test should return a success but is returning {$srvResult['header']['status']}");

		// Check the comment has well been deleted
		$this->assertFalse($this->Comment->exists($commentId), "The comment {$commentId} should not exist");
		// Check if the children has well been deleted
		$this->assertFalse($this->Comment->exists($childCommentId), "The child comment should has been delete with its parent");
	}
}
