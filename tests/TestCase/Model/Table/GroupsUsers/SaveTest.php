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

namespace App\Test\TestCase\Model\Table\GroupsUsers;

use App\Model\Table\GroupsUsersTable;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\FormatValidationTrait;
use App\Test\Lib\Model\GroupsModelTrait;
use App\Test\Lib\Model\GroupsUsersModelTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class SaveTest extends AppTestCase
{
    use FormatValidationTrait;
    use GroupsModelTrait;
    use GroupsUsersModelTrait;

    public $GroupsUsers;

    public $fixtures = ['app.Base/Groups', 'app.Base/Users', 'app.Base/GroupsUsers'];

    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('GroupsUsers') ? [] : ['className' => GroupsUsersTable::class];
        $this->GroupsUsers = TableRegistry::getTableLocator()->get('GroupsUsers', $config);
    }

    public function tearDown()
    {
        unset($this->GroupsUsers);

        parent::tearDown();
    }

    protected function getEntityDefaultOptions()
    {
        return [
            'validate' => 'default',
            'accessibleFields' => [
                'group_id' => true,
                'user_id' => true,
                'is_admin' => true,
            ]
        ];
    }

    /* ************************************************************** */
    /* FORMAT VALIDATION TESTS */
    /* ************************************************************** */

    public function testValidationGroupId()
    {
        $testCases = [
            'uuid' => self::getUuidTestCases(),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->GroupsUsers, 'group_id', self::getDummyGroupUser(), self::getEntityDefaultOptions(), $testCases);
    }

    public function testValidationUserId()
    {
        $testCases = [
            'uuid' => self::getUuidTestCases(),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->GroupsUsers, 'user_id', self::getDummyGroupUser(), self::getEntityDefaultOptions(), $testCases);
    }

    public function testValidationIsAdmin()
    {
        $testCases = [
            'boolean' => self::getBooleanTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->GroupsUsers, 'is_admin', self::getDummyGroupUser(), self::getEntityDefaultOptions(), $testCases);
    }

    /* ************************************************************** */
    /* LOGIC VALIDATION TESTS */
    /* ************************************************************** */

    public function testSuccess()
    {
        $data = self::getDummyGroupUser();
        $options = self::getEntityDefaultOptions();
        $entity = $this->GroupsUsers->newEntity($data, $options);
        $save = $this->GroupsUsers->save($entity);
        $this->assertEmpty($entity->getErrors(), 'Errors occurred while saving the entity: ' . json_encode($entity->getErrors()));
        $this->assertNotFalse($save, 'The group user save operation failed.');

        // Check that the groups and its sub-models are saved as expected.
        $group = $this->GroupsUsers->find()
            ->where(['id' => $save->id])
            ->first();
        $this->assertEquals($data['group_id'], $group->group_id);
        $this->assertEquals($data['user_id'], $group->user_id);
        $this->assertEquals($data['is_admin'], $group->is_admin);
    }

    public function testErrorRuleGroupUserUnique()
    {
        $data = self::getDummyGroupUser();
        $data['group_id'] = UuidFactory::uuid('group.id.freelancer');
        $data['user_id'] = UuidFactory::uuid('user.id.jean');
        $options = self::getEntityDefaultOptions();
        $entity = $this->GroupsUsers->newEntity($data, $options);
        $save = $this->GroupsUsers->save($entity);
        $this->assertFalse($save);
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['group_id']['group_user_unique']);
    }

    public function testErrorRuleGroupExists()
    {
        $data = self::getDummyGroupUser();
        $data['group_id'] = UuidFactory::uuid();
        $options = self::getEntityDefaultOptions();
        $entity = $this->GroupsUsers->newEntity($data, $options);
        $save = $this->GroupsUsers->save($entity);
        $this->assertFalse($save);
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['group_id']['group_exists']);
    }

    public function testErrorRuleGroupIsNotSoftDeleted()
    {
        $data = self::getDummyGroupUser();
        $data['group_id'] = UuidFactory::uuid('group.id.deleted');
        $options = self::getEntityDefaultOptions();
        $entity = $this->GroupsUsers->newEntity($data, $options);
        $save = $this->GroupsUsers->save($entity);
        $this->assertFalse($save);
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['group_id']['group_is_not_soft_deleted']);
    }

    public function testErrorRuleUserExists()
    {
        $data = self::getDummyGroupUser();
        $data['user_id'] = UuidFactory::uuid();
        $options = self::getEntityDefaultOptions();
        $entity = $this->GroupsUsers->newEntity($data, $options);
        $save = $this->GroupsUsers->save($entity);
        $this->assertFalse($save);
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['user_id']['user_exists']);
    }

    public function testErrorRuleUserIsNotSoftDeleted()
    {
        $data = self::getDummyGroupUser();
        $data['user_id'] = UuidFactory::uuid('user.id.sofia');
        $options = self::getEntityDefaultOptions();
        $entity = $this->GroupsUsers->newEntity($data, $options);
        $save = $this->GroupsUsers->save($entity);
        $this->assertFalse($save);
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['user_id']['user_is_not_soft_deleted']);
    }

    public function testErrorRuleUserIsActive()
    {
        $data = self::getDummyGroupUser();
        $data['user_id'] = UuidFactory::uuid('user.id.ruth');
        $options = self::getEntityDefaultOptions();
        $entity = $this->GroupsUsers->newEntity($data, $options);
        $save = $this->GroupsUsers->save($entity);
        $this->assertFalse($save);
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['user_id']['user_is_active']);
    }
}
