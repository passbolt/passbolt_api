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
namespace App\Test\TestCase\Controller\Users;

use App\Model\Entity\Permission;
use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class UsersDeleteControllerTest extends AppIntegrationTestCase
{
    public $Users;
    public $GroupsUsers;
    public $Permissions;

    public $fixtures = [
        'app.Base/users', 'app.Base/groups', 'app.Base/profiles', 'app.Base/gpgkeys', 'app.Base/roles',
        'app.Base/resources',
        'app.Alt0/groups_users', 'app.Alt0/permissions', 'app.Base/avatars', 'app.Base/favorites', 'app.Base/email_queue'
    ];

    public function setUp()
    {
        parent::setUp();
        $this->Users = TableRegistry::get('Users');
        $this->GroupsUsers = TableRegistry::get('GroupsUsers');
        $this->Permissions = TableRegistry::get('Permissions');
    }

    public function testUsersDeleteDryRunSuccess()
    {
        $this->authenticateAs('admin');
        $francesId = UuidFactory::uuid('user.id.frances');
        $this->deleteJson('/users/' . $francesId . '/dry-run.json?api-version=v1');
        $this->assertSuccess();
        $frances = $this->Users->get($francesId);
        $this->assertFalse($frances->deleted);
    }

    public function testUsersDeleteDryRunError()
    {
        $this->authenticateAs('admin');
        $adaId = UuidFactory::uuid('user.id.ada');
        $this->deleteJson('/users/' . $adaId . '/dry-run.json?api-version=v1');
        $this->assertError(400);
        $this->assertContains(
            'You need to transfer the user group manager role',
            $this->_responseJsonHeader->message
        );
    }

    public function testUsersDeleteSuccess()
    {
        $this->authenticateAs('admin');
        $francesId = UuidFactory::uuid('user.id.frances');
        $this->deleteJson('/users/' . $francesId . '.json?api-version=v1');
        $this->assertSuccess();
        $frances = $this->Users->get($francesId);
        $this->assertTrue($frances->deleted);
    }

    public function testUsersDeleteNotLoggedInError()
    {
        $francesId = UuidFactory::uuid('user.id.frances');
        $this->deleteJson('/users/' . $francesId . '.json?api-version=v1');
        $this->assertAuthenticationError();
    }

    public function testUsersDeleteNotAdminError()
    {
        $this->authenticateAs('ada');
        $francesId = UuidFactory::uuid('user.id.frances');
        $this->deleteJson('/users/' . $francesId . '.json?api-version=v1');
        $this->assertForbiddenError('You are not authorized to access that location.');
    }

    public function testUsersDeleteInvalidUserError()
    {
        $this->authenticateAs('admin');
        $bogusId = '0';
        $this->deleteJson('/users/' . $bogusId . '.json?api-version=v1');
        $this->assertError(400, 'The user id must be a valid uuid.');

        $this->authenticateAs('admin');
        $bogusId = 'true';
        $this->deleteJson('/users/' . $bogusId . '.json?api-version=v1');
        $this->assertError(400, 'The user id must be a valid uuid.');

        $this->authenticateAs('admin');
        $bogusId = 'null';
        $this->deleteJson('/users/' . $bogusId . '.json?api-version=v1');
        $this->assertError(400, 'The user id must be a valid uuid.');

        $this->authenticateAs('admin');
        $bogusId = '🔥';
        $this->deleteJson('/users/' . $bogusId . '.json?api-version=v1');
        $this->assertError(400, 'The user id must be a valid uuid.');
    }

    public function testUsersDeleteUserDoesNotExistError()
    {
        $this->authenticateAs('admin');
        $bogusId = UuidFactory::uuid('user.id.bogus');
        $this->deleteJson('/users/' . $bogusId . '.json?api-version=v1');
        $this->assertError(404, 'The user does not exist or has been already deleted.');
    }

    public function testUsersDeleteUserAlreadyDeletedError()
    {
        $this->authenticateAs('admin');
        $sofia = UuidFactory::uuid('user.id.sofia');
        $this->deleteJson('/users/' . $sofia . '.json?api-version=v1');
        $this->assertError(404, 'The user does not exist or has been already deleted.');
    }

    public function testUsersDeleteCannotDeleteSelfError()
    {
        $this->authenticateAs('admin');
        $adminId = UuidFactory::uuid('user.id.admin');
        $this->deleteJson('/users/' . $adminId . '.json?api-version=v1');
        $this->assertError(400, 'You are not allowed to delete yourself.');
    }

    public function testUsersDeleteSoleGroupOwnerError()
    {
        // Ada cannot be deleted because it's the sole manager of group accounting
        $this->authenticateAs('admin');
        $adaId = UuidFactory::uuid('user.id.ada');
        $this->deleteJson('/users/' . $adaId . '.json?api-version=v1');
        $this->assertError(400);
        $this->assertContains(
            'You need to transfer the user group manager role',
            $this->_responseJsonHeader->message
        );

        // Check accounting group is returned
        $this->assertEquals(1, count($this->_responseJsonBody->groups));
        $group = $this->_responseJsonBody->groups[0]->Group;
        $this->assertGroupAttributes($group);
        $this->assertEquals($group->id, UuidFactory::uuid('group.id.accounting'));
    }

    public function testUsersDeleteSoleResourceOwnerError()
    {
        // Make betty admin of accounting group
        $groupUser = $this->GroupsUsers->find()->select()->where([
            'group_id' => UuidFactory::uuid('group.id.accounting'),
            'user_id' => UuidFactory::uuid('user.id.betty')
        ])->first();
        $groupUser->is_admin = true;
        $this->GroupsUsers->save($groupUser);

        // Ada now cannot be deleted because it's the sole owner of the
        // shared resource april
        $this->authenticateAs('admin');
        $adaId = UuidFactory::uuid('user.id.ada');
        $this->deleteJson('/users/' . $adaId . '.json?api-version=v1');
        $this->assertError(400);
        $this->assertContains(
            'You need to transfer the ownership for the shared passwords',
            $this->_responseJsonHeader->message
        );

        // Check april resource is returned
        $this->assertEquals(1, count($this->_responseJsonBody->resources));
        $resource = $this->_responseJsonBody->resources[0]->Resource;
        $this->assertResourceAttributes($resource);
        $this->assertEquals($resource->id, UuidFactory::uuid('resource.id.april'));
    }

    /**
     * Assert that a user who is the sole owner of soft deleted resource can be deleted.
     */
    public function testUsersDeleteSoleDeletedResourceOwnerError()
    {
        // Remove ada as the owner of canjs.
        $permission = $this->Permissions->find()->select()->where([
            'aco_foreign_key' => UuidFactory::uuid('resource.id.canjs'),
            'aro_foreign_key' => UuidFactory::uuid('user.id.ada')
        ])->first();
        $permission->type = Permission::READ;
        $this->Permissions->save($permission);

        // Betty now cannot be deleted because it's the sole owner of the
        // shared resource canjs.
        $this->authenticateAs('admin');
        $bettyId = UuidFactory::uuid('user.id.betty');
        $this->deleteJson('/users/' . $bettyId . '.json?api-version=v1');
        $this->assertError(400);
        // Canjs should be returned as the shared resource which Betty is the sole owner of.
        $this->assertEquals(1, count($this->_responseJsonBody->resources));
        $resource = $this->_responseJsonBody->resources[0]->Resource;
        $this->assertEquals($resource->id, UuidFactory::uuid('resource.id.canjs'));

        // We soft delete canjs, so that there is no more restriction for deletion.
        $this->Resources = TableRegistry::get('Resources');
        $entityToDelete = $this->Resources->find()->where([
            'id' => UuidFactory::uuid('resource.id.canjs')
        ])->first();
        $deleted = $this->Resources->softDelete($bettyId, $entityToDelete);
        $this->assertTrue($deleted);

        // Try to delete the user again.
        $this->deleteJson('/users/' . $bettyId . '.json?api-version=v1');
        $this->assertSuccess();
    }

    public function testUsersDeleteSoleManagerOfEmptyGroupOwningAResourceError()
    {
        // Make betty admin of accounting group
        $groupUser = $this->GroupsUsers->find()->select()->where([
            'group_id' => UuidFactory::uuid('group.id.accounting'),
            'user_id' => UuidFactory::uuid('user.id.betty')
        ])->first();
        $groupUser->is_admin = true;
        $this->GroupsUsers->save($groupUser);

        // Make betty own the april resource
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => UuidFactory::uuid('user.id.betty'),
            'aco_foreign_key' => UuidFactory::uuid('resource.id.april')
        ])->first();
        $permission->type = Permission::OWNER;
        $this->Permissions->save($permission);

        // Ada now cannot be deleted because it's the sole owner of the
        // shared resource april
        $this->authenticateAs('admin');
        $adaId = UuidFactory::uuid('user.id.ada');
        $this->deleteJson('/users/' . $adaId . '.json?api-version=v1');
        $this->assertError(400);
        $this->assertContains(
            'This user is the only admin of one (or more) group that is the sole owner of shared',
            $this->_responseJsonHeader->message
        );

        // Check framasoft resource is returned
        $this->assertEquals(1, count($this->_responseJsonBody->resources));
        $resource = $this->_responseJsonBody->resources[0]->Resource;
        $this->assertResourceAttributes($resource);
        $this->assertEquals($resource->id, UuidFactory::uuid('resource.id.framasoft'));
    }
}
