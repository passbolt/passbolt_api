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
 * @since         5.14.0
 */
namespace Passbolt\PasskeyAuthentication\Test\TestCase\Service;

use App\Model\Entity\Role;
use App\Test\Factory\OrganizationSettingFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Http\Exception\ForbiddenException;
use Passbolt\PasskeyAuthentication\Service\PasskeyOrganizationSettingsService;

/**
 * @group passkey
 */
class PasskeyOrganizationSettingsServiceTest extends AppTestCase
{
    /**
     * @var \Passbolt\PasskeyAuthentication\Service\PasskeyOrganizationSettingsService
     */
    protected PasskeyOrganizationSettingsService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new PasskeyOrganizationSettingsService();
    }

    public function testIsEnabled_DefaultsToTrue(): void
    {
        // No setting stored at all: passkey login is enabled by default.
        $this->assertTrue($this->service->isEnabled());
    }

    public function testIsEnabled_FalseWhenDisabledSettingPersisted(): void
    {
        OrganizationSettingFactory::make()
            ->setPropertyAndValue(
                PasskeyOrganizationSettingsService::SETTINGS_PROPERTY,
                ['enabled' => false]
            )
            ->persist();

        $this->assertFalse($this->service->isEnabled());
    }

    public function testIsEnabled_TrueWhenEnabledSettingPersisted(): void
    {
        OrganizationSettingFactory::make()
            ->setPropertyAndValue(
                PasskeyOrganizationSettingsService::SETTINGS_PROPERTY,
                ['enabled' => true]
            )
            ->persist();

        $this->assertTrue($this->service->isEnabled());
    }

    public function testSetEnabled_RequiresAdmin(): void
    {
        $uac = new UserAccessControl(Role::USER, UuidFactory::uuid());

        $this->expectException(ForbiddenException::class);
        $this->service->setEnabled($uac, false);
    }

    public function testSetEnabled_AdminPersistsFlag(): void
    {
        $admin = UserFactory::make()->admin()->persist();
        $uac = new UserAccessControl(Role::ADMIN, $admin->id);

        $this->service->setEnabled($uac, false);
        $this->assertFalse($this->service->isEnabled());

        // Re-enabling restores the default behaviour.
        $this->service->setEnabled($uac, true);
        $this->assertTrue($this->service->isEnabled());
    }
}
