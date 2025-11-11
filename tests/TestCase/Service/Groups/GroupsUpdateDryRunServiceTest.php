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

namespace App\Test\TestCase\Service\Groups;

use App\Error\Exception\ValidationException;
use App\Service\Groups\GroupsUpdateDryRunService;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\SecretFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\UserAccessControlTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

/**
 * \App\Service\Groups\GroupsUpdateDryRunService Test Case
 *
 * @covers \App\Service\Groups\GroupsUpdateDryRunService
 */
class GroupsUpdateDryRunServiceTest extends AppTestCase
{
    use UserAccessControlTrait;

    /**
     * @var \App\Model\Table\GroupsUsersTable
     */
    private $groupsUsersTable;

    /**
     * @var \App\Model\Table\SecretsTable
     */
    private $secretsTable;

    /**
     * @var GroupsUpdateDryRunService
     */
    private $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->groupsUsersTable = TableRegistry::getTableLocator()->get('GroupsUsers');
        $this->secretsTable = TableRegistry::getTableLocator()->get('Secrets');
        $this->service = new GroupsUpdateDryRunService();
    }

    /* ADD/UPDATE/DELETE GROUP USER */

    public function testUpdateDryRunSuccess_AddUpdateRemoveGroupUsers_HavingMultipleResourcesSharedWith()
    {
        [$userA, $userB, $userC, $userD] = UserFactory::make(4)->persist();
        $g1 = GroupFactory::make()->withGroupsManagersFor([$userA, $userB])->persist();
        [$r1, $r2] = ResourceFactory::make(2)
            ->withPermissionsFor([$userA, $g1])
            ->withSecretsFor([$userA, $g1])
            ->persist();
        $r3 = ResourceFactory::make()->withPermissionsFor([$g1])->persist();
        // Additional secret to be ignored as it is soft deleted
        SecretFactory::make()
            ->deleted()
            ->with('Resources', $r3)
            ->with('Users', $userA)
            ->persist();

        $userAGroupUserId = $g1->groups_users[0]['id'];
        $userBGroupUserId = $g1->groups_users[1]['id'];
        $data = [
            ['id' => $userAGroupUserId, 'delete' => true],
            ['id' => $userBGroupUserId, 'is_admin' => false],
            ['user_id' => $userC->id, 'is_admin' => true],
            ['user_id' => $userD->id, 'is_admin' => false],
        ];

        $result = $this->service->dryRun($this->makeUac($userA), $g1->id, $data);

        // Assert the changes have not been applied.
        $this->assertUserIsMemberOf($g1->id, $userA->id, true);
        $this->assertUserIsMemberOf($g1->id, $userB->id, true);
        $this->assertUserIsNotMemberOf($g1->id, $userC->id);
        $this->assertUserIsNotMemberOf($g1->id, $userD->id);

        // Assert the service result.
        $operatorSecret = $this->secretsTable->findByResourceIdAndUserId($r1->id, $userA->id)->first();
        $this->assertCount(2, $result['secrets']);
        $this->assertEquals($operatorSecret->data, $result['secrets'][0]['data']);
        $this->assertEquals($operatorSecret->data, $result['secrets'][1]['data']);
        $this->assertCount(6, $result['secretsNeeded']);
        $this->assertContains(['resource_id' => $r1->id, 'user_id' => $userC->id], $result['secretsNeeded']);
        $this->assertContains(['resource_id' => $r1->id, 'user_id' => $userD->id,], $result['secretsNeeded']);
        $this->assertContains(['resource_id' => $r2->id, 'user_id' => $userC->id,], $result['secretsNeeded']);
        $this->assertContains(['resource_id' => $r2->id, 'user_id' => $userD->id,], $result['secretsNeeded']);
    }

    /* ADD GROUP USER */

    public function testUpdateDryRunSuccess_AddGroupUser_HavingOneResourceSharedWith()
    {
        [$userA, $userB, $userC, $userD] = UserFactory::make(4)->persist();
        $g1 = GroupFactory::make()
            ->withGroupsManagersFor([$userA])
            ->withGroupsUsersFor([$userB])
            ->persist();
        $r1 = ResourceFactory::make()
            ->withPermissionsFor([$userA, $g1])
            ->withSecretsFor([$userA, $g1])
            ->persist();

        $data = [
            ['user_id' => $userC->id, 'is_admin' => true],
            ['user_id' => $userD->id, 'is_admin' => false],
        ];

        $result = $this->service->dryRun($this->makeUac($userA), $g1->id, $data);

        // Assert the changes have not been applied.
        $this->assertUserIsMemberOf($g1->id, $userA->id, true);
        $this->assertUserIsMemberOf($g1->id, $userB->id, false);
        $this->assertUserIsNotMemberOf($g1->id, $userC->id);
        $this->assertUserIsNotMemberOf($g1->id, $userD->id);

        // Assert the service result.
        $operatorSecret = $this->secretsTable->findByResourceIdAndUserId($r1->id, $userA->id)->first();
        $this->assertCount(1, $result['secrets']);
        $this->assertEquals($operatorSecret->data, $result['secrets'][0]['data']);
        $this->assertCount(2, $result['secretsNeeded']);
        $this->assertContains(['resource_id' => $r1->id, 'user_id' => $userC->id], $result['secretsNeeded']);
        $this->assertContains(['resource_id' => $r1->id, 'user_id' => $userD->id], $result['secretsNeeded']);
    }

    public function testUpdateDryRunSuccess_AddGroupUser_HavingMultipleResourceSharedWith()
    {
        [$userA, $userB, $userC, $userD] = UserFactory::make(4)->persist();
        $g1 = GroupFactory::make()
            ->withGroupsManagersFor([$userA])
            ->withGroupsUsersFor([$userB])
            ->persist();
        [$r1, $r2] = ResourceFactory::make(2)
            ->withPermissionsFor([$userA, $g1])
            ->withSecretsFor([$userA, $g1])
            ->persist();

        $data = [
            ['user_id' => $userC->id, 'is_admin' => true],
            ['user_id' => $userD->id, 'is_admin' => false],
        ];

        $result = $this->service->dryRun($this->makeUac($userA), $g1->id, $data);

        // Assert the changes have not been applied.
        $this->assertUserIsMemberOf($g1->id, $userA->id, true);
        $this->assertUserIsMemberOf($g1->id, $userB->id, false);
        $this->assertUserIsNotMemberOf($g1->id, $userC->id);
        $this->assertUserIsNotMemberOf($g1->id, $userD->id);

        // Assert the service result.
        $operatorSecret = $this->secretsTable->findByResourceIdAndUserId($r1->id, $userA->id)->first();
        $this->assertCount(2, $result['secrets']);
        $this->assertEquals($operatorSecret->data, $result['secrets'][0]['data']);
        $this->assertEquals($operatorSecret->data, $result['secrets'][1]['data']);
        $this->assertCount(4, $result['secretsNeeded']);
        $this->assertContains(['resource_id' => $r1->id, 'user_id' => $userC->id], $result['secretsNeeded']);
        $this->assertContains(['resource_id' => $r1->id, 'user_id' => $userD->id], $result['secretsNeeded']);
        $this->assertContains(['resource_id' => $r2->id, 'user_id' => $userC->id], $result['secretsNeeded']);
        $this->assertContains(['resource_id' => $r2->id, 'user_id' => $userD->id], $result['secretsNeeded']);
    }

    public function testUpdateDryRunSuccess_AddGroupUser_UserHasAlreadyAccessToTheResource()
    {
        [$userA, $userB] = UserFactory::make(2)->persist();
        $g1 = GroupFactory::make()->withGroupsManagersFor([$userA])->persist();
        ResourceFactory::make()
            ->withPermissionsFor([$userB, $g1])
            ->withSecretsFor([$userB, $g1])
            ->persist();

        $data = [
            ['user_id' => $userB->id, 'is_admin' => true],
        ];

        $result = $this->service->dryRun($this->makeUac($userA), $g1->id, $data);

        // Assert the changes have not been applied.
        $this->assertUserIsMemberOf($g1->id, $userA->id, true);
        $this->assertUserIsNotMemberOf($g1->id, $userB->id);

        // Assert the service result.
        $this->assertCount(0, $result['secrets']);
        $this->assertCount(0, $result['secretsNeeded']);
    }

    public function testUpdateDryRunSuccess_AddGroupUser_Disabled()
    {
        [$user1, $user2] = UserFactory::make(2)->disabled()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$user1])->persist();
        $data = [
            ['user_id' => $user2->id, 'is_admin' => false],
        ];

        $result = $this->service->dryRun($this->makeUac($user1), $group->id, $data);

        // Assert the service result.
        $this->assertCount(0, $result['secrets']);
        $this->assertCount(0, $result['secretsNeeded']);
    }

    public function testUpdateDryRunError_AddGroupUser_GroupUserValidation()
    {
        [$userA, $userB, $userC] = UserFactory::make(3)->persist();
        $g1 = GroupFactory::make()
            ->withGroupsManagersFor([$userA])
            ->withGroupsUsersFor([$userB])
            ->persist();

        $data = [
            ['user_id' => $userC->id, 'is_admin' => 42],
        ];

        try {
            $this->service->dryRun($this->makeUac($userA), $g1->id, $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertUpdateDryRunValidationException($e, 'groups_users.0.is_admin.boolean');
        }

        $this->assertUserIsMemberOf($g1->id, $userA->id, true);
        $this->assertUserIsMemberOf($g1->id, $userB->id, false);
        $this->assertUserIsNotMemberOf($g1->id, $userC->id);
    }

    private function assertUpdateDryRunValidationException(ValidationException $e, string $errorFieldName)
    {
        $this->assertEquals('Could not validate group data.', $e->getMessage());
        $error = Hash::get($e->getErrors(), $errorFieldName);
        $this->assertNotNull($error, "Expected error not found : {$errorFieldName}. Errors: " . json_encode($e->getErrors()));
    }

    public function testUpdateDryRunError_AddGroupUser_GroupUserBuildRuleValidation_UserNotExist()
    {
        [$userA, $userB, $userC] = UserFactory::make(3)->persist();
        $g1 = GroupFactory::make()
            ->withGroupsManagersFor([$userA])
            ->withGroupsUsersFor([$userB])
            ->persist();

        $userNotExistId = UuidFactory::uuid();
        $data = [
            ['user_id' => $userC->id, 'is_admin' => true],
            ['user_id' => $userNotExistId, 'is_admin' => false],
        ];

        try {
            $this->service->dryRun($this->makeUac($userA), $g1->id, $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertUpdateDryRunValidationException($e, 'groups_users.1.user_id.user_exists');
        }

        $this->assertUserIsMemberOf($g1->id, $userA->id, true);
        $this->assertUserIsMemberOf($g1->id, $userB->id, false);
        $this->assertUserIsNotMemberOf($g1->id, $userC->id);
        $this->assertUserIsNotMemberOf($g1->id, $userNotExistId);
    }

    /* UPDATE GROUP USER */

    public function testUpdateDryRunSuccess_UpdateGroupUser()
    {
        [$userA, $userB] = UserFactory::make(2)->persist();
        $g1 = GroupFactory::make()
            ->withGroupsManagersFor([$userA])
            ->withGroupsUsersFor([$userB])
            ->persist();
        ResourceFactory::make()
            ->withPermissionsFor([$userA, $g1])
            ->withSecretsFor([$userA, $g1])
            ->persist();

        $userAGroupUserId = $g1->groups_users[0]['id'];
        $userBGroupUserId = $g1->groups_users[1]['id'];
        $data = [
            ['id' => $userAGroupUserId, 'is_admin' => false],
            ['id' => $userBGroupUserId, 'is_admin' => true],
        ];
        $result = $this->service->dryRun($this->makeUac($userA), $g1->id, $data);

        $this->assertCount(0, $result['secrets']);
        $this->assertCount(0, $result['secretsNeeded']);

        $this->assertUserIsMemberOf($g1->id, $userA->id, true);
        $this->assertUserIsMemberOf($g1->id, $userB->id, false);
    }

    public function testUpdateDryRunError_UpdateGroupUser_GroupUserValidation()
    {
        [$userA, $userB] = UserFactory::make(2)->persist();
        $g1 = GroupFactory::make()
            ->withGroupsManagersFor([$userA])
            ->withGroupsUsersFor([$userB])
            ->persist();
        ResourceFactory::make()
            ->withPermissionsFor([$userA, $g1])
            ->withSecretsFor([$userA, $g1])
            ->persist();

        $userAGroupUserId = $g1->groups_users[0]['id'];
        $data = [
            ['id' => $userAGroupUserId, 'is_admin' => 42],
        ];
        try {
            $this->service->dryRun($this->makeUac($userA), $g1->id, $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertUpdateDryRunValidationException($e, 'groups_users.0.is_admin.boolean');
        }
    }

    /* DELETE GROUP USER */

    public function testUpdateDryRunSuccess_DeleteGroupUser()
    {
        [$userA, $userB, $userC] = UserFactory::make(3)->persist();
        $g1 = GroupFactory::make()
            ->withGroupsManagersFor([$userA, $userB])
            ->withGroupsUsersFor([$userC])
            ->persist();
        ResourceFactory::make()
            ->withPermissionsFor([$userA, $g1])
            ->withSecretsFor([$userA, $g1])
            ->persist();

        $userBGroupUserId = $g1->groups_users[1]['id'];
        $userCGroupUserId = $g1->groups_users[2]['id'];
        $data = [
            ['id' => $userBGroupUserId, 'delete' => true],
            ['id' => $userCGroupUserId, 'delete' => true],
        ];
        $result = $this->service->dryRun($this->makeUac($userA), $g1->id, $data);

        $this->assertCount(0, $result['secrets']);
        $this->assertCount(0, $result['secretsNeeded']);

        $this->assertUserIsMemberOf($g1->id, $userA->id, true);
        $this->assertUserIsMemberOf($g1->id, $userB->id, true);
        $this->assertUserIsMemberOf($g1->id, $userC->id, false);
    }
}
