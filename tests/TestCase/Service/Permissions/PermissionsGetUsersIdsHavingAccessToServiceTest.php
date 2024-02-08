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
use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;

/**
 * \App\Test\TestCase\Service\Permissions\PermissionsGetUsersIdsHavingAccessToService Test Case
 *
 * @covers \App\Test\TestCase\Service\Permissions\PermissionsGetUsersIdsHavingAccessToService
 */
class PermissionsGetUsersIdsHavingAccessToServiceTest extends AppTestCase
{
    public PermissionsGetUsersIdsHavingAccessToService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new PermissionsGetUsersIdsHavingAccessToService();
    }

    public function testGetUsersIdsHavingAccessTo()
    {
        $allUsers = [$user1, $user2, $user3] = UserFactory::make(10)->persist();
        ResourceFactory::make(10)->persist();

        // User 1 and 2 own permission by group permission
        $group = GroupFactory::make()
            ->withGroupsUsersFor([$user1, $user2,])
            ->persist();

        // User 1 and 3 own permission by user permission
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$user1, $user3])
            ->withPermissionsFor([$group], Permission::READ)
            ->withSecretsFor($allUsers)
            ->persist();

        $result = $this->service->getUsersIdsHavingAccessTo($resource->id);

        // User should be retrieved only once
        $this->assertCount(3, $result);
        $this->assertContains($user1->id, $result);
        $this->assertContains($user2->id, $result);
        $this->assertContains($user3->id, $result);
    }
}
