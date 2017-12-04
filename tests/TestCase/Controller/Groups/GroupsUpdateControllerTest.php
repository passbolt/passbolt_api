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

use App\Model\Entity\Permission;
use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\Gpg;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class GroupsUpdateControllerTest extends AppIntegrationTestCase
{
    public $fixtures = ['app.groups', 'app.groups_users', 'app.resources', 'app.permissions', 'app.users',
        'app.secrets', 'app.profiles', 'app.gpgkeys', 'app.roles', 'app.favorites'];

    public function setUp()
    {
        $this->Groups = TableRegistry::get('Groups');
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
                ->where(['foreign_id' => $resourceId, 'user_id' => $userId])->first();
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

    public function testAsGroupManagerSuccess()
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
        $this->putJson("/groups/$groupId.json", ['groups_users' => $changes, 'secrets' => $secrets]);
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

    public function testAsAdminSuccess()
    {
        // Define actors of this tests
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $userFId = UuidFactory::uuid('user.id.frances');

        // Retrieve the resources the group has access.
        $resources = $this->Resources->findAllByGroupAccess($groupId)->all()->toArray();
        $groupHasAccess = Hash::extract($resources, '{n}.id');

        // Try to add the user frances.
        $data = [
            'name' => 'Updated group name',
            'groups_users' => [['user_id' => $userFId]]
        ];

        // Update the group name.
        $this->authenticateAs('admin');
        $this->putJson("/groups/$groupId.json", $data);
        $this->assertSuccess();

        // The name of the group should be updated
        $group = $this->Groups->get($groupId);
        $this->assertEquals($data['name'], $group->name);

        // No secrets members should be updated
        $this->_assertUserHasNotAccessResources($userFId, $groupHasAccess);
    }

    public function testAdminCannotUpdateGroupsUsers()
    {
        // Define actors of this tests
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $userFId = UuidFactory::uuid('user.id.frances');

        // Retrieve the resources the group has access.
        $resources = $this->Resources->findAllByGroupAccess($groupId)->all()->toArray();
        $groupHasAccess = Hash::extract($resources, '{n}.id');

        // Try to add the user frances.
        $data = [
            'groups_users' => [['user_id' => $userFId]]
        ];

        // Update the group name.
        $this->authenticateAs('admin');
        $this->putJson("/groups/$groupId.json", $data);
        $this->assertSuccess();

        // No secrets members should be updated
        $this->_assertUserHasNotAccessResources($userFId, $groupHasAccess);
    }

    public function testCannotModifyNotAccessibleFields()
    {
        $this->markTestIncomplete();
    }

    public function testErrorNotValidId()
    {
        $this->authenticateAs('ada');
        $groupId = 'invalid-id';
        $this->putJson("/groups/$groupId.json");
        $this->assertError(400, 'The group id is not valid.');
    }

    public function testErrorDoesNotExistGroup()
    {
        $this->authenticateAs('ada');
        $groupId = UuidFactory::uuid();
        $this->putJson("/groups/$groupId.json");
        $this->assertError(404, 'The group does not exist.');
    }

    public function testErrorGroupIsSoftDeleted()
    {
        $this->authenticateAs('admin');
        $groupId = UuidFactory::uuid('group.id.deleted');
        $this->putJson("/groups/$groupId.json");
        $this->assertError(404, 'The group does not exist.');
    }

    public function testErrorAccessDenied()
    {
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $this->authenticateAs('ada');
        $this->putJson("/groups/$groupId.json");
        $this->assertForbiddenError('You are not authorized to access that location.');
    }

    public function testErrorNotAuthenticated()
    {
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $postData = [];
        $this->putJson("/groups/$groupId.json", $postData);
        $this->assertAuthenticationError();
    }
}
