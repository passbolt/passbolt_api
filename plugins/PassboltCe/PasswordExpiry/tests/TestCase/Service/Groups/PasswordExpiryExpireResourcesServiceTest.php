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

namespace Passbolt\PasswordExpiry\Test\TestCase\Service\Groups;

use App\Model\Dto\EntitiesChangesDto;
use App\Model\Entity\Secret;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\GroupsUserFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\SecretFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Passbolt\Log\Test\Factory\SecretAccessFactory;
use Passbolt\PasswordExpiry\Service\Resources\PasswordExpiryExpireResourcesService;
use Passbolt\PasswordExpiry\Service\Resources\PasswordExpiryValidationService;
use Passbolt\PasswordExpiry\Service\Settings\PasswordExpiryGetSettingsService;
use Passbolt\PasswordExpiry\Test\Factory\PasswordExpirySettingFactory;

class PasswordExpiryExpireResourcesServiceTest extends AppTestCase
{
    public PasswordExpiryExpireResourcesService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new PasswordExpiryExpireResourcesService(
            new PasswordExpiryValidationService(
                new PasswordExpiryGetSettingsService()
            )
        );
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testPasswordExpiryExpireResourcesOnGroupsUpdateService_Remove_User_From_Group()
    {
        PasswordExpirySettingFactory::make()->persist();

        // Create three users
        [$manager, $user1, $user2,] = UserFactory::make(3)->persist();

        // Create a group with the 3 users, and resources in that group
        /** @var \App\Model\Entity\Group $group */
        $group = GroupFactory::make()->withGroupsManagersFor([$manager])->withGroupsUsersFor([$user1, $user2])->persist();
        [$resource1, $resource2] = ResourceFactory::make(2)
            ->withPermissionsFor([$group])
            ->withSecretsFor([$group])
            ->persist();

        // Create another group with the 2 first users, and resources in that group
        $someOtherGroupWithoutUser2 = GroupFactory::make()->withGroupsManagersFor([$manager])->withGroupsUsersFor([$user1,])->persist();
        [$otherResource1, $otherResource2] = ResourceFactory::make(2)
            ->withPermissionsFor([$someOtherGroupWithoutUser2])
            ->withSecretsFor([$someOtherGroupWithoutUser2])
            ->persist();

        // The user2 will be removed from the group
        // The user2 viewed the resource1, but never the resource2, so only resource1 will be expired
        SecretAccessFactory::make()
            ->withUsers(UserFactory::make($user2))
            ->withResources(ResourceFactory::make($resource1))
            ->persist();

        GroupsUserFactory::make()->getTable()->deleteOrFail($group->groups_users[2]);
        SecretFactory::make()->getTable()->deleteOrFail($resource1->secrets[2]);
        SecretFactory::make()->getTable()->deleteOrFail($resource2->secrets[2]);
        $entitiesChangesDto = new EntitiesChangesDto([], [], [$group->groups_users[2], $resource1->secrets[2], $resource2->secrets[2]]);
        $deletedSecrets = $entitiesChangesDto->getDeletedEntities(Secret::class);
        $result = $this->service->expireResourcesForSecrets($deletedSecrets);
        $this->assertTrue($result);

        // Assert that the resources in the group where user 2 was member are expired
        $resource1 = ResourceFactory::get($resource1->id);
        $this->assertTrue($resource1->isExpired());
        $resource2 = ResourceFactory::get($resource2->id);
        $this->assertFalse($resource2->isExpired());

        // Assert that the resources in the group where user 2 was not member are not expired
        $otherResource1 = ResourceFactory::get($otherResource1->id);
        $this->assertFalse($otherResource1->isExpired());
        $otherResource2 = ResourceFactory::get($otherResource2->id);
        $this->assertFalse($otherResource2->isExpired());
    }
}
