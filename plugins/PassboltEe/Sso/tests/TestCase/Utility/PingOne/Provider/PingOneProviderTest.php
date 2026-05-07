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
 * @since         5.11.0
 */

namespace Passbolt\Sso\Test\TestCase\Utility\PingOne\Provider;

use Cake\Routing\Router;
use Cake\TestSuite\TestCase;
use GuzzleHttp\Psr7\Response;
use Passbolt\Sso\Model\Entity\SsoSetting;
use Passbolt\Sso\Test\Lib\SsoProviderTestTrait;
use Passbolt\Sso\Utility\PingOne\Provider\PingOneProvider;
use Passbolt\Sso\Utility\Provider\AbstractOauth2Provider;

/**
 * @covers \Passbolt\Sso\Utility\PingOne\Provider\PingOneProvider
 */
class PingOneProviderTest extends TestCase
{
    use SsoProviderTestTrait;

    private PingOneProvider $pingOneProvider;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->pingOneProvider = new PingOneProvider([
            'clientId' => 'client-id',
            'clientSecret' => 'super-strong-client-secret',
            'redirectUri' => Router::url('/sso/pingone/redirect', true),
            'openIdBaseUri' => 'https://auth.pingone.com',
            'openIdConfigurationPath' => '/.well-known/openid-configuration',
            'environmentId' => 'd1b2c3a4-e5f6-7890-abcd-ef1234567890',
            'emailClaim' => SsoSetting::PINGONE_EMAIL_CLAIM_EMAIL,
        ]);
    }

    public function testSsoPingOneProvider_ExtendsAbstractOauth2Provider(): void
    {
        $this->assertInstanceOf(AbstractOauth2Provider::class, $this->pingOneProvider);
    }

    public function testSsoPingOneProvider_getBaseAuthorizationUrl(): void
    {
        $responseQueue = [
            new Response(200, [], json_encode([
                'jwks_uri' => 'https://auth.pingone.com/jwks/uri',
                'authorization_endpoint' => 'https://auth.pingone.com/authorize',
                'token_endpoint' => 'https://auth.pingone.com/token',
            ])),
        ];
        $httpClientMock = $this->mockHttpClientResponse($responseQueue);
        $this->pingOneProvider->setHttpClient($httpClientMock);

        $url = $this->pingOneProvider->getBaseAuthorizationUrl();

        $this->assertStringContainsString('authorize', $url);
    }

    public function testSsoPingOneProvider_getBaseAccessTokenUrl(): void
    {
        $responseQueue = [
            new Response(200, [], json_encode([
                'jwks_uri' => 'https://auth.pingone.com/jwks/uri',
                'authorization_endpoint' => 'https://auth.pingone.com/authorize',
                'token_endpoint' => 'https://auth.pingone.com/token',
            ])),
        ];
        $httpClientMock = $this->mockHttpClientResponse($responseQueue);
        $this->pingOneProvider->setHttpClient($httpClientMock);

        $url = $this->pingOneProvider->getBaseAccessTokenUrl([]);

        $this->assertStringContainsString('token', $url);
    }

    public function testSsoPingOneProvider_getOpenIdBaseUri(): void
    {
        $url = $this->pingOneProvider->getOpenIdBaseUri();

        $this->assertSame(
            'https://auth.pingone.com/d1b2c3a4-e5f6-7890-abcd-ef1234567890/as',
            $url
        );
    }

    public function testSsoPingOneProvider_getOpenIdConfigurationUri(): void
    {
        $url = $this->pingOneProvider->getOpenIdConfigurationUri();

        $this->assertSame(
            'https://auth.pingone.com/d1b2c3a4-e5f6-7890-abcd-ef1234567890/as/.well-known/openid-configuration',
            $url
        );
    }

    public function testSsoPingOneProvider_getClientId(): void
    {
        $clientId = $this->pingOneProvider->getClientId();

        $this->assertEquals('client-id', $clientId);
    }

    public function testSsoPingOneProvider_defaultOptionsValuesAreSetIfNullIsProvided(): void
    {
        $provider = new PingOneProvider([
            'clientId' => 'client-id',
            'clientSecret' => 'super-strong-client-secret',
            'redirectUri' => Router::url('/sso/pingone/redirect', true),
            'openIdBaseUri' => 'https://auth.pingone.com',
            'openIdConfigurationPath' => '/.well-known/openid-configuration',
            'environmentId' => 'd1b2c3a4-e5f6-7890-abcd-ef1234567890',
            'emailClaim' => null,
        ]);

        $this->assertEquals(SsoSetting::PINGONE_EMAIL_CLAIM_EMAIL, $provider->emailClaim);
    }
}
