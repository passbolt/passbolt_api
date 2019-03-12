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

trait GroupsUsersModelTrait
{

    /**
     * Get a dummy group user with test data.
     * The comment returned passes a default validation.
     *
     * @param array $data Custom data that will be merged with the default content.
     * @return array Comment data
     */
    public static function getDummyGroupUser($data = [])
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
     * Assert a user is admin of group a group.
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
