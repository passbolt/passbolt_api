<?php
/**
 * Groups Controller Delete Tests
 *
 * @copyright (c) 2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('AppController', 'Controller');
App::uses('GroupsController', 'Controller');
App::uses('User', 'Model');
App::uses('Group', 'Model');
App::uses('Role', 'Model');
App::uses('CakeSession', 'Model');
App::uses('CakeSession', 'Model/Datasource');
App::uses('CakeSessionFixture', 'Test/Fixture');

/**
 * Class GroupsControllerTest
 */
class GroupsControllerDeleteTest extends ControllerTestCase {

	// Fixtures to be used.
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
		'app.secret',
		'app.favorite',
		'app.permission',
		'app.permissions_type',
		'app.permission_view',
	);


/**
 * Setup.
 */
	public function setUp() {
		parent::setUp();
		$this->User = Common::getModel('User');
		$this->Group = Common::getModel('Group');
		$this->Secret = Common::getModel('Secret');
		$this->Resource = Common::getModel('Resource');
		$this->Permission = Common::getModel('Permission');
		$this->GroupUser = Common::getModel('GroupUser');
		$this->GroupResourcePermission = Common::getModel('GroupResourcePermission');
		$this->session = new CakeSession();
		$this->session->init();
	}

/**
 * Teardown.
 */
	public function tearDown() {
		parent::tearDown();
		// Make sure there is no session active after each test
		$this->User->setInactive();
	}

	/**
	 * Test deleting the group without being logged in.
	 *
	 * Expect a Forbidden exception
	 */
	public function testDeleteNotLoggedIn() {
		// We expect an exception.
		$this->setExpectedException('ForbiddenException', 'You need to login to access this location');
		// test with anonymous user, and expect a forbidden exception.
		$this->testAction('/groups.json', array('return' => 'contents', 'method' => 'DELETE'), true);
	}

	/**
	 * Test deleting with bad uuid.
	 *
	 * Expect a Forbidden exception
	 */
	public function testDeleteBadId() {
		$user = $this->User->findById(Common::uuid('user.id.ada'));
		$this->User->setActive($user);

		// We expect an exception.
		$this->setExpectedException('BadRequestException', 'The group id is not valid.');
		// test with anonymous user, and expect a forbidden exception.
		$this->testAction('/groups/aaa.json', array('return' => 'contents', 'method' => 'DELETE'), true);
	}


	/**
	 * Test deleting a group while not being an administrator.
	 */
	public function testDeleteNotAdmin() {
		$user = $this->User->findById(Common::uuid('user.id.ada'));
		$this->User->setActive($user);

		// the user to update
		$id = Common::uuid('group.id.developer');
		$this->setExpectedException('ForbiddenException', 'You are not authorized to perform this operation on this group.');
		$this->testAction(
			"/groups/$id.json",
			array('method' => 'DELETE', 'return' => 'contents')
		);
	}

/**
 * Test deleting a group who is the sole owner of a resource, and test that an exception is thrown.
 */
	public function testDeleteGroupIsSoleOwnerException() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		// Group to edit.
		$groupId = Common::uuid('group.id.developer');

		// Add a permission that sets the group as the sole owner of a resource.
		// Save a resource.
		$this->Resource->create();
		// Unload permissionable behavior, so it will not create an additional permission while saving.
		$this->Resource->Behaviors->unload('Permissionable');
		$resource = $this->Resource->save([
			'name' => 'resource-test'
		]);

		// Set permission for this resource.
		$this->Permission->create();
		$this->Permission->save([
			'aco' => 'Resource',
			'aco_foreign_key' => $resource['Resource']['id'],
			'aro' => 'Group',
			'aro_foreign_key' => $groupId,
			'type' => PermissionType::OWNER
		]);


		$this->setExpectedException('Exception', 'The group is sole owner of some passwords. Transfer the ownership before deleting.');
		$res = $this->testAction(
			"/groups/$groupId.json",
			[
				'method' => 'DELETE',
				'return' => 'contents'
			]
		);
	}

/**
 * Test delete  in a normal scenario.
 *
 * Assert that the group is soft deleted.
 * Assert that the linked models have their corresponding data deleted.
 */
	public function testDeleteNormal() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		// Group to edit.
		$groupId = Common::uuid('group.id.developer');

		$group = $this->Group->findById($groupId);
		$this->assertNotEmpty($group);

		// test action.
		$res = $this->testAction(
			"/groups/$groupId.json",
			[
				'method' => 'DELETE',
				'return' => 'contents'
			]
		);
		$res = json_decode($res, true);

		$this->assertEquals($res['header']['status'], Status::SUCCESS);
		$this->assertEmpty($res['body']);

		// Assert that the group is deleted in db.
		$group = $this->Group->findById($groupId);
		$this->assertTrue($group['Group']['deleted']);

		$permsAfterDelete = $this->Group->GroupResourcePermission->find('all', ['conditions' => ['aro_foreign_key' => $groupId]]);
		$groupUsersAfterDelete = $this->Group->GroupUser->find('all', ['conditions' => ['group_id' => $groupId]]);
		$this->assertEmpty($permsAfterDelete);
		$this->assertEmpty($groupUsersAfterDelete);
	}

}