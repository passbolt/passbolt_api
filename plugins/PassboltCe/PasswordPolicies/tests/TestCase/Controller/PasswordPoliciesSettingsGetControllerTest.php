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
 * @since         4.2.0
 */

namespace Passbolt\PasswordPolicies\Test\TestCase\Controller;

use App\Test\Lib\AppIntegrationTestCase;
use Cake\Core\Configure;
use Passbolt\PasswordPolicies\PasswordPoliciesPlugin;
use Passbolt\PasswordPolicies\Test\Lib\Controller\PasswordPoliciesModelTrait;

/**
 * @covers \Passbolt\PasswordPolicies\Controller\PasswordPoliciesSettingsGetController
 */
class PasswordPoliciesSettingsGetControllerTest extends AppIntegrationTestCase
{
    use PasswordPoliciesModelTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(PasswordPoliciesPlugin::class);
    }

    public function testPasswordPoliciesSettingsGetController_ErrorUnauthenticated()
    {
        $this->getJson('/password-policies/settings.json');

        $this->assertResponseCode(401);
    }

    public function testPasswordPoliciesSettingsGetController_SuccessDefaultSettingsUser()
    {
        $this->logInAsUser();

        $this->getJson('/password-policies/settings.json');

        $this->assertSuccess();
        $this->assertPasswordPoliciesAttributes($this->_responseJsonBody);
    }

    public function testPasswordPoliciesSettingsGetController_SuccessDefaultSettingsAdmin()
    {
        $this->logInAsAdmin();

        $this->getJson('/password-policies/settings.json');

        $this->assertSuccess();
        $this->assertPasswordPoliciesAttributes($this->_responseJsonBody);
    }

    public function testPasswordPoliciesSettingsGetController_ErrorInvalidSetting()
    {
        $this->logInAsAdmin();
        Configure::write(PasswordPoliciesPlugin::DEFAULT_PASSWORD_GENERATOR_CONFIG_KEY, 'invalid-password-generator-type');

        $this->getJson('/password-policies/settings.json');

        $this->assertError(500, 'Could not retrieve the password policies.');
    }
}
