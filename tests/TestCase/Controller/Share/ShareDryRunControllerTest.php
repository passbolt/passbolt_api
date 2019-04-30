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
use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class ShareDryRunControllerTest extends AppIntegrationTestCase
{
    public $fixtures = ['app.Base/Users', 'app.Base/Gpgkeys', 'app.Base/Profiles', 'app.Base/Avatars', 'app.Base/Roles', 'app.Base/Groups', 'app.Base/GroupsUsers', 'app.Base/Resources', 'app.Base/Permissions'];

    public function setUp()
    {
        $this->Permissions = TableRegistry::getTableLocator()->get('Permissions');
        $this->Resources = TableRegistry::getTableLocator()->get('Resources');
        $this->gpg = OpenPGPBackendFactory::get();
        parent::setUp();
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
        $expectedAddedUsersIds[] = $userEId;

        // Groups permissions changes.
        // Change the permission of the group Board (no users are expected to be added or removed).
        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.$resourceId-$groupBId"), 'type' => Permission::OWNER];
        // Delete the permission of the group Freelancer.
        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.$resourceId-$groupFId"), 'delete' => true];
        $expectedRemovedUsersIds = array_merge($expectedRemovedUsersIds, [$userJId, $userKId, $userLId, $userMId, $userNId]);
        // Add a read permission for the group Accounting.
        $data['permissions'][] = ['aro' => 'Group', 'aro_foreign_key' => $groupAId, 'type' => Permission::READ];
        $expectedAddedUsersIds = array_merge($expectedAddedUsersIds, [$userFId]);

        $this->authenticateAs('ada');
        $this->postJson("/share/simulate/resource/$resourceId.json", $data);
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

    /*
     * The format validation is done by the Resource & Permission model.
     * Test few scenarios to ensure the validation works as expected.
     * @see App\Test\TestCase\Model\Table\Resources\ShareDryRunTest
     * @see App\Test\TestCase\Model\Table\Permissions\PatchEntitiesWithChangesTest
     */
    public function testErrorValidation()
    {
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $resourceAprilId = UuidFactory::uuid('resource.id.april');
        $userAId = UuidFactory::uuid('user.id.ada');
        $userRId = UuidFactory::uuid('user.id.ruth');
        $userSId = UuidFactory::uuid('user.id.sofia');
        $testCases = [
            'cannot update a permission that does not exist' => [
                'errorField' => 'permissions.0.id.permission_exists',
                'data' => [['id' => UuidFactory::uuid()]]
            ],
            'cannot delete a permission of another resource' => [
                'errorField' => 'permissions.0.id.permission_exists',
                'data' => [
                    ['id' => UuidFactory::uuid("permission.id.$resourceAprilId-$userAId"), 'delete' => true]]
            ],
            'cannot add a permission with invalid data' => [
                'errorField' => 'permissions.0.aro_foreign_key._required',
                'data' => [['aro' => 'User', 'type' => Permission::OWNER]]
            ],
            'cannot add a permission for a soft deleted user' => [
                'errorField' => 'permissions.0.aro_foreign_key.aro_exists',
                'data' => [[
                    'aro' => 'User',
                    'aro_foreign_key' => $userSId,
                    'type' => Permission::OWNER]]
            ],
            'cannot add a permission for an inactive user' => [
                'errorField' => 'permissions.0.aro_foreign_key.aro_exists',
                'data' => [[
                    'aro' => 'User',
                    'aro_foreign_key' => $userRId,
                    'type' => Permission::OWNER]]
            ],
            'cannot remove the latest owner' => [
                'errorField' => 'permissions.at_least_one_owner',
                'data' => [
                    ['id' => UuidFactory::uuid("permission.id.$resourceId-$userAId"), 'delete' => true]]
            ]
        ];

        $this->authenticateAs('ada');
        foreach ($testCases as $caseLabel => $case) {
            $this->postJson("/share/simulate/resource/$resourceId.json?api-version=2", ['permissions' => $case['data']]);
            $this->assertError();
            $errors = json_decode(json_encode($this->_responseJsonBody), true);
            $this->assertNotEmpty($errors);
            $error = Hash::get($errors, $case['errorField']);
            $this->assertNotNull($error, "Expected error not found : {$case['errorField']}. Errors: " . json_encode($errors));
        }
    }

    public function testSuccessNoChange()
    {
        $resourceId = UuidFactory::uuid('resource.id.cakephp');
        $this->authenticateAs('ada');
        $this->postJson("/share/simulate/resource/$resourceId.json");
        $this->assertNotEmpty($this->_responseJsonBody);
        $this->assertNotEmpty($this->_responseJsonBody->changes);
        $this->assertEmpty($this->_responseJsonBody->changes->added);
        $this->assertEmpty($this->_responseJsonBody->changes->removed);
    }

    public function testErrorNotValidResourceId()
    {
        $this->authenticateAs('ada');
        $resourceId = 'invalid-id';
        $this->postJson("/share/simulate/resource/$resourceId.json");
        $this->assertError(400, 'The resource id is not valid.');
    }

    public function testErrorDoesNotExistResource()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid();
        $this->postJson("/share/simulate/resource/$resourceId.json");
        $this->assertError(404, 'The resource does not exist.');
    }

    public function testErrorResourceIsSoftDeleted()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.jquery');
        $this->postJson("/share/simulate/resource/$resourceId.json");
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
            $this->postJson("/share/simulate/resource/$resourceId.json");
            $this->assertError(404, 'The resource does not exist.');
        }
    }

    public function testErrorNotAuthenticated()
    {
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->postJson("/share/simulate/resource/$resourceId.json");
        $this->assertAuthenticationError();
    }
}
