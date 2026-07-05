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
namespace Passbolt\PasskeyAuthentication\Test\TestCase\Controller;

use App\Test\Factory\OrganizationSettingFactory;
use App\Test\Lib\AppIntegrationTestCase;
use Passbolt\PasskeyAuthentication\Service\PasskeyOrganizationSettingsService;

/**
 * @group passkey
 */
class PasskeyOrgSettingsGetControllerTest extends AppIntegrationTestCase
{
    public function testPasskeyOrgSettingsGet_DefaultEnabledUnauthenticated(): void
    {
        // No session: the login page needs this to decide whether to show the passkey button.
        $this->getJson('/passkey/organization/settings.json?api-version=v2');

        $this->assertResponseSuccess();
        $this->assertTrue($this->_responseJsonBody->enabled);
    }

    public function testPasskeyOrgSettingsGet_ReflectsDisabledSetting(): void
    {
        OrganizationSettingFactory::make()
            ->setPropertyAndValue(
                PasskeyOrganizationSettingsService::SETTINGS_PROPERTY,
                ['enabled' => false]
            )
            ->persist();

        $this->getJson('/passkey/organization/settings.json?api-version=v2');

        $this->assertResponseSuccess();
        $this->assertFalse($this->_responseJsonBody->enabled);
    }

    public function testPasskeyOrgSettingsGet_ReflectsEnabledSetting(): void
    {
        OrganizationSettingFactory::make()
            ->setPropertyAndValue(
                PasskeyOrganizationSettingsService::SETTINGS_PROPERTY,
                ['enabled' => true]
            )
            ->persist();

        $this->getJson('/passkey/organization/settings.json?api-version=v2');

        $this->assertResponseSuccess();
        $this->assertTrue($this->_responseJsonBody->enabled);
    }
}
