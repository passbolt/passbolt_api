<?php
/**
 * Setup Controller Tests
 *
 * @copyright (c) 2015-2016 Bolt Softwares Pvt Ltd
 *                2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('AppController', 'Controller');
App::uses('SetupController', 'Controller');
App::uses('User', 'Model');
App::uses('Group', 'Model');
App::uses('Role', 'Model');
App::uses('CakeSession', 'Model');
App::uses('CakeSession', 'Model/Datasource');
App::uses('CakeSessionFixture', 'Test/Fixture');

class SetupControllerTest extends ControllerTestCase {

	public $fixtures = [
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
	];

	public function setUp() {
		parent::setUp();
		$this->User = Common::getModel('User');
		$this->Gpgkey = Common::getModel('Gpgkey');
		$this->session = new CakeSession();
		$this->session->init();
	}

	public function tearDown() {
		parent::tearDown();
		// Make sure there is no session active after each test
		$this->User->setInactive();
	}

/**
 * Start a recovery and return user and token.
 */
	private function __startRecovery($username) {
		$u = $this->User->findByUsername($username);
		$token = $this->User->AuthenticationToken->generate($u['User']['id']);
		return [
			'User' => $u['User'],
			'AuthenticationToken' => $token['AuthenticationToken']
		];
	}

/**
 * Test install with no user id
 */
	public function testInstallNoUserId() {
		$this->setExpectedException('BadRequestException', 'The user id is missing.');
		$this->testAction('/setup/install.json', array('return' => 'contents', 'method' => 'get'));
	}

/**
 * Test install with invalid user id
 */
	public function testInstallInvalidUserId() {
		$this->setExpectedException('BadRequestException', 'The user id is not valid.');
		$this->testAction('/setup/install/zaa00003-c5cd-11e1-a0c5-080027aa6c4c', array('return' => 'contents', 'method' => 'get'));
	}

/**
 * Test install with no token
 */
	public function testInstallNoToken() {
		$this->setExpectedException('BadRequestException', 'The authentication token is missing.');
		$this->testAction('/setup/install/' . Common::uuid() . DS , array('return' => 'contents', 'method' => 'get'));
	}

/**
 * Test install with invalid token
 */
	public function testInstallInvalidToken() {
		$this->setExpectedException('BadRequestException', 'The authentication token is not valid.');
		$this->testAction('/setup/install/' . Common::uuid() . DS .  Common::uuid(), array('return' => 'contents', 'method' => 'get'));
	}

/**
 * Test install with invalid user id
 */
	public function testInstallInvalidUserGoodToken() {
		$this->setExpectedException('NotFoundException');
		$recovery = $this->__startRecovery('ada@passbolt.com');
		$this->testAction('/setup/install/' . Common::uuid() . DS .  $recovery['AuthenticationToken']['token'], array('return' => 'contents', 'method' => 'get'));
	}

/**
 * Test install with invalid token but good user id
 */
	public function testInstallGooddUserInvalidToken() {
		$this->setExpectedException('BadRequestException');
		$this->testAction('/setup/install/' . Common::uuid('user.id.ada') . DS .  Common::uuid(), array('return' => 'contents', 'method' => 'get'));
	}

/**
 * Test install with an already active user
 */
	public function testInstallActiveUser() {
		$recovery = $this->__startRecovery('ada@passbolt.com');
		$userId = Common::uuid('user.id.ada');
		$token = $recovery['AuthenticationToken']['token'];
		$this->setExpectedException('NotFoundException');
		$this->testAction('/setup/install/' . $userId . DS . $token, array('return' => 'contents', 'method' => 'get'));
	}

/**
 * Test install with a very old token
 */
	public function testInstallExpiredToken() {
		$recovery = $this->__startRecovery('ada@passbolt.com');
		$userId = Common::uuid('user.id.ada');
		$token = $recovery['AuthenticationToken']['token'];
		$this->User->AuthenticationToken->id = $recovery['AuthenticationToken']['id'];
		$this->User->AuthenticationToken->saveField('created', '2009-01-10 18:38:02');
		$this->setExpectedException('BadRequestException', 'The authentication token is expired.');
		$this->testAction('/setup/install/' . $userId . DS . $token, array('return' => 'vars', 'method' => 'get'));
	}

/**
 * Test install inactive user and good token
 */
	public function testInstallSuccess() {
		$recovery = $this->__startRecovery('ada@passbolt.com');
		$userId = Common::uuid('user.id.ada');
		$token = $recovery['AuthenticationToken']['token'];
		$this->User->id = $userId;
		$this->User->saveField('active', 0);
		$r = $this->testAction('/setup/install/' . $userId . DS . $token, array('return' => 'vars', 'method' => 'get'));
		$this->assertNotEmpty($r['userAgent']);
	}
}