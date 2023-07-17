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
class ActionLogsFinderResourcesCrudTest extends LogIntegrationTestCase
{
    use ActionLogsOperationsTestTrait;

    public $fixtures = [
        'app.Base/Users',
        'app.Base/Profiles',
        'app.Base/Resources',
        'app.Base/Permissions',
    ];

    public function testAuditLogsActionLogsFinderResourcesCreated()
    {
        $uac = new UserAccessControl(Role::USER, UuidFactory::uuid('user.id.ada'));
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->simulateResourceCrud($uac, $resourceId, EntityHistory::CRUD_CREATE);

        $ActionLogsFinder = new ResourceActionLogsFinder();
        $actionLogs = $ActionLogsFinder->find($uac, $resourceId);
        $actionLogs = (new ActionLogResultsParser($actionLogs->all(), ['resources' => [$resourceId]]))->parse();

        $this->assertEquals(count($actionLogs), 1);
        $this->assertEquals($actionLogs[0]['type'], 'Resources.created');
        $this->assertTrue(isset($actionLogs[0]['data']));
        $this->assertTrue(isset($actionLogs[0]['data']['resource']));
        $this->assertTrue(isset($actionLogs[0]['data']['resource']['name']));
        $this->assertEquals($actionLogs[0]['data']['resource']['name'], 'apache');
    }

    public function testAuditLogsActionLogsFinderResourcesUpdated()
    {
        $uac = new UserAccessControl(Role::USER, UuidFactory::uuid('user.id.ada'));
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->simulateResourceCrud($uac, $resourceId, EntityHistory::CRUD_UPDATE);

        $ActionLogsFinder = new ResourceActionLogsFinder();
        $actionLogs = $ActionLogsFinder->find($uac, $resourceId);
        $actionLogs = (new ActionLogResultsParser($actionLogs->all(), ['resources' => [$resourceId]]))->parse();

        $this->assertEquals(count($actionLogs), 1);
        $this->assertEquals($actionLogs[0]['type'], 'Resources.updated');
        $this->assertTrue(isset($actionLogs[0]['data']));
        $this->assertTrue(isset($actionLogs[0]['data']['resource']));
        $this->assertTrue(isset($actionLogs[0]['data']['resource']['name']));
        $this->assertEquals($actionLogs[0]['data']['resource']['name'], 'apache');
    }
}
