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

namespace Passbolt\Sso\Test\TestCase\Utility\Google\Provider;

use App\Test\Lib\AppTestCase;
use Cake\Routing\Router;
use GuzzleHttp\Psr7\Response;
use Passbolt\Sso\Test\Lib\GoogleProviderTestTrait;
use Passbolt\Sso\Test\Lib\SsoProviderTestTrait;
use Passbolt\Sso\Utility\Google\Provider\GoogleProvider;
use Passbolt\Sso\Utility\Provider\AbstractOauth2Provider;

/**
 * @see \Passbolt\Sso\Utility\Google\Provider\GoogleProvider
 */
class GoogleProviderTest extends AppTestCase
{
    use GoogleProviderTestTrait;
    use SsoProviderTestTrait;

    private array $config = [];

    private GoogleProvider $provider;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->config = [
            'clientId' => 'client-id',
            'clientSecret' => 'super-strong-client-secret',
            'redirectUri' => Router::url('/sso/google/redirect', true),
        ];
        $this->provider = new GoogleProvider($this->config);
    }

    public function testSsoGoogleProvider_ExtendsAbstractOauth2Provider(): void
    {
        $this->assertInstanceOf(AbstractOauth2Provider::class, $this->provider);
    }

    public function testSsoGoogleProvider_getBaseAuthorizationUrl(): void
    {
        // Mock HTTP client
        $responseQueue = [
            new Response(200, [], json_encode([
                'jwks_uri' => 'https://accounts.google.com/oauth2/jwks/uri',
                'authorization_endpoint' => 'https://accounts.google.com/oauth2/v2/auth',
                'token_endpoint' => 'https://accounts.google.com/oauth2/token',
            ])),
        ];
        $httpClientMock = $this->mockHttpClientResponse($responseQueue);
        $this->provider->setHttpClient($httpClientMock);

        $url = $this->provider->getBaseAuthorizationUrl();

        $this->assertStringContainsString('accounts.google.com', $url);
        $this->assertStringContainsString('oauth2/v2/auth', $url);
    }

    public function testSsoGoogleProvider_getBaseAccessTokenUrl(): void
    {
        // Mock HTTP client
        $responseQueue = [
            new Response(200, [], json_encode([
                'jwks_uri' => 'https://accounts.google.com/oauth2/jwks/uri',
                'authorization_endpoint' => 'https://accounts.google.com/oauth2/v2/auth',
                'token_endpoint' => 'https://accounts.google.com/oauth2/token',
            ])),
        ];
        $httpClientMock = $this->mockHttpClientResponse($responseQueue);
        $this->provider->setHttpClient($httpClientMock);

        $url = $this->provider->getBaseAccessTokenUrl([]);
        $this->assertStringContainsString('token', $url);
    }

    public function testSsoGoogleProvider_getOpenIdConfigurationUri(): void
    {
        $url = $this->provider->getOpenIdConfigurationUri();
        $this->assertStringContainsString('accounts.google.com/.well-known/openid-configuration', $url);
    }
}
