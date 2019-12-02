<?php
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
use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class ShareControllerTest extends AppIntegrationTestCase
{
    public $fixtures = [
        'app.Base/Users', 'app.Base/Gpgkeys', 'app.Base/Profiles', 'app.Base/Avatars', 'app.Base/Roles', 'app.Base/Groups',
        'app.Base/GroupsUsers', 'app.Base/Resources', 'app.Base/Permissions', 'app.Base/Secrets'
    ];

    public function setUp()
    {
        $this->Permissions = TableRegistry::getTableLocator()->get('Permissions');
        $this->Resources = TableRegistry::getTableLocator()->get('Resources');
        $this->Users = TableRegistry::getTableLocator()->get('Users');
        $this->gpg = OpenPGPBackendFactory::get();
        parent::setUp();
    }

    protected function getValidSecret()
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

    public function testSuccessApiV1()
    {
        // Define actors of this tests
        $resourceId = UuidFactory::uuid('resource.id.cakephp');
        // Users
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $userEId = UuidFactory::uuid('user.id.edith');
        $userFId = UuidFactory::uuid('user.id.frances');
        $userJId = UuidFactory::uuid('user.id.jean');
        $userKId = UuidFactory::uuid('user.id.kathleen');
        $userLId = UuidFactory::uuid('user.id.lynne');
        $userMId = UuidFactory::uuid('user.id.marlyn');
        $userNId = UuidFactory::uuid('user.id.nancy');
        // Groups
        $groupBId = UuidFactory::uuid('group.id.board');
        $groupFId = UuidFactory::uuid('group.id.freelancer');
        $groupAId = UuidFactory::uuid('group.id.accounting');

        // Expected results.
        $expectedAddedUsersIds = [];
        $expectedRemovedUsersIds = [];

        // Build the changes.
        $data = ['permissions' => []];

        // Users permissions changes.
        // Change the permission of the user Ada to read (no users are expected to be added or removed).
        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.$resourceId-$userAId"), 'type' => Permission::READ];
        // Delete the permission of the user Betty.
        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.$resourceId-$userBId"), 'delete' => true];
        $expectedRemovedUsersIds[] = $userBId;
        // Add an owner permission for the user Edith
        $data['permissions'][] = ['aro' => 'User', 'aro_foreign_key' => $userEId, 'type' => Permission::OWNER];
        $data['secrets'][] = ['user_id' => $userEId, 'data' => $this->getValidSecret()];
        $expectedAddedUsersIds[] = $userEId;

        // Groups permissions changes.
        // Change the permission of the group Board (no users are expected to be added or removed).
        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.$resourceId-$groupBId"), 'type' => Permission::OWNER];
        // Delete the permission of the group Freelancer.
        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.$resourceId-$groupFId"), 'delete' => true];
        $expectedRemovedUsersIds = array_merge($expectedRemovedUsersIds, [$userJId, $userKId, $userLId, $userMId, $userNId]);
        // Add a read permission for the group Accounting.
        $data['permissions'][] = ['aro' => 'Group', 'aro_foreign_key' => $groupAId, 'type' => Permission::READ];
        $data['secrets'][] = ['user_id' => $userFId, 'data' => $this->getValidSecret()];
        $expectedAddedUsersIds = array_merge($expectedAddedUsersIds, [$userFId]);

        $this->authenticateAs('ada');
        $this->putJson("/share/resource/$resourceId.json", $data);
        $this->assertSuccess();

        // Load the resource.
        $resource = $this->Resources->get($resourceId, ['contain' => ['Permissions', 'Secrets']]);

        // Verify that all the allowed users have a secret for the resource.
        $secretsUsersIds = Hash::extract($resource->secrets, '{n}.user_id');
        $hasAccessUsers = $this->Users->findIndex(Role::USER, ['filter' => ['has-access' => [$resourceId]]])->all()->toArray();
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

    public function testErrorValidation()
    {
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $resourceAprilId = UuidFactory::uuid('resource.id.april');
        $userAId = UuidFactory::uuid('user.id.ada');
        $userEId = UuidFactory::uuid('user.id.edith');
        $userRId = UuidFactory::uuid('user.id.ruth');
        $userSId = UuidFactory::uuid('user.id.sofia');
        $testCases = [
            'cannot a permission that does not exist' => [
                'errorField' => 'permissions.0.id.permission_exists',
                'data' => ['permissions' => [
                    ['id' => UuidFactory::uuid()]
                ]]
            ],
            'cannot delete a permission of another resource' => [
                'errorField' => 'permissions.0.id.permission_exists',
                'data' => ['permissions' => [
                    ['id' => UuidFactory::uuid("permission.id.$resourceAprilId-$userAId"), 'delete' => true]
                ]]
            ],
            'cannot add a permission with invalid data' => [
                'errorField' => 'permissions.0.aro_foreign_key._required',
                'data' => ['permissions' => [
                    ['aro' => 'User', 'type' => Permission::OWNER]
                ]]
            ],
            'cannot add a permission for a soft deleted user' => [
                'errorField' => 'permissions.0.aro_foreign_key.aro_exists',
                'data' => ['permissions' => [[
                    'aro' => 'User',
                    'aro_foreign_key' => $userSId,
                    'type' => Permission::OWNER]
                ]],
            ],
            'cannot add a permission for an inactive user' => [
                'errorField' => 'permissions.0.aro_foreign_key.aro_exists',
                'data' => ['permissions' => [[
                    'aro' => 'User',
                    'aro_foreign_key' => $userRId,
                    'type' => Permission::OWNER]
                ]],
            ],
            'cannot remove the latest owner' => [
                'errorField' => 'permissions.at_least_one_owner',
                'data' => ['permissions' => [
                    ['id' => UuidFactory::uuid("permission.id.$resourceId-$userAId"), 'delete' => true]
                ]]
            ],
            // Test on secrets.
            'cannot add a permission for a user and forget to send its secret' => [
                'errorField' => 'secrets.secrets_provided',
                'data' => ['permissions' => [
                    ['aro' => 'User', 'aro_foreign_key' => $userEId, 'type' => Permission::READ]
                ]]
            ],
            'cannot add a secret for a user who do not have access to the resource' => [
                'errorField' => 'secrets.secrets_provided',
                'data' => ['secrets' => [
                    ['user_id' => $userEId, 'data' => $this->getValidSecret()]
                ]]
            ],
        ];

        $this->authenticateAs('ada');
        foreach ($testCases as $caseLabel => $case) {
            $this->putJson("/share/resource/$resourceId.json?api-version=2", $case['data']);
            $this->assertError();
            $errors = json_decode(json_encode($this->_responseJsonBody), true);
            $this->assertNotEmpty($errors);
            $error = Hash::get($errors, $case['errorField']);
            $this->assertNotNull($error, "Expected error not found ({$case['errorField']}) for the case {$caseLabel}. Errors: " . json_encode($errors));
        }
    }

    public function testErrorNotValidResourceId()
    {
        $this->authenticateAs('ada');
        $resourceId = 'invalid-id';
        $this->putJson("/share/resource/$resourceId.json");
        $this->assertError(400, 'The resource id is not valid.');
    }

    public function testErrorDoesNotExistResource()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid();
        $this->putJson("/share/resource/$resourceId.json");
        $this->assertError(404, 'The resource does not exist.');
    }

    public function testErrorResourceIsSoftDeleted()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.jquery');
        $this->putJson("/share/resource/$resourceId.json");
        $this->assertError(404, 'The resource does not exist.');
    }

    public function testErrorAccessDenied()
    {
        $testCases = [
            'Cannot share a resource if no permission' => [
                'userAlias' => 'ada', 'resourceId' => UuidFactory::uuid('resource.id.april')],
            'Cannot share a resource with only read access' => [
                'userAlias' => 'ada', 'resourceId' => UuidFactory::uuid('resource.id.bower')],
            'Cannot share a resource with only update access' => [
                'userAlias' => 'ada', 'resourceId' => UuidFactory::uuid('resource.id.canjs')],
        ];

        foreach ($testCases as $testCase) {
            $this->authenticateAs($testCase['userAlias']);
            $resourceId = $testCase['resourceId'];
            $this->putJson("/share/resource/$resourceId.json");
            $this->assertError(404, 'The resource does not exist.');
        }
    }

    public function testErrorNotAuthenticated()
    {
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->putJson("/share/resource/$resourceId.json");
        $this->assertAuthenticationError();
    }
}
