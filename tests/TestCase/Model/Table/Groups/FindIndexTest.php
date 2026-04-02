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

namespace App\Test\TestCase\Model\Table\Groups;

use App\Model\Table\GroupsTable;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\PermissionFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class FindIndexTest extends AppTestCase
{
    public $Groups;

    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Groups') ? [] : ['className' => GroupsTable::class];
        $this->Groups = TableRegistry::getTableLocator()->get('Groups', $config);
    }

    public function tearDown(): void
    {
        unset($this->Groups);

        parent::tearDown();
    }

    public function testGroupFindIndexSuccess()
    {
        GroupFactory::make(2)->persist();
        $groups = $this->Groups->findIndex()->all();
        $this->assertGreaterThan(1, count($groups));

        // Expected content.
        $group = $groups->first();
        $this->assertGroupAttributes($group);
        // Not expected content.
        $this->assertObjectNotHasAttribute('modifier', $group);
        $this->assertObjectNotHasAttribute('users', $group);
    }

    public function testGroupFindIndexExcludeSoftDeletedGroups()
    {
        // Check the deleted groups exist.
        $deletedGroups = [GroupFactory::make()->deleted()->persist()->id];
        foreach ($deletedGroups as $deletedGroup) {
            $group = $this->Groups->get($deletedGroup);
            $this->assertNotNull($group);
        }

        // Retrieve the groups.
        $groups = $this->Groups->findIndex()->all();
        $groupsIds = Hash::extract($groups->toArray(), '{n}.id');

        // Ensure no deleted groups are present in the result
        $this->assertEquals(0, count(array_intersect($deletedGroups, $groupsIds)));
    }

    public function testGroupFindIndexContainModifier()
    {
        $user = UserFactory::make()->persist();
        GroupFactory::make(['modified_by' => $user->id])->persist();
        $options['contain']['modifier'] = true;
        $groups = $this->Groups->findIndex($options)->all();
        $group = $groups->first();

        // Expected content.
        $this->assertGroupAttributes($group);
        $this->assertObjectHasAttribute('modifier', $group);
        $this->assertUserAttributes($group->modifier);
    }

    public function testGroupFindIndexContainModifierProfile()
    {
        $user = UserFactory::make()->persist();
        GroupFactory::make(['modified_by' => $user->id])->persist();
        $options['contain']['modifier.profile'] = true;
        $groups = $this->Groups->findIndex($options)->all();
        $group = $groups->first();

        // Expected content.
        $this->assertGroupAttributes($group);
        $this->assertObjectHasAttribute('modifier', $group);
        $this->assertObjectHasAttribute('profile', $group->modifier);
        $this->assertProfileAttributes($group->modifier->profile);
    }

    public function testGroupFindIndexContainUserDeprecated()
    {
        [$userA, $userB, $userC, $userD] = UserFactory::make(4)->persist();
        $groupId = GroupFactory::make()->withGroupsManagersFor([$userA, $userB, $userC, $userD])->persist()->id;
        $options['contain']['user'] = true;
        $groups = $this->Groups->findIndex($options)->all();
        $group = $groups->first();

        // Expected content.
        $this->assertGroupAttributes($group);
        $this->assertObjectHasAttribute('users', $group);
        $this->assertUserAttributes($group->users[0]);

        // Check that the groups contain only the expected users.
        $groupUsers = [
            $userA->id,
            $userB->id,
            $userC->id,
            $userD->id,
        ];
        // Retrieve the users from the group we want to test.
        $group = Hash::extract($groups->toArray(), '{n}[id=' . $groupId . ']')[0];
        $usersIds = Hash::extract($group->users, '{n}.id');
        // Check that all the expected users are there.
        $this->assertEquals(0, count(array_diff($groupUsers, $usersIds)));
    }

    public function testGroupFindIndexContainUsers()
    {
        [$userA, $userB, $userC, $userD] = UserFactory::make(4)->persist();
        $groupId = GroupFactory::make()->withGroupsManagersFor([$userA, $userB, $userC, $userD])->persist()->id;
        $options['contain']['users'] = true;
        $groups = $this->Groups->findIndex($options)->all();
        $group = $groups->first();

        // Expected content.
        $this->assertGroupAttributes($group);
        $this->assertObjectHasAttribute('users', $group);
        $this->assertUserAttributes($group->users[0]);

        // Check that the groups contain only the expected users.
        $groupUsers = [
            $userA->id,
            $userB->id,
            $userC->id,
            $userD->id,
        ];
        // Retrieve the users from the group we want to test.
        $group = Hash::extract($groups->toArray(), '{n}[id=' . $groupId . ']')[0];
        $usersIds = Hash::extract($group->users, '{n}.id');
        // Check that all the expected users are there.
        $this->assertEquals(0, count(array_diff($groupUsers, $usersIds)));
    }

    public function testGroupFindIndexContainUserCount()
    {
        [$userA, $userB] = UserFactory::make(2)->persist();
        GroupFactory::make(2)->withGroupsManagersFor([$userA, $userB])->persist();
        $options['contain']['user_count'] = true;
        $groups = $this->Groups->findIndex($options)->all();

        foreach ($groups as $group) {
            $this->assertNotEmpty($group->user_count);
            $expectedCount = $this->Groups->getAssociation('GroupsUsers')->find()
                ->where(['GroupsUsers.group_id' => $group->id])->all()->count();
            $this->assertEquals($expectedCount, $group->user_count);
        }
    }

    public function testGroupFindIndexContainGroupUser_DeprecatedContain()
    {
        [$userA, $userB, $userC, $userD] = UserFactory::make(4)->persist();
        $groupId = GroupFactory::make()->withGroupsManagersFor([$userA, $userB, $userC, $userD])->persist()->id;
        $options['contain']['group_user'] = true;
        $groups = $this->Groups->findIndex($options)->all();
        $group = $groups->first();

        // Expected content.
        $this->assertGroupAttributes($group);
        $this->assertObjectHasAttribute('groups_users', $group);
        $this->assertGroupUserAttributes($group->groups_users[0]);

        // Check that the groups contain only the expected users.
        $groupUsers = [
            $userA->id,
            $userB->id,
            $userC->id,
            $userD->id,
        ];
        // Retrieve the users from the group we want to test.
        $group = Hash::extract($groups->toArray(), '{n}[id=' . $groupId . ']')[0];
        $usersIds = Hash::extract($group->groups_users, '{n}.user_id');
        // Check that all the expected users are there.
        $this->assertEquals(0, count(array_diff($groupUsers, $usersIds)));
    }

    public function testGroupFindIndexContainGroupsUsers_DeprecateContain()
    {
        [$userA, $userB, $userC, $userD] = UserFactory::make(4)->persist();
        $groupId = GroupFactory::make()->withGroupsManagersFor([$userA, $userB, $userC, $userD])->persist()->id;
        $options['contain']['groups_users'] = true;
        $groups = $this->Groups->findIndex($options)->all();
        $group = $groups->first();

        // Expected content.
        $this->assertGroupAttributes($group);
        $this->assertObjectHasAttribute('groups_users', $group);
        $this->assertGroupUserAttributes($group->groups_users[0]);

        // Check that the groups contain only the expected users.
        $groupUsers = [
            $userA->id,
            $userB->id,
            $userC->id,
            $userD->id,
        ];
        // Retrieve the users from the group we want to test.
        $group = Hash::extract($groups->toArray(), '{n}[id=' . $groupId . ']')[0];
        $usersIds = Hash::extract($group->groups_users, '{n}.user_id');
        // Check that all the expected users are there.
        $this->assertEquals(0, count(array_diff($groupUsers, $usersIds)));
    }

    public function testGroupFindIndexContainGroupsUsersProfile()
    {
        [$userA, $userB] = UserFactory::make(2)->persist();
        GroupFactory::make()->withGroupsManagersFor([$userA, $userB])->persist();
        $options['contain']['groups_users.user.profile'] = true;
        $groups = $this->Groups->findIndex($options)->all();
        $group = $groups->first();

        // Expected content.
        $this->assertGroupAttributes($group);
        $this->assertObjectHasAttribute('groups_users', $group);
        $this->assertObjectHasAttribute('user', $group->groups_users[0]);
        $this->assertObjectHasAttribute('profile', $group->groups_users[0]->user);
        $this->assertProfileAttributes($group->groups_users[0]->user->profile);
    }

    public function testGroupFindIndexContainGroupUserProfile_DeprecateContain()
    {
        [$userA, $userB] = UserFactory::make(2)->persist();
        GroupFactory::make()->withGroupsManagersFor([$userA, $userB])->persist();
        $options['contain']['group_user.user.profile'] = true;
        $groups = $this->Groups->findIndex($options)->all();
        $group = $groups->first();

        // Expected content.
        $this->assertGroupAttributes($group);
        $this->assertObjectHasAttribute('groups_users', $group);
        $this->assertObjectHasAttribute('user', $group->groups_users[0]);
        $this->assertObjectHasAttribute('profile', $group->groups_users[0]->user);
        $this->assertProfileAttributes($group->groups_users[0]->user->profile);
    }

    public function testFilterHasUsers()
    {
        $user = UserFactory::make()->persist();
        [$groupA, $groupB, $groupC] = GroupFactory::make(3)->withGroupsUsersFor([$user])->persist();
        $expectedGroupsIds = [$groupA->id, $groupB->id, $groupC->id];
        $options['filter']['has-users'] = [$user->id];
        $groups = $this->Groups->findIndex($options)->all();

        $this->assertCount(3, $groups);
        $groupsIds = Hash::extract($groups->toArray(), '{n}.id');
        $this->assertEquals(0, count(array_diff($expectedGroupsIds, $groupsIds)));
    }

    public function testFilterHasUsersNoResult()
    {
        $options['filter']['has-users'] = [UserFactory::make()->persist()->id];
        $groups = $this->Groups->findIndex($options)->all();

        $this->assertCount(0, $groups);
    }

    public function testFilterHasUsersMultipleUsers()
    {
        [$userA, $userB] = UserFactory::make(2)->persist();
        $expectedGroupsIds = [GroupFactory::make()->withGroupsManagersFor([$userA, $userB])->persist()->id];
        $options['filter']['has-users'] = [$userA->id, $userB->id];
        $groups = $this->Groups->findIndex($options)->all();

        $this->assertCount(1, $groups);
        $groupsIds = Hash::extract($groups->toArray(), '{n}.id');
        $this->assertEquals(0, count(array_diff($expectedGroupsIds, $groupsIds)));
    }

    public function testFilterHasUsersMultipleUsersNoResult()
    {
        [$userA, $userB] = UserFactory::make(2)->persist();
        $options['filter']['has-users'] = [$userA->id, $userB->id];
        $groups = $this->Groups->findIndex($options)->all();

        $this->assertCount(0, $groups);
    }

    public function testFilterHasManagersNoResult()
    {
        $options['filter']['has-managers'] = [UserFactory::make()->persist()->id];
        $groups = $this->Groups->findIndex($options)->all();

        $this->assertCount(0, $groups);
    }

    public function testFilterHasManagers()
    {
        $user = UserFactory::make()->persist();
        [$groupA, $groupB] = GroupFactory::make(2)->withGroupsManagersFor([$user])->persist();
        $expectedGroupsIds = [$groupA->id, $groupB->id];
        $options['filter']['has-managers'] = [$user->id];
        $groups = $this->Groups->findIndex($options)->all();

        $this->assertCount(2, $groups);
        $groupsIds = Hash::extract($groups->toArray(), '{n}.id');
        $this->assertEquals(0, count(array_diff($expectedGroupsIds, $groupsIds)));
    }

    public function testFilterHasManagersMultipleManagers()
    {
        [$userA, $userB] = UserFactory::make(2)->persist();
        $expectedGroupsIds = [GroupFactory::make()->withGroupsManagersFor([$userA, $userB])->persist()->id];
        $options['filter']['has-managers'] = [$userA->id, $userB->id];
        $groups = $this->Groups->findIndex($options)->all();

        $this->assertCount(1, $groups);
        $groupsIds = Hash::extract($groups->toArray(), '{n}.id');
        $this->assertEquals(0, count(array_diff($expectedGroupsIds, $groupsIds)));
    }

    public function testFilterHasManagersMultipleManagersNoResult()
    {
        [$userA, $userB] = UserFactory::make(2)->persist();
        $options['filter']['has-managers'] = [$userA->id, $userB->id];
        $groups = $this->Groups->findIndex($options)->all();

        $this->assertCount(0, $groups);
    }

    /**
     * Test that groups with a READ permission on a resource are excluded.
     */
    public function testFilterHasNotPermission_ExcludesGroupWithReadPermission()
    {
        [$groupWithAccess, $groupWithout] = GroupFactory::make(2)->persist();
        $resource = ResourceFactory::make()->persist();
        PermissionFactory::make()->acoResource($resource)->aroGroup($groupWithAccess)->typeRead()->persist();

        $findIndexOptions['filter']['has-not-permission'] = [$resource->id];
        $groups = $this->Groups->findIndex($findIndexOptions)->all();
        $groupsIds = Hash::extract($groups->toArray(), '{n}.id');

        $this->assertContains($groupWithout->id, $groupsIds);
        $this->assertNotContains($groupWithAccess->id, $groupsIds);
    }

    /**
     * Test that groups with an UPDATE permission on a resource are excluded.
     */
    public function testFilterHasNotPermission_ExcludesGroupWithUpdatePermission()
    {
        [$groupWithAccess, $groupWithout] = GroupFactory::make(2)->persist();
        $resource = ResourceFactory::make()->persist();
        PermissionFactory::make()->acoResource($resource)->aroGroup($groupWithAccess)->typeUpdate()->persist();

        $findIndexOptions['filter']['has-not-permission'] = [$resource->id];
        $groups = $this->Groups->findIndex($findIndexOptions)->all();
        $groupsIds = Hash::extract($groups->toArray(), '{n}.id');

        $this->assertContains($groupWithout->id, $groupsIds);
        $this->assertNotContains($groupWithAccess->id, $groupsIds);
    }

    /**
     * Test that groups with an OWNER permission on a resource are excluded.
     */
    public function testFilterHasNotPermission_ExcludesGroupWithOwnerPermission()
    {
        [$groupWithAccess, $groupWithout] = GroupFactory::make(2)->persist();
        $resource = ResourceFactory::make()->persist();
        PermissionFactory::make()->acoResource($resource)->aroGroup($groupWithAccess)->typeOwner()->persist();

        $findIndexOptions['filter']['has-not-permission'] = [$resource->id];
        $groups = $this->Groups->findIndex($findIndexOptions)->all();
        $groupsIds = Hash::extract($groups->toArray(), '{n}.id');

        $this->assertContains($groupWithout->id, $groupsIds);
        $this->assertNotContains($groupWithAccess->id, $groupsIds);
    }

    /**
     * Test that all permission types (READ, UPDATE, OWNER) are excluded and
     * only the group with no permission is returned.
     */
    public function testFilterHasNotPermission_MixedPermissionTypes()
    {
        [$groupRead, $groupUpdate, $groupOwner, $groupNoPerm] = GroupFactory::make(4)->persist();
        $resource = ResourceFactory::make()->persist();
        PermissionFactory::make()->acoResource($resource)->aroGroup($groupRead)->typeRead()->persist();
        PermissionFactory::make()->acoResource($resource)->aroGroup($groupUpdate)->typeUpdate()->persist();
        PermissionFactory::make()->acoResource($resource)->aroGroup($groupOwner)->typeOwner()->persist();

        $findIndexOptions['filter']['has-not-permission'] = [$resource->id];
        $groups = $this->Groups->findIndex($findIndexOptions)->all();
        $groupsIds = Hash::extract($groups->toArray(), '{n}.id');

        $this->assertEqualsCanonicalizing([$groupNoPerm->id], $groupsIds);
    }

    /**
     * Test that when a resource has no permissions at all, all groups are returned.
     */
    public function testFilterHasNotPermission_ResourceWithNoPermissions()
    {
        [$groupA, $groupB] = GroupFactory::make(2)->persist();
        $resource = ResourceFactory::make()->persist();

        $findIndexOptions['filter']['has-not-permission'] = [$resource->id];
        $groups = $this->Groups->findIndex($findIndexOptions)->all();
        $groupsIds = Hash::extract($groups->toArray(), '{n}.id');

        $this->assertContains($groupA->id, $groupsIds);
        $this->assertContains($groupB->id, $groupsIds);
    }

    /**
     * Test that permissions on a different resource do not affect the filter result.
     */
    public function testFilterHasNotPermission_PermissionOnDifferentResource()
    {
        [$groupA, $groupB] = GroupFactory::make(2)->persist();
        [$resourceA, $resourceB] = ResourceFactory::make(2)->persist();
        // groupA has permission on resourceA only
        PermissionFactory::make()->acoResource($resourceA)->aroGroup($groupA)->typeRead()->persist();

        // Filter on resourceB — groupA has no permission on resourceB, so both groups should be returned.
        $findIndexOptions['filter']['has-not-permission'] = [$resourceB->id];
        $groups = $this->Groups->findIndex($findIndexOptions)->all();
        $groupsIds = Hash::extract($groups->toArray(), '{n}.id');

        $this->assertContains($groupA->id, $groupsIds);
        $this->assertContains($groupB->id, $groupsIds);

        // Filter on resourceA — only groupB should be returned.
        $findIndexOptions['filter']['has-not-permission'] = [$resourceA->id];
        $groups = $this->Groups->findIndex($findIndexOptions)->all();
        $groupsIds = Hash::extract($groups->toArray(), '{n}.id');

        $this->assertContains($groupB->id, $groupsIds);
        $this->assertNotContains($groupA->id, $groupsIds);
    }

    /**
     * Test that soft-deleted groups are excluded from has-not-permission results.
     */
    public function testFilterHasNotPermission_ExcludesSoftDeletedGroups()
    {
        $activeGroup = GroupFactory::make()->persist();
        $deletedGroup = GroupFactory::make()->deleted()->persist();
        $resource = ResourceFactory::make()->persist();

        $findIndexOptions['filter']['has-not-permission'] = [$resource->id];
        $groups = $this->Groups->findIndex($findIndexOptions)->all();
        $groupsIds = Hash::extract($groups->toArray(), '{n}.id');

        $this->assertContains($activeGroup->id, $groupsIds);
        $this->assertNotContains($deletedGroup->id, $groupsIds);
    }

    /**
     * Test that a soft-deleted group with a permission on the resource is excluded.
     */
    public function testFilterHasNotPermission_SoftDeletedGroupWithPermission()
    {
        $activeGroup = GroupFactory::make()->persist();
        $deletedGroupWithPerm = GroupFactory::make()->deleted()->persist();
        $resource = ResourceFactory::make()->persist();
        PermissionFactory::make()->acoResource($resource)->aroGroup($deletedGroupWithPerm)->typeRead()->persist();

        $findIndexOptions['filter']['has-not-permission'] = [$resource->id];
        $groups = $this->Groups->findIndex($findIndexOptions)->all();
        $groupsIds = Hash::extract($groups->toArray(), '{n}.id');

        $this->assertContains($activeGroup->id, $groupsIds);
        $this->assertNotContains($deletedGroupWithPerm->id, $groupsIds);
    }

    public function testFilterSearch()
    {
        $groupId = GroupFactory::make(['name' => 'creative'])->persist()->id;
        $findIndexOptions['filter']['search'] = ['Creative'];
        $groups = $this->Groups->findIndex($findIndexOptions)->all();
        $this->assertCount(1, $groups);
        $group = $groups->first();
        $this->assertEquals($groupId, $group->id);
    }

    public function testOrderByName()
    {
        $groupA = GroupFactory::make(['name' => 'accounting'])->persist();
        $groupZ = GroupFactory::make(['name' => 'zoo'])->persist();
        $findIndexOptions = ['order' => ['Groups.name ASC']];
        $groups = $this->Groups->findIndex($findIndexOptions)->all()->toArray();
        $this->assertEquals($groups[0]->id, $groupA->id);

        $findIndexOptions = ['order' => 'Groups.name ASC'];
        $groups = $this->Groups->findIndex($findIndexOptions)->all()->toArray();
        $this->assertEquals($groups[0]->id, $groupA->id);

        $findIndexOptions = ['order' => ['Groups.name DESC']];
        $groups = $this->Groups->findIndex($findIndexOptions)->all()->toArray();
        $this->assertEquals($groups[0]->id, $groupZ->id);

        $findIndexOptions = ['order' => 'Groups.name DESC'];
        $groups = $this->Groups->findIndex($findIndexOptions)->all()->toArray();
        $this->assertEquals($groups[0]->id, $groupZ->id);
    }
}
