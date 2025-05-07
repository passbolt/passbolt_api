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

namespace Passbolt\Sso\Test\TestCase\Utility\Azure\Provider;

use App\Utility\UuidFactory;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;
use Exception;
use GuzzleHttp\Psr7\Response;
use Passbolt\Sso\Error\Exception\AzureException;
use Passbolt\Sso\Model\Entity\SsoSetting;
use Passbolt\Sso\Test\Lib\AzureProviderTestTrait;
use Passbolt\Sso\Test\Lib\SsoProviderTestTrait;
use Passbolt\Sso\Utility\Azure\Provider\AzureProvider;
use Passbolt\Sso\Utility\Provider\AbstractOauth2Provider;

/**
 * @covers \Passbolt\Sso\Utility\Azure\Provider\AzureProvider
 */
class AzureProviderTest extends TestCase
{
    use AzureProviderTestTrait;
    use SsoProviderTestTrait;

    private AzureProvider $azureProvider;

    private array $config = [];

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setup();

        $this->config = [
            'tenant' => UuidFactory::uuid(),
            'clientId' => 'client-id',
            'clientSecret' => 'super-strong-client-secret',
            'redirectUri' => Router::url('/sso/azure/redirect', true),
        ];
        $this->azureProvider = new AzureProvider($this->config);
    }

    /**
     * @inheritDoc
     */
    protected function tearDown(): void
    {
        unset($this->azureProvider);

        parent::tearDown();
    }

    public function testSsoAzureProvider_ExtendsAbstractOauth2Provider(): void
    {
        $this->assertInstanceOf(AbstractOauth2Provider::class, $this->azureProvider);
    }

    public function testSsoAzureProvider_getBaseAuthorizationUrl_Success(): void
    {
        // Mock HTTP client
        $responseQueue = [
            new Response(200, [], json_encode([
                'jwks_uri' => 'http://azure.passbolt.test/jwks/uri',
                'authorization_endpoint' => 'http://azure.passbolt.test/authorize',
                'token_endpoint' => 'http://azure.passbolt.test/token',
            ])),
        ];
        $httpClientMock = $this->mockHttpClientResponse($responseQueue);
        $this->azureProvider->setHttpClient($httpClientMock);

        $url = $this->azureProvider->getBaseAuthorizationUrl();

        $this->assertStringContainsString('authorize', $url);
    }

    public function testSsoAzureProvider_getBaseAuthorizationUrl_ErrorTenantNotExistShoudTrigger400(): void
    {
        $responseQueue = [
            new Response(400, [], json_encode([
                'error' => 'Tenant not found ',
                'error_description' => 'AADSTS90002: Tenant does not exist',
            ])),
        ];
        $httpClientMock = $this->mockHttpClientResponse($responseQueue);
        $this->azureProvider->setHttpClient($httpClientMock);

        try {
            $this->azureProvider->getBaseAuthorizationUrl();
        } catch (Exception $exception) {
            $this->assertSame(400, $exception->getCode());
            $this->assertInstanceOf(AzureException::class, $exception);
            $this->assertStringContainsString('AADSTS90002', $exception->getMessage());
        }
    }

    public function testSsoAzureProvider_getBaseAccessTokenUrl(): void
    {
        $responseQueue = [
            new Response(200, [], json_encode([
                'jwks_uri' => 'http://azure.passbolt.test/jwks/uri',
                'authorization_endpoint' => 'http://azure.passbolt.test/authorize',
                'token_endpoint' => 'http://azure.passbolt.test/token',
            ])),
        ];
        $httpClientMock = $this->mockHttpClientResponse($responseQueue);
        $this->azureProvider->setHttpClient($httpClientMock);

        $url = $this->azureProvider->getBaseAccessTokenUrl([]);

        $this->assertStringContainsString('token', $url);
    }

    public function testSsoAzureProvider_getOpenIdBaseUri(): void
    {
        $url = $this->azureProvider->getOpenIdBaseUri();
        $this->assertStringContainsString('microsoft', $url);
        $this->assertStringContainsString('v2.0', $url);
    }

    public function testSsoAzureProvider_getOpenIdConfigurationUri(): void
    {
        $url = $this->azureProvider->getOpenIdConfigurationUri();
        $this->assertStringContainsString('.well-known', $url);
    }

    public function testSsoAzureProvider_getTenant(): void
    {
        $this->assertEquals($this->config['tenant'], $this->azureProvider->getTenant());
    }

    public function testSsoAzureProvider_getClientId(): void
    {
        $this->assertEquals($this->config['clientId'], $this->azureProvider->getClientId());
    }

    public function testSsoAzureProvider_defaultOptionsValuesAreSetIfNullIsProvided(): void
    {
        $provider = new AzureProvider([
            'clientId' => 'default-client-id',
            'clientSecret' => 'default-client-secret',
            'redirectUri' => Router::url('/sso/azure/redirect', true),
            'tenant' => null,
            'openIdBaseUri' => null,
            'emailClaim' => null,
        ]);

        $this->assertEquals(SsoSetting::AZURE_EMAIL_CLAIM_ALIAS_EMAIL, $provider->emailClaim);
        $this->assertEquals('https://login.microsoftonline.com//v2.0', $provider->getOpenIdBaseUri());
        $this->assertEquals('', $provider->getTenant());
    }
}
