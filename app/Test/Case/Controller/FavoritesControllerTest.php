<?php
/**
 * Favorites Controller Tests
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @package      app.Test.Case.Controller.FavoritesControllerTest
 * @since        version 2.12.7
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
		'app.category',
		'app.categories_resource',
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
		'app.authenticationBlacklist',
		'app.gpgkey',
		'app.file_storage',
		'core.cakeSession',
		'app.user_agent',
		'app.controller_log'
	);

	public function setUp() {
		parent::setUp();
		$this->User = Common::getModel('User');
		$this->Favorite = Common::getModel('Favorite');
		$this->Resource = Common::getModel('Resource');
		
		// log the user as a manager to be able to access all categories
		$user = $this->User->findById(Common::uuid('user.id.dame'));
		$this->User->setActive($user);
	}

	public function testAddNotCommentable() {
		$model = 'User';
		$this->setExpectedException('HttpException', "The model {$model} is not favoritable");
		$this->testAction("/favorites/$model/badId.json", array('method' => 'post', 'return' => 'contents'));
	}

	public function testAddModelIdIsMissing() {
		// Unable to test missing id param because of route
	}

	public function testAddIdIsNotValid() {
		$model = 'Resource';
		$this->setExpectedException('HttpException', 'The Resource id is invalid');
		$this->testAction("/favorites/$model/badId.json", array('method' => 'post', 'return' => 'contents'));
	}

	public function testAddDoesNotExist() {
		$model = 'resource';
		$id = Common::uuid('not-valid-reference');

		$this->setExpectedException('HttpException', 'The Resource does not exist');
		$this->testAction("/favorites/$model/$id.json", array('method' => 'post', 'return' => 'contents'));
	}

	public function testAdd() {
		$model = 'resource';
		$rsId = Common::uuid('resource.id.salesforce-account');
		$this->testAction("/favorites/$model/{$rsId}.json", array('method' => 'post', 'return' => 'contents'));
	}

	public function testDeleteModelIdIsMissing() {
		// Unable to test missing id param because of route
	}

	public function testDeleteIdIsNotValid() {
		$this->setExpectedException('HttpException', 'The starred id is not valid');
		$this->testAction("/favorites/badId.json", array('method' => 'delete', 'return' => 'contents'));
	}

	public function testDeleteDoesNotExist() {
		$id = Common::uuid('not-valid-reference');

		$this->setExpectedException('HttpException', 'The record is not in your starred item list');
		$this->testAction("/favorites/$id.json", array('method' => 'delete', 'return' => 'contents'));
	}

	public function testDelete() {
		$model = 'resource';
		$rsId = Common::uuid('resource.id.salesforce-account');
		$result = json_decode($this->testAction("/favorites/$model/{$rsId}.json", array('method' => 'post', 'return' => 'contents')), true);
		$this->testAction("/favorites/{$result['body']['Favorite']['id']}.json", array('method' => 'delete', 'return' => 'contents'));
	}
}
