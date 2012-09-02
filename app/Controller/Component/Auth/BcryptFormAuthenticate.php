<?php
/**
 * Bcrypt based Form Authentication Component
 * 
 * @copyright    Copyright 2012, Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Controller.Component.Auth.BcryptFormAuthenticate
 * @since        version 2.12.9
 */
App::uses('FormAuthenticate', 'Controller/Component/Auth');

class BcryptFormAuthenticate extends FormAuthenticate {

/**
 * Find a user record using the standard options.
 *
 * @param string $username The username/identifier.
 * @param string $password The unhashed password.
 * @return Mixed Either false on failure, or an array of user data.
 */
	protected function _findUser($username, $password) {
		$this->settings['scope'] = array(
			'active' => 1
			// TODO is not guest and password is not null
		);
		$u = parent::_findUser($username, $password);
		if (is_array($u)) {
			 $u = array('User' => $u);
		}
		return $u;
	}

/**
 * Password method used for logging in.
 *
 * @param string $password Password.
 * @return string Hashed password.
 * @access protected
 */
	protected function _password($password) {
		// @todo PASSBOLT-180 use a non application-wide salt
		return self::hash($password);
	}

/**
 * Create a blowfish / bcrypt hash.
 * Individual salts could/should used for increased security.
 *
 * @param string $password Password.
 * @return string Hashed password.
 * @access public
 */
	public static function hash($password, $salt=null) {
		if (!isset($salt) || strlen($salt) < 23) {
			$salt = Configure::read('Auth.bcrypt.salt');
		}
		// @see https://wiki.mozilla.org/WebAppSec/Secure_Coding_Guidelines#Password_Storage
		$salt = hash_hmac('sha512', $salt, Configure::read('Auth.bcrypt.hmac'));
		$salt = substr($salt, 0, 22);
		$salt = '$2a$' . Configure::read('Auth.bcrypt.cost') . '$' . $salt;
		return crypt($password, $salt);
	}
}
