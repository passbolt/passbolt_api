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

namespace App\Test\TestCase\Model\Table\Groups;

use App\Model\Table\GroupsTable;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\FormatValidationTrait;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class SaveTest extends AppTestCase
{
    use FormatValidationTrait;

    public GroupsTable $Groups;

    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Groups') ? [] : ['className' => GroupsTable::class];
        $this->Groups = TableRegistry::getTableLocator()->get('Groups', $config);
    }

    public function tearDown(): void
    {
        unset($this->Groups);

        parent::tearDown();
    }

    protected function getEntityDefaultOptions()
    {
        return [
            'validate' => 'default',
            'accessibleFields' => [
                'name' => true,
                'created_by' => true,
                'modified_by' => true,
                'groups_users' => true,
                'deleted' => true,
            ],
            'associated' => [
                'GroupsUsers' => [
                    'validate' => 'saveGroup',
                    'accessibleFields' => [
                        'user_id' => true,
                        'is_admin' => true,
                    ],
                ],
            ],
        ];
    }

    /**
     * Build default group data using factories.
     */
    private function generateDummyGroup(array $data = []): array
    {
        $admin = UserFactory::make()->admin()->persist();

        return array_merge([
            'name' => 'New group name',
            'created_by' => $admin->id,
            'modified_by' => $admin->id,
            'deleted' => false,
        ], $data);
    }

    /* FORMAT VALIDATION TESTS */

    public function testValidationName()
    {
        $testCases = [
            'utf8Extended' => self::getUtf8ExtendedTestCases(255),
            'maxLength' => self::getMaxLengthTestCases(255),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Groups, 'name', $this->generateDummyGroup(), self::getEntityDefaultOptions(), $testCases);
    }

    /* LOGIC VALIDATION TESTS */

    public function testSuccess()
    {
        [$userA, $userB] = UserFactory::make(2)->persist();
        $groupData = [
            'groups_users' => [
                ['user_id' => $userA->id, 'is_admin' => true],
                ['user_id' => $userB->id],
            ],
        ];
        $data = $this->generateDummyGroup($groupData);
        $options = self::getEntityDefaultOptions();
        $entity = $this->Groups->newEntity($data, $options);
        $save = $this->Groups->save($entity);
        $this->assertEmpty($entity->getErrors(), 'Errors occurred while saving the entity: ' . json_encode($entity->getErrors()));
        $this->assertNotFalse($save, 'The group save operation failed.');

        // Check that the groups and its sub-models are saved as expected.
        $group = $this->Groups->find()
            ->contain('GroupsUsers')
            ->contain('GroupsUsers.Users')
            ->where(['id' => $save->id])
            ->first();
        $this->assertEquals($data['name'], $group->name);
        $this->assertEquals(false, $group->deleted);
        $this->assertCount(2, $group->groups_users);
        $userAId = $userA->id;
        $userBId = $userB->id;
        $groupUserA = Hash::extract($group->groups_users, "{n}[user_id=$userAId][is_admin=true]");
        $this->assertNotEmpty($groupUserA);
        $groupUserB = Hash::extract($group->groups_users, "{n}[user_id=$userBId]");
        $this->assertNotEmpty($groupUserB);
    }

    public function testSuccessRuleGroupUnique()
    {
        $groupId = GroupFactory::make(['name' => 'Freelancer'])->persist()->id;
        $group = $this->Groups->findById($groupId)->first();
        $this->Groups->softDelete($group);
        [$userA, $userB] = UserFactory::make(2)->persist();
        $groupData = [
            'groups_users' => [
                ['user_id' => $userA->id, 'is_admin' => true],
                ['user_id' => $userB->id],
            ],
        ];
        $data = $this->generateDummyGroup($groupData);
        $data['name'] = 'Freelancer';
        $options = self::getEntityDefaultOptions();
        $entity = $this->Groups->newEntity($data, $options);
        $save = $this->Groups->save($entity);
        $this->assertEmpty($entity->getErrors(), 'Errors occurred while saving the entity: ' . json_encode($entity->getErrors()));
        $this->assertNotFalse($save, 'The group save operation failed.');
    }

    public function testErrorRuleGroupUnique()
    {
        GroupFactory::make(['name' => 'Freelancer'])->persist();
        $data = $this->generateDummyGroup();
        $data['name'] = 'Freelancer';
        $options = self::getEntityDefaultOptions();
        $entity = $this->Groups->newEntity($data, $options);
        $save = $this->Groups->save($entity);
        $this->assertFalse($save);
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['name']['group_unique']);
    }

    public function testErrorRuleAtLeastOneAdmin()
    {
        $data = $this->generateDummyGroup();
        [$userA, $userB] = UserFactory::make(2)->persist();
        $data['groups_users'] = [
            ['user_id' => $userA->id],
            ['user_id' => $userB->id],
        ];
        $options = self::getEntityDefaultOptions();
        $entity = $this->Groups->newEntity($data, $options);
        $save = $this->Groups->save($entity);
        $this->assertFalse($save);
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['groups_users']['at_least_one_group_manager']);
    }
}
