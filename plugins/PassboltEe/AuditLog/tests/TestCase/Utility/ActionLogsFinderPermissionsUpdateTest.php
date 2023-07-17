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
use Passbolt\AuditLog\Test\Lib\ActionLogsOperationsTestTrait;
use Passbolt\AuditLog\Utility\ActionLogResultsParser;
use Passbolt\AuditLog\Utility\ResourceActionLogsFinder;
use Passbolt\Log\Model\Entity\EntityHistory;
use Passbolt\Log\Test\Lib\LogIntegrationTestCase;

/**
 * @property \App\Model\Table\UsersTable $Users
 * @property \Passbolt\Log\Model\Table\PermissionsHistoryTable&\Cake\ORM\Association\BelongsTo $PermissionsHistory
 */
class ActionLogsFinderPermissionsUpdateTest extends LogIntegrationTestCase
{
    use ActionLogsOperationsTestTrait;

    public $fixtures = [
        'app.Base/Users',
        'app.Base/Profiles',
        'app.Base/Resources',
        'app.Base/Permissions',
    ];

    public function testAuditLogsActionLogsFinderPermissionCreated()
    {
        $uac = new UserAccessControl(Role::USER, UuidFactory::uuid('user.id.ada'));
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->simulateShare(
            $uac,
            'Resource',
            $resourceId,
            'User',
            UuidFactory::uuid('user.id.betty'),
            EntityHistory::CRUD_CREATE,
            Permission::READ
        );

        $ActionLogsFinder = new ResourceActionLogsFinder();
        $actionLogs = $ActionLogsFinder->find($uac, $resourceId);
        $actionLogs = (new ActionLogResultsParser($actionLogs->all(), ['resources' => [$resourceId]]))->parse();

        $this->assertEquals(count($actionLogs), 1);
        $this->assertEquals($actionLogs[0]['type'], 'Permissions.updated');
        $this->assertTrue(isset($actionLogs[0]['data']));
        $this->assertTrue(isset($actionLogs[0]['data']['permissions']));
        $this->assertTrue(isset($actionLogs[0]['data']['permissions']['added']));
        $this->assertNotEmpty($actionLogs[0]['data']['permissions']['added']);
        $this->assertCount(1, $actionLogs[0]['data']['permissions']['added']);
        $this->assertEquals($actionLogs[0]['data']['permissions']['added'][0]['type'], Permission::READ);
        $this->assertEquals($actionLogs[0]['data']['permissions']['added'][0]['resource']['id'], $resourceId);
        $this->assertEquals($actionLogs[0]['data']['permissions']['added'][0]['user']['id'], UuidFactory::uuid('user.id.betty'));
    }

    public function testAuditLogsActionLogsFinderPermissionUpdated()
    {
        $uac = new UserAccessControl(Role::USER, UuidFactory::uuid('user.id.ada'));
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->simulateShare(
            $uac,
            //@@@@@@
            'Resource',
            $resourceId,
            'User',
            UuidFactory::uuid('user.id.betty'),
            EntityHistory::CRUD_UPDATE,
            Permission::OWNER
        );

        $ActionLogsFinder = new ResourceActionLogsFinder();
        $actionLogs = $ActionLogsFinder->find($uac, $resourceId);
        $actionLogs = (new ActionLogResultsParser($actionLogs->all(), ['resources' => [$resourceId]]))->parse();

        $this->assertEquals(count($actionLogs), 1);
        $this->assertEquals($actionLogs[0]['type'], 'Permissions.updated');
        $this->assertTrue(isset($actionLogs[0]['data']));
        $this->assertTrue(isset($actionLogs[0]['data']['permissions']));
        $this->assertTrue(isset($actionLogs[0]['data']['permissions']['updated']));
        $this->assertNotEmpty($actionLogs[0]['data']['permissions']['updated']);
        $this->assertCount(1, $actionLogs[0]['data']['permissions']['updated']);
        $this->assertEquals($actionLogs[0]['data']['permissions']['updated'][0]['type'], Permission::OWNER);
        $this->assertEquals($actionLogs[0]['data']['permissions']['updated'][0]['resource']['id'], $resourceId);
        $this->assertEquals($actionLogs[0]['data']['permissions']['updated'][0]['user']['id'], UuidFactory::uuid('user.id.betty'));
    }

    public function testAuditLogsActionLogsFinderPermissionRemoved()
    {
        $uac = new UserAccessControl(Role::USER, UuidFactory::uuid('user.id.ada'));
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->simulateShare(
            $uac,
            'Resource',
            $resourceId,
            'User',
            UuidFactory::uuid('user.id.betty'),
            EntityHistory::CRUD_DELETE,
            Permission::OWNER
        );

        $ActionLogsFinder = new ResourceActionLogsFinder();
        $actionLogs = $ActionLogsFinder->find($uac, $resourceId);
        $actionLogs = (new ActionLogResultsParser($actionLogs->all(), ['resources' => [$resourceId]]))->parse();

        $this->assertEquals(count($actionLogs), 1);
        $this->assertEquals($actionLogs[0]['type'], 'Permissions.updated');
        $this->assertTrue(isset($actionLogs[0]['data']));
        $this->assertTrue(isset($actionLogs[0]['data']['permissions']));
        $this->assertTrue(isset($actionLogs[0]['data']['permissions']['removed']));
        $this->assertNotEmpty($actionLogs[0]['data']['permissions']['removed']);
        $this->assertCount(1, $actionLogs[0]['data']['permissions']['removed']);
        $this->assertEquals($actionLogs[0]['data']['permissions']['removed'][0]['type'], Permission::OWNER);
        $this->assertEquals($actionLogs[0]['data']['permissions']['removed'][0]['resource']['id'], $resourceId);
        $this->assertEquals($actionLogs[0]['data']['permissions']['removed'][0]['user']['id'], UuidFactory::uuid('user.id.betty'));
    }
}
