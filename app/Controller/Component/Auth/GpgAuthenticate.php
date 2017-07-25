<?php
/**
 * GpgAuthenticate
 * Manages a GPG based authentication scheme
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('BaseAuthenticate', 'Controller/Component/Auth');
App::uses('Gpgkey', 'Model');
App::uses('User', 'Model');

class GpgAuthenticate extends BaseAuthenticate {

/**
 * @var $_config array loaded from Configure::read('GPG')
 * @access protected
 */
	protected $_config;

/**
 * @var $_gpg gnupg instance
 * @access protected
 * @link http://php.net/manual/en/book.gnupg.php
 */
	protected $_gpg;

/**
 * @var $_response CakeResponse
 * @access protected
 */
	protected $_response;

/**
 * @var $_request CakeRequest
 * @access protected
 */
	protected $_request;

/**
 * Authenticate
 *
 * @param CakeRequest $request interface for accessing request parameters
 * @param CakeResponse $response features and functionality for generating HTTP responses
 * @return array|false the user or false if authentication failed
 */
	public function authenticate(CakeRequest $request, CakeResponse $response) {
		// Init gpg object and load server key
		$this->_initKeyring();
		$this->_response = &$response;
		$this->_request = &$request;

		// Begin process by checking if the user exist and his key is valid
		$response->header('X-GPGAuth-Authenticated', 'false');
		$response->header('X-GPGAuth-Progress', 'stage0');

		$user = $this->_identifyUserWithFingerprint($request);
		if ($user === false) {
			// If the user doesn't exist, we want to mention it in the debug anyway (no matter we are in debug mode or not)
			// IMPORTANT : Do not change this behavior. Exceptionally here, the client will need to know that
			// we are in this case to be able to render a proper feedback.
			$msg = 'There is no user associated with this key';
			$this->__error($msg);
			$this->_response->header('X-GPGAuth-Debug', $msg);
			return false;
		}

		// Step 0. Server authentication
		// The user is asking the server to identify itself by decrypting a token
		// that was encrypted by the client using the server public key
		if (isset($request->data['gpg_auth']['server_verify_token'])) {
			// Log authenticate call.
			ControllerLog::write(Status::DEBUG, $request, 'authenticate_stage_0', '');
			try {
				$nonce = $this->_gpg->decrypt($request->data['gpg_auth']['server_verify_token']);
				// check if the nonce is in valid format to avoid returning something sensitive decrypted
				if ($this->__checkNonce($nonce)) {
					$response->header('X-GPGAuth-Verify-Response', $nonce);
				}
			} catch (Exception $e) {
				return $this->__error('Decryption failed');
			}
			return false;
		}

		// Stage 1.
		// The user request an authentication by claiming he owns a given public key
		// We therefore send an encrypted message that must be returned next time in order to verify
		$AuthenticationToken = Common::getModel('AuthenticationToken');
		if (!isset($request->data['gpg_auth']['user_token_result'])) {
			ControllerLog::write(Status::DEBUG, $request, 'authenticate_stage_1', '');
			// set the stage
			$this->_response->header('X-GPGAuth-Progress', 'stage1');

			// set encryption and signature keys
			$this->_setUserKey($request->data['gpg_auth']['keyid'], $user);
			$this->_gpg->addsignkey(
				$this->_config['serverKey']['fingerprint'], $this->_config['serverKey']['passphrase']);

			// generate the authentication token
			$token = $AuthenticationToken->generate($user['User']['id']);

			if (!isset($token['AuthenticationToken']['token'])) {
				return $this->__error('Failed to create token');
			}
			$token = 'gpgauthv1.3.0|36|' . $token['AuthenticationToken']['token'] . '|gpgauthv1.3.0';

			// encrypt and sign and send
			$msg = $this->_gpg->encryptsign($token);
			$msg = quotemeta(urlencode($msg));
			$this->_response->header('X-GPGAuth-User-Auth-Token', $msg);
			return false;
		}

		// Stage 2.
		// Check if the token provided at stage 1 have been decrypted and is still valid
		if (isset($request->data['gpg_auth']['user_token_result'])) {
			ControllerLog::write(Status::DEBUG, $request, 'authenticate_stage_2', '');
			$this->_response->header('X-GPGAuth-Progress', 'stage2');
			if (!($this->__checkNonce($request->data['gpg_auth']['user_token_result']))) {
				return $this->__error('The user token result is not a valid UUID');
			}
			// extract the UUID to get the database records
			list($version, $length, $uuid, $version2) = explode('|', $request->data['gpg_auth']['user_token_result']);
			$isValidToken = $AuthenticationToken->isValid($uuid, $user['User']['id']);
			if (!$isValidToken) {
				return $this->__error('The user token result could not be found ' .
					't=' . $uuid . ' u=' . $user['User']['id']);
			}
		}

		// Completed
		// we set the user to active, delete the auth token and provide some success feedback
		AuthenticationToken::setInactive($uuid, $user['User']['id']);
		$user = User::setActive($user, false); // session is updated by Auth component itself

		$this->_response->header('X-GPGAuth-Progress', 'complete');
		$this->_response->header('X-GPGAuth-Authenticated', 'true');
		$this->_response->header('X-GPGAuth-Refer', '/');
		return $user;
	}

/**
 * When an unauthenticated user tries to access a protected page this method is called
 *
 * @param CakeRequest $request interface for accessing request parameters
 * @param CakeResponse $response features and functionality for generating HTTP responses
 * @throws ForbiddenException
 * @return true
 */
	public function unauthenticated(CakeRequest $request, CakeResponse $response) {
		// If it's JSON we show an error message
		if ($request->is('json')) {
			throw new ForbiddenException(__('You need to login to access this location'));
		}

		// If it's a page request we redirect to the login form
		return false;
	}

/**
 * Initialize GPG keyring and load the config
 *
 * @throws CakeException if the config is missing or key is not set or not usable to decrypt
 * @return void
 */
	protected function _initKeyring() {
		// load base configuration
		$this->_config = Configure::read('GPG');
		if (!isset($this->_config['serverKey']['fingerprint'])) {
			throw new InternalErrorException('The GnuPG config for the server is not available or incomplete');
		}
		$keyid = $this->_config['serverKey']['fingerprint'];

		// check if the default key is set and available in gpg
		$this->_gpg = new gnupg();
		$info = $this->_gpg->keyinfo($keyid);
		if (empty($info)) {
			throw new InternalErrorException('The GPG Server key defined in the config is not found in the gpg keyring');
		}

		// set the key to be used for decrypting
		if (!$this->_gpg->adddecryptkey($keyid, $this->_config['serverKey']['passphrase'])) {
			throw new InternalErrorException('The GPG Server key defined in the config cannot be used to decrypt');
		}

		$this->_gpg->seterrormode(gnupg::ERROR_EXCEPTION);
	}

/**
 * Set user key for encryption and import it in the keyring if needed
 *
 * @param string $keyid SHA1 fingerprint
 * @param array $user the current user
 * @throws CakeException when the key is not valid
 * @return void
 */
	protected function _setUserKey($keyid, $user) {
		$info = $this->_gpg->keyinfo($keyid);
		if (empty($info)) {
			if (!$this->_gpg->import($user['Gpgkey']['key'])) {
				throw new InternalErrorException('The GnuPG key for the user could not be imported');
			}
			// check that the imported key match the fingerprint
			$info = $this->_gpg->keyinfo($keyid);
			if (empty($info)) {
				throw new InternalErrorException('The GnuPG key for the user is not available or not working');
			}
		}
		$this->_gpg->addencryptkey($keyid);
	}

/**
 * Find a user record from a public key fingerprint
 *
 * @param CakeRequest $request interface for accessing request parameters
 * @return mixed false or user
 */
	protected function _identifyUserWithFingerprint(CakeRequest $request) {
		// First we check if we can get the user with the key fingerprint
		if (!isset($request->data['gpg_auth']['keyid'])) {
			$this->__debug('not key id set');
			return false;
		}
		$keyid = strtoupper($request->data['gpg_auth']['keyid']);

		// validate the fingerprint format
		if (!Gpgkey::isValidFingerprint($keyid)) {
			$this->__debug('invalid fingerprint');
			return false;
		}

		// try to find the user
		$User = Common::getModel('User');
		$user = [
			'Gpgkey' => [
				'fingerprint' => $keyid
			]
		];
		$user = $User->find('first', User::getFindOptions('User::GpgAuth', Role::USER, $user));
		if (empty($user)) {
			$this->__debug('user nor found');
			return false;
		}

		return $user;
	}

/**
 * Set a debug message in header if debug is enabled
 *
 * @param string $s debug message
 * @return void
 */
	private function __debug($s = null) {
		if (isset($s) && Configure::read('debug')) {
			$this->_response->header('X-GPGAuth-Debug', $s);
		}
	}

/**
 * Trigger a GPGAuth Error
 *
 * @param string $msg the error message
 * @return bool always false, that will be used as authenticated method final result
 */
	private function __error($msg = null) {
		$this->__debug($msg);
		$this->_response->header('X-GPGAuth-Error', 'true');
		return false;
	}

/**
 * Validate the format of the nonce
 *
 * @param string $nonce for example: 'gpgauthv1.3.0|36|de305d54-75b4-431b-adb2-eb6b9e546014|gpgauthv1.3.0'
 * @return bool true if valid, false otherwise
 */
	private function __checkNonce($nonce) {
		$result = explode('|', $nonce);
		$errorMsg = 'Invalid verify token format, ';
		if (count($result) != 4) {
			return $this->__error($errorMsg . 'sections missing or wrong delimiters');
		}
		list($version, $length, $uuid, $version2) = $result;
		if ($version != $version2) {
			return $this->__error($errorMsg . 'version numbers don\'t match');
		}
		if ($version != 'gpgauthv1.3.0') {
			return $this->__error($errorMsg . 'wrong version number');
		}
		if ($version != Common::isUuid($uuid)) {
			return $this->__error($errorMsg . 'not a UUID');
		}
		if ($length != 36) {
			return $this->__error($errorMsg . 'wrong token data length');
		}
		return true;
	}
}