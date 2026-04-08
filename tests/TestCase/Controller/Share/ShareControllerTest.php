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
use App\Model\Entity\Role;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\SecretRevisions\Test\Factory\SecretRevisionFactory;

class ShareControllerTest extends AppIntegrationTestCase
{
    /**
     * @var \App\Model\Table\UsersTable|null
     */
    public $Users = null;

    /**
     * @var \App\Utility\OpenPGP\Backends\Gnupg|null
     */
    public $gpg = null;

    /**
     * @var \App\Model\Table\PermissionsTable
     */
    public $Permissions;

    public function setUp(): void
    {
        parent::setUp();

        $this->Users = TableRegistry::getTableLocator()->get('Users');
        $this->gpg = OpenPGPBackendFactory::get();
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

    protected static function getValidSecret(): string
    {
        return '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf9HpfcNeuC5W/VAzEtAe8mTBUk1vcJENtGpMyRkVTC8KbQ
xaEr3+UG6h0ZVzfrMFYrYLolS3fie83cj4FnC3gg1uijo7zTf9QhJMdi7p/ASB6N
y7//8AriVqUAOJ2WCxAVseQx8qt2KqkQvS7F7iNUdHfhEhiHkczTlehyel7PEeas
SdM/kKEsYKk6i4KLPBrbWsflFOkfQGcPL07uRK3laFz8z4LNzvNQOoU7P/C1L0X3
tlK3vuq+r01zRwmflCaFXaHVifj3X74ljhlk5i/JKLoPRvbxlPTevMNag5e6QhPQ
kpj+TJD2frfGlLhyM50hQMdJ7YVypDllOBmnTRwZ0tJFAXm+F987ovAVLMXGJtGO
P+b3c493CfF0fQ1MBYFluVK/Wka8usg/b0pNkRGVWzBcZ1BOONYlOe/JmUyMutL5
hcciUFw5
=TcQF
-----END PGP MESSAGE-----';
    }

    public function testShareController_Success(): void
    {
        // Define actors of this tests
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

        SecretRevisionFactory::make(['resource_id' => $resourceA->id])->persist();

        // Expected results.
        $expectedAddedUsersIds = [];
        $expectedRemovedUsersIds = [];

        // Build the changes.
        $data = ['permissions' => [], 'secrets' => []];

        // Users permissions changes.
        // Change the permission of the user Ada to read (no users are expected to be added or removed).
        $data['permissions'][] = ['id' => $this->getPermissionId($resourceA->id, $userA->id), 'type' => Permission::READ];
        // Delete the permission of the user Betty.
        $data['permissions'][] = ['id' => $this->getPermissionId($resourceA->id, $userB->id), 'delete' => true];
        $expectedRemovedUsersIds[] = $userB->id;
        // Add an owner permission for the user Edith
        $data['permissions'][] = ['aro' => 'User', 'aro_foreign_key' => $userC->id, 'type' => Permission::OWNER];
        $data['secrets'][] = ['user_id' => $userC->id, 'data' => self::getValidSecret()];
        $expectedAddedUsersIds[] = $userC->id;

        // Groups permissions changes.
        // Change the permission of the group Board (no users are expected to be added or removed).
        $data['permissions'][] = ['id' => $this->getPermissionId($resourceA->id, $groupA->id), 'type' => Permission::OWNER];
        // Delete the permission of the group Freelancer.
        $data['permissions'][] = ['id' => $this->getPermissionId($resourceA->id, $groupB->id), 'delete' => true];
        $expectedRemovedUsersIds = array_merge($expectedRemovedUsersIds, [$userE->id, $userF->id, $userG->id, $userH->id, $userI->id]);
        // Add a read permission for the group Accounting.
        $data['permissions'][] = ['aro' => 'Group', 'aro_foreign_key' => $groupC->id, 'type' => Permission::READ];
        $data['secrets'][] = ['user_id' => $userD->id, 'data' => self::getValidSecret()];
        $expectedAddedUsersIds[] = $userD->id;

        $this->logInAs($userA);
        $this->putJson("/share/resource/$resourceA->id.json", $data);
        $this->assertSuccess();

        // Load the resource.
        $resource = ResourceFactory::find()
            ->where(['Resources.id' => $resourceA->id,])
            ->contain('Permissions')
            ->contain('Secrets', function ($q) {
                return $q->find('notDeleted');
            })->firstOrFail();

        // Verify that all the allowed users have a secret for the resource.
        $secretsUsersIds = Hash::extract($resource->secrets, '{n}.user_id');
        $hasAccessUsers = $this->Users->findIndex(Role::USER, ['filter' => ['has-access' => [$resourceA->id]]])->all()->toArray();
        $hasAccessUsersIds = Hash::extract($hasAccessUsers, '{n}.id');
        $this->assertEquals(count($secretsUsersIds), count($hasAccessUsersIds));
        $this->assertEmpty(array_diff($secretsUsersIds, $hasAccessUsersIds));

        // Ensure that the newly added users have a secret, and are allowed to access the resource.
        foreach ($expectedAddedUsersIds as $userId) {
            $this->assertContains($userId, $secretsUsersIds);
            $this->assertContains($userId, $hasAccessUsersIds);
        }
        // Ensure that the removed users don't have a secret, and are no more allowed to access the resource.
        foreach ($expectedRemovedUsersIds as $userId) {
            $this->assertNotContains($userId, $secretsUsersIds);
            $this->assertNotContains($userId, $hasAccessUsersIds);
        }
    }

    public function testShareController_Error_Validation(): void
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
        SecretRevisionFactory::make(['resource_id' => $resourceOwned->id])->persist();

        $cases = [
            'cannot a permission that does not exist' => [
                'errorField' => 'permissions.0.id.exists',
                'data' => ['permissions' => [
                    ['id' => UuidFactory::uuid()],
                ]],
            ],
            'cannot delete a permission of another resource' => [
                'errorField' => 'permissions.0.id.exists',
                'data' => ['permissions' => [
                    ['id' => $this->getPermissionId($resourceOther->id, $userB->id), 'delete' => true],
                ]],
            ],
            'cannot add a permission with invalid data' => [
                'errorField' => 'permissions.0.aro_foreign_key._empty',
                'data' => ['permissions' => [
                    ['aro' => 'User', 'type' => Permission::OWNER],
                ]],
            ],
            'cannot add a permission for a soft deleted user' => [
                'errorField' => 'permissions.0.aro_foreign_key.aro_exists',
                'data' => ['permissions' => [[
                    'aro' => 'User',
                    'aro_foreign_key' => $userDeleted->id,
                    'type' => Permission::OWNER],
                ]],
            ],
            'cannot add a permission for an inactive user' => [
                'errorField' => 'permissions.0.aro_foreign_key.aro_exists',
                'data' => ['permissions' => [[
                    'aro' => 'User',
                    'aro_foreign_key' => $userInactive->id,
                    'type' => Permission::OWNER],
                ]],
            ],
            'cannot remove the latest owner' => [
                'errorField' => 'permissions.at_least_one_owner',
                'data' => ['permissions' => [
                    ['id' => $this->getPermissionId($resourceOwned->id, $userA->id), 'delete' => true],
                ]],
            ],
            // Test on secrets.
            'cannot add a permission for a user and forget to send its secret' => [
                'errorField' => 'secrets.secrets_provided',
                'data' => ['permissions' => [
                    ['aro' => 'User', 'aro_foreign_key' => $userB->id, 'type' => Permission::READ],
                ]],
            ],
            'cannot add a secret for a user who do not have access to the resource' => [
                'errorField' => 'secrets.0.resource_id.has_resource_access',
                'data' => ['secrets' => [
                    ['user_id' => $userB->id, 'data' => self::getValidSecret()],
                ]],
            ],
        ];

        $this->logInAs($userA);

        foreach ($cases as $caseLabel => $case) {
            $this->putJson("/share/resource/$resourceOwned->id.json", $case['data']);
            $this->assertError();
            $errors = $this->getResponseBodyAsArray();
            $this->assertNotEmpty($errors);
            $error = Hash::get($errors, $case['errorField']);
            $this->assertNotNull($error, "Expected error not found ({$case['errorField']}) for the case {$caseLabel}. Errors: " . json_encode($errors));
        }
    }

    public function testShareController_Error_NotValidResourceId(): void
    {
        $this->logInAsUser();
        $resourceId = 'invalid-id';
        $this->putJson("/share/resource/$resourceId.json");
        $this->assertError(400, 'The resource identifier should be a valid UUID.');
    }

    public function testShareController_Error_DoesNotExistResource(): void
    {
        $this->logInAsUser();
        $resourceId = UuidFactory::uuid();
        $this->putJson("/share/resource/$resourceId.json");
        $this->assertError(404, 'The resource does not exist.');
    }

    public function testShareController_Error_ResourceIsSoftDeleted(): void
    {
        $this->logInAsUser();
        $resource = ResourceFactory::make()->deleted()->persist();
        $this->putJson("/share/resource/$resource->id.json");
        $this->assertError(404, 'The resource does not exist.');
    }

    public function testShareController_Error_AccessDenied(): void
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
            $this->putJson("/share/resource/$resourceId.json");
            $this->assertError(403, 'You are not authorized to share this resource.');
        }
    }

    public function testShareController_Error_NotAuthenticated(): void
    {
        $resource = ResourceFactory::make()->persist();
        $this->putJson("/share/resource/$resource->id.json");
        $this->assertAuthenticationError();
    }

    /**
     * Check that calling url without JSON extension throws a 404
     */
    public function testShareController_Error_NotJson(): void
    {
        // Define actors of this tests
        $userA = UserFactory::make()->user()->persist();
        $resource = ResourceFactory::make()->withPermissionsFor([$userA])->persist();
        // Build the changes.
        $data = ['permissions' => []];

        $this->logInAs($userA);
        $this->put("/share/resource/$resource->id", $data);
        $this->assertResponseCode(404);
    }
}
