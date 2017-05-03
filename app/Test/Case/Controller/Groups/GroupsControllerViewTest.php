<?php
/**
 * Groups Controller View Tests
 *
 * @copyright (c) 2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('AppController', 'Controller');
App::uses('UsersController', 'Controller');
App::uses('User', 'Model');
App::uses('Group', 'Model');
App::uses('Role', 'Model');
App::uses('CakeSession', 'Model');
App::uses('CakeSession', 'Model/Datasource');
App::uses('CakeSessionFixture', 'Test/Fixture');

/**
 * Class GroupsControllerTest
 */
class GroupsControllerViewTest extends ControllerTestCase {

	public $fixtures = array(
		'app.groups_user',
		'app.group',
		'app.user',
		'app.group',
		'app.gpgkey',
		'app.email_queue',
		'app.profile',
		'app.file_storage',
		'app.role',
		'app.authenticationToken',
		'core.cakeSession',
		'app.user_agent',
		'app.controller_log',
		'app.resource',
		'app.permission',
		'app.permissions_type',
		'app.permission_view',
	);

	public function setUp() {
		parent::setUp();
		$this->User = Common::getModel('User');
		$this->Group = Common::getModel('Group');
		$this->session = new CakeSession();
		$this->session->init();

		$user = $this->User->findById(Common::uuid('user.id.dame'));
		$this->User->setActive($user);
	}

	public function tearDown() {
		parent::tearDown();
		// Make sure there is no session active after each test
		$this->User->setInactive();
	}

	public function testViewForbiddenAccess() {
		$this->User->setInactive();
		$this->setExpectedException('ForbiddenException', 'You need to login to access this location');
		$id = Common::uuid('group.id.accounting');
		$this->testAction("/groups/$id.json", array('return' => 'contents', 'method' => 'GET'));
	}

	public function testViewGroupIdIsMissing() {
		// Unable to test missing id param because of route
	}

	public function testViewGroupIdNotValid() {
		// test an error bad id
		$this->setExpectedException('HttpException', 'The group id is not valid.');
		$this->testAction("/groups/badid.json", array('method' => 'get', 'return' => 'contents'));
	}

	public function testViewGroupDoesNotExist() {
		$id = Common::uuid('not-valid-reference');
		// test when a wrong id is provided
		$this->setExpectedException('HttpException', 'The group does not exist.');
		$this->testAction("/groups/{$id}.json", array('method' => 'get','return' => 'contents'));
	}

	public function testViewGroupDeletedGroup() {
		$id = Common::uuid('group.id.ergonom');

		// delete the group
		$this->Group->softDelete($id);

		// View the group
		$this->setExpectedException('HttpException', 'The group does not exist.');
		$this->testAction("/groups/$id.json", array('return' => 'contents', 'method' => 'get'));
	}

	public function testViewGroup(){
		$id = Common::uuid('group.id.ergonom');

		// test if the object returned is a success one
		$result = json_decode($this->testAction("/groups/$id.json", array('return' => 'contents', 'method' => 'get')), true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status']);
		$this->assertEquals('Ergonom', $result['body']['Group']['name']);
		// Test the result contain the expected associated model
		$keys = array_keys($result['body']);
		$this->assertEquals($keys, ['Group', 'GroupUser']);
	}

	public function testViewGroupWithUserContain(){
		$id = Common::uuid('group.id.ergonom');

		// The result should contain associated model
		$data['contain'] = ['user' => 1];

		// test if the object returned is a success one
		$result = json_decode($this->testAction("/groups/$id.json", array('return' => 'contents', 'method' => 'get', 'data' => $data)), true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status']);
		$this->assertEquals('Ergonom', $result['body']['Group']['name']);
		// Test the result contain the expected associated model
		$keys = array_keys($result['body']);
		$this->assertEquals($keys, ['Group', 'GroupUser']);
	}

	public function testViewGroupWithoutUserContain(){
		$id = Common::uuid('group.id.ergonom');

		// The result should contain associated model
		$data['contain'] = ['user' => 0];

		// test if the object returned is a success one
		$result = json_decode($this->testAction("/groups/$id.json", array('return' => 'contents', 'method' => 'get', 'data' => $data)), true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status']);
		$this->assertEquals('Ergonom', $result['body']['Group']['name']);
		// Test the result contain the expected associated model
		$keys = array_keys($result['body']);
		$this->assertEquals($keys, ['Group']);
	}

	public function testViewGroupWithModifierContain(){
		$id = Common::uuid('group.id.ergonom');

		// The result should contain associated model
		$data['contain'] = ['modifier' => 1];

		// test if the object returned is a success one
		$result = json_decode($this->testAction("/groups/$id.json", array('return' => 'contents', 'method' => 'get', 'data' => $data)), true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status']);
		$this->assertEquals('Ergonom', $result['body']['Group']['name']);
		// Test the result contain the expected associated model
		$keys = array_keys($result['body']);
		$this->assertEquals($keys, ['Group', 'Modifier', 'GroupUser']);
	}

}