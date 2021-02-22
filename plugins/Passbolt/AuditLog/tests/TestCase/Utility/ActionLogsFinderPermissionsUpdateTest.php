<?php
declare(strict_types=1);

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

namespace Passbolt\AuditLog\Test\TestCase\Utility;

use App\Model\Entity\Permission;
use App\Model\Entity\Role;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Passbolt\AuditLog\Test\TestCase\Traits\ActionLogsOperationsTrait;
use Passbolt\AuditLog\Utility\ActionLogsFinder;
use Passbolt\Log\Model\Entity\EntityHistory;
use Passbolt\Log\Test\Lib\LogIntegrationTestCase;

class ActionLogsFinderPermissionsUpdateTest extends LogIntegrationTestCase
{
    use ActionLogsOperationsTrait;

    public $fixtures = [
        'app.Base/Users',
        'app.Base/Gpgkeys',
        'app.Base/Profiles',
        'app.Base/Avatars',
        'app.Base/Roles',
        'app.Base/Groups',
        'app.Base/GroupsUsers',
        'app.Base/Resources',
        'app.Base/ResourceTypes',
        'app.Base/Permissions',
        'app.Base/Secrets',
        'app.Base/Favorites',
    ];

    public function setUp()
    {
        parent::setUp();
        $this->Users = TableRegistry::getTableLocator()->get('Users');
        $this->PermissionsHistory = TableRegistry::getTableLocator()->get('Passbolt/Log.PermissionsHistory');
    }

    public function testAuditLogsActionLogsFinderPermissionCreated()
    {
        $uac = new UserAccessControl(Role::USER, UuidFactory::uuid('user.id.ada'));
        $this->simulateShare(
            $uac,
            'Resource',
            UuidFactory::uuid('resource.id.apache'),
            'User',
            UuidFactory::uuid('user.id.betty'),
            EntityHistory::CRUD_CREATE,
            Permission::READ
        );

        $ActionLogsFinder = new ActionLogsFinder();
        $actionLogs = $ActionLogsFinder->findForResource($uac, UuidFactory::uuid('resource.id.apache'));

        $this->assertEquals(count($actionLogs), 1);
        $this->assertEquals($actionLogs[0]['type'], 'Permissions.updated');
        $this->assertTrue(isset($actionLogs[0]['data']));
        $this->assertTrue(isset($actionLogs[0]['data']['permissions']));
        $this->assertTrue(isset($actionLogs[0]['data']['permissions']['added']));
        $this->assertNotEmpty($actionLogs[0]['data']['permissions']['added']);
        $this->assertCount(1, $actionLogs[0]['data']['permissions']['added']);
        $this->assertEquals($actionLogs[0]['data']['permissions']['added'][0]['type'], Permission::READ);
        $this->assertEquals($actionLogs[0]['data']['permissions']['added'][0]['resource']['id'], UuidFactory::uuid('resource.id.apache'));
        $this->assertEquals($actionLogs[0]['data']['permissions']['added'][0]['user']['id'], UuidFactory::uuid('user.id.betty'));
    }

    public function testAuditLogsActionLogsFinderPermissionUpdated()
    {
        $uac = new UserAccessControl(Role::USER, UuidFactory::uuid('user.id.ada'));
        $this->simulateShare(
            $uac,
            //@@@@@@
            'Resource',
            UuidFactory::uuid('resource.id.apache'),
            'User',
            UuidFactory::uuid('user.id.betty'),
            EntityHistory::CRUD_UPDATE,
            Permission::OWNER
        );

        $ActionLogsFinder = new ActionLogsFinder();
        $actionLogs = $ActionLogsFinder->findForResource($uac, UuidFactory::uuid('resource.id.apache'));

        $this->assertEquals(count($actionLogs), 1);
        $this->assertEquals($actionLogs[0]['type'], 'Permissions.updated');
        $this->assertTrue(isset($actionLogs[0]['data']));
        $this->assertTrue(isset($actionLogs[0]['data']['permissions']));
        $this->assertTrue(isset($actionLogs[0]['data']['permissions']['updated']));
        $this->assertNotEmpty($actionLogs[0]['data']['permissions']['updated']);
        $this->assertCount(1, $actionLogs[0]['data']['permissions']['updated']);
        $this->assertEquals($actionLogs[0]['data']['permissions']['updated'][0]['type'], Permission::OWNER);
        $this->assertEquals($actionLogs[0]['data']['permissions']['updated'][0]['resource']['id'], UuidFactory::uuid('resource.id.apache'));
        $this->assertEquals($actionLogs[0]['data']['permissions']['updated'][0]['user']['id'], UuidFactory::uuid('user.id.betty'));
    }

    public function testAuditLogsActionLogsFinderPermissionRemoved()
    {
        $uac = new UserAccessControl(Role::USER, UuidFactory::uuid('user.id.ada'));
        $this->simulateShare(
            $uac,
            'Resource',
            UuidFactory::uuid('resource.id.apache'),
            'User',
            UuidFactory::uuid('user.id.betty'),
            EntityHistory::CRUD_DELETE,
            Permission::OWNER
        );

        $ActionLogsFinder = new ActionLogsFinder();
        $actionLogs = $ActionLogsFinder->findForResource($uac, UuidFactory::uuid('resource.id.apache'));

        $this->assertEquals(count($actionLogs), 1);
        $this->assertEquals($actionLogs[0]['type'], 'Permissions.updated');
        $this->assertTrue(isset($actionLogs[0]['data']));
        $this->assertTrue(isset($actionLogs[0]['data']['permissions']));
        $this->assertTrue(isset($actionLogs[0]['data']['permissions']['removed']));
        $this->assertNotEmpty($actionLogs[0]['data']['permissions']['removed']);
        $this->assertCount(1, $actionLogs[0]['data']['permissions']['removed']);
        $this->assertEquals($actionLogs[0]['data']['permissions']['removed'][0]['type'], Permission::OWNER);
        $this->assertEquals($actionLogs[0]['data']['permissions']['removed'][0]['resource']['id'], UuidFactory::uuid('resource.id.apache'));
        $this->assertEquals($actionLogs[0]['data']['permissions']['removed'][0]['user']['id'], UuidFactory::uuid('user.id.betty'));
    }
}
