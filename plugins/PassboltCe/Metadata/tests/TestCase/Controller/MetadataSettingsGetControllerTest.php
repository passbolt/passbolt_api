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
class MetadataSettingsGetControllerTest extends AppIntegrationTestCaseV5
{
    use LocatorAwareTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(MetadataPlugin::class);
    }

    public function testMetadataSettingsGetController_Error_AuthenticationNeeded()
    {
        $this->getJson('/metadata/settings.json');
        $this->assertAuthenticationError();
    }

    public function testMetadataSettingsGetController_Success_NoEntryReturnsDefault(): void
    {
        $this->logInAsAdmin();
        $this->getJson('/metadata/settings.json');
        $this->assertResponseCode(200);
        $this->assertEquals(MetadataSettingsFactory::getDefaultData(), $this->getResponseBodyAsArray());
    }

    public function testMetadataSettingsGetController_Success_SavedEntry(): void
    {
        $this->logInAsAdmin();
        $data = MetadataSettingsFactory::getDefaultData();
        $data[MetadataSettingsDto::DEFAULT_COMMENT_TYPE] = 'v5';
        $data[MetadataSettingsDto::ALLOW_CREATION_OF_V4_COMMENTS] = false;
        $data[MetadataSettingsDto::ALLOW_CREATION_OF_V5_COMMENTS] = true;
        MetadataSettingsFactory::make()->value(json_encode($data))->persist();
        $this->assertEquals(1, OrganizationSettingFactory::count());
        $this->getJson('/metadata/settings.json');
        $this->assertResponseCode(200);
        $this->assertEquals($data, $this->getResponseBodyAsArray());
    }
}
