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
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use App\Test\Lib\Model\EmailQueueTrait;
use Cake\Core\Configure;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Metadata\MetadataPlugin;
use Passbolt\Metadata\Model\Dto\MetadataKeysSettingsDto;
use Passbolt\Metadata\Test\Factory\MetadataKeysSettingsFactory;

/**
 * @uses \Passbolt\Metadata\Controller\MetadataKeysSettingsGetController
 */
class MetadataKeysSettingsPostControllerTest extends AppIntegrationTestCaseV5
{
    use EmailQueueTrait;
    use LocatorAwareTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(MetadataPlugin::class);
    }

    public function testMetadataKeysSettingsPostController_Success(): void
    {
        [$loggedInUser, $otherAdmin] = UserFactory::make(2)->admin()->persist();
        // Create a disabled admin and a user to test emails
        UserFactory::make()->admin()->disabled()->persist();
        UserFactory::make()->user()->persist();
        $this->logInAs($loggedInUser);
        $data = MetadataKeysSettingsFactory::getDefaultData();

        $data[MetadataKeysSettingsDto::ALLOW_USAGE_OF_PERSONAL_KEYS] = false;
        $this->postJson('/metadata/keys/settings.json', $data);
        $this->assertResponseCode(200);
        $this->assertEquals(1, OrganizationSettingFactory::count());
        $this->assertEmailQueueCount(2);
        $this->assertEmailInBatchContains([
            'You edited the metadata settings',
            'Allow usage of personal keys: False',
            'Zero-knowledge key share: False',
        ], $loggedInUser->username);
        $this->assertEmailInBatchContains($loggedInUser->profile->last_name . ' edited the metadata settings', $otherAdmin->username);
    }

    public function testMetadataKeysSettingsPostController_Error_AuthenticationNeeded()
    {
        $this->postJson('/metadata/keys/settings.json', []);
        $this->assertAuthenticationError();
    }

    public function testMetadataKeysSettingsPostController_Error_NotAdmin(): void
    {
        $this->logInAsUser();
        $this->postJson('/metadata/keys/settings.json', []);
        $this->assertResponseCode(403);
    }

    public function testMetadataKeysSettingsPostController_Error_InvalidData(): void
    {
        $this->logInAsAdmin();
        $data = MetadataKeysSettingsFactory::getDefaultData();
        $data[MetadataKeysSettingsDto::ZERO_KNOWLEDGE_KEY_SHARE] = 'zero-trust';
        $this->postJson('/metadata/keys/settings.json', $data);
        $this->assertResponseCode(400);
        $this->assertResponseContains('Could not validate the settings');
    }

    public function testMetadataKeysSettingsPostController_ErrorSettingsEditionDisabled(): void
    {
        $setting = Configure::read('passbolt.security.metadata.settings.editionDisabled');
        Configure::write('passbolt.security.metadata.settings.editionDisabled', true);
        $this->logInAsAdmin();
        $data = MetadataKeysSettingsFactory::getDefaultData();
        $this->postJson('/metadata/keys/settings.json', $data);
        $this->assertResponseCode(403);
        Configure::write('passbolt.security.metadata.settings.editionDisabled', $setting);
    }
}
