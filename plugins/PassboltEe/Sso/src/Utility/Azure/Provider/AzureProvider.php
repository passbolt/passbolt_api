<?php
declare(strict_types=1);

/**
 * MIT License
 *
 * Copyright (c) 2022 Passbolt SA (https://www.passbolt.com)
 * Copyright (c) 2016 TheNetw.org
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
 * documentation files (the "Software"), to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software,
 * and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of
 * the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED
 * TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF
 * CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 *
 * @link          https://github.com/TheNetworg/oauth2-azure/blob/master/src/Provider/Azure.php
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         3.9.0
 */

namespace Passbolt\Sso\Utility\Azure\Provider;

use Cake\Http\Exception\InternalErrorException;
use Firebase\JWT\Key;
use League\OAuth2\Client\Grant\AbstractGrant;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Passbolt\Sso\Error\Exception\AzureException;
use Passbolt\Sso\Model\Entity\SsoSetting;
use Passbolt\Sso\Utility\Azure\OpenId\AzureIdToken;
use Passbolt\Sso\Utility\Azure\ResourceOwner\AzureResourceOwner;
use Passbolt\Sso\Utility\Grant\JwtBearer;
use Passbolt\Sso\Utility\Provider\AbstractOauth2Provider;
use Psr\Http\Message\ResponseInterface;

class AzureProvider extends AbstractOauth2Provider
{
    use BearerAuthorizationTrait;

    /**
     * Api version in use
     */
    public const ENDPOINT_VERSION_2_0 = '2.0';

    /**
     * @var string default base url for login
     */
    public const AZURE_OPENID_BASE_URI = 'https://login.microsoftonline.com';

    /**
     * @var string $tenant name
     */
    protected $tenant = '';

    /**
     * Email claim alias field to check as username/email.
     *
     * @var string
     */
    public $emailClaim = SsoSetting::AZURE_EMAIL_CLAIM_ALIAS_EMAIL;

    /**
     * @inheritDoc
     */
    public function __construct(array $options = [], array $collaborators = [])
    {
        // Variations allowed on baseUri for example for gov agencies
        $options['openIdBaseUri'] = $options['openIdBaseUri'] ?? self::AZURE_OPENID_BASE_URI;

        // Variations allowed on email mapping
        $options['emailClaim'] = $options['emailClaim'] ?? $this->emailClaim;

        // Custom azure field
        $options['tenant'] = $options['tenant'] ?? $this->tenant;

        parent::__construct($options, $collaborators);

        $this->grantFactory->setGrant('jwt_bearer', new JwtBearer());
    }

    /**
     * @return string
     */
    public function getOpenIdBaseUri(): string
    {
        // Azure specificity: tenant is added to the base as well as API version
        return parent::getOpenIdBaseUri() . '/' . $this->getTenant() . '/v' . self::ENDPOINT_VERSION_2_0;
    }

    /**
     * @inheritDoc
     */
    public function getOpenIdConfigurationUri(): string
    {
        // Azure specificity: appid is happened
        return parent::getOpenIdConfigurationUri() . '?appid=' . $this->getClientId();
    }

    /**
     * @return string
     */
    public function getTenant(): string
    {
        return $this->tenant;
    }

    /**
     * @inheritDoc
     */
    protected function createAccessToken(array $response, AbstractGrant $grant): AccessToken
    {
        return new AzureIdToken($response, $this);
    }

    /**
     * @inheritDoc
     */
    protected function createResourceOwner(array $response, AccessToken $token): ResourceOwnerInterface
    {
        return new AzureResourceOwner($response, $this->emailClaim);
    }

    /**
     * @inheritDoc
     */
    protected function checkResponse(ResponseInterface $response, $data): void
    {
        if (empty($data['error'])) {
            return;
        }

        if (is_string($data['error']) && isset($data['error_description']) && is_string($data['error_description'])) {
            throw new AzureException($data['error'], $data['error_description']);
        } else {
            throw new IdentityProviderException(
                $response->getReasonPhrase(),
                $response->getStatusCode(),
                (string)$response->getBody()
            );
        }
    }

    /**
     * Get JWT verification keys from Azure Active Directory.
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

        /**
         * Here we are using custom method to check JWK key signature as we can't use `JWK::parseKeySet` method directly
         * because Azure don't provide "kty" parameter in the keys.
         *
         * @see \Firebase\JWT\JWK::parseKeySet()
         */
        return $this->parseJwksKeys($response['keys']);
    }

    /**
     * Parse & check JWT keys signature from Azure.
     *
     * @param array $responseKeys keys from Jwks endpoint
     * @return array of openssl compatible keys
     */
    protected function parseJwksKeys(array $responseKeys): array
    {
        $keys = [];
        foreach ($responseKeys as $i => $keyinfo) {
            if (isset($keyinfo['x5c']) && is_array($keyinfo['x5c'])) {
                foreach ($keyinfo['x5c'] as $encodedkey) {
                    $cert =
                        '-----BEGIN CERTIFICATE-----' . PHP_EOL
                        . chunk_split($encodedkey, 64, PHP_EOL)
                        . '-----END CERTIFICATE-----' . PHP_EOL;

                    $cert_object = openssl_x509_read($cert);

                    if ($cert_object === false) {
                        throw new InternalErrorException(__('Failed to read certificate: {0}', $encodedkey));
                    }

                    $pkey_object = openssl_pkey_get_public($cert_object);

                    if ($pkey_object === false) {
                        $msg = __('Failed to read public key from certificate: {0}', $encodedkey);
                        throw new InternalErrorException($msg);
                    }

                    $pkey_array = openssl_pkey_get_details($pkey_object);

                    if ($pkey_array === false) {
                        $msg = __('Failed to public key properties from certificate: {0}', $encodedkey);
                        throw new InternalErrorException($msg);
                    }

                    $publicKey = $pkey_array['key'];

                    $keys[$keyinfo['kid']] = new Key($publicKey, 'RS256');
                }
            }
        }

        if (empty($keys)) {
            throw new InternalErrorException('No JWT key defined for Azure service.');
        }

        return $keys;
    }
}
