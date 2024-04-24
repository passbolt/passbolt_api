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
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use Passbolt\SelfRegistration\SelfRegistrationPlugin;
use Passbolt\SelfRegistration\Test\Lib\SelfRegistrationTestTrait;

class SelfRegistrationSetSettingsControllerTest extends AppIntegrationTestCase
{
    use EmailQueueTrait;
    use SelfRegistrationTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(SelfRegistrationPlugin::class);
    }

    public function testSelfRegistrationSetSettingsControllerTest_Success()
    {
        $admin = $this->logInAsAdmin();
        $nOtherAdmins = rand(2, 3);
        $otherAdmins = UserFactory::make($nOtherAdmins)->admin()->persist();
        $data = $this->getSelfRegistrationSettingsData();
        $this->postJson('/self-registration/settings.json', $data);
        $this->assertResponseOk();
        $this->assertSame(1, OrganizationSettingFactory::count());

        $this->assertEmailQueueCount($nOtherAdmins + 1);
        $emailSubject = $admin->profile->first_name . ' edited the self registration settings';
        foreach ($otherAdmins as $otherAdmin) {
            $this->assertEmailInBatchContains($emailSubject, $otherAdmin->username);
        }
        $this->assertEmailInBatchContains('You edited the self registration settings', $admin->username);
    }

    public function testSelfRegistrationSetSettingsControllerTest_Error()
    {
        $this->logInAsAdmin();
        $data = $this->getSelfRegistrationSettingsData('data', 'blah');
        $this->postJson('/self-registration/settings.json', $data);
        $this->assertResponseError();
        $this->assertResponseContains('Could not validate the self registration settings.');
        $this->assertSame('The list of allowed domains should not be empty.', $this->_responseJsonBody->data->allowed_domains->_empty);
        $this->assertSame(0, OrganizationSettingFactory::count());
    }

    public function testSelfRegistrationSetSettingsControllerTest_Guest_Have_No_Access()
    {
        $this->postJson('/self-registration/settings.json');
        $this->assertAuthenticationError();
    }

    public function testSelfRegistrationSetSettingsControllerTest_Admin_Access_Only()
    {
        $this->logInAsUser();
        $this->postJson('/self-registration/settings.json');
        $this->assertForbiddenError('Access restricted to administrators.');
    }
}
