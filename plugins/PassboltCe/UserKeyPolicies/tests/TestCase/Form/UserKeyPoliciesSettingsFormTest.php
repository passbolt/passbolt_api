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
 * @since         5.2.0
 */

namespace Passbolt\UserKeyPolicies\Test\TestCase\Form;

use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\FormatValidationTrait;
use Cake\Event\EventDispatcherTrait;
use Cake\Utility\Hash;
use Passbolt\UserKeyPolicies\Form\UserKeyPoliciesSettingsForm;
use Passbolt\UserKeyPolicies\Model\Dto\UserKeyPoliciesSettingsDto;

class UserKeyPoliciesSettingsFormTest extends AppTestCase
{
    use EventDispatcherTrait;
    use FormatValidationTrait;

    private ?UserKeyPoliciesSettingsForm $form = null;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->form = new UserKeyPoliciesSettingsForm();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->form);

        parent::tearDown();
    }

    protected function getDummyUserKeyPoliciesSettings(array $data = []): array
    {
        return UserKeyPoliciesSettingsDto::createFromDefault($data)->toArray();
    }

    public function testUserKeyPoliciesSettingsForm_Validate_PreferredKeyType(): void
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];

        $this->assertFormFieldFormatValidation(
            UserKeyPoliciesSettingsForm::class,
            'preferred_key_type',
            $this->getDummyUserKeyPoliciesSettings(),
            $testCases
        );
    }

    public function testUserKeyPoliciesSettingsForm_Validate_PreferredKeySize(): void
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'inList' => self::getInListTestCases(UserKeyPoliciesSettingsForm::ALLOWED_KEY_SIZES),
        ];

        $settings = $this->getDummyUserKeyPoliciesSettings([
            'preferred_key_type' => UserKeyPoliciesSettingsDto::KEY_TYPE_RSA,
            'preferred_key_size' => UserKeyPoliciesSettingsDto::KEY_SIZE_3072,
            'preferred_key_curve' => null,
        ]);
        $this->assertFormFieldFormatValidation(
            UserKeyPoliciesSettingsForm::class,
            'preferred_key_size',
            $settings,
            $testCases
        );
    }

    public function testUserKeyPoliciesSettingsForm_Validate_PreferredKeyCurve_WithCurve(): void
    {
        $data = $this->getDummyUserKeyPoliciesSettings([
            'preferred_key_type' => UserKeyPoliciesSettingsDto::KEY_TYPE_CURVE,
            'preferred_key_size' => null,
            'preferred_key_curve' => UserKeyPoliciesSettingsDto::KEY_CURVE_ED25519_LEGACY,
        ]);

        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'inList' => self::getInListTestCases(UserKeyPoliciesSettingsForm::ALLOWED_KEY_CURVES),
        ];

        $this->assertFormFieldFormatValidation(
            UserKeyPoliciesSettingsForm::class,
            'preferred_key_curve',
            $data,
            $testCases
        );
    }

    public function testUserKeyPoliciesSettingsForm_Validate_PreferredKeyCurve_WithRSA(): void
    {
        $data = $this->getDummyUserKeyPoliciesSettings([
            'preferred_key_type' => UserKeyPoliciesSettingsDto::KEY_TYPE_RSA,
            'preferred_key_size' => UserKeyPoliciesSettingsDto::KEY_SIZE_3072,
            'preferred_key_curve' => null,
        ]);
        $result = $this->form->validate($data);
        $this->assertTrue($result);
    }

    public function testUserKeyPoliciesSettingsForm_Validate_InvalidPreferredKeyType(): void
    {
        $data = $this->getDummyUserKeyPoliciesSettings([
            'preferred_key_type' => 'foo-bar',
        ]);
        $result = $this->form->validate($data);
        $this->assertFalse($result);
        $this->assertTrue(Hash::check($this->form->getErrors(), 'preferred_key_type.inList'));
    }

    public static function validTypeSizeCurveCombinationProvider(): array
    {
        return [
            [
                [
                    'preferred_key_type' => UserKeyPoliciesSettingsDto::KEY_TYPE_CURVE,
                    'preferred_key_size' => null,
                    'preferred_key_curve' => UserKeyPoliciesSettingsDto::KEY_CURVE_ED25519_LEGACY,
                ],
            ],
            [
                [
                    'preferred_key_type' => UserKeyPoliciesSettingsDto::KEY_TYPE_RSA,
                    'preferred_key_size' => UserKeyPoliciesSettingsDto::KEY_SIZE_4096,
                    'preferred_key_curve' => null,
                ],
            ],
            [
                [
                    'preferred_key_type' => UserKeyPoliciesSettingsDto::KEY_TYPE_RSA,
                    'preferred_key_size' => UserKeyPoliciesSettingsDto::KEY_SIZE_3072,
                    'preferred_key_curve' => null,
                ],
            ],
        ];
    }

    /**
     * @dataProvider validTypeSizeCurveCombinationProvider
     * @param array $inputData Valid input data.
     * @return void
     */
    public function testUserKeyPoliciesSettingsForm_Validate_ValidCombinations(array $inputData): void
    {
        $data = $this->getDummyUserKeyPoliciesSettings($inputData);
        $result = $this->form->validate($data);
        $this->assertTrue($result);
    }

    public static function invalidTypeSizeCurveCombinationProvider(): array
    {
        return [
            [
                'data' => [
                    'preferred_key_type' => UserKeyPoliciesSettingsDto::KEY_TYPE_RSA,
                    'preferred_key_size' => null,
                ],
                'error key' => 'preferred_key_type.invalid_key_type_size_combination',
            ],
            [
                'data' => [
                    'preferred_key_type' => UserKeyPoliciesSettingsDto::KEY_TYPE_CURVE,
                    'preferred_key_size' => UserKeyPoliciesSettingsDto::KEY_SIZE_3072,
                ],
                'error key' => 'preferred_key_type.invalid_key_type_size_combination',
            ],
            [
                'data' => [
                    'preferred_key_type' => UserKeyPoliciesSettingsDto::KEY_TYPE_CURVE,
                    'preferred_key_size' => UserKeyPoliciesSettingsDto::KEY_SIZE_4096,
                ],
                'error key' => 'preferred_key_type.invalid_key_type_size_combination',
            ],
            [
                'data' => [
                    'preferred_key_type' => UserKeyPoliciesSettingsDto::KEY_TYPE_CURVE,
                    'preferred_key_size' => null,
                    'preferred_key_curve' => null,
                ],
                'error key' => 'preferred_key_type.invalid_key_type_curve_combination',
            ],
            [
                'data' => [
                    'preferred_key_type' => UserKeyPoliciesSettingsDto::KEY_TYPE_RSA,
                    'preferred_key_size' => UserKeyPoliciesSettingsDto::KEY_SIZE_4096,
                    'preferred_key_curve' => UserKeyPoliciesSettingsDto::KEY_CURVE_ED25519_LEGACY,
                ],
                'error key' => 'preferred_key_type.invalid_key_type_curve_combination',
            ],
        ];
    }

    /**
     * @dataProvider invalidTypeSizeCurveCombinationProvider
     * @param array $providerData Data to overwrite.
     * @param string $errorKeyPath Expected error key path.
     * @return void
     */
    public function testUserKeyPoliciesSettingsForm_Validate_InvalidTypeSizeCurveCombinations(array $providerData, string $errorKeyPath): void
    {
        $data = $this->getDummyUserKeyPoliciesSettings($providerData);

        $result = $this->form->validate($data);

        $this->assertFalse($result);
        $errors = $this->form->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertTrue(Hash::check($errors, $errorKeyPath));
    }
}
