<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */

namespace App\Test\TestCase\Controller\Groups;

use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class GroupsUpdateControllerTest extends AppIntegrationTestCase
{
    public $fixtures = ['app.Base/groups', 'app.Base/groups_users', 'app.Base/resources', 'app.Base/permissions',
        'app.Base/users', 'app.Base/secrets', 'app.Base/profiles', 'app.Base/gpgkeys', 'app.Base/roles',
        'app.Base/favorites', 'app.Base/avatars', 'app.Base/email_queue'];

    public function setUp()
    {
        $this->Favorites = TableRegistry::get('Favorites');
        $this->Groups = TableRegistry::get('Groups');
        $this->GroupsUsers = TableRegistry::get('GroupsUsers');
        $this->Resources = TableRegistry::get('Resources');
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

    protected function _assertUserHasNotAccessResources(string $userId, array $resourcesIds = [])
    {
        foreach ($resourcesIds as $resourceId) {
            // No access to the resource.
            $hasAccess = $this->Resources->hasAccess($userId, $resourceId);
            $this->assertFalse($hasAccess);
            // No secret for the resource.
            $secret = $this->Resources->Secrets->find()
                ->where(['resource_id' => $resourceId, 'user_id' => $userId])->first();
            $this->assertNull($secret);
            // Not favorite for the resource.
            $favorite = $this->Resources->Favorites->find()
                ->where(['foreign_key' => $resourceId, 'user_id' => $userId])->first();
            $this->assertNull($favorite);
        }
    }

    protected function _assertUserHasAccessResources(string $userId, array $resourcesIds = [])
    {
        foreach ($resourcesIds as $resourceId) {
            // Access granted to the resource.
            $hasAccess = $this->Resources->hasAccess($userId, $resourceId);
            $this->assertTrue($hasAccess);
            // Secret existing.
            $secret = $this->Resources->Secrets->find()
                ->where(['resource_id' => $resourceId, 'user_id' => $userId])->first();
            $this->assertNotNull($secret);
        }
    }

    protected function _assertUserIsAdmin($groupId, $userId)
    {
        $groupUser = $this->GroupsUsers->find()->where(['user_id' => $userId, 'group_id' => $groupId])->first();
        $this->assertTrue($groupUser->is_admin);
    }

    protected function _assertUserIsNotAdmin($groupId, $userId)
    {
        $groupUser = $this->GroupsUsers->find()->where(['user_id' => $userId, 'group_id' => $groupId])->first();
        $this->assertFalse($groupUser->is_admin);
    }

    /*
     * As a group manager I can update the roles of the members of a group I manage
     *   - Remove the group manager role of a member
     *   - Add the group manager role to a member
     */
    public function testAsGMUpdateMembersRoleSuccess()
    {
        // Define actors of this tests
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $userJId = UuidFactory::uuid('user.id.jean');
        $userNId = UuidFactory::uuid('user.id.nancy');

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

        // Update the group users.
        $this->authenticateAs('jean');
        $this->putJson("/groups/$groupId.json?api-version=v1", ['groups_users' => $changes]);
        $this->assertSuccess();

        // Jean and Nancy should still have access to the resources.
        $this->_assertUserHasAccessResources($userJId, $groupHasAccess);
        $this->_assertUserHasAccessResources($userNId, $groupHasAccess);

        // Jean should no longer be a group manager of the group
        $this->_assertUserIsNotAdmin($groupId, $userJId);

        // Nancy should be a group manager of the group
        $this->_assertUserIsAdmin($groupId, $userNId);
    }

    /*
     * As a group manager I can add members to a group I manage
     *   - A member who has no previous access to the resources shared with the group
     *   - A member who has already an access to all the resources shared with the group
     *   - A member who has already an access to some resources shared with the group
     */
    public function testAsGMAddMembersSuccess()
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
        $secrets = [];

        // Add a user who has not access to the group resources before adding it to the group.
        // Add Frances.
        $changes[] = ['user_id' => $userFId, 'is_admin' => true];
        // Add all the new secrets for the user.
        foreach ($groupHasAccess as $resourceId) {
            $secrets[] = ['resource_id' => $resourceId, 'user_id' => $userFId, 'data' => $this->getValidSecret()];
        }

        // Add a user who already has access to all of the resources the group has access.
        // Carol has the same access as the group Freelancer.
        // No secret need to be encrypted for the user.
        $changes[] = ['user_id' => $userCId];

        // Add a user who already has access to some of the resources the group has access.
        // Ada already has access to few resources the group has access : chai, fosdem, grunt
        // Expect the secrets Ada had no access to be encrypted.
        $changes[] = ['user_id' => $userAId];
        $resourcesAdaAccessedBefore = [$resourceCId, $resourceFId, $resourceGId];
        foreach ($resourcesAdaAccessedBefore as $resourceId) {
            $secrets[] = ['resource_id' => $resourceId, 'user_id' => $userAId, 'data' => $this->getValidSecret()];
        }

        // Update the group users.
        $this->authenticateAs('jean');
        $this->putJson("/groups/$groupId.json?api-version=v1", ['groups_users' => $changes, 'secrets' => $secrets]);
        $this->assertSuccess();

        // Frances should have access to the group resources.
        $this->_assertUserHasAccessResources($userFId, $groupHasAccess);
        // Frances should also be group manager of the group.
        $this->_assertUserIsAdmin($groupId, $userFId);

        // Carol should have access to the group resources.
        $this->_assertUserHasAccessResources($userCId, $groupHasAccess);
        // Carol should not be group manager of the group.
        $this->_assertUserIsNotAdmin($groupId, $userCId);

        // Ada should have access to the group resources.
        $this->_assertUserHasAccessResources($userAId, $groupHasAccess);
        // Ada should not be group manager of the group.
        $this->_assertUserIsNotAdmin($groupId, $userAId);
    }

    /*
     * As a group manager I can delete members to a group I manage
     *   - A member who has access to the resources shared with the group only because of its membership
     *   - A member who has access to some resources shared with the group because of other permissions
     */
    public function testAsGMDeleteMembersSuccess()
    {
        // Define actors of this tests
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $userKId = UuidFactory::uuid('user.id.kathleen');
        $userLId = UuidFactory::uuid('user.id.lynne');
        $resourceCId = UuidFactory::uuid('resource.id.chai');

        // Retrieve the resources the group has access.
        $resources = $this->Resources->findAllByGroupAccess($groupId)->all()->toArray();
        $groupHasAccess = Hash::extract($resources, '{n}.id');

        // Build the request data.
        $changes = [];

        // Remove users from the group
        // Remove Kathleen who has access to the group resources only because of her membership.
        $changes[] = ['id' => UuidFactory::uuid("group_user.id.freelancer-kathleen"), 'delete' => true];

        // Remove a user who has its own access to the same resource the group has.
        // Remove lynne who has a direct access to the resource chai.
        $changes[] = ['id' => UuidFactory::uuid("group_user.id.freelancer-lynne"), 'delete' => true];

        // Update the group users.
        $this->authenticateAs('jean');
        $this->putJson("/groups/$groupId.json?api-version=v1", ['groups_users' => $changes]);
        $this->assertSuccess();

        // kathleen should not have anymore access to the group resources.
        $this->_assertUserHasNotAccessResources($userKId, $groupHasAccess);

        // Lynne should not have anymore access to the group resources (except chai).
        $userHasAccess = [$resourceCId];
        $userHasNotAccess = array_diff($groupHasAccess, $userHasAccess);
        $this->_assertUserHasNotAccessResources($userLId, $userHasNotAccess);
        $this->_assertUserHasAccessResources($userLId, $userHasAccess);
    }

    /*
     * As a group manager I can update a group (complex scenario).
     *
     * - Update members roles:
     *   - Remove the group manager role of a member
     *   - Add the group manager role to a member
     * - Add multiple members:
     *   - A member who has no previous access to the resources shared with the group
     *   - A member who has already an access to all the resources shared with the group
     *   - A member who has already an access to some resources shared with the group
     * - Delete multiple members :
     *   - A member who has access to the resources shared with the group only because of its membership
     *   - A member who has access to some resources shared with the group because of other permissions
     */
    public function testAsGMUpdateGroupComplexScenarioSuccess()
    {
        // Define actors of this tests
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $userAId = UuidFactory::uuid("user.id.ada");
        $userCId = UuidFactory::uuid('user.id.carol');
        $userFId = UuidFactory::uuid('user.id.frances');
        $userJId = UuidFactory::uuid('user.id.jean');
        $userKId = UuidFactory::uuid('user.id.kathleen');
        $userLId = UuidFactory::uuid('user.id.lynne');
        $userNId = UuidFactory::uuid('user.id.nancy');
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
        // Add all the new secrets for the user.
        foreach ($groupHasAccess as $resourceId) {
            $secrets[] = ['resource_id' => $resourceId, 'user_id' => $userFId, 'data' => $this->getValidSecret()];
        }

        // Add a user who already has access to all of the resources the group has access.
        // Carol has the same access as the group Freelancer.
        // No secret need to be encrypted for the user.
        $changes[] = ['user_id' => $userCId];

        // Add a user who already has access to some of the resources the group has access.
        // Ada already has access to few resources the group has access : chai, fosdem, grunt
        // Expect the secrets Ada had no access to be encrypted.
        $changes[] = ['user_id' => $userAId];
        $resourcesAdaAccessedBefore = [$resourceCId, $resourceFId, $resourceGId];
        foreach ($resourcesAdaAccessedBefore as $resourceId) {
            $secrets[] = ['resource_id' => $resourceId, 'user_id' => $userAId, 'data' => $this->getValidSecret()];
        }

        // Update the group users.
        $this->authenticateAs('jean');
        $this->putJson("/groups/$groupId.json?api-version=v1", ['groups_users' => $changes, 'secrets' => $secrets]);
        $this->assertSuccess();

        // Jean and Nancy should still have access to the resources.
        $this->_assertUserHasAccessResources($userJId, $groupHasAccess);
        $this->_assertUserHasAccessResources($userNId, $groupHasAccess);

        // kathleen should not have anymore access to the group resources.
        $this->_assertUserHasNotAccessResources($userKId, $groupHasAccess);

        // Lynne should not have anymore access to the group resources (except chai).
        $userHasAccess = [$resourceCId];
        $userHasNotAccess = array_diff($groupHasAccess, $userHasAccess);
        $this->_assertUserHasNotAccessResources($userLId, $userHasNotAccess);
        $this->_assertUserHasAccessResources($userLId, $userHasAccess);

        // Frances should have access to the group resources.
        $this->_assertUserHasAccessResources($userFId, $groupHasAccess);
        // Carol should have access to the group resources.
        $this->_assertUserHasAccessResources($userCId, $groupHasAccess);
        // Ada should have access to the group resources.
        $this->_assertUserHasAccessResources($userAId, $groupHasAccess);
    }

    /*
     * As an administrator I can update the name of a group
     * Only an administrator is allowed to update the name of a group
     */
    public function testAsGMCannotUpdateNameError()
    {
        // Define actors of this tests
        $groupId = UuidFactory::uuid('group.id.freelancer');

        // Try to add the user frances.
        $data = [
            'name' => 'Updated group name'
        ];

        // Update the group name.
        $this->authenticateAs('jean');
        $this->putJson("/groups/$groupId.json?api-version=v1", $data);
        $this->assertSuccess();

        // The name of the group should be updated
        $group = $this->Groups->get($groupId);
        $this->assertNotEquals($data['name'], $group->name);
        $this->assertEquals('Freelancer', $group->name);
    }

    /*
     * As an administrator I can update the name of a group
     * Only an administrator is allowed to update the name of a group
     */
    public function testAsADUpdateNameSuccess()
    {
        // Define actors of this tests
        $groupId = UuidFactory::uuid('group.id.freelancer');

        // Try to add the user frances.
        $data = [
            'name' => 'Updated group name'
        ];

        // Update the group name.
        $this->authenticateAs('admin');
        $this->putJson("/groups/$groupId.json?api-version=v1", $data);
        $this->assertSuccess();

        // The name of the group should be updated
        $group = $this->Groups->get($groupId);
        $this->assertEquals($data['name'], $group->name);
    }

    /*
     * As an administrator I can update the roles of the members of a group
     * @see testAsGMUpdateMembersRoleSuccess
     */
    public function testAsADUpdateMembersRoleSuccess()
    {
        // Define actors of this tests
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $userJId = UuidFactory::uuid('user.id.jean');
        $userNId = UuidFactory::uuid('user.id.nancy');

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

        // Update the group users.
        $this->authenticateAs('admin');
        $this->putJson("/groups/$groupId.json?api-version=v1", ['groups_users' => $changes]);
        $this->assertSuccess();

        // Jean and Nancy should still have access to the resources.
        $this->_assertUserHasAccessResources($userJId, $groupHasAccess);
        $this->_assertUserHasAccessResources($userNId, $groupHasAccess);

        // Jean should no longer be a group manager of the group
        $this->_assertUserIsNotAdmin($groupId, $userJId);

        // Nancy should be a group manager of the group
        $this->_assertUserIsAdmin($groupId, $userNId);
    }

    /*
     * As a, administrator I can delete members to a group I manage
     *   - A member who has access to the resources shared with the group only because of its membership
     *   - A member who has access to some resources shared with the group because of other permissions
     */
    public function testAsADDeleteMembersSuccess()
    {
        // Define actors of this tests
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $userKId = UuidFactory::uuid('user.id.kathleen');
        $userLId = UuidFactory::uuid('user.id.lynne');
        $resourceCId = UuidFactory::uuid('resource.id.chai');

        // Retrieve the resources the group has access.
        $resources = $this->Resources->findAllByGroupAccess($groupId)->all()->toArray();
        $groupHasAccess = Hash::extract($resources, '{n}.id');

        // Build the request data.
        $changes = [];

        // Remove users from the group
        // Remove Kathleen who has access to the group resources only because of her membership.
        $changes[] = ['id' => UuidFactory::uuid("group_user.id.freelancer-kathleen"), 'delete' => true];

        // Remove a user who has its own access to the same resource the group has.
        // Remove lynne who has a direct access to the resource chai.
        $changes[] = ['id' => UuidFactory::uuid("group_user.id.freelancer-lynne"), 'delete' => true];

        // Update the group users.
        $this->authenticateAs('admin');
        $this->putJson("/groups/$groupId.json?api-version=v1", ['groups_users' => $changes]);
        $this->assertSuccess();

        // kathleen should not have anymore access to the group resources.
        $this->_assertUserHasNotAccessResources($userKId, $groupHasAccess);

        // Lynne should not have anymore access to the group resources (except chai).
        $userHasAccess = [$resourceCId];
        $userHasNotAccess = array_diff($groupHasAccess, $userHasAccess);
        $this->_assertUserHasNotAccessResources($userLId, $userHasNotAccess);
        $this->_assertUserHasAccessResources($userLId, $userHasAccess);
    }

    /*
     * As a administrator I can update a group (complex scenario).
     *
     * - Update group name
     * - Update members roles:
     *   - Remove the group manager role of a member
     *   - Add the group manager role to a member
     */
    public function testAsADUpdateGroupComplexScenarioSuccess()
    {
        // Define actors of this tests
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $userJId = UuidFactory::uuid('user.id.jean');
        $userNId = UuidFactory::uuid('user.id.nancy');

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

        // Try to add the user frances.
        $data = [
            'name' => 'Updated group name',
            'groups_users' => $changes
        ];

        // Update the group users.
        $this->authenticateAs('admin');
        $this->putJson("/groups/$groupId.json?api-version=v1", $data);
        $this->assertSuccess();

        // The name of the group should be updated
        $group = $this->Groups->get($groupId);
        $this->assertEquals($data['name'], $group->name);

        // Jean and Nancy should still have access to the resources.
        $this->_assertUserHasAccessResources($userJId, $groupHasAccess);
        $this->_assertUserHasAccessResources($userNId, $groupHasAccess);

        // Jean should no longer be a group manager of the group
        $this->_assertUserIsNotAdmin($groupId, $userJId);

        // Nancy should be a group manager of the group
        $this->_assertUserIsAdmin($groupId, $userNId);
    }

    // As an administrator I shouldn't be able to add users to a group
    public function testAsAdminCannotAddGroupUserError()
    {
        // Define actors of this tests
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $userCId = UuidFactory::uuid('user.id.carol');

        // Add a user who already has access to all of the resources the group has access.
        // Carol has the same access as the group Freelancer.
        // No secret need to be encrypted for the user.
        $changes[] = ['user_id' => $userCId];

        // Update the group name.
        $this->authenticateAs('admin');
        $this->putJson("/groups/$groupId.json?api-version=v1", ['groups_users' => $changes]);
        $this->assertSuccess();

        // The user carol shouldn't be member of the group
        $groupUser = $this->GroupsUsers->find()->where(['user_id' => $userCId, 'group_id' => $groupId])->first();
        $this->assertEmpty($groupUser);
    }

    public function testLostAccessFavoritesDeleted()
    {
        // Define actors of this tests
        $userLId = UuidFactory::uuid('user.id.lynne');
        $groupFId = UuidFactory::uuid('group.id.freelancer');
        $resourceCId = UuidFactory::uuid('resource.id.cakephp');

        // Build the changes.
        $changes = [];

        // Delete irene from the group developer
        $changes[] = ['id' => UuidFactory::uuid("group_user.id.freelancer-lynne"), 'delete' => true];

        // Update the group.
        $this->authenticateAs('admin');
        $this->putJson("/groups/$groupFId.json", ['groups_users' => $changes]);
        $this->assertSuccess();

        // As Irene is also member of the group ergonom, the favorite for cakephp shouldn't be removed.
        $resources = $this->Favorites->find()
            ->where(['user_id' => $userLId])
            ->all();
        $resourcesId = Hash::extract($resources->toArray(), '{n}.foreign_key');
        $this->assertNotcontains($resourceCId, $resourcesId);
    }

    // As an administrator I shouldn't be able to add users to a group
    public function testAsAdminCannotDeleteGroupUserError()
    {
        // Define actors of this tests
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $userKId = UuidFactory::uuid('user.id.nancy');

        // Remove users from the group
        // Remove Kathleen who has access to the group resources only because of her membership.
        $changes[] = ['id' => UuidFactory::uuid("group_user.id.freelancer-kathleen"), 'delete' => true];

        // Update the group name.
        $this->authenticateAs('admin');
        $this->putJson("/groups/$groupId.json?api-version=v1", ['groups_users' => $changes]);
        $this->assertSuccess();

        // The user Kathleen should still be member of the group
        $groupUser = $this->GroupsUsers->find()->where(['user_id' => $userKId, 'group_id' => $groupId])->first();
        $this->assertnotEmpty($groupUser);
    }

    public function testCannotModifyNotAccessibleFields()
    {
        $this->markTestIncomplete();
    }

    public function testErrorNotValidId()
    {
        $this->authenticateAs('ada');
        $groupId = 'invalid-id';
        $this->putJson("/groups/$groupId.json?api-version=v1");
        $this->assertError(400, 'The group id is not valid.');
    }

    public function testErrorDoesNotExistGroup()
    {
        $this->authenticateAs('ada');
        $groupId = UuidFactory::uuid();
        $this->putJson("/groups/$groupId.json?api-version=v1");
        $this->assertError(404, 'The group does not exist.');
    }

    public function testErrorGroupIsSoftDeleted()
    {
        $this->authenticateAs('admin');
        $groupId = UuidFactory::uuid('group.id.deleted');
        $this->putJson("/groups/$groupId.json?api-version=v1");
        $this->assertError(404, 'The group does not exist.');
    }

    public function testErrorAccessDenied()
    {
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $this->authenticateAs('ada');
        $this->putJson("/groups/$groupId.json?api-version=v1");
        $this->assertForbiddenError('You are not authorized to access that location.');
    }

    public function testErrorNotAuthenticated()
    {
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $postData = [];
        $this->putJson("/groups/$groupId.json?api-version=v1", $postData);
        $this->assertAuthenticationError();
    }
}
