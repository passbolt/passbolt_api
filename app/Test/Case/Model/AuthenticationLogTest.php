<?php
/**
 * AuthenticationLog Model Test
 *
 * @copyright     Copyright 2012, Passbolt.com
 * @package       app.Test.Case.Model.AuthenticationLogTest
 * @since         version 2.13.03
 * @license       http://www.passbolt.com/license
 */
App::uses('AuthenticationLog', 'Model');

class AuthenticationLogTest extends CakeTestCase {

	public $fixtures = array('app.user', 'app.authenticationLog');

	public function setUp() {
		parent::setUp();
		$this->AuthenticationLog = ClassRegistry::init('AuthenticationLog');
		$this->User = ClassRegistry::init('User');
	}
	
	public function testLog() {
		//TODO
	}

	public function testGetFailedAuthenticationCount() {
		$userTest = $this->User->findById(Common::uuid('user.id.user'));
		$l1 = array('AuthenticationLog' => array(
			'user_id' => $userTest['User']['id'],
			'username' => $userTest['User']['username'],
			'ip' => '127.0.0.1',
			'status' => false,
			'created' => '2012-07-04 13:39:25',
		));
		$this->AuthenticationLog->create();
		$this->AuthenticationLog->save($l1);
		$l2 = array('AuthenticationLog' => array(
			'user_id' => $userTest['User']['id'],
			'username' => $userTest['User']['username'],
			'ip' => '127.0.0.1',
			'status' => false,
			'created' => '2012-07-04 13:40:25',
		));
		$this->AuthenticationLog->create();
		$this->AuthenticationLog->save($l2);
		$l3 = array('AuthenticationLog' => array(
			'user_id' => $userTest['User']['id'],
			'username' => $userTest['User']['username'],
			'ip' => '127.0.0.1',
			'status' => true,
			'created' => '2012-07-04 13:41:25',
		));
		$this->AuthenticationLog->create();
		$this->AuthenticationLog->save($l3);
		$l4 = array('AuthenticationLog' => array(
			'user_id' => $userTest['User']['id'],
			'username' => $userTest['User']['username'],
			'ip' => '127.0.0.1',
			'status' => false,
			'created' => '2012-07-04 13:42:25',
		));
		$this->AuthenticationLog->create();
		$this->AuthenticationLog->save($l4);
		$l5 = array('AuthenticationLog' => array(
			'user_id' => $userTest['User']['id'],
			'username' => $userTest['User']['username'],
			'ip' => '192.168.0.1',
			'status' => false,
			'created' => '2012-07-04 13:43:25',
		));
		$this->AuthenticationLog->create();
		$this->AuthenticationLog->save($l5);

		// test count for user and ip
		$c = $this->AuthenticationLog->getFailedAuthenticationCount($userTest['User']['username'], '127.0.0.1');
		$this->AssertEquals($c, 3, "The test should have returned 3 but returned $c");

		// test count for user only
		$c = $this->AuthenticationLog->getFailedAuthenticationCount($userTest['User']['username']);
		$this->AssertEquals($c, 4, "The test should have returned 4 but returned $c");

		// test count for ip only
		$c = $this->AuthenticationLog->getFailedAuthenticationCount(null, '127.0.0.1');
		$this->AssertEquals($c, 3, "The test should have returned 3 but returned $c");

		// test count for user, ip, and since Timestamp
		$c = $this->AuthenticationLog->getFailedAuthenticationCount($userTest['User']['username'], '127.0.0.1', strtotime('2012-07-04 13:41:25'));
		$this->AssertEquals($c, 1, "The test should have returned 1 but returned $c");

		// test with wrong username
		$c = $this->AuthenticationLog->getFailedAuthenticationCount('whatkindofusernameisthat');
		$this->AssertEquals($c, false, "The test should have returned false but returned $c");
	}

	public function testGetLastFailedAuthenticationLog() {
		$userTest = $this->User->findById(Common::uuid('user.id.user'));
		$l3 = array('AuthenticationLog' => array(
			'user_id' => $userTest['User']['id'],
			'username' => $userTest['User']['username'],
			'ip' => '127.0.0.1',
			'status' => true,
			'created' => '2012-07-04 13:41:25',
		));
		$this->AuthenticationLog->create();
		$this->AuthenticationLog->save($l3);
		$l4 = array('AuthenticationLog' => array(
			'user_id' => $userTest['User']['id'],
			'username' => $userTest['User']['username'],
			'ip' => '127.0.0.1',
			'status' => false,
			'created' => '2012-07-04 13:42:25',
		));
		$this->AuthenticationLog->create();
		$this->AuthenticationLog->save($l4);
		$l5 = array('AuthenticationLog' => array(
			'user_id' => $userTest['User']['id'],
			'username' => $userTest['User']['username'],
			'ip' => '192.168.0.1',
			'status' => false,
			'created' => '2012-07-04 13:43:25',
		));
		$this->AuthenticationLog->create();
		$this->AuthenticationLog->save($l5);

		// test with login only
		$a = $this->AuthenticationLog->getLastFailedAuthenticationLog($userTest['User']['username']);
		$this->AssertEquals($a['AuthenticationLog']['created'], '2012-07-04 13:43:25', "The test has returned the wrong result");

		// test with ip only
		$a = $this->AuthenticationLog->getLastFailedAuthenticationLog(null, '127.0.0.1');
		$this->AssertEquals($a['AuthenticationLog']['created'], '2012-07-04 13:42:25', "The test has returned the wrong result");

		// test with fake username
		$a = $this->AuthenticationLog->getLastFailedAuthenticationLog('whatkindofusernameisthat');
		$this->AssertEquals($a, null, "The test should have returned null but has returned $a");
	}
}