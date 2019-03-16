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

use App\Model\Entity\Permission;
use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use App\Model\Entity\Role;
use Passbolt\Log\Model\Entity\EntityHistory;
use Passbolt\AuditLog\Utility\ActionLogsFinder;
use Passbolt\AuditLog\Test\TestCase\Traits\ActionLogsOperationsTrait;

class ActionLogsFinderResourceSecretUpdateTest extends AppIntegrationTestCase
{
    use ActionLogsOperationsTrait;

    public $fixtures = [
        'app.Base/users',
        'app.Base/gpgkeys',
        'app.Base/profiles',
        'app.Base/avatars',
        'app.Base/roles',
        'app.Base/groups',
        'app.Base/groups_users',
        'app.Base/resources',
        'app.Base/permissions',
        'app.Base/secrets',
        'app.Base/favorites',
        'app.Base/email_queue',
        'app.Base/secret_accesses',
        'plugin.passbolt/log.Base/actions',
        'plugin.passbolt/log.Base/actionLogs',
        'plugin.passbolt/log.Base/entitiesHistory',
        'plugin.passbolt/log.Base/permissionsHistory',
        'plugin.passbolt/log.Base/secretsHistory',
    ];

    public function setUp()
    {
        $this->Permissions        = TableRegistry::get('Permissions');
        $this->Resources          = TableRegistry::get('Resources');
        $this->Users              = TableRegistry::get('Users');
        $this->ActionLogs         = TableRegistry::get('Passbolt/Log.ActionLogs');
        $this->EntitiesHistory    = TableRegistry::get('Passbolt/Log.EntitiesHistory');
        $this->SecretsHistory = TableRegistry::get('Passbolt/Log.SecretsHistory');
        parent::setUp();
    }

    public function testActionLogsFinderResourceSecretUpdated()
    {
        $uac = new UserAccessControl(Role::USER, UuidFactory::uuid('user.id.ada'));
        $this->simulateResourceSecretUpdate($uac, UuidFactory::uuid('resource.id.apache'));

        $ActionLogsFinder = new ActionLogsFinder();
        $actionLogs = $ActionLogsFinder->findForResource(UuidFactory::uuid('resource.id.apache'));

        $this->assertEquals(count($actionLogs), 1);

        $this->assertEquals($actionLogs[0]['type'], 'Resource.Secrets.updated');
        $this->assertTrue(isset($actionLogs[0]['data']));
        $this->assertTrue(isset($actionLogs[0]['data']['resource']));
        $this->assertTrue(isset($actionLogs[0]['data']['secrets']));
    }
}