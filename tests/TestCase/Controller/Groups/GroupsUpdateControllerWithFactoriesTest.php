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
 * @since         4.6.0
 */

namespace App\Test\TestCase\Controller\Groups;

use App\Notification\Email\Redactor\Group\GroupUpdateAdminSummaryEmailRedactor;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\GroupsUserFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Test\Lib\Model\GroupsModelTrait;
use App\Test\Lib\Model\GroupsUsersModelTrait;

/**
 * @property \App\Model\Table\ResourcesTable $Resources
 */
class GroupsUpdateControllerWithFactoriesTest extends AppIntegrationTestCase
{
    use GroupsModelTrait;
    use GroupsUsersModelTrait;
    use EmailQueueTrait;

    public function testGroupsUpdateController_Add_A_Group_Manager(): void
    {
        $group = GroupFactory::make()
            ->with('GroupsUsers', GroupsUserFactory::make(2)->admin()->with('Users'))
            ->persist();
        $groupId = $group->id;

        $groupManager1 = $group->groups_users[0]->user;
        $groupManager2 = $group->groups_users[1]->user;

        $newGroupMember = UserFactory::make()->user()->persist();

        // Build the request data.
        $changes = [];

        // Add new member.
        $changes[] = ['user_id' => $newGroupMember->id, 'is_admin' => true];

        // Update the group users.
        $this->logInAs($groupManager1);
        $this->putJson("/groups/$groupId.json", ['groups_users' => $changes]);
        $this->assertSuccess();

        $this->assertUserIsAdmin($groupId, $newGroupMember->id);
        $this->assertEmailQueueCount(2);

        // Assert email summary is sent to the group managers
        $this->assertEmailInBatchContains([
            "{$groupManager1->profile->first_name} updated the group $group->name",
            "{$newGroupMember->profile->full_name}                                                                (Group manager)",
        ], $groupManager2->username);
        $this->assertEmailInBatchNotContains([
            'Updated roles',
            'Removed members',
        ], $groupManager2->username);

        $this->assertEmailInBatchContains([
            "{$groupManager1->profile->first_name} added you to the group $group->name",
        ], $newGroupMember->username);
    }

    public function testGroupsUpdateController_Update_Role(): void
    {
        $group = GroupFactory::make()
            ->with('GroupsUsers', GroupsUserFactory::make(3)->admin()->with('Users'))
            ->with('GroupsUsers[2].Users')
            ->persist();
        $groupId = $group->id;

        $groupManager1 = $group->groups_users[0]->user;
        $groupManager2 = $group->groups_users[1]->user;
        $groupManager3 = $group->groups_users[2]->user;
        $groupMember1 = $group->groups_users[3]->user;
        $groupMember2 = $group->groups_users[4]->user;

        [$newGroupMember, $newGroupManager] = UserFactory::make(2)->user()->persist();

        // Build the request data.
        $changes = [];

        // Update memberships.
        // Remove group admin 2 as admin
        $changes[] = ['id' => $group->groups_users[1]->id, 'is_admin' => false];
        // Make group member 1 as admin
        $changes[] = ['id' => $group->groups_users[3]->id, 'is_admin' => true];
        // Add a user to the group
        $changes[] = ['user_id' => $newGroupMember->id, 'is_admin' => false];
        // Add a group admin to the group
        $changes[] = ['user_id' => $newGroupManager->id, 'is_admin' => true];

        // Update the group users.
        $this->logInAs($groupManager1);
        $this->putJson("/groups/$groupId.json", ['groups_users' => $changes]);

        $this->assertSuccess();

        // Group manager 2 should no longer be a group manager of the group
        $this->assertUserIsNotAdmin($groupId, $groupManager2->id);
        $this->assertUserIsNotAdmin($groupId, $groupMember2->id);
        $this->assertUserIsNotAdmin($groupId, $newGroupMember->id);

        // Group member 1 should be a group manager of the group
        $this->assertUserIsAdmin($groupId, $groupMember1->id);
        $this->assertUserIsAdmin($groupId, $groupManager1->id);
        $this->assertUserIsAdmin($groupId, $newGroupManager->id);

        // Assert emails are sent to the members which roles changed
        $this->assertEmailSubject($groupManager2->username, "{$groupManager1->profile->first_name} updated your membership in the group $group->name");
        $this->assertEmailSubject($newGroupMember->username, "{$groupManager1->profile->first_name} added you to the group $group->name");
        $this->assertEmailSubject($newGroupManager->username, "{$groupManager1->profile->first_name} added you to the group $group->name");

        // Assert email summary is sent to the group managers
        $this->assertEmailInBatchContains([
            "{$groupManager1->profile->first_name} updated the group $group->name",
            "{$newGroupMember->profile->full_name}                                                                (Member)",
            "{$newGroupManager->profile->full_name}                                                                (Group manager)",
        ], $groupManager3->username);
        $this->assertEmailInBatchNotContains('Removed members', $groupManager3->username);

        // The member made manager does not receive a notification as group manager
        $this->assertEmailIsNotInQueue([
            'email' => $groupMember1->username,
            'template' => GroupUpdateAdminSummaryEmailRedactor::TEMPLATE,
        ]);
        // The new admin does not receive a notification as group manager
        $this->assertEmailIsNotInQueue([
            'email' => $newGroupManager->username,
            'template' => GroupUpdateAdminSummaryEmailRedactor::TEMPLATE,
        ]);
        // Assert the group manager removed does not receive an email as group manager
        $this->assertEmailIsNotInQueue([
            'email' => $groupManager2->username,
            'template' => GroupUpdateAdminSummaryEmailRedactor::TEMPLATE,
        ]);
        // Assert that the user performing the action is not notified
        $this->assertEmailIsNotInQueue([
            'email' => $groupManager1->username,
            'template' => GroupUpdateAdminSummaryEmailRedactor::TEMPLATE,
        ]);
        $this->assertEmailQueueCount(5);
    }

    public function testGroupsUpdateController_Remove_A_Member(): void
    {
        $group = GroupFactory::make()
            ->with('GroupsUsers', GroupsUserFactory::make(2)->admin()->with('Users'))
            ->with('GroupsUsers[2].Users')
            ->persist();
        $groupId = $group->id;

        $groupManager1 = $group->groups_users[0]->user;
        $groupManager2 = $group->groups_users[1]->user;
        $groupMember1 = $group->groups_users[2]->user;
        $groupMember2 = $group->groups_users[3]->user;

        // Build the request data.
        $changes = [];

        // Add new member.
        $changes[] = ['id' => $group->groups_users[2]->id, 'delete' => true];

        // Update the group users.
        $this->logInAs($groupManager1);
        $this->putJson("/groups/$groupId.json", ['groups_users' => $changes]);
        $this->assertSuccess();

        $this->assertUserIsNotMemberOf($groupId, $groupMember1->id);
        $this->assertEmailQueueCount(2);

        // Assert email summary is sent to the group managers
        $this->assertEmailInBatchContains([
            'Removed members',
            "{$groupManager1->profile->first_name} updated the group $group->name",
            "{$groupMember1->profile->full_name}                                                                (Member)",
        ], $groupManager2->username);
        $this->assertEmailInBatchNotContains([
            'Added members',
            'Updated roles',
        ], $groupManager2->username);

        $this->assertEmailInBatchContains([
            "{$groupManager1->profile->first_name} removed you from the group $group->name",
        ], $groupMember1->username);
    }
}
