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
use App\Service\GroupsUsers\GroupsUsersDeleteService;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\GroupsUserFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\SecretFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Datasource\Exception\RecordNotFoundException;

class GroupsUsersDeleteServiceTest extends AppTestCase
{
    /**
     * @var GroupsUsersAddService
     */
    public $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new GroupsUsersDeleteService();
    }

    /* ******************************************
     * Success scenarios
     ****************************************** */

    /**
     * Delete a member from a group having one manager and one member.
     *
     * @throws \Exception
     */
    public function testGroupsUsersDeleteServiceSuccess_DeleteGroupMember(): void
    {
        [$u1, $u2, $g1] = $this->insertFixture_GroupWithOneManagerAndOneMember();
        $uac = new UserAccessControl(Role::USER, $u1->id);
        $groupUserToDelete = $g1->groups_users[1];

        $this->service->delete($uac, $groupUserToDelete->id);

        $this->assertEquals(1, GroupsUserFactory::count());
        $this->assertUserIsMemberOf($g1->id, $u1->id, true);
        $this->assertUserIsNotMemberOf($g1->id, $u2->id);
    }

    /**
     * Delete a manager from a group having two managers.
     *
     * @throws \Exception
     */
    public function testGroupsUsersDeleteServiceSuccess_DeleteGroupManager(): void
    {
        [$u1, $u2, $g1] = $this->insertFixture_GroupWithTwoManagers();
        $uac = new UserAccessControl(Role::USER, $u1->id);

        $groupUserToDelete = $g1->groups_users[1];
        $this->service->delete($uac, $groupUserToDelete->id);

        $this->assertEquals(1, GroupsUserFactory::count());
        $this->assertUserIsMemberOf($g1->id, $u1->id, true);
        $this->assertUserIsNotMemberOf($g1->id, $u2->id);
    }

    /**
     * Delete a member from group and the associated data of the unique resource shared with the group the user lost access.
     *
     * @throws \Exception
     */
    public function testGroupsUsersDeleteServiceSuccess_DeleteLostAccessAssociatedData_SingleResources(): void
    {
        [$u1, $u2, $g1, $r1] = $this->insertFixture_GroupWithTwoManagers_OneResourceSharedWithGroup();
        $uac = new UserAccessControl(Role::USER, $u1->id);

        $groupUserToDelete = $g1->groups_users[1];
        $this->service->delete($uac, $groupUserToDelete->id);

        $this->assertEquals(1, GroupsUserFactory::count());
        $this->assertEquals(1, SecretFactory::count());
        $this->assertUserIsMemberOf($g1->id, $u1->id, true);
        $this->assertUserIsNotMemberOf($g1->id, $u2->id);
        $this->assertSecretExists($r1->id, $u1->id);
        $this->assertSecretNotExist($r1->id, $u2->id);
    }

    /**
     * Delete a member from group and the associated data of the multiple resources shared with the group the user lost access.
     *
     * @throws \Exception
     */
    public function testGroupsUsersDeleteServiceSuccess_DeleteLostAccessAssociatedData_MultipleResources(): void
    {
        [$u1, $u2, $g1, $r1, $r2] = $this->insertFixture_GroupWithTwoManagers_MultipleResourcesSharedWithGroup();
        $uac = new UserAccessControl(Role::USER, $u1->id);

        $groupUserToDelete = $g1->groups_users[1];
        $this->service->delete($uac, $groupUserToDelete->id);

        $this->assertEquals(1, GroupsUserFactory::count());
        $this->assertEquals(2, SecretFactory::count());
        $this->assertUserIsMemberOf($g1->id, $u1->id, true);
        $this->assertUserIsNotMemberOf($g1->id, $u2->id);
        $this->assertSecretExists($r1->id, $u1->id);
        $this->assertSecretNotExist($r1->id, $u2->id);
        $this->assertSecretExists($r2->id, $u1->id);
        $this->assertSecretNotExist($r2->id, $u2->id);
    }

    /**
     * Delete a member from group and only the associated data of the resources the user lost access.
     *
     * @throws \Exception
     */
    public function testGroupsUsersDeleteServiceSuccess_DeleteLostAccessAssociatedData_PartialAccessLoss(): void
    {
        [$u1, $u2, $g1, $r1, $r2] = $this->insertFixture_GroupWithTwoManagers_MultipleResourcesSharedWithGroup_OneResourceSharedDirectlyWithUser();
        $uac = new UserAccessControl(Role::USER, $u1->id);

        $groupUserToDelete = $g1->groups_users[1];
        $this->service->delete($uac, $groupUserToDelete->id);

        $this->assertEquals(1, GroupsUserFactory::count());
        $this->assertEquals(3, SecretFactory::count());
        $this->assertUserIsMemberOf($g1->id, $u1->id, true);
        $this->assertUserIsNotMemberOf($g1->id, $u2->id);
        $this->assertSecretExists($r1->id, $u1->id);
        $this->assertSecretNotExist($r1->id, $u2->id);
        $this->assertSecretExists($r2->id, $u1->id);
        $this->assertSecretExists($r2->id, $u2->id);
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
        $groupUserIdToDelete = UuidFactory::uuid();

        try {
            $this->service->delete($uac, $groupUserIdToDelete);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (\Exception $e) {
            $this->assertInstanceOf(RecordNotFoundException::class, $e);
        }
    }

    /**
     * Cannot delete a group user if it is the last group manager.
     *
     * @throws \Exception
     */
    public function testGroupsUsersDeleteServiceError_AtLeastOneGroupManager(): void
    {
        [$u1, $g1] = $this->insertFixture_GroupWithOneManager();
        $uac = new UserAccessControl(Role::USER, $u1->id);
        $groupUserToDelete = $g1->groups_users[0];

        try {
            $this->service->delete($uac, $groupUserToDelete->id);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertValidationException($e, 'Cannot delete group user.', 'is_admin.at_least_one_group_manager');
        }

        $this->assertEquals(1, GroupsUserFactory::count());
        $this->assertEquals(0, SecretFactory::count());
        $this->assertUserIsMemberOf($g1->id, $u1->id, true);
    }

    /* ******************************************
     * Fixtures
     ****************************************** */

    private function insertFixture_GroupWithOneManager(): array
    {
        $u1 = UserFactory::make()->persist();
        $g1 = GroupFactory::make()->withGroupsManagersFor([$u1])->persist();

        return [$u1, $g1];
    }

    private function insertFixture_GroupWithOneManagerAndOneMember(): array
    {
        [$u1, $u2] = UserFactory::make(2)->persist();
        $g1 = GroupFactory::make()->withGroupsManagersFor([$u1])->withGroupsUsersFor([$u2])->persist();

        return [$u1, $u2, $g1];
    }

    private function insertFixture_GroupWithTwoManagers(): array
    {
        [$u1, $u2] = UserFactory::make(2)->persist();
        $g1 = GroupFactory::make()->withGroupsManagersFor([$u1, $u2])->persist();

        return [$u1, $u2, $g1];
    }

    private function insertFixture_GroupWithTwoManagers_OneResourceSharedWithGroup(): array
    {
        [$u1, $u2] = UserFactory::make(2)->persist();
        $g1 = GroupFactory::make()->withGroupsManagersFor([$u1, $u2])->persist();
        $r1 = ResourceFactory::make()->withPermissionsFor([$g1])->withSecretsFor([$u1, $u2])->persist();

        return [$u1, $u2, $g1, $r1];
    }

    private function insertFixture_GroupWithTwoManagers_MultipleResourcesSharedWithGroup(): array
    {
        [$u1, $u2] = UserFactory::make(2)->persist();
        $g1 = GroupFactory::make()->withGroupsManagersFor([$u1, $u2])->persist();
        $r1 = ResourceFactory::make()->withPermissionsFor([$g1])->withSecretsFor([$u1, $u2])->persist();
        $r2 = ResourceFactory::make()->withPermissionsFor([$g1])->withSecretsFor([$u1, $u2])->persist();

        return [$u1, $u2, $g1, $r1, $r2];
    }

    private function insertFixture_GroupWithTwoManagers_MultipleResourcesSharedWithGroup_OneResourceSharedDirectlyWithUser(): array
    {
        [$u1, $u2] = UserFactory::make(2)->persist();
        $g1 = GroupFactory::make()->withGroupsManagersFor([$u1, $u2])->persist();
        $r1 = ResourceFactory::make()->withPermissionsFor([$g1])->withSecretsFor([$u1, $u2])->persist();
        $r2 = ResourceFactory::make()->withPermissionsFor([$g1, $u2])->withSecretsFor([$u1, $u2])->persist();

        return [$u1, $u2, $g1, $r1, $r2];
    }
}
