<?php

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

namespace App\Test\Lib\Model;

use Cake\ORM\Table;
use Cake\Utility\Hash;
use Cake\Validation\Validation;

trait FormatValidationTrait
{

    /**
     * Defines a field that is not provided.
     * When used in assertFieldFormatValidation, the field will be removed from the entity before validation.
     * @var string
     */
    public static $FIELD_NOT_PROVIDED = '__NOT_PROVIDED__';

    /**
     * Defines a field that is left empty.
     * When used in assertFieldFormatValidation, the field will be set to '' before validation.
     * @var string
     */
    public static $FIELD_EMPTY = '__FIELD_EMPTY__';

    /**
     * Defines a field that is not scalar.
     * When used in assertFieldFormatValidation, the field will be set to [] before validation.
     * @var string
     */
    public static $FIELD_NOT_SCALAR = '__FIELD_NOT_SCALAR__';

    /**
     * Adjust entity data before validation.
     * This function will mainly process special fields such as FIELD_NOT_PROVIDED,
     * FIELD_EMPTY and FIELD_NOT_SCALAR and replace the value with what it should be.
     * for instance, FIELD_NOT_PROVIDED will be unset from the data array.
     * @param $entityData
     *
     * @return mixed
     */
    private function _adjustEntityData($entityData)
    {
        foreach ($entityData as $fieldName => $value) {
            if ($value === self::$FIELD_NOT_SCALAR) {
                $entityData[$fieldName] = ['array'];
            } elseif ($value === self::$FIELD_EMPTY) {
                $entityData[$fieldName] = '';
            } elseif ($value === self::$FIELD_NOT_PROVIDED) {
                unset($entityData[$fieldName]);
            }
        }

        return $entityData;
    }

    /**
     * Assert that a field passes the format validation according to the test cases given.
     *
     * Beware, this function tests only the format validation rules. The custom rules
     * defined in buildRules will not be tested.
     *
     * @param \Cake\ORM\Table $entityTable the entityTable object
     * @param string $fieldName field name to be validated
     * @param array $entityData data to populate the entity with. Add 'id' if you want to test an update.
     * @param array $entityOptions entity options used at the creation
     * @param array $testCases the test cases to run
     *   a test case array is composed as follow:
     *   [
     *     '$name' => [
     *       [
     *         'rule_name' => 'uuid',
     *         'test_cases' => [
     *           'string1' => true, // Defines a value that should validate
     *           'string2' => false // Defines a value that shouldn't validate
     *         ]
     *       ],
     *       [....]
     *     ]
     *   ]
     * @return void
     */
    public function assertFieldFormatValidation($entityTable, $fieldName, $entityData, $entityOptions, $testCases)
    {
        $context = isset($entityData['id']) ? 'update' : 'create';
        foreach ($testCases as $testCaseName => $testCase) {
            foreach ($testCase['test_cases'] as $testCaseData => $expectedResult) {
                if ($context == 'create') {
                    // Update entity data with the input we want to test.
                    $entityData = array_merge($entityData, [$fieldName => $testCaseData]);
                    $entityData = $this->_adjustEntityData($entityData);
                    $entity = $entityTable->newEntity($entityData, $entityOptions);
                } elseif ($context == 'update') {
                    $entity = $entityTable->get($entityData['id']);
                    $entityData = array_merge($entityData, [$fieldName => $testCaseData]);
                    $entityData = $this->_adjustEntityData($entityData);
                    $entity = $entityTable->patchEntity($entity, $entityData, $entityOptions);
                }

                $save = $entityTable->save($entity, ['checkRules' => false]);

                if ($expectedResult == true) {
                    $this->assertEquals(true, (bool)$save, __("The test for {0}:{1} = {2} is expected to save data", $fieldName, $testCaseName, $testCaseData));
                } else {
                    $this->assertEquals(false, (bool)$save, __("The test for {0}:{1} = {2} is not expected to save data", $fieldName, $testCaseName, $testCaseData));
                    $errors = $entity->getErrors();
                    $this->assertNotEmpty($errors, __("The test {0}:{1} = {2} should have returned an error.", $fieldName, $testCaseName, $testCaseData));
                    $this->assertNotEmpty(
                        Hash::extract($errors, "$fieldName.{$testCase['rule_name']}"),
                        __("The test {0}:{1} = {2} should have returned an error on the rule {3} but did not.", $fieldName, $testCaseName, $testCaseData, $testCase['rule_name'])
                    );
                }
            }
        }
    }

    /**
     * Assert that a field passes the format validation according to the test cases given.
     *
     * Beware, this function tests only the format validation rules. The custom rules
     * defined in buildRules will not be tested.
     *
     * @param \Cake\ORM\Table $entityTable the entityTable object
     * @param string $fieldName field name to be validated
     * @param array $entityData data to populate the entity with. Add 'id' if you want to test an update.
     * @param array $entityOptions entity options used at the creation
     * @param array $testCases the test cases to run
     *   a test case array is composed as follow:
     *   [
     *     '$name' => [
     *       [
     *         'rule_name' => 'uuid',
     *         'test_cases' => [
     *           'string1' => true, // Defines a value that should validate
     *           'string2' => false // Defines a value that shouldn't validate
     *         ]
     *       ],
     *       [....]
     *     ]
     *   ]
     * @return void
     */
    public function assertFormFieldFormatValidation($FormClass, $fieldName, $formData, $testCases)
    {
        foreach ($testCases as $testCaseName => $testCase) {
            foreach ($testCase['test_cases'] as $testCaseData => $expectedResult) {
                $formData = array_merge($formData, [$fieldName => $testCaseData]);
                $formData = $this->_adjustEntityData($formData);
                $form = new $FormClass();
                $validate = $form->validate($formData);

                if ($expectedResult == true) {
                    $this->assertEquals(true, (bool)$validate, __("The test for {0}:{1} = {2} is expected to validate", $fieldName, $testCaseName, $testCaseData));
                } else {
                    $this->assertEquals(false, (bool)$validate, __("The test for {0}:{1} = {2} is not expected to not validate", $fieldName, $testCaseName, $testCaseData));
                    $errors = $form->getErrors();
                    $this->assertNotEmpty($errors, __("The test {0}:{1} = {2} should have returned an error.", $fieldName, $testCaseName, $testCaseData));
                    $this->assertNotEmpty(
                        Hash::extract($errors, "$fieldName.{$testCase['rule_name']}"),
                        __("The test {0}:{1} = {2} should have returned an error on the rule {3} but did not.", $fieldName, $testCaseName, $testCaseData, $testCase['rule_name'])
                    );
                }
            }
        }
    }

    public function assertPatchEntityValidation($entityTable, $fieldName, $entity, $entityOptions, $testCases)
    {
        foreach ($testCases as $testCaseName => $testCase) {
            foreach ($testCase['test_cases'] as $testCaseData => $expectedResult) {
                // Patch the entity with the input we want to test.
                $entityData = [$fieldName => $testCaseData];
                $entityData = $this->_adjustEntityData($entityData);
                $entity = $entityTable->patchEntity($entity, $entityData, $entityOptions);
                $save = $entityTable->save($entity, ['checkRules' => false]);

                if ($expectedResult == true) {
                    $this->assertEquals(true, (bool)$save, __("The test for {0}:{1} = {2} is expected to save data", $fieldName, $testCaseName, $testCaseData));
                } else {
                    $this->assertEquals(false, (bool)$save, __("The test for {0}:{1} = {2} is not expected to save data", $fieldName, $testCaseName, $testCaseData));
                    $errors = $entity->getErrors();
                    $this->assertNotEmpty($errors, __("The test {0}:{1} = {2} should have returned an error.", $fieldName, $testCaseName, $testCaseData));
                    $this->assertNotEmpty(
                        Hash::extract($errors, "$fieldName.{$testCase['rule_name']}"),
                        __("The test {0}:{1} = {2} should have returned an error on the rule {3} but did not.", $fieldName, $testCaseName, $testCaseData, $testCase['rule_name'])
                    );
                }
            }
        }
    }

    /**
     * Test cases for uuid validation rule.
     *
     * @return array
     */
    public static function getUuidTestCases()
    {
        $test = [
            'rule_name' => 'uuid',
            'test_cases' => [
                'aaa00003-c5cd-11e1-a0c5-080027z!6c4c' => false,
                'aaa00003-c5cd-11e1-a0c5-080027796c4c' => true,
            ],
        ];

        return $test;
    }

    /**
     * Test cases for allowEmpty validation rule.
     *
     * @return array
     */
    public static function getAllowEmptyTestCases()
    {
        $test = [
            'rule_name' => '_empty',
            'test_cases' => [
                self::$FIELD_EMPTY => true,
            ],
        ];

        return $test;
    }

    /**
     * Test cases for notEmpty validation rule.
     *
     * @return array
     */
    public static function getNotEmptyTestCases()
    {
        $test = [
            'rule_name' => '_empty',
            'test_cases' => [
                self::$FIELD_EMPTY => false,
            ],
        ];

        return $test;
    }

    /**
     * Test cases for requirePresence validation rule.
     *
     * @return array
     */
    public static function getRequirePresenceTestCases()
    {
        $test = [
            'rule_name' => '_required',
            'test_cases' => [
                self::$FIELD_NOT_PROVIDED => false,
            ],
        ];

        return $test;
    }

    /**
     * Test cases for scalar validation rule.
     *
     * @return array
     */
    public static function getScalarTestCases()
    {
        $test = [
            'rule_name' => 'scalar',
            'test_cases' => [
                self::$FIELD_NOT_SCALAR => false,
            ],
        ];

        return $test;
    }

    /**
     * Test cases for boolean validation rule.
     *
     * @return array
     */
    public static function getBooleanTestCases()
    {
        $test = [
            'rule_name' => 'boolean',
            'test_cases' => [
                true => true,
                false => true,
                1 => true,
                0 => true,
                '1' => true,
                '0' => true,
                'abcd' => false,
                125 => false,
            ],
        ];

        return $test;
    }

    /**
     * Test cases for inList validation rule.
     *
     * @param array $list test cases
     * @return array
     */
    public static function getInListTestCases($list = [])
    {
        $test = [
            'rule_name' => 'inList',
            'test_cases' => [],
        ];
        foreach ($list as $elt) {
            $test['test_cases'][$elt] = true;
        }
        $test['test_cases']['__NOT_IN_LIST__'] = false;

        return $test;
    }

    /**
     * Test cases for utf8 validation rule.
     *
     * @param int $length default 255
     * @return array
     */
    public static function getUtf8TestCases($length = 255)
    {
        $test = [
            'rule_name' => '_required',
            'test_cases' => [
                self::getStringMask('alphaASCII', $length) => true,
                self::getStringMask('alphaASCIIUpper', $length) => true,
                self::getStringMask('alphaAccent', $length) => true,
                self::getStringMask('alphaChinese', $length) => true,
                self::getStringMask('alphaArabic', $length) => true,
                self::getStringMask('alphaRussian', $length) => true,
                self::getStringMask('special', $length) => true,
                self::getStringMask('html', $length) => true,
            ],
        ];

        return $test;
    }

    /**
     * Test cases for ascii validation rule.
     *
     * @param int $length default 255
     * @return array
     */
    public static function getAsciiTestCases($length = 255)
    {
        $test = [
            'rule_name' => '_ascii',
            'test_cases' => [
                self::getStringMask('alphaASCII', $length) => true,
                self::getStringMask('alphaASCIIUpper', $length) => true
            ],
        ];

        return $test;
    }

    /**
     * Test cases for utf8Extended validation rule.
     *
     * @param int $length default 255
     * @return array
     */
    public static function getUtf8ExtendedTestCases($length = 255)
    {
        $test = self::getUtf8TestCases($length);
        $test['test_cases'][self::getStringMask('alphaEmojis', $length)] = true;

        return $test;
    }

    /**
     * Test cases for lengthBetween validation rule.
     *
     * @param int $min minimum
     * @param int $max maximum
     * @return array
     */
    public static function getLengthBetweenTestCases($min, $max)
    {
        $test = [
            'rule_name' => 'lengthBetween',
            'test_cases' => [
                self::getStringMask('alphaASCII', $min) => true,
                self::getStringMask('alphaASCII', $max) => true,
                self::getStringMask('alphaASCII', $max + 1) => false,
            ],
        ];

        if ($min > 1) {
            $test['test_cases'][self::getStringMask('alphaASCII', $min - 1)] = false;
        }

        return $test;
    }

    /**
     * Test emails validation rule.
     *
     * @param bool $checkMx
     * @return array
     */
    public static function getEmailTestCases($checkMx = false)
    {
        $test = [
            'rule_name' => 'email',
            'test_cases' => [
                '0' => false,
                'nope' => false,
                'passbolt.com' => false,
                'dummy@passbolt.com' => true,
                'dummy@unreachable.tld' => !$checkMx,
            ],
        ];

        return $test;
    }

    /**
     * Test cases for maxLength validation rule.
     *
     * @param int $max maximum
     * @return array
     */
    public static function getMaxLengthTestCases($max)
    {
        $test = [
            'rule_name' => 'maxLength',
            'test_cases' => [
                self::getStringMask('alphaASCII', 0) => true,
                self::getStringMask('alphaASCII', $max) => true,
                self::getStringMask('alphaASCII', $max + 1) => false,
            ],
        ];

        return $test;
    }

    /**
     * Test cases for range validation rule.
     *
     * @param int $min minimum
     * @param int $max maximum
     * @return array
     */
    public static function getRangeTestCases($min, $max)
    {
        $test = [
            'rule_name' => 'range',
            'test_cases' => [
                $min - 1 => false,
                $min => true,
                $max => true,
                $max + 1 => false
            ],
        ];

        return $test;
    }

    /**
     * Test cases for gpg message validation rule.
     *
     * @return array
     */
    protected static function getGpgMessageTestCases()
    {
        return [
            'rule_name' => 'isValidGpgMessage',
            'test_cases' => [
                '!#*' => false,
                // Message without gpg markers shouldn't be valid
                'hQEMAwvNmZMMcWZiAQf9HpfcNeuC5W/VAzEtAe8mTBUk1vcJENtGpMyRkVTC8KbQ
xaEr3+UG6h0ZVzfrMFYrYLolS3fie83cj4FnC3gg1uijo7zTf9QhJMdi7p/ASB6N
y7//8AriVqUAOJ2WCxAVseQx8qt2KqkQvS7F7iNUdHfhEhiHkczTlehyel7PEeas
SdM/kKEsYKk6i4KLPBrbWsflFOkfQGcPL07uRK3laFz8z4LNzvNQOoU7P/C1L0X3
tlK3vuq+r01zRwmflCaFXaHVifj3X74ljhlk5i/JKLoPRvbxlPTevMNag5e6QhPQ
kpj+TJD2frfGlLhyM50hQMdJ7YVypDllOBmnTRwZ0tJFAXm+F987ovAVLMXGJtGO
P+b3c493CfF0fQ1MBYFluVK/Wka8usg/b0pNkRGVWzBcZ1BOONYlOe/JmUyMutL5
hcciUFw5
=TcQF' => false,
                // Corrupted message
                '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf9HpfcNeuC5W/VAzEtAe8mTBUk1vcJENtGpMyRkVTC8KbQ
xaEr3+UG6h0ZVzfrMFYrYLolS3fie83cj4FnC3gg1uijo7zTf9QhJMdi7p/ASB6N
y7//8AriVqUAOJ2WCxAVseQx8qt2KqkQvS7F7iNUdHfhEhiHkczTlehyel7PEeas
SdM/kKEsYKk6i4KLPBrbWsflFOkfQGcPL07uRK3laFz8z4LNzvNQOoU7P/C1L0X3
tlK3vuq+r01zRwmflCaFXaHVifj3X74ljhlk5i/JKLoPRvbxlPTevMNag5e6QhPQ
kpj+TJD2frfGlLhyM50hQMdJ7YVypDllOBmnTRwZ0tJFAXm+F987ovAVLMXGJtGO
P+b3c493CfF0fQ1MBYFluVK/Wka8usg/b0pNkRGVWzBcZ1BOONYlOe/JmUyMutL5
hcciUFw
-----END PGP MESSAGE-----' => false,
                '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf9HpfcNeuC5W/VAzEtAe8mTBUk1vcJENtGpMyRkVTC8KbQ
xaEr3+UG6h0ZVzfrMFYrYLolS3fie83cj4FnC3gg1uijo7zTf9QhJMdi7p/ASB6N
y7//8AriVqUAOJ2WCxAVseQx8qt2KqkQvS7F7iNUdHfhEhiHkczTlehyel7PEeas
SdM/kKEsYKk6i4KLPBrbWsflFOkfQGcPL07uRK3laFz8z4LNzvNQOoU7P/C1L0X3
tlK3vuq+r01zRwmflCaFXaHVifj3X74ljhlk5i/JKLoPRvbxlPTevMNag5e6QhPQ
kpj+TJD2frfGlLhyM50hQMdJ7YVypDllOBmnTRwZ0tJFAXm+F987ovAVLMXGJtGO
P+b3c493CfF0fQ1MBYFluVK/Wka8usg/b0pNkRGVWzBcZ1BOONYlOe/JmUyMutL5
hcciUFw5
=TcQF
-----END PGP MESSAGE-----' => true,
            ],
        ];
    }

    protected function _reloadValidationRules(Table $entityTable)
    {
        $entityTable->validationDefault($entityTable->getValidator());
    }
}
