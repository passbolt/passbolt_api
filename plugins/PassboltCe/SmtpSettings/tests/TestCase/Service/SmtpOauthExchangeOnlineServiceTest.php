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

namespace Passbolt\SmtpSettings\Test\TestCase\Service;

use App\Utility\UuidFactory;
use Cake\Http\Client;
use Cake\Http\Client\Response;
use Cake\Http\Exception\InternalErrorException;
use Cake\TestSuite\TestCase;
use Passbolt\SmtpSettings\Service\SmtpOauthExchangeOnlineService;

/**
 * @covers \Passbolt\SmtpSettings\Service\SmtpOauthExchangeOnlineService
 */
class SmtpOauthExchangeOnlineServiceTest extends TestCase
{
    public function testSmtpOauthExchangeOnlineService_GetAccessToken_Success(): void
    {
        $config = $this->getOauth2Config();
        $expectedToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOi...';

        $httpClient = $this->createMock(Client::class);
        $httpClient->expects($this->once())
            ->method('post')
            ->willReturn($this->createMockResponse(200, ['access_token' => $expectedToken]));

        $service = new SmtpOauthExchangeOnlineService($config, $httpClient);
        $token = $service->getAccessToken();

        $this->assertSame($expectedToken, $token);
    }

    public function testSmtpOauthExchangeOnlineService_GetAccessToken_VerifiesTokenUrlContainsTenantId(): void
    {
        $tenantId = UuidFactory::uuid();
        $config = $this->getOauth2Config(['tenant_id' => $tenantId]);
        $expectedUrl = "https://login.microsoftonline.com/{$tenantId}/oauth2/v2.0/token";

        $httpClient = $this->createMock(Client::class);
        $httpClient->expects($this->once())
            ->method('post')
            ->with(
                $this->equalTo($expectedUrl),
                $this->anything()
            )
            ->willReturn($this->createMockResponse(200, ['access_token' => 'token']));

        $service = new SmtpOauthExchangeOnlineService($config, $httpClient);
        $service->getAccessToken();
    }

    public function testSmtpOauthExchangeOnlineService_GetAccessToken_VerifiesPostBody(): void
    {
        $config = $this->getOauth2Config();

        $httpClient = $this->createMock(Client::class);
        $httpClient->expects($this->once())
            ->method('post')
            ->with(
                $this->anything(),
                $this->equalTo([
                    'grant_type' => 'client_credentials',
                    'client_id' => $config['client_id'],
                    'client_secret' => $config['client_secret'],
                    'scope' => SmtpOauthExchangeOnlineService::SCOPE,
                ])
            )
            ->willReturn($this->createMockResponse(200, ['access_token' => 'token']));

        $service = new SmtpOauthExchangeOnlineService($config, $httpClient);
        $service->getAccessToken();
    }

    public function testSmtpOauthExchangeOnlineService_GetAccessToken_ErrorResponse_ThrowsException(): void
    {
        $config = $this->getOauth2Config();

        $httpClient = $this->createMock(Client::class);
        $httpClient->expects($this->once())
            ->method('post')
            ->willReturn($this->createMockResponse(400, [
                'error' => 'invalid_client',
                'error_description' => 'Client authentication failed.',
            ]));

        $service = new SmtpOauthExchangeOnlineService($config, $httpClient);

        $this->expectException(InternalErrorException::class);
        $this->expectExceptionMessage('Failed to obtain SMTP OAuth2 access token');
        $service->getAccessToken();
    }

    public function testSmtpOauthExchangeOnlineService_GetAccessToken_MissingAccessToken_ThrowsException(): void
    {
        $config = $this->getOauth2Config();

        $httpClient = $this->createMock(Client::class);
        $httpClient->expects($this->once())
            ->method('post')
            ->willReturn($this->createMockResponse(200, ['token_type' => 'Bearer']));

        $service = new SmtpOauthExchangeOnlineService($config, $httpClient);

        $this->expectException(InternalErrorException::class);
        $this->expectExceptionMessage('did not contain an access token');
        $service->getAccessToken();
    }

    public function testSmtpOauthExchangeOnlineService_GetUsername_ReturnsConfiguredUsername(): void
    {
        $email = 'user@example.com';
        $config = $this->getOauth2Config(['oauth_username' => $email]);

        $httpClient = $this->createMock(Client::class);
        $service = new SmtpOauthExchangeOnlineService($config, $httpClient);

        $this->assertSame($email, $service->getUsername());
    }

    public function testSmtpOauthExchangeOnlineService_IsOauth2ClientCredentials_TrueWhenAllFieldsPresent(): void
    {
        $settings = $this->getOauth2Config();
        $this->assertTrue(SmtpOauthExchangeOnlineService::isOauth2ClientCredentials($settings));
    }

    /**
     * @dataProvider missingFieldProvider
     */
    public function testSmtpOauthExchangeOnlineService_IsOauth2ClientCredentials_FalseWhenFieldMissing(string $missingField): void
    {
        $settings = $this->getOauth2Config();
        unset($settings[$missingField]);
        $this->assertFalse(SmtpOauthExchangeOnlineService::isOauth2ClientCredentials($settings));
    }

    /**
     * @dataProvider missingFieldProvider
     */
    public function testSmtpOauthExchangeOnlineService_IsOauth2ClientCredentials_FalseWhenFieldEmpty(string $emptyField): void
    {
        $settings = $this->getOauth2Config([$emptyField => '']);
        $this->assertFalse(SmtpOauthExchangeOnlineService::isOauth2ClientCredentials($settings));
    }

    /**
     * @dataProvider missingFieldProvider
     */
    public function testIsOauth2ClientCredentials_FalseWhenFieldNull(string $nullField): void
    {
        $settings = $this->getOauth2Config([$nullField => null]);
        $this->assertFalse(SmtpOauthExchangeOnlineService::isOauth2ClientCredentials($settings));
    }

    public static function missingFieldProvider(): array
    {
        return [
            ['tenant_id'],
            ['client_id'],
            ['client_secret'],
            ['oauth_username'],
        ];
    }

    // ---------------------------
    // Helper methods
    // ---------------------------

    /**
     * Helper to build a valid OAuth2 config array.
     *
     * @param array $overrides Optional overrides.
     * @return array
     */
    private function getOauth2Config(array $overrides = []): array
    {
        return array_merge([
            'tenant_id' => 'a1b2c3d4-e5f6-7890-abcd-ef1234567890',
            'client_id' => 'b2c3d4e5-f6a7-8901-bcde-f12345678901',
            'client_secret' => 'my-client-secret',
            'oauth_username' => 'user@example.com',
        ], $overrides);
    }

    /**
     * Helper to create a mock Cake\Http\Client\Response.
     *
     * @param int $statusCode HTTP status code.
     * @param array $body JSON body as array.
     * @return \Cake\Http\Client\Response
     */
    private function createMockResponse(int $statusCode, array $body): Response
    {
        $response = $this->createMock(Response::class);
        $response->method('isOk')->willReturn($statusCode >= 200 && $statusCode < 300);
        $response->method('getJson')->willReturn($body);

        return $response;
    }
}
