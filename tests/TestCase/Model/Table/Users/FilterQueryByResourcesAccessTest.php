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

namespace App\Test\TestCase\Model\Table\Users;

use App\Model\Entity\Permission;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\GroupsUserFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;

class FilterQueryByResourcesAccessTest extends TestCase
{
    use TruncateDirtyTables;

    /**
     * @var \App\Model\Table\UsersTable
     */
    public $Users;

    public function setUp(): void
    {
        parent::setUp();
        $this->Users = TableRegistry::getTableLocator()->get('Users');
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->Users);
    }

    public function role(): array
    {
        return [
            'no role defined' => [0, 'roles' => []],
            'readers only' => [1, 'roles' => [Permission::READ]],
            'editors only' => [2, 'roles' => [Permission::UPDATE]],
            'editors and readers' => [3, 'roles' => [Permission::READ, Permission::UPDATE]],
            'owners only' => [4, 'roles' => [Permission::OWNER]],
            'owners and readers' => [5, 'roles' => [Permission::READ, Permission::OWNER]],
            'owners and editors' => [6, 'roles' => [Permission::OWNER, Permission::UPDATE]],
            'all' => [7, 'roles' => [Permission::READ, Permission::UPDATE, Permission::OWNER]],
        ];
    }

    /**
     * @dataProvider role
     */
    public function testFilterQueryByResourcesAccess(int $scenario, array $permissions)
    {
        [$owner1, $owner2, $editor1, $editor2, $reader1, $reader2] = UserFactory::make(10)
            ->user()
            ->persist();
        $groupOwner = GroupFactory::make()
            ->withGroupsUsersFor([$owner1, $owner2]) // Add the two owners in this group to test that no duplicates are returned by the query
            ->persist();
        $groupEditor = GroupFactory::make()
            ->withGroupsUsersFor([$editor1])
            ->persist();
        $groupViewer = GroupFactory::make()
            ->withGroupsUsersFor([$reader1])
            ->persist();

        ResourceFactory::make()
            ->withPermissionsFor([$groupOwner])
            ->withPermissionsFor([$groupEditor], Permission::UPDATE)
            ->withPermissionsFor([$groupViewer], Permission::READ)
            ->persist();

        ResourceFactory::make()
            ->withPermissionsFor([$owner2])
            ->withPermissionsFor([$editor2], Permission::UPDATE)
            ->withPermissionsFor([$reader2], Permission::READ)
            ->persist();

        // Add some noise
        ResourceFactory::make(2)->persist();
        GroupsUserFactory::make(2)->persist();

        $query = $this->Users->find('list', valueField: 'id');
        $result = $this->Users->filterQueryByResourcesAccess(
            $query,
            ResourceFactory::find()->select('id'),
            $permissions
        );

        switch ($scenario) {
            case 1:
                $expectedCount = 2;
                $expectedUsers = [$reader1, $reader2];
                break;
            case 2:
                $expectedCount = 2;
                $expectedUsers = [$editor1, $editor2];
                break;
            case 3:
                $expectedCount = 4;
                $expectedUsers = [$reader1, $reader2, $editor1, $editor2];
                break;
            case 4:
                $expectedCount = 2;
                $expectedUsers = [$owner1, $owner2];
                break;
            case 5:
                $expectedCount = 4;
                $expectedUsers = [$owner1, $owner2, $reader1, $reader2];
                break;
            case 6:
                $expectedCount = 4;
                $expectedUsers = [$owner1, $owner2, $editor1, $editor2];
                break;
            default:
                $expectedCount = 6;
                $expectedUsers = [$owner1, $owner2, $editor1, $editor2, $reader1, $reader2];
                break;
        }
        $this->assertSame($expectedCount, $result->all()->count());
        foreach ($expectedUsers as $user) {
            $this->assertArrayHasKey($user->id, $result->toArray());
        }
    }
}
