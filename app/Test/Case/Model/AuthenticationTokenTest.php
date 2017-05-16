<?php
/**
 * AuthenticationToken Model Test
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @package       app.Test.Case.Model.AuthenticationTokenTest
 * @since         version 2.13.03
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

class AuthenticationTokenTest extends CakeTestCase {

	public $fixtures = array(
		'app.user',
		'app.role',
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
		$user = $this->User->findById(Common::uuid('user.id.user'));
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
	 * Test generate a token with an invalid user id.
	 */
	public function testGenerateUserIdNotValid() {
		$token = $this->AuthenticationToken->generate('badId');
		$this->assertEquals(false, $token, 'Creation of the token should have failed');
	}

	/**
	 * Test generate.
	 */
	public function testGenerate() {
		$user = $this->User->findByUsername('user@passbolt.com');
		$token = $this->AuthenticationToken->generate($user['User']['id']);
		$this->assertEquals(!empty($token), true, 'Token should have been created, but has not');
	}

	/**
	 * Test isValid with an invalid user id
	 */
	public function testIsValidInvalidUserId() {
		$user = $this->User->findByUsername('user@passbolt.com');
		$token = $this->AuthenticationToken->generate($user['User']['id']);
		$isValid = $this->AuthenticationToken->isValid($token['AuthenticationToken']['token'], 'badId');
		$this->assertFalse($isValid);
	}

	/**
	 * Test isValid with an invalid token
	 */
	public function testIsValidInvalidToken() {
		$user = $this->User->findByUsername('user@passbolt.com');
		$token = $this->AuthenticationToken->generate($user['User']['id']);
		$isValid = $this->AuthenticationToken->isValid('badId', $user['User']['id']);
		$this->assertFalse($isValid);
	}

	/**
	 * Test that a token is valid for an invalid user
	 */
	public function testIsValidExpiredToken() {
		$user = $this->User->findByUsername('user@passbolt.com');

		// Generate a token and check that it is valid.
		$token = $this->AuthenticationToken->generate($user['User']['id']);
		$isValid = $this->AuthenticationToken->isValid($token['AuthenticationToken']['token'], $user['User']['id']);
		$this->assertTrue($isValid);

		// Reduce the authentication token expiracy period, and test that the token is not valid on sec in the future.
		Configure::write('Auth.tokenExpiracy', 0.016);
		sleep(1);
		$isValid = $this->AuthenticationToken->isValid($token['AuthenticationToken']['token'], $user['User']['id']);
		$this->assertFalse($isValid);
	}

	/**
	 * Test that a token is valid
	 */
	public function testIsValid() {
		$user = $this->User->findByUsername('user@passbolt.com');
		$token = $this->AuthenticationToken->generate($user['User']['id']);
		$isValid = $this->AuthenticationToken->isValid($token['AuthenticationToken']['token'], $user['User']['id']);
		$this->assertTrue($isValid);
	}

	/**
	 * Test that a token is inactive for an inactive user.
	 */
	public function testSetInactive() {
		$user = $this->User->findByUsername('user@passbolt.com');
		$token = $this->AuthenticationToken->generate($user['User']['id']);
		$isValid = $this->AuthenticationToken->isValid($token['AuthenticationToken']['token'], $user['User']['id']);
		$this->assertTrue($isValid);
		$this->AuthenticationToken->setInactive($token['AuthenticationToken']['token'], $user['User']['id']);
		$isValid = $this->AuthenticationToken->isValid($token['AuthenticationToken']['token'], $user['User']['id']);
		$this->assertFalse($isValid);
		$t = $this->AuthenticationToken->find('first', $token['AuthenticationToken']['id']);
		$this->assertTrue($t['AuthenticationToken']['active'] == 0, 'The authentication token should be inactive');
	}

	/**
	 * Test that a token is expired for an invalid token
	 */
	public function testExpiredInvalidToken() {
		$isNotExpired = $this->AuthenticationToken->isNotExpired('badId');
		$this->assertFalse($isNotExpired);
	}

	/**
	 * Test that a token is expired while the token go over the expiracy period.
	 */
	public function testIsNotExpiredExpiredToken() {
		$user = $this->User->findByUsername('user@passbolt.com');

		// Generate a token and check that it is expired.
		$token = $this->AuthenticationToken->generate($user['User']['id']);
		$isNotExpired = $this->AuthenticationToken->isNotExpired($token['AuthenticationToken']['token']);
		$this->assertTrue($isNotExpired);

		// Reduce the authentication token expiracy period, and test that the token is expired.
		Configure::write('Auth.tokenExpiracy', 0.016);
		sleep(1);
		$isValid = $this->AuthenticationToken->isNotExpired($token['AuthenticationToken']['token']);
		$this->assertFalse($isValid);
	}

	/**
	 * Test that a token is not expired for a just created token
	 */
	public function testIsNotExpired() {
		$user = $this->User->findByUsername('user@passbolt.com');
		$token = $this->AuthenticationToken->generate($user['User']['id']);
		$isNotExpired = $this->AuthenticationToken->isNotExpired($token['AuthenticationToken']['token']);
		$this->assertTrue($isNotExpired);
	}

}
