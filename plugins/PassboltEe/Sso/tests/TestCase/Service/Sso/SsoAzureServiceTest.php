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

namespace Passbolt\Sso\Test\TestCase\Service\Sso;

use App\Service\Cookie\DefaultSecureCookieService;
use App\Test\Factory\UserFactory;
use App\Utility\ExtendedUserAccessControl;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\Http\Exception\BadRequestException;
use Cake\I18n\DateTime;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Service\Sso\AbstractSsoService;
use Passbolt\Sso\Service\Sso\Azure\SsoAzureService;
use Passbolt\Sso\Test\Factory\SsoSettingsFactory;
use Passbolt\Sso\Test\Factory\SsoStateFactory;
use Passbolt\Sso\Test\Lib\AzureProviderTestTrait;
use Passbolt\Sso\Test\Lib\SsoIntegrationTestCase;
use Passbolt\Sso\Utility\Azure\Provider\AzureProvider;
use Passbolt\Sso\Utility\Provider\SsoProviderFactory;

/**
 * @covers \Passbolt\Sso\Service\Sso\Azure\SsoAzureService
 */
class SsoAzureServiceTest extends SsoIntegrationTestCase
{
    use AzureProviderTestTrait;

    public function testSsoAzureService_Success(): void
    {
        // Load default plugin config
        $this->loadPlugins(['Passbolt/Sso' => []]);
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->active()->persist();
        $settings = SsoSettingsFactory::make()->azure()->active()->persist();
        // Decrypt settings data
        $gpg = OpenPGPBackendFactory::get();
        $gpg->setDecryptKeyFromFingerprint(
            Configure::read('passbolt.gpg.serverKey.fingerprint'),
            Configure::read('passbolt.gpg.serverKey.passphrase')
        );
        $settingsData = json_decode($gpg->decrypt($settings->get('data')), true);
        // Mock provider
        $mockAzureProvider = $this->getProviderMockForStage1(AzureProvider::class);
        $state = SsoState::generate();
        $url = $this->getDummyAzureAuthorizationUrl($admin, $state, [
            'tenant_id' => $settingsData['tenant_id'],
            'client_id' => $settingsData['client_id'],
        ]);
        $mockAzureProvider->method('getAuthorizationUrl')->willReturn($url);
        $mockAzureProvider->method('getState')->willReturn($state);
        // Swap actual implementation
        SsoProviderFactory::set($mockAzureProvider);
        $uac = new ExtendedUserAccessControl($admin->role->name, $admin->id, $admin->username, '127.0.0.1', 'phpunit');

        // Main service features = generate url + cookie
        $sut = new SsoAzureService(new DefaultSecureCookieService());
        $url = $sut->getAuthorizationUrl($uac);
        $cookie = $sut->createStateCookie($uac, SsoState::TYPE_SSO_SET_SETTINGS);

        // Check state & nonce values are present
        $this->assertStringContainsString('state=', $url);
        $this->assertStringContainsString('nonce=', $url);

        // Check SSO state props
        /** @var \Passbolt\Sso\Model\Entity\SsoState $ssoState */
        $ssoState = SsoStateFactory::find()->firstOrFail();
        $this->assertInstanceOf(SsoState::class, $ssoState);
        $this->assertEquals($admin->id, $ssoState->user_id);
        $this->assertEquals('127.0.0.1', $ssoState->ip);
        $this->assertEquals('phpunit', $ssoState->user_agent);
        $this->assertEquals($settings->get('id'), $ssoState->sso_settings_id);

        // Check URL props
        $this->assertNotNull($settingsData);
        $this->assertTrue(is_string($settingsData['url']));
        $this->assertTrue(is_string($settingsData['tenant_id']));
        $this->assertTrue(is_string($settingsData['client_id']));
        $this->assertStringContainsString($settingsData['url'] . '/', $url);
        $this->assertStringContainsString('/' . $settingsData['tenant_id'] . '/', $url);
        $this->assertStringContainsString('client_id=' . $settingsData['client_id'], $url);
        $this->assertStringContainsString('state=' . $ssoState->state, $url);
        $this->assertStringContainsString('prompt=login', $url);
        $this->assertStringContainsString('login_hint=' . rawurlencode($admin->username), $url);

        // Check cookie props
        $this->assertTrue($cookie->isHttpOnly());
        $this->assertTrue($cookie->isSecure());
        $this->assertEquals($ssoState->state, $cookie->getValue());
    }

    public function testSsoAzureService_getAuthorizationUrl_Response_Mode_Query(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->active()->persist();
        $settings = SsoSettingsFactory::make()->azure()->active()->persist();
        // Decrypt settings data
        $gpg = OpenPGPBackendFactory::get();
        $gpg->setDecryptKeyFromFingerprint(
            Configure::read('passbolt.gpg.serverKey.fingerprint'),
            Configure::read('passbolt.gpg.serverKey.passphrase')
        );
        $settingsData = json_decode($gpg->decrypt($settings->get('data')), true);
        // Mock provider
        $mockAzureProvider = $this->getProviderMockForStage1(AzureProvider::class);
        $state = SsoState::generate();
        $url = $this->getDummyAzureAuthorizationUrl($user, $state, [
            'tenant_id' => $settingsData['tenant_id'],
            'client_id' => $settingsData['client_id'],
        ]);
        $mockAzureProvider->method('getAuthorizationUrl')->willReturn($url);
        $mockAzureProvider->method('getState')->willReturn($state);
        // Swap actual implementation
        SsoProviderFactory::set($mockAzureProvider);
        $uac = new ExtendedUserAccessControl($user->role->name, $user->id, $user->username, '127.0.0.1', 'phpunit');

        // Main service features = generate url + cookie
        $sut = new SsoAzureService(new DefaultSecureCookieService());
        $url = $sut->getAuthorizationUrl($uac);
        $this->assertTextContains('response_mode=query', $url);
    }

    public function testSsoAzureService_getAuthorizationUrl_Response_Mode_Post(): void
    {
        Configure::write(AbstractSsoService::SSO_SECURITY_REDIRECT_METHOD_CONFIG, 'POST');
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->active()->persist();
        $settings = SsoSettingsFactory::make()->azure()->active()->persist();
        // Decrypt settings data
        $gpg = OpenPGPBackendFactory::get();
        $gpg->setDecryptKeyFromFingerprint(
            Configure::read('passbolt.gpg.serverKey.fingerprint'),
            Configure::read('passbolt.gpg.serverKey.passphrase')
        );
        $settingsData = json_decode($gpg->decrypt($settings->get('data')), true);
        // Mock provider
        $mockAzureProvider = $this->getProviderMockForStage1(AzureProvider::class);
        $state = SsoState::generate();
        $url = $this->getDummyAzureAuthorizationUrl($user, $state, [
            'tenant_id' => $settingsData['tenant_id'],
            'client_id' => $settingsData['client_id'],
        ]);
        $mockAzureProvider->method('getAuthorizationUrl')->willReturn($url);
        $mockAzureProvider->method('getState')->willReturn($state);
        // Swap actual implementation
        SsoProviderFactory::set($mockAzureProvider);
        $uac = new ExtendedUserAccessControl($user->role->name, $user->id, $user->username, '127.0.0.1', 'phpunit');

        // Main service features = generate url + cookie
        $sut = new SsoAzureService(new DefaultSecureCookieService());
        $url = $sut->getAuthorizationUrl($uac);
        $this->assertTextContains('response_mode=form_post', $url);
    }

    public function testSsoAzureService_Error(): void
    {
        $this->markTestIncomplete();
    }

    public function testSsoAzureService_assertResourceOwnerAgainstSsoState_SuccessPromptLogin(): void
    {
        $nonce = SsoState::generate();
        UserFactory::make()->admin()->active()->persist();
        SsoSettingsFactory::make()->azure()->active()->persist();
        $ssoState = SsoStateFactory::make([
            'nonce' => $nonce,
            'created' => DateTime::now()->subMinutes(2),
        ])->withTypeSsoGetKey()->persist();
        // Mock resource owner object
        $resourceOwner = $this->mockAzureResourceOwner([
            'oid' => UuidFactory::uuid(),
            'email' => 'ada@passbolt.com',
            'nonce' => $nonce,
            'auth_time' => DateTime::now()->getTimestamp(),
        ]);

        $sut = new SsoAzureService(new DefaultSecureCookieService());
        $sut->assertResourceOwnerAgainstSsoState($resourceOwner, $ssoState);

        $this->assertTrue(true);
    }

    public function testSsoAzureService_assertResourceOwnerAgainstSsoState_SuccessPromptNone(): void
    {
        $nonce = SsoState::generate();
        UserFactory::make()->admin()->active()->persist();
        SsoSettingsFactory::make()->azure()->active()->persist();
        $ssoState = SsoStateFactory::make([
            'nonce' => $nonce,
            'created' => DateTime::now()->subMinutes(2),
        ])->withTypeSsoGetKey()->persist();
        // Mock resource owner object
        $resourceOwner = $this->mockAzureResourceOwner([
            'oid' => UuidFactory::uuid(),
            'email' => 'ada@passbolt.com',
            'nonce' => $nonce,
            'auth_time' => DateTime::now()->getTimestamp(),
        ]);

        $sut = new SsoAzureService(new DefaultSecureCookieService());
        $sut->assertResourceOwnerAgainstSsoState($resourceOwner, $ssoState);

        $this->assertTrue(true);
    }

    public function testSsoAzureService_assertResourceOwnerAgainstSsoState_ErrorNonceMismatch(): void
    {
        UserFactory::make()->admin()->active()->persist();
        SsoSettingsFactory::make()->azure()->active()->persist();
        $ssoState = SsoStateFactory::make([
            'nonce' => SsoState::generate(),
            'created' => DateTime::now()->subMinutes(2),
        ])->withTypeSsoGetKey()->persist();
        // Mock resource owner object
        $resourceOwner = $this->mockAzureResourceOwner([
            'oid' => UuidFactory::uuid(),
            'email' => 'ada@passbolt.com',
            'nonce' => SsoState::generate(), // different nonce value than sso state
            'auth_time' => DateTime::now()->getTimestamp(),
        ]);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('Invalid nonce');

        $sut = new SsoAzureService(new DefaultSecureCookieService());
        $sut->assertResourceOwnerAgainstSsoState($resourceOwner, $ssoState);
    }

    public function testSsoAzureService_assertResourceOwnerAgainstSsoState_ErrorAuthTime(): void
    {
        $nonce = SsoState::generate();
        UserFactory::make()->admin()->active()->persist();
        SsoSettingsFactory::make()->azure()->active()->persist();
        $ssoState = SsoStateFactory::make([
            'nonce' => $nonce,
            'created' => DateTime::now(),
        ])->withTypeSsoGetKey()->persist();
        // Mock resource owner object
        $resourceOwner = $this->mockAzureResourceOwner([
            'oid' => UuidFactory::uuid(),
            'email' => 'ada@passbolt.com',
            'nonce' => $nonce,
            'auth_time' => DateTime::now()->subMinutes(5)->getTimestamp(), // less than sso state create date time
        ]);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('You must authenticate with Azure again');

        $sut = new SsoAzureService(new DefaultSecureCookieService());
        $sut->assertResourceOwnerAgainstSsoState($resourceOwner, $ssoState);
    }
}
