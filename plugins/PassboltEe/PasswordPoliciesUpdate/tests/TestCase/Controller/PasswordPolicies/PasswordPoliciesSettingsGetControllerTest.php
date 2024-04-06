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

namespace Passbolt\PasswordPoliciesUpdate\Test\TestCase\Controller\PasswordPolicies;

use App\Test\Lib\AppIntegrationTestCase;
use Passbolt\PasswordPolicies\Model\Dto\PasswordPoliciesSettingsDto;
use Passbolt\PasswordPolicies\PasswordPoliciesPlugin;
use Passbolt\PasswordPolicies\Test\Lib\Controller\PasswordPoliciesModelTrait;
use Passbolt\PasswordPoliciesUpdate\PasswordPoliciesUpdatePlugin;
use Passbolt\PasswordPoliciesUpdate\Test\Factory\PasswordPoliciesSettingFactory;

/**
 * @covers \Passbolt\PasswordPolicies\Controller\PasswordPoliciesSettingsGetController
 */
class PasswordPoliciesSettingsGetControllerTest extends AppIntegrationTestCase
{
    use PasswordPoliciesModelTrait;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->enableFeaturePlugin(PasswordPoliciesPlugin::class);
        $this->enableFeaturePlugin(PasswordPoliciesUpdatePlugin::class);
    }

    public function testPasswordPoliciesSettingsGetController_SuccessDefaultSettings()
    {
        $this->logInAsUser();

        $this->getJson('/password-policies/settings.json');

        $this->assertSuccess();
        $this->assertPasswordPoliciesAttributes($this->_responseJsonBody);
        $this->assertEquals(PasswordPoliciesSettingsDto::SOURCE_DEFAULT, $this->_responseJsonBody->source);
    }

    public function testPasswordPoliciesSettingsGetController_SuccessDatabaseSettings()
    {
        PasswordPoliciesSettingFactory::make([
            'value.default_generator' => PasswordPoliciesSettingsDto::PASSWORD_GENERATOR_PASSPHRASE,
            'value.external_dictionary_check' => false,
            'value.password_generator_settings.length' => 25,
        ])->persist();
        $this->logInAsAdmin();

        $this->getJson('/password-policies/settings.json');

        $this->assertSuccess();
        $this->assertPasswordPoliciesAttributes($this->_responseJsonBody, true);
        $this->assertSame(PasswordPoliciesSettingsDto::SOURCE_DATABASE, $this->_responseJsonBody->source);
        $this->assertSame(PasswordPoliciesSettingsDto::PASSWORD_GENERATOR_PASSPHRASE, $this->_responseJsonBody->default_generator);
        $this->assertSame(false, $this->_responseJsonBody->external_dictionary_check);
        $this->assertSame(25, $this->_responseJsonBody->password_generator_settings->length);
    }

    public function testPasswordPoliciesSettingsGetController_SuccessDefaultSettingsUser__PasswordPoliciesUpdate()
    {
        PasswordPoliciesSettingFactory::make([
            'value.default_generator' => PasswordPoliciesSettingsDto::PASSWORD_GENERATOR_PASSWORD,
            'value.external_dictionary_check' => true,
            'value.password_generator_settings.length' => 30,
            'value.password_generator_settings.mask_emoji' => true,
            'value.passphrase_generator_settings.words' => 11,
            'value.passphrase_generator_settings.word_separator' => '.',
        ])->persist();
        $this->logInAsUser();

        $this->getJson('/password-policies/settings.json');

        $this->assertSuccess();
        $this->assertPasswordPoliciesAttributes($this->_responseJsonBody, true);
        $this->assertSame(PasswordPoliciesSettingsDto::PASSWORD_GENERATOR_PASSWORD, $this->_responseJsonBody->default_generator);
        $this->assertSame(true, $this->_responseJsonBody->external_dictionary_check);
        $this->assertSame(30, $this->_responseJsonBody->password_generator_settings->length);
        $this->assertSame(true, $this->_responseJsonBody->password_generator_settings->mask_emoji);
        $this->assertSame(11, $this->_responseJsonBody->passphrase_generator_settings->words);
        $this->assertSame('.', $this->_responseJsonBody->passphrase_generator_settings->word_separator);
    }
}
