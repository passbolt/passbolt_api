<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */

namespace Passbolt\AccountSettings\Test\TestCase\Controller\Themes;

use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Passbolt\AccountSettings\Test\Lib\AccountSettingsPluginIntegrationTestCase;

class ThemesSelectControllerTest extends AccountSettingsPluginIntegrationTestCase
{
    public $AccountSettings;

    public $fixtures = [
        'app.Base/users', 'app.Base/roles', 'app.Base/profiles',
        'plugin.passbolt/accountSettings.accountSettings'
    ];

    public function setUp()
    {
        parent::setUp();
        $this->AccountSettings = TableRegistry::get('AccountSettings');
    }

    public function testThemesSelectSuccess()
    {
        // Check that there is no prior setting is set
        $setting = $this->AccountSettings->find()
            ->where([
                'user_id' => UuidFactory::uuid('user.id.ada'),
                'property_id' => UuidFactory::uuid('account.settings.property.id.theme')
            ])
            ->first();
        $this->assertNotEmpty($setting);

        // Authenticate as ada and change the theme setting
        $this->authenticateAs('ada');
        $postData = ['value' => 'midgar'];
        $this->postJson('/account/settings/themes.json?api-version=v2', $postData);
        $this->assertSuccess();

        // Check that the setting is set
        $themeSettingFindConditions = [
            'user_id' => UuidFactory::uuid('user.id.ada'),
            'property_id' => UuidFactory::uuid('account.settings.property.id.theme')
        ];
        $setting = $this->AccountSettings->find()->where($themeSettingFindConditions)->first();
        $this->assertNotEmpty($setting);
        $this->assertEquals($setting['value'], 'midgar');

        // Change the theme setting to default
        $postData = ['value' => 'default'];
        $this->postJson('/account/settings/themes.json?api-version=v2', $postData);
        $this->assertSuccess();

        // Check that the setting is set to default
        $setting = $this->AccountSettings->find()->where($themeSettingFindConditions)->first();
        $this->assertNotEmpty($setting);
        $this->assertEquals($setting['value'], 'default');
    }

    public function testThemesSelectErrorThemeDoesNotExist()
    {
        $this->authenticateAs('ada');
        $postData = ['value' => 'costa-del-sol'];
        $this->postJson('/account/settings/themes.json?api-version=2', $postData);
        $this->assertError(400, 'This is not a valid theme.');
    }

    public function testThemesSelectErrorThemeEmpty()
    {
        $this->authenticateAs('ada');
        $postData = ['value' => ''];
        $this->postJson('/account/settings/themes.json', $postData);
        $this->assertError(400, 'A value for the theme must be provided.');
    }

    public function testThemesSelectErrorNotAuthenticated()
    {
        $postData = ['value' => 'midgar'];
        $this->postJson('/account/settings/themes.json', $postData);
        $this->assertAuthenticationError();
    }
}
