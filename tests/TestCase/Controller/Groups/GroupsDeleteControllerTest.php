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
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class GroupsDeleteControllerTest extends AppIntegrationTestCase
{
    public $Groups;
    public $GroupsGroups;
    public $Permissions;

    public $fixtures = [
        'app.Base/users', 'app.Base/groups', 'app.Base/profiles', 'app.Base/gpgkeys', 'app.Base/roles',
        'app.Base/resources', 'app.Alt0/groups_users', 'app.Alt0/permissions', 'app.Base/avatars'
    ];

    public function setUp()
    {
        parent::setUp();
        $this->Groups = TableRegistry::get('Groups');
        $this->GroupsGroups = TableRegistry::get('GroupsGroups');
        $this->Permissions = TableRegistry::get('Permissions');
    }

    public function testGroupsDeleteDryRunSuccess()
    {
        $this->authenticateAs('admin');
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $this->deleteJson('/groups/' . $groupId . '/dry-run.json?api-version=v1');
        $this->assertSuccess();
        $group = $this->Groups->get($groupId);
        $this->assertFalse($group->deleted);
    }

    public function testGroupsDeleteDryRunError()
    {
        $this->authenticateAs('admin');
        $groupId = UuidFactory::uuid('group.id.creative');
        $this->deleteJson('/groups/' . $groupId . '/dry-run.json?api-version=v1');
        $this->assertError(400);
        $this->assertContains(
            'You need to transfer the ownership for the shared passwords',
            $this->_responseJsonHeader->message
        );
    }

    public function testGroupsDeleteAsAdminSuccess()
    {
        $this->authenticateAs('admin');
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $this->deleteJson('/groups/' . $groupId . '.json?api-version=v1');
        $this->assertSuccess();
        $group = $this->Groups->get($groupId);
        $this->assertTrue($group->deleted);
    }

    public function testGroupsDeleteAsGroupOwnerSuccess()
    {
        $this->authenticateAs('edith');
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $this->deleteJson('/groups/' . $groupId . '.json?api-version=v1');
        $this->assertSuccess();
        $group = $this->Groups->get($groupId);
        $this->assertTrue($group->deleted);
    }

    public function testGroupsDeleteNotLoggedInError()
    {
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $this->deleteJson('/groups/' . $groupId . '.json?api-version=v1');
        $this->assertAuthenticationError();
    }

    public function testGroupsDeleteNotAdminError()
    {
        $this->authenticateAs('ada');
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $this->deleteJson('/groups/' . $groupId . '.json?api-version=v1');
        $this->assertForbiddenError('You are not authorized to access that location.');
    }

    public function testGroupsDeleteInvalidGroupError()
    {
        $this->authenticateAs('admin');
        $bogusId = '0';
        $this->deleteJson('/groups/' . $bogusId . '.json?api-version=v1');
        $this->assertError(400, 'The group id must be a valid uuid.');

        $this->authenticateAs('admin');
        $bogusId = 'true';
        $this->deleteJson('/groups/' . $bogusId . '.json?api-version=v1');
        $this->assertError(400, 'The group id must be a valid uuid.');

        $this->authenticateAs('admin');
        $bogusId = 'null';
        $this->deleteJson('/groups/' . $bogusId . '.json?api-version=v1');
        $this->assertError(400, 'The group id must be a valid uuid.');

        $this->authenticateAs('admin');
        $bogusId = '🔥';
        $this->deleteJson('/groups/' . $bogusId . '.json?api-version=v1');
        $this->assertError(400, 'The group id must be a valid uuid.');
    }

    public function testGroupsDeleteGroupDoesNotExistError()
    {
        $this->authenticateAs('admin');
        $bogusId = UuidFactory::uuid('group.id.bogus');
        $this->deleteJson('/groups/' . $bogusId . '.json?api-version=v1');
        $this->assertError(404, 'The group does not exist or has been already deleted.');
    }

    public function testGroupsDeleteGroupAlreadyDeletedError()
    {
        // Delete the group twice
        $this->authenticateAs('admin');
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $this->deleteJson('/groups/' . $groupId . '.json?api-version=v1');
        $this->deleteJson('/groups/' . $groupId . '.json?api-version=v1');
        $this->assertError(404, 'The group does not exist or has been already deleted.');
    }

    public function testGroupsDeleteSoleResourceOwnerError()
    {
        $this->authenticateAs('admin');
        $groupId = UuidFactory::uuid('group.id.creative');
        $this->deleteJson('/groups/' . $groupId . '/dry-run.json?api-version=v1');
        $this->assertError(400);
        $this->assertContains(
            'You need to transfer the ownership for the shared passwords',
            $this->_responseJsonHeader->message
        );
        $this->assertNotEmpty($this->_responseJsonBody);
        $this->assertResourceAttributes($this->_responseJsonBody->resources[0]->Resource);
    }
}
