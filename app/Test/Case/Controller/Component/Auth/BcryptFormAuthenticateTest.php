<?php
/**
 * BcrypFormAuthenticateTest file
 *
 * @copyright   Copyright 2012, Passbolt.com
 * @license     http://www.passbolt.com/license
 * @package     Cake.Test.Case.Controller.Component.Auth
 * @since       version 2.12.9
 */

App::uses('AuthComponent', 'Controller/Component');
App::uses('FormAuthenticate', 'Controller/Component/Auth');
App::uses('BcryptFormAuthenticate', 'Controller/Component/Auth');
App::uses('AppModel', 'Model');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');

require_once CAKE . 'Test' . DS . 'Case' . DS . 'Model' . DS . 'models.php';

/**
 * Test case for FormAuthentication
 *
 * @package			 Cake.Test.Case.Controller.Component.Auth
 */
class BcryptFormAuthenticateTest extends CakeTestCase {

	public $fixtures = array('app.user');

/**
 * setup
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Collection = $this->getMock('ComponentCollection');
		$this->auth = new BcryptFormAuthenticate($this->Collection, array(
			'fields' => array('username' => 'username', 'password' => 'password'),
			'userModel' => 'User'
		));
		// update the passwords of the users in the fixtures
		$password = BcryptFormAuthenticate::hash('password');
		$User = ClassRegistry::init('User');
		$User->updateAll(array('password' => $User->getDataSource()->value($password)));
		$this->response = $this->getMock('CakeResponse');
	}

/**
 * test config exists
 *
 * @return void
 */
	public function testConfig() {
		// Test cost
		$this->assertEqual((Configure::read('Auth.bcrypt.cost') > 3), true, 'Bcrypt cost should be min 4');
		$this->assertEqual((Configure::read('Auth.bcrypt.cost') < 32), true, 'Bcrypt cost should be max 31');
	}

/**
 * test if hashing returns bcrypt hash
 *
 * @return void
 */
	public function testHash() {
		$password = BcryptFormAuthenticate::hash('password');
		$this->assertEqual(strlen($password), 60, 'password format should be 61 in length');

		$password2 = $this->auth->_password('password');

		$this->assertEqual($password, $password2, 'passwords should match');
		//$password = BcryptFormAuthenticate::hash('password', null);
		//
	}

/**
 * test applying settings in the constructor
 *
 * @return void
 */
	public function testConstructor() {
		$object = new FormAuthenticate($this->Collection, array(
			'userModel' => 'User',
			'fields' => array('username' => 'username', 'password' => 'password')
		));
		$this->assertEquals('User', $object->settings['userModel']);
		$this->assertEquals(array('username' => 'username', 'password' => 'password'), $object->settings['fields']);
	}

/**
 * test the authenticate method
 *
 * @return void
 */
	public function testAuthenticateNoData() {
		$request = new CakeRequest('users/index', false);
		$request->data = array();
		$this->assertFalse($this->auth->authenticate($request, $this->response));
	}

/**
 * test the authenticate method
 *
 * @return void
 */
	public function testAuthenticateNoUsername() {
		$request = new CakeRequest('users/index', false);
		$request->data = array('User' => array('password' => 'password'));
		$this->assertFalse($this->auth->authenticate($request, $this->response));
	}

/**
 * test the authenticate method
 *
 * @return void
 */
	public function testAuthenticateNoPassword() {
		$request = new CakeRequest('users/index', false);
		$request->data = array('User' => array('username' => 'kevin@passbolt.com'));
		$this->assertFalse($this->auth->authenticate($request, $this->response));
	}

/**
 * test the authenticate method
 *
 * @return void
 */
	public function testAuthenticateInjection() {
		$request = new CakeRequest('users/index', false);
		$request->data = array(
			'User' => array(
				'username' => '> 1',
				'password' => "' OR 1 = 1"
		));
		$this->assertFalse($this->auth->authenticate($request, $this->response));
	}

	/*
	public function testGuestAuthenticationFail() {
		$request = new CakeRequest('users/index', false);
		$request->data = array('User' => array(
			'username' => 'Anonymous',
			'password' => 'password'
		));
		$result = $this->auth->authenticate($request, $this->response);
		$this->assertFalse($this->auth->authenticate($request, $this->response));
	}
	*/

/**
 * test authenticate success
 *
 * @return void
 */
	public function testAuthenticateSuccess() {
		$request = new CakeRequest('posts/index', false);
		$request->data = array('User' => array(
			'username' => 'remy@passbolt.com',
			'password' => 'password'
		));
		//pr(BcryptFormAuthenticate::hash('password')); die;
		$result = $this->auth->authenticate($request, $this->response);
		$this->User = ClassRegistry::init('User');
		//pr($this->User->find('all'));// die;
		$expected = array( 'User' => array(
			'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4a',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'remy@passbolt.com',
			'active' => '1',
			'created' => '2012-07-04 13:45:11',
			'modified' => '2012-07-04 13:45:14',
			'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		));
		$this->assertEquals($expected, $result);
	}
}
