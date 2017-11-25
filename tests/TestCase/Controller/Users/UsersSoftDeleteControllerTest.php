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

class UsersSoftDeleteControllerTest extends AppIntegrationTestCase
{
    public $Users;
    public $GroupsUsers;
    public $Permissions;

    public $fixtures = [
        'app.users', 'app.groups', 'app.profiles', 'app.gpgkeys', 'app.roles',
        'app.resources',
        'app.alt0/groups_users', 'app.alt0/permissions'
    ];

    public function setUp()
    {
        parent::setUp();
        $this->Users = TableRegistry::get('Users');
        $this->GroupsUsers = TableRegistry::get('GroupsUsers');
        $this->Permissions = TableRegistry::get('Permissions');
    }

    public function testUsersSoftDeleteDryRunSuccess()
    {
        $this->authenticateAs('admin');
        $francesId = UuidFactory::uuid('user.id.frances');
        $this->deleteJson('/users/' . $francesId . '/dry-run.json');
        $this->assertSuccess();
        $frances = $this->Users->get($francesId);
        $this->assertFalse($frances->deleted);
    }

    public function testUsersSoftDeleteDryRunError()
    {
        $this->authenticateAs('admin');
        $adaId = UuidFactory::uuid('user.id.ada');
        $this->deleteJson('/users/' . $adaId . '/dry-run.json');
        $this->assertError(400);
        $this->assertContains(
            'You need to transfer the user group manager role',
            $this->_responseJsonHeader->message
        );
    }

    public function testUsersSoftDeleteSuccess()
    {
        $this->authenticateAs('admin');
        $francesId = UuidFactory::uuid('user.id.frances');
        $this->deleteJson('/users/' . $francesId . '.json');
        $this->assertSuccess();
        $frances = $this->Users->get($francesId);
        $this->assertTrue($frances->deleted);
    }

    public function testUsersSoftDeleteNotLoggedInError()
    {
        $francesId = UuidFactory::uuid('user.id.frances');
        $this->deleteJson('/users/' . $francesId . '.json');
        $this->assertAuthenticationError();
    }

    public function testUsersSoftDeleteNotAdminError()
    {
        $this->authenticateAs('ada');
        $francesId = UuidFactory::uuid('user.id.frances');
        $this->deleteJson('/users/' . $francesId . '.json');
        $this->assertForbiddenError('You are not authorized to access that location.');
    }

    public function testUsersSoftDeleteInvalidUserError()
    {
        $this->authenticateAs('admin');
        $bogusId = '0';
        $this->deleteJson('/users/' . $bogusId . '.json');
        $this->assertError(400, 'The user id must be a valid uuid.');

        $this->authenticateAs('admin');
        $bogusId = 'true';
        $this->deleteJson('/users/' . $bogusId . '.json');
        $this->assertError(400, 'The user id must be a valid uuid.');

        $this->authenticateAs('admin');
        $bogusId = 'null';
        $this->deleteJson('/users/' . $bogusId . '.json');
        $this->assertError(400, 'The user id must be a valid uuid.');

        $this->authenticateAs('admin');
        $bogusId = '🔥';
        $this->deleteJson('/users/' . $bogusId . '.json');
        $this->assertError(400, 'The user id must be a valid uuid.');
    }

    public function testUsersSoftDeleteUserDoesNotExistError()
    {
        $this->authenticateAs('admin');
        $bogusId = UuidFactory::uuid('user.id.bogus');
        $this->deleteJson('/users/' . $bogusId . '.json');
        $this->assertError(404, 'The user does not exist or has been already deleted.');
    }

    public function testUsersSoftDeleteUserAlreadyDeletedError()
    {
        $this->authenticateAs('admin');
        $sofia = UuidFactory::uuid('user.id.sofia');
        $this->deleteJson('/users/' . $sofia . '.json');
        $this->assertError(404, 'The user does not exist or has been already deleted.');
    }

    public function testUsersSoftDeleteCannotDeleteSelfError()
    {
        $this->authenticateAs('admin');
        $adminId = UuidFactory::uuid('user.id.admin');
        $this->deleteJson('/users/' . $adminId . '.json');
        $this->assertError(400, 'You are not allowed to delete yourself.');
    }

    public function testUsersSoftDeleteSoleGroupOwnerError()
    {
        // Ada cannot be deleted because it's the sole manager of group accounting
        $this->authenticateAs('admin');
        $adaId = UuidFactory::uuid('user.id.ada');
        $this->deleteJson('/users/' . $adaId . '.json');
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

    public function testUsersSoftDeleteSoleResourceOwnerError()
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
        $this->deleteJson('/users/' . $adaId . '.json');
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

    public function testUsersSoftDeleteSoleManagerOfEmptyGroupOwningAResourceError()
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
        $this->deleteJson('/users/' . $adaId . '.json');
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
