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

namespace Passbolt\PasswordPoliciesUpdate\Test\TestCase\Controller;

use App\Test\Factory\RoleFactory;
use App\Test\Lib\AppIntegrationTestCase;
use Passbolt\PasswordPolicies\Model\Dto\PassphraseGeneratorSettingsDto;
use Passbolt\PasswordPolicies\Model\Dto\PasswordGeneratorSettingsDto;
use Passbolt\PasswordPolicies\Model\Dto\PasswordPoliciesSettingsDto;
use Passbolt\PasswordPolicies\PasswordPoliciesPlugin;
use Passbolt\PasswordPoliciesUpdate\PasswordPoliciesUpdatePlugin;
use Passbolt\PasswordPoliciesUpdate\Test\Factory\PasswordPoliciesSettingFactory;

/**
 * @covers \Passbolt\PasswordPolicies\Controller\PasswordPoliciesSettingsSetController
 */
class PasswordPoliciesSettingsSetControllerTest extends AppIntegrationTestCase
{
    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->enableFeaturePlugin(PasswordPoliciesUpdatePlugin::class);
        $this->enableFeaturePlugin(PasswordPoliciesPlugin::class);

        RoleFactory::make()->guest()->persist();
        // Mock user agent and IP so extended user access control don't fail
        $this->mockUserAgent();
        $this->mockUserIp();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        $this->disableFeaturePlugin(PasswordPoliciesUpdatePlugin::class);
        $this->disableFeaturePlugin(PasswordPoliciesPlugin::class);

        parent::tearDown();
    }

    private function getDummyPasswordPoliciesSettings(): array
    {
        return [
            'default_generator' => PasswordPoliciesSettingsDto::PASSWORD_GENERATOR_PASSWORD,
            'password_generator_settings' => PasswordGeneratorSettingsDto::createFromDefault()->toArray(),
            'passphrase_generator_settings' => PassphraseGeneratorSettingsDto::createFromDefault()->toArray(),
            'external_dictionary_check' => true,
        ];
    }

    public function testPasswordPoliciesSettingsSet_Error_Unauthenticated()
    {
        $this->postJson('/password-policies/settings.json');

        $this->assertResponseCode(401);
    }

    public function testPasswordPoliciesSettingsSet_Error_ForbiddenForUser()
    {
        $this->logInAsUser();

        $this->postJson('/password-policies/settings.json');

        $this->assertForbiddenError('Access restricted to administrators.');
    }

    public function testPasswordPoliciesSettingsSet_Error_ValidationRequired()
    {
        $this->logInAsAdmin();

        $this->postJson('/password-policies/settings.json', []);

        $response = $this->_responseJsonBody;
        $this->assertBadRequestError('Could not validate the password policies settings');
        $this->assertObjectHasAttribute('default_generator', $response);
        $this->assertObjectHasAttribute('external_dictionary_check', $response);
        $this->assertObjectHasAttribute('password_generator_settings', $response);
        $this->assertObjectHasAttribute('passphrase_generator_settings', $response);
    }

    public function testPasswordPoliciesSettingsSet_Error_ValidationInvalidValues()
    {
        $this->logInAsAdmin();
        $data = $this->getDummyPasswordPoliciesSettings();
        // Manipulate some fields for testing
        unset($data['password_generator_settings']['length'], $data['passphrase_generator_settings']['words']);
        $data['passphrase_generator_settings']['word_case'] = 'foo';

        $this->postJson('/password-policies/settings.json', $data);

        $response = $this->getResponseBodyAsArray();
        $this->assertBadRequestError('Could not validate the password policies settings');
        $this->assertStringContainsString(
            'password generator length is required',
            $response['password_generator_settings']['length']['_required']
        );
        $this->assertStringContainsString(
            'passphrase generator words is required',
            $response['passphrase_generator_settings']['words']['_required']
        );
        $this->assertStringContainsString(
            'passphrase generator word case should be one of the following',
            $response['passphrase_generator_settings']['word_case']['inList']
        );
    }

    public function testPasswordPoliciesSettingsSet_Error_AtleastOneMaskIsSelected()
    {
        $this->logInAsAdmin();
        $data = $this->getDummyPasswordPoliciesSettings();
        // Disable all masks
        $data['password_generator_settings']['mask_upper'] = false;
        $data['password_generator_settings']['mask_lower'] = false;
        $data['password_generator_settings']['mask_digit'] = false;
        $data['password_generator_settings']['mask_parenthesis'] = false;
        $data['password_generator_settings']['mask_emoji'] = false;
        $data['password_generator_settings']['mask_char1'] = false;
        $data['password_generator_settings']['mask_char2'] = false;
        $data['password_generator_settings']['mask_char3'] = false;
        $data['password_generator_settings']['mask_char4'] = false;
        $data['password_generator_settings']['mask_char5'] = false;

        $this->postJson('/password-policies/settings.json', $data);

        $response = $this->getResponseBodyAsArray();
        $this->assertBadRequestError('Could not validate the password policies settings');
        $this->assertStringContainsString('The password generator settings should have at least one mask selected.', $response['password_generator_settings']['noMaskSelected']);
    }

    public function testPasswordPoliciesSettingsSet_SuccessCreate()
    {
        $admin = $this->logInAsAdmin();
        $data = [
            'default_generator' => PasswordPoliciesSettingsDto::PASSWORD_GENERATOR_PASSWORD,
            'external_dictionary_check' => true,
            'password_generator_settings' => [
                'length' => 12,
                'mask_upper' => true,
                'mask_lower' => true,
                'mask_digit' => true,
                'mask_parenthesis' => true,
                'mask_emoji' => true,
                'mask_char1' => true,
                'mask_char2' => true,
                'mask_char3' => true,
                'mask_char4' => true,
                'mask_char5' => true,
                'exclude_look_alike_chars' => false,
            ],
            'passphrase_generator_settings' => [
                'words' => 8,
                'word_separator' => '+',
                'word_case' => PassphraseGeneratorSettingsDto::PASSPHRASE_GENERATOR_WORDS_CASE_UPPER,
            ],
        ];

        $this->postJson('/password-policies/settings.json', $data);

        $response = $this->_responseJsonBody;
        /** Make sure response is in correct format & values are valid. */
        $this->assertSuccess();
        $this->assertSame($data['default_generator'], $response->default_generator);
        $this->assertSame($data['external_dictionary_check'], $response->external_dictionary_check);
        $this->assertObjectHasAttribute('id', $response);
        $this->assertObjectHasAttribute('created', $response);
        $this->assertObjectHasAttribute('modified', $response);
        $this->assertSame($admin->id, $response->created_by);
        $this->assertSame($admin->id, $response->modified_by);
        /**
         * Make sure entry is created in the DB.
         *
         * @var \Passbolt\PasswordPoliciesUpdate\Model\Entity\PasswordPoliciesSetting[] $settings
         */
        $settings = PasswordPoliciesSettingFactory::find()->toArray();
        $this->assertCount(1, $settings);
        $this->assertArrayEqualsCanonicalizing($data, $settings[0]->value);
    }

    public function testPasswordPoliciesSettingsSet_SuccessUpdate()
    {
        $admin = $this->logInAsAdmin();
        PasswordPoliciesSettingFactory::make()->persist();
        $data = [
            'default_generator' => PasswordPoliciesSettingsDto::PASSWORD_GENERATOR_PASSWORD,
            'external_dictionary_check' => true,
            'password_generator_settings' => [
                'length' => 16,
                'mask_upper' => true,
                'mask_lower' => true,
                'mask_digit' => true,
                'mask_parenthesis' => false,
                'mask_emoji' => true,
                'mask_char1' => false,
                'mask_char2' => false,
                'mask_char3' => false,
                'mask_char4' => false,
                'mask_char5' => false,
                'exclude_look_alike_chars' => false,
            ],
            'passphrase_generator_settings' => [
                'words' => 10,
                'word_separator' => '\'',
                'word_case' => PassphraseGeneratorSettingsDto::PASSPHRASE_GENERATOR_WORDS_CASE_CAMEL,
            ],
        ];

        $this->postJson('/password-policies/settings.json', $data);

        $response = $this->_responseJsonBody;
        $this->assertSuccess();
        $this->assertSame($data['default_generator'], $response->default_generator);
        $this->assertSame($data['external_dictionary_check'], $response->external_dictionary_check);
        $this->assertObjectHasAttribute('id', $response);
        $this->assertObjectHasAttribute('created', $response);
        $this->assertObjectHasAttribute('modified', $response);
        $this->assertSame($admin->id, $response->modified_by);
        /**
         * Make sure entry is created in the DB.
         *
         * @var \Passbolt\PasswordPoliciesUpdate\Model\Entity\PasswordPoliciesSetting[] $settings
         */
        $settings = PasswordPoliciesSettingFactory::find()->toArray();
        $this->assertCount(1, $settings);
        $this->assertArrayEqualsCanonicalizing($data, $settings[0]->value);
    }

    public function testPasswordPoliciesSettingsSet_Success_PasswordPoliciesPluginDisabled(): void
    {
        $this->disableFeaturePlugin(PasswordPoliciesPlugin::class);
        $this->logInAsAdmin();

        $this->postJson('/password-policies/settings.json', [
            'default_generator' => PasswordPoliciesSettingsDto::PASSWORD_GENERATOR_PASSWORD,
            'external_dictionary_check' => true,
            'password_generator_settings' => [
                'length' => 12,
                'mask_upper' => true,
                'mask_lower' => true,
                'mask_digit' => true,
                'mask_parenthesis' => true,
                'mask_emoji' => true,
                'mask_char1' => true,
                'mask_char2' => true,
                'mask_char3' => true,
                'mask_char4' => true,
                'mask_char5' => true,
                'exclude_look_alike_chars' => false,
            ],
            'passphrase_generator_settings' => [
                'words' => 8,
                'word_separator' => '+',
                'word_case' => PassphraseGeneratorSettingsDto::PASSPHRASE_GENERATOR_WORDS_CASE_UPPER,
            ],
        ]);

        $this->assertSuccess();
    }
}
