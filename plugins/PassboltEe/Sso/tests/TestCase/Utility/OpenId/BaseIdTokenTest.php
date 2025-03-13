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
use Cake\Core\Configure;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\I18n\DateTime;
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
        $provider = $this->getTestProvider();
        $idToken = $this->getIdToken([
            'clientId' => 'client-id',
            'openIdBaseUri' => 'https://oauth2.passbolt.test/',
            'keyId' => 'jwk1',
            'username' => 'ada@passbolt.test',
        ]);

        new BaseIdToken($this->getBasedIdTokenOptions($idToken), $provider);
        $this->assertTrue(true);
    }

    public function testBaseIdToken_Success_WithTrailingSlash(): void
    {
        $provider = $this->getTestProvider();
        $idToken = $this->getIdToken([
            'clientId' => 'client-id',
            'openIdBaseUri' => 'https://oauth2.passbolt.test/', // Trailing slash is important here
            'keyId' => 'jwk1',
            'username' => 'ada@passbolt.test',
        ]);

        new BaseIdToken($this->getBasedIdTokenOptions($idToken), $provider);
        $this->assertTrue(true);
    }

    /**
     * @covers \Passbolt\Sso\Utility\Provider\AbstractOauth2Provider::assertJwkDefaultAlg()
     * @return void
     */
    public function testBaseIdToken_JwtVerificationKeys_Success_DefaultAlgSet(): void
    {
        Configure::write('passbolt.plugins.sso.security.jwks.defaultAlg', 'RS256');
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

        new BaseIdToken($this->getBasedIdTokenOptions($idToken), $provider);
        $this->assertTrue(true);
    }

    public function invalidJwksDefaultAlgValuesProvider(): array
    {
        return [
            [
                // Invalid configuration type
                'configValue' => ['foo' => 'bar'],
                'expectedErrorMessage' => 'configuration value should be a string or NULL',
            ],
            [
                // Invalid configuration type
                'configValue' => new \stdClass(),
                'expectedErrorMessage' => 'configuration value should be a string or NULL',
            ],
            [
                // Invalid configuration(alg) value
                'configValue' => 'FOOBAR256',
                'expectedErrorMessage' => 'configuration value should be one of the following',
            ],
        ];
    }

    /**
     * @dataProvider invalidJwksDefaultAlgValuesProvider
     * @covers  \Passbolt\Sso\Utility\Provider\AbstractOauth2Provider::assertJwkDefaultAlg()
     * @param mixed $value Config value to set.
     * @param string $expectedExceptionMsg Message to assert.
     * @return void
     */
    public function testBaseIdToken_JwtVerificationKeys_Error_InvalidConfigType($value, string $expectedExceptionMsg): void
    {
        Configure::write('passbolt.plugins.sso.security.jwks.defaultAlg', $value);
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

        $this->expectException(InternalErrorException::class);
        $this->expectExceptionMessage($expectedExceptionMsg);

        $idToken = $this->getIdToken([
            'clientId' => 'client-id',
            'openIdBaseUri' => 'https://oauth2.passbolt.test/',
            'keyId' => 'jwk1',
            'username' => 'ada@passbolt.test',
        ]);
        new BaseIdToken([
            'id_token' => $idToken,
            'expires_in' => DateTime::now()->addHours(1)->getTimestamp(),
            'access_token' => 'access_token',
            'resource_owner_id' => 'resource_owner_id',
            'refresh_token' => 'refresh_token',
        ], $provider);
    }

    public function testBaseIdToken_Success_WithAudArray(): void
    {
        $provider = $this->getTestProvider();
        $idToken = $this->getIdToken([
            'clientId' => ['client-id'],
            'openIdBaseUri' => 'https://oauth2.passbolt.test',
            'keyId' => 'jwk1',
            'username' => 'ada@passbolt.test',
        ]);

        new BaseIdToken($this->getBasedIdTokenOptions($idToken), $provider);
        $this->assertTrue(true);
    }

    public function testBaseIdToken_Success_WithAudArray2(): void
    {
        $provider = $this->getTestProvider();
        $idToken = $this->getIdToken([
            'clientId' => ['client-id1', 'client-id'],
            'openIdBaseUri' => 'https://oauth2.passbolt.test/',
            'keyId' => 'jwk1',
            'username' => 'ada@passbolt.test',
        ]);

        new BaseIdToken($this->getBasedIdTokenOptions($idToken), $provider);
        $this->assertTrue(true);
    }

    public function testBaseIdToken_Error_EmptyAudArray(): void
    {
        $provider = $this->getTestProvider();
        $idToken = $this->getIdToken([
            'clientId' => [],
            'openIdBaseUri' => 'https://oauth2.passbolt.test/',
            'keyId' => 'jwk1',
            'username' => 'ada@passbolt.test',
        ]);

        $this->expectException(BadRequestException::class);
        new BaseIdToken($this->getBasedIdTokenOptions($idToken), $provider);
    }

    public function testBaseIdToken_Error_InvalidAudArray(): void
    {
        $provider = $this->getTestProvider();
        $idToken = $this->getIdToken([
            'clientId' => ['🔥'],
            'openIdBaseUri' => 'https://oauth2.passbolt.test/',
            'keyId' => 'jwk1',
            'username' => 'ada@passbolt.test',
        ]);

        $this->expectException(BadRequestException::class);
        new BaseIdToken($this->getBasedIdTokenOptions($idToken), $provider);
    }

    public function testBaseIdToken_Error_InvalidEmail(): void
    {
        $provider = $this->getTestProvider();
        $idToken = $this->getIdToken([
            'clientId' => 'client-id',
            'openIdBaseUri' => 'https://oauth2.passbolt.test/',
            'keyId' => 'jwk1',
            'username' => '🔥',
        ]);

        $this->expectException(BadRequestException::class);
        new BaseIdToken($this->getBasedIdTokenOptions($idToken), $provider);
    }

    public function testBaseIdToken_Error_InvalidEmail2(): void
    {
        $provider = $this->getTestProvider();
        $idToken = $this->getIdToken([
            'clientId' => 'client-id',
            'openIdBaseUri' => 'https://oauth2.passbolt.test/',
            'keyId' => 'jwk1',
            'username' => '',
        ]);

        $this->expectException(BadRequestException::class);
        new BaseIdToken($this->getBasedIdTokenOptions($idToken), $provider);
    }

    public function testBaseIdToken_Error_InvalidIss(): void
    {
        $provider = $this->getTestProvider();
        $idToken = $this->getIdToken([
            'clientId' => 'client-id',
            'openIdBaseUri' => '🔥',
            'keyId' => 'jwk1',
            'username' => 'ada@passbolt.test',
        ]);

        $this->expectException(BadRequestException::class);
        new BaseIdToken($this->getBasedIdTokenOptions($idToken), $provider);
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

    public function getTestProvider(): OAuth2Provider
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

        return $provider;
    }

    public function getBasedIdTokenOptions(string $idToken): array
    {
        return [
            'id_token' => $idToken,
            'expires_in' => DateTime::now()->addHours(1)->getTimestamp(),
            'access_token' => 'access_token',
            'resource_owner_id' => 'resource_owner_id',
            'refresh_token' => 'refresh_token',
        ];
    }
}
