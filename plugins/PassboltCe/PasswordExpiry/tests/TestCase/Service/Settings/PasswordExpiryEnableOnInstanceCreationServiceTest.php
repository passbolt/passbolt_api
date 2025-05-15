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
 * @since         5.1.0
 */

namespace Passbolt\PasswordExpiry\Test\TestCase\Service\Settings;

use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Passbolt\PasswordExpiry\Service\Settings\PasswordExpiryEnableOnInstanceCreationService;
use Passbolt\PasswordExpiry\Test\Factory\PasswordExpirySettingFactory;

/**
 * @covers  \Passbolt\PasswordExpiry\Service\Settings\PasswordExpiryEnableOnInstanceCreationService
 */
class PasswordExpiryEnableOnInstanceCreationServiceTest extends AppTestCase
{
    private PasswordExpiryEnableOnInstanceCreationService $service;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->service = new PasswordExpiryEnableOnInstanceCreationService();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testPasswordExpiryEnableOnInstanceCreationService_With_One_Active_Admin()
    {
        UserFactory::make()->admin()->deleted()->persist();
        UserFactory::make()->admin()->inactive()->persist();
        $uac = UserFactory::make()->admin()->persistedUAC();
        $setting = $this->service->enableOnPassboltInstanceCreation($uac);
        $this->assertSame(1, PasswordExpirySettingFactory::count());
        $this->assertSame($uac->getId(), $setting->created_by);
        $this->assertSame($uac->getId(), $setting->modified_by);
    }

    public function testPasswordExpiryEnableOnInstanceCreationService_With_An_Active_Non_Admin_User()
    {
        UserFactory::make()->deleted()->persist();
        UserFactory::make()->inactive()->persist();
        $uac = UserFactory::make()->user()->persistedUAC();
        $setting = $this->service->enableOnPassboltInstanceCreation($uac);
        $this->assertNull($setting);
        $this->assertSame(0, PasswordExpirySettingFactory::count());
    }

    public function testPasswordExpiryEnableOnInstanceCreationService_With_Two_Active_Admins()
    {
        $uac = UserFactory::make()->admin()->persistedUAC();
        UserFactory::make()->admin()->persist();
        $setting = $this->service->enableOnPassboltInstanceCreation($uac);
        $this->assertNull($setting);
        $this->assertSame(0, PasswordExpirySettingFactory::count());
    }

    public function testPasswordExpiryEnableOnInstanceCreationService_With_Settings_Already_In_DB()
    {
        $uac = UserFactory::make()->admin()->persistedUAC();
        /** @var \App\Model\Entity\OrganizationSetting $setting */
        $setting = PasswordExpirySettingFactory::make()->persist();
        $result = $this->service->enableOnPassboltInstanceCreation($uac);
        $this->assertNull($result);
        $this->assertSame(1, PasswordExpirySettingFactory::count());
        $this->assertNotEquals($uac->getId(), $setting->created_by);
        $this->assertNotEquals($uac->getId(), $setting->modified_by);
    }
}
