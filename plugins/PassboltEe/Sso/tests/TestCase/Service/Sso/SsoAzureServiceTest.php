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
use App\Utility\UuidFactory;
use Cake\Http\Exception\BadRequestException;
use Cake\I18n\FrozenTime;
use Passbolt\Sso\Form\SsoSettingsAzureDataForm;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Service\Sso\Azure\SsoAzureService;
use Passbolt\Sso\Test\Factory\SsoStateFactory;
use Passbolt\Sso\Test\Lib\SsoIntegrationTestCase;

/**
 * @covers \Passbolt\Sso\Service\Sso\Azure\SsoAzureService
 */
class SsoAzureServiceTest extends SsoIntegrationTestCase
{
    public function testSsoAzureService_Success(): void
    {
        // Load default plugin config
        $this->loadPlugins(['Passbolt/Sso' => []]);

        $user = UserFactory::make()->admin()->active()->persist();
        $setting = $this->createAzureSettingsFromConfig($user);
        $uac = new ExtendedUserAccessControl($user->role->name, $user->id, $user->username, '127.0.0.1', 'phpunit');

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
        $this->assertEquals($user->id, $ssoState->user_id);
        $this->assertEquals('127.0.0.1', $ssoState->ip);
        $this->assertEquals('phpunit', $ssoState->user_agent);
        $this->assertEquals($setting->id, $ssoState->sso_settings_id);

        // Check URL props
        $data = $setting->getData();
        $this->assertNotNull($data);
        $data = $data->toArray();
        $this->assertTrue(is_string($data['url']));
        $this->assertTrue(is_string($data['tenant_id']));
        $this->assertTrue(is_string($data['client_id']));
        $this->assertStringContainsString($data['url'] . '/', $url);
        $this->assertStringContainsString('/' . $data['tenant_id'] . '/', $url);
        $this->assertStringContainsString('client_id=' . $data['client_id'], $url);
        $this->assertStringContainsString('state=' . $ssoState->state, $url);
        $this->assertStringContainsString('prompt=login', $url);
        $this->assertStringContainsString('login_hint=' . urlencode($user->username), $url);

        // Check cookie props
        $this->assertTrue($cookie->isHttpOnly());
        $this->assertTrue($cookie->isSecure());
        $this->assertEquals($ssoState->state, $cookie->getValue());
    }

    public function testSsoAzureService_Error(): void
    {
        $this->markTestIncomplete();
    }

    public function testSsoAzureService_assertResourceOwnerAgainstSsoState_SuccessPromptLogin(): void
    {
        $nonce = SsoState::generate();
        $user = UserFactory::make()->admin()->active()->persist();
        $this->createAzureSettingsFromConfig($user);
        $ssoState = SsoStateFactory::make([
            'nonce' => $nonce,
            'created' => FrozenTime::now()->subMinutes(2),
        ])->withTypeSsoGetKey()->persist();
        // Mock resource owner object
        $resourceOwner = $this->mockAzureResourceOwner([
            'oid' => UuidFactory::uuid(),
            'email' => 'ada@passbolt.com',
            'nonce' => $nonce,
            'auth_time' => FrozenTime::now()->getTimestamp(),
        ]);

        $sut = new SsoAzureService(new DefaultSecureCookieService());
        $sut->assertResourceOwnerAgainstSsoState($resourceOwner, $ssoState);

        $this->assertTrue(true);
    }

    public function testSsoAzureService_assertResourceOwnerAgainstSsoState_SuccessPromptNone(): void
    {
        $nonce = SsoState::generate();
        $user = UserFactory::make()->admin()->active()->persist();
        $this->createAzureSettingsFromConfig($user);
        $ssoState = SsoStateFactory::make([
            'nonce' => $nonce,
            'created' => FrozenTime::now()->subMinutes(2),
        ])->withTypeSsoGetKey()->persist();
        // Mock resource owner object
        $resourceOwner = $this->mockAzureResourceOwner([
            'oid' => UuidFactory::uuid(),
            'email' => 'ada@passbolt.com',
            'nonce' => $nonce,
            'auth_time' => FrozenTime::now()->getTimestamp(),
        ]);

        $sut = new SsoAzureService(new DefaultSecureCookieService());
        $sut->assertResourceOwnerAgainstSsoState($resourceOwner, $ssoState);

        $this->assertTrue(true);
    }

    public function testSsoAzureService_assertResourceOwnerAgainstSsoState_ErrorNonceMismatch(): void
    {
        $user = UserFactory::make()->admin()->active()->persist();
        $this->createAzureSettingsFromConfig($user);
        $ssoState = SsoStateFactory::make([
            'nonce' => SsoState::generate(),
            'created' => FrozenTime::now()->subMinutes(2),
        ])->withTypeSsoGetKey()->persist();
        // Mock resource owner object
        $resourceOwner = $this->mockAzureResourceOwner([
            'oid' => UuidFactory::uuid(),
            'email' => 'ada@passbolt.com',
            'nonce' => SsoState::generate(), // different nonce value than sso state
            'auth_time' => FrozenTime::now()->getTimestamp(),
        ]);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('Invalid nonce');

        $sut = new SsoAzureService(new DefaultSecureCookieService());
        $sut->assertResourceOwnerAgainstSsoState($resourceOwner, $ssoState);
    }

    public function testSsoAzureService_assertResourceOwnerAgainstSsoState_ErrorAuthTime(): void
    {
        $nonce = SsoState::generate();
        $user = UserFactory::make()->admin()->active()->persist();
        $settings = $this->createAzureSettingsFromConfig($user);
        $ssoState = SsoStateFactory::make([
            'nonce' => $nonce,
            'created' => FrozenTime::now(),
        ])->withTypeSsoGetKey()->persist();
        // Mock resource owner object
        $resourceOwner = $this->mockAzureResourceOwner([
            'oid' => UuidFactory::uuid(),
            'email' => 'ada@passbolt.com',
            'nonce' => $nonce,
            'auth_time' => FrozenTime::now()->subMinutes(5)->getTimestamp(), // less than sso state create date time
        ]);

        // Make sure prompt is "login", the error will only throw if prompt is "login"
        $this->assertEquals(SsoSettingsAzureDataForm::PROMPT_LOGIN, $settings->getData()->toArray()['prompt']);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('You must authenticate with Azure again');

        $sut = new SsoAzureService(new DefaultSecureCookieService());
        $sut->assertResourceOwnerAgainstSsoState($resourceOwner, $ssoState);
    }
}
