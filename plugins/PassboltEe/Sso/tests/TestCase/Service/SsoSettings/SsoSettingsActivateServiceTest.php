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

namespace Passbolt\Sso\Test\TestCase\Service\SsoSettings;

use App\Model\Entity\Role;
use App\Test\Factory\UserFactory;
use App\Utility\ExtendedUserAccessControl;
use App\Utility\UuidFactory;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Passbolt\Sso\Model\Entity\SsoSetting;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Service\SsoSettings\SsoSettingsActivateService;
use Passbolt\Sso\Test\Factory\SsoAuthenticationTokenFactory;
use Passbolt\Sso\Test\Factory\SsoSettingsFactory;
use Passbolt\Sso\Test\Lib\SsoTestCase;

class SsoSettingsActivateServiceTest extends SsoTestCase
{
    public function testSsoSettingsActivateService_Success(): void
    {
        // Setup events
        EventManager::instance()->setEventList(new EventList());

        SsoSettingsFactory::make()->azure()->active()->persist();
        SsoSettingsFactory::make()->azure()->draft()->persist();
        $settings = SsoSettingsFactory::make()->azure()->persist();
        $user = UserFactory::make()->admin()->active()->persist();
        $ip = '127.0.0.1';
        $ua = 'phpunit';
        $uac = new ExtendedUserAccessControl(Role::ADMIN, $user->id, $user->username, $ip, $ua);
        $ssoAuthToken = SsoAuthenticationTokenFactory::make()
            ->type(SsoState::TYPE_SSO_SET_SETTINGS)
            ->userId($user->id)
            ->active()
            ->data([
                'sso_setting_id' => $settings->id,
                'ip' => $ip,
                'user_agent' => $ua,
            ])
            ->persist();

        $sut = new SsoSettingsActivateService();
        $activeSetting = $sut->activate($uac, $settings->id, [
            'status' => SsoSetting::STATUS_ACTIVE,
            'token' => $ssoAuthToken->token,
        ]);

        /** @var SsoSetting $result */
        $result = SsoSettingsFactory::find()->firstOrFail();
        $this->assertEquals($activeSetting->id, $result->id);
        /** @var \Passbolt\Sso\Model\Entity\SsoAuthenticationToken $updatedToken */
        $updatedToken = SsoAuthenticationTokenFactory::find()->firstOrFail();
        $this->assertEquals(SsoState::TYPE_SSO_SET_SETTINGS, $updatedToken->type);
        $this->assertFalse($updatedToken->active);
        $this->assertEquals(1, SsoSettingsFactory::count());
        // Assert event is fired
        $this->assertEventFiredWith(
            SsoSettingsActivateService::AFTER_ACTIVATE_SSO_SETTINGS_EVENT,
            'uac',
            $uac
        );
    }

    public function testSsoSettingsActivateService_Error_UserNotAdmin(): void
    {
        $ip = '127.0.0.1';
        $ua = 'phpunit';
        $uac = new ExtendedUserAccessControl(Role::USER, UuidFactory::uuid(), 'test@passbolt.com', $ip, $ua);
        $sut = new SsoSettingsActivateService();
        $this->expectException(ForbiddenException::class);
        $sut->activate($uac, UuidFactory::uuid(), [
            'status' => SsoSetting::STATUS_ACTIVE,
            'token' => UuidFactory::uuid(),
        ]);
    }

    public function testSsoSettingsActivateService_Error_StatusNotActive(): void
    {
        $ip = '127.0.0.1';
        $ua = 'phpunit';
        $uac = new ExtendedUserAccessControl(Role::ADMIN, UuidFactory::uuid(), 'test@passbolt.com', $ip, $ua);
        $sut = new SsoSettingsActivateService();
        $this->expectException(BadRequestException::class);
        $sut->activate($uac, UuidFactory::uuid(), [
            'status' => SsoSetting::STATUS_DRAFT,
            'token' => UuidFactory::uuid(),
        ]);
    }

    public function testSsoSettingsActivateService_Error_SettingsNotUuid(): void
    {
        $ip = '127.0.0.1';
        $ua = 'phpunit';
        $uac = new ExtendedUserAccessControl(Role::ADMIN, UuidFactory::uuid(), 'test@passbolt.com', $ip, $ua);
        $sut = new SsoSettingsActivateService();
        $this->expectException(BadRequestException::class);
        $sut->activate($uac, 'nope', [
            'status' => SsoSetting::STATUS_ACTIVE,
            'token' => UuidFactory::uuid(),
        ]);
    }

    public function testSsoSettingsActivateService_Error_SettingsNotFound(): void
    {
        $ip = '127.0.0.1';
        $ua = 'phpunit';
        $uac = new ExtendedUserAccessControl(Role::ADMIN, UuidFactory::uuid(), 'test@passbolt.com', $ip, $ua);
        $sut = new SsoSettingsActivateService();
        $this->expectException(NotFoundException::class);
        $sut->activate($uac, UuidFactory::uuid(), [
            'status' => SsoSetting::STATUS_ACTIVE,
            'token' => UuidFactory::uuid(),
        ]);
    }

    public function testSsoSettingsActivateService_Error_SettingsStatusNotDraft(): void
    {
        $settings = SsoSettingsFactory::make()->active()->azure()->persist();
        $user = UserFactory::make()->admin()->active()->persist();
        $ip = '127.0.0.1';
        $ua = 'phpunit';
        $uac = new ExtendedUserAccessControl(Role::ADMIN, $user->id, $user->username, $ip, $ua);
        $ssoAuthToken = SsoAuthenticationTokenFactory::make()
            ->type(SsoState::TYPE_SSO_SET_SETTINGS)
            ->userId($user->id)
            ->active()
            ->data([
                'sso_setting_id' => $settings->id,
                'ip' => $ip,
                'user_agent' => $ua,
            ])
            ->persist();

        $this->expectException(BadRequestException::class);

        $sut = new SsoSettingsActivateService();
        $sut->activate($uac, $settings->id, [
            'status' => SsoSetting::STATUS_ACTIVE,
            'token' => $ssoAuthToken->token,
        ]);
    }

    public function testSsoSettingsActivateService_Error_AuthTokenNotUuid(): void
    {
        $settings = SsoSettingsFactory::make()->azure()->persist();
        $user = UserFactory::make()->admin()->active()->persist();
        $ip = '127.0.0.1';
        $ua = 'phpunit';
        $uac = new ExtendedUserAccessControl(Role::ADMIN, $user->id, $user->username, $ip, $ua);

        $sut = new SsoSettingsActivateService();
        $this->expectException(BadRequestException::class);
        $sut->activate($uac, $settings->id, [
            'status' => SsoSetting::STATUS_ACTIVE,
            'token' => 'nope',
        ]);
    }
}
