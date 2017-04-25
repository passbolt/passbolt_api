<?php
/**
 * Favorites Controller Tests
 *
 * @copyright (c) 2015-2016 Bolt Softwares Pvt Ltd
 *                2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('AppController', 'Controller');
App::uses('FavoritesController', 'Controller');
App::uses('Favorite', 'Model');
App::uses('Resource', 'Model');
App::uses('User', 'Model');
App::uses('Role', 'Model');

// Uses sessions
// App::uses('CakeSession', 'Model/Datasource'); // doesn't work here
if (!class_exists('CakeSession')) {
	require CAKE . 'Model/Datasource/CakeSession.php';
}

class FavoritesControllerTest extends ControllerTestCase {

	public $fixtures = array(
		'app.resource',
		'app.secret',
		'app.favorite',
		'app.user',
		'app.profile',
		'app.group',
		'app.groupsUser',
		'app.role',
		'app.permission',
		'app.permissions_type',
		'app.permission_view',
		'app.gpgkey',
		'app.file_storage',
		'core.cakeSession',
		'app.user_agent',
		'app.controller_log'
	);

/**
 * Set up the tests parameters
 * Set the User, Favorite and Resource models
 * Set Dame as current user
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->User = Common::getModel('User');
		$this->Favorite = Common::getModel('Favorite');
		$this->Resource = Common::getModel('Resource');
		$user = $this->User->findById(Common::uuid('user.id.dame'));
		$this->User->setActive($user);
	}

/**
 * ADD TESTS
 */
/**
 * Test cannot add favorites to a model that does not support this
 */
	public function testAddNotFavoritable() {
		$model = 'User';
		$this->setExpectedException('BadRequestException', 'Favorites are not possible on this type of resource (User).');
		$this->testAction("/favorites/{$model}/badId.json", array('method' => 'post', 'return' => 'contents'));
	}

/**
 * Test cannot add if id is not valid
 */
	public function testAddIdIsNotValid() {
		$model = 'Resource';
		$this->setExpectedException('BadRequestException', 'The resource id is not valid.');
		$this->testAction("/favorites/{$model}/badId.json", array('method' => 'post', 'return' => 'contents'));
	}

/**
 * Test cannot add if id does not exist
 */
	public function testAddDoesNotExist() {
		$model = 'resource';
		$id = Common::uuid('not-valid-reference');
		$this->setExpectedException('NotFoundException', 'The resource does not exist.');
		$this->testAction("/favorites/{$model}/{$id}.json", array('method' => 'post', 'return' => 'contents'));
	}

/**
 * Test add fails if already starred
 */
	public function testAddAlreadyExist() {
		$model = 'resource';
		$rsId = Common::uuid('resource.id.debian');
		$this->testAction("/favorites/{$model}/{$rsId}.json", array('method' => 'post', 'return' => 'contents'));

		$this->setExpectedException('BadRequestException', 'This record is already marked as favorite.');
		$this->testAction("/favorites/{$model}/{$rsId}.json", array('method' => 'post', 'return' => 'contents'));
	}

/**
 * Test add successful if everything goes well
 */
	public function testAddSuccess() {
		$model = 'resource';
		$rsId = Common::uuid('resource.id.debian');
		$result = json_decode($this->testAction("/favorites/{$model}/{$rsId}.json", array('method' => 'post', 'return' => 'contents')));
		$this->assertEquals($result->header->status, Status::SUCCESS);
	}

/**
 * DELETE TESTS
 */
/**
 * Test delete fail if id is not valid uuid
 */
	public function testDeleteIdIsNotValid() {
		$this->setExpectedException('BadRequestException', 'The resource id is not valid.');
		$this->testAction("/favorites/badId.json", array('method' => 'delete', 'return' => 'contents'));
	}

/**
 * Test delete fails if id does not exist
 */
	public function testDeleteIdDoesNotExist() {
		$id = Common::uuid('not-valid-reference');
		$this->setExpectedException('NotFoundException', 'The favorite does not exist.');
		$this->testAction("/favorites/{$id}.json", array('method' => 'delete', 'return' => 'contents'));
	}

/**
 * Test delete fails if the user is the not owner
 */
	public function testDeleteNotOwner() {
		$model = 'resource';

		$this->Favorite->create([
			'user_id' => Common::uuid('user.id.ada'),
			'foreign_id' => Common::uuid('resource.id.debian'),
			'foreign_model' => $model
		]);
		$favorite = $this->Favorite->save();

		$this->setExpectedException('ForbiddenException', 'Your are not allowed to delete this favorite.');
		$this->testAction("/favorites/{$favorite['Favorite']['id']}.json", array('method' => 'delete', 'return' => 'contents'));
	}

/**
 * Test delete is succesfull if all parameters are alright
 */
	public function testDeleteSuccess() {
		$model = 'resource';
		$rsId = Common::uuid('resource.id.debian');
		$result = json_decode(
			$this->testAction("/favorites/{$model}/{$rsId}.json", array('method' => 'post', 'return' => 'contents')), true
		);
		$result = json_decode(
			$this->testAction("/favorites/{$result['body']['Favorite']['id']}.json", array('method' => 'delete', 'return' => 'contents'))
		);
		$this->assertEquals($result->header->status, Status::SUCCESS);
	}
}
