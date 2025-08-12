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

namespace Passbolt\Metadata\Test\TestCase\Controller\Setup;

use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use Cake\Core\Configure;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Metadata\MetadataPlugin;
use Passbolt\Metadata\Model\Dto\Setup\MetadataSettingsSetupDto;

/**
 * @covers \Passbolt\Metadata\Controller\Setup\MetadataSettingsSetupController
 */
class MetadataSettingsSetupControllerTest extends AppIntegrationTestCaseV5
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

    public function testMetadataSettingsSetupController_Error_AuthenticationNeeded(): void
    {
        $this->getJson('/metadata/setup/settings.json');
        $this->assertAuthenticationError();
    }

    public function testMetadataSettingsSetupController_Error_UserNotAdmin(): void
    {
        $this->logInAsUser();
        $this->getJson('/metadata/setup/settings.json');
        $this->assertForbiddenError('Access restricted to administrators');
    }

    public function testMetadataSettingsSetupController_Error_PluginDisabled(): void
    {
        Configure::write('passbolt.v5.enabled', false);
        $this->disableFeaturePlugin(MetadataPlugin::class);
        Configure::write('debug', false);
        $this->get('/metadata/setup/settings.json');
        $this->assertResponseCode(404);
    }

    public function testMetadataSettingsSetupController_Success_FlagEnabledByDefault(): void
    {
        $this->logInAsAdmin();

        $this->getJson('/metadata/setup/settings.json');

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertTrue($response[MetadataSettingsSetupDto::ENABLE_FOR_NEW_INSTANCES]);
    }

    public function testMetadataSettingsSetupController_Success_FlagDisabled(): void
    {
        $this->logInAsAdmin();
        Configure::write('passbolt.plugins.metadata.enableForNewInstances', false);

        $this->getJson('/metadata/setup/settings.json');

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertFalse($response[MetadataSettingsSetupDto::ENABLE_FOR_NEW_INSTANCES]);
    }

    public function testMetadataSettingsSetupController_Success_MultipleActiveUsers(): void
    {
        UserFactory::make()->admin()->active()->persist();
        UserFactory::make()->user()->active()->persist();
        $this->logInAsAdmin();
        Configure::write('passbolt.plugins.metadata.enableForNewInstances', true);

        $this->getJson('/metadata/setup/settings.json');

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertFalse($response[MetadataSettingsSetupDto::ENABLE_FOR_NEW_INSTANCES]);
    }

    public function testMetadataSettingsSetupController_Success_SingleActiveUserWithDeletedUsers(): void
    {
        UserFactory::make()->user()->deleted()->persist();
        UserFactory::make()->admin()->deleted()->persist();
        $this->logInAsAdmin();

        $this->getJson('/metadata/setup/settings.json');

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertFalse($response[MetadataSettingsSetupDto::ENABLE_FOR_NEW_INSTANCES]);
    }

    public function testMetadataSettingsSetupController_Success_SingleActiveUserWithDisabledUsers(): void
    {
        UserFactory::make()->user()->disabled()->persist();
        $this->logInAsAdmin();

        $this->getJson('/metadata/setup/settings.json');

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertFalse($response[MetadataSettingsSetupDto::ENABLE_FOR_NEW_INSTANCES]);
    }
}
