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
 * @since         4.9.0
 */

namespace App\Test\TestCase\Controller\Groups;

use App\Model\Entity\Permission;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\PermissionFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class GroupsUpdateDryRunControllerWithFactoriesTest extends AppIntegrationTestCase
{
    /**
     * @var \App\Model\Table\GroupsTable
     */
    protected $Groups;

    /**
     * @var \App\Model\Table\ResourcesTable
     */
    protected $Resources;

    public function setUp(): void
    {
        parent::setUp();
        $this->Groups = TableRegistry::getTableLocator()->get('Groups');
        $this->Resources = TableRegistry::getTableLocator()->get('Resources');
    }

    /*
     * Tested scenarios :
     *
     * - Update membership type of users
     *   => expected result: no secret are requested for deletion or encryption
     *
     * - Remove a user who will lose access to all the resources a group has access
     *   => expected result: all the secrets of the resources the group has access should be requested for deletion
     *
     * - Remove a user who will lose access to the resources a group has access, except the ones the user has direct
     *   access or inherits access from another group
     *   => expected result: only the secrets the user does not have access through another source should be requested
     *      for deletion
     *
     * - Add a user who will gain access to the resources a group has access
     *   => expected result: all the secrets of the resources the group has access should be requested for encryption
     *
     * - Add a user who will gain access to the resources a group has access, a user who already has access to all the
     *   resources the group has access
     *   => expected result: no secret are requested for encryption
     *
     * - Add a user who will gain access to the resources a group has access, a user who already has access to some of
     *   the resources the group has access
     *   => expected result: only the secrets the user does not have already access through another source should be
     *      requested for encryption
     */

    public function testGroupsUpdateDryRunController_Success_AsGroupManager(): void
    {
        // Define actors of this tests
        [$userJ, $userN, $userK, $userL] = UserFactory::make(4)->persist();
        $group = GroupFactory::make()
            ->withGroupsManagersFor([$userJ])
            ->withGroupsUsersFor([$userN, $userK, $userL])
            ->persist();
        [$userA, $userC, $userF] = UserFactory::make(3)->persist();

        [$resourceC, $resourceF, $resourceG] = ResourceFactory::make(3)->withPermissionsFor([$group, $userC], Permission::UPDATE)
            ->withSecretsFor([$group, $userC])
            ->persist();
        PermissionFactory::make()
            ->acoResource($resourceC)
            ->aroUser($userL)
            ->typeOwner()
            ->persist();

        // Ids
        $groupId = $group->id;
        $groupUserJId = $group->groups_users[0]->id;
        $groupUserNId = $group->groups_users[1]->id;
        $groupUserKId = $group->groups_users[2]->id;
        $groupUserLId = $group->groups_users[3]->id;

        // Retrieve the resources the group has access.
        $resources = $this->Resources->findAllByGroupAccess($groupId)->all()->toArray();
        $groupHasAccess = Hash::extract($resources, '{n}.id');

        // Build the request data.
        $changes = [];

        // Update memberships.
        // Remove userJ as admin
        $changes[] = ['id' => $groupUserJId, 'is_admin' => false];
        // Make userN admin
        $changes[] = ['id' => $groupUserNId, 'is_admin' => true];

        // Remove users from the group
        // Remove userK who has access to the group resources only because of her membership.
        $changes[] = ['id' => $groupUserKId, 'delete' => true];

        // Remove a user who has its own access to the same resource the group has.
        // Remove userL who has direct access to the resource chai.
        $changes[] = ['id' => $groupUserLId, 'delete' => true];

        // Add a user who has not access to the group resources before adding it to the group.
        // Add userF.
        $changes[] = ['user_id' => $userF->id];

        // Add a user who already has access to all the resources the group has access.
        // userC has the same access as the group Freelancer.
        // No secret need to be encrypted for the user.
        $changes[] = ['user_id' => $userC->id];

        // Add a user who already has access to some of the resources the group has access.
        // Ada already has access to few resources the group has access : resourceC, resourceG, resourceF
        // Expect the secrets userA had no access to be encrypted.
        $changes[] = ['user_id' => $userA->id];

        // Update the group users.
        $this->logInAs($userJ);
        $this->putJson("/groups/$groupId/dry-run.json", ['groups_users' => $changes]);

        $this->assertSuccess();
        $result = $this->getResponseBodyAsArray();
        $this->assertNotEmpty($result);

        // Extract from the result the secrets to add and the secrets of the current users that will be used to encrypt
        // the secret of the new users.
        $this->assertNotEmpty($result['dry-run']);
        $this->assertNotEmpty($result['dry-run']['SecretsNeeded']);
        $secretsToAdd = $result['dry-run']['SecretsNeeded'];
        $this->assertNotEmpty($result['dry-run']['Secrets']);
        $secretsToEncrypt = $result['dry-run']['Secrets'];

        // Assert that all the secrets that need to be added are in the result.
        $expectedSecretsToAdd = [];

        // Membership of userJ and userN are changed. Nothing expected.
        // userK should not have anymore access to the group resources. Nothing expected.
        // userL should not have anymore access to the group resources (except resourceC). Nothing expected.
        // userF should have access to the group resources.
        foreach ($groupHasAccess as $resourceId) {
            $expectedSecretsToAdd[] = ['resource_id' => $resourceId, 'user_id' => $userF->id];
        }
        // userC already have access to the resources, nothing expected.
        // userA should have access to the group resources.
        $userHasNotAccess = [$resourceC->id, $resourceF->id, $resourceG->id];
        foreach ($userHasNotAccess as $resourceId) {
            $expectedSecretsToAdd[] = ['resource_id' => $resourceId, 'user_id' => $userA->id];
        }

        // Assert the added secrets are as expected.
        $this->assertCount(count($expectedSecretsToAdd), $secretsToAdd);
        foreach ($expectedSecretsToAdd as $secret) {
            $extract = Hash::extract($secretsToAdd, "{n}.Secret[user_id={$secret['user_id']}][resource_id={$secret['resource_id']}]");
            $this->assertNotEmpty($extract);
        }

        // Assert that all the secrets that will be used to encrypt the secret to add are in the result.
        $expectedSecretsToEncryptIds = Hash::extract($secretsToAdd, '{n}.Secret.resource_id');
        $expectedSecretsToEncryptIds = array_unique($expectedSecretsToEncryptIds);
        $secretsToEncryptIds = Hash::extract($secretsToEncrypt, '{n}.Secret.0.resource_id');
        $this->assertCount(count($expectedSecretsToEncryptIds), $secretsToEncryptIds);
        $this->assertEmpty(array_diff($expectedSecretsToEncryptIds, $secretsToEncryptIds));
    }

    public function testGroupsUpdateDryRunController_Success_RunAsAdmin(): void
    {
        // Define actors of this tests
        $groupId = GroupFactory::make()->persist()->id;
        $userAId = UserFactory::make()->persist()->id;

        // Try to add the userA.
        $data = [
            'name' => 'Name changed',
            'groups_users' => [['user_id' => $userAId]],
        ];

        // Update the group name.
        $this->logInAsAdmin();
        $this->putJson("/groups/$groupId/dry-run.json", $data);
        $this->assertSuccess();

        // No secrets should be requested nor source secrets given.
        $result = $this->getResponseBodyAsArray();
        $this->assertNotEmpty($result['dry-run']);
        $this->assertEmpty($result['dry-run']['SecretsNeeded']);
        $this->assertEmpty($result['dry-run']['Secrets']);
    }

    public function testGroupsUpdateDryRunController_Error_InValidGroupId(): void
    {
        $this->logInAsAdmin();
        $groupId = 'invalid-id';
        $this->putJson("/groups/$groupId/dry-run.json");
        $this->assertError(400, 'The group id is not valid.');
    }

    public function testGroupsUpdateDryRunController_Error_DoesNotExistGroup(): void
    {
        $this->logInAsAdmin();
        $groupId = UuidFactory::uuid();
        $this->putJson("/groups/$groupId/dry-run.json");
        $this->assertError(404, 'The group does not exist.');
    }

    public function testGroupsUpdateDryRunController_Error_GroupIsSoftDeleted(): void
    {
        $this->logInAsAdmin();
        $groupId = GroupFactory::make()->deleted()->persist()->id;
        $this->putJson("/groups/$groupId/dry-run.json");
        $this->assertError(404, 'The group does not exist.');
    }

    public function testGroupsUpdateDryRunController_Error_AccessDenied(): void
    {
        $groupId = GroupFactory::make()->persist()->id;
        $this->logInAsUser();
        $this->putJson("/groups/$groupId/dry-run.json");
        $this->assertForbiddenError('You are not authorized to access that location.');
    }

    public function testGroupsUpdateDryRunController_Error_NotAuthenticated(): void
    {
        $groupId = GroupFactory::make()->persist()->id;
        $postData = [];
        $this->putJson("/groups/$groupId/dry-run.json", $postData);
        $this->assertAuthenticationError();
    }

    public function testGroupsUpdateDryRunController_Error_CsrfToken(): void
    {
        $this->disableCsrfToken();
        $this->logInAsAdmin();
        $groupId = GroupFactory::make()->persist()->id;
        $this->put("/groups/$groupId/dry-run.json");
        $this->assertResponseCode(403);
    }

    public function testGroupsUpdateDryRunController_Error_NotJson(): void
    {
        $groupId = GroupFactory::make()->persist()->id;
        $data = [
            'name' => 'Updated group name',
        ];

        $this->logInAsAdmin();
        $this->put("/groups/$groupId/dry-run", $data);
        $this->assertResponseCode(404);
    }
}
