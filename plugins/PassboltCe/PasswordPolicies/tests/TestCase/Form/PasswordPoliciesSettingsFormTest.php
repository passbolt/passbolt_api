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
namespace Passbolt\PasswordPolicies\Test\TestCase\Form;

use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\FormatValidationTrait;
use Cake\Event\EventDispatcherTrait;
use Passbolt\PasswordPolicies\Form\PasswordPoliciesSettingsForm;
use Passbolt\PasswordPolicies\Model\Dto\PassphraseGeneratorSettingsDto;
use Passbolt\PasswordPolicies\Model\Dto\PasswordPoliciesSettingsDto;
use Passbolt\PasswordPolicies\Validation\PasswordGeneratorSettingsValidator;

/**
 * @covers \Passbolt\PasswordPolicies\Form\PasswordPoliciesSettingsForm
 */
class PasswordPoliciesSettingsFormTest extends AppTestCase
{
    use EventDispatcherTrait;
    use FormatValidationTrait;

    /**
     * @var \Passbolt\PasswordPolicies\Form\PasswordPoliciesSettingsForm
     */
    private $form;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->form = new PasswordPoliciesSettingsForm();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->form);

        parent::tearDown();
    }

    private function getDummyPasswordGeneratorSettings(): array
    {
        return [
            'default_generator' => PasswordPoliciesSettingsDto::DEFAULT_PASSWORD_GENERATOR,
            'external_dictionary_check' => true,
            'password_generator_settings' => [
                'length' => 12,
                'mask_upper' => true,
                'mask_lower' => true,
                'mask_digit' => true,
                'mask_parenthesis' => true,
                'mask_emoji' => false,
                'mask_char1' => true,
                'mask_char2' => true,
                'mask_char3' => true,
                'mask_char4' => true,
                'mask_char5' => true,
                'exclude_look_alike_chars' => false,
            ],
            'passphrase_generator_settings' => [
                'words' => 8,
                'word_separator' => ' ',
                'word_case' => PassphraseGeneratorSettingsDto::PASSPHRASE_GENERATOR_WORDS_CASE_UPPER,
            ],
        ];
    }

    public function testPasswordPoliciesSettingsForm_Validate_GeneratorType(): void
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'inList' => self::getInListTestCases(PasswordPoliciesSettingsForm::PASSWORD_GENERATORS),
            //'maxLength' => self::getMaxLengthTestCases(PasswordGeneratorSettings::GENERATOR_TYPE_LENGTH_MAX), // TODO: Failing because inList taking precedence
        ];

        $this->assertFormFieldFormatValidation(
            PasswordPoliciesSettingsForm::class,
            'default_generator',
            $this->getDummyPasswordGeneratorSettings(),
            $testCases
        );
    }

    public function testPasswordPoliciesSettingsForm_Validate_ExternalDictionaryCheck(): void
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'boolean' => self::getBooleanTestCases(),
        ];

        $this->assertFormFieldFormatValidation(
            PasswordPoliciesSettingsForm::class,
            'external_dictionary_check',
            $this->getDummyPasswordGeneratorSettings(),
            $testCases
        );
    }

    public function testPasswordPoliciesSettingsForm_Validate_PasswordGeneratorSettings(): void
    {
        $testCases = ['requirePresence' => self::getRequirePresenceTestCases()];

        $this->assertFormFieldFormatValidation(
            PasswordPoliciesSettingsForm::class,
            'password_generator_settings',
            $this->getDummyPasswordGeneratorSettings(),
            $testCases
        );
    }

    public function testPasswordPoliciesSettingsForm_Validate_PasswordGeneratorSettingsLength(): void
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'range' => self::getRangeTestCases(
                PasswordGeneratorSettingsValidator::PASSWORD_GENERATOR_SETTING_LENGTH_MIN,
                PasswordGeneratorSettingsValidator::PASSWORD_GENERATOR_SETTING_LENGTH_MAX
            ),
        ];

        $this->assertFormFieldFormatValidation(
            PasswordPoliciesSettingsForm::class,
            'password_generator_settings.length',
            $this->getDummyPasswordGeneratorSettings(),
            $testCases
        );
    }

    /**
     * @dataProvider maskFieldsDataProvider
     */
    public function testPasswordPoliciesSettingsForm_Validate_PasswordGeneratorSettingsMaskFields($maskField): void
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'boolean' => self::getBooleanTestCases(),
        ];

        $this->assertFormFieldFormatValidation(
            PasswordPoliciesSettingsForm::class,
            "password_generator_settings.{$maskField}",
            $this->getDummyPasswordGeneratorSettings(),
            $testCases
        );
    }

    public function testPasswordPoliciesSettingsForm_Validate_PasswordGeneratorSettingsExcludeLookAlikeChars(): void
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'boolean' => self::getBooleanTestCases(),
        ];

        $this->assertFormFieldFormatValidation(
            PasswordPoliciesSettingsForm::class,
            'password_generator_settings.exclude_look_alike_chars',
            $this->getDummyPasswordGeneratorSettings(),
            $testCases
        );
    }

    public function testPasswordPoliciesSettingsForm_Validate_PassphraseGeneratorSettings(): void
    {
        $testCases = ['requirePresence' => self::getRequirePresenceTestCases()];

        $this->assertFormFieldFormatValidation(
            PasswordPoliciesSettingsForm::class,
            'passphrase_generator_settings',
            $this->getDummyPasswordGeneratorSettings(),
            $testCases
        );
    }

    public function testPasswordPoliciesSettingsForm_Validate_PassphraseGeneratorSettingsWords(): void
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'range' => self::getRangeTestCases(
                PassphraseGeneratorSettingsDto::PASSPHRASE_GENERATOR_WORDS_MIN,
                PassphraseGeneratorSettingsDto::PASSPHRASE_GENERATOR_WORDS_MAX
            ),
        ];

        $this->assertFormFieldFormatValidation(
            PasswordPoliciesSettingsForm::class,
            'passphrase_generator_settings.words',
            $this->getDummyPasswordGeneratorSettings(),
            $testCases
        );
    }

    public function testPasswordPoliciesSettingsForm_Validate_PassphraseGeneratorSettingsWordSeparator(): void
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'maxLength' => self::getMaxLengthTestCases(PassphraseGeneratorSettingsDto::PASSPHRASE_GENERATOR_WORD_SEPARATOR_LENGTH_MAX),
        ];

        $this->assertFormFieldFormatValidation(
            PasswordPoliciesSettingsForm::class,
            'passphrase_generator_settings.word_separator',
            $this->getDummyPasswordGeneratorSettings(),
            $testCases
        );
    }

    public function testPasswordPoliciesSettingsForm_Validate_PassphraseGeneratorSettingsWordCase(): void
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'inList' => self::getInListTestCases(PassphraseGeneratorSettingsDto::ALLOWED_PASSPHRASE_GENERATOR_WORDS_CASES),
        ];

        $this->assertFormFieldFormatValidation(
            PasswordPoliciesSettingsForm::class,
            'passphrase_generator_settings.word_case',
            $this->getDummyPasswordGeneratorSettings(),
            $testCases
        );
    }

    public function testPasswordPoliciesSettingsForm_Validate_AtleastOneMaskIsSelected(): void
    {
        $data = array_replace_recursive($this->getDummyPasswordGeneratorSettings(), [
            'password_generator_settings' => [
                'mask_upper' => false,
                'mask_lower' => false,
                'mask_digit' => false,
                'mask_parenthesis' => false,
                'mask_emoji' => false,
                'mask_char1' => false,
                'mask_char2' => false,
                'mask_char3' => false,
                'mask_char4' => false,
                'mask_char5' => false,
            ],
        ]);

        $result = $this->form->execute($data);

        $this->assertFalse($result);
        $errors = $this->form->getErrors();
        $this->assertArrayHasKey('noMaskSelected', $errors['password_generator_settings']);
    }

    /**
     * @return array
     */
    public function maskFieldsDataProvider(): array
    {
        return [
            ['mask_upper'],
            ['mask_lower'],
            ['mask_digit'],
            ['mask_parenthesis'],
            ['mask_emoji'],
            ['mask_char1'],
            ['mask_char2'],
            ['mask_char3'],
            ['mask_char4'],
            ['mask_char5'],
        ];
    }
}
