<?php
/**
 * Comments Controller Tests
 *
 * @copyright (c) 2015-2016 Bolt Softwares Pvt Ltd
 *            (c) 2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
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
		'app.email_queue',
		'app.user',
		'app.group',
		'app.groups_user',
		'app.role',
		'app.profile',
		'app.file_storage',
		'app.permission',
		'app.permissions_type',
		'app.permission_view',
		'app.gpgkey',
		'core.cakeSession',
		'app.user_agent',
		'app.controller_log'
	);

/**
 * setUp
 * Init the modesl and set the current user to Dame
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->User = ClassRegistry::init('User');
		$this->Comment = ClassRegistry::init('Comment');
		$this->Resource = ClassRegistry::init('Resource');

		$user = $this->User->findById(Common::uuid('user.id.dame'));
		$this->User->setActive($user);
	}

/**
 * TESTS FOR VIEW
 */
/**
 * Test can not view comment on a model that does not implement the commentable behavior
 */
	public function testViewNotCommentable() {
		$model = 'User';
		$this->setExpectedException('BadRequestException', "Comments are not possible on this type of resource ($model)");
		$this->testAction("/comments/$model/badId.json", array('method' => 'get', 'return' => 'contents'));
	}

/**
 * Test that invalid uuid are not accepted.
 */
	public function testViewIdIsNotValid() {
		$model = 'Resource';
		$this->setExpectedException('BadRequestException', 'The resource id is not valid');
		$this->testAction("/comments/$model/badId.json", array('method' => 'get', 'return' => 'contents'));

		$id = '00000000-1111-1111-1111-000000000000';
		$this->setExpectedException('BadRequestException', 'The resource id is not valid');
		$this->testAction("/comments/$model/$id.json", array('method' => 'get', 'return' => 'contents'));
	}

/**
 * Test that cannot view comments for a non existing uuid.
 */
	public function testViewDoesNotExist() {
		$model = 'resource';
		$id = Common::uuid('not-valid-reference');

		$this->setExpectedException('NotFoundException', 'The resource does not exist');
		$this->testAction("/comments/$model/$id.json", array('method' => 'get', 'return' => 'contents'));
	}

/**
 * Test that cannot view comment if no permission
 */
	public function testViewAndPermission() {
		$model = 'resource';
		$id = Common::uuid('resource.id.inkscape');

		// Looking at the matrix of permission frances should not be able to read the resource inkscape
		$user = $this->User->findById(Common::uuid('user.id.frances'));
		$this->User->setActive($user);

		$this->setExpectedException('NotFoundException', 'The resource does not exist');
		$this->testAction("/comments/$model/$id.json", array('method' => 'get', 'return' => 'contents'));
	}

/**
 * Test view comments works if everything is alright
 */
	public function testViewSuccess() {
		$getOptions = array(
			'method' => 'get',
			'return' => 'contents'
		);

		$model = 'resource';
		$rsId = Common::uuid('resource.id.apache');
		$result = json_decode($this->testAction("/comments/$model/$rsId.json", $getOptions), true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status'], "/comments/viewForeignComments/$model/$rsId.json : The test should return a success but is returning {$result['header']['status']}");

		// We expect 1 root comment
		$this->assertEquals(count($result['body']), 1, "We expect 1 root comment");
		// We expect the root comment as an answer
		$this->assertEquals(count($result['body'][0]['children']), 1, "We expect 1 root comment");
	}

/**
 * TEST FOR ADD
 */
/**
 * Test adding a comment fails on a model that does not implement commentable behavior
 */
	public function testAddNotCommentable() {
		$model = 'User';
		$this->setExpectedException('BadRequestException', "Comments are not possible on this type of resource ($model).");
		$this->testAction("/comments/$model/badId.json", array('method' => 'post', 'return' => 'contents'));
	}

/**
 * Test adding fails if comment id is not a valid UUID
 */
	public function testAddIdIsNotValid() {
		$model = 'Resource';
		$this->setExpectedException('BadRequestException', 'The resource id is not valid.');
		$this->testAction("/comments/$model/badId.json", array('method' => 'post', 'return' => 'contents'));
	}

/**
 * Test adding fails if comment id does not exist
 */
	public function testAddDoesNotExist() {
		$model = 'resource';
		$id = Common::uuid('not-valid-reference');

		$this->setExpectedException('NotFoundException', 'The resource does not exist.');
		$this->testAction("/comments/$model/$id.json", array('method' => 'post', 'return' => 'contents'));
	}

/**
 * Test adding fails when no data is provided
 */
	public function testAddNoDataProvided() {
		$model = 'resource';
		$rsId = Common::uuid('resource.id.debian');
		$this->setExpectedException('HttpException', 'No comment data provided.');
		$this->testAction("/comments/$model/$rsId.json", array(
			'method' => 'post',
			'return' => 'contents'
		));
	}

/**
 * Test adding fails when the user does not have permission on the record
 */
	public function testAddAndPermission() {
		$user = $this->User->findById(Common::uuid('user.id.frances'));
		$this->User->setActive($user);

		$model = 'resource';
		$rsId = Common::uuid('resource.id.inkskape');

		$postOptions = array(
			'method' => 'post',
			'return' => 'contents',
			'data' => array('Comment' => array(
				'content' => 'UNIT TEST comment'
			))
		);

		$this->setExpectedException('HttpException', 'The resource does not exist.');
		$this->testAction("/comments/$model/$rsId.json", $postOptions);
	}

/**
 * Test edit fails if data does not validate
 */
	public function testAddValidationFails() {
		$model = 'resource';
		$rsId = Common::uuid('resource.id.debian');
		$postOptions = array(
			'method' => 'post',
			'return' => 'contents',
			'data' => array('Comment' => array(
				'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt 
								ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation 
								ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in 
								reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. 
								Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt 
								mollit anim id est laborum.'
			))
		);

		$this->setExpectedException('BadRequestException', 'Could not validate comment data.');
		$this->testAction("/comments/$model/$rsId.json", $postOptions);
	}

/**
 * Test add everything goes well
 */
	public function testAddSuccess() {
		$model = 'resource';
		$rsId = Common::uuid('resource.id.debian');
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
		$this->assertTrue(!empty($path), "The result should contain the comment {$srvResult['body']['Comment']['id']}, but it is not found.");
	}

/**
 * TEST FOR EDIT
 */
/**
 * Test edit fails with comment id missing
 */
	public function testEditCommentIdIsMissing() {
		$this->setExpectedException('BadRequestException', 'The comment id is missing.');
		$this->testAction("/comments.json", array('method' => 'put', 'return' => 'contents'));
	}

/**
 * Test edit fails with invalid comment id
 */
	public function testEditCommentIdNotValid() {
		$this->setExpectedException('BadRequestException', 'The comment id is not valid.');
		$this->testAction("/comments/badid.json", array('method' => 'put', 'return' => 'contents'));
	}

/**
 * Test edit fails if comment does not exist
 */
	public function testEditCommentIdDoesNotExist() {
		$id = Common::uuid('not-valid-reference');
		$this->setExpectedException('NotFoundException', 'The comment does not exist.');
		$this->testAction("/comments/{$id}.json", array('method' => 'put', 'return' => 'contents'));
	}

/**
 * Test edit fails if user is not the owner of the comment
 */
	public function testEditNotOwner() {
		$putOptions = array(
			'method' => 'put',
			'return' => 'contents',
			'data' => array('Comment' => array(
				'content' => 'this is an edited short comment'
			))
		);
		$id = Common::uuid('comment.id.apache-1'); // has to exist, and user has not to be owner
		$this->setExpectedException('ForbiddenException', 'You are not allowed to edit this comment.');
		$this->testAction("/comments/$id.json", $putOptions);
	}

/**
 * Test edit fails if data does not validate
 */
	public function testEditValidationFails() {
		$commentParentId = Common::uuid('comment.id.apache-1');
		$resourceId = Common::uuid('resource.id.apache');
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

		$putOptions = array(
			'method' => 'put',
			'return' => 'contents',
			'data' => array('Comment' => array(
				'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt 
								ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation 
								ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in 
								reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. 
								Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt 
								mollit anim id est laborum.'
			))
		);

		$this->setExpectedException('BadRequestException', 'Could not validate comment data.');
		$this->testAction("/comments/$commentId.json", $putOptions);
	}

/**
 * Test edit works when everything goes well
 */
	public function testEditSuccess() {
		$commentParentId = Common::uuid('comment.id.apache-1');
		$resourceId = Common::uuid('resource.id.apache');
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

/**
 * TEST FOR DELETE
 */
/**
 * Test delete fails when comment id is missing
 */
	public function testDeleteCommentIdIsMissing() {
		$this->setExpectedException('HttpException', 'The comment id is missing');
		$this->testAction("/comments.json", array('method' => 'delete', 'return' => 'contents'));
	}

/**
 * Test delete fails when comment id is not valid UUID
 */
	public function testDeleteCommentIdNotValid() {
		$this->setExpectedException('HttpException', 'The comment id is not valid');
		$this->testAction("/comments/badid.json", array('method' => 'delete', 'return' => 'contents'));
	}

/**
 * Test delete fails when comment does not exist
 */
	public function testDeleteCommentIdDoesNotExist() {
		$id = Common::uuid('not-valid-reference');
		$this->setExpectedException('HttpException', 'The comment does not exist');
		$this->testAction("/comments/{$id}.json", array('method' => 'delete', 'return' => 'contents'));
	}

/**
 * Test delete fails when user is not the owner
 */
	public function testDeleteNotOwner() {
		$putOptions = array(
			'method' => 'delete',
			'return' => 'contents'
		);

		$id = Common::uuid('comment.id.apache-1'); // has to exist, and user has not to be owner
		$this->setExpectedException('HttpException', 'You are not allowed to delete this comment.');
		$this->testAction("/comments/$id.json", $putOptions);
	}

/**
 * Test delete works when everything goes well
 */
	public function testDeleteSuccess() {
		$commentParentId = Common::uuid('comment.id.apache-1');
		$resourceId = Common::uuid('resource.id.apache');
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
