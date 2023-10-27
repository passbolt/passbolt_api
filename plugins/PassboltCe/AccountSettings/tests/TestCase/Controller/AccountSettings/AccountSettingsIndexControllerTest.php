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
 * @since         3.2.0
 */

namespace Passbolt\AccountSettings\Test\TestCase\Controller\AccountSettings;

use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use Passbolt\AccountSettings\Test\Factory\AccountSettingFactory;

class AccountSettingsIndexControllerTest extends AppIntegrationTestCase
{
    /**
     * @Given I have a theme, a locale and a dummy property set in my settings
     * @When I get '/account/settings.json'
     * @Then the response should return both theme and locale settings only.
     * @throws \Exception
     */
    public function testAccountSettingsIndexSuccess(): void
    {
        // Create a user with 3 account settings, with only 2 known and rendered by the controller
        $user = UserFactory::make()
            ->user()
            ->with('AccountSettings', AccountSettingFactory::make()->theme('dummy_theme'))
            ->with('AccountSettings', AccountSettingFactory::make()->locale('en_UK'))
            ->with('AccountSettings', AccountSettingFactory::make()->setPropertyValue('dummy_prop', 'dummy_prop'))
            ->persist();

        $this->logInAs($user);
        $this->getJson('/account/settings.json?api-version=v2');

        $this->assertResponseOk();
        $this->assertSame(2, count($this->_responseJsonBody));
        $this->assertResponseContains('dummy_theme');
        $this->assertResponseContains('en_UK');
        $this->assertResponseNotContains('dummy_prop');
    }

    /**
     * @Given I am not logged in
     * @When I get '/account/settings.json'
     * @Then the response should be refused.
     */
    public function testAccountSettingsErrorNotAuthenticated(): void
    {
        $this->getJson('/account/settings.json?api-version=v2');
        $this->assertAuthenticationError();
    }
}
