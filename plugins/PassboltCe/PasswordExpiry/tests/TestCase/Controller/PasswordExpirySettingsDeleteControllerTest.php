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
 * @since         4.5.0
 */

namespace Passbolt\PasswordExpiry\Test\TestCase\Controller;

use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Utility\UuidFactory;
use Passbolt\PasswordExpiry\PasswordExpiryPlugin;
use Passbolt\PasswordExpiry\Test\Factory\PasswordExpirySettingFactory;

/**
 * @covers \Passbolt\PasswordExpiry\Controller\PasswordExpirySettingsDeleteController
 */
class PasswordExpirySettingsDeleteControllerTest extends AppIntegrationTestCase
{
    use EmailQueueTrait;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        // Mock user agent and IP so extended user access control don't fail
        $this->mockUserAgent();
        $this->mockUserIp();
        $this->enableFeaturePlugin(PasswordExpiryPlugin::class);
    }

    public function testPasswordExpiryDeleteController_Success()
    {
        $settingId = PasswordExpirySettingFactory::make()->persist()->get('id');
        /** @var \App\Model\Entity\User $otherAdmin */
        $otherAdmin = UserFactory::make()->admin()->persist();
        $activeAdmin = $this->logInAsAdmin();
        $this->deleteJson("/password-expiry/settings/{$settingId}.json");
        $this->assertSuccess();
        $this->assertEmpty($this->_responseJsonBody);
        $this->assertSame(0, PasswordExpirySettingFactory::count());

        $this->assertEmailQueueCount(2);
        $this->assertEmailInBatchContains('You edited the password expiry settings', $activeAdmin->username);
        $this->assertEmailInBatchContains(
            $activeAdmin->profile->full_name . ' edited the password expiry settings',
            $otherAdmin->username
        );
    }

    public function testPasswordExpiryDeleteController_Authentication()
    {
        $this->logInAsUser();
        $this->deleteJson('/password-expiry/settings/foo.json');
        $this->assertForbiddenError('Access restricted to administrators.');
    }

    public function testPasswordExpiryDeleteController_Settings_Is_Not_Found_In_DB()
    {
        $settingId = UuidFactory::uuid();
        $this->logInAsAdmin();
        $this->deleteJson("/password-expiry/settings/{$settingId}.json");
        $this->assertNotFoundError('The password expiry setting does not exist.');
    }
}
