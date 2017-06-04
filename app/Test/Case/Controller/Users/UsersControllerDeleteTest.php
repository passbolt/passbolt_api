<?php
/**
 * Users Controller Delete Action Tests
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

class UsersControllerDeleteTest extends ControllerTestCase {

	public $fixtures = array(
		'app.groups_user',
		'app.group',
		'app.user',
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
		'app.favorite',
		'app.secret',
		'app.permission',
		'app.permissions_type',
		'app.permission_view',
	);

	public $user;

	public $session;

	public function setUp() {
		parent::setUp();
		$this->User = Common::getModel('User');
		$this->Gpgkey = Common::getModel('Gpgkey');
		$u = $this->User->get();
		$this->session = new CakeSession();
		$this->session->init();
	}

	public function tearDown() {
		parent::tearDown();
		// Make sure there is no session active after each test
		$this->User->setInactive();
	}

/**
 * Test delete for non admin user.
 *
 * @return void
 */
	public function testDeleteNoAllowed() {
		$id = Common::uuid('user.id.user');
		$user = $this->User->findById($id);
		$this->User->setActive($user);

		$this->setExpectedException('ForbiddenException', 'You are not authorized to access that location.');
		json_decode(
			$this->testAction(
				"/users/{$id}.json",
				array(
					'method' => 'delete',
					'return' => 'contents'
				)
			),
			true
		);
	}

/**
 * Test delete with missing user id.
 *
 * @return void
 */
	public function testDeleteUserIdIsMissing() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);
		$this->setExpectedException('BadRequestException', 'The user id is missing.');
		$this->testAction('/users.json', array('return' => 'contents', 'method' => 'delete'), true);
	}

/**
 * Test delete with an invalid user id.
 *
 * @return void
 */
	public function testDeleteUserIdNotValid() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);
		$this->setExpectedException('BadRequestException', 'The user id is not valid.');
		$this->testAction('/users/badId.json', array('return' => 'contents', 'method' => 'delete'), true);
	}

/**
 * Test delete yourself.
 *
 * @return void
 */
	public function testDeleteSelfNotAllowed() {
		$id = Common::uuid('user.id.admin');
		$user = $this->User->findById($id);
		$this->User->setActive($user);
		$this->setExpectedException('BadRequestException', 'You are not allowed to delete yourself');
		$this->testAction("/users/{$id}.json", array('return' => 'contents', 'method' => 'delete'), true);
	}

/**
 * Test delete for a non existing user.
 *
 * @return void
 */
	public function testDeleteUserDoesNotExist() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);
		$id = Common::uuid('not-valid-reference');
		$this->setExpectedException('NotFoundException', 'The user does not exist.');
		$this->testAction(
			"/users/{$id}.json",
			array('return' => 'contents', 'method' => 'delete'),
			true
		);
	}

/**
 * Test delete fails if user is the sole owner of some shared passwords
 *
 * @return void
 */
	public function testDeleteUserIsSoleOwner() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$this->setExpectedException('ValidationException', 'The user is sole owner of some passwords. Transfer the ownership before deleting.');
		$userId = Common::uuid('user.id.ada');
		$this->testAction(
			"/users/{$userId}.json",
			array(
				'method' => 'delete',
				'return' => 'contents'
			)
		);
	}

/**
 * Test dry-run delete in a normal scenario.
 *
 * @return void
 */
	public function testDeleteDryRunSuccess() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		// the user to delete
		$userId = Common::uuid('user.id.user');
		$result = json_decode(
			$this->testAction(
				"/users/{$userId}/dry-run.json",
				array(
					'method' => 'delete',
					'return' => 'contents'
				)
			),
			true
		);
		$this->assertEquals(
			Status::SUCCESS,
			$result['header']['status'],
			"delete /users/{$userId}.json : The test should return a success but is returning {$result['header']['status']}"
		);

		$deleted = $this->User->findById($userId);
		$this->assertEquals(
			0,
			$deleted['User']['deleted'],
			"delete /users/{$userId}.json : after delete, the value of the field deleted should be 1 but is {$deleted['User']['deleted']}"
		);
	}

/**
 * Test delete in a normal scenario.
 *
 * @return void
 */
	public function testDeleteSuccess() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		// the user to delete
		$userId = Common::uuid('user.id.user');
		$result = json_decode(
			$this->testAction(
				"/users/{$userId}.json",
				array(
					'method' => 'delete',
					'return' => 'contents'
				)
			),
			true
		);
		$this->assertEquals(
			Status::SUCCESS,
			$result['header']['status'],
			"delete /users/{$userId}.json : The test should return a success but is returning {$result['header']['status']}"
		);

		$deleted = $this->User->findById($userId);
		$this->assertEquals(
			1,
			$deleted['User']['deleted'],
			"delete /users/{$userId}.json : after delete, the value of the field deleted should be 1 but is {$deleted['User']['deleted']}"
		);
	}

/**
 * Test retrieve see user in index that has been previously soft deleted.
 *
 * @return void
 */
	public function testDeleteAndIndex() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		// the user to delete
		$userId = Common::uuid('user.id.user');
		$result = json_decode(
			$this->testAction(
				"/users/{$userId}.json",
				array(
					'method' => 'delete',
					'return' => 'contents'
				)
			),
			true
		);
		$this->assertEquals(
			Status::SUCCESS,
			$result['header']['status'],
			"delete /users/{$userId}.json : The test should return a success but is returning {$result['header']['status']}"
		);

		// Try to get all users
		$result = json_decode($this->testAction('/users.json', array('return' => 'contents', 'method' => 'GET')), true);
		$usersIds = Set::extract($result['body'], '{n}.User.id');

		// The user that has been deleted is not in the list of user
		$this->assertFalse(in_array($userId, $usersIds));
	}

/**
 * Test can't retrieve user that has been previously soft deleted.
 *
 * @return void
 */
	public function testDeleteAndView() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		// the user to delete
		$userId = Common::uuid('user.id.user');
		$result = json_decode(
			$this->testAction(
				"/users/{$userId}.json",
				array(
					'method' => 'delete',
					'return' => 'contents'
				)
			),
			true
		);
		$this->assertEquals(
			Status::SUCCESS,
			$result['header']['status'],
			"delete /users/{$userId}.json : The test should return a success but is returning {$result['header']['status']}"
		);

		// Try to retrieve the user details
		$this->setExpectedException('HttpException', 'The user does not exist.');
		$result = json_decode(
			$this->testAction(
				"/users/{$userId}.json",
				array('return' => 'contents', 'method' => 'GET'),
				true
			)
		);
	}

/**
 * Test can't update user that has been previously soft deleted.
 *
 * @return void
 */
	public function testDeleteAndUpdate() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		// the user to delete
		$userId = Common::uuid('user.id.user');
		$result = json_decode(
			$this->testAction(
				"/users/{$userId}.json",
				array(
					'method' => 'delete',
					'return' => 'contents'
				)
			),
			true
		);
		$this->assertEquals(
			Status::SUCCESS,
			$result['header']['status'],
			"delete /users/{$userId}.json : The test should return a success but is returning {$result['header']['status']}"
		);

		// Try to update a user that has been deleted
		$this->setExpectedException('HttpException', 'The user does not exist');
		$data['Profile']['first_name'] = 'Update user name';
		$this->testAction(
			"/users/{$userId}.json",
			array(
				'data' => $data,
				'method' => 'put',
				'return' => 'contents'
			)
		);
	}

/**
 * Test can't update avatar user that has been previously soft deleted.
 *
 * @return void
 */
	public function testDeleteAndUpdateAvatar() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		// the user to delete
		$userId = Common::uuid('user.id.user');
		$result = json_decode(
			$this->testAction(
				"/users/{$userId}.json",
				array(
					'method' => 'delete',
					'return' => 'contents'
				)
			),
			true
		);
		$this->assertEquals(
			Status::SUCCESS,
			$result['header']['status'],
			"delete /users/{$userId}.json : The test should return a success but is returning {$result['header']['status']}"
		);

		// Try to update a user that has been deleted
		$this->setExpectedException('HttpException', 'The user does not exist');
		$_FILES['file-0'] = array(
			'file' => array(
				'tmp_name' => APP . 'Test' . DS . 'Data' . DS . 'img' . DS . 'user.png'
			)
		);
		$this->testAction(
			"/users/avatar/{$userId}.json",
			array(
				'data' => [],
				'method' => 'post',
				'return' => 'contents'
			)
		);
	}

/**
 * Test can't delete user that has been previously soft deleted.
 *
 * @return void
 */
	public function testDeleteAndDelete() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		// the user to delete
		$userId = Common::uuid('user.id.user');
		$result = json_decode(
			$this->testAction(
				"/users/{$userId}.json",
				array(
					'method' => 'delete',
					'return' => 'contents'
				)
			),
			true
		);
		$this->assertEquals(
			Status::SUCCESS,
			$result['header']['status'],
			"delete /users/{$userId}.json : The test should return a success but is returning {$result['header']['status']}"
		);

		// Try to delete a user that has been deleted
		$this->setExpectedException('HttpException', 'The user does not exist');
		$this->testAction(
			"/users/{$userId}.json",
			array(
				'method' => 'delete',
				'return' => 'contents'
			)
		);
	}
}
