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

namespace App\Test\TestCase\Model\Table\GroupsUsers;

use App\Model\Table\GroupsUsersTable;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\GroupsUserFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\FormatValidationTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class SaveTest extends AppTestCase
{
    use FormatValidationTrait;

    public $GroupsUsers;

    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('GroupsUsers') ? [] : ['className' => GroupsUsersTable::class];
        $this->GroupsUsers = TableRegistry::getTableLocator()->get('GroupsUsers', $config);
    }

    public function tearDown(): void
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
            ],
        ];
    }

    /**
     * Build default group user data using factories.
     */
    private function generateDummyGroupUserData(array $data = []): array
    {
        $group = GroupFactory::make()->persist();
        $user = UserFactory::make()->persist();

        return array_merge([
            'group_id' => $group->id,
            'user_id' => $user->id,
            'is_admin' => true,
        ], $data);
    }

    /* FORMAT VALIDATION TESTS */

    public function testValidationGroupId()
    {
        $testCases = [
            'uuid' => self::getUuidTestCases(),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->GroupsUsers, 'group_id', $this->generateDummyGroupUserData(), self::getEntityDefaultOptions(), $testCases);
    }

    public function testValidationUserId()
    {
        $testCases = [
            'uuid' => self::getUuidTestCases(),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->GroupsUsers, 'user_id', $this->generateDummyGroupUserData(), self::getEntityDefaultOptions(), $testCases);
    }

    public function testValidationIsAdmin()
    {
        $testCases = [
            'boolean' => self::getBooleanTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->GroupsUsers, 'is_admin', $this->generateDummyGroupUserData(), self::getEntityDefaultOptions(), $testCases);
    }

    /* LOGIC VALIDATION TESTS */

    public function testSuccess()
    {
        $data = $this->generateDummyGroupUserData();
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
        $existingGroupUser = GroupsUserFactory::make()->persist();
        $data = $this->generateDummyGroupUserData([
            'group_id' => $existingGroupUser->group_id,
            'user_id' => $existingGroupUser->user_id,
        ]);
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
        $data = $this->generateDummyGroupUserData();
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
        $deletedGroup = GroupFactory::make()->deleted()->persist();
        $data = $this->generateDummyGroupUserData(['group_id' => $deletedGroup->id]);
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
        $data = $this->generateDummyGroupUserData();
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
        $deletedUser = UserFactory::make()->deleted()->persist();
        $data = $this->generateDummyGroupUserData(['user_id' => $deletedUser->id]);
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
        $inactiveUser = UserFactory::make()->inactive()->persist();
        $data = $this->generateDummyGroupUserData(['user_id' => $inactiveUser->id]);
        $options = self::getEntityDefaultOptions();
        $entity = $this->GroupsUsers->newEntity($data, $options);
        $save = $this->GroupsUsers->save($entity);
        $this->assertFalse($save);
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['user_id']['user_is_active']);
    }
}
