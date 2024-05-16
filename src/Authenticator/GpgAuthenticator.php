<?php
declare(strict_types=1);

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
namespace App\Authenticator;

use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\User;
use App\Service\OpenPGP\PublicKeyValidationService;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Authentication\Authenticator\Result;
use Authentication\Authenticator\ResultInterface;
use Authentication\Authenticator\SessionAuthenticator;
use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;
use Exception;
use Psr\Http\Message\ServerRequestInterface;

class GpgAuthenticator extends SessionAuthenticator
{
    use GpgAuthenticatorTrait;

    public const HTTP_HEADERS_WHITELIST = [
        'X-GPGAuth-Verify-Response',
        'X-GPGAuth-Progress',
        'X-GPGAuth-User-Auth-Token',
        'X-GPGAuth-Authenticated',
        'X-GPGAuth-Refer',
        'X-GPGAuth-Debug',
        'X-GPGAuth-Error',
        'X-GPGAuth-Pubkey',
        'X-GPGAuth-Logout-Url',
        'X-GPGAuth-Version',
    ];

    public const AUTHENTICATION_REQUIRED_MESSAGE = 'You need to login to access this location.';

    /**
     * @var array loaded from Configure::read('GPG')
     * @access protected
     */
    protected $_config;

    /**
     * @var \App\Utility\OpenPGP\OpenPGPBackendInterface instance
     * @access protected
     */
    protected $_gpg;

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @var string additional debug info
     */
    protected $_debug;

    /**
     * @var array|null
     */
    protected $_data;

    /**
     * @var \App\Model\Entity\User
     */
    protected $_user;

    /**
     * When an unauthenticated user tries to access a protected page this method is called
     *
     * @param \Cake\Http\ServerRequest $request interface for accessing request parameters
     * @param \Cake\Http\Response $response features and functionality for generating HTTP responses
     * @throws \Cake\Http\Exception\ForbiddenException
     * @return void
     */
    public function unauthenticated(ServerRequest $request, Response $response)
    {
        // If it's JSON we show an error message
        if ($request->is('json')) {
            throw new ForbiddenException(__('You need to login to access this location.'));
        }
        // Otherwise we let the controller handle the redirections
    }

    /**
     * Return a failed result with the headers collected in the
     * messages result property. These headers are then translated into actual http headers
     * in the GpgAuthHeadersMiddleware
     *
     * @see GpgAuthHeadersMiddleware
     * @param string $status Result::FAILURE_*
     * @return \Authentication\Authenticator\ResultInterface
     */
    private function authenticationFailedResult(string $status): ResultInterface
    {
        return new Result(null, $status, $this->headers);
    }

    /**
     * Return a success result with the headers collected in the
     * messages result property. These headers are then translated into actual http headers
     * in the GpgAuthHeadersMiddleware
     *
     * @see GpgAuthHeadersMiddleware
     * @return \Authentication\Authenticator\ResultInterface
     */
    private function authenticationSuccessResult(): ResultInterface
    {
        return new Result(['user' => $this->_user->toArray()], Result::SUCCESS, $this->headers);
    }

    /**
     * Authenticate
     * See. https://www.passbolt.com/help/tech/auth
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request interface for accessing request parameters
     * @return \Authentication\Authenticator\ResultInterface User|false the user or false if authentication failed
     */
    public function authenticate(ServerRequestInterface $request): ResultInterface
    {
        try {
            /** @var \Cake\Http\ServerRequest $request */
            // Init keyring and try to pre-identify the user using fingerprint
            if (!$this->_initForAllSteps($request)) {
                return $this->authenticationFailedResult(Result::FAILURE_IDENTITY_NOT_FOUND);
            }

            // Step 0. Server authentication
            // The user is asking the server to identify itself by decrypting a token
            // that was encrypted by the client using the server public key
            if (isset($this->_data['server_verify_token'])) {
                $this->_stage0();

                return $this->authenticationFailedResult(Result::FAILURE_CREDENTIALS_MISSING);
            }

            // Stage 1.
            // The user request an authentication by claiming he owns a given public key
            // We therefore send an encrypted message that must be returned next time in order to verify
            if (!isset($this->_data['user_token_result'])) {
                $this->_stage1();

                return $this->authenticationFailedResult(Result::FAILURE_CREDENTIALS_MISSING);
            }

            // Stage 2.
            // Check if the token provided at stage 1 have been decrypted and is still valid
            if (!$this->_stage2()) {
                return $this->authenticationFailedResult(Result::FAILURE_CREDENTIALS_INVALID);
            }
        } catch (InternalErrorException $exception) {
            return $this->authenticationFailedResult(Result::FAILURE_OTHER);
        }

        // Return the user to be set as active
        return $this->authenticationSuccessResult();
    }

    /**
     * Step 0 - Server private key verification
     * Decrypt server_verify_token and set it X-GPGAuth-Verify-Response
     *
     * @return bool
     */
    private function _stage0()
    {
        // Sanity check
        $serverVerifyToken = $this->_data['server_verify_token'] ?? '';
        $this->assertGpgMessageIsValid(
            $this->_gpg,
            $serverVerifyToken,
            __('The server verify token is missing or invalid.')
        );

        // Decrypt and verify nonce
        try {
            $nonce = $this->_gpg->decrypt($serverVerifyToken);
            // check if the nonce is in valid format to avoid returning something sensitive decrypted
            if ($this->_checkNonce($nonce)) {
                $this->addHeader('X-GPGAuth-Verify-Response', $nonce);
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
     * @throws \Cake\Http\Exception\InternalErrorException
     * @return bool
     */
    private function _stage1()
    {
        $this->addHeader('X-GPGAuth-Progress', 'stage1');

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
        /** @var \App\Model\Table\AuthenticationTokensTable $AuthenticationToken */
        $AuthenticationToken = TableRegistry::getTableLocator()->get('AuthenticationTokens');
        $authenticationToken = $AuthenticationToken->generate($this->_user->id, AuthenticationToken::TYPE_LOGIN);
        if (!isset($authenticationToken->token)) {
            return $this->_error(__('Failed to create token.'));
        }

        // encrypt and sign and send
        $token = 'gpgauthv1.3.0|36|' . $authenticationToken->token . '|gpgauthv1.3.0';
        $msg = $this->_gpg->encrypt($token, true);
        $msg = quotemeta(urlencode($msg));
        $this->addHeader('X-GPGAuth-User-Auth-Token', $msg);

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
        $this->addHeader('X-GPGAuth-Progress', 'stage2');
        if (!$this->_checkNonce($this->_data['user_token_result'])) {
            return $this->_error(__('The user token result should be a valid UUID.'));
        }

        // extract the UUID to get the database records
        [$version, $length, $uuid, $version2] = explode('|', $this->_data['user_token_result']);

        /** @var \App\Model\Table\AuthenticationTokensTable $AuthenticationToken */
        $AuthenticationToken = TableRegistry::getTableLocator()->get('AuthenticationTokens');
        $isValidToken = $AuthenticationToken->isValid($uuid, $this->_user->id, AuthenticationToken::TYPE_LOGIN);
        if (!$isValidToken) {
            return $this->_error(__('The user token result could not be found ') .
                't=' . $uuid . ' u=' . $this->_user->id);
        }

        // All good!
        $AuthenticationToken->setInactive($uuid);
        $this
            ->addHeader('X-GPGAuth-Progress', 'complete')
            ->addHeader('X-GPGAuth-Authenticated', 'true')
            ->addHeader('X-GPGAuth-Refer', '/');

        return true;
    }

    /**
     * Common initialization for all steps
     *
     * @param \Cake\Http\ServerRequest $request request
     * @throws \Cake\Http\Exception\InternalErrorException when the key is not valid
     * @return bool
     */
    private function _initForAllSteps(ServerRequest $request): bool
    {
        $this
            ->addHeader('X-GPGAuth-Authenticated', 'false')
            ->addHeader('X-GPGAuth-Progress', 'stage0');

        $this->_normalizeRequestData($request);
        $this->_initKeyring();

        // Begin process by checking if the user exist and his key is valid
        $this->_user = $this->_identifyUserWithFingerprint();
        if ($this->_user === null) {
            $this->_missingUserError();

            return false;
        }

        return true;
    }

    /**
     * Initialize OpenPGP keyring and load the config
     *
     * @throws \Cake\Http\Exception\InternalErrorException if config is missing or key is not set nor usable to decrypt
     * @return void
     */
    private function _initKeyring()
    {
        // check if the default key is set and available in gpg
        $this->_gpg = OpenPGPBackendFactory::get();
        $fingerprint = Configure::read('passbolt.gpg.serverKey.fingerprint');
        $passphrase = Configure::read('passbolt.gpg.serverKey.passphrase');

        // Check if config contains fingerprint
        if (!is_string($fingerprint) || !PublicKeyValidationService::isValidFingerprint($fingerprint)) {
            throw new InternalErrorException('The GnuPG config for the server is not available or incomplete.');
        }

        // set the key to be used for decrypting
        try {
            $this->_gpg->setDecryptKeyFromFingerprint($fingerprint, $passphrase);
        } catch (Exception $exception) {
            try {
                $this->_gpg->importServerKeyInKeyring();
                $this->_gpg->setDecryptKeyFromFingerprint($fingerprint, $passphrase);
            } catch (Exception $exception) {
                $msg = __('The OpenPGP server key defined in the config cannot be used to decrypt.') . ' ';
                $msg .= $exception->getMessage();
                throw new InternalErrorException($msg, 500, $exception);
            }
        }
    }

    /**
     * Set user key for encryption and import it in the keyring if needed
     *
     * @param string $fingerprint fingerprint
     * @throws \Cake\Http\Exception\InternalErrorException when the key is not valid
     * @return void
     */
    private function _initUserKey(string $fingerprint): void
    {
        try {
            $this->_gpg->setEncryptKeyFromFingerprint($fingerprint);
        } catch (Exception $exception) {
            // Try to import the key in keyring again
            try {
                $this->_gpg->importKeyIntoKeyring($this->_user->gpgkey->armored_key);
                $this->_gpg->setEncryptKeyFromFingerprint($fingerprint);
            } catch (Exception $exception) {
                $msg = __('Could not import the user OpenPGP key.');
                throw new InternalErrorException($msg, 500, $exception);
            }
        }
    }

    /**
     * Find a user record from a public key fingerprint
     *
     * @return \App\Model\Entity\User|null
     */
    private function _identifyUserWithFingerprint(): ?User
    {
        // First we check if we can get the user with the key fingerprint
        if (!isset($this->_data['keyid']) || !is_string($this->_data['keyid'])) {
            $this->_debug('No key id set.');

            return null;
        }

        // validate the fingerprint format
        $fingerprint = strtoupper($this->_data['keyid']);
        if (!PublicKeyValidationService::isValidFingerprint($fingerprint)) {
            $this->_debug('Invalid fingerprint.');

            return null;
        }

        // try to find the user
        /** @var \App\Model\Table\UsersTable $Users */
        $Users = TableRegistry::getTableLocator()->get('Users');

        /** @var \App\Model\Entity\User $user */
        $user = $Users->find('auth', ['fingerprint' => $fingerprint])->first();
        if (empty($user) || $user->isDisabled()) {
            $this->_debug('User not found.');

            return null;
        }

        return $user;
    }

    /**
     * Set a debug message in header if debug is enabled
     *
     * @param string|null $s debug message
     * @return void
     */
    private function _debug(?string $s): void
    {
        $this->_debug = $s;
        if (isset($s) && Configure::read('debug')) {
            $this->addHeader('X-GPGAuth-Debug', $s);
        }
    }

    /**
     * Trigger a GPGAuth Error
     *
     * @param string|null $msg the error message
     * @return bool always false, that will be used as authenticated method final result
     */
    private function _error(?string $msg): bool
    {
        $this->_debug($msg);
        $this->addHeader('X-GPGAuth-Error', 'true');

        return false;
    }

    /**
     * Validate the format of the nonce
     *
     * @param mixed $nonce Valid nonce example: 'gpgauthv1.3.0|36|de305d54-75b4-431b-adb2-eb6b9e546014|gpgauthv1.3.0'
     * @return bool true if valid, false otherwise
     */
    private function _checkNonce($nonce): bool
    {
        if (!is_string($nonce)) {
            return $this->_error(__('Invalid verify token type.'));
        }

        $result = explode('|', $nonce);
        $errorMsg = __('Invalid verify token format, ');
        if (count($result) != 4) {
            return $this->_error($errorMsg . __('sections are missing or using wrong delimiters.'));
        }
        [$version, $length, $uuid, $version2] = $result;
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
            return $this->_error($errorMsg . __('wrong token data length.'));
        }

        return true;
    }

    /**
     * Normalize request data
     *
     * @param \Cake\Http\ServerRequest $request Request
     * @return array|null
     */
    private function _normalizeRequestData(ServerRequest $request): ?array
    {
        $data = $request->getData();
        if (isset($data['data'])) {
            $data = $data['data'];
        }
        if (isset($data['gpg_auth']) && is_array($data['gpg_auth'])) {
            $this->_data = $data['gpg_auth'];
        } else {
            $this->_data = null;
        }

        return $this->_data;
    }

    /**
     * Handle missing user error
     *
     * @return void
     */
    private function _missingUserError(): void
    {
        // If the user doesn't exist, we want to mention it in the debug anyway (no matter we are in debug mode or not)
        // IMPORTANT : Do not change this behavior. Exceptionally here, the client will need to know that
        // we are in this case to be able to render a proper feedback.
        $msg = __('There is no user associated with this key.') . ' ' . $this->_debug;
        $this->_error($msg);
        $this->addHeader('X-GPGAuth-Debug', $msg);
    }

    /**
     * Collect the messages displayed in the head of the response.
     *
     * @param string $name Header name.
     * @param string $msg Header message.
     * @return $this
     */
    public function addHeader(string $name, string $msg)
    {
        $this->headers[$name] = $msg;

        return $this;
    }
}
