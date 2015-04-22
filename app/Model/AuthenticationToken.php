<?php
/**
 * AuthenticationToken Model
 *
 * Copyright 2012, Passbolt
 * Passbolt(tm), the simple password management solution
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright		Copyright 2012, Passbolt.com
 * @package			app.Model.authenticationToken
 * @since			version 2.12.7
 * @license			http://www.passbolt.com/license
 */
class AuthenticationToken extends AppModel {

	/**
	 * Length of the base random string used to generate tokens.
	 */
	const TOKEN_STRING_LENGTH = 30;

	/**
	 * Generate a token.
	 * @return string
	 */
	public static function generateToken() {
		$rdStr = Common::randomString(self::TOKEN_STRING_LENGTH);
		$token = md5($rdStr + time());
		return $token;
	}

	/**
	 * Check if a token is valid.
	 *
	 * @param string $token
	 * @param uuid $userId
	 *
	 * @return array or null if doesn't exist.
	 */
	public function checkTokenIsValid($token, $userId) {
		$token = $this->find('first', array(
				'conditions' => array(
					'AuthenticationToken.user_id' => $userId,
					'AuthenticationToken.token' => $token
				),
				'order' => array(
					'created' => 'DESC'
				),
			));
		return $token;
	}

	/**
	 * Create a token for a given user.
	 *
	 * @param uuid $userId
	 *
	 * @return array result of the save function for token
	 */
	public function createToken($userId) {
		$token = array(
			'user_id' => $userId,
			'token' => self::generateToken(),
		);
		$this->create();
		$s = $this->save($token);
		return $s;
	}
}
