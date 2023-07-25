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
 * @since         3.3.0
 */
namespace Passbolt\JwtAuthentication\Authenticator;

use App\Middleware\ContainerAwareMiddlewareTrait;
use App\Model\Entity\Role;
use App\Model\Entity\User;
use App\Service\OpenPGP\PublicKeyValidationService;
use App\Utility\OpenPGP\Exceptions\InvalidSignatureException;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Authentication\Authenticator\AbstractAuthenticator;
use Authentication\Authenticator\Result;
use Authentication\Authenticator\ResultInterface;
use Cake\Core\Configure;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Validation\Validation;
use Passbolt\JwtAuthentication\Error\Exception\Challenge\InvalidDomainException;
use Passbolt\JwtAuthentication\Error\Exception\Challenge\InvalidUserSignatureException;
use Passbolt\JwtAuthentication\Service\VerifyToken\VerifyTokenValidationService;
use Psr\Http\Message\ServerRequestInterface;

class GpgJwtAuthenticator extends AbstractAuthenticator
{
    use ContainerAwareMiddlewareTrait;
    use EventDispatcherTrait;
    use \App\Authenticator\GpgAuthenticatorTrait;

    public const PROTOCOL_VERSION = '1.0.0';

    public const JWT_AUTHENTICATION_AFTER_IDENTIFY = 'jwt_authentication_after_identify';

    /**
     * @var \App\Utility\OpenPGP\OpenPGPBackend $gpg gpg backend
     * @access protected
     */
    protected $gpg;

    /**
     * @var \Cake\Http\ServerRequest $request request
     * @access protected
     */
    protected $request;

    /**
     * @var \App\Model\Entity\User $user user
     * @access protected
     */
    protected $user;

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
     * Authenticate
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request interface for accessing request parameters
     * @return \Authentication\Authenticator\ResultInterface User|false the user or false if authentication failed
     */
    public function authenticate(ServerRequestInterface $request): ResultInterface
    {
        /** @var \Cake\Http\ServerRequest $request */

        try {
            $this->setRequest($request);
            $this->init();
            $verifyToken = $this->verifyChallenge();

            return $this->successResult($verifyToken);
        } catch (\InvalidArgumentException $exception) {
            return $this->errorResult($exception, ResultInterface::FAILURE_CREDENTIALS_MISSING);
        } catch (NotFoundException $exception) {
            return $this->errorResult($exception, ResultInterface::FAILURE_IDENTITY_NOT_FOUND);
        } catch (BadRequestException $exception) {
            return $this->errorResult($exception, ResultInterface::FAILURE_CREDENTIALS_INVALID);
        } catch (\Exception $exception) {
            return $this->errorResult($exception, ResultInterface::FAILURE_OTHER);
        }
    }

    /**
     * Authentication process initialization
     *
     * @throws \Cake\Http\Exception\InternalErrorException if the server or user keys cannot be loaded
     * @throws \Cake\Http\Exception\BadRequestException if the user data is not valid, if the user id is not provided
     * @throws \Cake\Http\Exception\NotFoundException if the user cannot be found, is deleted, is not active
     * @return void
     */
    public function init(): void
    {
        $this->setOpenPGPBackend();
        $this->setServerKey();
        $this->loadUserData();
        $this->setUserKey();
    }

    /**
     * Format success results
     *
     * @param string $verifyToken token
     * @return \Authentication\Authenticator\Result
     * @access private
     */
    public function successResult(string $verifyToken): Result
    {
        $armoredChallenge = $this->makeArmoredChallenge($verifyToken);
        $data = ['challenge' => $armoredChallenge, 'user' => $this->user];

        return new Result($data, ResultInterface::SUCCESS);
    }

    /**
     * Create an encrypted challenge.
     *
     * @param string $verifyToken verify token.
     * @return string encrypted challenge
     */
    public function makeArmoredChallenge(string $verifyToken): string
    {
        /** @var \Passbolt\JwtAuthentication\Authenticator\JwtArmoredChallengeInterface $armoredChallengeService */
        $armoredChallengeService = $this->getContainer($this->getRequest())->get(JwtArmoredChallengeInterface::class);
        $challenge = $armoredChallengeService->makeArmoredChallenge($this->request, $this->user, $verifyToken);

        $this->dispatchEvent(self::JWT_AUTHENTICATION_AFTER_IDENTIFY, $challenge, $this);

        return $this->gpg->encryptSign(json_encode($challenge));
    }

    /**
     * Format error result
     * Log additional information about the error for the administrator
     *
     * @param \Exception $exception exception
     * @param string $reason example Result::FAILURE_CREDENTIALS_MISSING
     * @return \Authentication\Authenticator\Result
     * @access private
     */
    public function errorResult(\Exception $exception, string $reason): Result
    {
        Log::error($exception->getMessage());

        return new Result(null, $reason);
    }

    /**
     * @throws \Cake\Http\Exception\InternalErrorException if backend cannot be loaded
     * @return void
     */
    public function setOpenPGPBackend(): void
    {
        $this->gpg = OpenPGPBackendFactory::get();
    }

    /**
     * @throws \Cake\Http\Exception\InternalErrorException if the server key cannot be loaded
     * @return void
     */
    public function setServerKey(): void
    {
        // Check if config contains fingerprint
        $fingerprint = Configure::read('passbolt.gpg.serverKey.fingerprint');
        $this->assertServerFingerprint($fingerprint);

        // Check if config contains valid passphrase
        $passphrase = Configure::read('passbolt.gpg.serverKey.passphrase');
        $this->assertServerPassphrase($passphrase);

        // set the key to be used for decrypting
        try {
            $this->gpg->setSignKeyFromFingerprint($fingerprint, $passphrase);
            $this->gpg->setDecryptKeyFromFingerprint($fingerprint, $passphrase);
        } catch (\Exception $exception) {
            try {
                $this->gpg->importServerKeyInKeyring();
                $this->gpg->setSignKeyFromFingerprint($fingerprint, $passphrase);
                $this->gpg->setDecryptKeyFromFingerprint($fingerprint, $passphrase);
            } catch (\Exception $exception) {
                $msg = __('The OpenPGP server key defined in the config cannot be used to decrypt.') . ' ';
                $msg .= $exception->getMessage();
                throw new InternalErrorException($msg, 500, $exception);
            }
        }
    }

    /**
     * Set user key
     *
     * @throws \Cake\Http\Exception\BadRequestException if the user data is not valid
     * @throws \Cake\Http\Exception\InternalErrorException if the user key cannot be loaded
     * @return void
     */
    public function setUserKey(): void
    {
        try {
            $this->gpg->setVerifyKeyFromFingerprint($this->user->gpgkey->fingerprint);
            $this->gpg->setEncryptKeyFromFingerprint($this->user->gpgkey->fingerprint);
        } catch (\Exception $exception) {
            // Try to import the key in keyring again
            try {
                $this->gpg->importKeyIntoKeyring($this->user->gpgkey->armored_key);
                $this->gpg->setVerifyKeyFromFingerprint($this->user->gpgkey->fingerprint);
                $this->gpg->setEncryptKeyFromFingerprint($this->user->gpgkey->fingerprint);
            } catch (\Exception $exception) {
                $msg = __('Could not import the user OpenPGP key.');
                throw new InternalErrorException($msg, 500, $exception);
            }
        }
    }

    /**
     * Load user data including OpenPGP key in $user props
     *
     * @throws \Cake\Http\Exception\BadRequestException if the user id is missing in the request
     * @throws \Cake\Http\Exception\NotFoundException if the user cannot be found, is deleted, is not active
     * @return void
     * @access private
     */
    public function loadUserData(): void
    {
        $userId = $this->request->getData('user_id');
        $this->assertUserId($userId);
        $userData = $this->findUser($userId);
        $this->assertUserData($userData);
        $this->user = $userData;
    }

    /**
     * @param string $userId uuid
     * @throws \Cake\Http\Exception\NotFoundException if the user cannot be found, is deleted, is not active
     * @return \App\Model\Entity\User
     */
    private function findUser(string $userId): User
    {
        try {
            /** @var \App\Model\Table\UsersTable $Users */
            $Users = TableRegistry::getTableLocator()->get('Users');

            /** @var \App\Model\Entity\User|null $userData */
            $userData = $Users->findView($userId, Role::GUEST)
                ->contain('Gpgkeys')
                ->first();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            throw new NotFoundException(__('The user does not exist or has been deleted.'));
        }

        if (!isset($userData)) {
            throw new NotFoundException(__('The user does not exist or has been deleted.'));
        }

        if ($userData->isDisabled()) {
            throw new NotFoundException(__('The user does not exist or has been deleted.'));
        }

        return $userData;
    }

    /**
     * @throws \InvalidArgumentException if the challenge is missing
     * @throws \Cake\Http\Exception\BadRequestException if the challenge is invalid
     * @return string
     */
    public function verifyChallenge(): string
    {
        // Sanity check
        $armoredChallenge = $this->request->getData('challenge');
        $this->assertArmoredChallenge($armoredChallenge);

        // Decrypt and verify signature
        try {
            $clearTextChallenge = $this->gpg->decrypt($armoredChallenge, true);
        } catch (InvalidSignatureException $exception) {
            Log::error($exception->getMessage());
            throw new InvalidUserSignatureException(__('The user signature could not be verified.'));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            throw new BadRequestException(__('The challenge cannot be decrypted.'));
        }

        // Deserialize JSON
        try {
            $jsonChallenge = json_decode($clearTextChallenge, true, 2, JSON_THROW_ON_ERROR);
            [
                'version' => $version,
                'domain' => $domain,
                'verify_token' => $verifyToken,
                'verify_token_expiry' => $verifyTokenExpiry,
            ] = $jsonChallenge;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage() . "\n" . $clearTextChallenge);
            throw new BadRequestException(__('The challenge is invalid. Deserialization failed.'));
        }

        // Challenge sanity check

        // If domain is not known, let the exception be thrown. It will send email alerts.
        $this->assertDomain($domain);
        try {
            $this->assertVersion($version);
            (new VerifyTokenValidationService())->validateToken(
                $verifyTokenExpiry,
                $verifyToken,
                $this->request->getData('user_id')
            );
        } catch (\Exception $exception) {
            Log::error($exception->getMessage() . "\n" . $clearTextChallenge);
            throw new BadRequestException(__('The challenge is invalid. Validation Failed.'));
        }

        return $verifyToken;
    }

    /**
     * @param mixed $fingerprint fingerprint
     * @throws \Cake\Http\Exception\InternalErrorException
     * @return void
     */
    public function assertServerFingerprint($fingerprint): void
    {
        if (!is_string($fingerprint) || !PublicKeyValidationService::isValidFingerprint($fingerprint)) {
            $msg = __('The config for the server private key fingerprint is not available or incomplete.');
            throw new InternalErrorException($msg);
        }
    }

    /**
     * @param mixed $passphrase passphrase
     * @throws \Cake\Http\Exception\InternalErrorException
     * @return void
     */
    public function assertServerPassphrase($passphrase): void
    {
        if (!is_string($passphrase)) {
            $msg = __('The config for the server private key passphrase is invalid.');
            throw new InternalErrorException($msg);
        }
    }

    /**
     * @param mixed $userId uuid
     * @throws \Cake\Http\Exception\BadRequestException
     * @return void
     */
    public function assertUserId($userId): void
    {
        if (!is_string($userId) || !Validation::uuid($userId)) {
            $msg = __('The user id is missing or invalid.');
            throw new BadRequestException($msg);
        }
    }

    /**
     * @param mixed $userData data
     * @throws \Cake\Http\Exception\BadRequestException
     * @return void
     */
    public function assertUserData($userData): void
    {
        if (
            !isset($userData->gpgkey) ||
            !isset($userData->gpgkey->fingerprint) ||
            !isset($userData->gpgkey->armored_key) ||
            !is_string($userData->gpgkey->fingerprint) ||
            !PublicKeyValidationService::isValidFingerprint($userData->gpgkey->fingerprint) ||
            !is_string($userData->gpgkey->armored_key)
        ) {
            $msg = __('The user OpenPGP key does not exist, or is invalid, or has been deleted.');
            throw new BadRequestException($msg);
        }
    }

    /**
     * @param mixed $armoredChallenge challenge
     * @throws \InvalidArgumentException if armored challenge is invalid
     * @return void
     */
    public function assertArmoredChallenge($armoredChallenge): void
    {
        $this->assertGpgMessageIsValid($this->gpg, $armoredChallenge, __('The user challenge is missing or invalid.'));
    }

    /**
     * @param mixed $version version
     * @throws \Exception if version is not supported
     * @return void
     */
    public function assertVersion($version): void
    {
        if (!isset($version) || !is_string($version) || $version !== self::PROTOCOL_VERSION) {
            throw new \Exception(__('The version is invalid.'));
        }
    }

    /**
     * Assert domain
     *
     * @param mixed $domain domain
     * @return void
     * @throws \Passbolt\JwtAuthentication\Error\Exception\Challenge\InvalidDomainException if domain is invalid
     */
    public function assertDomain($domain): void
    {
        if (!isset($domain) || !is_string($domain)) {
            throw new InvalidDomainException(__('The domain is invalid.'));
        }

        if (rtrim($domain, '/') !== rtrim(Router::url('/', true), '/')) {
            $expect = rtrim(Router::url('/', true));
            $got = rtrim($domain, '/');
            throw new InvalidDomainException(__('The domain is invalid. Expected: {0} and got {1}', $expect, $got));
        }
    }

    /**
     * @return \App\Utility\OpenPGP\OpenPGPBackend
     */
    public function getGpg(): \App\Utility\OpenPGP\OpenPGPBackend
    {
        return $this->gpg;
    }

    /**
     * @return \App\Model\Entity\User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @return \Cake\Http\ServerRequest
     */
    public function getRequest(): ServerRequest
    {
        return $this->request;
    }

    /**
     * @param \Cake\Http\ServerRequest $request Server request
     * @return void
     */
    public function setRequest(ServerRequest $request): void
    {
        $this->request = $request;
    }
}
