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

namespace Passbolt\PasswordExpiryPolicies\Test\TestCase\Controller;

use App\Test\Lib\AppIntegrationTestCase;
use Passbolt\PasswordExpiry\Model\Dto\PasswordExpirySettingsDto;
use Passbolt\PasswordExpiry\PasswordExpiryPlugin;
use Passbolt\PasswordExpiry\Test\Lib\PasswordExpiryTestTrait;
use Passbolt\PasswordExpiryPolicies\PasswordExpiryPoliciesPlugin;
use Passbolt\PasswordExpiryPolicies\Test\Factory\PasswordExpiryPoliciesSettingFactory;

/**
 * @covers \Passbolt\PasswordExpiry\Controller\PasswordExpirySettingsGetController
 */
class PasswordExpiryPoliciesSettingsGetControllerTest extends AppIntegrationTestCase
{
    use PasswordExpiryTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(PasswordExpiryPlugin::class);
        $this->enableFeaturePlugin(PasswordExpiryPoliciesPlugin::class);
    }

    public function testPasswordExpiryPoliciesGetController_Success_Settings_In_DB()
    {
        /** @var \Passbolt\PasswordExpiry\Model\Entity\PasswordExpirySetting $setting */
        $setting = PasswordExpiryPoliciesSettingFactory::make()->persist();
        $this->logInAsUser();

        $this->getJson('/password-expiry/settings.json');
        $this->assertSuccess();

        $response = (array)$this->_responseJsonBody;
        $this->assertSame($setting->get('id'), $response['id']);
        $this->assertTrue($response[PasswordExpirySettingsDto::AUTOMATIC_EXPIRY]);
        $this->assertTrue($response[PasswordExpirySettingsDto::AUTOMATIC_UPDATE]);
        $this->assertTrue($response[PasswordExpirySettingsDto::POLICY_OVERRIDE]);
        $this->assertSame($setting->value[PasswordExpirySettingsDto::DEFAULT_EXPIRY_PERIOD], $response[PasswordExpirySettingsDto::DEFAULT_EXPIRY_PERIOD]);
        $this->assertSame($setting->value[PasswordExpirySettingsDto::EXPIRY_NOTIFICATION], $response[PasswordExpirySettingsDto::EXPIRY_NOTIFICATION]);
        $this->assertNotNull($response['created']);
        $this->assertNotNull($response['modified']);
        $this->assertNotNull($response['created_by']);
        $this->assertNotNull($response['modified_by']);
    }

    public function testPasswordExpiryPoliciesGetController_Success_Settings_Not_In_DB_Plugin_Disabled()
    {
        $this->logInAsUser();

        $this->getJson('/password-expiry/settings.json');
        $this->assertSuccess();

        $response = (array)$this->_responseJsonBody;
        $this->assertSame([
            PasswordExpirySettingsDto::AUTOMATIC_EXPIRY => false,
            PasswordExpirySettingsDto::AUTOMATIC_UPDATE => false,
            PasswordExpirySettingsDto::POLICY_OVERRIDE => false,
            PasswordExpirySettingsDto::DEFAULT_EXPIRY_PERIOD => null,
            PasswordExpirySettingsDto::EXPIRY_NOTIFICATION => null,
        ], $response);
    }
}
