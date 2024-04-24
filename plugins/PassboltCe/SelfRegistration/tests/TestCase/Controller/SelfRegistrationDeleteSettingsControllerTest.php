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
 * @since         3.10.0
 */

namespace Passbolt\SelfRegistration\Test\TestCase\Controller;

use App\Test\Factory\OrganizationSettingFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Passbolt\SelfRegistration\SelfRegistrationPlugin;
use Passbolt\SelfRegistration\Test\Lib\SelfRegistrationTestTrait;

class SelfRegistrationDeleteSettingsControllerTest extends AppIntegrationTestCase
{
    use SelfRegistrationTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(SelfRegistrationPlugin::class);
    }

    public function testSelfRegistrationDeleteSettingsControllerTest_Success()
    {
        $this->logInAsAdmin();
        $settingInDB = $this->setSelfRegistrationSettingsData();
        $id = $settingInDB->get('id');

        $this->deleteJson("/self-registration/settings/$id.json");
        $this->assertResponseOk();
        $this->assertSame(0, OrganizationSettingFactory::count());
    }

    public function testSelfRegistrationDeleteSettingsControllerTest_Not_Found()
    {
        $this->setSelfRegistrationSettingsData();
        $this->logInAsAdmin();
        $id = UuidFactory::uuid();
        $this->deleteJson("/self-registration/settings/$id.json");
        $this->assertNotFoundError('The self registration setting does not exist.');
    }

    public function testSelfRegistrationDeleteSettingsControllerTest_No_UUID()
    {
        $this->logInAsAdmin();
        $id = 'not-a-uuid';
        $this->deleteJson("/self-registration/settings/$id.json");
        $this->assertBadRequestError('The self registration setting id should be a valid UUID.');
    }

    public function testSelfRegistrationDeleteSettingsControllerTest_Guest_Have_No_Access()
    {
        $this->deleteJson('/self-registration/settings/foo.json');
        $this->assertAuthenticationError();
    }

    public function testSelfRegistrationDeleteSettingsControllerTest_Admin_Access_Only()
    {
        $this->logInAsUser();
        $this->deleteJson('/self-registration/settings/foo.json');
        $this->assertForbiddenError('Access restricted to administrators.');
    }
}
