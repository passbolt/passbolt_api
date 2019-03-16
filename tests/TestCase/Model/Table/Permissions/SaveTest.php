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

namespace App\Test\TestCase\Model\Table\Permissions;

use App\Model\Table\PermissionsTable;
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

    public $fixtures = ['app.Base/Groups', 'app.Base/Permissions', 'app.Base/Resources', 'app.Base/Users'];

    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Permissions') ? [] : ['className' => PermissionsTable::class];
        $this->Permissions = TableRegistry::getTableLocator()->get('Permissions', $config);
    }

    public function tearDown()
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

    /* ************************************************************** */
    /* FORMAT VALIDATION TESTS */
    /* ************************************************************** */

    public function testValidationAco()
    {
        $testCases = [
            'inList' => self::getInListTestCases(PermissionsTable::ALLOWED_ACOS),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Permissions, 'aco', self::getDummyPermission(), self::getEntityDefaultOptions(), $testCases);
    }

    public function testValidationAcoForeignKey()
    {
        $testCases = [
            'uuid' => self::getUuidTestCases(),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Permissions, 'aco_foreign_key', self::getDummyPermission(), self::getEntityDefaultOptions(), $testCases);
    }

    public function testValidationAro()
    {
        $testCases = [
            'inList' => self::getInListTestCases(PermissionsTable::ALLOWED_AROS),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Permissions, 'aro', self::getDummyPermission(), self::getEntityDefaultOptions(), $testCases);
    }

    public function testValidationAroForeignKey()
    {
        $testCases = [
            'uuid' => self::getUuidTestCases(),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Permissions, 'aro_foreign_key', self::getDummyPermission(), self::getEntityDefaultOptions(), $testCases);
    }

    public function testValidationType()
    {
        $testCases = [
            'inList' => self::getInListTestCases(PermissionsTable::ALLOWED_TYPES),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Permissions, 'type', self::getDummyPermission(), self::getEntityDefaultOptions(), $testCases);
    }

    /* ************************************************************** */
    /* LOGIC VALIDATION TESTS */
    /* ************************************************************** */

    public function testUserResourcePermissionSuccess()
    {
        $data = self::getDummyPermission();
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
        $data = self::getDummyPermission();
        $data['aro'] = 'Group';
        $data['aro_foreign_key'] = UuidFactory::uuid('group.id.board');
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
        $data = self::getDummyPermission();
        $data['aco_foreign_key'] = UuidFactory::uuid('resource.id.apache');
        $data['aro_foreign_key'] = UuidFactory::uuid('user.id.ada');
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
        $data = self::getDummyPermission();
        $data['aco_foreign_key'] = UuidFactory::uuid();
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
        $data = self::getDummyPermission();
        $data['aro_foreign_key'] = UuidFactory::uuid();
        $options = self::getEntityDefaultOptions();
        $entity = $this->Permissions->newEntity($data, $options);

        $save = $this->Permissions->save($entity);
        $this->assertFalse($save);
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['aro_foreign_key']['aro_exists']);

        // Check w/ a user that is soft deleted.
        $data = self::getDummyPermission();
        $data['aro_foreign_key'] = UuidFactory::uuid('user.id.sofia');
        $options = self::getEntityDefaultOptions();
        $entity = $this->Permissions->newEntity($data, $options);

        $save = $this->Permissions->save($entity);
        $this->assertFalse($save);
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['aro_foreign_key']['aro_exists']);

        // Check w/ a group that does not exist.
        $data = self::getDummyPermission();
        $data['aro'] = 'Group';
        $data['aro_foreign_key'] = UuidFactory::uuid();
        $options = self::getEntityDefaultOptions();
        $entity = $this->Permissions->newEntity($data, $options);

        $save = $this->Permissions->save($entity);
        $this->assertFalse($save);
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['aro_foreign_key']['aro_exists']);

        // Check w/ a group that is soft deleted.
        $data = self::getDummyPermission();
        $data['aro'] = 'Group';
        $data['aro_foreign_key'] = UuidFactory::uuid('group.id.deleted');
        $options = self::getEntityDefaultOptions();
        $entity = $this->Permissions->newEntity($data, $options);

        $save = $this->Permissions->save($entity);
        $this->assertFalse($save);
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['aro_foreign_key']['aro_exists']);
    }
}
