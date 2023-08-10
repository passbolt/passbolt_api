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
use App\Test\Lib\Utility\ExtendedUserAccessControlTestTrait;
use App\Utility\ExtendedUserAccessControl;
use App\Utility\UuidFactory;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\TestSuite\Constraint\EventFired;
use Passbolt\Sso\Service\SsoSettings\SsoSettingsDeleteService;
use Passbolt\Sso\Test\Factory\SsoSettingsFactory;
use Passbolt\Sso\Test\Lib\SsoIntegrationTestCase;
use Passbolt\Sso\Test\Lib\SsoTestCase;

class SsoSettingsDeleteServiceTest extends SsoTestCase
{
    use ExtendedUserAccessControlTestTrait;

    public function testSsoSettingsDeleteService_Success(): void
    {
        // Setup events
        EventManager::instance()->setEventList(new EventList());

        $user = UserFactory::make()->admin()->persist();
        $setting = SsoSettingsFactory::make()->active()->persist();
        $this->assertEquals(1, SsoSettingsFactory::count());

        $uac = new ExtendedUserAccessControl(
            Role::ADMIN,
            $user->id,
            $user->username,
            SsoIntegrationTestCase::IP_ADDRESS,
            SsoIntegrationTestCase::USER_AGENT
        );
        (new SsoSettingsDeleteService())->delete($uac, $setting->id);

        $this->assertEquals(0, SsoSettingsFactory::count());

        // Event is fired if
        $this->assertEventFiredWith(
            SsoSettingsDeleteService::AFTER_DELETE_ACTIVE_SSO_SETTINGS_EVENT,
            'uac',
            $uac
        );
    }

    public function testSsoSettingsDeleteService_Success_Draft(): void
    {
        // Setup events
        EventManager::instance()->setEventList(new EventList());

        $user = UserFactory::make()->admin()->persist();
        $setting = SsoSettingsFactory::make()->draft()->persist();
        $this->assertEquals(1, SsoSettingsFactory::count());

        $uac = new ExtendedUserAccessControl(
            Role::ADMIN,
            $user->id,
            $user->username,
            SsoIntegrationTestCase::IP_ADDRESS,
            SsoIntegrationTestCase::USER_AGENT
        );
        (new SsoSettingsDeleteService())->delete($uac, $setting->id);

        $this->assertEquals(0, SsoSettingsFactory::count());

        // Check event was not fired
        /**
         * @psalm-suppress InternalClass
         * @psalm-suppress InternalMethod
         */
        $fired = (new EventFired(EventManager::instance()));
        /** @psalm-suppress InternalMethod */
        $this->assertEquals(0, $fired->matches(SsoSettingsDeleteService::AFTER_DELETE_ACTIVE_SSO_SETTINGS_EVENT));
    }

    public function testSsoSettingsDeleteService_Error_NotFound(): void
    {
        $uac = $this->mockExtendedAdminAccessControl();
        $this->expectException(NotFoundException::class);
        (new SsoSettingsDeleteService())->delete($uac, UuidFactory::uuid());
    }

    public function testSsoSettingsDeleteService_Error_InvalidId(): void
    {
        $uac = $this->mockExtendedAdminAccessControl();
        $this->expectException(BadRequestException::class);
        (new SsoSettingsDeleteService())->delete($uac, 'nope');
    }

    public function testSsoSettingsDeleteService_Error_NotAdmin(): void
    {
        $uac = $this->mockExtendedUserAccessControl();
        $this->expectException(BadRequestException::class);
        (new SsoSettingsDeleteService())->delete($uac, 'nope');
    }
}
