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

namespace App\Test\TestCase\Controller\Groups;

use App\Model\Entity\Permission;
use App\Notification\Email\Redactor\Group\GroupUpdateAdminSummaryEmailRedactor;
use App\Test\Factory\FavoriteFactory;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\GroupsUserFactory;
use App\Test\Factory\PermissionFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\SecretFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Test\Lib\Model\GroupsModelTrait;
use App\Test\Lib\Model\GroupsUsersModelTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

/**
 * @property \App\Model\Table\ResourcesTable $Resources
 */
class GroupsUpdateControllerTest extends AppIntegrationTestCase
{
    use GroupsModelTrait;
    use GroupsUsersModelTrait;
    use EmailQueueTrait;

    /**
     * @var \App\Model\Table\ResourcesTable|null
     */
    public $Resources = null;

    public function setUp(): void
    {
        parent::setUp();
        $this->Resources = TableRegistry::getTableLocator()->get('Resources');
    }

    protected function getValidSecret(): string
    {
        return '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf9HpfcNeuC5W/VAzEtAe8mTBUk1vcJENtGpMyRkVTC8KbQ
xaEr3+UG6h0ZVzfrMFYrYLolS3fie83cj4FnC3gg1uijo7zTf9QhJMdi7p/ASB6N
y7//8AriVqUAOJ2WCxAVseQx8qt2KqkQvS7F7iNUdHfhEhiHkczTlehyel7PEeas
SdM/kKEsYKk6i4KLPBrbWsflFOkfQGcPL07uRK3laFz8z4LNzvNQOoU7P/C1L0X3
tlK3vuq+r01zRwmflCaFXaHVifj3X74ljhlk5i/JKLoPRvbxlPTevMNag5e6QhPQ
kpj+TJD2frfGlLhyM50hQMdJ7YVypDllOBmnTRwZ0tJFAXm+F987ovAVLMXGJtGO
P+b3c493CfF0fQ1MBYFluVK/Wka8usg/b0pNkRGVWzBcZ1BOONYlOe/JmUyMutL5
hcciUFw5
=TcQF
-----END PGP MESSAGE-----';
    }

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

    /*
     * As a group manager I can update a group (complex scenario).
     *
     * - Update members roles:
     *   - Remove the group manager role of a member
     *   - Add the group manager role to a member
     * - Add multiple members:
     *   - A member who has no previous access to the resources shared with the group
     *   - A member who has already an access to all the resources shared with the group
     *   - A member who has already an access to some resources shared with the group
     * - Delete multiple members :
     *   - A member who has access to the resources shared with the group only because of its membership
     *   - A member who has access to some resources shared with the group because of other permissions
     */

    public function testGroupsUpdateController_Success_AsGMUpdateGroupComplexScenario(): void
    {
        // Define actors of this tests
        [$userJ, $userN, $userK, $userL] = UserFactory::make(4)->persist();
        $group = GroupFactory::make()
            ->withGroupsManagersFor([$userJ])
            ->withGroupsUsersFor([$userN, $userK, $userL])
            ->persist();
        [$userA, $userC, $userF] = UserFactory::make(3)->persist();

        [$resourceC, $resourceF, $resourceG] = ResourceFactory::make(3)->withPermissionsFor([$group, $userC], Permission::UPDATE)
            ->withSecretsFor([$group, $userC])
            ->persist();
        PermissionFactory::make()
            ->acoResource($resourceC)
            ->aroUser($userL)
            ->typeOwner()
            ->persist();

        // Ids
        $groupId = $group->id;
        $groupUserJId = $group->groups_users[0]->id;
        $groupUserNId = $group->groups_users[1]->id;
        $groupUserKId = $group->groups_users[2]->id;
        $groupUserLId = $group->groups_users[3]->id;

        // Retrieve the resources the group has access.
        $resources = $this->Resources->findAllByGroupAccess($groupId)->all()->toArray();
        $groupHasAccess = Hash::extract($resources, '{n}.id');

        // Build the request data.
        $changes = [];

        // Update memberships.
        // Remove userJ as admin
        $changes[] = ['id' => $groupUserJId, 'is_admin' => false];
        // Make userN admin
        $changes[] = ['id' => $groupUserNId, 'is_admin' => true];

        // Remove users from the group
        // Remove userK who has access to the group resources only because of her membership.
        $changes[] = ['id' => $groupUserKId, 'delete' => true];

        // Remove a user who has its own access to the same resource the group has.
        // Remove userL who has direct access to the resourceC.
        $changes[] = ['id' => $groupUserLId, 'delete' => true];

        // Add a user who has not access to the group resources before adding it to the group.
        // Add userF.
        $changes[] = ['user_id' => $userF->id];
        // Add all the new secrets for the user.
        foreach ($groupHasAccess as $resourceId) {
            $secrets[] = ['resource_id' => $resourceId, 'user_id' => $userF->id, 'data' => $this->getValidSecret()];
        }

        // Add a user who already has access to all of the resources the group has access.
        // userC has the same access as the group resourceF.
        // No secret need to be encrypted for the user.
        $changes[] = ['user_id' => $userC->id];

        // Add a user who already has access to some of the resources the group has access.
        // Ada already has access to few resources the group has access : resourceC, resourceF, resourceG
        // Expect the secrets Ada had no access to be encrypted.
        $changes[] = ['user_id' => $userA->id];
        $resourcesAdaAccessedBefore = [$resourceC->id, $resourceF->id, $resourceG->id];
        foreach ($resourcesAdaAccessedBefore as $resourceId) {
            $secrets[] = ['resource_id' => $resourceId, 'user_id' => $userA->id, 'data' => $this->getValidSecret()];
        }

        // Update the group users.
        $this->logInAs($userJ);
        $this->putJson("/groups/$groupId.json", ['groups_users' => $changes, 'secrets' => $secrets]);

        $this->assertSuccess();

        // userJ and userN should still have access to the resources.
        $this->assertUserHasAccessResources($userJ->id, $groupHasAccess);
        $this->assertUserHasAccessResources($userN->id, $groupHasAccess);

        // userK should not have anymore access to the group resources.
        $this->assertUserHasNotAccessResources($userK->id, $groupHasAccess);

        // Lynne should not have anymore access to the group resources (except resourceC).
        $userHasAccess = [$resourceC->id];
        $userHasNotAccess = array_diff($groupHasAccess, $userHasAccess);
        $this->assertUserHasNotAccessResources($userL->id, $userHasNotAccess);
        $this->assertUserHasAccessResources($userL->id, $userHasAccess);

        // Frances should have access to the group resources.
        $this->assertUserHasAccessResources($userF->id, $groupHasAccess);
        // Carol should have access to the group resources.
        $this->assertUserHasAccessResources($userC->id, $groupHasAccess);
        // Ada should have access to the group resources.
        $this->assertUserHasAccessResources($userA->id, $groupHasAccess);

        // Assert entries of database
        foreach ($groupHasAccess as $resourceGroupHasAccessId) {
            /** @var \App\Model\Entity\Secret[] $secrets */
            $secrets = SecretFactory::find()->where(['resource_id' => $resourceGroupHasAccessId])->all()->toArray();
            foreach ($secrets as $secret) {
                if ($secret->user_id === $userF->id) {
                    $this->assertSame($userJ->id, $secret->created_by);
                    $this->assertSame($userJ->id, $secret->modified_by);
                } elseif ($secret->user_id === $userC->id) {
                    // $userC already had permission to resources so it's not updated
                    $this->assertSame($userC->id, $secret->created_by);
                    $this->assertSame($userC->id, $secret->modified_by);
                } elseif ($secret->user_id === $userA->id) {
                    $this->assertSame($userJ->id, $secret->created_by);
                    $this->assertSame($userJ->id, $secret->modified_by);
                }
            }
        }
    }

    /*
     * As an administrator I can update the name of a group
     * Only an administrator is allowed to update the name of a group
     */

    public function testGroupsUpdateController_Error_AsGMCannotUpdateName(): void
    {
        $group = GroupFactory::make(['name' => 'Freelancer'])
            ->with('GroupsUsers', GroupsUserFactory::make()
                ->admin()
                ->with('Users'))
            ->persist();
        $groupId = $group->id;
        $groupManager1 = $group->groups_users[0]->user;

        // Try to update the name.
        $data = [
            'name' => 'Updated group name',
        ];

        // Update the group name.
        $this->logInAs($groupManager1);
        $this->putJson("/groups/$groupId.json", $data);
        $this->assertSuccess();

        // The name of the group should not be updated
        $group = GroupFactory::get($groupId);
        $this->assertNotEquals($data['name'], $group->name);
        $this->assertEquals('Freelancer', $group->name);
    }

    /*
     * As an administrator I can update the name of a group
     * Only an administrator is allowed to update the name of a group
     */

    public function testGroupsUpdateController_Success_AsADUpdateName(): void
    {
        $groupId = GroupFactory::make(['name' => 'Freelancer'])->persist()->id;

        // Try to update name.
        $data = [
            'name' => 'Updated group name',
        ];

        // Update the group name.
        $this->logInAsAdmin();
        $this->putJson("/groups/$groupId.json", $data);
        $this->assertSuccess();

        // The name of the group should be updated
        $group = GroupFactory::get($groupId);
        $this->assertEquals($data['name'], $group->name);
    }

    /*
     * As an administrator I can update the roles of the members of a group
     * @see testAsGMUpdateMembersRoleSuccess
     */

    public function testGroupUpdatedController_Success_AsAAdminUpdateMembersRole(): void
    {
        // Define actors of this tests
        $group = GroupFactory::make()
            ->with('GroupsUsers', GroupsUserFactory::make()
                ->with('Users', UserFactory::make()
                    ->user())
                ->admin())
            ->with('GroupsUsers', GroupsUserFactory::make()
                ->with('Users', UserFactory::make()
                    ->user()))
            ->with(
                'Permissions',
                PermissionFactory::make()
                    ->with('Resources', ResourceFactory::make())
            )
            ->persist();
        $groupId = $group->id;
        $groupManager = $group->groups_users[0];
        $groupMember = $group->groups_users[1];

        // Retrieve the resources the group has access.
        $resources = $this->Resources->findAllByGroupAccess($groupId)->all()->toArray();
        $groupHasAccess = Hash::extract($resources, '{n}.id');

        // Build the request data.
        $changes = [];

        // Update memberships.
        // Remove userJ as admin
        $changes[] = ['id' => $groupManager->id, 'is_admin' => false];
        // Make userN admin
        $changes[] = ['id' => $groupMember->id, 'is_admin' => true];

        // Update the group users.
        $this->logInAsAdmin();
        $this->putJson("/groups/$groupId.json", ['groups_users' => $changes]);
        $this->assertSuccess();

        // userJ and userN should still have access to the resources.
        $this->assertUserHasAccessResources($groupManager->user_id, $groupHasAccess);
        $this->assertUserHasAccessResources($groupMember->user_id, $groupHasAccess);

        // Jean should no longer be a group manager of the group
        $this->assertUserIsNotAdmin($groupId, $groupManager->user_id);

        // Nancy should be a group manager of the group
        $this->assertUserIsAdmin($groupId, $groupMember->user_id);
    }

    /*
     * As a, administrator I can delete members to a group I manage
     *   - A member who has access to the resources shared with the group only because of its membership
     *   - A member who has access to some resources shared with the group because of other permissions
     */

    public function testGroupsUpdateController_Success_AsADDeleteMembers(): void
    {
        // Define actors of this tests
        $group = GroupFactory::make()->with(
            'GroupsUsers',
            GroupsUserFactory::make(2)->with(
                'Users',
                UserFactory::make()
            )
        )->persist();
        $groupId = $group->id;
        $groupUserK = $group->groups_users[0];
        $groupUserL = $group->groups_users[1];

        ResourceFactory::make(4)->withPermissionsFor([$group])->persist();
        $resourceC = ResourceFactory::make()->withPermissionsFor([$groupUserL->user, $group])->withSecretsFor([$groupUserL->user, $group])->persist();

        // Retrieve the resources the group has access.
        $resources = $this->Resources->findAllByGroupAccess($groupId)->all()->toArray();
        $groupHasAccess = Hash::extract($resources, '{n}.id');

        // Build the request data.
        $changes = [];

        // Remove users from the group
        // Remove userK who has access to the group resources only because of her membership.
        $changes[] = ['id' => $groupUserK->id, 'delete' => true];

        // Remove a user who has its own access to the same resource the group has.
        // Remove userL who has direct access to the resourceC.
        $changes[] = ['id' => $groupUserL->id, 'delete' => true];

        // Update the group users.
        $this->logInAsAdmin();
        $this->putJson("/groups/$groupId.json", ['groups_users' => $changes]);
        $this->assertSuccess();

        // userL should not have anymore access to the group resources.
        $this->assertUserHasNotAccessResources($groupUserK->user_id, $groupHasAccess);

        // userL should not have anymore access to the group resources (except chai).
        $userHasAccess = [$resourceC->id];
        $userHasNotAccess = array_diff($groupHasAccess, $userHasAccess);
        $this->assertUserHasNotAccessResources($groupUserL->user_id, $userHasNotAccess);
        $this->assertUserHasAccessResources($groupUserL->user_id, $userHasAccess);
    }

    /*
     * As a administrator I can update a group (complex scenario).
     *
     * - Update group name
     * - Update members roles:
     *   - Remove the group manager role of a member
     *   - Add the group manager role to a member
     */

    public function testGroupsUpdateController_Success_AsADUpdateGroupComplexScenario(): void
    {
        // Define actors of this tests
        [$userJ, $userN] = UserFactory::make(2)->user()->persist();
        $group = GroupFactory::make()
            ->withGroupsManagersFor([$userJ])
            ->withGroupsUsersFor([$userN])
            ->persist();
        ResourceFactory::make(3)
            ->withSecretsFor([$group])->withPermissionsFor([$group])->persist();

        // Ids
        $groupId = $group->id;

        // Retrieve the resources the group has access.
        $resources = $this->Resources->findAllByGroupAccess($groupId)->all()->toArray();
        $groupHasAccess = Hash::extract($resources, '{n}.id');

        // Build the request data.
        $changes = [];

        // Update memberships.
        // Remove userJ as admin
        $changes[] = ['id' => $group->groups_users[0]->id, 'is_admin' => false];
        // Make userN admin
        $changes[] = ['id' => $group->groups_users[1]->id, 'is_admin' => true];

        $data = [
            'name' => 'Updated group name',
            'groups_users' => $changes,
        ];

        // Update the group users.
        $this->logInAsAdmin();
        $this->putJson("/groups/$groupId.json", $data);
        $this->assertSuccess();
        // The name of the group should be updated
        $group = GroupFactory::get($groupId);
        $this->assertEquals($data['name'], $group->name);

        // userJ and userN should still have access to the resources.
        $this->assertUserHasAccessResources($userJ->id, $groupHasAccess);
        $this->assertUserHasAccessResources($userN->id, $groupHasAccess);

        // userJ should no longer be a group manager of the group
        $this->assertUserIsNotAdmin($groupId, $userJ->id);

        // userN should be a group manager of the group
        $this->assertUserIsAdmin($groupId, $userN->id);
    }

    public function testGroupsUpdateController_Error_AsAdminCannotAddGroupUser(): void
    {
        // Define actors of this tests
        $group = GroupFactory::make()->persist();
        $groupId = $group->id;
        $user = UserFactory::make()->user()->persist();
        $userId = $user->id;
        ResourceFactory::make()->withPermissionsFor([$group, $user])->persist();

        // Add a user who already has access to all the resources the group has access.
        // user has the same access as the group.
        // No secret need to be encrypted for the user.
        $changes[] = ['user_id' => $userId];

        // Update the group.
        $this->logInAsAdmin();
        $this->putJson("/groups/$groupId.json", ['groups_users' => $changes]);
        $this->assertSuccess();

        // The user shouldn't be member of the group
        $groupUser = GroupsUserFactory::find()->where(['user_id' => $userId, 'group_id' => $groupId])->first();
        $this->assertEmpty($groupUser);
    }

    public function testGroupsUpdateController_Success_LostAccessFavoritesDeleted(): void
    {
        // Define actors of this tests
        [$userJ, $userN, $userL] = UserFactory::make(3)->user()->persist();
        $userLId = $userL->id;
        $group = GroupFactory::make()->withGroupsManagersFor([$userJ])->withGroupsUsersFor([$userN, $userL])->persist();
        $groupId = $group->id;
        $resourceC = ResourceFactory::make()->withPermissionsFor([$group], Permission::READ)->withSecretsFor([$group])->persist();
        $resourceCId = $resourceC->id;
        FavoriteFactory::make()->setUser($userL)->setResource(ResourceFactory::make()->persist())->persist();
        FavoriteFactory::make()->setUser($userL)->setResource($resourceC)->persist();

        // Build the changes.
        $changes = [];

        // Delete userL from the group
        $changes[] = ['id' => $group->groups_users[2]->id, 'delete' => true];

        // Update the group.
        $this->logInAsAdmin();
        $this->putJson("/groups/$groupId.json", ['groups_users' => $changes]);
        $this->assertSuccess();

        // For userL the favorite for other resource shouldn't be removed.
        $resources = FavoriteFactory::find()
            ->where(['user_id' => $userLId])
            ->all();
        $resourcesId = Hash::extract($resources->toArray(), '{n}.foreign_key');
        $this->assertNotcontains($resourceCId, $resourcesId);
    }

    public function testGroupUpdatedController_Error_AdminCannotDeleteGroupUser(): void
    {
        // Define actors of this test
        $group = GroupFactory::make()->with('GroupsUsers.Users')->persist();
        $groupId = $group->id;
        $groupUserKId = $group->groups_users[0]->id;
        $UserKId = $group->groups_users[0]->user_id;

        // Remove users from the group
        // Remove userK who has access to the group resources only because of her membership.
        $changes[] = ['id' => $groupUserKId, true];

        // Update the group
        $this->logInAsAdmin();
        $this->putJson("/groups/$groupId.json", ['groups_users' => $changes]);
        $this->assertSuccess();

        // The user userK should still be member of the group
        $groupUser = GroupsUserFactory::find()->where(['user_id' => $UserKId, 'group_id' => $groupId])->first();
        $this->assertnotEmpty($groupUser);
    }

    public function testGroupsUpdateController_Error_NotValidGroupId(): void
    {
        $this->logInAsUser();
        $groupId = 'invalid-id';
        $this->putJson("/groups/$groupId.json");
        $this->assertError(400, 'The group id is not valid.');
    }

    public function testGroupsUpdateController_Error_GroupDoesNotExist(): void
    {
        $this->logInAsUser();
        $groupId = UuidFactory::uuid();
        $this->putJson("/groups/$groupId.json");
        $this->assertError(404, 'The group does not exist.');
    }

    public function testGroupsUpdateController_Error_GroupIsSoftDeleted(): void
    {
        $this->logInAsAdmin();
        $groupId = GroupFactory::make()->deleted()->persist()->id;

        $this->putJson("/groups/$groupId.json");
        $this->assertError(404, 'The group does not exist.');
    }

    public function testGroupsUpdateController_Error_AccessDenied(): void
    {
        $groupId = GroupFactory::make()->persist()->id;
        $this->logInAsUser();
        $this->putJson("/groups/$groupId.json");
        $this->assertForbiddenError('You are not authorized to access that location.');
    }

    public function testGroupsUpdateController_Error_NotAuthenticated(): void
    {
        $postData = [];
        $this->putJson('/groups/foo.json', $postData);
        $this->assertAuthenticationError();
    }

    public function testGroupsUpdateController_Error_CsrfToken(): void
    {
        $this->disableCsrfToken();
        $this->logInAsAdmin();
        $this->put('/groups/foo.json');
        $this->assertResponseCode(403);
    }

    public function testGroupsUpdateController_Error_NotJson(): void
    {
        $groupId = UuidFactory::uuid();

        // Update the group name.
        $this->logInAsAdmin();
        $this->put("/groups/$groupId", []);
        $this->assertResponseCode(404);
    }
}
