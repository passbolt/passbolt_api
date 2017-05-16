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

class SetupControllerRecoverTest extends ControllerTestCase {

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
	protected $requestMethod;

	public function setUp() {
		parent::setUp();
		$this->User = Common::getModel('User');
		$this->Gpgkey = Common::getModel('Gpgkey');
		$this->session = new CakeSession();
		$this->requestMethod = ['return' => 'contents', 'method' => 'get'];
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
 * Test recover with no user id
 */
	public function testRecoverNoUserId() {
		$this->setExpectedException('BadRequestException', 'The user id is missing.');
		$this->testAction('/setup/recover.json', $this->requestMethod);
	}

/**
 * Test recover with invalid user id
 */
	public function testRecoverInvalidUserId() {
		$this->setExpectedException('BadRequestException', 'The user id is not valid.');
		$this->testAction('/setup/recover/zaa00003-c5cd-11e1-a0c5-080027aa6c4c', $this->requestMethod);
	}

/**
 * Test recover with invalid user id
 */
	public function testRecoverNoToken() {
		$this->setExpectedException('BadRequestException', 'The authentication token is missing.');
		$this->testAction('/setup/recover/' . Common::uuid() . DS , $this->requestMethod);
	}

/**
 * Test recover with invalid user id
 */
	public function testRecoverInvalidToken() {
		$this->setExpectedException('BadRequestException', 'The authentication token is not valid.');
		$this->testAction('/setup/recover/' . Common::uuid() . DS .  Common::uuid(), $this->requestMethod);
	}

/**
 * Test recover with invalid user id
 */
	public function testRecoverInvalidUserGoodToken() {
		$this->setExpectedException('BadRequestException');
		$recovery = $this->__startRecovery('ada@passbolt.com');
		$this->testAction('/setup/recover/' . Common::uuid() . DS .  $recovery['AuthenticationToken']['token'], $this->requestMethod);
	}

/**
 * Test recover with invalid token but good user id
 */
	public function testRecoverGooddUserInvalidToken() {
		$this->setExpectedException('BadRequestException');
		$this->testAction('/setup/recover/' . Common::uuid('user.id.ada') . DS .  Common::uuid(), $this->requestMethod);
	}

/**
 * Test recover with an already active user
 */
	public function testRecoverInactiveUser() {
		$recovery = $this->__startRecovery('ada@passbolt.com');
		$userId = Common::uuid('user.id.ada');
		$token = $recovery['AuthenticationToken']['token'];
		$this->User->id = $userId;
		$this->User->saveField('active', 0);
		$this->setExpectedException('NotFoundException', 'The user does not exist.');
		$this->testAction('/setup/recover/' . $userId . DS . $token, $this->requestMethod);
	}

/**
 * Test recover with a very old token
 */
	public function testRecoverExpiredToken() {
		$recovery = $this->__startRecovery('ada@passbolt.com');
		$userId = Common::uuid('user.id.ada');
		$token = $recovery['AuthenticationToken']['token'];
		$this->User->AuthenticationToken->id = $recovery['AuthenticationToken']['id'];
		$this->User->AuthenticationToken->saveField('created', '2009-01-10 18:38:02');
		$this->setExpectedException('BadRequestException', 'The authentication token is not valid.');
		$this->testAction('/setup/recover/' . $userId . DS . $token, array('return' => 'vars', 'method' => 'get'));
	}

/**
 * Test recover with active user and good token
 */
	public function testRecoverSuccess() {
		$recovery = $this->__startRecovery('ada@passbolt.com');
		$userId = Common::uuid('user.id.ada');
		$token = $recovery['AuthenticationToken']['token'];
		$r = $this->testAction('/setup/recover/' . $userId . DS . $token, array('return' => 'vars', 'method' => 'get'));
		$this->assertNotEmpty($r['userAgent']);
	}
}