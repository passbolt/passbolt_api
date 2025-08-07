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
 * @since         5.4.0
 */

namespace Passbolt\Metadata\Test\TestCase\Controller;

use App\Test\Lib\AppIntegrationTestCaseV5;
use Cake\Core\Configure;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Metadata\MetadataPlugin;
use Passbolt\Metadata\Model\Dto\MetadataSettingsGetStartedDto;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataKeysSettingsFactory;
use Passbolt\Metadata\Test\Factory\MetadataTypesSettingsFactory;

/**
 * @covers \Passbolt\Metadata\Controller\MetadataSettingsGetStartedController
 */
class MetadataSettingsGetStartedControllerTest extends AppIntegrationTestCaseV5
{
    use LocatorAwareTrait;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(MetadataPlugin::class);
    }

    public function testMetadataSettingsGetStartedController_Error_AuthenticationNeeded(): void
    {
        $this->getJson('/metadata/settings/getting-started.json');
        $this->assertAuthenticationError();
    }

    public function testMetadataSettingsGetStartedController_Error_UserNotAdmin(): void
    {
        $this->logInAsUser();
        $this->getJson('/metadata/settings/getting-started.json');
        $this->assertForbiddenError('Access restricted to administrators');
    }

    public function testMetadataSettingsGetStartedController_Error_PluginDisabled(): void
    {
        Configure::write('passbolt.v5.enabled', false);
        $this->disableFeaturePlugin(MetadataPlugin::class);
        Configure::write('debug', false);
        $this->get('/metadata/settings/getting-started.json');
        $this->assertResponseCode(404);
    }

    // todo: testMetadataSettingsGetStartedController_Error_MFARequired

    public function testMetadataSettingsGetStartedController_Success_FlagEnabledByDefault(): void
    {
        $this->logInAsAdmin();

        $this->getJson('/metadata/settings/getting-started.json');

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertTrue($response[MetadataSettingsGetStartedDto::ENABLE_FOR_EXISTING_INSTANCES]);
    }

    public function testMetadataSettingsGetStartedController_Success_FlagDisabled(): void
    {
        $this->logInAsAdmin();
        Configure::write('passbolt.plugins.metadata.enableForExistingInstances', false);

        $this->getJson('/metadata/settings/getting-started.json');

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertFalse($response[MetadataSettingsGetStartedDto::ENABLE_FOR_EXISTING_INSTANCES]);
    }

    public function testMetadataSettingsGetStartedController_Success_MetadataTypesSettingsPresent(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        $this->logInAsAdmin();
        Configure::write('passbolt.plugins.metadata.enableForExistingInstances', true);

        $this->getJson('/metadata/settings/getting-started.json');

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertFalse($response[MetadataSettingsGetStartedDto::ENABLE_FOR_EXISTING_INSTANCES]);
    }

    public function testMetadataSettingsGetStartedController_Success_MetadataKeysSettingsPresent(): void
    {
        MetadataKeysSettingsFactory::make()->persist();
        $this->logInAsAdmin();

        $this->getJson('/metadata/settings/getting-started.json');

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertFalse($response[MetadataSettingsGetStartedDto::ENABLE_FOR_EXISTING_INSTANCES]);
    }

    public function testMetadataSettingsGetStartedController_Success_MetadataKeysPresent(): void
    {
        MetadataKeyFactory::make()->withServerKey()->withServerPrivateKey()->withCreatorAndModifier()->persist();
        $this->logInAsAdmin();

        $this->getJson('/metadata/settings/getting-started.json');

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertFalse($response[MetadataSettingsGetStartedDto::ENABLE_FOR_EXISTING_INSTANCES]);
    }

    public function testMetadataSettingsGetStartedController_Success_MissingMetadataPrivateKeyEntry(): void
    {
        MetadataKeyFactory::make()->withServerKey()->withCreatorAndModifier()->persist();
        $this->logInAsAdmin();

        $this->getJson('/metadata/settings/getting-started.json');

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertFalse($response[MetadataSettingsGetStartedDto::ENABLE_FOR_EXISTING_INSTANCES]);
    }
}
