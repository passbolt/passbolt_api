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

namespace App\Test\TestCase\Model\Table\Resources;

use App\Model\Entity\Permission;
use App\Model\Table\ResourcesTable;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\FormatValidationTrait;
use App\Test\Lib\Model\ResourcesModelTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Passbolt\ResourceTypes\Model\Entity\ResourceType;

/**
 * @covers \App\Model\Table\ResourcesTable
 */
class ValidationRulesTest extends AppTestCase
{
    use FormatValidationTrait;
    use ResourcesModelTrait;

    public $Resources;

    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Resources') ? [] : ['className' => ResourcesTable::class];
        $this->Resources = TableRegistry::getTableLocator()->get('Resources', $config);
        $this->ResourceTypes = TableRegistry::getTableLocator()->get('ResourceTypes', $config);
    }

    public function tearDown(): void
    {
        unset($this->Resources);

        parent::tearDown();
    }

    protected function getEntityDefaultOptions(): array
    {
        return [
            'validate' => 'default',
            'accessibleFields' => [
                'name' => true,
                'username' => true,
                'uri' => true,
                'description' => true,
                'created_by' => true,
                'modified_by' => true,
                'secrets' => true,
                'permissions' => true,
                'resource_type_id' => true,
            ],
            'associated' => [
                'Permissions' => [
                    'validate' => 'saveResource',
                    'accessibleFields' => [
                        'aco' => true,
                        'aro' => true,
                        'aro_foreign_key' => true,
                        'type' => true,
                    ],
                ],
                'Secrets' => [
                    'validate' => 'saveResource',
                    'accessibleFields' => [
                        'user_id' => true,
                        'data' => true,
                    ],
                ],
            ],
        ];
    }

    private function _getDummyResourceData(): array
    {
        $userId = UuidFactory::uuid5('user.id.ada');
        $dummy = self::getDummyResourceData();
        $dummy['permissions'][] = $this->getDummyPermission(['aco_foreign_key' => $userId]);
        $dummy['secrets'][] = $this->getDummySecretData(['resource_id' => null]);

        return $dummy;
    }

    /* FORMAT VALIDATION TESTS */

    public function testResourcesTable_ValidationName()
    {
        $testCases = [
            'utf8Extended' => self::getUtf8ExtendedTestCases(255),
            'maxLength' => self::getMaxLengthTestCases(255),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Resources, 'name', $this->_getDummyResourceData(), self::getEntityDefaultOptions(), $testCases);
    }

    public function testResourcesTable_ValidationUsername()
    {
        $testCases = [
            'utf8Extended' => self::getUtf8ExtendedTestCases(255),
            'maxLength' => self::getMaxLengthTestCases(255),
            'allowEmpty' => self::getAllowEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Resources, 'username', $this->_getDummyResourceData(), self::getEntityDefaultOptions(), $testCases);
    }

    public function testResourcesTable_ValidationUri()
    {
        $testCases = [
            'utf8' => self::getUtf8TestCases(1024),
            'maxLength' => self::getMaxLengthTestCases(1024),
            'allowEmpty' => self::getAllowEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Resources, 'uri', $this->_getDummyResourceData(), self::getEntityDefaultOptions(), $testCases);
    }

    public function testResourcesTable_ValidationDescription()
    {
        $testCases = [
            'utf8Extended' => self::getUtf8ExtendedTestCases(10000),
            'maxLength' => self::getMaxLengthTestCases(10000),
            'allowEmpty' => self::getAllowEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Resources, 'description', $this->_getDummyResourceData(), self::getEntityDefaultOptions(), $testCases);
    }

    public function testResourcesTable_ValidationPermissions()
    {
        $resource = $this->_getDummyResourceData();
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Resources, 'permissions', $resource, self::getEntityDefaultOptions(), $testCases);

        // Cannot use the default AssertFieldFormatValidation to test array value.
        // Test the hasMost rule.
        $userId = UuidFactory::uuid5('user.id.ada');
        $permissions = [[
            'aro' => 'User',
            'aro_foreign_key' => $userId,
            'aco' => 'Resource',
            'type' => Permission::OWNER,
        ], [
            'aro' => 'User',
            'aro_foreign_key' => $userId,
            'aco' => 'Resource',
            'type' => Permission::OWNER,
        ]];
        $entityData = array_merge($resource, ['permissions' => $permissions]);
        $entity = $this->Resources->newEntity($entityData, self::getEntityDefaultOptions());
        $save = $this->Resources->save($entity, ['checkRules' => false]);
        $this->assertEquals(false, (bool)$save, __('The test for {0}:{1} = {2} is not expected to save data', 'permissions', 'hasAtMost', json_encode($permissions)));
    }

    public function testResourcesTable_ValidationSecrets()
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Resources, 'secrets', $this->_getDummyResourceData(), self::getEntityDefaultOptions(), $testCases);
    }

    public function testResourcesTable_ValidationResourceTypeId()
    {
        $testCases = [
            'uuid' => [
                'rule_name' => 'uuid',
                'test_cases' => [
                    'aaa00003-c5cd-11e1-a0c5-080027z!6c4c' => false,
                    UuidFactory::uuid5('resource-types.id.' . ResourceType::SLUG_PASSWORD_STRING) => true,
                ],
            ],
            'requirePresence' => self::getRequirePresenceTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Resources, 'resource_type_id', $this->_getDummyResourceData(), self::getEntityDefaultOptions(), $testCases);
    }

    public function testResourcesTable_Validation_Error_ResourceTypeDoesNotInList()
    {
        $data = $this->_getDummyResourceData();
        $data['resource_type_id'] = UuidFactory::uuid5('nope');
        $options = self::getEntityDefaultOptions();
        $entity = $this->Resources->newEntity($data, $options);
        $save = $this->Resources->save($entity);
        $this->assertFalse($save, 'The resource save operation should fail.');
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['resource_type_id']['inList']);
    }
}
