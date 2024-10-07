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
 * @since         4.10.0
 */

namespace Passbolt\Metadata\TestCase\Controller;

use App\Test\Factory\OrganizationSettingFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Metadata\MetadataPlugin;
use Passbolt\Metadata\Model\Dto\MetadataKeysSettingsDto;
use Passbolt\Metadata\Test\Factory\MetadataKeysSettingsFactory;

/**
 * @uses \Passbolt\Metadata\Controller\MetadataKeysSettingsGetController
 */
class MetadataKeysSettingsGetControllerTest extends AppIntegrationTestCaseV5
{
    use LocatorAwareTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(MetadataPlugin::class);
    }

    public function testMetadataKeysSettingsGetController_Error_AuthenticationNeeded()
    {
        $this->getJson('/metadata/keys/settings.json');
        $this->assertAuthenticationError();
    }

    public function testMetadataKeysSettingsGetController_Success_NoEntryReturnsDefault(): void
    {
        $this->logInAsAdmin();
        $this->getJson('/metadata/keys/settings.json');
        $this->assertResponseCode(200);
        $this->assertEquals(MetadataKeysSettingsFactory::getDefaultData(), $this->getResponseBodyAsArray());
    }

    public function testMetadataKeysSettingsGetController_Success_SavedEntry(): void
    {
        $this->logInAsAdmin();
        $data = MetadataKeysSettingsFactory::getDefaultData();
        $data[MetadataKeysSettingsDto::ALLOW_USAGE_OF_PERSONAL_KEYS] = false;
        MetadataKeysSettingsFactory::make()->value(json_encode($data))->persist();
        $this->assertEquals(1, OrganizationSettingFactory::count());
        $this->getJson('/metadata/keys/settings.json');
        $this->assertResponseCode(200);
        $this->assertEquals($data, $this->getResponseBodyAsArray());
    }
}
