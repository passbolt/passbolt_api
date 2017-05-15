<?php
/**
 * Users Controller Edit Avatar Tests
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

class UsersControllerEditAvatarTest extends ControllerTestCase {

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
 * Test update avatar by a non allowed user.
 *
 * @return void
 */
	public function testUpdateAvatarNoAllowed() {
		$user = $this->User->findById(Common::uuid('user.id.dame'));
		$this->User->setActive($user);

		$id = Common::uuid('user.id.user');

		$this->setExpectedException('ForbiddenException', 'You are not authorized to access that location');
		// test with anonymous user
		$_FILES['file-0'] = array(
			'file' => array(
				'tmp_name' => APP . 'Test' . DS . 'Data' . DS . 'img' . DS . 'user.png'
			)
		);
		json_decode(
			$this->testAction(
				"/users/avatar/$id.json",
				array(
					'data' => array(),
					'method' => 'post',
					'return' => 'contents'
				)
			),
			true
		);
	}

/**
 * Test update avatar when user id is missing.
 *
 * @return void
 */
	public function testUpdateAvatarUserIdIsMissing() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);
		$this->setExpectedException('BadRequestException', 'The user id is missing.');
		$this->testAction('/users/avatar.json', array('return' => 'contents', 'method' => 'post'), true);
	}

/**
 * Test update avatar user id not valid.
 *
 * @return void
 */
	public function testUpdateAvatarUserIdNotValid() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);
		$this->setExpectedException('BadRequestException', 'The user id is not valid.');
		$this->testAction('/users/avatar/badId.json', array('return' => 'contents', 'method' => 'post'), true);
	}

/**
 * Test updateAvatar when the user does not exist.
 *
 * @return void
 */
	public function testUpdateAvatarUserDoesNotExist() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$_FILES['file-0'] = array(
			'tmp_name' => APP . 'Test' . DS . 'Data' . DS . 'img' . DS . 'user.png'
		);

		$id = Common::uuid('not-valid-reference');
		$this->setExpectedException('NotFoundException', 'The user does not exist.');
		$this->testAction(
			"/users/avatar/{$id}.json",
			array('return' => 'contents', 'method' => 'post'),
			true
		);
	}

/**
 * Test updateAvatar when no data is provided.
 *
 * @return void
 */
	public function testUpdateAvatarNoDataProvided() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$id = Common::uuid('user.id.user');
		$this->setExpectedException('BadRequestException', 'No avatar data was provided');
		$this->testAction(
			"/users/avatar/$id.json",
			array(
				'method' => 'post',
				'return' => 'contents'
			)
		);
	}

/**
 * Test updateAvatar in normal scenario.
 *
 * @return void
 */
	public function testUpdateAvatar() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		$id = Common::uuid('user.id.user');
		$findConditions = array('User.id' => $id);
		$options = $this->User->getFindOptions('User::view', User::get('Role.name'), $findConditions);
		$user = $this->User->find('first', $options);

		// Get empty image url.
		$defaults = Configure::read('Media.imageDefaults.ProfileAvatar');
		$diff = Set::diff($user['Profile']['Avatar']['url'], $defaults);

		$this->assertEmpty($diff, "The user " . $user['User']['username'] . " should have the default avatar");

		$_FILES['file-0'] = array(
			'tmp_name' => APP . 'Test' . DS . 'Data' . DS . 'img' . DS . 'user.png'
		);
		$result = json_decode(
			$this->testAction(
				"/users/avatar/$id.json",
				array(
					'data' => array(),
					'method' => 'post',
					'return' => 'contents'
				)
			),
			true
		);
		$this->assertEquals(
			Status::SUCCESS,
			$result['header']['status'],
			"Edit : /users.json : The test should return sucess but is returning " . print_r($result, true)
		);

		$findConditions = array('User.id' => $id);
		$options = $this->User->getFindOptions('User::view', User::get('Role.name'), $findConditions);
		$user = $this->User->find('first', $options);

		$this->assertNotEmpty($user['Profile']['Avatar'], "The user " . $user['User']['username'] . " should have an avatar");
	}
}
