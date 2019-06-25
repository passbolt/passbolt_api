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

namespace Passbolt\AuditLog\Test\TestCase\Utility;

use App\Model\Entity\Role;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Passbolt\AuditLog\Test\TestCase\Traits\ActionLogsOperationsTrait;
use Passbolt\AuditLog\Utility\ActionLogsFinder;
use Passbolt\Log\Model\Entity\EntityHistory;
use Passbolt\Log\Test\Lib\LogIntegrationTestCase;

class ActionLogsFinderResourcesCrudTest extends LogIntegrationTestCase
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
        'app.Base/Permissions',
        'app.Base/Secrets',
        'app.Base/Favorites',
        'app.Base/EmailQueue',
        'plugin.Passbolt/Log.Base/Actions',
        'plugin.Passbolt/Log.Base/ActionLogs',
        'plugin.Passbolt/Log.Base/EntitiesHistory',
        'plugin.Passbolt/Log.Base/PermissionsHistory',
        'plugin.Passbolt/Log.Base/SecretAccesses',
        'plugin.Passbolt/Log.Base/SecretsHistory'
    ];

    public function setUp()
    {
        parent::setUp();
        $this->Users = TableRegistry::getTableLocator()->get('Users');
        $this->PermissionsHistory = TableRegistry::getTableLocator()->get('Passbolt/Log.PermissionsHistory');
    }

    public function testActionLogsFinderResourcesCreated()
    {
        $uac = new UserAccessControl(Role::USER, UuidFactory::uuid('user.id.ada'));
        $this->simulateResourceCrud($uac, UuidFactory::uuid('resource.id.apache'), EntityHistory::CRUD_CREATE);

        $ActionLogsFinder = new ActionLogsFinder();
        $actionLogs = $ActionLogsFinder->findForResource($uac, UuidFactory::uuid('resource.id.apache'));

        $this->assertEquals(count($actionLogs), 1);
        $this->assertEquals($actionLogs[0]['type'], 'Resources.created');
        $this->assertTrue(isset($actionLogs[0]['data']));
        $this->assertTrue(isset($actionLogs[0]['data']['resource']));
        $this->assertTrue(isset($actionLogs[0]['data']['resource']['name']));
        $this->assertEquals($actionLogs[0]['data']['resource']['name'], 'apache');
    }

    public function testActionLogsFinderResourcesUpdated()
    {
        $uac = new UserAccessControl(Role::USER, UuidFactory::uuid('user.id.ada'));
        $this->simulateResourceCrud($uac, UuidFactory::uuid('resource.id.apache'), EntityHistory::CRUD_UPDATE);

        $ActionLogsFinder = new ActionLogsFinder();
        $actionLogs = $ActionLogsFinder->findForResource($uac, UuidFactory::uuid('resource.id.apache'));

        $this->assertEquals(count($actionLogs), 1);
        $this->assertEquals($actionLogs[0]['type'], 'Resources.updated');
        $this->assertTrue(isset($actionLogs[0]['data']));
        $this->assertTrue(isset($actionLogs[0]['data']['resource']));
        $this->assertTrue(isset($actionLogs[0]['data']['resource']['name']));
        $this->assertEquals($actionLogs[0]['data']['resource']['name'], 'apache');
    }
}
