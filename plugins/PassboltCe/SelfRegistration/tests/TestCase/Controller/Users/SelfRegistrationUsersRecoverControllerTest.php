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
namespace Passbolt\SelfRegistration\Test\TestCase\Controller\Users;

use App\Test\Lib\AppIntegrationTestCase;
use Passbolt\SelfRegistration\SelfRegistrationPlugin;
use Passbolt\SelfRegistration\Test\Lib\SelfRegistrationTestTrait;

/**
 * @covers \App\Controller\Users\UsersRecoverController
 */
class SelfRegistrationUsersRecoverControllerTest extends AppIntegrationTestCase
{
    use SelfRegistrationTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(SelfRegistrationPlugin::class);
    }

    public function testSelfRegistrationUsersRecoverController_SelfRegistrationOpen_UserNotFound_Domain_Not_Supported()
    {
        $this->setSelfRegistrationSettingsData();
        $this->postJson('/users/recover.json', ['username' => 'john@some-url.com']);
        $this->assertNotFoundError('This user does not exist or has been deleted. Please contact your administrator.');
    }

    public function testSelfRegistrationUsersRecoverController_SelfRegistrationOpen_UserNotFound_Domain_Supported()
    {
        $this->setSelfRegistrationSettingsData();
        $this->postJson('/users/recover.json', ['username' => 'john@passbolt.com']);
        $this->assertNotFoundError('This user does not exist or has been deleted. Please register and complete the setup first.');
    }

    public function testSelfRegistrationUsersRecoverController_SelfRegistrationOpen_Settings_In_DB_Invalid()
    {
        $this->setSelfRegistrationSettingsData('provider', 'invalid');
        $this->postJson('/users/recover.json', ['username' => 'john@passbolt.com']);
        $this->assertInternalError('Could not validate the self registration settings found in database.');
    }
}
