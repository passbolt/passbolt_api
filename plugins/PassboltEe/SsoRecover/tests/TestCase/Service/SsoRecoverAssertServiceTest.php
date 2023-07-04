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
 * @since         3.11.0
 */
namespace Passbolt\SsoRecover\Test\TestCase\Service;

use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\Http\Exception\BadRequestException;
use Cake\Routing\Router;
use Passbolt\SelfRegistration\Test\Lib\SelfRegistrationTestTrait;
use Passbolt\Sso\Model\Dto\SsoSettingsDto;
use Passbolt\Sso\Model\Entity\SsoSetting;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Test\Factory\SsoAuthenticationTokenFactory;
use Passbolt\Sso\Test\Factory\SsoSettingsFactory;
use Passbolt\Sso\Test\Factory\SsoStateFactory;
use Passbolt\Sso\Test\Lib\MockAzureResourceOwnerTrait;
use Passbolt\Sso\Test\TestCase\Service\Sso\TestableSsoService;
use Passbolt\SsoRecover\Service\SsoRecoverAssertService;

/**
 * @covers \Passbolt\SsoRecover\Service\SsoRecoverAssertService
 */
class SsoRecoverAssertServiceTest extends AppTestCase
{
    use SelfRegistrationTestTrait;
    use MockAzureResourceOwnerTrait;

    /**
     * @var \Passbolt\SsoRecover\Service\SsoRecoverAssertService
     */
    private $service;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new SsoRecoverAssertService();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);

        parent::tearDown();
    }

    public function testAssertStateCodeAndGetAuthToken_ErrorNonceMismatch(): void
    {
        $nonce = SsoState::generate();
        $ip = '127.0.0.1';
        $userAgent = 'phpunit';
        $user = UserFactory::make()->user()->active()->persist();
        $ssoSetting = SsoSettingsFactory::make()->active()->persist();
        $ssoState = SsoStateFactory::make(['user_id' => null, 'nonce' => $nonce, 'ip' => $ip, 'user_agent' => $userAgent])
            ->withTypeSsoRecover()
            ->ssoSettingsId($ssoSetting->id)
            ->persist();
        // Mock azure SSO service to return specific resource owner
        $azureResourceOwner = $this->mockAzureResourceOwner(['email' => $user->username, 'nonce' => 'different']);
        $ssoService = $this
            ->getMockBuilder(TestableSsoService::class)
            ->onlyMethods(['getResourceOwner', 'getSettings']) // let other methods do it's work(i.e. behave naturally)
            ->getMock();
        $ssoService->method('getResourceOwner')->willReturn($azureResourceOwner);
        $settingsDto = new SsoSettingsDto($ssoSetting, []);
        $ssoService->method('getSettings')->willReturn($settingsDto);

        $this->expectException(BadRequestException::class);
        $this->expectErrorMessage('Invalid nonce');

        $this->service->assertAndGetRedirectUrl(
            $ssoService,
            $ssoState,
            '123456',
            $ip,
            $userAgent,
            SsoSetting::PROVIDER_AZURE
        );
    }

    public function testAssertStateCodeAndGetAuthToken_ErrorUserNotExist(): void
    {
        $nonce = SsoState::generate();
        $ip = '127.0.0.1';
        $userAgent = 'phpunit';
        $ssoSetting = SsoSettingsFactory::make()->active()->persist();
        $ssoState = SsoStateFactory::make(['user_id' => null, 'nonce' => $nonce, 'ip' => $ip, 'user_agent' => $userAgent])
            ->withTypeSsoRecover()
            ->ssoSettingsId($ssoSetting->id)
            ->persist();
        // Mock azure SSO service to return specific resource owner
        $azureResourceOwner = $this->mockAzureResourceOwner(['email' => 'foo@test.test', 'nonce' => $nonce]);
        $ssoService = $this
            ->getMockBuilder(TestableSsoService::class)
            ->onlyMethods(['getResourceOwner', 'getSettings']) // let other methods do it's work(i.e. behave naturally)
            ->getMock();
        $ssoService->method('getResourceOwner')->willReturn($azureResourceOwner);
        $settingsDto = new SsoSettingsDto($ssoSetting, []);
        $ssoService->method('getSettings')->willReturn($settingsDto);

        $this->expectException(BadRequestException::class);
        $this->expectErrorMessage('Access to this service requires an invitation');

        $this->service->assertAndGetRedirectUrl(
            $ssoService,
            $ssoState,
            '123456',
            $ip,
            $userAgent,
            SsoSetting::PROVIDER_AZURE
        );
    }

    public function testAssertStateCodeAndGetAuthToken_ErrorUserDeleted(): void
    {
        $nonce = SsoState::generate();
        $ip = '127.0.0.1';
        $userAgent = 'phpunit';
        $user = UserFactory::make()->user()->deleted()->persist();
        $ssoSetting = SsoSettingsFactory::make()->active()->persist();
        $ssoState = SsoStateFactory::make(['user_id' => null, 'nonce' => $nonce, 'ip' => $ip, 'user_agent' => $userAgent])
            ->withTypeSsoRecover()
            ->ssoSettingsId($ssoSetting->id)
            ->persist();
        // Mock azure SSO service to return specific resource owner
        $azureResourceOwner = $this->mockAzureResourceOwner(['email' => $user->username, 'nonce' => $nonce]);
        $ssoService = $this
            ->getMockBuilder(TestableSsoService::class)
            ->onlyMethods(['getResourceOwner', 'getSettings']) // let other methods do it's work(i.e. behave naturally)
            ->getMock();
        $ssoService->method('getResourceOwner')->willReturn($azureResourceOwner);
        $settingsDto = new SsoSettingsDto($ssoSetting, []);
        $ssoService->method('getSettings')->willReturn($settingsDto);

        $this->expectException(BadRequestException::class);
        $this->expectErrorMessage('Access to this service requires an invitation');

        $this->service->assertAndGetRedirectUrl(
            $ssoService,
            $ssoState,
            '123456',
            $ip,
            $userAgent,
            SsoSetting::PROVIDER_AZURE
        );
    }

    public function testAssertStateCodeAndGetAuthToken_ErrorStateExpired(): void
    {
        $nonce = SsoState::generate();
        $ip = '127.0.0.1';
        $userAgent = 'phpunit';
        $user = UserFactory::make()->user()->active()->persist();
        $ssoSetting = SsoSettingsFactory::make()->active()->persist();
        $ssoState = SsoStateFactory::make(['user_id' => null, 'nonce' => $nonce, 'ip' => $ip, 'user_agent' => $userAgent])
            ->withTypeSsoRecover()
            ->ssoSettingsId($ssoSetting->id)
            ->deleted()
            ->persist();
        // Mock azure SSO service to return specific resource owner
        $azureResourceOwner = $this->mockAzureResourceOwner(['email' => $user->username, 'nonce' => $nonce]);
        $ssoService = $this
            ->getMockBuilder(TestableSsoService::class)
            ->onlyMethods(['getResourceOwner', 'getSettings']) // let other methods do it's work(i.e. behave naturally)
            ->getMock();
        $ssoService->method('getResourceOwner')->willReturn($azureResourceOwner);
        $settingsDto = new SsoSettingsDto($ssoSetting, []);
        $ssoService->method('getSettings')->willReturn($settingsDto);

        $this->expectException(BadRequestException::class);
        $this->expectErrorMessage('The SSO state is expired');

        $this->service->assertAndGetRedirectUrl(
            $ssoService,
            $ssoState,
            '123456',
            $ip,
            $userAgent,
            SsoSetting::PROVIDER_AZURE
        );
    }

    public function testAssertStateCodeAndGetAuthToken_Success_Azure(): void
    {
        $nonce = SsoState::generate();
        $ip = '127.0.0.1';
        $userAgent = 'phpunit';
        $user = UserFactory::make()->user()->active()->persist();
        $ssoSetting = SsoSettingsFactory::make()->active()->persist();
        $ssoState = SsoStateFactory::make(['user_id' => null, 'nonce' => $nonce, 'ip' => $ip, 'user_agent' => $userAgent])
            ->withTypeSsoRecover()
            ->ssoSettingsId($ssoSetting->id)
            ->persist();
        // Mock azure SSO service to return specific resource owner
        $azureResourceOwner = $this->mockAzureResourceOwner(['email' => $user->username, 'nonce' => $nonce]);
        $ssoService = $this->getMockBuilder(TestableSsoService::class)->getMock();
        $ssoService->method('getResourceOwner')->willReturn($azureResourceOwner);
        $settingsDto = new SsoSettingsDto($ssoSetting, []);
        $ssoService->method('getSettings')->willReturn($settingsDto);

        $result = $this->service->assertAndGetRedirectUrl(
            $ssoService,
            $ssoState,
            '123456',
            $ip,
            $userAgent,
            SsoSetting::PROVIDER_AZURE
        );

        /** @var \Passbolt\Sso\Model\Entity\SsoAuthenticationToken $ssoAuthToken */
        $ssoAuthToken = SsoAuthenticationTokenFactory::find()->firstOrFail();
        $this->assertEquals(
            Router::url("/sso/recover/azure/success?token={$ssoAuthToken->token}", true),
            $result
        );
    }

    public function testAssertStateCodeAndGetAuthToken_Success_Google(): void
    {
        $nonce = SsoState::generate();
        $ip = '127.0.0.1';
        $userAgent = 'phpunit';
        $user = UserFactory::make()->user()->active()->persist();
        $ssoSetting = SsoSettingsFactory::make()->active()->persist();
        $ssoState = SsoStateFactory::make(['user_id' => null, 'nonce' => $nonce, 'ip' => $ip, 'user_agent' => $userAgent])
            ->withTypeSsoRecover()
            ->ssoSettingsId($ssoSetting->id)
            ->persist();
        // Mock azure SSO service to return specific resource owner
        $azureResourceOwner = $this->mockAzureResourceOwner(['email' => $user->username, 'nonce' => $nonce]);
        $ssoService = $this->getMockBuilder(TestableSsoService::class)->getMock();
        $ssoService->method('getResourceOwner')->willReturn($azureResourceOwner);
        $settingsDto = new SsoSettingsDto($ssoSetting, []);
        $ssoService->method('getSettings')->willReturn($settingsDto);

        $result = $this->service->assertAndGetRedirectUrl(
            $ssoService,
            $ssoState,
            '123456',
            $ip,
            $userAgent,
            SsoSetting::PROVIDER_GOOGLE
        );

        /** @var \Passbolt\Sso\Model\Entity\SsoAuthenticationToken $ssoAuthToken */
        $ssoAuthToken = SsoAuthenticationTokenFactory::find()->firstOrFail();
        $this->assertEquals(
            Router::url("/sso/recover/google/success?token={$ssoAuthToken->token}", true),
            $result
        );
    }

    public function testAssertAndGetRedirectUrl_Success_SelfRegistration(): void
    {
        $nonce = SsoState::generate();
        $ip = '127.0.0.1';
        $userAgent = 'phpunit';
        $userEmail = 'ada@passbolt.com';
        $ssoSetting = SsoSettingsFactory::make()->active()->persist();
        $ssoState = SsoStateFactory::make(['user_id' => null, 'nonce' => $nonce, 'ip' => $ip, 'user_agent' => $userAgent])
            ->withTypeSsoRecover()
            ->ssoSettingsId($ssoSetting->id)
            ->persist();
        // Set self-registration data
        $this->setSelfRegistrationSettingsData();
        // Mock azure SSO service to return specific resource owner
        $azureResourceOwner = $this->mockAzureResourceOwner(['email' => $userEmail, 'nonce' => $nonce]);
        $ssoService = $this->getMockBuilder(TestableSsoService::class)->getMock();
        $ssoService->method('getResourceOwner')->willReturn($azureResourceOwner);
        $settingsDto = new SsoSettingsDto($ssoSetting, []);
        $ssoService->method('getSettings')->willReturn($settingsDto);

        $result = $this->service->assertAndGetRedirectUrl(
            $ssoService,
            $ssoState,
            '123456',
            $ip,
            $userAgent,
            SsoSetting::PROVIDER_AZURE
        );

        $this->assertEquals(Router::url("/sso/recover/error?email={$userEmail}", true), $result);
    }

    public function testAssertAndGetRedirectUrl_Error_SelfRegistrationDisabled(): void
    {
        $nonce = SsoState::generate();
        $ip = '127.0.0.1';
        $userAgent = 'phpunit';
        $userEmail = 'ada@passbolt.com';
        $ssoSetting = SsoSettingsFactory::make()->active()->persist();
        $ssoState = SsoStateFactory::make(['user_id' => null, 'nonce' => $nonce, 'ip' => $ip, 'user_agent' => $userAgent])
            ->withTypeSsoRecover()
            ->ssoSettingsId($ssoSetting->id)
            ->persist();
        // Mock azure SSO service to return specific resource owner
        $azureResourceOwner = $this->mockAzureResourceOwner(['email' => $userEmail, 'nonce' => $nonce]);
        $ssoService = $this->getMockBuilder(TestableSsoService::class)->getMock();
        $ssoService->method('getResourceOwner')->willReturn($azureResourceOwner);
        $settingsDto = new SsoSettingsDto($ssoSetting, []);
        $ssoService->method('getSettings')->willReturn($settingsDto);
        // Disable plugin
        $this->disableFeaturePlugin('SelfRegistration');

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('user does not exist or has been deleted');

        $this->service->assertAndGetRedirectUrl(
            $ssoService,
            $ssoState,
            '123456',
            $ip,
            $userAgent,
            SsoSetting::PROVIDER_AZURE
        );
    }

    public function testAssertAndGetRedirectUrl_Error_EmailNotAllowed(): void
    {
        $nonce = SsoState::generate();
        $ip = '127.0.0.1';
        $userAgent = 'phpunit';
        $userEmail = 'foo@not-a-passbolt.com';
        $ssoSetting = SsoSettingsFactory::make()->active()->persist();
        $ssoState = SsoStateFactory::make(['user_id' => null, 'nonce' => $nonce, 'ip' => $ip, 'user_agent' => $userAgent])
            ->withTypeSsoRecover()
            ->ssoSettingsId($ssoSetting->id)
            ->persist();
        // Set self-registration data
        $this->setSelfRegistrationSettingsData();
        // Mock azure SSO service to return specific resource owner
        $azureResourceOwner = $this->mockAzureResourceOwner(['email' => $userEmail, 'nonce' => $nonce]);
        $ssoService = $this->getMockBuilder(TestableSsoService::class)->getMock();
        $ssoService->method('getResourceOwner')->willReturn($azureResourceOwner);
        $settingsDto = new SsoSettingsDto($ssoSetting, []);
        $ssoService->method('getSettings')->willReturn($settingsDto);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('Access to this service requires an invitation');

        $this->service->assertAndGetRedirectUrl(
            $ssoService,
            $ssoState,
            '123456',
            $ip,
            $userAgent,
            SsoSetting::PROVIDER_AZURE
        );
    }
}
