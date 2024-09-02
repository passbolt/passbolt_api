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

namespace Passbolt\Metadata\Test\TestCase\Controller;

use App\Test\Factory\OrganizationSettingFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Metadata\MetadataPlugin;
use Passbolt\Metadata\Model\Dto\MetadataSettingsDto;
use Passbolt\Metadata\Test\Factory\MetadataSettingsFactory;

/**
 * @uses \Passbolt\Metadata\Controller\MetadataSettingsGetController
 */
class MetadataSettingsPostControllerTest extends AppIntegrationTestCaseV5
{
    use LocatorAwareTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(MetadataPlugin::class);
    }

    public function testMetadataSettingsPostController_Success(): void
    {
        $this->logInAsAdmin();
        $data = MetadataSettingsFactory::getDefaultDataV4();
        $this->postJson('/metadata/settings.json', $data);
        $this->assertResponseCode(200);
        $this->assertEquals(1, OrganizationSettingFactory::count());

        $data[MetadataSettingsDto::DEFAULT_COMMENT_TYPE] = 'v5';
        $data[MetadataSettingsDto::ALLOW_CREATION_OF_V4_COMMENTS] = false;
        $data[MetadataSettingsDto::ALLOW_CREATION_OF_V5_COMMENTS] = true;
        $this->postJson('/metadata/settings.json', $data);
        $this->assertResponseCode(200);
        $this->assertEquals(1, OrganizationSettingFactory::count());
    }

    public function testMetadataSettingsPostController_Error_AuthenticationNeeded()
    {
        $this->postJson('/metadata/settings.json', []);
        $this->assertAuthenticationError();
    }

    public function testMetadataSettingsPostController_Error_NotAdmin(): void
    {
        $this->logInAsUser();
        $this->postJson('/metadata/settings.json', []);
        $this->assertResponseCode(403);
    }

    public function testMetadataSettingsPostController_Error_InvalidData(): void
    {
        $this->logInAsAdmin();
        $data = MetadataSettingsFactory::getDefaultDataV4();
        $data[MetadataSettingsDto::DEFAULT_RESOURCE_TYPES] = 'v8';
        $this->postJson('/metadata/settings.json', $data);
        $this->assertResponseCode(400);
        $this->assertResponseContains('Could not validate the metadata settings');
    }
}
