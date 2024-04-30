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
 * @since         4.7.0
 */

namespace Passbolt\Sso\Test\TestCase\Utility\OpenId;

use App\Test\Lib\AppTestCase;
use Cake\I18n\FrozenTime;
use Cake\Routing\Router;
use Firebase\JWT\JWT;
use GuzzleHttp\Psr7\Response;
use Passbolt\Sso\Test\Lib\SsoProviderTestTrait;
use Passbolt\Sso\Utility\Adfs\Provider\AdfsProvider;
use Passbolt\Sso\Utility\OAuth2\Provider\OAuth2Provider;
use Passbolt\Sso\Utility\OpenId\BaseIdToken;

/**
 * @see \Passbolt\Sso\Utility\OpenId\BaseIdToken
 */
class BaseIdTokenTest extends AppTestCase
{
    use SsoProviderTestTrait;

    public function testBaseIdToken_Success(): void
    {
        $provider = new OAuth2Provider([
            'clientId' => 'client-id',
            'clientSecret' => 'super-strong-client-secret',
            'redirectUri' => Router::url('/sso/oauth2/redirect', true),
            'openIdBaseUri' => 'https://oauth2.passbolt.test',
            'openIdConfigurationPath' => '/.well-known/openid-configuration',
        ]);
        $jwkSet = $this->getJwkSet();
        $responseQueue = [
            new Response(200, [], json_encode([
                'jwks_uri' => 'https://oauth2.passbolt.test/jwks/uri',
                'authorization_endpoint' => 'https://oauth2.passbolt.test/authorize',
                'token_endpoint' => 'https://oauth2.passbolt.test/token',
                'keys' => $jwkSet['keys'],
            ])),
            new Response(200, [], json_encode([
                'jwks_uri' => 'https://oauth2.passbolt.test/jwks/uri',
                'authorization_endpoint' => 'https://oauth2.passbolt.test/authorize',
                'token_endpoint' => 'https://oauth2.passbolt.test/token',
                'keys' => $jwkSet['keys'],
            ])),
        ];
        $httpClientMock = $this->mockHttpClientResponse($responseQueue);
        $provider->setHttpClient($httpClientMock);

        $idToken = $this->getIdToken([
            'clientId' => 'client-id',
            'openIdBaseUri' => 'https://oauth2.passbolt.test/',
            'keyId' => 'jwk1',
            'username' => 'ada@passbolt.test',
        ]);
        new BaseIdToken([
            'id_token' => $idToken,
            'expires_in' => FrozenTime::now()->addHours(1)->getTimestamp(),
            'access_token' => 'access_token',
            'resource_owner_id' => 'resource_owner_id',
            'refresh_token' => 'refresh_token',
        ], $provider);

        $this->assertTrue(true);
    }

    public function testBaseIdToken_Success_WithTrailingSlash(): void
    {
        $provider = new AdfsProvider([
            'clientId' => 'client-id',
            'clientSecret' => 'super-strong-client-secret',
            'redirectUri' => Router::url('/sso/oauth2/redirect', true),
            'openIdBaseUri' => 'https://oauth2.passbolt.test', // without trailing slash
            'openIdConfigurationPath' => '/.well-known/openid-configuration',
        ]);
        $jwkSet = $this->getJwkSet();
        $responseQueue = [
            new Response(200, [], json_encode([
                'issuer' => 'https://oauth2.passbolt.test/o/passbolt/', // Trailing slash is important here
                'jwks_uri' => 'https://oauth2.passbolt.test/jwks/uri/',
                'authorization_endpoint' => 'https://oauth2.passbolt.test/authorize/',
                'token_endpoint' => 'https://oauth2.passbolt.test/token/',
                'keys' => $jwkSet['keys'],
            ])),
            new Response(200, [], json_encode([
                'issuer' => 'https://oauth2.passbolt.test/o/passbolt/',
                'jwks_uri' => 'https://oauth2.passbolt.test/jwks/uri/',
                'authorization_endpoint' => 'https://oauth2.passbolt.test/authorize/',
                'token_endpoint' => 'https://oauth2.passbolt.test/token/',
                'keys' => $jwkSet['keys'],
            ])),
        ];
        $httpClientMock = $this->mockHttpClientResponse($responseQueue);
        $provider->setHttpClient($httpClientMock);

        $idToken = $this->getIdToken([
            'clientId' => 'client-id',
            'openIdBaseUri' => 'https://oauth2.passbolt.test/', // Trailing slash is important here
            'keyId' => 'jwk1',
            'username' => 'ada@passbolt.test',
        ]);
        new BaseIdToken([
            'id_token' => $idToken,
            'expires_in' => FrozenTime::now()->addHours(1)->getTimestamp(),
            'access_token' => 'access_token',
            'resource_owner_id' => 'resource_owner_id',
            'refresh_token' => 'refresh_token',
        ], $provider);

        $this->assertTrue(true);
    }

    /** Helper methods */

    public function getJwkSet(): array
    {
        return json_decode(
            file_get_contents(__DIR__ . DS . '..' . DS . '..' . DS . '..' . DS . 'Fixture' . DS . 'Jwk' . DS . 'rsa-jwkset.json'),
            true
        );
    }

    /**
     * Returns ID Token from given options.
     *
     * @param array $options Options.
     * @return string
     */
    private function getIdToken(array $options): string
    {
        $alg = 'RS256';
        $privateKey = file_get_contents(__DIR__ . DS . '..' . DS . '..' . DS . '..' . DS . 'Fixture' . DS . 'Jwk' . DS . 'rsa1-private.pem');
        $payload = [
            'sub' => 'foo',
            'exp' => strtotime('+10 seconds'),
            'aud' => $options['clientId'],
            'iss' => $options['openIdBaseUri'],
            'email' => $options['username'],
        ];

        return JWT::encode($payload, $privateKey, $alg, $options['keyId']);
    }
}
