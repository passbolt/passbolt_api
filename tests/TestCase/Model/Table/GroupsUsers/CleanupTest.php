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

use App\Test\Factory\GroupFactory;
use App\Test\Factory\GroupsUserFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\CleanupTrait;
use App\Utility\UuidFactory;
use Cake\I18n\Time;

class CleanupTest extends AppTestCase
{
    use CleanupTrait;

    public function testCleanupGroupsUsersSoftDeletedUsersSuccess()
    {
        // The group user to cleanup.
        GroupsUserFactory::make()
            ->with('Users', UserFactory::make()->deleted())
            ->persist();

        // Witness groups users to not cleanup.
        $groupUserWithUser = GroupsUserFactory::make()
            ->with('Users')
            ->persist();
        $groupUserWithHardDeletedUser = GroupsUserFactory::make()->persist();

        $this->runCleanupChecks('GroupsUsers', 'cleanupSoftDeletedUsers', 2);

        $groupsUsersIdsPostCleanup = GroupsUserFactory::find()->extract('id')->toArray();
        $this->assertCount(2, $groupsUsersIdsPostCleanup);
        $this->assertContains($groupUserWithUser->id, $groupsUsersIdsPostCleanup);
        $this->assertContains($groupUserWithHardDeletedUser->id, $groupsUsersIdsPostCleanup);
    }

    public function testCleanupGroupsUsersHardDeletedUsersSuccess()
    {
        // The group user to cleanup.
        GroupsUserFactory::make()->persist();

        // Witness groups users to not cleanup.
        $groupUserWithUser = GroupsUserFactory::make()
            ->with('Users')
            ->persist();
        $groupUserWithSoftDeletedUser = GroupsUserFactory::make()
            ->with('Users', UserFactory::make()->deleted())
            ->persist();

        $this->runCleanupChecks('GroupsUsers', 'cleanupHardDeletedUsers', 2);

        $groupsUsersIdsPostCleanup = GroupsUserFactory::find()->extract('id')->toArray();
        $this->assertCount(2, $groupsUsersIdsPostCleanup);
        $this->assertContains($groupUserWithUser->id, $groupsUsersIdsPostCleanup);
        $this->assertContains($groupUserWithSoftDeletedUser->id, $groupsUsersIdsPostCleanup);
    }

    public function testCleanupGroupsUsersSoftDeletedGroupsSuccess()
    {
        // The group user to cleanup.
        GroupsUserFactory::make()
            ->with('Groups', GroupFactory::make()->deleted())
            ->persist();

        // Witness groups users to not cleanup.
        $groupUserWithGroup = GroupsUserFactory::make()
            ->with('Groups')
            ->persist();
        $groupUserWithHardDeletedGroup = GroupsUserFactory::make()->persist();

        $this->runCleanupChecks('GroupsUsers', 'cleanupSoftDeletedGroups', 2);

        $groupsUsersIdsPostCleanup = GroupsUserFactory::find()->extract('id')->toArray();
        $this->assertCount(2, $groupsUsersIdsPostCleanup);
        $this->assertContains($groupUserWithGroup->id, $groupsUsersIdsPostCleanup);
        $this->assertContains($groupUserWithHardDeletedGroup->id, $groupsUsersIdsPostCleanup);
    }

    public function testCleanupGroupsUsersHardDeletedGroupsSuccess()
    {
        // The group user to cleanup.
        GroupsUserFactory::make()->persist();

        // Witness groups users to not cleanup.
        $groupUserWithGroup = GroupsUserFactory::make()
            ->with('Groups')
            ->persist();
        $groupUserWithSoftDeletedGroup = GroupsUserFactory::make()
            ->with('Groups', GroupFactory::make()->deleted())
            ->persist();

        $this->runCleanupChecks('GroupsUsers', 'cleanupHardDeletedGroups', 2);

        $groupsUsersIdsPostCleanup = GroupsUserFactory::find()->extract('id')->toArray();
        $this->assertCount(2, $groupsUsersIdsPostCleanup);
        $this->assertContains($groupUserWithGroup->id, $groupsUsersIdsPostCleanup);
        $this->assertContains($groupUserWithSoftDeletedGroup->id, $groupsUsersIdsPostCleanup);
    }

    public function testCleanupGroupsUsersDuplicatedGroupsUsers()
    {
        // Duplicated groups users to cleanup.
        $duplicateGroupUserMeta = [
            'group_id' => UuidFactory::uuid(),
            'user_id' => UuidFactory::uuid(),
            'created' => Time::now(),
        ];
        GroupsUserFactory::make($duplicateGroupUserMeta)->persist();

        // Duplicate group user to keep as it is the oldest.
        $duplicateGroupUserToKeep = GroupsUserFactory::make($duplicateGroupUserMeta)
            ->patchData(['created' => Time::now()->subDay()])->persist();

        // Witness groups users to not cleanup:
        // - A group user including a group involved in the cleanup
        // - A group user including a user involved in the cleanup
        // - A group having 2 different members.
        // - A user member of 2 different groups.
        $groupUserWithGroupInvolvedInCleanup = GroupsUserFactory::make($duplicateGroupUserMeta)->patchData(['user_id' => UuidFactory::uuid()])->persist();
        $groupUserWithUserInvolvedInCleanup = GroupsUserFactory::make($duplicateGroupUserMeta)->patchData(['group_id' => UuidFactory::uuid()])->persist();
        $userMemberOfMultipleGroups = UserFactory::make()->with('GroupsUsers[2]')->persist();
        $groupHavingMultipleMembers = GroupFactory::make()->with('GroupsUsers[2]')->persist();

        $this->runCleanupChecks('GroupsUsers', 'cleanupDuplicatedGroupsUsers', 7);

        $groupsUsersIdsPostCleanup = GroupsUserFactory::find()->extract('id')->toArray();
        $this->assertCount(7, $groupsUsersIdsPostCleanup);
        $this->assertContains($duplicateGroupUserToKeep->id, $groupsUsersIdsPostCleanup);
        $this->assertContains($groupUserWithGroupInvolvedInCleanup->id, $groupsUsersIdsPostCleanup);
        $this->assertContains($groupUserWithUserInvolvedInCleanup->id, $groupsUsersIdsPostCleanup);
        $this->assertContains($userMemberOfMultipleGroups->groups_users[0]->id, $groupsUsersIdsPostCleanup);
        $this->assertContains($userMemberOfMultipleGroups->groups_users[1]->id, $groupsUsersIdsPostCleanup);
        $this->assertContains($groupHavingMultipleMembers->groups_users[0]->id, $groupsUsersIdsPostCleanup);
        $this->assertContains($groupHavingMultipleMembers->groups_users[1]->id, $groupsUsersIdsPostCleanup);
    }
}
