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

use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

trait GroupsUsersModelTrait
{
    /**
     * Add a dummy group user.
     *
     * @param array $data The group user data
     * @param array $options The entity options
     * @return Group
     */
    public function addGroupUser($data = [], $options = [])
    {
        $groupsUsersTable = TableRegistry::getTableLocator()->get('GroupsUsers');
        $groupUser = self::getDummyGroupUserEntity($data, $options);

        $groupsUsersTable->save($groupUser, ['checkRules' => true]);

        return $groupUser;
    }

    /**
     * Get a new group user entity
     *
     * @param array $data The group user data.
     * @param array $options The new entity options.
     * @return Resouce
     */
    public function getDummyGroupUserEntity($data = [], $options = [])
    {
        $groupsUsersTable = TableRegistry::getTableLocator()->get('GroupsUsers');
        $defaultOptions = [
            'validate' => false,
            'accessibleFields' => [
                'group_id' => true,
                'user_id' => true,
                'is_admin' => true,
            ],
        ];

        $data = self::getDummyGroupUserData($data);
        $options = array_merge($defaultOptions, $options);

        return $groupsUsersTable->newEntity($data, $options);
    }

    /**
     * Get a dummy group user with test data.
     * The comment returned passes a default validation.
     *
     * @param array $data Custom data that will be merged with the default content.
     * @return array Comment data
     */
    public static function getDummyGroupUserData($data = [])
    {
        $entityContent = [
            'group_id' => UuidFactory::uuid('group.id.board'),
            'user_id' => UuidFactory::uuid('user.id.ada'),
            'is_admin' => true,
            'created_by' => UuidFactory::uuid('user.id.admin'),
        ];
        $entityContent = array_merge($entityContent, $data);

        return $entityContent;
    }

    /**
     * Asserts that an object has all the attributes a group should have.
     *
     * @param object $group
     */
    protected function assertGroupUserAttributes($group)
    {
        $attributes = ['id', 'group_id', 'user_id', 'is_admin', 'created'];
        $this->assertObjectHasAttributes($attributes, $group);
    }

    /**
     * Assert a user is member of a group.
     * @param string $groupId The target group
     * @param string $userId The target user
     * @param bool $isAdmin Is the member also admin of the group
     * @return bool
     */
    protected function assertUserIsMemberOf(string $groupId, string $userId, bool $isAdmin = false)
    {
        $groupsUsersTable = TableRegistry::getTableLocator()->get('GroupsUsers');
        $count = $groupsUsersTable->find()->where([
            'user_id' => $userId,
            'group_id' => $groupId,
            'is_admin' => $isAdmin,
        ])->count();
        $this->assertEquals(1, $count);
    }

    /**
     * Assert a user is not member of a group.
     * @param string $groupId The target group
     * @param string $userId The target user
     * @return bool
     */
    protected function assertUserIsNotMemberOf(string $groupId, string $userId)
    {
        $groupsUsersTable = TableRegistry::getTableLocator()->get('GroupsUsers');
        $count = $groupsUsersTable->find()->where([
            'user_id' => $userId,
            'group_id' => $groupId,
        ])->count();
        $this->assertEquals(0, $count);
    }

    /**
     * Assert a user is admin of a group.
     * @param string $groupId
     * @param string $userId
     */
    protected function assertUserIsAdmin($groupId, $userId)
    {
        $groupUser = $this->GroupsUsers->find()->where(['user_id' => $userId, 'group_id' => $groupId])->first();
        $this->assertTrue($groupUser->is_admin);
    }

    /**
     * Assert a user is not admin of group a group.
     * @param string $groupId
     * @param string $userId
     */
    protected function assertUserIsNotAdmin($groupId, $userId)
    {
        $groupUser = $this->GroupsUsers->find()->where(['user_id' => $userId, 'group_id' => $groupId])->first();
        if (!empty($groupUser)) {
            $this->assertFalse($groupUser->is_admin);
        } else {
            $this->assertTrue(true);
        }
    }
}
