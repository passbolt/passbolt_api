<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         4.7.0
 */
namespace Passbolt\DirectorySync\Test\TestCase\Actions;

use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use Passbolt\DirectorySync\Actions\GroupSyncAction;
use Passbolt\DirectorySync\Test\Factory\DirectoryEntryFactory;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncIntegrationTestCase;
use Passbolt\Log\Test\Factory\SecretAccessFactory;
use Passbolt\PasswordExpiry\Service\Resources\PasswordExpiryExpireResourcesService;
use Passbolt\PasswordExpiry\Service\Resources\PasswordExpiryValidationService;
use Passbolt\PasswordExpiry\Service\Settings\PasswordExpiryGetSettingsService;
use Passbolt\PasswordExpiry\Test\Factory\PasswordExpirySettingFactory;

class PasswordExpiryGroupSyncActionDeleteGroupTest extends DirectorySyncIntegrationTestCase
{
    public $fixtures = [];

    public function setUp(): void
    {
        parent::setUp();
        UserFactory::make()->admin()->persist();
        $this->action = new GroupSyncAction(
            new PasswordExpiryExpireResourcesService(
                new PasswordExpiryValidationService(
                    new PasswordExpiryGetSettingsService()
                )
            )
        );
    }

    public function testPasswordExpiryGroupSyncActionDeleteGroup_Delete_Group_Should_Expire_Resource()
    {
        $user = UserFactory::make()->persist();
        PasswordExpirySettingFactory::make()->persist();
        $directoryEntry = DirectoryEntryFactory::make()
            ->withGroup(GroupFactory::make()->withGroupsUsersFor([$user]))
            ->persist();
        /** @var \App\Model\Entity\Group $groupToDelete */
        $groupToDelete = $directoryEntry->get('group');

        // Create a group with the 2 users, and resources in that group
        [$resource1, $resource2] = ResourceFactory::make(2)
            ->withPermissionsFor([$groupToDelete])
            ->withSecretsFor([$groupToDelete])
            ->persist();

        // The user will lose permission on the resources, as the group is deleted
        // The user viewed the resource1, but never the resource2, so only resource1 will be expired
        SecretAccessFactory::make()
            ->withUsers(UserFactory::make($user))
            ->withResources(ResourceFactory::make($resource1))
            ->persist();

        $this->action->execute();

        $group = GroupFactory::get($groupToDelete->id);
        $resourceSharedViewed = ResourceFactory::get($resource1->id);
        $resourceSharedNotViewed = ResourceFactory::get($resource2->id);
        $this->assertTrue($group->deleted);
        $this->assertTrue($resourceSharedViewed->isExpired());
        $this->assertFalse($resourceSharedNotViewed->isExpired());
    }

    public function testPasswordExpiryGroupSyncActionDeleteGroup_Delete_Group_But_Permission_Kept_Should_Not_Expire_Resource()
    {
        $user = UserFactory::make()->persist();
        PasswordExpirySettingFactory::make()->persist();
        $directoryEntry = DirectoryEntryFactory::make()
            ->withGroup(GroupFactory::make()->withGroupsUsersFor([$user]))
            ->persist();
        /** @var \App\Model\Entity\Group $groupToDelete */
        $groupToDelete = $directoryEntry->get('group');

        // Create a group with the 2 users, and resources in that group
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$groupToDelete, $user])
            ->withSecretsFor([$groupToDelete, $user])
            ->persist();

        // The user will keep permission on the resources, as the group is deleted but the direct permission is kept
        // The user viewed the resource1, but never the resource2, so only resource1 will be expired
        SecretAccessFactory::make()
            ->withUsers(UserFactory::make($user))
            ->withResources(ResourceFactory::make($resource))
            ->persist();

        $this->action->execute();

        $group = GroupFactory::get($groupToDelete->id);
        $resourceSharedViewed = ResourceFactory::get($resource->id);
        $this->assertTrue($group->deleted);
        $this->assertFalse($resourceSharedViewed->isExpired());
    }
}
