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

class AuthenticationTokenTest extends CakeTestCase {

	public $fixtures = array(
		'app.user',
		'app.authenticationToken'
	);

	public function setUp() {
		parent::setUp();
		$this->AuthenticationToken = ClassRegistry::init('AuthenticationToken');
		$this->User = ClassRegistry::init('User');
	}

	/**
	 * Test UserId Validation
	 * @return void
	 */
	public function testUserIdValidation() {
		$user = $this->User->findById(common::uuid('user.id.user'));
		$testcases = array(
			'' => false,
			'?!#' => false,
			'test' => false,
			'aaa00003-c5cd-11e1-a0c5-080027z!6c4c' => false,
			'zzz00003-c5cd-11e1-a0c5-080027796c4c' => false,
			'aaa00003-c5cd-11e1-a0c5-080027796c4c' => false,
			$user['User']['id'] => true,
		);
		foreach ($testcases as $testcase => $result) {
			$authenticationToken = array('AuthenticationToken' => array('user_id' => $testcase));
			$this->AuthenticationToken->set($authenticationToken);
			if($result) $msg = 'validation of the user_id with ' . $testcase . ' should validate';
			else $msg = 'validation of the user_id with ' . $testcase . ' should not validate';
			$validate = $this->AuthenticationToken->validates(array('fieldList' => array('user_id')));
			$this->assertEquals($validate, $result, $msg);
		}
	}

	/**
	 * Test Token Validation
	 * @return void
	 */
	public function testTokenValidation() {
		$md5 = md5('test');
		$uuid = Common::uuid();
		$testcases = array(
			'' => false,
			'?!#' => false,
			'test' => false,
			'!7§5HJhYtgfgbvfdrthgfrtrgfdrtrer' => false,
			$md5 => false,
			$uuid => true,
		);
		foreach ($testcases as $testcase => $result) {
			$authenticationToken = array('AuthenticationToken' => array('token' => $testcase));
			$this->AuthenticationToken->set($authenticationToken);
			if($result) $msg = 'validation of the token with ' . $testcase . ' should validate';
			else $msg = 'validation of the token with ' . $testcase . ' should not validate';
			$validate = $this->AuthenticationToken->validates(array('fieldList' => array('token')));
			$this->assertEquals($validate, $result, $msg);
		}
	}

	/**
	 * Test create.
	 */
	public function testCreateToken() {
		$user = $this->User->findByUsername('user@passbolt.com');
		$token = $this->AuthenticationToken->generate($user['User']['id']);
		$this->assertEquals(!empty($token), true, 'Token should have been created, but has not');
	}

	/**
	 * Test create token for invalid user.
	 */
	public function testCreateTokenInvalidUser() {
		$token = $this->AuthenticationToken->generate('aaa00003-c5cd-11e1-a0c5-080027z!6c4c');
		$this->assertEquals(false, $token, 'Creation of the token should have failed');
	}

	/**
	 * Test that a token is valid.
	 */
	public function testCheckTokenIsValid() {
		$user = $this->User->findByUsername('user@passbolt.com');
		$token = $this->AuthenticationToken->generate($user['User']['id']);
		$isValid = $this->AuthenticationToken->isValid($token['AuthenticationToken']['token'], $user['User']['id']);
		$this->assertEquals(is_array($isValid), true, 'The test should have returned a valid token, but has not');
	}

	/**
	 * Test that a token is valid for an invalid user
	 */
	public function testCheckTokenIsValidInvalidUser() {
		$user = $this->User->findByUsername('user@passbolt.com');
		$token = $this->AuthenticationToken->generate($user['User']['id']);
		$isValid = $this->AuthenticationToken->isValid($token['AuthenticationToken']['token'], 'aaa00003-c5cd-11e1-a0c5-080027z!6c4c');
		$this->assertEquals((bool)$isValid, false, 'The test should have returned an invalid token');
	}

	public function testSetInactive() {
		$user = $this->User->findByUsername('user@passbolt.com');
		$token = $this->AuthenticationToken->generate($user['User']['id']);
		$isValid = $this->AuthenticationToken->isValid($token['AuthenticationToken']['token'], $user['User']['id']);
		$this->AuthenticationToken->setInactive($token['AuthenticationToken']['token'], $user['User']['id']);
		$t = $this->AuthenticationToken->find('first', $token['AuthenticationToken']['id']);
		$this->assertTrue($t['AuthenticationToken']['active'] == 0, 'The authentication token should be inactive');
	}
}
