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

namespace Passbolt\Sso\Utility\Google\Provider;

use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotImplementedException;
use Firebase\JWT\JWK;
use League\OAuth2\Client\Grant\AbstractGrant;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Passbolt\Sso\Error\Exception\GoogleException;
use Passbolt\Sso\Utility\Google\ResourceOwner\GoogleResourceOwner;
use Passbolt\Sso\Utility\Grant\JwtBearer;
use Passbolt\Sso\Utility\OpenId\BaseIdToken;
use Passbolt\Sso\Utility\Provider\BaseOauth2Provider;
use Psr\Http\Message\ResponseInterface;

class GoogleProvider extends BaseOauth2Provider
{
    use BearerAuthorizationTrait;

    /**
     * @var string Default base url for OpenID.
     */
    public $openIdBaseUri = 'https://accounts.google.com';

    /**
     * @inheritDoc
     */
    public function __construct(array $options = [], array $collaborators = [])
    {
        parent::__construct($options, $collaborators);

        $this->grantFactory->setGrant('jwt_bearer', new JwtBearer());
    }

    /**
     * ABSTRACT METHODS
     *
     * @see \League\OAuth2\Client\Provider\AbstractProvider
     */

    /**
     * @inheritDoc
     */
    protected function checkResponse(ResponseInterface $response, $data): void
    {
        if (empty($data['error'])) {
            return;
        }

        if (is_string($data['error']) && isset($data['error_description']) && is_string($data['error_description'])) {
            throw new GoogleException($data['error'], $data['error_description']);
        } else {
            throw new IdentityProviderException(
                $response->getReasonPhrase(),
                $response->getStatusCode(),
                (string)$response->getBody()
            );
        }
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
     * @inheritDoc
     */
    public function getOpenIdConfigurationUri(): string
    {
        return $this->openIdBaseUri . '/.well-known/openid-configuration';
    }

    /**
     * @return string
     */
    public function getOpenIdBaseUri(): string
    {
        return $this->openIdBaseUri;
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
        /**
         * The response should be cached in production application.
         *
         * @link https://developers.google.com/identity/openid-connect/openid-connect#validatinganidtoken
         */
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
        /** Note: "openid" MUST be the first scope in the list. */
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
    protected function createResourceOwner(array $response, AccessToken $token): ResourceOwnerInterface
    {
        return new GoogleResourceOwner($response);
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

            return $this->createResourceOwner($data, $token);
        }

        throw new InternalErrorException('AccessToken should be an instance of BaseIdToken class.');
    }
}
