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

use App\Model\Entity\Permission;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Test\TestCase\Controller\Share\ShareControllerTest;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;

class ShareNotificationTest extends ShareControllerTest
{
    use EmailQueueTrait;
    use EmailNotificationSettingsTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadNotificationSettings();
    }

    public function tearDown(): void
    {
        $this->unloadNotificationSettings();
        parent::tearDown();
    }

    public function testShareNotificationSuccess(): void
    {
        $uac = UserFactory::make()->admin()->persistedUAC();
        $this->setEmailNotificationSettings([
            'show.description' => true,
            'show.username' => true,
            'show.uri' => true,
            'show.secret' => true,
        ], $uac);

        // Create users.
        [$userA, $userB, $userE, $userMemberOfTheGroupToAddPermissionOn, $userMemberOfTheGroupToAddPermissionOn2, $userOwner] = UserFactory::make(6)->persist();

        // Create groups.
        [$groupB, $groupF] = GroupFactory::make(2)->withGroupsManagersFor([$userOwner])->persist();
        // Create group to add permission to
        $groupToAddPermissionTo = GroupFactory::make()
            ->withGroupsManagersFor([
                $userOwner,
                $userMemberOfTheGroupToAddPermissionOn,
                $userMemberOfTheGroupToAddPermissionOn2,
            ])
            ->persist();

        // Create a resource shared with ada betty freelancer and board (all OWNER).
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$userOwner, $userA, $userB, $groupF, $groupB])
            ->withSecretRevisions()
            ->withSecretsFor([$userOwner, $userA, $userB])
            ->persist();

        // Permissions
        $permissionUserAId = $resource->permissions[1]->id;
        $permissionUserBId = $resource->permissions[2]->id;
        $permissionGroupFId = $resource->permissions[3]->id;
        $permissionGroupBId = $resource->permissions[4]->id;

        // Build the changes.
        $data = ['permissions' => []];

        // User permissions changes.
        // Change the permission of the user Ada to read (no users are expected to be added or removed).
        $data['permissions'][] = ['id' => $permissionUserAId, 'type' => Permission::READ];
        // Delete the permission of the user Betty.
        $data['permissions'][] = ['id' => $permissionUserBId, 'delete' => true];
        // Add an owner permission for the user Edith
        $data['permissions'][] = ['aro' => 'User', 'aro_foreign_key' => $userE->id, 'type' => Permission::OWNER];
        $data['secrets'][] = ['user_id' => $userE->id, 'data' => self::getValidSecret()];

        // Group permissions changes.
        // Change the permission of the group Board (no users are expected to be added or removed).
        $data['permissions'][] = ['id' => $permissionGroupBId, 'type' => Permission::UPDATE];
        // Delete the permission of the group Freelancer.
        $data['permissions'][] = ['id' => $permissionGroupFId, 'delete' => true];
        // Add a read permission for the group Accounting.
        $data['permissions'][] = ['aro' => 'Group', 'aro_foreign_key' => $groupToAddPermissionTo->id, 'type' => Permission::READ];
        $data['secrets'][] = ['user_id' => $userMemberOfTheGroupToAddPermissionOn->id, 'data' => self::getValidSecret()];
        $data['secrets'][] = ['user_id' => $userMemberOfTheGroupToAddPermissionOn2->id, 'data' => self::getValidSecret()];

        $resourceId = $resource->id;
        $this->logInAs($userOwner);
        $this->putJson("/share/resource/$resourceId.json", $data);
        $this->assertSuccess();

        // Check email notification should have 2 emails.
        $this->assertEmailInBatchContains(
            ['shared a password with you',
            'Name: ' . $resource->name,
            'Username: ' . $resource->username,
            $resource->description,
            'URL: ' . $resource->uri,
            'BEGIN PGP MESSAGE'],
            $userE->username
        );
        $this->assertEmailInBatchContains('shared a password with you', $userMemberOfTheGroupToAddPermissionOn->username);
        $this->assertEmailInBatchContains('shared a password with you', $userMemberOfTheGroupToAddPermissionOn2->username);
        $this->assertEmailQueueCount(3);
    }
}
