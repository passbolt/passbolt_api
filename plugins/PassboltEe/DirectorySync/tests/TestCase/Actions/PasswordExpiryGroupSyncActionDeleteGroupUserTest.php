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
use App\Test\Factory\GroupsUserFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use Passbolt\DirectorySync\Actions\GroupSyncAction;
use Passbolt\DirectorySync\Test\Factory\DirectoryEntryFactory;
use Passbolt\DirectorySync\Test\Factory\DirectoryRelationFactory;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncIntegrationTestCase;
use Passbolt\Log\Test\Factory\SecretAccessFactory;
use Passbolt\PasswordExpiry\Service\Resources\PasswordExpiryExpireResourcesService;
use Passbolt\PasswordExpiry\Service\Resources\PasswordExpiryValidationService;
use Passbolt\PasswordExpiry\Service\Settings\PasswordExpiryGetSettingsService;
use Passbolt\PasswordExpiry\Test\Factory\PasswordExpirySettingFactory;

class PasswordExpiryGroupSyncActionDeleteGroupUserTest extends DirectorySyncIntegrationTestCase
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

    public function userKeepsPermission(): array
    {
        return [[false], [true],];
    }

    /**
     * @dataProvider userKeepsPermission
     */
    public function testPasswordExpiryGroupSyncActionDeleteGroupUser_Expire_Resource_On_User_Removed_From_Group(bool $userKeepsPermission)
    {
        PasswordExpirySettingFactory::make()->persist();
        /** @var \Passbolt\DirectorySync\Model\Entity\DirectoryEntry $directoryUserEntry */
        $directoryUserEntry = DirectoryEntryFactory::make()
            ->withUser()
            ->persist();
        $user = $directoryUserEntry->user;
        /** @var \Passbolt\DirectorySync\Model\Entity\DirectoryEntry $directoryGroupEntry */
        $directoryGroupEntry = DirectoryEntryFactory::make()
            ->withGroup(GroupFactory::make()->withGroupsUsersFor([$user]))
            ->persist();

        /** @var \App\Model\Entity\Group $group */
        $group = $directoryGroupEntry->get('group');
        $groupUser = $group->groups_users[0];

        DirectoryRelationFactory::make(['id' => $groupUser->id])
            ->setParentKey($directoryGroupEntry->id)
            ->setChildKey($directoryUserEntry->id)
            ->persist();

        $this->mockDirectoryGroupData($group->name, [
            'id' => $directoryGroupEntry->id,
        ]);

        $resourceFactory = ResourceFactory::make()->withPermissionsFor([$group])->withSecretsFor([$group]);
        if ($userKeepsPermission) {
            // Associate the user to another group that maintains the permission of the user to the resource
            $group2 = GroupFactory::make()->withGroupsUsersFor([$user])->persist();
            $resourceFactory->withPermissionsFor([$group2])->withSecretsFor([$group2]);
        }
        /** @var \App\Model\Entity\Resource $resource */
        $resource = $resourceFactory->persist();

        // The user will lose permission on the resources, as the group is deleted
        // The user viewed the resource, that will thus be expired if it did not have a direct permission
        SecretAccessFactory::make()
            ->withUsers(UserFactory::make($user))
            ->withResources(ResourceFactory::make($resource))
            ->persist();

        $this->action->execute();

        $group = GroupFactory::get($group->id);
        $user = UserFactory::get($user->id);
        $resource = ResourceFactory::get($resource->id);
        $this->assertFalse($group->deleted);
        $this->assertFalse($user->deleted);
        $groupUserRelation = GroupsUserFactory::find()->where([
            'user_id' => $user->id,
            'group_id' => $group->id,
        ])->first();
        $this->assertNull($groupUserRelation);
        $this->assertSame($userKeepsPermission, !$resource->isExpired());
    }
}
