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

namespace App\Test\TestCase\Model\Table\GroupsUsers;

use App\Test\Factory\GroupFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\ORM\TableRegistry;

class FindNonEmptyGroupsWhereUserIsSoleManagerTest extends AppTestCase
{
    /**
     * @var \App\Model\Table\GroupsUsersTable
     */
    public $GroupsUsers;

    public function setUp(): void
    {
        parent::setUp();
        $this->GroupsUsers = TableRegistry::getTableLocator()->get('GroupsUsers');
    }

    public function tearDown(): void
    {
        unset($this->GroupsUsers);
        parent::tearDown();
    }

    public function testNotAManager()
    {
        // Ada is not manager of any group
        $ada = UserFactory::make()->persist();
        $result = $this->GroupsUsers->findNonEmptyGroupsWhereUserIsSoleManager($ada->id)
            ->all()
            ->extract('group_id')
            ->toArray();
        $this->assertEmpty($result);
    }

    public function testOtherManagersPresent()
    {
        // Ursula is manager of it_support group where ping is also manager
        [$ursula, $ping] = UserFactory::make(2)->persist();
        GroupFactory::make()->withGroupsManagersFor([$ursula, $ping])->persist();
        $result = $this->GroupsUsers->findNonEmptyGroupsWhereUserIsSoleManager($ursula->id)
            ->all()
            ->extract('group_id')
            ->toArray();
        $this->assertEmpty($result);
    }

    public function testOnlyManagerButEmptyGroup()
    {
        // Admin is manager of a lot of empty groups and no active groups
        $admin = UserFactory::make()->persist();
        GroupFactory::make(5)->withGroupsManagersFor([$admin])->persist();
        $result = $this->GroupsUsers->findNonEmptyGroupsWhereUserIsSoleManager($admin->id)
            ->all()
            ->extract('group_id')
            ->toArray();
        $this->assertEmpty($result);

        // Same for hedy but only 1 group
        $hedy = UserFactory::make()->persist();
        GroupFactory::make()->withGroupsManagersFor([$hedy])->persist();
        $result = $this->GroupsUsers->findNonEmptyGroupsWhereUserIsSoleManager($hedy->id)
            ->all()
            ->extract('group_id')
            ->toArray();
        $this->assertEmpty($result);
    }

    public function testOnlyManagerInNonEmptyGroup()
    {
        // Frances is admin of the accounting group where grace is also a user
        [$frances, $grace] = UserFactory::make(2)->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$frances])->withGroupsUsersFor([$grace])->persist();
        $result = $this->GroupsUsers->findNonEmptyGroupsWhereUserIsSoleManager($frances->id)
            ->all()
            ->extract('group_id')
            ->toArray();
        $this->assertNotEmpty($result);
        $this->assertEquals($result[0], $group->id);

        // Same for jean with freelancer group but with more members
        $jean = UserFactory::make()->persist();
        $group2 = GroupFactory::make()
            ->withGroupsManagersFor([$jean])
            ->withGroupsUsersFor(UserFactory::make(5)->persist())
            ->persist();
        $result = $this->GroupsUsers->findNonEmptyGroupsWhereUserIsSoleManager($jean->id)
            ->all()
            ->extract('group_id')
            ->toArray();
        $this->assertNotEmpty($result);
        $this->assertEquals($result[0], $group2->id);
    }
}
