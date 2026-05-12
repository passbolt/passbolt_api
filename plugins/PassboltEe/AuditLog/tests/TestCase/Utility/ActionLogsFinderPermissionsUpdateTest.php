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
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UserAccessControl;
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

    public function testAuditLogsActionLogsFinderPermissionCreated()
    {
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        /** @var \App\Model\Entity\Resource $resourceA */
        $resourceA = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withSecretsFor([$userA])
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);
        $this->simulateShare(
            $uac,
            'Resource',
            $resourceA->id,
            'User',
            $userB->id,
            EntityHistory::CRUD_CREATE,
            Permission::READ
        );

        $ActionLogsFinder = new ResourceActionLogsFinder();
        $actionLogs = $ActionLogsFinder->find($uac, $resourceA->id);
        $actionLogs = (new ActionLogResultsParser($actionLogs->all(), ['resources' => [$resourceA->id]]))->parse();

        $this->assertEquals(count($actionLogs), 1);
        $this->assertEquals($actionLogs[0]['type'], 'Permissions.updated');
        $this->assertTrue(isset($actionLogs[0]['data']));
        $this->assertTrue(isset($actionLogs[0]['data']['permissions']));
        $this->assertTrue(isset($actionLogs[0]['data']['permissions']['added']));
        $this->assertNotEmpty($actionLogs[0]['data']['permissions']['added']);
        $this->assertCount(1, $actionLogs[0]['data']['permissions']['added']);
        $this->assertEquals($actionLogs[0]['data']['permissions']['added'][0]['type'], Permission::READ);
        $this->assertEquals($actionLogs[0]['data']['permissions']['added'][0]['resource']['id'], $resourceA->id);
        $this->assertEquals($actionLogs[0]['data']['permissions']['added'][0]['user']['id'], $userB->id);
    }

    public function testAuditLogsActionLogsFinderPermissionUpdated()
    {
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        /** @var \App\Model\Entity\Resource $resourceA */
        $resourceA = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withSecretsFor([$userA])
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);
        $this->simulateShare(
            $uac,
            //@@@@@@
            'Resource',
            $resourceA->id,
            'User',
            $userB->id,
            EntityHistory::CRUD_UPDATE,
            Permission::OWNER
        );

        $ActionLogsFinder = new ResourceActionLogsFinder();
        $actionLogs = $ActionLogsFinder->find($uac, $resourceA->id);
        $actionLogs = (new ActionLogResultsParser($actionLogs->all(), ['resources' => [$resourceA->id]]))->parse();

        $this->assertEquals(count($actionLogs), 1);
        $this->assertEquals($actionLogs[0]['type'], 'Permissions.updated');
        $this->assertTrue(isset($actionLogs[0]['data']));
        $this->assertTrue(isset($actionLogs[0]['data']['permissions']));
        $this->assertTrue(isset($actionLogs[0]['data']['permissions']['updated']));
        $this->assertNotEmpty($actionLogs[0]['data']['permissions']['updated']);
        $this->assertCount(1, $actionLogs[0]['data']['permissions']['updated']);
        $this->assertEquals($actionLogs[0]['data']['permissions']['updated'][0]['type'], Permission::OWNER);
        $this->assertEquals($actionLogs[0]['data']['permissions']['updated'][0]['resource']['id'], $resourceA->id);
        $this->assertEquals($actionLogs[0]['data']['permissions']['updated'][0]['user']['id'], $userB->id);
    }

    public function testAuditLogsActionLogsFinderPermissionRemoved()
    {
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        /** @var \App\Model\Entity\Resource $resourceA */
        $resourceA = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withSecretsFor([$userA])
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);
        $this->simulateShare(
            $uac,
            'Resource',
            $resourceA->id,
            'User',
            $userB->id,
            EntityHistory::CRUD_DELETE,
            Permission::OWNER
        );

        $ActionLogsFinder = new ResourceActionLogsFinder();
        $actionLogs = $ActionLogsFinder->find($uac, $resourceA->id);
        $actionLogs = (new ActionLogResultsParser($actionLogs->all(), ['resources' => [$resourceA->id]]))->parse();

        $this->assertEquals(count($actionLogs), 1);
        $this->assertEquals($actionLogs[0]['type'], 'Permissions.updated');
        $this->assertTrue(isset($actionLogs[0]['data']));
        $this->assertTrue(isset($actionLogs[0]['data']['permissions']));
        $this->assertTrue(isset($actionLogs[0]['data']['permissions']['removed']));
        $this->assertNotEmpty($actionLogs[0]['data']['permissions']['removed']);
        $this->assertCount(1, $actionLogs[0]['data']['permissions']['removed']);
        $this->assertEquals($actionLogs[0]['data']['permissions']['removed'][0]['type'], Permission::OWNER);
        $this->assertEquals($actionLogs[0]['data']['permissions']['removed'][0]['resource']['id'], $resourceA->id);
        $this->assertEquals($actionLogs[0]['data']['permissions']['removed'][0]['user']['id'], $userB->id);
    }
}
