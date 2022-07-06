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
 * @since         3.7.0
 */

namespace App\Test\TestCase\Service\GroupsUsers;

use App\Error\Exception\ValidationException;
use App\Model\Entity\Role;
use App\Service\GroupsUsers\GroupsUsersUpdateService;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\GroupsUserFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Datasource\Exception\RecordNotFoundException;

class GroupsUsersUpdateServiceTest extends AppTestCase
{
    /**
     * @var GroupsUsersAddService
     */
    public $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new GroupsUsersUpdateService();
    }

    /* ******************************************
     * Success scenarios
     ****************************************** */

    /**
     * Grant group manager role to a group member
     *
     * @throws \Exception
     */
    public function testGroupsUsersDeleteServiceSuccess_GrantGroupManagerRole(): void
    {
        [$u1, $u2, $g1] = $this->insertFixture_GroupWithOneManagerAndOneMember();
        $uac = new UserAccessControl(Role::USER, $u1->id);
        $groupUserToUpdate = $g1->groups_users[1];

        $groupUserData = ['is_admin' => true];
        $this->service->update($uac, $groupUserToUpdate->id, $groupUserData);

        $this->assertEquals(2, GroupsUserFactory::count());
        $this->assertUserIsMemberOf($g1->id, $u1->id, true);
        $this->assertUserIsMemberOf($g1->id, $u2->id, true);
    }

    /**
     * Revoke group manager role to a group manager
     *
     * @throws \Exception
     */
    public function testGroupsUsersDeleteServiceSuccess_RevokeGroupManagerRole(): void
    {
        [$u1, $u2, $g1] = $this->insertFixture_GroupWithTwoManagers();
        $uac = new UserAccessControl(Role::USER, $u1->id);
        $groupUserToUpdate = $g1->groups_users[1];

        $groupUserData = ['is_admin' => false];
        $this->service->update($uac, $groupUserToUpdate->id, $groupUserData);

        $this->assertEquals(2, GroupsUserFactory::count());
        $this->assertUserIsMemberOf($g1->id, $u1->id, true);
        $this->assertUserIsMemberOf($g1->id, $u2->id, false);
    }

    /**
     * Update for same group manager role to a group user.
     *
     * @throws \Exception
     */
    public function testGroupsUsersDeleteServiceSuccess_UpdateForSameGroupManagerRole(): void
    {
        [$u1, $u2, $g1] = $this->insertFixture_GroupWithOneManagerAndOneMember();
        $uac = new UserAccessControl(Role::USER, $u1->id);
        $groupUserToUpdate = $g1->groups_users[1];

        $groupUserData = ['is_admin' => false];
        $this->service->update($uac, $groupUserToUpdate->id, $groupUserData);

        $this->assertEquals(2, GroupsUserFactory::count());
        $this->assertUserIsMemberOf($g1->id, $u1->id, true);
        $this->assertUserIsMemberOf($g1->id, $u2->id, false);
    }

    /* ******************************************
     * Error scenarios
     ****************************************** */

    /**
     * Cannot delete a group user if it doesn't exist
     *
     * @throws \Exception
     */
    public function testGroupsUsersDeleteServiceError_GroupUserDoesNotExist(): void
    {
        $u1 = UserFactory::make()->persist();
        $uac = new UserAccessControl(Role::USER, $u1->id);
        $groupUserIdToUpdate = UuidFactory::uuid();
        $groupUserData = ['is_admin' => true];

        try {
            $this->service->update($uac, $groupUserIdToUpdate, $groupUserData);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (\Exception $e) {
            $this->assertInstanceOf(RecordNotFoundException::class, $e);
        }
    }

    /**
     * Cannot delete a revoke group manager role if it is the last group manager.
     *
     * @throws \Exception
     */
    public function testGroupsUsersDeleteServiceError_AtLeastOneGroupManager(): void
    {
        [$u1, $u2, $g1] = $this->insertFixture_GroupWithOneManagerAndOneMember();
        $uac = new UserAccessControl(Role::USER, $u1->id);
        $groupUserToUpdate = $g1->groups_users[0];
        $groupUserData = ['is_admin' => false];

        try {
            $this->service->update($uac, $groupUserToUpdate->id, $groupUserData);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertValidationException($e, 'Cannot update group user.');
        }

        $this->assertEquals(2, GroupsUserFactory::count());
        $this->assertUserIsMemberOf($g1->id, $u1->id, true);
        $this->assertUserIsMemberOf($g1->id, $u2->id, false);
    }

    /* ******************************************
     * Fixtures
     ****************************************** */

    private function insertFixture_GroupWithOneManagerAndOneMember(): array
    {
        $u1 = UserFactory::make()->persist();
        $u2 = UserFactory::make()->persist();
        $g1 = GroupFactory::make()->withGroupsManagersFor([$u1])->withGroupsUsersFor([$u2])->persist();

        return [$u1, $u2, $g1];
    }

    private function insertFixture_GroupWithTwoManagers(): array
    {
        $u1 = UserFactory::make()->persist();
        $u2 = UserFactory::make()->persist();
        $g1 = GroupFactory::make()->withGroupsManagersFor([$u1, $u2])->persist();

        return [$u1, $u2, $g1];
    }
}
