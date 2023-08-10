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

namespace App\Test\TestCase\Controller\Notifications;

use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Utility\UuidFactory;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;

class GroupsUpdateNotificationTest extends AppIntegrationTestCase
{
    use EmailNotificationSettingsTestTrait;
    use EmailQueueTrait;

    public $fixtures = ['app.Base/Groups', 'app.Base/GroupsUsers', 'app.Base/Resources', 'app.Base/Permissions', 'app.Base/Users',
        'app.Base/Secrets', 'app.Base/Profiles', 'app.Base/Gpgkeys', 'app.Base/Roles', 'app.Base/Favorites',
         ];

    public function testUpdateNotificationAddMemberSuccess(): void
    {
        // Define actors of this tests
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $userCId = UuidFactory::uuid('user.id.carol');

        // Add a user who already has access to all of the resources the group has access.
        // Carol has the same access as the group Freelancer.
        // No secret need to be encrypted for the user.
        $changes[] = ['user_id' => $userCId];

        // Update the group users.
        $this->authenticateAs('jean');
        $this->putJson("/groups/$groupId.json?api-version=v2", ['groups_users' => $changes]);
        $this->assertSuccess();

        $this->assertEmailInBatchContains('added you to the group', 'carol@passbolt.com');
        $this->assertEmailInBatchContains('As member of the group', 'carol@passbolt.com');
        $this->assertEmailInBatchNotContains('And as group manager', 'carol@passbolt.com');
    }

    public function testUpdateNotificationAddGroupManagerSuccess(): void
    {
        // Define actors of this tests
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $userCId = UuidFactory::uuid('user.id.carol');

        // Add a user who already has access to all of the resources the group has access.
        // Carol has the same access as the group Freelancer.
        // No secret need to be encrypted for the user.
        $changes[] = ['user_id' => $userCId, 'is_admin' => true];

        // Update the group users.
        $this->authenticateAs('jean');
        $this->putJson("/groups/$groupId.json?api-version=v2", ['groups_users' => $changes]);
        $this->assertSuccess();

        $this->assertEmailInBatchContains('added you to the group', 'carol@passbolt.com');
        $this->assertEmailInBatchContains('As member of the group', 'carol@passbolt.com');
        $this->assertEmailInBatchContains('And as group manager', 'carol@passbolt.com');
    }

    public function testUpdateNotificationAddUserDisabled(): void
    {
        $this->setEmailNotificationSetting('send.group.user.add', false);

        // Define actors of this tests
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $userCId = UuidFactory::uuid('user.id.carol');

        // Add a user who already has access to all of the resources the group has access.
        // Carol has the same access as the group Freelancer.
        // No secret need to be encrypted for the user.
        $changes[] = ['user_id' => $userCId, 'is_admin' => true];

        // Update the group users.
        $this->authenticateAs('jean');
        $this->putJson("/groups/$groupId.json?api-version=v2", ['groups_users' => $changes]);
        $this->assertSuccess();

        $this->assertEmailWithRecipientIsInNotQueue('carol@passbolt.com');
    }

    public function testUpdateNotificationRemoveMemberSuccess(): void
    {
        // Define actors of this tests
        $groupId = UuidFactory::uuid('group.id.freelancer');

        // Remove Kathleen.
        $changes[] = ['id' => UuidFactory::uuid('group_user.id.freelancer-kathleen'), 'delete' => true];

        // Update the group users.
        $this->authenticateAs('jean');
        $this->putJson("/groups/$groupId.json?api-version=v2", ['groups_users' => $changes, 'secrets' => []]);
        $this->assertSuccess();

        $this->assertEmailInBatchContains('you from the group', 'kathleen@passbolt.com');
        $this->assertEmailInBatchContains('You are no longer a member', 'kathleen@passbolt.com');
    }

    public function testUpdateNotificationRemoveMemberDisabled(): void
    {
        $this->setEmailNotificationSetting('send.group.user.delete', false);

        // Define actors of this tests
        $groupId = UuidFactory::uuid('group.id.freelancer');

        // Remove Kathleen.
        $changes[] = ['id' => UuidFactory::uuid('group_user.id.freelancer-kathleen'), 'delete' => true];

        // Update the group users.
        $this->authenticateAs('jean');
        $this->putJson("/groups/$groupId.json?api-version=v2", ['groups_users' => $changes, 'secrets' => []]);
        $this->assertSuccess();

        $this->assertEmailWithRecipientIsInNotQueue('kathleen@passbolt.com');
    }

    public function testUpdateNotificationUpdateMembershipSuccess(): void
    {
        $this->setEmailNotificationSetting('send.group.user.delete', false);

        // Define actors of this tests
        $groupId = UuidFactory::uuid('group.id.freelancer');

        // Remove Jean as admin
        $changes[] = ['id' => UuidFactory::uuid('group_user.id.freelancer-jean'), 'is_admin' => false];
        // Make Kathleen admin
        $changes[] = ['id' => UuidFactory::uuid('group_user.id.freelancer-nancy'), 'is_admin' => true];

        // Update the group users.
        $this->authenticateAs('jean');
        $this->putJson("/groups/$groupId.json?api-version=v2", ['groups_users' => $changes, 'secrets' => []]);
        $this->assertSuccess();

        $this->assertEmailInBatchContains(
            'You are no longer a group manager of this group.',
            'jean@passbolt.com'
        );

        $this->assertEmailInBatchContains(
            'You are now a group manager of this group.',
            'nancy@passbolt.com'
        );
    }

    public function testUpdateNotificationUpdateMembershipDisabled(): void
    {
        $this->setEmailNotificationSetting('send.group.user.update', false);

        // Define actors of this tests
        $groupId = UuidFactory::uuid('group.id.freelancer');

        // Remove Jean as admin
        $changes[] = ['id' => UuidFactory::uuid('group_user.id.freelancer-jean'), 'is_admin' => false];
        // Make Kathleen admin
        $changes[] = ['id' => UuidFactory::uuid('group_user.id.freelancer-nancy'), 'is_admin' => true];

        // Update the group users.
        $this->authenticateAs('jean');
        $this->putJson("/groups/$groupId.json?api-version=v2", ['groups_users' => $changes, 'secrets' => []]);
        $this->assertSuccess();

        $this->assertEmailWithRecipientIsInNotQueue('jean@passbolt.com');
        $this->assertEmailWithRecipientIsInNotQueue('nancy@passbolt.com');
    }

    public function testUpdateNotificationAdminSummarySuccess(): void
    {
        // Define actors of this tests
        $groupId = UuidFactory::uuid('group.id.human_resource');
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');

        // Add users
        $changes[] = ['user_id' => $userAId, 'is_admin' => true];
        $changes[] = ['user_id' => $userBId, 'is_admin' => false];
        // Update memberships
        $changes[] = ['id' => UuidFactory::uuid('group_user.id.human_resource-wang'), 'is_admin' => true];
        // Remove users
        $changes[] = ['id' => UuidFactory::uuid('group_user.id.human_resource-ursula'), 'delete' => true];

        // Update the group users.
        $this->authenticateAs('ping');
        $this->putJson("/groups/$groupId.json?api-version=v2", ['groups_users' => $changes]);
        $this->assertSuccess();

        $this->assertEmailInBatchContains('Added members', 'thelma@passbolt.com');
        $this->assertEmailInBatchContains('Ada Lovelace', 'thelma@passbolt.com');
        $this->assertEmailInBatchContains('Betty Holberton', 'thelma@passbolt.com');
        $this->assertEmailInBatchContains('Removed members', 'thelma@passbolt.com');
        $this->assertEmailInBatchContains('Ursula Martin', 'thelma@passbolt.com');
        $this->assertEmailInBatchContains('Updated roles', 'thelma@passbolt.com');
        $this->assertEmailInBatchContains('Wang Xiaoyun', 'thelma@passbolt.com');
    }

    public function testUpdateNotificationUpdateAdminSummaryDisabled(): void
    {
        $this->setEmailNotificationSetting('send.group.manager.update', false);

        // Define actors of this tests
        $groupId = UuidFactory::uuid('group.id.human_resource');
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');

        // Add users
        $changes[] = ['user_id' => $userAId, 'is_admin' => true];
        $changes[] = ['user_id' => $userBId, 'is_admin' => false];
        // Update memberships
        $changes[] = ['id' => UuidFactory::uuid('group_user.id.human_resource-wang'), 'is_admin' => true];
        // Remove users
        $changes[] = ['id' => UuidFactory::uuid('group_user.id.human_resource-ursula'), 'delete' => true];

        // Update the group users.
        $this->authenticateAs('ping');
        $this->putJson("/groups/$groupId.json?api-version=v2", ['groups_users' => $changes, 'secrets' => []]);
        $this->assertSuccess();

        $this->assertEmailWithRecipientIsInNotQueue('thelma@passbolt.com');
    }
}
