<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
namespace App\Auth;

use Aura\Intl\Exception;
use Cake\Auth\BaseAuthenticate;
use Cake\Core\Configure;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\InternalErrorException;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;

class GpgAuthenticate extends BaseAuthenticate
{

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
     * @var $_response Response
     * @access protected
     */
    protected $_response;

    /**
     * @var string additional debug info
     */
    protected $_debug;

    /**
     * @var
     */
    protected $_data;

    /**
     * Authenticate
     *
     * @param ServerRequest $request interface for accessing request parameters
     * @param Response $response features and functionality for generating HTTP responses
     * @return array|false the user or false if authentication failed
     */
    public function authenticate(ServerRequest $request, Response $response)
    {
        $this->_response = $response
            ->withHeader('X-GPGAuth-Authenticated', 'false')
            ->withHeader('X-GPGAuth-Progress', 'stage0');

        // Init gpg object and load server key
        $this->__normalizeRequestData($request);
        $this->__initKeyring();

        // Begin process by checking if the user exist and his key is valid
        $user = $this->__identifyUserWithFingerprint();
        if ($user === false) {
            // If the user doesn't exist, we want to mention it in the debug anyway (no matter we are in debug mode or not)
            // IMPORTANT : Do not change this behavior. Exceptionally here, the client will need to know that
            // we are in this case to be able to render a proper feedback.
            $msg = 'There is no user associated with this key. ' . $this->_debug;
            $this->__error($msg);
            $this->_response = $this->_response->withHeader('X-GPGAuth-Debug', $msg);

            return false;
        }

        // Step 0. Server authentication
        // The user is asking the server to identify itself by decrypting a token
        // that was encrypted by the client using the server public key
        if (isset($this->_data['server_verify_token'])) {
            // Log authenticate call.
            //ControllerLog::write(Status::DEBUG, $request, 'authenticate_stage_0', '');
            try {
                $nonce = $this->_gpg->decrypt($this->_data['server_verify_token']);
                // check if the nonce is in valid format to avoid returning something sensitive decrypted
                if ($this->__checkNonce($nonce)) {
                    $this->_response = $this->_response->withHeader('X-GPGAuth-Verify-Response', $nonce);
                }
            } catch (Exception $e) {
                return $this->__error('Decryption failed');
            }

            return false;
        }

        // Stage 1.
        // The user request an authentication by claiming he owns a given public key
        // We therefore send an encrypted message that must be returned next time in order to verify
        $AuthenticationToken = TableRegistry::get('AuthenticationTokens');
        if (!isset($this->_data['user_token_result'])) {
            //ControllerLog::write(Status::DEBUG, $request, 'authenticate_stage_1', '');
            // set the stage
            $this->_response = $this->_response->withHeader('X-GPGAuth-Progress', 'stage1');

            // set encryption and signature keys
            try {
                $this->__setUserKey($this->_data['keyid'], $user);
            } catch (Exception $e) {
                return $this->__error($e->getMessage());
            }
            $this->_gpg->addsignkey(
                $this->_config['serverKey']['fingerprint'],
                $this->_config['serverKey']['passphrase']
            );

            // generate the authentication token
            $authenticationToken = $AuthenticationToken->generate($user->id);
            if (!isset($authenticationToken->token)) {
                return $this->__error('Failed to create token');
            }
            $token = 'gpgauthv1.3.0|36|' . $authenticationToken->token . '|gpgauthv1.3.0';

            // encrypt and sign and send
            $msg = $this->_gpg->encryptsign($token);
            $msg = quotemeta(urlencode($msg));
            $this->_response = $this->_response->withHeader('X-GPGAuth-User-Auth-Token', $msg);

            return false;
        }

        // Stage 2.
        // Check if the token provided at stage 1 have been decrypted and is still valid
        if (isset($this->_data['user_token_result'])) {
            //ControllerLog::write(Status::DEBUG, $request, 'authenticate_stage_2', '');
            $this->_response = $this->_response->withHeader('X-GPGAuth-Progress', 'stage2');
            if (!($this->__checkNonce($this->_data['user_token_result']))) {
                return $this->__error('The user token result is not a valid UUID');
            }
            // extract the UUID to get the database records
            list($version, $length, $uuid, $version2) = explode('|', $this->_data['user_token_result']);
            $isValidToken = $AuthenticationToken->isValid($uuid, $user->id);
            if (!$isValidToken) {
                return $this->__error('The user token result could not be found ' .
                    't=' . $uuid . ' u=' . $user->id);
            }
        }

        // Completed
        // we set the user to active, delete the auth token and provide some success feedback
        $AuthenticationToken->setInactive($uuid);
        $this->_response = $this->_response
            ->withHeader('X-GPGAuth-Progress', 'complete')
            ->withHeader('X-GPGAuth-Authenticated', 'true')
            ->withHeader('X-GPGAuth-Refer', '/');

        return $user->toArray();
    }

    /**
     * When an unauthenticated user tries to access a protected page this method is called
     *
     * @param ServerRequest $request interface for accessing request parameters
     * @param Response $response features and functionality for generating HTTP responses
     * @throws ForbiddenException
     * @return void
     */
    public function unauthenticated(ServerRequest $request, Response $response)
    {
        // If it's JSON we show an error message
        if ($request->is('json')) {
            throw new ForbiddenException(__('You need to login to access this location.'));
        }
    }

    /**
     * Initialize GPG keyring and load the config
     *
     * @throws CakeException if the config is missing or key is not set or not usable to decrypt
     * @return void
     */
    private function __initKeyring()
    {
        // load base configuration
        $this->_config = Configure::read('passbolt.gpg');
        if (!isset($this->_config['serverKey']['fingerprint'])) {
            throw new InternalErrorException('The GnuPG config for the server is not available or incomplete');
        }
        $keyid = $this->_config['serverKey']['fingerprint'];

        // check if the default key is set and available in gpg
        $this->_gpg = new \gnupg();
        $info = $this->_gpg->keyinfo($keyid);
        $this->_gpg->seterrormode(\gnupg::ERROR_EXCEPTION);
        if (empty($info)) {
            throw new InternalErrorException('The GPG Server key defined in the config is not found in the gpg keyring');
        }

        // set the key to be used for decrypting
        if (!$this->_gpg->adddecryptkey($keyid, $this->_config['serverKey']['passphrase'])) {
            throw new InternalErrorException('The GPG Server key defined in the config cannot be used to decrypt');
        }
    }

    /**
     * Set user key for encryption and import it in the keyring if needed
     *
     * @param string $keyid SHA1 fingerprint
     * @param array $user the current user
     * @throws CakeException when the key is not valid
     * @return void
     */
    private function __setUserKey($keyid, $user)
    {
        $info = $this->_gpg->keyinfo($keyid);
        if (empty($info)) {
            if (!$this->_gpg->import($user->gpgkey->armored_key)) {
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
     * @return mixed false or user
     */
    private function __identifyUserWithFingerprint()
    {
        // First we check if we can get the user with the key fingerprint
        if (!isset($this->_data['keyid'])) {
            $this->__debug('No key id set.');

            return false;
        }
        $keyid = strtoupper($this->_data['keyid']);

        // validate the fingerprint format
        $Gpgkeys = TableRegistry::get('Gpgkeys');
        if (!$Gpgkeys->isValidFingerprintRule($keyid)) {
            $this->__debug('Invalid fingerprint.');

            return false;
        }

        // try to find the user
        $Users = TableRegistry::get('Users');
        $user = $Users->find('auth', ['fingerprint' => $keyid])->first();
        if (empty($user)) {
            $this->__debug('User not found.');

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
    private function __debug($s = null)
    {
        $this->_debug = $s;
        if (isset($s) && Configure::read('debug')) {
            $this->_response = $this->_response->withHeader('X-GPGAuth-Debug', $s);
        }
    }

    /**
     * Trigger a GPGAuth Error
     *
     * @param string $msg the error message
     * @return bool always false, that will be used as authenticated method final result
     */
    private function __error($msg = null)
    {
        $this->__debug($msg);
        $this->_response = $this->_response->withHeader('X-GPGAuth-Error', 'true');

        return false;
    }

    /**
     * Validate the format of the nonce
     *
     * @param string $nonce for example: 'gpgauthv1.3.0|36|de305d54-75b4-431b-adb2-eb6b9e546014|gpgauthv1.3.0'
     * @return bool true if valid, false otherwise
     */
    private function __checkNonce($nonce)
    {
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
        if ($version != Validation::uuid($uuid)) {
            return $this->__error($errorMsg . 'not a UUID');
        }
        if ($length != 36) {
            return $this->__error($errorMsg . 'wrong token data length');
        }

        return true;
    }

    /**
     * Normalize request data
     *
     * @param object $request Request
     * @return array|null
     */
    private function __normalizeRequestData($request)
    {
        $data = $request->getData();
        if (isset($data['data'])) {
            $data = $data['data'];
        }
        if (isset($data['gpg_auth'])) {
            $this->_data = $data['gpg_auth'];
        } else {
            $this->_data = null;
        }

        return $this->_data;
    }

    /**
     * Return the updated response
     * Usefull to get back response in controller since response is immutable
     *
     * @return Response
     */
    public function getUpdatedResponse()
    {
        return $this->_response;
    }
}
