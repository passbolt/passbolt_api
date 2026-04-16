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

namespace App\Test\TestCase\Model\Table\Permissions;

use App\Model\Entity\Permission;
use App\Model\Table\PermissionsTable;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\FormatValidationTrait;
use App\Test\Lib\Model\PermissionsModelTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class SaveTest extends AppTestCase
{
    use FormatValidationTrait;
    use PermissionsModelTrait;

    public $Permissions;

    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Permissions') ? [] : ['className' => PermissionsTable::class];
        $this->Permissions = TableRegistry::getTableLocator()->get('Permissions', $config);
    }

    public function tearDown(): void
    {
        unset($this->Permissions);

        parent::tearDown();
    }

    protected function getEntityDefaultOptions()
    {
        return [
            'validate' => 'default',
            'accessibleFields' => [
                'aco' => true,
                'aco_foreign_key' => true,
                'aro' => true,
                'aro_foreign_key' => true,
                'type' => true,
            ],
        ];
    }

    /**
     * Build default Permission data using factories.
     */
    private function generateDummyPermissionData(array $data = []): array
    {
        $userA = UserFactory::make()->persist();
        $resource = ResourceFactory::make()->persist();

        return array_merge([
            'aco' => 'Resource',
            'aco_foreign_key' => $resource->id,
            'aro' => 'User',
            'aro_foreign_key' => $userA->id,
            'type' => Permission::OWNER,
        ], $data);
    }

    /* FORMAT VALIDATION TESTS */

    public function testValidationAco()
    {
        $testCases = [
            'inList' => self::getInListTestCases(PermissionsTable::ALLOWED_ACOS),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Permissions, 'aco', $this->generateDummyPermissionData(), self::getEntityDefaultOptions(), $testCases);
    }

    public function testValidationAcoForeignKey()
    {
        $testCases = [
            'uuid' => self::getUuidTestCases(),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Permissions, 'aco_foreign_key', $this->generateDummyPermissionData(), self::getEntityDefaultOptions(), $testCases);
    }

    public function testValidationAro()
    {
        $testCases = [
            'inList' => self::getInListTestCases(PermissionsTable::ALLOWED_AROS),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Permissions, 'aro', $this->generateDummyPermissionData(), self::getEntityDefaultOptions(), $testCases);
    }

    public function testValidationAroForeignKey()
    {
        $testCases = [
            'uuid' => self::getUuidTestCases(),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Permissions, 'aro_foreign_key', $this->generateDummyPermissionData(), self::getEntityDefaultOptions(), $testCases);
    }

    public function testValidationType()
    {
        $testCases = [
            'inList' => self::getInListTestCases(PermissionsTable::ALLOWED_TYPES),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Permissions, 'type', $this->generateDummyPermissionData(), self::getEntityDefaultOptions(), $testCases);
    }

    /* LOGIC VALIDATION TESTS */

    public function testUserResourcePermissionSuccess()
    {
        $data = $this->generateDummyPermissionData();
        $options = self::getEntityDefaultOptions();
        $entity = $this->Permissions->newEntity($data, $options);
        $save = $this->Permissions->save($entity);
        $this->assertEmpty($entity->getErrors(), 'Errors occurred while saving the entity: ' . json_encode($entity->getErrors()));
        $this->assertNotFalse($save, 'The permission save operation failed.');

        // Check that the resource and its sub-models are saved as expected.
        $permission = $this->Permissions->find()
            ->where(['Permissions.id' => $save->id])
            ->first();

        // Check the resource attributes.
        $this->assertPermissionAttributes($permission);
        $this->assertEquals($data['aco'], $permission->aco);
        $this->assertEquals($data['aco_foreign_key'], $permission->aco_foreign_key);
        $this->assertEquals($data['aro'], $permission->aro);
        $this->assertEquals($data['aro_foreign_key'], $permission->aro_foreign_key);
        $this->assertEquals($data['type'], $permission->type);
    }

    public function testGroupResourcePermissionSuccess()
    {
        $userB = UserFactory::make()->persist();
        $groupA = GroupFactory::make()->withGroupsManagersFor([$userB])->persist();
        $data = $this->generateDummyPermissionData([
            'aro' => 'Group',
            'aro_foreign_key' => $groupA->id,
        ]);
        $options = self::getEntityDefaultOptions();
        $entity = $this->Permissions->newEntity($data, $options);
        $save = $this->Permissions->save($entity);
        $this->assertEmpty($entity->getErrors(), 'Errors occurred while saving the entity: ' . json_encode($entity->getErrors()));
        $this->assertNotFalse($save, 'The permission save operation failed.');

        // Check that the resource and its sub-models are saved as expected.
        $permission = $this->Permissions->find()
            ->where(['Permissions.id' => $save->id])
            ->first();

        // Check the resource attributes.
        $this->assertPermissionAttributes($permission);
        $this->assertEquals($data['aco'], $permission->aco);
        $this->assertEquals($data['aco_foreign_key'], $permission->aco_foreign_key);
        $this->assertEquals($data['aro'], $permission->aro);
        $this->assertEquals($data['aro_foreign_key'], $permission->aro_foreign_key);
        $this->assertEquals($data['type'], $permission->type);
    }

    public function testErrorRulePermissionUnique()
    {
        $userA = UserFactory::make()->persist();
        $resource = ResourceFactory::make()->withPermissionsFor([$userA])->persist();
        $data = $this->generateDummyPermissionData([
            'aco_foreign_key' => $resource->id,
            'aro_foreign_key' => $userA->id,
        ]);
        $options = self::getEntityDefaultOptions();
        $entity = $this->Permissions->newEntity($data, $options);

        $save = $this->Permissions->save($entity);
        $this->assertFalse($save);
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['aco_foreign_key']['permission_unique']);
    }

    public function testErrorRuleAcoExists()
    {
        $data = $this->generateDummyPermissionData([
            'aco_foreign_key' => UuidFactory::uuid(),
        ]);
        $options = self::getEntityDefaultOptions();
        $entity = $this->Permissions->newEntity($data, $options);

        $save = $this->Permissions->save($entity);
        $this->assertFalse($save);
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['aco_foreign_key']['aco_exists']);
    }

    public function testErrorRuleAroExists()
    {
        // Check w/ a user that does not exist.
        $data = $this->generateDummyPermissionData([
            'aro_foreign_key' => UuidFactory::uuid(),
        ]);
        $options = self::getEntityDefaultOptions();
        $entity = $this->Permissions->newEntity($data, $options);

        $save = $this->Permissions->save($entity);
        $this->assertFalse($save);
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['aro_foreign_key']['aro_exists']);

        // Check w/ a user that is soft deleted.
        $userDeleted = UserFactory::make()->deleted()->persist();
        $data = $this->generateDummyPermissionData([
            'aro_foreign_key' => $userDeleted->id,
        ]);
        $options = self::getEntityDefaultOptions();
        $entity = $this->Permissions->newEntity($data, $options);

        $save = $this->Permissions->save($entity);
        $this->assertFalse($save);
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['aro_foreign_key']['aro_exists']);

        // Check w/ a group that does not exist.
        $data = $this->generateDummyPermissionData([
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid(),
        ]);
        $options = self::getEntityDefaultOptions();
        $entity = $this->Permissions->newEntity($data, $options);

        $save = $this->Permissions->save($entity);
        $this->assertFalse($save);
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['aro_foreign_key']['aro_exists']);

        // Check w/ a group that is soft deleted.
        $groupDeleted = GroupFactory::make()->deleted()->persist();
        $data = $this->generateDummyPermissionData([
            'aro' => 'Group',
            'aro_foreign_key' => $groupDeleted->id,
        ]);
        $options = self::getEntityDefaultOptions();
        $entity = $this->Permissions->newEntity($data, $options);

        $save = $this->Permissions->save($entity);
        $this->assertFalse($save);
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['aro_foreign_key']['aro_exists']);
    }
}
