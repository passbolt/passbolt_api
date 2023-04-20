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
use App\Service\GroupsUsers\GroupsUsersAddService;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\GroupsUserFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\SecretFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Utility\Hash;

class GroupsUsersAddServiceTest extends AppTestCase
{
    /**
     * @var GroupsUsersAddService
     */
    public $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new GroupsUsersAddService();
    }

    /* ******************************************
     * Success scenarios
     ****************************************** */

    /**
     * Add a member to a group having one manager and no password shared with
     *
     * @throws \Exception
     */
    public function testGroupsUsersAddServiceSuccess_AddMemberToGroup(): void
    {
        [$u1, $g1] = $this->insertFixture_GroupWithOneManager();
        $u2 = UserFactory::make()->persist();
        $uac = new UserAccessControl(Role::USER, $u1->id);

        $groupUserData = ['group_id' => $g1->id, 'user_id' => $u2->id];
        $this->service->add($uac, $groupUserData);

        $this->assertEquals(2, GroupsUserFactory::count());
        $this->assertEquals(0, SecretFactory::count());
        $this->assertUserIsMemberOf($g1->id, $u1->id, true);
        $this->assertUserIsMemberOf($g1->id, $u2->id, false);
    }

    /**
     * Add a manager to a group having one manager and no password shared with
     *
     * @throws \Exception
     */
    public function testGroupsUsersAddServiceSuccess_AddManagerToGroup(): void
    {
        [$u1, $g1] = $this->insertFixture_GroupWithOneManager();
        $u2 = UserFactory::make()->persist();
        $uac = new UserAccessControl(Role::USER, $u1->id);

        $groupUserData = ['group_id' => $g1->id, 'user_id' => $u2->id, 'is_admin' => true];
        $this->service->add($uac, $groupUserData);

        $this->assertEquals(2, GroupsUserFactory::count());
        $this->assertEquals(0, SecretFactory::count());
        $this->assertUserIsMemberOf($g1->id, $u1->id, true);
        $this->assertUserIsMemberOf($g1->id, $u2->id, true);
    }

    /**
     * Add a member to a group having one manager and one password shared with
     *
     * @throws \Exception
     */
    public function testGroupsUsersAddServiceSuccess_AddMemberToGroupHavingSinglePassword(): void
    {
        [$u1, $g1, $r1] = $this->insertFixture_GroupWithOneManager_OneResourceSharedWithGroup();
        $u2 = UserFactory::make()->persist();
        $uac = new UserAccessControl(Role::USER, $u1->id);

        $groupUserData = ['group_id' => $g1->id, 'user_id' => $u2->id];
        $secretsData = [['resource_id' => $r1->id, 'user_id' => $u2->id, 'data' => Hash::get($this->getDummySecretData(), 'data')]];
        $this->service->add($uac, $groupUserData, $secretsData);

        $this->assertEquals(2, GroupsUserFactory::count());
        $this->assertEquals(2, SecretFactory::count());
        $this->assertUserIsMemberOf($g1->id, $u1->id, true);
        $this->assertUserIsMemberOf($g1->id, $u2->id, false);
        $this->assertSecretExists($r1->id, $u1->id);
        $this->assertSecretExists($r1->id, $u2->id);
    }

    /**
     * Add a member to a group having one manager and multiple passwords shared with
     *
     * @throws \Exception
     */
    public function testGroupsUsersAddServiceSuccess_AddMemberToGroupHavingMultiplePasswords(): void
    {
        [$u1, $g1, $r1, $r2] = $this->insertFixture_GroupWithOneManager_MultipleResourcesSharedWithGroup();
        $u2 = UserFactory::make()->persist();
        $uac = new UserAccessControl(Role::USER, $u1->id);

        $groupUserData = ['group_id' => $g1->id, 'user_id' => $u2->id];
        $secretsData = [
            ['resource_id' => $r1->id, 'user_id' => $u2->id, 'data' => Hash::get($this->getDummySecretData(), 'data')],
            ['resource_id' => $r2->id, 'user_id' => $u2->id, 'data' => Hash::get($this->getDummySecretData(), 'data')],
        ];
        $this->service->add($uac, $groupUserData, $secretsData);

        $this->assertEquals(2, GroupsUserFactory::count());
        $this->assertEquals(4, SecretFactory::count());
        $this->assertUserIsMemberOf($g1->id, $u1->id, true);
        $this->assertUserIsMemberOf($g1->id, $u2->id, false);
        $this->assertSecretExists($r1->id, $u1->id);
        $this->assertSecretExists($r1->id, $u2->id);
        $this->assertSecretExists($r2->id, $u1->id);
        $this->assertSecretExists($r2->id, $u2->id);
    }

    /**
     * Add a member having access to one password shared with a group to a group having one manager and multiple passwords shared with
     *
     * @throws \Exception
     */
    public function testGroupsUsersAddServiceSuccess_AddMemberHavingAlreadyAccessToOnePasswordToGroupHavingMultiplePasswords(): void
    {
        [$u1, $u2, $g1, $r1, $r2] = $this->insertFixture_GroupWithOneManager_MultipleResourcesSharedWithGroup_OneUserHavingAlreadyAccessToOnePassword();
        $uac = new UserAccessControl(Role::USER, $u1->id);

        $groupUserData = ['group_id' => $g1->id, 'user_id' => $u2->id];
        $secretsData = [
            ['resource_id' => $r2->id, 'user_id' => $u2->id, 'data' => Hash::get($this->getDummySecretData(), 'data')],
        ];
        $this->service->add($uac, $groupUserData, $secretsData);

        $this->assertEquals(2, GroupsUserFactory::count());
        $this->assertEquals(4, SecretFactory::count());
        $this->assertUserIsMemberOf($g1->id, $u1->id, true);
        $this->assertUserIsMemberOf($g1->id, $u2->id, false);
        $this->assertSecretExists($r1->id, $u1->id);
        $this->assertSecretExists($r1->id, $u2->id);
        $this->assertSecretExists($r2->id, $u1->id);
        $this->assertSecretExists($r2->id, $u2->id);
    }

    /**
     * Add a member having access to all passwords shared with a group to a group having one manager and multiple passwords shared with
     *
     * @throws \Exception
     */
    public function testGroupsUsersAddServiceSuccess_AddMemberHavingAlreadyAccessToAllPasswordsToGroupHavingMultiplePasswords(): void
    {
        [$u1, $u2, $g1, $r1, $r2] = $this->insertFixture_GroupWithOneManager_MultipleResourcesSharedWithGroup_OneUserHavingAlreadyAccessToAllPasswords();
        $uac = new UserAccessControl(Role::USER, $u1->id);

        $groupUserData = ['group_id' => $g1->id, 'user_id' => $u2->id];
        $secretsData = [];
        $this->service->add($uac, $groupUserData, $secretsData);

        $this->assertEquals(2, GroupsUserFactory::count());
        $this->assertEquals(4, SecretFactory::count());
        $this->assertUserIsMemberOf($g1->id, $u1->id, true);
        $this->assertUserIsMemberOf($g1->id, $u2->id, false);
        $this->assertSecretExists($r1->id, $u1->id);
        $this->assertSecretExists($r1->id, $u2->id);
        $this->assertSecretExists($r2->id, $u1->id);
        $this->assertSecretExists($r2->id, $u2->id);
    }

    /* ******************************************
     * Error scenarios
     ****************************************** */

    /**
     * Cannot add a member if the provided group user data are not valid.
     * It should trigger the groups users table validation.
     *
     * @throws \Exception
     */
    public function testGroupsUsersAddServiceError_InvalidGroupUserData_FieldsValidation(): void
    {
        [$u1, $g1] = $this->insertFixture_GroupWithOneManager();
        $uac = new UserAccessControl(Role::USER, $u1->id);
        $u2Id = 'not-valid-id';

        $groupUserData = ['group_id' => 'not-valid-id', 'user_id' => $u2Id, 'is_admin' => 42];

        try {
            $this->service->add($uac, $groupUserData);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertValidationException($e, 'Could not validate group user data.', 'group_id.uuid');
            $this->assertValidationException($e, 'Could not validate group user data.', 'user_id.uuid');
            $this->assertValidationException($e, 'Could not validate group user data.', 'is_admin.boolean');
        }

        $this->assertEquals(1, GroupsUserFactory::count());
        $this->assertEquals(0, SecretFactory::count());
        $this->assertUserIsMemberOf($g1->id, $u1->id, true);
    }

    /**
     * Cannot add a member if the provided group user data are not valid.
     * It should trigger the groups users table build rules.
     *
     * @throws \Exception
     */
    public function testGroupsUsersAddServiceError_InvalidGroupUserData_BuildRules(): void
    {
        [$u1, $g1] = $this->insertFixture_GroupWithOneManager();
        $uac = new UserAccessControl(Role::USER, $u1->id);
        $u2Id = UuidFactory::uuid();

        $groupUserData = ['group_id' => $g1->id, 'user_id' => $u2Id];

        try {
            $this->service->add($uac, $groupUserData);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertValidationException($e, 'Could not validate group user data.', 'user_id.user_exists');
        }

        $this->assertEquals(1, GroupsUserFactory::count());
        $this->assertEquals(0, SecretFactory::count());
        $this->assertUserIsMemberOf($g1->id, $u1->id, true);
        $this->assertUserIsNotMemberOf($g1->id, $u2Id);
    }

    /**
     * Cannot add a member if the provided group user data are not valid.
     * It should trigger the groups users table validation.
     *
     * @throws \Exception
     */
    public function testGroupsUsersAddServiceError_InvalidSecretData_FieldsValidation(): void
    {
        [$u1, $g1, $r1] = $this->insertFixture_GroupWithOneManager_OneResourceSharedWithGroup();
        $uac = new UserAccessControl(Role::USER, $u1->id);
        $u2 = UserFactory::make()->persist();

        $groupUserData = ['group_id' => $g1->id, 'user_id' => $u2->id];
        $secretsData = [
            ['resource_id' => $r1->id, 'user_id' => $u2->id, 'data' => true],
        ];

        try {
            $this->service->add($uac, $groupUserData, $secretsData);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertValidationException($e, 'Could not validate group user data.', 'secrets.0.data.ascii');
        }

        $this->assertEquals(1, GroupsUserFactory::count());
        $this->assertEquals(1, SecretFactory::count());
        $this->assertUserIsMemberOf($g1->id, $u1->id, true);
        $this->assertUserIsNotMemberOf($g1->id, $u2->id);
    }

    /**
     * Cannot add a member if all the secrets for the new resources the user is getting access are not provided.
     *
     * @throws \Exception
     */
    public function testGroupsUsersAddServiceError_MissingSecrets(): void
    {
        [$u1, $g1] = $this->insertFixture_GroupWithOneManager_OneResourceSharedWithGroup();
        $uac = new UserAccessControl(Role::USER, $u1->id);
        $u2 = UserFactory::make()->persist();

        $groupUserData = ['group_id' => $g1->id, 'user_id' => $u2->id];

        try {
            $this->service->add($uac, $groupUserData);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertValidationException($e, 'Could not validate group user data.', 'secrets.all_missing');
        }

        $this->assertEquals(1, GroupsUserFactory::count());
        $this->assertEquals(1, SecretFactory::count());
        $this->assertUserIsMemberOf($g1->id, $u1->id, true);
        $this->assertUserIsNotMemberOf($g1->id, $u2->id);
    }

    /**
     * Cannot add a member if too many secrets for the new resources the user is getting access are provided.
     *
     * @throws \Exception
     */
    public function testGroupsUsersAddServiceError_TooManySecrets(): void
    {
        [$u1, $g1, $r1] = $this->insertFixture_GroupWithOneManager_OneResourceSharedWithGroup();
        $uac = new UserAccessControl(Role::USER, $u1->id);
        $u2 = UserFactory::make()->persist();

        $groupUserData = ['group_id' => $g1->id, 'user_id' => $u2->id];
        $secretsData = [
            ['resource_id' => $r1->id, 'user_id' => $u2->id, 'data' => Hash::get($this->getDummySecretData(), 'data')],
            ['resource_id' => $r1->id, 'user_id' => $u2->id, 'data' => Hash::get($this->getDummySecretData(), 'data')],
        ];

        try {
            $this->service->add($uac, $groupUserData, $secretsData);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertValidationException($e, 'Could not validate group user data.', 'secrets.all_missing_only');
        }

        $this->assertEquals(1, GroupsUserFactory::count());
        $this->assertEquals(1, SecretFactory::count());
        $this->assertUserIsMemberOf($g1->id, $u1->id, true);
        $this->assertUserIsNotMemberOf($g1->id, $u2->id);
    }

    /**
     * Cannot add a member if secrets for resources the user is not getting access via the group are provided
     *
     * @throws \Exception
     */
    public function testGroupsUsersAddServiceError_SecretForResourcesUserHasNoAccessProvided(): void
    {
        [$u1, $g1, $r1] = $this->insertFixture_GroupWithOneManager_OneResourceSharedWithGroup();
        $uac = new UserAccessControl(Role::USER, $u1->id);
        $u2 = UserFactory::make()->persist();
        $r2 = ResourceFactory::make()->withPermissionsFor([$u1])->withSecretsFor([$u1])->persist();

        $groupUserData = ['group_id' => $g1->id, 'user_id' => $u2->id];
        $secretsData = [
            ['resource_id' => $r1->id, 'user_id' => $u2->id, 'data' => Hash::get($this->getDummySecretData(), 'data')],
            ['resource_id' => $r2->id, 'user_id' => $u2->id, 'data' => Hash::get($this->getDummySecretData(), 'data')],
        ];

        try {
            $this->service->add($uac, $groupUserData, $secretsData);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertValidationException($e, 'Could not validate group user data.', 'secrets.1.resource_id.only_missing');
        }

        $this->assertEquals(1, GroupsUserFactory::count());
        $this->assertEquals(2, SecretFactory::count());
        $this->assertUserIsMemberOf($g1->id, $u1->id, true);
        $this->assertUserIsNotMemberOf($g1->id, $u2->id);
    }

    /**
     * Cannot add a member if secrets for resources the user has already gotten access are provided.
     *
     * @throws \Exception
     */
    public function testGroupsUsersAddServiceError_SecretsForResourcesUserHasAlreadyAccessProvided(): void
    {
        [$u1, $g1, $r1] = $this->insertFixture_GroupWithOneManager_OneResourceSharedWithGroup();
        $uac = new UserAccessControl(Role::USER, $u1->id);
        $u2 = UserFactory::make()->persist();
        $r2 = ResourceFactory::make()->withPermissionsFor([$g1, $u2])->withSecretsFor([$u1, $u2])->persist();

        $groupUserData = ['group_id' => $g1->id, 'user_id' => $u2->id];
        $secretsData = [
            ['resource_id' => $r1->id, 'user_id' => $u2->id, 'data' => Hash::get($this->getDummySecretData(), 'data')],
            ['resource_id' => $r2->id, 'user_id' => $u2->id, 'data' => Hash::get($this->getDummySecretData(), 'data')],
        ];

        try {
            $this->service->add($uac, $groupUserData, $secretsData);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertValidationException($e, 'Could not validate group user data.', 'secrets.1.resource_id.only_missing');
        }

        $this->assertEquals(1, GroupsUserFactory::count());
        $this->assertEquals(3, SecretFactory::count());
        $this->assertUserIsMemberOf($g1->id, $u1->id, true);
        $this->assertUserIsNotMemberOf($g1->id, $u2->id);
    }

    /**
     * Cannot add a member if secrets for another user are provided.
     *
     * @throws \Exception
     */
    public function testGroupsUsersAddServiceError_SecretsForAnotherUserProvided(): void
    {
        [$u1, $g1, $r1] = $this->insertFixture_GroupWithOneManager_OneResourceSharedWithGroup();
        $uac = new UserAccessControl(Role::USER, $u1->id);
        $u2 = UserFactory::make()->persist();
        $u3 = UserFactory::make()->persist();

        $groupUserData = ['group_id' => $g1->id, 'user_id' => $u2->id];
        $secretsData = [
            ['resource_id' => $r1->id, 'user_id' => $u2->id, 'data' => Hash::get($this->getDummySecretData(), 'data')],
            ['resource_id' => $r1->id, 'user_id' => $u3->id, 'data' => Hash::get($this->getDummySecretData(), 'data')],
        ];

        try {
            $this->service->add($uac, $groupUserData, $secretsData);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertValidationException($e, 'Could not validate group user data.', 'secrets.1.user_id.same_user');
        }

        $this->assertEquals(1, GroupsUserFactory::count());
        $this->assertEquals(1, SecretFactory::count());
        $this->assertUserIsMemberOf($g1->id, $u1->id, true);
        $this->assertUserIsNotMemberOf($g1->id, $u2->id);
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

    private function insertFixture_GroupWithOneManager_OneResourceSharedWithGroup(): array
    {
        $u1 = UserFactory::make()->persist();
        $g1 = GroupFactory::make()->withGroupsManagersFor([$u1])->persist();
        $r1 = ResourceFactory::make()->withPermissionsFor([$g1])->withSecretsFor([$u1])->persist();

        return [$u1, $g1, $r1];
    }

    private function insertFixture_GroupWithOneManager_MultipleResourcesSharedWithGroup(): array
    {
        $u1 = UserFactory::make()->persist();
        $g1 = GroupFactory::make()->withGroupsManagersFor([$u1])->persist();
        $r1 = ResourceFactory::make()->withPermissionsFor([$g1])->withSecretsFor([$u1])->persist();
        $r2 = ResourceFactory::make()->withPermissionsFor([$g1])->withSecretsFor([$u1])->persist();

        return [$u1, $g1, $r1, $r2];
    }

    private function insertFixture_GroupWithOneManager_MultipleResourcesSharedWithGroup_OneUserHavingAlreadyAccessToOnePassword(): array
    {
        $u1 = UserFactory::make()->persist();
        $u2 = UserFactory::make()->persist();
        $g1 = GroupFactory::make()->withGroupsManagersFor([$u1])->persist();
        $r1 = ResourceFactory::make()->withPermissionsFor([$g1, $u2])->withSecretsFor([$u1, $u2])->persist();
        $r2 = ResourceFactory::make()->withPermissionsFor([$g1])->withSecretsFor([$u1])->persist();

        return [$u1, $u2, $g1, $r1, $r2];
    }

    private function insertFixture_GroupWithOneManager_MultipleResourcesSharedWithGroup_OneUserHavingAlreadyAccessToAllPasswords(): array
    {
        $u1 = UserFactory::make()->persist();
        $u2 = UserFactory::make()->persist();
        $g1 = GroupFactory::make()->withGroupsManagersFor([$u1])->persist();
        $r1 = ResourceFactory::make()->withPermissionsFor([$g1, $u2])->withSecretsFor([$u1, $u2])->persist();
        $r2 = ResourceFactory::make()->withPermissionsFor([$g1, $u2])->withSecretsFor([$u1, $u2])->persist();

        return [$u1, $u2, $g1, $r1, $r2];
    }
}
