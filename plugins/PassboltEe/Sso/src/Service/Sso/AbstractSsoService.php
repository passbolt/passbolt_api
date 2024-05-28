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
 * @since         3.9.0
 */

namespace Passbolt\Sso\Service\Sso;

use App\Model\Entity\User;
use App\Model\Validation\EmailValidationRule;
use App\Service\Cookie\AbstractSecureCookieService;
use App\Service\Users\UserGetService;
use App\Utility\ExtendedUserAccessControl;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\Cookie\Cookie;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use Cake\Log\Log;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Passbolt\Sso\Model\Dto\SsoSettingsDto;
use Passbolt\Sso\Model\Entity\SsoAuthenticationToken;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Service\SsoAuthenticationTokens\SsoAuthenticationTokenSetService;
use Passbolt\Sso\Service\SsoStates\SsoStatesAssertService;
use Passbolt\Sso\Service\SsoStates\SsoStatesSetService;
use Passbolt\Sso\Utility\OpenId\SsoResourceOwnerInterface;

abstract class AbstractSsoService
{
    use EventDispatcherTrait;

    /**
     * @var \App\Service\Cookie\AbstractSecureCookieService $cookieService used to generate cookies
     */
    protected $cookieService;

    /**
     * @var \League\OAuth2\Client\Provider\AbstractProvider $provider used to cache provider
     */
    protected $provider;

    /**
     * @var \Passbolt\Sso\Model\Dto\SsoSettingsDto $settings
     */
    protected $settings;

    /**
     * @var string|null
     */
    protected $nonce = null;

    /**
     * Cookie name used to store the state
     */
    public const SSO_STATE_COOKIE = 'passbolt_sso_state';

    public const SSO_SECURITY_REDIRECT_METHOD_CONFIG = 'passbolt.plugins.sso.security.redirectMethod';

    /**
     * Build the url to redirect the user to
     *
     * @param \App\Utility\ExtendedUserAccessControl $uac user access control
     * @return string
     */
    abstract public function getAuthorizationUrl(ExtendedUserAccessControl $uac): string;

    /**
     * This function must return the OAuth provider that is used for this particular
     * service implementation, for example AzureProvider
     *
     * @param \Passbolt\Sso\Model\Dto\SsoSettingsDto $settings settings
     * @return \League\OAuth2\Client\Provider\AbstractProvider
     */
    abstract protected function getOAuthProvider(SsoSettingsDto $settings): AbstractProvider;

    /**
     * This function must get SsoSettings from Database and perform baseline assertions
     * Such as is the settings matching the provider we want to use in this context
     *
     * @return \Passbolt\Sso\Model\Dto\SsoSettingsDto
     */
    abstract protected function assertAndGetSsoSettings(): SsoSettingsDto;

    /**
     * Constructor
     *
     * @param \App\Service\Cookie\AbstractSecureCookieService $cookieService Cookie service
     * @param \Passbolt\Sso\Model\Dto\SsoSettingsDto|null $settingsDto setting
     */
    public function __construct(AbstractSecureCookieService $cookieService, ?SsoSettingsDto $settingsDto = null)
    {
        $this->cookieService = $cookieService;
        // settings must be initialized before provider to work
        $this->settings = $settingsDto ?? $this->assertAndGetSsoSettings();
        $this->provider = $this->getOAuthProvider($this->settings);
    }

    /**
     * @return \Passbolt\Sso\Model\Dto\SsoSettingsDto
     */
    public function getSettings(): SsoSettingsDto
    {
        return $this->settings;
    }

    /**
     * Build the cookie that will help preventing CSRF attacks on stage 2 (redirection from provider)
     * To build the cookie we need to get the state from the provider and save an authentication token
     * that matches the state
     *
     * @param \App\Utility\ExtendedUserAccessControl $uac user access control
     * @param string $type Type of state.
     * @return \Cake\Http\Cookie\Cookie
     */
    public function createStateCookie(ExtendedUserAccessControl $uac, string $type): Cookie
    {
        if ($this->provider->getState() === null) {
            // state is set in getAuthorizationUrl
            throw new InternalErrorException('Invalid use. State not set.');
        }

        // Store the OAuth state in sso state
        $ssoState = $this->createSsoState($this->provider->getState(), $uac, $this->settings->id, $type);

        // Build cookie that matches the OAuth state
        return $this->createHttpOnlySecureCookie($ssoState);
    }

    /**
     * Build an SSO state cookie that is expired to trigger delete in the browser
     *
     * @return \Cake\Http\Cookie\Cookie
     */
    public function clearStateCookie(): Cookie
    {
        return $this->cookieService->createExpired(
            self::SSO_STATE_COOKIE,
            'deleted',
            '/sso'
        );
    }

    /**
     * @param \Passbolt\Sso\Model\Entity\SsoState $ssoState SSO state.
     * @return \Cake\Http\Cookie\Cookie
     */
    protected function createHttpOnlySecureCookie(SsoState $ssoState): Cookie
    {
        return $this->cookieService->create(
            self::SSO_STATE_COOKIE,
            $ssoState->state,
            '/sso',
            $ssoState->getExpiryTime()
        );
    }

    /**
     * Check a given state against authentication token and extended user info
     *
     * @param \Passbolt\Sso\Model\Entity\SsoState $ssoState SSO state entity
     * @param string $code client ip
     * @param string $ip user agent
     * @param string $userAgent user agent
     * @throws \Cake\Http\Exception\BadRequestException If the user_id in SSO state is `null`.
     * @throws \Cake\Http\Exception\BadRequestException if the user does not exist or is inactive
     * @throws \Cake\Http\Exception\BadRequestException if resource owner username is not provider or does not match user entity
     * @return \App\Utility\ExtendedUserAccessControl
     */
    public function assertStateCodeAndGetUac(
        SsoState $ssoState,
        string $code,
        string $ip,
        string $userAgent
    ): ExtendedUserAccessControl {
        if ($ssoState->user_id === null) {
            throw new BadRequestException(__('The user is missing for the SSO state.'));
        }

        try {
            $user = (new UserGetService())->getActiveNotDeletedNotDisabledOrFail($ssoState->user_id);
        } catch (NotFoundException $exception) {
            throw new BadRequestException(__('The user does not exist or is not active.'), 400, $exception);
        }

        // Check the token against extended user info and consume it
        $uac = new ExtendedUserAccessControl($user->role->name, $user->id, $user->username, $ip, $userAgent);
        (new SsoStatesAssertService())->assertAndConsume($ssoState, $this->getSettings()->id, $uac);

        try {
            // Assert access request and if it matches current suer
            $resourceOwner = $this->getResourceOwnerAndAssertAgainstUser($code, $user);

            $this->assertResourceOwnerAgainstSsoState($resourceOwner, $ssoState);
        } catch (\Exception $e) {
            $msg = 'There was an error while asserting user against resource owner. ';
            $msg .= "Message: {$e->getMessage()}, State ID: {$ssoState->state}, User ID: {$user->id}";

            Log::error($msg);

            throw $e;
        }

        return $uac;
    }

    /**
     * @param string $code JWT access request
     * @param \App\Model\Entity\User $user entity
     * @return \Passbolt\Sso\Utility\OpenId\SsoResourceOwnerInterface
     * @throws \Passbolt\Sso\Error\Exception\AzureException If any error is thrown from azure provider
     * @throws \Cake\Http\Exception\BadRequestException if resource owner username is not provider or does not match user entity
     */
    public function getResourceOwnerAndAssertAgainstUser(string $code, User $user): SsoResourceOwnerInterface
    {
        $resourceOwner = $this->getResourceOwner($code);
        $this->assertResourceOwnerAgainstUser($resourceOwner, $user);

        return $resourceOwner;
    }

    /**
     * @param string $code authorization code to call OIDC endpoint
     * @return \Passbolt\Sso\Utility\OpenId\SsoResourceOwnerInterface
     * @throws \Cake\Http\Exception\InternalErrorException if resource owner does not implement ResourceOwnerWithEmailInterface
     * @throws \Cake\Http\Exception\BadRequestException if resource owner returned an error
     */
    public function getResourceOwner(string $code): SsoResourceOwnerInterface
    {
        try {
            // Try to get an access token using the authorization code grant.
            /** @var \League\OAuth2\Client\Token\AccessToken $accessToken */
            $accessToken = $this->provider->getAccessToken('authorization_code', ['code' => $code]);

            // Using the access token id_token, we may look up details about the resource owner.
            $resourceOwner = $this->provider->getResourceOwner($accessToken);
        } catch (IdentityProviderException $exception) {
            $msg = "Error while getting access token. Message: {$exception->getMessage()}, ";
            if (!is_string($exception->getResponseBody())) {
                $msg .= 'Response: ' . json_encode($exception->getResponseBody());
            } else {
                $msg .= "Response: {$exception->getResponseBody()}";
            }

            Log::error($msg);

            $msg = __('Single sign-on failed.') . ' ' . __('Provider error: "{0}"', $exception->getMessage());
            throw new BadRequestException($msg, 400, $exception);
        }

        // Helper for developers working on new providers
        if (!($resourceOwner instanceof SsoResourceOwnerInterface)) {
            $msg = 'Provider must return a ResourceOwner that implements ResourceOwnerWithEmailInterface.';
            throw new InternalErrorException($msg);
        }

        $email = $resourceOwner->getEmail();
        if (!isset($email) || !is_string($email) || !EmailValidationRule::check($email)) {
            $msg = __('Single sign-on failed.') . ' ' . __('Email not provided by provider.');
            throw new BadRequestException($msg);
        }

        return $resourceOwner;
    }

    /**
     * @param \Passbolt\Sso\Utility\OpenId\SsoResourceOwnerInterface $resourceOwner user
     * @param \App\Model\Entity\User $user user
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException if the assertion failed
     */
    public function assertResourceOwnerAgainstUser(SsoResourceOwnerInterface $resourceOwner, User $user): void
    {
        if (mb_strtolower($resourceOwner->getEmail()) !== mb_strtolower($user->username)) {
            $msg = __('Single sign-on failed.') . ' ' . __('Username mismatch.');
            throw new BadRequestException($msg);
        }
    }

    /**
     * @param \Passbolt\Sso\Utility\OpenId\SsoResourceOwnerInterface $resourceOwner Resource owner.
     * @param \Passbolt\Sso\Model\Entity\SsoState $ssoState SSO state.
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException if the assertion failed
     */
    public function assertResourceOwnerAgainstSsoState(
        SsoResourceOwnerInterface $resourceOwner,
        SsoState $ssoState
    ): void {
        if ($ssoState->nonce !== $resourceOwner->getNonce()) {
            $msg = __('Single sign-on failed.') . ' ' . __('Invalid nonce.');
            throw new BadRequestException($msg);
        }
    }

    /**
     * @param string $state uuid
     * @param \App\Utility\ExtendedUserAccessControl $uac extend user access control
     * @param string $settingsId uuid
     * @param string $type Type of state.
     * @return \Passbolt\Sso\Model\Entity\SsoState
     */
    public function createSsoState(
        string $state,
        ExtendedUserAccessControl $uac,
        string $settingsId,
        string $type
    ): SsoState {
        return (new SsoStatesSetService())->create(
            $this->nonce,
            $state,
            $type,
            $settingsId,
            $uac
        );
    }

    /**
     * @param \App\Utility\ExtendedUserAccessControl $uac extend user access control
     * @param string $settingsId uuid
     * @return \Passbolt\Sso\Model\Entity\SsoAuthenticationToken
     */
    public function createAuthTokenToGetKey(ExtendedUserAccessControl $uac, string $settingsId): SsoAuthenticationToken
    {
        return (new SsoAuthenticationTokenSetService())->createOrFail(
            $uac,
            SsoState::TYPE_SSO_GET_KEY,
            $settingsId
        );
    }

    /**
     * @param \App\Utility\ExtendedUserAccessControl $uac extend user access control
     * @param string $settingsId uuid
     * @return \Passbolt\Sso\Model\Entity\SsoAuthenticationToken
     */
    public function createAuthTokenToActiveSettings(
        ExtendedUserAccessControl $uac,
        string $settingsId
    ): SsoAuthenticationToken {
        return (new SsoAuthenticationTokenSetService())->createOrFail(
            $uac,
            SsoState::TYPE_SSO_SET_SETTINGS,
            $settingsId
        );
    }

    /**
     * Returns random ASCII string containing the hexadecimal representation of string value.
     *
     * @return string
     * @throws \Exception
     */
    protected function generateNonce(): string
    {
        $this->nonce = SsoState::generate();

        return $this->nonce;
    }
}
