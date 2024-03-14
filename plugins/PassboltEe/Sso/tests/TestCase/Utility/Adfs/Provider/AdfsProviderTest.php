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
 * @since         4.6.0
 */

namespace Passbolt\Sso\Test\TestCase\Utility\Adfs\Provider;

use Cake\Routing\Router;
use Cake\TestSuite\TestCase;
use Passbolt\Sso\Model\Entity\SsoSetting;
use Passbolt\Sso\Test\Lib\AdfsProviderTestTrait;
use Passbolt\Sso\Utility\Adfs\Provider\AdfsProvider;
use Passbolt\Sso\Utility\Provider\AbstractOauth2Provider;

/**
 * @covers \Passbolt\Sso\Utility\Adfs\Provider\AdfsProvider
 */
class AdfsProviderTest extends TestCase
{
    use AdfsProviderTestTrait;

    private AdfsProvider $adfsProvider;

    /**
     * @inheritDoc
     */
    public function setup(): void
    {
        parent::setUp();

        $this->adfsProvider = new AdfsProvider([
            'clientId' => 'client-id',
            'clientSecret' => 'super-strong-client-secret',
            'redirectUri' => Router::url('/sso/adfs/redirect', true),
            'openIdBaseUri' => 'https://adfs.passbolt.test',
            'openIdConfigurationPath' => '/.well-known/openid-configuration',
            'emailClaim' => SsoSetting::ADFS_EMAIL_CLAIM_UPN,
        ]);
    }

    public function testSsoAdfsProvider_ExtendsAbstractOauth2Provider(): void
    {
        $this->assertInstanceOf(AbstractOauth2Provider::class, $this->adfsProvider);
    }

    public function testSsoAdfsProvider_getBaseAuthorizationUrl(): void
    {
        // Mock HTTP client
        $httpClientMock = $this->mockHttpClientResponse(200, [], json_encode([
            'jwks_uri' => 'http://adfs.passbolt.test/jwks/uri',
            'authorization_endpoint' => 'http://adfs.passbolt.test/authorize',
            'token_endpoint' => 'http://adfs.passbolt.test/token',
        ]));
        $this->adfsProvider->setHttpClient($httpClientMock);

        $url = $this->adfsProvider->getBaseAuthorizationUrl();

        $this->assertStringContainsString('authorize', $url);
    }

    public function testSsoAdfsProvider_getBaseAccessTokenUrl(): void
    {
        // Mock HTTP client
        $httpClientMock = $this->mockHttpClientResponse(200, [], json_encode([
            'jwks_uri' => 'http://adfs.passbolt.test/jwks/uri',
            'authorization_endpoint' => 'http://adfs.passbolt.test/authorize',
            'token_endpoint' => 'http://adfs.passbolt.test/token',
        ]));
        $this->adfsProvider->setHttpClient($httpClientMock);

        $url = $this->adfsProvider->getBaseAccessTokenUrl([]);

        $this->assertStringContainsString('token', $url);
    }

    public function testSsoAdfsProvider_getOpenIdBaseUri(): void
    {
        $httpClientMock = $this->mockHttpClientResponse(200, [], json_encode([
            'jwks_uri' => 'http://adfs.passbolt.test/jwks/uri',
            'authorization_endpoint' => 'http://adfs.passbolt.test/authorize',
            'token_endpoint' => 'http://adfs.passbolt.test/token',
        ]));
        $this->adfsProvider->setHttpClient($httpClientMock);

        $url = $this->adfsProvider->getOpenIdBaseUri();

        $this->assertStringContainsString('adfs.passbolt.test', $url);
    }

    public function testSsoAdfsProvider_getOpenIdConfigurationUri(): void
    {
        $httpClientMock = $this->mockHttpClientResponse(200, [], json_encode([
            'jwks_uri' => 'http://adfs.passbolt.test/jwks/uri',
            'authorization_endpoint' => 'http://adfs.passbolt.test/authorize',
            'token_endpoint' => 'http://adfs.passbolt.test/token',
        ]));
        $this->adfsProvider->setHttpClient($httpClientMock);

        $url = $this->adfsProvider->getOpenIdConfigurationUri();

        $this->assertStringContainsString('.well-known/openid-configuration', $url);
    }

    public function testSsoAdfsProvider_getClientId(): void
    {
        $httpClientMock = $this->mockHttpClientResponse(200, [], json_encode([
            'jwks_uri' => 'http://adfs.passbolt.test/jwks/uri',
            'authorization_endpoint' => 'http://adfs.passbolt.test/authorize',
            'token_endpoint' => 'http://adfs.passbolt.test/token',
        ]));
        $this->adfsProvider->setHttpClient($httpClientMock);

        $clientId = $this->adfsProvider->getClientId();

        $this->assertEquals('client-id', $clientId);
    }

    public function testSsoAdfsProvider_defaultOptionsValuesAreSetIfNullIsProvided(): void
    {
        $provider = new AdfsProvider([
            'clientId' => 'client-id',
            'clientSecret' => 'super-strong-client-secret',
            'redirectUri' => Router::url('/sso/adfs/redirect', true),
            'openIdBaseUri' => 'https://adfs.passbolt.test',
            'openIdConfigurationPath' => '/.well-known/openid-configuration',
            'emailClaim' => null,
        ]);

        $this->assertEquals(SsoSetting::ADFS_EMAIL_CLAIM_UPN, $provider->emailClaim);
    }
}
