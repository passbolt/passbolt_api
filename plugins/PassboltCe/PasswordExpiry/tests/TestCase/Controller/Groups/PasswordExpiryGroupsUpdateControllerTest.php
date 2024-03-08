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
 * @since         4.5.0
 */

namespace Passbolt\PasswordExpiry\Test\TestCase\Controller\Groups;

use App\Model\Entity\Permission;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use Passbolt\Log\Test\Factory\SecretAccessFactory;
use Passbolt\PasswordExpiry\PasswordExpiryPlugin;
use Passbolt\PasswordExpiry\Test\Factory\PasswordExpirySettingFactory;

class PasswordExpiryGroupsUpdateControllerTest extends AppIntegrationTestCase
{
    use EmailQueueTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(PasswordExpiryPlugin::class);
        PasswordExpirySettingFactory::make()->persist();
    }

    public function testPasswordExpiryGroupsUpdateController_Edit_On_Plugin_Disabled(): void
    {
        $this->disableFeaturePlugin(PasswordExpiryPlugin::class);
        $owner = $this->logInAsUser();
        $group = GroupFactory::make()
            ->withGroupsManagersFor([$owner])
            ->persist();
        $groupId = $group->get('id');

        // Update the group users.
        $this->putJson("/groups/$groupId.json", ['groups_users' => []]);
        $this->assertSuccess();
    }

    /**
     * Given that a user is member of a group
     * And the user has accessed the secret of an associated resource
     * When he is removed from a group
     * Than the resource should be expired
     */
    public function testPasswordExpiryGroupsUpdateController_GroupsUpdateAsGMDeleteMembers_Expire_Accessed_Resources(): void
    {
        $allUsers = [
            $owner1,
            $owner2,
            $viewerActive1,
            $viewerActive2,
            $viewerInactive,
            $userNotInThatGroupWithDirectPermissionOnSameResource,
            $userInAnotherGroupWithAccessOnSameResource,
        ] = UserFactory::make(8)->persist();

        /** @var \App\Model\Entity\Group $group */
        $group = GroupFactory::make()
            ->withGroupsManagersFor([$owner1])
            ->withGroupsUsersFor([$owner2, $viewerActive1, $viewerActive2, $viewerInactive])
            ->persist();
        $anotherGroup = GroupFactory::make()
            ->withGroupsUsersFor([$userInAnotherGroupWithAccessOnSameResource])
            ->persist();

        [$resource1, $resource2, $resource3] = ResourceFactory::make(4)
            ->withPermissionsFor([
                $group,
                $anotherGroup,
                $userNotInThatGroupWithDirectPermissionOnSameResource,
            ])
            ->withSecretsFor($allUsers)
            ->persist();

        SecretAccessFactory::make()
            ->withUsers(UserFactory::make($viewerActive1))
            ->withResources(ResourceFactory::make($resource1))
            ->persist();

        SecretAccessFactory::make()
            ->withUsers(UserFactory::make($viewerActive2))
            ->withResources(ResourceFactory::make($resource2))
            ->persist();

        // Define actors of this tests
        $groupId = $group->id;
        $groupUserToDelete1 = $group->groups_users[2];
        $groupUserToDelete2 = $group->groups_users[3];
        $groupUserToDelete3 = $group->groups_users[4];

        // Remove users from the group
        // Remove $viewerActive1
        $changes[] = ['id' => $groupUserToDelete1->id, 'delete' => true];
        // Remove $viewerActive2
        $changes[] = ['id' => $groupUserToDelete2->id, 'delete' => true];
        // Remove $viewerInactive
        $changes[] = ['id' => $groupUserToDelete3->id, 'delete' => true];

        // Update the group users.
        $this->logInAs($owner1);
        $this->putJson("/groups/$groupId.json", ['groups_users' => $changes]);
        $this->assertSuccess();

        $resource1 = ResourceFactory::get($resource1->id);
        $resource2 = ResourceFactory::get($resource2->id);
        $resource3 = ResourceFactory::get($resource3->id);
        $this->assertTrue($resource1->isExpired());
        $this->assertTrue($resource2->isExpired());
        $this->assertFalse($resource3->isExpired());

        // Owners should be notified
        $emailContent = [
            'Some of your passwords expired',
            'Access for users to your shared passwords have been revoked.',
            'These passwords are now marked as expired.',
            'Please rotate them to ensure continued security.',
        ];
        $this->assertEmailInBatchContains($emailContent, $owner1->username);
        $this->assertEmailInBatchContains($emailContent, $owner2->username);

        $subject = $owner1->profile->first_name . ' removed you from the group ' . $group->name;
        $this->assertEmailSubject($viewerActive1->username, $subject);
        $this->assertEmailSubject($viewerActive2->username, $subject);
        $this->assertEmailSubject($viewerInactive->username, $subject);
        // 2 emails are sent to the two users keeping permission
        // 2 emails are sent to the two users in the group keeping permission
        // 1 email is sent to the user with permission access to the expired resource
        // 1 email is sent to the user with permission through another group
        // 1 email is sent to the user losing permission
        $this->assertEmailQueueCount(7);
    }

    public function testPasswordExpiryGroupsUpdateController_GroupsUpdateAsGMDeleteMembers_AccessMaintainedByUserPermission(): void
    {
        // ViewerActive1 will lose the permission from Group, but keeps permission direct
        // The password is thus not expired
        $allUsers = [$owner1, $owner2, $viewerActive1,] = UserFactory::make(4)->persist();

        /** @var \App\Model\Entity\Group $group */
        $group = GroupFactory::make()
            ->withGroupsManagersFor([$owner1])
            ->withGroupsUsersFor([$owner2, $viewerActive1])
            ->persist();

        // $resource1 will not expire as the viewer has permission through direct permission
        /** @var \App\Model\Entity\Resource $resource1 */
        $resource1 = ResourceFactory::make()
            ->withPermissionsFor([$group], Permission::READ)
            ->withPermissionsFor([$viewerActive1])
            ->withSecretsFor($allUsers)
            ->persist();

        // $resource2 will expire, as $viewerActive1 lost access to it and view it
        // $resource3 will not expire as it was not accessed
        [$resource2, $resource3] = ResourceFactory::make(2)
            ->withPermissionsFor([$group])
            ->withSecretsFor($allUsers)
            ->persist();

        SecretAccessFactory::make()
            ->withUsers(UserFactory::make($viewerActive1))
            ->withResources(ResourceFactory::make($resource1))
            ->persist();

        SecretAccessFactory::make()
            ->withUsers(UserFactory::make($viewerActive1))
            ->withResources(ResourceFactory::make($resource2))
            ->persist();

        // Define actors of this tests
        $groupId = $group->id;
        $groupUserToDelete = $group->groups_users[2];

        // Remove users from the group
        // Remove $viewerActive1
        $changes[] = ['id' => $groupUserToDelete->id, 'delete' => true];

        // Update the group users.
        $this->logInAs($owner1);
        $this->putJson("/groups/$groupId.json", ['groups_users' => $changes]);
        $this->assertSuccess();

        $resource1 = ResourceFactory::get($resource1->id);
        $resource2 = ResourceFactory::get($resource2->id);
        $resource3 = ResourceFactory::get($resource3->id);
        $this->assertFalse($resource1->isExpired());
        $this->assertTrue($resource2->isExpired());
        $this->assertFalse($resource3->isExpired());

        // Owners should be notified
        $emailContent = [
            'Some of your passwords expired',
            'Access for users to your shared passwords have been revoked.',
            'These passwords are now marked as expired.',
            'Please rotate them to ensure continued security.',
        ];
        $this->assertEmailInBatchContains($emailContent, $owner1->username);
        $this->assertEmailInBatchContains($emailContent, $owner2->username);

        $subject = $owner1->profile->first_name . ' removed you from the group ' . $group->name;
        $this->assertEmailSubject($viewerActive1->username, $subject);
        // Two emails are sent to the two users keeping permission
        // One email is sent to user losing permission
        $this->assertEmailQueueCount(3);
    }

    public function testPasswordExpiryGroupsUpdateController_GroupsUpdateAsGMDeleteMembers_AccessMaintainedByGroupPermission(): void
    {
        // ViewerActive1 will lose the permission from group1, but keeps permission and password is not expired
        // as it has resource permission via another group2
        $allUsers = [$owner1, $owner2, $viewerActive1,] = UserFactory::make(4)->persist();

        /** @var \App\Model\Entity\Group $group1 */
        $group1 = GroupFactory::make()
            ->withGroupsManagersFor([$owner1])
            ->withGroupsUsersFor([$owner2, $viewerActive1])
            ->persist();

        /** @var \App\Model\Entity\Group $group2 */
        $group2 = GroupFactory::make()
            ->withGroupsUsersFor([$viewerActive1])
            ->persist();

        // Will not expire as the viewer has still permission through group2
        /** @var \App\Model\Entity\Resource $resource1 */
        $resource1 = ResourceFactory::make()
            ->withPermissionsFor([$group1, $group2])
            ->withSecretsFor($allUsers)
            ->persist();

        // Resource2 will expire as it was accessed, resource3 will not expire as it was not accessed
        [$resource2, $resource3] = ResourceFactory::make(2)
            ->withPermissionsFor([$group1])
            ->withSecretsFor($allUsers)
            ->persist();

        SecretAccessFactory::make()
            ->withUsers(UserFactory::make($viewerActive1))
            ->withResources(ResourceFactory::make($resource1))
            ->persist();

        SecretAccessFactory::make()
            ->withUsers(UserFactory::make($viewerActive1))
            ->withResources(ResourceFactory::make($resource2))
            ->persist();

        // Define actors of this tests
        $groupId = $group1->id;
        $groupUserToDelete = $group1->groups_users[2];

        // Remove users from the group
        // Remove $viewerActive1
        $changes[] = ['id' => $groupUserToDelete->id, 'delete' => true];

        // Update the group users.
        $this->logInAs($owner1);
        $this->putJson("/groups/$groupId.json", ['groups_users' => $changes]);
        $this->assertSuccess();

        $resource1 = ResourceFactory::get($resource1->id);
        $resource2 = ResourceFactory::get($resource2->id);
        $resource3 = ResourceFactory::get($resource3->id);
        $this->assertFalse($resource1->isExpired());
        $this->assertTrue($resource2->isExpired());
        $this->assertFalse($resource3->isExpired());

        // Owners should be notified
        $emailContent = [
            'Some of your passwords expired',
            'Access for users to your shared passwords have been revoked.',
            'These passwords are now marked as expired.',
            'Please rotate them to ensure continued security.',
        ];
        $this->assertEmailInBatchContains($emailContent, $owner1->username);
        $this->assertEmailInBatchContains($emailContent, $owner2->username);

        $subject = $owner1->profile->first_name . ' removed you from the group ' . $group1->name;
        $this->assertEmailSubject($viewerActive1->username, $subject);
        // Two emails are sent to the two users keeping permission
        // One email is sent to user losing permission
        $this->assertEmailQueueCount(3);
    }
}
