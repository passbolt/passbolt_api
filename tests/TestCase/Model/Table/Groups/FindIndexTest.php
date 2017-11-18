<?php
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
 * @since         2.0.0
 */

namespace App\Test\TestCase\Model\Table\Groups;

use App\Model\Table\GroupsTable;
use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use PassboltTestData\Lib\PermissionMatrix;

class FindIndexTest extends AppTestCase
{
    public $Groups;

    public $fixtures = ['app.groups', 'app.users', 'app.groups_users', 'app.permissions'];

    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Groups') ? [] : ['className' => GroupsTable::class];
        $this->Groups = TableRegistry::get('Groups', $config);
    }

    public function tearDown()
    {
        unset($this->Groups);

        parent::tearDown();
    }

    public function testSuccess()
    {
        $groups = $this->Groups->findIndex()->all();
        $this->assertGreaterThan(1, count($groups));

        // Expected content.
        $group = $groups->first();
        $this->assertGroupAttributes($group);
        // Not expected content.
        $this->assertObjectNotHasAttribute('modifier', $group);
        $this->assertObjectNotHasAttribute('users', $group);
    }

    public function testExcludeSoftDeletedGroups()
    {
        // Check the deleted groups exist.
        $deletedGroups = [UuidFactory::uuid('group.id.deleted')];
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

    public function testContainModifier()
    {
        $options['contain']['modifier'] = true;
        $groups = $this->Groups->findIndex($options)->all();
        $group = $groups->first();

        // Expected content.
        $this->assertGroupAttributes($group);
        $this->assertObjectHasAttribute('modifier', $group);
        $this->assertUserAttributes($group->modifier);
    }

    public function testContainUser()
    {
        $options['contain']['user'] = true;
        $groups = $this->Groups->findIndex($options)->all();
        $group = $groups->first();

        // Expected content.
        $this->assertGroupAttributes($group);
        $this->assertObjectHasAttribute('users', $group);
        $this->assertUserAttributes($group->users[0]);

        // Check that the groups contain only the expected users.
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $groupUsers = [
            UuidFactory::uuid('user.id.jean'),
            UuidFactory::uuid('user.id.kathleen'),
            UuidFactory::uuid('user.id.lynne'),
            UuidFactory::uuid('user.id.marlyn'),
            UuidFactory::uuid('user.id.nancy'),
        ];
        // Retrieve the users from the group we want to test.
        $group = Hash::extract($groups->toArray(), '{n}[id=' . $groupId . ']')[0];
        $usersIds = Hash::extract($group->users, '{n}.id');
        // Check that all the expected users are there.
        $this->assertEquals(0, count(array_diff($groupUsers, $usersIds)));
    }

    public function testFilterHasUsers()
    {
        $expectedGroupsIds = [UuidFactory::uuid('group.id.creative'), UuidFactory::uuid('group.id.developer'), UuidFactory::uuid('group.id.ergonom')];
        $options['filter']['has-users'] = [UuidFactory::uuid('user.id.irene')];
        $groups = $this->Groups->findIndex($options)->all();

        $this->assertCount(3, $groups);
        $groupsIds = Hash::extract($groups->toArray(), '{n}.id');
        $this->assertEquals(0, count(array_diff($expectedGroupsIds, $groupsIds)));
    }

    public function testFilterHasUsersNoResult()
    {
        $options['filter']['has-users'] = [UuidFactory::uuid('user.id.ada')];
        $groups = $this->Groups->findIndex($options)->all();

        $this->assertCount(0, $groups);
    }

    public function testFilterHasUsersMultipleUsers()
    {
        $expectedGroupsIds = [UuidFactory::uuid('group.id.freelancer')];
        $options['filter']['has-users'] = [UuidFactory::uuid('user.id.jean'), UuidFactory::uuid('user.id.nancy')];
        $groups = $this->Groups->findIndex($options)->all();

        $this->assertCount(1, $groups);
        $groupsIds = Hash::extract($groups->toArray(), '{n}.id');
        $this->assertEquals(0, count(array_diff($expectedGroupsIds, $groupsIds)));
    }

    public function testFilterHasUsersMultipleUsersNoResult()
    {
        $options['filter']['has-users'] = [UuidFactory::uuid('user.id.frances'), UuidFactory::uuid('user.id.hedy')];
        $groups = $this->Groups->findIndex($options)->all();

        $this->assertCount(0, $groups);
    }

    public function testFilterHasManagersNoResult()
    {
        $options['filter']['has-managers'] = [UuidFactory::uuid('user.id.ad')];
        $groups = $this->Groups->findIndex($options)->all();

        $this->assertCount(0, $groups);
    }

    public function testFilterHasManagers()
    {
        $expectedGroupsIds = [UuidFactory::uuid('group.id.human_resource'), UuidFactory::uuid('group.id.it_support')];
        $options['filter']['has-managers'] = [UuidFactory::uuid('user.id.ping')];
        $groups = $this->Groups->findIndex($options)->all();

        $this->assertCount(2, $groups);
        $groupsIds = Hash::extract($groups->toArray(), '{n}.id');
        $this->assertEquals(0, count(array_diff($expectedGroupsIds, $groupsIds)));
    }

    public function testFilterHasManagersMultipleManagers()
    {
        $expectedGroupsIds = [UuidFactory::uuid('group.id.human_resource')];
        $options['filter']['has-managers'] = [UuidFactory::uuid('user.id.ping'), UuidFactory::uuid('user.id.thelma')];
        $groups = $this->Groups->findIndex($options)->all();

        $this->assertCount(1, $groups);
        $groupsIds = Hash::extract($groups->toArray(), '{n}.id');
        $this->assertEquals(0, count(array_diff($expectedGroupsIds, $groupsIds)));
    }

    public function testFilterHasManagersMultipleManagersNoResult()
    {
        $options['filter']['has-managers'] = [UuidFactory::uuid('user.id.ping'), UuidFactory::uuid('user.id.admin')];
        $groups = $this->Groups->findIndex($options)->all();

        $this->assertCount(0, $groups);
    }

    public function testFilterHasNotPermission()
    {
        $permissionsMatrix = PermissionMatrix::getGroupsResourcesPermissions('resource');
        foreach ($permissionsMatrix as $resourceAlias => $resourcesExpectedPermissions) {
            // Extract expected groups.
            $expectedGroupsIds = [];
            foreach($resourcesExpectedPermissions as $groupAlias => $permissionType) {
                if (!$permissionType) {
                    $expectedGroupsIds[] = UuidFactory::uuid("group.id.$groupAlias");
                }
            }

            // Find all the groups who have access to the resource.
            $findIndexOptions['filter']['has-not-permission'] = [UuidFactory::uuid("resource.id.$resourceAlias")];
            $groups = $this->Groups->findIndex($findIndexOptions)->all();
            $groupsIds = Hash::extract($groups->toArray(), '{n}.id');

            $this->assertEmpty(array_diff($expectedGroupsIds, $groupsIds), "The filter hasNotPermission does not return expected groups for the resource $resourceAlias");
            $this->assertEmpty(array_diff($groupsIds, $expectedGroupsIds), "The filter hasNotPermission does not return expected groups for the resource $resourceAlias");
        }
    }

    public function testFilterSearch()
    {
        $findIndexOptions['filter']['search'] = ['Creative'];
        $groups = $this->Groups->findIndex($findIndexOptions)->all();
        $this->assertCount(1, $groups);
        $group = $groups->first();
        $this->assertEquals(UuidFactory::uuid('group.id.creative'), $group->id);
    }
}
