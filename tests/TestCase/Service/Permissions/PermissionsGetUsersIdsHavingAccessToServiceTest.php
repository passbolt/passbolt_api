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
 * @since         2.13.0
 */

namespace App\Test\TestCase\Service\Permissions;

use App\Model\Entity\Permission;
use App\Service\Permissions\PermissionsGetUsersIdsHavingAccessToService;
use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

/**
 * \App\Test\TestCase\Service\Permissions\PermissionsGetUsersIdsHavingAccessToService Test Case
 *
 * @covers \App\Test\TestCase\Service\Permissions\PermissionsGetUsersIdsHavingAccessToService
 */
class PermissionsGetUsersIdsHavingAccessToServiceTest extends AppTestCase
{
    /**
     * @var PermissionsTable
     */
    public $permissionsTable;

    /**
     * @var ResourcesTable
     */
    public $resourcesTable;

    /**
     * @var PermissionsUpdatePermissionsServiceTest
     */
    public $service;

    public $fixtures = [
        'app.Base/Groups', 'app.Base/GroupsUsers', 'app.Base/Permissions', 'app.Base/Resources', 'app.Base/Secrets',
        'app.Base/Users',
    ];

    public function setUp(): void
    {
        parent::setUp();
        $this->permissionsTable = TableRegistry::getTableLocator()->get('Permissions');
        $this->resourcesTable = TableRegistry::getTableLocator()->get('Resources');
        $this->service = new PermissionsGetUsersIdsHavingAccessToService();
    }

    public function testGetUsersIdsHavingAccessTo()
    {
        [$resource1, $g1, $userAId, $userBId, $userCId] = $this->insertFixture_GetUsersIdsHavingAccessTo();

        $result = $this->service->getUsersIdsHavingAccessTo($resource1->id);

        $this->assertCount(3, $result);
        $this->assertContains($userAId, $result);
        $this->assertContains($userBId, $result);
        $this->assertContains($userCId, $result);
    }

    private function insertFixture_GetUsersIdsHavingAccessTo()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $userCId = UuidFactory::uuid('user.id.carol');
        $g1 = $this->addGroup(['name' => 'G1', 'groups_users' => [
            ['user_id' => $userBId, 'is_admin' => true],
            ['user_id' => $userCId, 'is_admin' => true],
        ]]);
        $forUsers = [$userAId => Permission::OWNER, $userBId => Permission::OWNER];
        $forGroups = [$g1->id => Permission::OWNER];
        $resource1 = $this->addResourceFor(['name' => 'R1'], $forUsers, $forGroups);

        return [$resource1, $g1, $userAId, $userBId, $userCId];
    }
}
