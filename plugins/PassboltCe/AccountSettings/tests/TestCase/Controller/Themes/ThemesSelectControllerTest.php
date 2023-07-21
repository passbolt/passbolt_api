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
 * @since         2.0.0
 */

namespace Passbolt\AccountSettings\Test\TestCase\Controller\Themes;

use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\AccountSettings\Test\Factory\AccountSettingFactory;

/**
 * @uses \Passbolt\AccountSettings\Controller\Themes\ThemesSelectController
 */
class ThemesSelectControllerTest extends AppIntegrationTestCase
{
    use LocatorAwareTrait;

    /**
     * @var \Passbolt\AccountSettings\Model\Table\AccountSettingsTable
     */
    protected $AccountSettings;

    public function setUp(): void
    {
        parent::setUp();
        /** @phpstan-ignore-next-line */
        $this->AccountSettings = $this->fetchTable('Passbolt/AccountSettings.AccountSettings');
    }

    public function testThemesSelectSuccess()
    {
        $user = UserFactory::make()->user()->persist();
        AccountSettingFactory::make()->theme('midgar')->withUser($user)->persist();

        /** Authenticate as ada and change the theme setting. */
        $this->logInAs($user);
        $postData = ['value' => 'midgar'];
        $this->postJson('/account/settings/themes.json?api-version=v2', $postData);
        $this->assertSuccess();

        // Check that the setting is set
        $themeSettingFindConditions = [
            'user_id' => $user->id,
            'property_id' => UuidFactory::uuid('account.settings.property.id.theme'),
        ];
        $setting = AccountSettingFactory::find()->where($themeSettingFindConditions)->first();
        $this->assertNotEmpty($setting);
        $this->assertEquals($setting['value'], 'midgar');

        // Change the theme setting to default
        $postData = ['value' => 'default'];
        $this->postJson('/account/settings/themes.json?api-version=v2', $postData);
        $this->assertSuccess();

        // Check that the setting is set to default
        $setting = AccountSettingFactory::find()->where($themeSettingFindConditions)->first();
        $this->assertNotEmpty($setting);
        $this->assertEquals($setting['value'], 'default');
    }

    public function testThemesSelectErrorThemeDoesNotExist()
    {
        $this->logInAsUser();
        $postData = ['value' => 'costa-del-sol'];
        $this->postJson('/account/settings/themes.json?api-version=2', $postData);
        $this->assertError(400, 'This is not a valid theme.');
    }

    public function testThemesSelectErrorThemeEmpty()
    {
        $this->logInAsUser();
        $postData = ['value' => ''];
        $this->postJson('/account/settings/themes.json', $postData);
        $this->assertError(400, 'A value for the theme should be provided.');
    }

    public function testThemesSelectErrorNotAuthenticated()
    {
        $postData = ['value' => 'midgar'];
        $this->postJson('/account/settings/themes.json', $postData);
        $this->assertAuthenticationError();
    }
}
