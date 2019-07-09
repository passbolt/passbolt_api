<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
namespace App\Auth;

use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\User;
use App\Model\Table\AuthenticationTokensTable;
use App\Model\Table\GpgkeysTable;
use App\Model\Table\UsersTable;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use App\Utility\OpenPGP\OpenPGPBackendInterface;
use Cake\Auth\BaseAuthenticate;
use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;
use Exception;

class GpgAuthenticate extends BaseAuthenticate
{
    const HTTP_HEADERS_WHITELIST = 'X-GPGAuth-Verify-Response, X-GPGAuth-Progress, X-GPGAuth-User-Auth-Token, ' .
        'X-GPGAuth-Authenticated, X-GPGAuth-Refer, X-GPGAuth-Debug, X-GPGAuth-Error, X-GPGAuth-Pubkey, ' .
        'X-GPGAuth-Logout-Url, X-GPGAuth-Version';

    /**
     * @var $_config array loaded from Configure::read('GPG')
     * @access protected
     */
    protected $_config;

    /**
     * @var $_gpg OpenPGPBackendInterface instance
     * @access protected
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
     * @var User $_user
     */
    protected $_user;

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
        // Otherwise we let the controller handle it
    }

    /**
     * Authenticate
     * See. https://www.passbolt.com/help/tech/auth
     *
     * @param ServerRequest $request interface for accessing request parameters
     * @param Response $response features and functionality for generating HTTP responses
     * @throws InternalErrorException if the config or key is not set or not usable
     * @return mixed User|false the user or false if authentication failed
     */
    public function authenticate(ServerRequest $request, Response $response)
    {
        if (!$this->_initForAllSteps($request, $response)) {
            return false;
        }

        // Step 0. Server authentication
        // The user is asking the server to identify itself by decrypting a token
        // that was encrypted by the client using the server public key
        if (isset($this->_data['server_verify_token'])) {
            $this->_stage0();

            return false;
        }

        // Stage 1.
        // The user request an authentication by claiming he owns a given public key
        // We therefore send an encrypted message that must be returned next time in order to verify
        if (!isset($this->_data['user_token_result'])) {
            $this->_stage1();

            return false;
        } else {
            // Stage 2.
            // Check if the token provided at stage 1 have been decrypted and is still valid
            if (!$this->_stage2()) {
                return false;
            }
        }

        // Return the user to be set as active
        return $this->_user->toArray();
    }

    /**
     * Step 0 - Server private key verification
     * Decrypt server_verify_token and set it X-GPGAuth-Verify-Response
     *
     * @return bool
     */
    private function _stage0()
    {
        try {
            $nonce = $this->_gpg->decrypt($this->_data['server_verify_token']);
            // check if the nonce is in valid format to avoid returning something sensitive decrypted
            if ($this->_checkNonce($nonce)) {
                $this->_response = $this->_response->withHeader('X-GPGAuth-Verify-Response', $nonce);
            }
        } catch (Exception $e) {
            return $this->_error(__('Decryption failed.'));
        }

        return true;
    }

    /**
     * Stage 1 - Client private key verification
     * Generate a random number, encrypt and send it back for the user to decrypts
     *
     * @throws InternalErrorException
     * @return bool
     */
    private function _stage1()
    {
        $this->_response = $this->_response->withHeader('X-GPGAuth-Progress', 'stage1');

        // set encryption and signature keys
        try {
            $this->_initUserKey($this->_data['keyid']);
        } catch (Exception $e) {
            return $this->_error($e->getMessage());
        }

        $this->_gpg->setSignKeyFromFingerprint(
            Configure::read('passbolt.gpg.serverKey.fingerprint'),
            Configure::read('passbolt.gpg.serverKey.passphrase')
        );

        // generate the authentication token
        /** @var AuthenticationTokensTable $AuthenticationToken */
        $AuthenticationToken = TableRegistry::getTableLocator()->get('AuthenticationTokens');
        $authenticationToken = $AuthenticationToken->generate($this->_user->id, AuthenticationToken::TYPE_LOGIN);
        if (!isset($authenticationToken->token)) {
            return $this->_error(__('Failed to create token.'));
        }

        // encrypt and sign and send
        $token = 'gpgauthv1.3.0|36|' . $authenticationToken->token . '|gpgauthv1.3.0';
        $msg = $this->_gpg->encrypt($token, true);
        $msg = quotemeta(urlencode($msg));
        $this->_response = $this->_response->withHeader('X-GPGAuth-User-Auth-Token', $msg);

        return true;
    }

    /**
     * Stage 2
     * Check if the token provided at stage 1 have been decrypted and is still valid
     *
     * @return bool
     */
    private function _stage2()
    {
        //ControllerLog::write(Status::DEBUG, $request, 'authenticate_stage_2', '');
        $this->_response = $this->_response->withHeader('X-GPGAuth-Progress', 'stage2');
        if (!($this->_checkNonce($this->_data['user_token_result']))) {
            return $this->_error(__('The user token result is not a valid UUID.'));
        }

        // extract the UUID to get the database records
        list($version, $length, $uuid, $version2) = explode('|', $this->_data['user_token_result']);

        /** @var AuthenticationTokensTable $AuthenticationToken */
        $AuthenticationToken = TableRegistry::getTableLocator()->get('AuthenticationTokens');
        $isValidToken = $AuthenticationToken->isValid($uuid, $this->_user->id, AuthenticationToken::TYPE_LOGIN);
        if (!$isValidToken) {
            return $this->_error(__('The user token result could not be found ') .
                't=' . $uuid . ' u=' . $this->_user->id);
        }

        // All good!
        $AuthenticationToken->setInactive($uuid);
        $this->_response = $this->_response
            ->withHeader('X-GPGAuth-Progress', 'complete')
            ->withHeader('X-GPGAuth-Authenticated', 'true')
            ->withHeader('X-GPGAuth-Refer', '/');

        return true;
    }

    /**
     * Common initialization for all steps
     *
     * @param ServerRequest $request request
     * @param Response $response response
     * @throws InternalErrorException when the key is not valid
     * @return bool
     */
    private function _initForAllSteps(ServerRequest $request, Response $response)
    {
        $this->_response = $response
            ->withHeader('X-GPGAuth-Authenticated', 'false')
            ->withHeader('X-GPGAuth-Progress', 'stage0');

        $this->_normalizeRequestData($request);
        $this->_initKeyring();

        // Begin process by checking if the user exist and his key is valid
        $this->_user = $this->_identifyUserWithFingerprint();
        if ($this->_user === false) {
            $this->_missingUserError();

            return false;
        }

        return true;
    }

    /**
     * Initialize GPG keyring and load the config
     *
     * @throws InternalErrorException if the config is missing or key is not set or not usable to decrypt
     * @return void
     */
    private function _initKeyring()
    {
        // check if the default key is set and available in gpg
        $this->_gpg = OpenPGPBackendFactory::get();
        $fingerprint = Configure::read('passbolt.gpg.serverKey.fingerprint');

        // Check if config contains fingerprint
        if (!GpgkeysTable::isValidFingerprint($fingerprint)) {
            throw new InternalErrorException(__('The GnuPG config for the server is not available or incomplete.'));
        }

        // set the key to be used for decrypting
        try {
            $this->_gpg->setDecryptKeyFromFingerprint($fingerprint, Configure::read('passbolt.gpg.serverKey.passphrase'));
        } catch (Exception $exception) {
            try {
                $this->_gpg->importServerKeyInKeyring();
                $this->_gpg->setDecryptKeyFromFingerprint($fingerprint, Configure::read('passbolt.gpg.serverKey.passphrase'));
            } catch (Exception $exception) {
                $msg = __('The OpenPGP server key defined in the config cannot be used to decrypt.') . ' ';
                $msg .= $exception->getMessage();
                throw new InternalErrorException($msg);
            }
        }
    }

    /**
     * Set user key for encryption and import it in the keyring if needed
     *
     * @param string $fingerprint fingerprint
     * @throws InternalErrorException when the key is not valid
     * @return void
     */
    private function _initUserKey(string $fingerprint)
    {
        try {
            $this->_gpg->setEncryptKeyFromFingerprint($fingerprint);
        } catch (Exception $exception) {
            // Try to import the key in keyring again
            try {
                $this->_gpg->importKeyIntoKeyring($this->_user->gpgkey->armored_key);
                $this->_gpg->setEncryptKeyFromFingerprint($fingerprint);
            } catch (Exception $exception) {
                throw new InternalErrorException(__('The OpenPGP key for the user could not be imported in GnuPG.'));
            }
        }
    }

    /**
     * Find a user record from a public key fingerprint
     *
     * @return mixed false or User
     */
    private function _identifyUserWithFingerprint()
    {
        // First we check if we can get the user with the key fingerprint
        if (!isset($this->_data['keyid'])) {
            $this->_debug('No key id set.');

            return false;
        }
        $fingerprint = strtoupper($this->_data['keyid']);

        // validate the fingerprint format
        /** @var GpgkeysTable $Gpgkeys */
        $Gpgkeys = TableRegistry::getTableLocator()->get('Gpgkeys');
        if (!$Gpgkeys->isValidFingerprintRule($fingerprint)) {
            $this->_debug('Invalid fingerprint.');

            return false;
        }

        // try to find the user
        /** @var UsersTable $Users */
        $Users = TableRegistry::getTableLocator()->get('Users');
        $user = $Users->find('auth', ['fingerprint' => $fingerprint])->first();
        if (empty($user)) {
            $this->_debug('User not found.');

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
    private function _debug($s = null)
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
    private function _error($msg = null)
    {
        $this->_debug($msg);
        $this->_response = $this->_response->withHeader('X-GPGAuth-Error', 'true');

        return false;
    }

    /**
     * Validate the format of the nonce
     *
     * @param string $nonce for example: 'gpgauthv1.3.0|36|de305d54-75b4-431b-adb2-eb6b9e546014|gpgauthv1.3.0'
     * @return bool true if valid, false otherwise
     */
    private function _checkNonce($nonce)
    {
        $result = explode('|', $nonce);
        $errorMsg = __('Invalid verify token format, ');
        if (count($result) != 4) {
            return $this->_error($errorMsg . __('sections are missing or using wrong delimiters.'));
        }
        list($version, $length, $uuid, $version2) = $result;
        if ($version != $version2) {
            return $this->_error($errorMsg . __('the version numbers do not match.'));
        }
        if ($version != 'gpgauthv1.3.0') {
            return $this->_error($errorMsg . __('wrong version number.'));
        }
        if ($version != Validation::uuid($uuid)) {
            return $this->_error($errorMsg . __('it is not a UUID.'));
        }
        if ($length != 36) {
            return $this->_error($errorMsg . __('using wrong token data length.'));
        }

        return true;
    }

    /**
     * Normalize request data
     *
     * @param object $request Request
     * @return array|null
     */
    private function _normalizeRequestData($request)
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

    /**
     * Handle missing user error
     *
     * @return void
     */
    private function _missingUserError()
    {
        // If the user doesn't exist, we want to mention it in the debug anyway (no matter we are in debug mode or not)
        // IMPORTANT : Do not change this behavior. Exceptionally here, the client will need to know that
        // we are in this case to be able to render a proper feedback.
        $msg = __('There is no user associated with this key.') . ' ' . $this->_debug;
        $this->_error($msg);
        $this->_response = $this->_response->withHeader('X-GPGAuth-Debug', $msg);
    }
}
