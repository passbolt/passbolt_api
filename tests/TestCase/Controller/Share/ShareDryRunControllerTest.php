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

namespace App\Test\TestCase\Controller\Share;

use App\Model\Entity\Permission;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class ShareDryRunControllerTest extends AppIntegrationTestCase
{
    /**
     * @var \App\Model\Table\PermissionsTable
     */
    public $Permissions;

    public function setUp(): void
    {
        parent::setUp();

        $this->Permissions = TableRegistry::getTableLocator()->get('Permissions');
    }

    /**
     * Fetch the DB permission ID for a given resource + ARO (user or group).
     *
     * @param string $resourceId
     * @param string $aroForeignKeyId
     * @return string
     */
    private function getPermissionId(string $resourceId, string $aroForeignKeyId): string
    {
        $permission = $this->Permissions
            ->find()
            ->where([
                'aco_foreign_key' => $resourceId,
                'aro_foreign_key' => $aroForeignKeyId,
            ])
            ->firstOrFail();

        return $permission->id;
    }

    public function testShareDryRunController_Success(): void
    {
        // Define actors of these tests
        // Users
        RoleFactory::make()->guest()->persist();
        [$userA, $userB, $userC, $userD, $userE, $userF, $userG, $userH, $userI] = UserFactory::make(9)->user()->persist();
        // Groups
        $groupA = GroupFactory::make()->persist();
        $groupB = GroupFactory::make()
            ->withGroupsManagersFor([$userF, $userG, $userH, $userI])
            ->withGroupsUsersFor([$userE])
            ->persist();
        $groupC = GroupFactory::make()->withGroupsManagersFor([$userD])->persist();
        // Resource
        $resourceA = ResourceFactory::make()
            ->withPermissionsFor([$userA, $userB, $groupA, $groupB])
            ->withSecretsFor([$userA, $userB, $groupB])
            ->persist();

        // Expected results.
        $expectedAddedUsersIds = [];
        $expectedRemovedUsersIds = [];

        // Build the changes.
        $data = ['permissions' => []];

        // Users permissions changes.
        // Change the permission of the user Ada to read (no users are expected to be added or removed).
        $data['permissions'][] = ['id' => $this->getPermissionId($resourceA->id, $userA->id), 'type' => Permission::READ];
        // Delete the permission of the user Betty.
        $data['permissions'][] = ['id' => $this->getPermissionId($resourceA->id, $userB->id), 'delete' => true];
        $expectedRemovedUsersIds[] = $userB->id;
        // Add an owner permission for the user Edith
        $data['permissions'][] = ['aro' => 'User', 'aro_foreign_key' => $userC->id, 'type' => Permission::OWNER];
        $expectedAddedUsersIds[] = $userC->id;

        // Groups permissions changes.
        // Change the permission of the group Board (no users are expected to be added or removed).
        $data['permissions'][] = ['id' => $this->getPermissionId($resourceA->id, $groupA->id), 'type' => Permission::OWNER];
        // Delete the permission of the group Freelancer.
        $data['permissions'][] = ['id' => $this->getPermissionId($resourceA->id, $groupB->id), 'delete' => true];
        $expectedRemovedUsersIds = array_merge($expectedRemovedUsersIds, [$userE->id, $userF->id, $userG->id, $userH->id, $userI->id]);
        // Add a read permission for the group Accounting.
        $data['permissions'][] = ['aro' => 'Group', 'aro_foreign_key' => $groupC->id, 'type' => Permission::READ];
        $expectedAddedUsersIds = array_merge($expectedAddedUsersIds, [$userD->id]);

        $this->logInAs($userA);
        $this->postJson("/share/simulate/resource/$resourceA->id.json", $data);
        $this->assertNotEmpty($this->_responseJsonBody);
        $this->assertNotEmpty($this->_responseJsonBody->changes);
        $addedUsers = $this->_responseJsonBody->changes->added;
        $this->assertNotEmpty($addedUsers);
        $addedUsersIds = Hash::extract($addedUsers, '{n}.User.id');
        $removedUsers = $this->_responseJsonBody->changes->removed;
        $this->assertNotEmpty($removedUsers);
        $removedUsersIds = Hash::extract($removedUsers, '{n}.User.id');

        // Assert the results.
        $this->assertCount(count($expectedAddedUsersIds), $addedUsersIds);
        $this->assertCount(count($expectedRemovedUsersIds), $removedUsersIds);
        $this->assertEmpty(array_diff($expectedAddedUsersIds, $addedUsersIds));
        $this->assertEmpty(array_diff($expectedRemovedUsersIds, $removedUsersIds));
    }

    public function testShareDryRunController_Success_NoChange(): void
    {
        $userA = UserFactory::make()->user()->persist();
        $resourceA = ResourceFactory::make()->withPermissionsFor([$userA])->persist();
        $this->logInAs($userA);
        $this->postJson("/share/simulate/resource/$resourceA->id.json");
        $this->assertNotEmpty($this->_responseJsonBody);
        $this->assertNotEmpty($this->_responseJsonBody->changes);
        $this->assertEmpty($this->_responseJsonBody->changes->added);
        $this->assertEmpty($this->_responseJsonBody->changes->removed);
    }

    /*
     * The format validation is done by the Resource & Permission model.
     * Test few scenarios to ensure the validation works as expected.
     * @see App\Test\TestCase\Model\Table\Resources\ShareDryRunTest
     * @see App\Test\TestCase\Model\Table\Permissions\PatchEntitiesWithChangesTest
     */

    public function testShareDryRunController_Error_Validation(): void
    {
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $userInactive = UserFactory::make()->user()->inactive()->persist();
        $userDeleted = UserFactory::make()->user()->deleted()->persist();
        $resourceOwned = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withSecretsFor([$userA])
            ->persist();
        $resourceOther = ResourceFactory::make()
            ->withPermissionsFor([$userB])
            ->withSecretsFor([$userB])
            ->persist();

        $testCases = [
            'cannot update a permission that does not exist' => [
                'errorField' => 'permissions.0.id.exists',
                'data' => [['id' => UuidFactory::uuid()]],
            ],
            'cannot delete a permission of another resource' => [
                'errorField' => 'permissions.0.id.exists',
                'data' => [
                    ['id' => $this->getPermissionId($resourceOther->id, $userB->id), 'delete' => true]],
            ],
            'cannot add a permission with invalid data' => [
                'errorField' => 'permissions.0.aro_foreign_key._empty',
                'data' => [['aro' => 'User', 'type' => Permission::OWNER]],
            ],
            'cannot add a permission for a soft deleted user' => [
                'errorField' => 'permissions.0.aro_foreign_key.aro_exists',
                'data' => [[
                    'aro' => 'User',
                    'aro_foreign_key' => $userDeleted->id,
                    'type' => Permission::OWNER]],
            ],
            'cannot add a permission for an inactive user' => [
                'errorField' => 'permissions.0.aro_foreign_key.aro_exists',
                'data' => [[
                    'aro' => 'User',
                    'aro_foreign_key' => $userInactive->id,
                    'type' => Permission::OWNER]],
            ],
            'cannot remove the latest owner' => [
                'errorField' => 'permissions.at_least_one_owner',
                'data' => [
                    ['id' => $this->getPermissionId($resourceOwned->id, $userA->id), 'delete' => true]],
            ],
        ];

        $this->logInAs($userA);
        foreach ($testCases as $case) {
            $this->postJson("/share/simulate/resource/$resourceOwned->id.json", ['permissions' => $case['data']]);
            $this->assertError();
            $errors = $this->getResponseBodyAsArray();
            $this->assertNotEmpty($errors);
            $error = Hash::get($errors, $case['errorField']);
            $this->assertNotNull($error, "Expected error not found : {$case['errorField']}. Errors: " . json_encode($errors));
        }
    }

    public function testShareDryRunController_Error_NotValidResourceId(): void
    {
        $this->logInAsUser();
        $resourceId = 'invalid-id';
        $this->postJson("/share/simulate/resource/$resourceId.json");
        $this->assertError(400, 'The resource identifier should be a valid UUID.');
    }

    public function testShareDryRunController_Error_DoesNotExistResource(): void
    {
        $this->logInAsUser();
        $resourceId = UuidFactory::uuid();
        $this->postJson("/share/simulate/resource/$resourceId.json");
        $this->assertError(404, 'The resource does not exist.');
    }

    public function testShareDryRunController_Error_ResourceIsSoftDeleted(): void
    {
        $userA = UserFactory::make()->user()->persist();
        $resourceDeleted = ResourceFactory::make()->withPermissionsFor([$userA])->deleted()->persist();
        $this->logInAs($userA);
        $this->postJson("/share/simulate/resource/$resourceDeleted->id.json");
        $this->assertError(404, 'The resource does not exist.');
    }

    public function testShareDryRunController_Error_AccessDenied(): void
    {
        $userA = UserFactory::make()->user()->persist();
        $resourceA = ResourceFactory::make()->persist();
        $resourceB = ResourceFactory::make()->withPermissionsFor([$userA], Permission::READ)->persist();
        $resourceC = ResourceFactory::make()->withPermissionsFor([$userA], Permission::UPDATE)->persist();
        $testCases = [
            'Cannot share a resource if no permission' => [
                'resourceId' => $resourceA->id],
            'Cannot share a resource with only read access' => [
                'resourceId' => $resourceB->id],
            'Cannot share a resource with only update access' => [
                'resourceId' => $resourceC->id],
        ];

        foreach ($testCases as $testCase) {
            $this->logInAs($userA);
            $resourceId = $testCase['resourceId'];
            $this->postJson("/share/simulate/resource/$resourceId.json");
            $this->assertError(403, 'You are not authorized to share this resource.');
        }
    }

    public function testShareDryRunController_Error_NotAuthenticated(): void
    {
        $resourceA = ResourceFactory::make()->persist();
        $this->postJson("/share/simulate/resource/$resourceA->id.json");
        $this->assertAuthenticationError();
    }

    /**
     * Check that calling url without JSON extension throws a 404
     */
    public function testShareDryRunController_Error_NotJson(): void
    {
        $userA = UserFactory::make()->user()->persist();
        $resourceA = ResourceFactory::make()->withPermissionsFor([$userA])->persist();
        $this->logInAs($userA);
        $this->post("/share/simulate/resource/$resourceA->id");
        $this->assertResponseCode(404);
    }
}
