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
use App\Test\TestCase\ApplicationTest;
use App\Utility\Common;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class FindIndexTest extends ApplicationTest
{
    public $Groups;

    public $fixtures = ['app.groups', 'app.users', 'app.groups_users'];

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

    public function testResultAttributes()
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
        $deletedGroups = [Common::uuid('group.id.deleted')];
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

    public function testContainUsers()
    {
        $options['contain']['user'] = true;
        $groups = $this->Groups->findIndex($options)->all();
        $group = $groups->first();

        // Expected content.
        $this->assertGroupAttributes($group);
        $this->assertObjectHasAttribute('users', $group);
        $this->assertUserAttributes($group->users[0]);

        // Check that the groups contain only the expected users.
        $groupId = Common::uuid('group.id.freelancer');
        $groupUsers = [
            Common::uuid('user.id.jean'),
            Common::uuid('user.id.kathleen'),
            Common::uuid('user.id.lynne'),
            Common::uuid('user.id.marlyn'),
            Common::uuid('user.id.nancy'),
        ];
        // Retrieve the users from the group we want to test.
        $group = Hash::extract($groups->toArray(), '{n}[id=' . $groupId .']')[0];
        $usersIds = Hash::extract($group->users, '{n}.id');
        // Check that all the expected users are there.
        $this->assertEquals(0, count(array_diff($groupUsers, $usersIds)));
    }

    public function testFilterHasUsers()
    {
        $expectedGroupsIds = [Common::uuid('group.id.creative'), Common::uuid('group.id.developer'), Common::uuid('group.id.ergonom')];
        $options['filter']['has-users'] = [Common::uuid('user.id.irene')];
        $groups = $this->Groups->findIndex($options)->all();

        $this->assertCount(3, $groups);
        $groupsIds = Hash::extract($groups->toArray(), '{n}.id');
        $this->assertEquals(0, count(array_diff($expectedGroupsIds, $groupsIds)));
    }

    public function testFilterHasUsersNoResult()
    {
        $options['filter']['has-users'] = [Common::uuid('user.id.ada')];
        $groups = $this->Groups->findIndex($options)->all();

        $this->assertCount(0, $groups);
    }

    public function testFilterHasUsersMultipleUsers()
    {
        $expectedGroupsIds = [Common::uuid('group.id.freelancer')];
        $options['filter']['has-users'] = [Common::uuid('user.id.jean'), Common::uuid('user.id.nancy')];
        $groups = $this->Groups->findIndex($options)->all();

        $this->assertCount(1, $groups);
        $groupsIds = Hash::extract($groups->toArray(), '{n}.id');
        $this->assertEquals(0, count(array_diff($expectedGroupsIds, $groupsIds)));
    }

    public function testFilterHasUsersMultipleUsersNoResult()
    {
        $options['filter']['has-users'] = [Common::uuid('user.id.frances'), Common::uuid('user.id.hedy')];
        $groups = $this->Groups->findIndex($options)->all();

        $this->assertCount(0, $groups);
    }

    public function testFilterHasManagersNoResult()
    {
        $options['filter']['has-managers'] = [Common::uuid('user.id.ad')];
        $groups = $this->Groups->findIndex($options)->all();

        $this->assertCount(0, $groups);
    }

    public function testFilterHasManagers()
    {
        $expectedGroupsIds = [Common::uuid('group.id.human_resource'), Common::uuid('group.id.it_support')];
        $options['filter']['has-managers'] = [Common::uuid('user.id.ping')];
        $groups = $this->Groups->findIndex($options)->all();

        $this->assertCount(2, $groups);
        $groupsIds = Hash::extract($groups->toArray(), '{n}.id');
        $this->assertEquals(0, count(array_diff($expectedGroupsIds, $groupsIds)));
    }

    public function testFilterHasManagersMultipleManagers()
    {
        $expectedGroupsIds = [Common::uuid('group.id.human_resource')];
        $options['filter']['has-managers'] = [Common::uuid('user.id.ping'), Common::uuid('user.id.thelma')];
        $groups = $this->Groups->findIndex($options)->all();

        $this->assertCount(1, $groups);
        $groupsIds = Hash::extract($groups->toArray(), '{n}.id');
        $this->assertEquals(0, count(array_diff($expectedGroupsIds, $groupsIds)));
    }

    public function testFilterHasManagersMultipleManagersNoResult()
    {
        $options['filter']['has-managers'] = [Common::uuid('user.id.ping'), Common::uuid('user.id.admin')];
        $groups = $this->Groups->findIndex($options)->all();

        $this->assertCount(0, $groups);
    }
}
