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
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         4.9.0
 */

namespace Passbolt\DirectorySync\Test\TestCase\Controller;

use Passbolt\DirectorySync\Test\Factory\DirectoryOrgSettingFactory;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncIntegrationTestCase;
use Passbolt\DirectorySync\Test\Utility\LdapConfigurationTestUtility;
use Passbolt\DirectorySync\Utility\Alias;

class DirectorySettingsControllerDeleteUserBehaviorTest extends DirectorySyncIntegrationTestCase
{
    public function testDirectorySettingsControllerDeleteUserBehavior_View_Behavior_Not_Set()
    {
        $this->logInAsAdmin();
        DirectoryOrgSettingFactory::make()->default()->persist();

        $this->getJson('/directorysync/settings.json');
        $this->assertSuccess();

        $settings = (array)$this->_responseJsonBody;
        $this->assertArrayNotHasKey(Alias::DELETE_USER_BEHAVIOR_DELETE, $settings);
    }

    public function testDirectorySettingsControllerDeleteUserBehavior_View_Behavior_Set_To_Disable()
    {
        $this->logInAsAdmin();
        DirectoryOrgSettingFactory::make()->deleteUserBehaviorDisable()->persist();

        $this->getJson('/directorysync/settings.json');
        $this->assertSuccess();

        $settings = (array)$this->_responseJsonBody;
        $this->assertSame(Alias::DELETE_USER_BEHAVIOR_DISABLE, $settings[Alias::DELETE_USER_BEHAVIOR_PROPERTY]);
        $settingsInDB = json_decode(DirectoryOrgSettingFactory::firstOrFail()->value, true);
        $this->assertSame(Alias::DELETE_USER_BEHAVIOR_DISABLE, $settingsInDB[Alias::DELETE_USER_BEHAVIOR_MAPPING_KEY]);
    }

    public function testDirectorySettingsControllerDeleteUserBehavior_View_Behavior_Invalid()
    {
        $this->logInAsAdmin();
        $setting = DirectoryOrgSettingFactory::make()->deleteUserBehaviorInvalid()->persist();
        $deleteUserBehaviorSetting = json_decode($setting->get('value'), true)[Alias::DELETE_USER_BEHAVIOR_MAPPING_KEY];

        $this->getJson('/directorysync/settings.json');
        $this->assertSuccess();

        $settings = (array)$this->_responseJsonBody;
        $this->assertSame($deleteUserBehaviorSetting, $settings[Alias::DELETE_USER_BEHAVIOR_PROPERTY]);
    }

    public function testDirectorySettingsControllerDeleteUserBehavior_Update_Behavior_Set_To_Disable()
    {
        $formData = LdapConfigurationTestUtility::getDummyFormData();
        $formData[Alias::DELETE_USER_BEHAVIOR_PROPERTY] = Alias::DELETE_USER_BEHAVIOR_DISABLE;
        $this->logInAsAdmin();

        $this->putJson('/directorysync/settings.json', $formData);
        $this->assertSuccess();

        $settings = json_decode(DirectoryOrgSettingFactory::firstOrFail()->value, true);
        $this->assertSame(Alias::DELETE_USER_BEHAVIOR_DISABLE, $settings[Alias::DELETE_USER_BEHAVIOR_MAPPING_KEY]);
    }

    public function testDirectorySettingsControllerDeleteUserBehavior_Update_Behavior_Setting_Remain_On_Disabled_If_Not_Specified_In_Payload()
    {
        $formData = LdapConfigurationTestUtility::getDummyFormData();
        DirectoryOrgSettingFactory::make()->deleteUserBehaviorDisable()->persist();
        $this->logInAsAdmin();

        $this->putJson('/directorysync/settings.json', $formData);
        $this->assertSuccess();

        $settings = (array)$this->_responseJsonBody;
        $this->assertArrayNotHasKey(Alias::DELETE_USER_BEHAVIOR_PROPERTY, $settings);
        $settingsInDB = json_decode(DirectoryOrgSettingFactory::firstOrFail()->value, true);
        $this->assertArrayNotHasKey(Alias::DELETE_USER_BEHAVIOR_MAPPING_KEY, $settingsInDB);
    }
}
