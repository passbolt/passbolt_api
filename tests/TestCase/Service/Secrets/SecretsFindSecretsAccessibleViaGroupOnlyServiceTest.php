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

namespace App\Test\TestCase\Service\Secrets;

use App\Model\Table\PermissionsTable;
use App\Service\Secrets\SecretsFindSecretsAccessibleViaGroupOnlyService;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Cake\TestSuite\TestCase;
use Cake\Utility\Hash;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use TypeError;

class SecretsFindSecretsAccessibleViaGroupOnlyServiceTest extends TestCase
{
    use TruncateDirtyTables;

    public SecretsFindSecretsAccessibleViaGroupOnlyService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new SecretsFindSecretsAccessibleViaGroupOnlyService();
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    /*
     * Assert function parameters
     */

    public function testSecretsFindSecretsAccessibleViaGroupOnlyService_Find_AssertGroupIdParameter()
    {
        try {
            $this->service->find('not-a-valid-uuid', [], PermissionsTable::RESOURCE_ACO);
            $this->assertFalse('Parameter groupId should throw a TypeError exception');
        } catch (TypeError $error) {
            $this->assertFalse(false);
        }
    }

    public function testSecretsFindSecretsAccessibleViaGroupOnlyService_Find_AssertUsersIdsParameter()
    {
        try {
            $this->service->find(UuidFactory::uuid(), ['not-a-valid-uuid'], PermissionsTable::RESOURCE_ACO);
            $this->assertFalse('Parameter usersIds should throw a TypeError exception');
        } catch (TypeError $error) {
            $this->assertFalse(false);
        }
    }

    /*
     * Assert scenario where users have access to resources via group only.
     */

    /*
     * Even if group has permissions, find should return empty result if no users given.
     */

    public function testSecretsFindSecretsAccessibleViaGroupOnlyService_Find_NoResult_NoUsersGiven()
    {
        $owner1 = UserFactory::make()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$owner1])->persist();
        ResourceFactory::make()
            ->withPermissionsFor([$group])
            ->withSecretsFor([$group])
            ->persist();

        $result = $this->service->find($group->id, [], PermissionsTable::RESOURCE_ACO)
            ->all()->toArray();
        $this->assertEmpty($result);
    }

    /*
     * Even if group has permission, it shouldn't return nothing for users not in group having no permission.
     */

    public function testSecretsFindSecretsAccessibleViaGroupOnlyService_Find_NoResult_UserNotInGroupWithNoPermission()
    {
        [$owner1, $owner2] = UserFactory::make(2)->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$owner1])->persist();
        ResourceFactory::make()
            ->withPermissionsFor([$group])
            ->withSecretsFor([$group])
            ->persist();

        $result = $this->service->find($group->id, [$owner2->id], PermissionsTable::RESOURCE_ACO)
            ->all()->toArray();
        $this->assertEmpty($result);
    }

    /*
     * Even if group has permission, it shouldn't return the secrets of another user not member of the group
     * having direct permissions for the same resources as the group.
     */

    public function testSecretsFindSecretsAccessibleViaGroupOnlyService_Find_NoResult_UserNotInGroupWithDirectPermission()
    {
        [$owner1, $owner2] = UserFactory::make(2)->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$owner1])->persist();
        ResourceFactory::make()
            ->withPermissionsFor([$group, $owner2])
            ->withSecretsFor([$group, $owner2])
            ->persist();

        $result = $this->service->find($group->id, [$owner2->id], PermissionsTable::RESOURCE_ACO)
            ->all()->toArray();
        $this->assertEmpty($result);
    }

    /*
     * Even if group has permission, it shouldn't return the secrets of another user not member of the group
     * having inherited permissions from another group for the same resources as the group.
     */

    public function testSecretsFindSecretsAccessibleViaGroupOnlyService_Find_NoResult_UserNotInGroupWithOtherGroupPermission()
    {
        [$owner1, $owner2] = UserFactory::make(2)->persist();
        $group1 = GroupFactory::make()->withGroupsManagersFor([$owner1])->persist();
        $group2 = GroupFactory::make()->withGroupsManagersFor([$owner2])->persist();
        ResourceFactory::make()
            ->withPermissionsFor([$group1, $group2])
            ->withSecretsFor([$group1, $group2])
            ->persist();

        $result = $this->service->find($group1->id, [$owner2->id], PermissionsTable::RESOURCE_ACO)
            ->all()->toArray();
        $this->assertEmpty($result);
    }

    /*
     * Even if group has permission, it shouldn't return the secrets of another user not member of the group
     * having direct & inherited permissions from another group for the same resources as the group.
     */

    public function testSecretsFindSecretsAccessibleViaGroupOnlyService_Find_NoResult_UserNotInGroupWithOtherDirectAndGroupPermission()
    {
        [$owner1, $owner2] = UserFactory::make(2)->persist();
        $group1 = GroupFactory::make()->withGroupsManagersFor([$owner1])->persist();
        $group2 = GroupFactory::make()->withGroupsManagersFor([$owner2])->persist();
        ResourceFactory::make()
            ->withPermissionsFor([$group1, $group2, $owner2])
            ->withSecretsFor([$group1, $group2, $owner2])
            ->persist();

        $result = $this->service->find($group1->id, [$owner2->id], PermissionsTable::RESOURCE_ACO)
            ->all()->toArray();
        $this->assertEmpty($result);
    }

    /*
     * Even if group has permission, it shouldn't return the secrets of a user member of the group having also
     * direct permissions.
     */

    public function testSecretsFindSecretsAccessibleViaGroupOnlyService_Find_NoResult_UserInGroupWithDirectPermission()
    {
        [$owner1] = UserFactory::make(2)->persist();
        $group1 = GroupFactory::make()->withGroupsManagersFor([$owner1])->persist();
        ResourceFactory::make()
            ->withPermissionsFor([$group1, $owner1])
            ->withSecretsFor([$group1, $owner1])
            ->persist();

        $result = $this->service->find($group1->id, [$owner1->id], PermissionsTable::RESOURCE_ACO)
            ->all()->toArray();
        $this->assertEmpty($result);
    }

    /*
     * Even if group has permission, it shouldn't return the secrets of a user member of the group having also
     * inherited permissions from another group.
     */

    public function testSecretsFindSecretsAccessibleViaGroupOnlyService_Find_NoResult_UserInGroupWithOtherGroupPermission()
    {
        [$owner1] = UserFactory::make(2)->persist();
        $group1 = GroupFactory::make()->withGroupsManagersFor([$owner1])->persist();
        $group2 = GroupFactory::make()->withGroupsManagersFor([$owner1])->persist();
        ResourceFactory::make()
            ->withPermissionsFor([$group1, $group2])
            ->withSecretsFor([$group1, $group2])
            ->persist();

        $result = $this->service->find($group1->id, [$owner1->id], PermissionsTable::RESOURCE_ACO)
            ->all()->toArray();
        $this->assertEmpty($result);
    }

    /*
     * Even if group has permission, it shouldn't return the secrets of a user member of the group having also
     * inherited permissions from another group and direct permission.
     */

    public function testSecretsFindSecretsAccessibleViaGroupOnlyService_Find_NoResult_UserInGroupWithDirectAndOtherGroupPermission()
    {
        [$owner1] = UserFactory::make(2)->persist();
        $group1 = GroupFactory::make()->withGroupsManagersFor([$owner1])->persist();
        $group2 = GroupFactory::make()->withGroupsManagersFor([$owner1])->persist();
        ResourceFactory::make()
            ->withPermissionsFor([$group1, $group2, $owner1])
            ->withSecretsFor([$group1, $group2, $owner1])
            ->persist();

        $result = $this->service->find($group1->id, [$owner1->id], PermissionsTable::RESOURCE_ACO)
            ->all()->toArray();
        $this->assertEmpty($result);
    }

    /*
     * Assert scenario where users have access to resources via group only.
     */

    /*
     * It should return secret for a user having permissions only via group.
     */

    public function testSecretsFindSecretsAccessibleViaGroupOnlyService_Find_SingleResult_UserInGroupWithGroupPermission()
    {
        $owner1 = UserFactory::make()->persist();
        $group1 = GroupFactory::make()->withGroupsManagersFor([$owner1])->persist();
        $resource1 = ResourceFactory::make()
            ->withPermissionsFor([$group1])
            ->withSecretsFor([$group1])
            ->persist();

        $result = $this->service->find($group1->id, [$owner1->id], PermissionsTable::RESOURCE_ACO)
            ->all()->toArray();
        $this->assertCount(1, $result);
        $this->assertEquals($resource1->secrets[0]->id, $result[0]->id);
    }

    /*
     * It should return secret for a user having permissions only via group containing other users.
     */

    public function testSecretsFindSecretsAccessibleViaGroupOnlyService_Find_SingleResult_UsersInGroupWithGroupPermission()
    {
        [$owner1, $owner2] = UserFactory::make(2)->persist();
        $group1 = GroupFactory::make()->withGroupsManagersFor([$owner1, $owner2])->persist();
        $resource1 = ResourceFactory::make()
            ->withPermissionsFor([$group1])
            ->withSecretsFor([$group1])
            ->persist();

        $result = $this->service->find($group1->id, [$owner1->id], PermissionsTable::RESOURCE_ACO)
            ->all()->toArray();
        $this->assertCount(1, $result);
        $this->assertEquals($resource1->secrets[0]->id, $result[0]->id);
    }

    /*
     * It should return secret for users having permissions only via group.
     */

    public function testSecretsFindSecretsAccessibleViaGroupOnlyService_Find_MultipleResult_UsersInGroupWithGroupPermission()
    {
        [$owner1, $owner2] = UserFactory::make(2)->persist();
        $group1 = GroupFactory::make()->withGroupsManagersFor([$owner1, $owner2])->persist();
        $resource1 = ResourceFactory::make()
            ->withPermissionsFor([$group1])
            ->withSecretsFor([$group1])
            ->persist();

        $result = $this->service->find($group1->id, [$owner1->id, $owner2->id], PermissionsTable::RESOURCE_ACO)
            ->all()->toArray();
        $this->assertCount(2, $result);
        $resultSecretsIds = Hash::extract($result, '{n}.id');
        $this->assertContains($resource1->secrets[0]->id, $resultSecretsIds);
        $this->assertContains($resource1->secrets[1]->id, $resultSecretsIds);
    }

    /*
     * It should return secrets for a user having permissions only via group on multiple resources.
     */

    public function testSecretsFindSecretsAccessibleViaGroupOnlyService_Find_MultipleResults_UserInGroupWithMultiplePermissions()
    {
        $owner1 = UserFactory::make()->persist();
        $group1 = GroupFactory::make()->withGroupsManagersFor([$owner1])->persist();
        [$resource1, $resource2] = ResourceFactory::make(2)
            ->withPermissionsFor([$group1])
            ->withSecretsFor([$group1])
            ->persist();

        $result = $this->service->find($group1->id, [$owner1->id], PermissionsTable::RESOURCE_ACO)
            ->all()->toArray();
        $this->assertCount(2, $result);
        $resultSecretsIds = Hash::extract($result, '{n}.id');
        $this->assertContains($resource1->secrets[0]->id, $resultSecretsIds);
        $this->assertContains($resource2->secrets[0]->id, $resultSecretsIds);
    }

    /*
     * It should return secrets for users having permissions only via group on multiple resources.
     */

    public function testSecretsFindSecretsAccessibleViaGroupOnlyService_Find_MultipleResults_UsersInGroupWithMultiplePermissions()
    {
        [$owner1, $owner2] = UserFactory::make(2)->persist();
        $group1 = GroupFactory::make()->withGroupsManagersFor([$owner1, $owner2])->persist();
        [$resource1, $resource2] = ResourceFactory::make(2)
            ->withPermissionsFor([$group1])
            ->withSecretsFor([$group1])
            ->persist();

        $result = $this->service->find($group1->id, [$owner1->id, $owner2->id], PermissionsTable::RESOURCE_ACO)
            ->all()->toArray();
        $this->assertCount(4, $result);
        $resultSecretsIds = Hash::extract($result, '{n}.id');
        $this->assertContains($resource1->secrets[0]->id, $resultSecretsIds);
        $this->assertContains($resource2->secrets[0]->id, $resultSecretsIds);
        $this->assertContains($resource1->secrets[1]->id, $resultSecretsIds);
        $this->assertContains($resource2->secrets[1]->id, $resultSecretsIds);
    }

    /*
     * Assert multiple real life scenarios
     * 1. A user (3) not member of the group having no permission
     * 2. A user (4) not member of the group having direct access
     * 3. A user (5) not member of the group having inherited permission via another group (2)
     * 4. A user (6) member of the group having direct access
     * 5. A user (7) member of the group having inherited permission via another group (3)
     * 6. A user (8) member of the group having direct and inherited permissions via another group (4)
     * 7. A user (9) member of the group having access to resource group doesn't have
     * 9. A user (1) member of the group having access only via the group
     * 10. A user (2) member of the group having access only via the group and also to other resource via direct and or other group
     * 11. A user (10) member of the group but not included in the find parameters
     */
    public function testSecretsFindSecretsAccessibleViaGroupOnlyService_Find_MultipleScenarios()
    {
        [$user1, $user2, $user3, $user4, $user5, $user6, $user7, $user8, $user9, $user10] =
            UserFactory::make(10)->persist();
        $group1 = GroupFactory::make()
            ->withGroupsManagersFor([$user1, $user2, $user6, $user7, $user8, $user9, $user10])->persist();

        // 9.
        $resource1 = ResourceFactory::make()
            ->withPermissionsFor([$group1])
            ->withSecretsFor([$group1])
            ->persist();
        // 10.
        $resource2 = ResourceFactory::make()
            ->withPermissionsFor([$group1, $user2])
            ->withSecretsFor([$group1, $user2])
            ->persist();
        // 2.
        $resource3 = ResourceFactory::make()
            ->withPermissionsFor([$group1, $user4])
            ->withSecretsFor([$group1, $user4])
            ->persist();
        // 3.
        $group2 = GroupFactory::make()->withGroupsManagersFor([$user5])->persist();
        $resource4 = ResourceFactory::make()
            ->withPermissionsFor([$group1, $group2])
            ->withSecretsFor([$group1, $group2])
            ->persist();
        // 4.
        $resource5 = ResourceFactory::make()
            ->withPermissionsFor([$group1, $user6])
            ->withSecretsFor([$group1, $user6])
            ->persist();
        // 5.
        $group3 = GroupFactory::make()->withGroupsManagersFor([$user7])->persist();
        $resource6 = ResourceFactory::make()
            ->withPermissionsFor([$group1, $group3])
            ->withSecretsFor([$group1, $group3])
            ->persist();
        // 6.
        $group4 = GroupFactory::make()->withGroupsManagersFor([$user8])->persist();
        $resource7 = ResourceFactory::make()
            ->withPermissionsFor([$group1, $group4, $user8])
            ->withSecretsFor([$group1, $group4])
            ->persist();
        // 7.
        $resource8 = ResourceFactory::make()
            ->withPermissionsFor([$user9])
            ->withSecretsFor([$user9])
            ->persist();

        $result = $this->service->find($group1->id, [
            $user1->id, $user2->id, $user3->id, $user4->id, $user5->id, $user6->id, $user7->id, $user8->id, $user9->id,

        ], PermissionsTable::RESOURCE_ACO)
            ->select(['resource_id', 'user_id'])->disableHydration()->all()->toArray();

        // 9.
        $this->assertContains(['resource_id' => $resource1->id, 'user_id' => $user1->id], $result);
        $this->assertContains(['resource_id' => $resource1->id, 'user_id' => $user2->id], $result);
        $this->assertContains(['resource_id' => $resource1->id, 'user_id' => $user6->id], $result);
        $this->assertContains(['resource_id' => $resource1->id, 'user_id' => $user7->id], $result);
        $this->assertContains(['resource_id' => $resource1->id, 'user_id' => $user8->id], $result);
        $this->assertContains(['resource_id' => $resource1->id, 'user_id' => $user9->id], $result);
        // 10.
        $this->assertContains(['resource_id' => $resource2->id, 'user_id' => $user1->id], $result);
        $this->assertContains(['resource_id' => $resource2->id, 'user_id' => $user6->id], $result);
        $this->assertContains(['resource_id' => $resource2->id, 'user_id' => $user7->id], $result);
        $this->assertContains(['resource_id' => $resource2->id, 'user_id' => $user8->id], $result);
        $this->assertContains(['resource_id' => $resource2->id, 'user_id' => $user9->id], $result);
        // 1.
        $this->assertNotContains(['user_id' => $user3->id], $result);
        // 2.
        $this->assertNotContains(['user_id' => $user4->id], $result);
        $this->assertContains(['resource_id' => $resource3->id, 'user_id' => $user1->id], $result);
        $this->assertContains(['resource_id' => $resource3->id, 'user_id' => $user2->id], $result);
        $this->assertContains(['resource_id' => $resource3->id, 'user_id' => $user6->id], $result);
        $this->assertContains(['resource_id' => $resource3->id, 'user_id' => $user7->id], $result);
        $this->assertContains(['resource_id' => $resource3->id, 'user_id' => $user8->id], $result);
        $this->assertContains(['resource_id' => $resource3->id, 'user_id' => $user9->id], $result);
        // 3.
        $this->assertNotContains(['user_id' => $user5->id], $result);
        $this->assertContains(['resource_id' => $resource4->id, 'user_id' => $user1->id], $result);
        $this->assertContains(['resource_id' => $resource4->id, 'user_id' => $user2->id], $result);
        $this->assertContains(['resource_id' => $resource4->id, 'user_id' => $user6->id], $result);
        $this->assertContains(['resource_id' => $resource4->id, 'user_id' => $user7->id], $result);
        $this->assertContains(['resource_id' => $resource4->id, 'user_id' => $user8->id], $result);
        $this->assertContains(['resource_id' => $resource4->id, 'user_id' => $user9->id], $result);
        // 4.
        $this->assertNotContains(['resource_id' => $resource5->id, 'user_id' => $user6->id], $result);
        $this->assertContains(['resource_id' => $resource5->id, 'user_id' => $user1->id], $result);
        $this->assertContains(['resource_id' => $resource5->id, 'user_id' => $user2->id], $result);
        $this->assertContains(['resource_id' => $resource5->id, 'user_id' => $user7->id], $result);
        $this->assertContains(['resource_id' => $resource5->id, 'user_id' => $user8->id], $result);
        $this->assertContains(['resource_id' => $resource5->id, 'user_id' => $user9->id], $result);
        // 5.
        $this->assertNotContains(['resource_id' => $resource6->id, 'user_id' => $user7->id], $result);
        $this->assertContains(['resource_id' => $resource6->id, 'user_id' => $user1->id], $result);
        $this->assertContains(['resource_id' => $resource6->id, 'user_id' => $user2->id], $result);
        $this->assertContains(['resource_id' => $resource6->id, 'user_id' => $user6->id], $result);
        $this->assertContains(['resource_id' => $resource6->id, 'user_id' => $user8->id], $result);
        $this->assertContains(['resource_id' => $resource6->id, 'user_id' => $user9->id], $result);
        // 6.
        $this->assertNotContains(['resource_id' => $resource7->id, 'user_id' => $user8->id], $result);
        $this->assertContains(['resource_id' => $resource7->id, 'user_id' => $user1->id], $result);
        $this->assertContains(['resource_id' => $resource7->id, 'user_id' => $user2->id], $result);
        $this->assertContains(['resource_id' => $resource7->id, 'user_id' => $user6->id], $result);
        $this->assertContains(['resource_id' => $resource7->id, 'user_id' => $user7->id], $result);
        $this->assertContains(['resource_id' => $resource7->id, 'user_id' => $user9->id], $result);
        // 7.
        $this->assertNotContains(['user_id' => $user9->id], $result);
        $this->assertNotContains(['resource_id' => $resource8->id], $result);
        // Ensure no other secrets are retrieved
        $this->assertCount(38, $result);
    }
}
