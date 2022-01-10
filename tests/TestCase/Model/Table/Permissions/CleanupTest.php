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

namespace App\Test\TestCase\Model\Table\Permissions;

use App\Model\Table\PermissionsTable;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\PermissionFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\CleanupTrait;
use App\Utility\UuidFactory;
use Cake\I18n\Time;

class CleanupTest extends AppTestCase
{
    use CleanupTrait;

    public function testCleanupPermissionsSoftDeletedUsersSuccess()
    {
        // The permission to cleanup.
        PermissionFactory::make()
            ->withAroUser(UserFactory::make()->deleted())
            ->persist();

        // Witness permissions to not cleanup.
        $permissionWithUser = PermissionFactory::make()
            ->withAroUser()
            ->persist();
        $permissionWithHardDeletedUser = PermissionFactory::make(['aro' => PermissionsTable::USER_ARO])->persist();

        $this->runCleanupChecks('Permissions', 'cleanupSoftDeletedUsers', 2);

        $permissionsIdsPostCleanup = PermissionFactory::find()->extract('id')->toArray();
        $this->assertCount(2, $permissionsIdsPostCleanup);
        $this->assertContains($permissionWithUser->id, $permissionsIdsPostCleanup);
        $this->assertContains($permissionWithHardDeletedUser->id, $permissionsIdsPostCleanup);
    }

    public function testCleanupPermissionsHardDeletedUsersSuccess()
    {
        // The permission to cleanup.
        PermissionFactory::make(['aro' => PermissionsTable::USER_ARO])->persist();

        // Witness permissions to not cleanup.
        $permissionWithUser = PermissionFactory::make()
            ->withAroUser()
            ->persist();
        $permissionWithSoftDeletedUser = PermissionFactory::make()
            ->withAroUser(UserFactory::make()->deleted())
            ->persist();

        $this->runCleanupChecks('Permissions', 'cleanupHardDeletedUsers', 2);

        $permissionsIdsPostCleanup = PermissionFactory::find()->extract('id')->toArray();
        $this->assertCount(2, $permissionsIdsPostCleanup);
        $this->assertContains($permissionWithUser->id, $permissionsIdsPostCleanup);
        $this->assertContains($permissionWithSoftDeletedUser->id, $permissionsIdsPostCleanup);
    }

    public function testCleanupPermissionsSoftDeletedGroupsSuccess()
    {
        // The permission to cleanup.
        PermissionFactory::make()
            ->withAroGroup(GroupFactory::make()->deleted())
            ->persist();

        // Witness permissions to not cleanup.
        $permissionWithGroup = PermissionFactory::make()
            ->withAroGroup()
            ->persist();
        $permissionWithHardDeletedGroup = PermissionFactory::make(['aro' => PermissionsTable::GROUP_ARO])->persist();

        $this->runCleanupChecks('Permissions', 'cleanupSoftDeletedGroups', 2);

        $permissionsIdsPostCleanup = PermissionFactory::find()->extract('id')->toArray();
        $this->assertCount(2, $permissionsIdsPostCleanup);
        $this->assertContains($permissionWithGroup->id, $permissionsIdsPostCleanup);
        $this->assertContains($permissionWithHardDeletedGroup->id, $permissionsIdsPostCleanup);
    }

    public function testCleanupPermissionsHardDeletedGroupsSuccess()
    {
        // The permission to cleanup.
        PermissionFactory::make(['aro' => PermissionsTable::GROUP_ARO])->persist();

        // Witness permissions to not cleanup.
        $permissionWithGroup = PermissionFactory::make()
            ->withAroGroup()
            ->persist();
        $permissionWithSoftDeletedGroup = PermissionFactory::make()
            ->withAroGroup(GroupFactory::make()->deleted())
            ->persist();

        $this->runCleanupChecks('Permissions', 'cleanupHardDeletedGroups', 2);

        $permissionsIdsPostCleanup = PermissionFactory::find()->extract('id')->toArray();
        $this->assertCount(2, $permissionsIdsPostCleanup);
        $this->assertContains($permissionWithGroup->id, $permissionsIdsPostCleanup);
        $this->assertContains($permissionWithSoftDeletedGroup->id, $permissionsIdsPostCleanup);
    }

    public function testCleanupPermissionsSoftDeletedResourcesSuccess()
    {
        // The permission to cleanup.
        PermissionFactory::make()
            ->withAcoResource(ResourceFactory::make()->deleted())
            ->persist();

        // Witness permissions to not cleanup.
        $permissionWithResource = PermissionFactory::make()
            ->withAcoResource()
            ->persist();
        $permissionWithHardDeletedResource = PermissionFactory::make(['aco' => PermissionsTable::RESOURCE_ACO])->persist();

        $this->runCleanupChecks('Permissions', 'cleanupSoftDeletedResources', 2);

        $permissionsIdsPostCleanup = PermissionFactory::find()->extract('id')->toArray();
        $this->assertCount(2, $permissionsIdsPostCleanup);
        $this->assertContains($permissionWithResource->id, $permissionsIdsPostCleanup);
        $this->assertContains($permissionWithHardDeletedResource->id, $permissionsIdsPostCleanup);
    }

    public function testCleanupPermissionsHardDeletedResourcesSuccess()
    {
        // The permission to cleanup.
        PermissionFactory::make(['aco' => PermissionsTable::RESOURCE_ACO])->persist();

        // Witness permissions to not cleanup.
        $permissionWithResource = PermissionFactory::make()
            ->withAcoResource()
            ->persist();
        $permissionWithSoftDeletedResource = PermissionFactory::make()
            ->withAcoResource(ResourceFactory::make()->deleted())
            ->persist();

        $this->runCleanupChecks('Permissions', 'cleanupHardDeletedResources', 2);

        $permissionsIdsPostCleanup = PermissionFactory::find()->extract('id')->toArray();
        $this->assertCount(2, $permissionsIdsPostCleanup);
        $this->assertContains($permissionWithResource->id, $permissionsIdsPostCleanup);
        $this->assertContains($permissionWithSoftDeletedResource->id, $permissionsIdsPostCleanup);
    }

    public function testCleanupPermissionsDuplicatedPermissions()
    {
        // Duplicated permissions to cleanup.
        $duplicatedPermissionsForUser = PermissionFactory::make(['modified' => Time::now()])
            ->typeOwner()
            ->withAcoResource()
            ->withAroUser()
            ->persist();

        // Duplicate permission to keep as it is the oldest.
        $duplicatedPermissionForUserMeta = $duplicatedPermissionsForUser->extractOriginal(['aco', 'aco_foreign_key', 'aro', 'aro_foreign_key', 'type']);
        $duplicatedPermissionToKeep = PermissionFactory::make($duplicatedPermissionForUserMeta)
            ->patchData(['modified' => Time::now()->subDay()])->persist();

        $duplicatedPermissionsForGroup = PermissionFactory::make()
            ->typeRead()
            ->withAcoResource()
            ->withAroGroup()
            ->persist();
        $duplicatedPermissionForGroupMeta = $duplicatedPermissionsForGroup->extractOriginal(['aco', 'aco_foreign_key', 'aro', 'aro_foreign_key', 'type']);
        PermissionFactory::make($duplicatedPermissionForGroupMeta)->persist();

        // Witness permissions to not cleanup:
        // - A permission including a resource involved in the cleanup
        // - A permission including a user involved in the cleanup
        // - A permission including a group involved in the cleanup
        // - A user having permissions on 2 different resources.
        // - A group having permissions on 2 different resources.
        // - A resource having multiple permissions for different users and groups.
        $permissionWithResourceInvolvedInCleanup = PermissionFactory::make($duplicatedPermissionForUserMeta)->patchData(['aro_foreign_key' => UuidFactory::uuid()])->persist();
        $permissionWithUserInvolvedInCleanup = PermissionFactory::make($duplicatedPermissionForUserMeta)->patchData(['aco_foreign_key' => UuidFactory::uuid()])->persist();
        $permissionWithGroupInvolvedInCleanup = PermissionFactory::make($duplicatedPermissionForGroupMeta)->patchData(['aco_foreign_key' => UuidFactory::uuid()])->persist();
        $userHavingAccessToMultipleResources = UserFactory::make()->persist();
        $userPermissionToKeep1 = PermissionFactory::make()->aroUser($userHavingAccessToMultipleResources)->typeOwner()->withAcoResource()->persist();
        $userPermissionToKeep2 = PermissionFactory::make()->aroUser($userHavingAccessToMultipleResources)->typeOwner()->withAcoResource()->persist();
        $groupHavingAccessToMultipleResources = GroupFactory::make()->persist();
        $groupPermissionToKeep1 = PermissionFactory::make()->aroGroup($groupHavingAccessToMultipleResources)->typeOwner()->withAcoResource()->persist();
        $groupPermissionToKeep2 = PermissionFactory::make()->aroGroup($groupHavingAccessToMultipleResources)->typeOwner()->withAcoResource()->persist();
        $resourceHavingMultiplePermissions = ResourceFactory::make()->persist();
        $resourcePermissionsToKeep = PermissionFactory::make(4)->acoResource($resourceHavingMultiplePermissions)->persist();

        $this->runCleanupChecks('Permissions', 'cleanupDuplicatedPermissions', 13, ['cleanupCount' => 2]);

        $permissionsIdsPostCleanup = PermissionFactory::find()->extract('id')->toArray();
        $this->assertCount(13, $permissionsIdsPostCleanup);
        $this->assertContains($duplicatedPermissionToKeep->id, $permissionsIdsPostCleanup);
        $this->assertContains($permissionWithResourceInvolvedInCleanup->id, $permissionsIdsPostCleanup);
        $this->assertContains($permissionWithUserInvolvedInCleanup->id, $permissionsIdsPostCleanup);
        $this->assertContains($permissionWithGroupInvolvedInCleanup->id, $permissionsIdsPostCleanup);
        $this->assertContains($userPermissionToKeep1->id, $permissionsIdsPostCleanup);
        $this->assertContains($userPermissionToKeep2->id, $permissionsIdsPostCleanup);
        $this->assertContains($groupPermissionToKeep1->id, $permissionsIdsPostCleanup);
        $this->assertContains($groupPermissionToKeep2->id, $permissionsIdsPostCleanup);
        $this->assertContains($resourcePermissionsToKeep[0]->id, $permissionsIdsPostCleanup);
        $this->assertContains($resourcePermissionsToKeep[1]->id, $permissionsIdsPostCleanup);
        $this->assertContains($resourcePermissionsToKeep[2]->id, $permissionsIdsPostCleanup);
        $this->assertContains($resourcePermissionsToKeep[3]->id, $permissionsIdsPostCleanup);
    }
}
