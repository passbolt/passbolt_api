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

namespace App\Test\TestCase\Controller\Notifications;

use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;

class GroupsUpdateNotificationTest extends AppIntegrationTestCase
{
    use EmailNotificationSettingsTestTrait;

    public $fixtures = ['app.Base/Groups', 'app.Base/GroupsUsers', 'app.Base/Resources', 'app.Base/Permissions', 'app.Base/Users',
        'app.Base/Secrets', 'app.Base/Profiles', 'app.Base/Gpgkeys', 'app.Base/Roles', 'app.Base/Favorites', 'app.Base/EmailQueue',
        'app.Base/Avatars'];

    public function testUpdateNotificationAddMemberSuccess()
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
        $this->putJson("/groups/$groupId.json", ['groups_users' => $changes]);
        $this->assertSuccess();

        $this->get('/seleniumtests/showLastEmail/carol@passbolt.com');
        $this->assertResponseCode(200);
        $this->assertResponseContains('added you to the group');
        $this->assertResponseContains('As member of the group');
        $this->assertResponseNotContains('And as group manager');
    }

    public function testUpdateNotificationAddGroupManagerSuccess()
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
        $this->putJson("/groups/$groupId.json", ['groups_users' => $changes]);
        $this->assertSuccess();

        $this->get('/seleniumtests/showLastEmail/carol@passbolt.com');
        $this->assertResponseCode(200);
        $this->assertResponseContains('added you to the group');
        $this->assertResponseContains('As member of the group');
        $this->assertResponseContains('And as group manager');
    }

    public function testUpdateNotificationAddUserDisabled()
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
        $this->putJson("/groups/$groupId.json", ['groups_users' => $changes]);
        $this->assertSuccess();

        $this->get('/seleniumtests/showLastEmail/carol@passbolt.com');
        $this->assertResponseCode(500);
        $this->assertResponseContains('No email was sent to this user.');
    }

    public function testUpdateNotificationRemoveMemberSuccess()
    {
        // Define actors of this tests
        $groupId = UuidFactory::uuid('group.id.freelancer');

        // Remove Kathleen.
        $changes[] = ['id' => UuidFactory::uuid("group_user.id.freelancer-kathleen"), 'delete' => true];

        // Update the group users.
        $this->authenticateAs('jean');
        $this->putJson("/groups/$groupId.json", ['groups_users' => $changes, 'secrets' => []]);
        $this->assertSuccess();

        $this->get('/seleniumtests/showLastEmail/kathleen@passbolt.com');
        $this->assertResponseCode(200);
        $this->assertResponseContains('you from the group');
        $this->assertResponseContains('You are no longer a member');
    }

    public function testUpdateNotificationRemoveMemberDisabled()
    {
        $this->setEmailNotificationSetting('send.group.user.delete', false);

        // Define actors of this tests
        $groupId = UuidFactory::uuid('group.id.freelancer');

        // Remove Kathleen.
        $changes[] = ['id' => UuidFactory::uuid("group_user.id.freelancer-kathleen"), 'delete' => true];

        // Update the group users.
        $this->authenticateAs('jean');
        $this->putJson("/groups/$groupId.json", ['groups_users' => $changes, 'secrets' => []]);
        $this->assertSuccess();

        $this->get('/seleniumtests/showLastEmail/kathleen@passbolt.com');
        $this->assertResponseCode(500);
        $this->assertResponseContains('No email was sent to this user.');
    }

    public function testUpdateNotificationUpdateMembershipSuccess()
    {
        $this->setEmailNotificationSetting('send.group.user.delete', false);

        // Define actors of this tests
        $groupId = UuidFactory::uuid('group.id.freelancer');

        // Remove Jean as admin
        $changes[] = ['id' => UuidFactory::uuid("group_user.id.freelancer-jean"), 'is_admin' => false];
        // Make Kathleen admin
        $changes[] = ['id' => UuidFactory::uuid("group_user.id.freelancer-nancy"), 'is_admin' => true];

        // Update the group users.
        $this->authenticateAs('jean');
        $this->putJson("/groups/$groupId.json", ['groups_users' => $changes, 'secrets' => []]);
        $this->assertSuccess();

        $this->get('/seleniumtests/showLastEmail/jean@passbolt.com');
        $this->assertResponseCode(200);
        $this->assertResponseContains('You are no longer a group manager of this group.');

        $this->get('/seleniumtests/showLastEmail/nancy@passbolt.com');
        $this->assertResponseCode(200);
        $this->assertResponseContains('You are now a group manager of this group.');
    }

    public function testUpdateNotificationUpdateMembershipDisabled()
    {
        $this->setEmailNotificationSetting('send.group.user.update', false);

        // Define actors of this tests
        $groupId = UuidFactory::uuid('group.id.freelancer');

        // Remove Jean as admin
        $changes[] = ['id' => UuidFactory::uuid("group_user.id.freelancer-jean"), 'is_admin' => false];
        // Make Kathleen admin
        $changes[] = ['id' => UuidFactory::uuid("group_user.id.freelancer-nancy"), 'is_admin' => true];

        // Update the group users.
        $this->authenticateAs('jean');
        $this->putJson("/groups/$groupId.json", ['groups_users' => $changes, 'secrets' => []]);
        $this->assertSuccess();

        $this->get('/seleniumtests/showLastEmail/jean@passbolt.com');
        $this->assertResponseCode(500);
        $this->assertResponseContains('No email was sent to this user.');

        $this->get('/seleniumtests/showLastEmail/nancy@passbolt.com');
        $this->assertResponseCode(500);
        $this->assertResponseContains('No email was sent to this user.');
    }

    public function testUpdateNotificationAdminSummarySuccess()
    {
        // Define actors of this tests
        $groupId = UuidFactory::uuid('group.id.human_resource');
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');

        // Add users
        $changes[] = ['user_id' => $userAId, 'is_admin' => true];
        $changes[] = ['user_id' => $userBId, 'is_admin' => false];
        // Update memberships
        $changes[] = ['id' => UuidFactory::uuid("group_user.id.human_resource-wang"), 'is_admin' => true];
        // Remove users
        $changes[] = ['id' => UuidFactory::uuid("group_user.id.human_resource-ursula"), 'delete' => true];

        // Update the group users.
        $this->authenticateAs('ping');
        $this->putJson("/groups/$groupId.json", ['groups_users' => $changes]);
        $this->assertSuccess();

        $this->get('/seleniumtests/showLastEmail/thelma@passbolt.com');
        $this->assertResponseCode(200);
        $this->assertResponseContains('Added members');
        $this->assertResponseContains('Ada Lovelace');
        $this->assertResponseContains('Betty Holberton');
        $this->assertResponseContains('Removed members');
        $this->assertResponseContains('Ursula Martin');
        $this->assertResponseContains('Updated roles');
        $this->assertResponseContains('Wang Xiaoyun');
    }

    public function testUpdateNotificationUpdateAdminSummaryDisabled()
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
        $changes[] = ['id' => UuidFactory::uuid("group_user.id.human_resource-wang"), 'is_admin' => true];
        // Remove users
        $changes[] = ['id' => UuidFactory::uuid("group_user.id.human_resource-ursula"), 'delete' => true];

        // Update the group users.
        $this->authenticateAs('ping');
        $this->putJson("/groups/$groupId.json", ['groups_users' => $changes, 'secrets' => []]);
        $this->assertSuccess();

        $this->get('/seleniumtests/showLastEmail/thelma@passbolt.com');
        $this->assertResponseCode(500);
        $this->assertResponseContains('No email was sent to this user.');
    }
}
