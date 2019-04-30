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

namespace App\Test\TestCase\Controller\Groups;

use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class GroupsUpdateDryRunControllerTest extends AppIntegrationTestCase
{
    public $fixtures = ['app.Base/Groups', 'app.Base/GroupsUsers', 'app.Base/Resources', 'app.Base/Permissions', 'app.Base/Users', 'app.Base/Secrets'];

    public function setUp()
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
    public function testGroupsUpdateDryRunAsGroupManagerSuccess()
    {
        // Define actors of this tests
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $userAId = UuidFactory::uuid("user.id.ada");
        $userCId = UuidFactory::uuid('user.id.carol');
        $userFId = UuidFactory::uuid('user.id.frances');
        $resourceCId = UuidFactory::uuid('resource.id.chai');
        $resourceFId = UuidFactory::uuid('resource.id.fosdem');
        $resourceGId = UuidFactory::uuid('resource.id.grunt');

        // Retrieve the resources the group has access.
        $resources = $this->Resources->findAllByGroupAccess($groupId)->all()->toArray();
        $groupHasAccess = Hash::extract($resources, '{n}.id');

        // Build the request data.
        $changes = [];

        // Update memberships.
        // Remove Jean as admin
        $changes[] = ['id' => UuidFactory::uuid("group_user.id.freelancer-jean"), 'is_admin' => false];
        // Make Kathleen admin
        $changes[] = ['id' => UuidFactory::uuid("group_user.id.freelancer-nancy"), 'is_admin' => true];

        // Remove users from the group
        // Remove Kathleen who has access to the group resources only because of her membership.
        $changes[] = ['id' => UuidFactory::uuid("group_user.id.freelancer-kathleen"), 'delete' => true];

        // Remove a user who has its own access to the same resource the group has.
        // Remove lynne who has a direct access to the resource chai.
        $changes[] = ['id' => UuidFactory::uuid("group_user.id.freelancer-lynne"), 'delete' => true];

        // Add a user who has not access to the group resources before adding it to the group.
        // Add Frances.
        $changes[] = ['user_id' => $userFId];

        // Add a user who already has access to all of the resources the group has access.
        // Carol has the same access as the group Freelancer.
        // No secret need to be encrypted for the user.
        $changes[] = ['user_id' => $userCId];

        // Add a user who already has access to some of the resources the group has access.
        // Ada already has access to few resources the group has access : chai, fosdem, grunt
        // Expect the secrets Ada had no access to be encrypted.
        $changes[] = ['user_id' => $userAId];

        // Update the group users.
        $this->authenticateAs('jean');
        $this->putJson("/groups/$groupId/dry-run.json", ['groups_users' => $changes]);

        $this->assertSuccess();
        $result = json_decode(json_encode($this->_responseJsonBody), true);
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

        // Membership of Jean and Nancy are changed. Nothing expected.
        // kathleen should not have anymore access to the group resources. Nothing expected.
        // Lynne should not have anymore access to the group resources (except chai). Nothing expected.
        // Frances should have access to the group resources.
        foreach ($groupHasAccess as $resourceId) {
            $expectedSecretsToAdd[] = ['resource_id' => $resourceId, 'user_id' => $userFId];
        }
        // Carol already have access to the resources, nothing expected.
        // Ada should have access to the group resources.
        $userHasNotAccess = [$resourceCId, $resourceFId, $resourceGId];
        foreach ($userHasNotAccess as $resourceId) {
            $expectedSecretsToAdd[] = ['resource_id' => $resourceId, 'user_id' => $userAId];
        }

        // Assert the added secrets are as expected.
        $this->assertCount(count($expectedSecretsToAdd), $secretsToAdd);
        foreach ($expectedSecretsToAdd as $secret) {
            $extract = Hash::extract($secretsToAdd, "{n}.Secret[user_id={$secret['user_id']}][resource_id={$secret['resource_id']}]");
            $this->assertNotEmpty($extract);
        }

        // Assert that all the secrets that will be used to encrypt the secret to add are in the result.
        $expectedSecretsToEncryptIds = Hash::extract($secretsToAdd, "{n}.Secret.resource_id");
        $expectedSecretsToEncryptIds = array_unique($expectedSecretsToEncryptIds);
        $secretsToEncryptIds = Hash::extract($secretsToEncrypt, "{n}.Secret.0.resource_id");
        $this->assertCount(count($expectedSecretsToEncryptIds), $secretsToEncryptIds);
        $this->assertEmpty(array_diff($expectedSecretsToEncryptIds, $secretsToEncryptIds));
    }

    public function testGroupsUpdateDryRunAsAdminSuccess()
    {
        // Define actors of this tests
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $userAId = UuidFactory::uuid('user.id.ada');

        // Try to add the user Ada.
        $data = [
            'name' => 'Name changed',
            'groups_users' => [['user_id' => $userAId]]
        ];

        // Update the group name.
        $this->authenticateAs('admin');
        $this->putJson("/groups/$groupId/dry-run.json", $data);
        $this->assertSuccess();

        // No secrets should be requested nor source secrets given.
        $result = json_decode(json_encode($this->_responseJsonBody), true);
        $this->assertNotEmpty($result['dry-run']);
        $this->assertEmpty($result['dry-run']['SecretsNeeded']);
        $this->assertEmpty($result['dry-run']['Secrets']);
    }

    public function testGroupsUpdateDryRunCannotModifyNotAccessibleFields()
    {
        $this->markTestIncomplete();
    }

    public function testGroupsUpdateDryRunErrorNotValidId()
    {
        $this->authenticateAs('ada');
        $groupId = 'invalid-id';
        $this->putJson("/groups/$groupId/dry-run.json");
        $this->assertError(400, 'The group id is not valid.');
    }

    public function testGroupsUpdateDryRunErrorDoesNotExistGroup()
    {
        $this->authenticateAs('ada');
        $groupId = UuidFactory::uuid();
        $this->putJson("/groups/$groupId/dry-run.json");
        $this->assertError(404, 'The group does not exist.');
    }

    public function testGroupsUpdateDryRunErrorGroupIsSoftDeleted()
    {
        $this->authenticateAs('admin');
        $groupId = UuidFactory::uuid('group.id.deleted');
        $this->putJson("/groups/$groupId/dry-run.json");
        $this->assertError(404, 'The group does not exist.');
    }

    public function testGroupsUpdateDryRunErrorAccessDenied()
    {
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $this->authenticateAs('ada');
        $this->putJson("/groups/$groupId/dry-run.json");
        $this->assertForbiddenError('You are not authorized to access that location.');
    }

    public function testGroupsUpdateDryRunErrorNotAuthenticated()
    {
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $postData = [];
        $this->putJson("/groups/$groupId/dry-run.json", $postData);
        $this->assertAuthenticationError();
    }

    public function testGroupsUpdateDryRunErrorCsrfToken()
    {
        $this->disableCsrfToken();
        $this->authenticateAs('admin');
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $this->put("/groups/$groupId/dry-run.json");
        $this->assertResponseCode(403);
    }
}
