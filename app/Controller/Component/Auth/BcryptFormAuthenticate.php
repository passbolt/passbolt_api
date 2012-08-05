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
 * Password method used for logging in.
 *
 * @param string $password Password.
 * @return string Hashed password.
 * @access protected
 */
	protected function _password($password) {
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
	public static function hash($password) {
		// @todo PASSBOLT-180 use a non application-wide salt
		$salt = substr(Configure::read('Auth.bcrypt.salt'), 0, 22);
		return crypt($password, '$2a$' . Configure::read('Auth.bcrypt.cost') . '$' . $salt);
	}
}
