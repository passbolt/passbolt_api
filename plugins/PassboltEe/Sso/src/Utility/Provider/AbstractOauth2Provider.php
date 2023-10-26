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
 * @since         4.0.0
 */

namespace Passbolt\Sso\Utility\Provider;

use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotImplementedException;
use Cake\Validation\Validation;
use Firebase\JWT\JWK;
use League\OAuth2\Client\Grant\AbstractGrant;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Passbolt\Sso\Utility\OpenId\BaseIdToken;

abstract class AbstractOauth2Provider extends AbstractProvider
{
    use BearerAuthorizationTrait;

    /**
     * @var string $openIdBaseUri typically the issuer, ex. https://my.idp.com with no trailing slashes
     */
    protected $openIdBaseUri;

    /**
     * @var string $openIdConfigurationPath for example '/moved/somewhere/else/.well-known/open-id-configuration'
     * default $openIdConfigurationPath path to .well-know/openid-configuration endpoint
     * See. https://openid.net/specs/openid-connect-discovery-1_0.html#ProviderConfig
     */
    protected $openIdConfigurationPath = '/.well-known/openid-configuration';

    /**
     * @var array|null see. AbstractOauth2Provider::getOpenIdConfiguration()
     */
    protected $openIdConfiguration = null;

    /**
     * @param array $response see BaseIdToken::getIdTokenClaims
     * @param \League\OAuth2\Client\Token\AccessToken $token unused
     * @return \League\OAuth2\Client\Provider\ResourceOwnerInterface
     */
    abstract protected function createResourceOwner(array $response, AccessToken $token): ResourceOwnerInterface;

    /**
     * Returns Open ID configuration URL.
     *
     * @return string
     */
    public function getOpenIdConfigurationUri(): string
    {
        $url = $this->getOpenIdBaseUri();
        $url .= $this->openIdConfigurationPath;

        return $url;
    }

    /**
     * Returns Open ID base URL.
     *
     * @return string
     */
    public function getOpenIdBaseUri(): string
    {
        return $this->openIdBaseUri;
    }

    /**
     * @inheritDoc
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        // Not needed, we will get the resource owner information from JWT token claims
        // And not the userinfo_endpoint
        throw new NotImplementedException();
    }

    /**
     * ABSTRACT METHODS
     *
     * @see \League\OAuth2\Client\Provider\AbstractProvider
     */

    /**
     * @inheritDoc
     */
    public function getBaseAuthorizationUrl(): string
    {
        $openIdConfiguration = $this->getOpenIdConfiguration();

        return $openIdConfiguration['authorization_endpoint'];
    }

    /**
     * @inheritDoc
     */
    public function getBaseAccessTokenUrl(array $params): string
    {
        $openIdConfiguration = $this->getOpenIdConfiguration();

        return $openIdConfiguration['token_endpoint'];
    }

    /**
     * @return array
     */
    protected function getOpenIdConfiguration(): array
    {
        if (isset($this->openIdConfiguration)) {
            return $this->openIdConfiguration;
        }

        $factory = $this->getRequestFactory();
        $request = $factory->getRequestWithOptions(
            'get',
            $this->getOpenIdConfigurationUri(),
            []
        );

        try {
            $response = $this->getParsedResponse($request);
        } catch (\Exception $exception) {
            throw new InternalErrorException($exception->getMessage(), 500, $exception);
        }

        $this->validateOpenIdConfiguration($response);
        $this->openIdConfiguration = $response;

        return $this->openIdConfiguration;
    }

    /**
     * Check the endpoints info we expect to use later are present
     *
     * @param mixed $response from .well-known
     * @return void
     */
    public function validateOpenIdConfiguration($response): void
    {
        if (!is_array($response)) {
            throw new InternalErrorException('Invalid response.');
        }
        if (!isset($response['jwks_uri'])) {
            throw new InternalErrorException('Invalid response. Missing JWKS URI');
        }
        if (!isset($response['authorization_endpoint'])) {
            throw new InternalErrorException('Invalid response. Missing authorization endpoint.');
        }
        if (!isset($response['token_endpoint'])) {
            throw new InternalErrorException('Invalid response. Missing token endpoint.');
        }
        if (!Validation::url($response['jwks_uri'])) {
            throw new InternalErrorException('Invalid response. Invalid JWKS URI');
        }
        if (!Validation::url($response['authorization_endpoint'])) {
            throw new InternalErrorException('Invalid response. Invalid authorization endpoint.');
        }
        if (!Validation::url($response['token_endpoint'])) {
            throw new InternalErrorException('Invalid response. Invalid token endpoint.');
        }
    }

    /**
     * @inheritDoc
     */
    protected function getAuthorizationParameters(array $options)
    {
        $options = parent::getAuthorizationParameters($options);

        /**
         * The "approval_prompt" MUST be removed as it is not supported by Google, use "prompt" instead:
         *
         * @link https://developers.google.com/identity/protocols/oauth2/openid-connect#prompt
         */
        unset($options['approval_prompt']);

        return $options;
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * REDEFINED METHODS
     *
     * @see \League\OAuth2\Client\Provider\AbstractProvider
     */

    /**
     * @inheritDoc
     */
    protected function getDefaultScopes(): array
    {
        return ['openid', 'profile', 'email'];
    }

    /**
     * @inheritDoc
     */
    protected function getScopeSeparator(): string
    {
        return ' ';
    }

    /**
     * @inheritDoc
     */
    protected function createAccessToken(array $response, AbstractGrant $grant): AccessToken
    {
        return new BaseIdToken($response, $this);
    }

    /**
     * @inheritDoc
     */
    public function getResourceOwner(AccessToken $token): ResourceOwnerInterface
    {
        // We get resource owner information from id_token only
        // We could fall back calling user info API user access_token but we rather not
        if ($token instanceof BaseIdToken) {
            $data = $token->getIdTokenClaims();
            // e.g. token is passed to match League\AbstractProvider interface but not used
            return $this->createResourceOwner($data, $token);
        }

        throw new InternalErrorException('AccessToken should be an instance of BaseIdToken class.');
    }

    /**
     * Get JWT verification keys from Google.
     *
     * @return array
     */
    public function getJwtVerificationKeys()
    {
        $openIdConfiguration = $this->getOpenIdConfiguration();
        $keysUri = $openIdConfiguration['jwks_uri'];

        $factory = $this->getRequestFactory();
        $request = $factory->getRequestWithOptions('get', $keysUri, []);

        try {
            $response = $this->getParsedResponse($request);
        } catch (\Throwable $exception) {
            throw new InternalErrorException(__('Cannot parse JWKS endpoint response.'), 500, $exception);
        }

        if (!is_array($response) || !isset($response['keys'])) {
            throw new InternalErrorException(__('Invalid JWKS endpoint response. Keys missing.'));
        }

        return JWK::parseKeySet($response);
    }
}
