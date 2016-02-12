<?php
/**
 * CategoryResourcesController Tests
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @package      app.Test.Case.Controller.CategoryResourcesController
 * @since        version 2.12.7
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('AppController', 'Controller');
App::uses('CategoriesResourcesController', 'Controller');
App::uses('CategoryResource', 'Model');
App::uses('Category', 'Model');
App::uses('Resource', 'Model');
App::uses('User', 'Model');
App::uses('Role', 'Model');

if (!class_exists('CakeSession')) {
	require CAKE . 'Model/Datasource/CakeSession.php';
}

class CategoriesResourcesControllerTest extends ControllerTestCase {

	public $fixtures = array(
		'app.category',
		'app.resource',
		'app.categories_resource',
		'app.group',
		'app.user',
		'app.profile',
		'app.gpgkey',
		'app.groups_user',
		'app.role',
		'app.permission',
		'app.permissions_type',
		'app.permission_view',
		'app.authenticationBlacklist',
		'app.file_storage',
		'core.cakeSession',
		'app.user_agent',
		'app.controller_log'
	);

	public function setUp() {
		parent::setUp();
		$this->User = ClassRegistry::init('User');
		$this->Resource = ClassRegistry::init('Resource');
		$this->Category = ClassRegistry::init('Category');
		$this->CategoryResource = ClassRegistry::init('CategoryResource');

		// log the user as a manager to be able to access all categories
		$user = $this->User->findById(common::uuid('user.id.dame'));
		$this->User->setActive($user);
	}

	public function testViewCatResIdIsMissing() {
		$this->setExpectedException('HttpException', 'The categoryResource id is missing');
		$this->testAction("/categoriesResources.json", array('method' => 'get', 'return' => 'contents'));
	}

	public function testViewCatResIdNotValid() {
		$this->setExpectedException('HttpException', 'The categoryResource id is invalid');
		$this->testAction("/categoriesResources/badid.json", array('method' => 'get', 'return' => 'contents'));
	}

	public function testViewCatResDoesNotExist() {
		$id = Common::uuid('not-valid-reference');
		$this->setExpectedException('HttpException', 'The categoryResource does not exist');
		$this->testAction("/categoriesResources/{$id}.json", array('method' => 'get', 'return' => 'contents'));
	}

	public function testView() {
		$id = Common::uuid('category_resource.id.cpp1-project1_cpp1-pwd2');
		$result = json_decode($this->testAction("/categoriesResources/$id.json", array('method' => 'get', 'return' => 'contents')), true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status'], "/categoriesResources.json : The test should return success but is returning {$result['header']['status']}");
	}

	public function testAddNoDataProvided() {
		$this->setExpectedException('HttpException', 'No data were provided');
		$this->testAction('/categoriesResources.json', array(
			 'method' => 'post',
			 'return' => 'contents'
		));
	}

	public function testAddWrongDataProvided() {
		$id1 = Common::uuid('not-valid-reference');
		$id2 = Common::uuid('not-valid-reference');
		$data = array(
			'CategoryResource' => array(
				'category_id' => $id1,
				'resource_id' => $id2,
			)
		);
		$this->setExpectedException('HttpException', 'Could not validate data');
		$this->testAction('/categoriesResources.json', array(
			'data' => $data,
			'method' => 'post',
			'return' => 'contents'
		));
	}

	public function testAdd() {
		$catId = Common::uuid('category.id.bolt');
		$resId = Common::uuid('resource.id.facebook-account');
		$data = array(
			'CategoryResource' => array(
				'category_id' => $catId,
				'resource_id' => $resId
			)
		);

		$result = json_decode($this->testAction('/categoriesResources.json', array(
			'data' => $data,
			 'method' => 'post',
			 'return' => 'contents'
		)), true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status'], "Add : /categoriesResources.json : The test should return sucess but is returning " . print_r($result, true));

		// check that Categories were properly saved
		$cr = $this->CategoryResource->find('all', array(
			'conditions' => array(
				'category_id' => $catId,
				'resource_id' => $resId
			)
		));
		$this->assertEquals(1, count($cr), "Add : /categoriesResources.json : The number of categoriesResources returned should be 1, but actually is " . count($cr));
	}

	public function testDeleteCatResIdIsMissing() {
		$this->setExpectedException('HttpException', 'The categoryResource id is missing');
		$this->testAction("/categoriesResources.json", array('method' => 'delete', 'return' => 'contents'));
	}

	public function testDeleteCatResIdNotValid() {
		$this->setExpectedException('HttpException', 'The categoryResource id is invalid');
		$this->testAction("/categoriesResources/badid.json", array('method' => 'delete', 'return' => 'contents'));
	}

	public function testDeleteCatResDoesNotExist() {
		$id = Common::uuid('not-valid-reference');
		$this->setExpectedException('HttpException', 'The categoryResource does not exist');
		$this->testAction("/categoriesResources/{$id}.json", array('method' => 'delete', 'return' => 'contents'));
	}

	public function testDelete() {
		$id = Common::uuid('category_resource.id.cpp1-project1_cpp1-pwd2');

		$result = json_decode($this->testAction("/categoriesResources/$id.json", array(
			 'method' => 'delete',
			 'return' => 'contents'
		)), true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status'], "delete /categoriesResources/$id.json : The test should return a success but is returning {$result['header']['status']}");

		$found = $this->CategoryResource->findById($id);
		$this->assertEquals(
			count($found), 0,
			"delete /categoriesResources/$id.json : This test should have fetched 0 elements from the database but it is not the case. Is the element properly deleted ?"
		);
	}
}
