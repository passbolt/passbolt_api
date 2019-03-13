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

namespace Passbolt\Log\Test\TestCase\Controller\Share;

use App\Model\Entity\Permission;
use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UserAccessControl;
use App\Utility\UserAction;
use App\Utility\UuidFactory;
use Passbolt\Log\Events\ActionsListener;
use Cake\Event\EventManager;
use Cake\ORM\TableRegistry;
use App\Model\Entity\Role;
use Passbolt\Log\Model\Table\EntitiesHistoryTable;
use Passbolt\Log\Model\Entity\EntityHistory;
use Passbolt\Log\Test\TestCase\Model\Traits\EntitiesHistoryTrait;
use Passbolt\Log\Test\TestCase\Model\Traits\PermissionsHistoryTrait;
use Passbolt\Log\Test\TestCase\Model\Traits\ActionLogsTrait;
use Passbolt\AuditLog\Utility\ActionLogsReader;

class ShareControllerLogTest extends AppIntegrationTestCase
{
    use EntitiesHistoryTrait;
    use PermissionsHistoryTrait;
    use ActionLogsTrait;

    public $fixtures = [
        'app.Base/users', 'app.Base/gpgkeys', 'app.Base/profiles', 'app.Base/avatars', 'app.Base/roles', 'app.Base/groups',
        'app.Base/groups_users', 'app.Base/resources', 'app.Base/permissions', 'app.Base/secrets', 'app.Base/favorites','app.Base/email_queue',
        'plugin.passbolt/log.Base/actions', 'plugin.passbolt/log.Base/actionLogs', 'plugin.passbolt/log.Base/entitiesHistory', 'plugin.passbolt/log.Base/permissionsHistory',
        'plugin.passbolt/log.Base/secretsHistory'
    ];

    public function setUp()
    {
        $this->Permissions = TableRegistry::get('Permissions');
        $this->Resources = TableRegistry::get('Resources');
        $this->Users = TableRegistry::get('Users');
        $this->ActionLogs = TableRegistry::get('Passbolt/Log.ActionLogs');
        $this->EntitiesHistory = TableRegistry::get('Passbolt/Log.EntitiesHistory');
        $this->PermissionsHistory = TableRegistry::get('Passbolt/Log.PermissionsHistory');
        parent::setUp();

        $this->initActionLogEvents();
    }

    protected function initActionLogEvents()
    {
        UserAction::destroy();
        $actions = new ActionsListener();
        EventManager::instance()->on($actions);
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

    public function testAddSuccess()
    {
        // Define actors of this tests
        $resourceId = UuidFactory::uuid('resource.id.apache');
        // Udd Edith in the list of permissions.
        $userEId = UuidFactory::uuid('user.id.edith');

        // Expected results.
        $expectedAddedUsersIds = [];

        // Build the changes.
        $data = ['permissions' => []];

        // Users permissions changes.
        // Add an owner permission for the user Edith
        $data['permissions'][] = ['aro' => 'User', 'aro_foreign_key' => $userEId, 'type' => Permission::OWNER];
        $data['secrets'][] = ['user_id' => $userEId, 'data' => $this->getValidSecret()];
        $expectedAddedUsersIds[] = $userEId;

        $this->authenticateAs('ada');
        $this->putJson("/share/resource/$resourceId.json", $data);
        $this->assertSuccess();

        // Assert action log is correct.
        $this->assertOneActionLog();
        $actionLog = $this->assertActionLogExists([
            'action_id' => UuidFactory::uuid('Share.share'),
            'user_id' => UuidFactory::uuid('user.id.ada'),
            'status' => 1
        ]);
        $this->assertActionLogIdMatchesResponse($actionLog['id'], $this->_responseJsonHeader);

        // Assert permissionHistory is correct.
        $this->assertOnePermissionHistory();
        $permissionHistory = $this->assertPermissionHistoryExists([
            'aco_foreign_key' => $resourceId,
            'aro_foreign_key' => $userEId,
            'type' =>  Permission::OWNER,
        ]);

        // Assert entityHistory is correct.
        $this->assertOneEntityHistory();
        $this->assertEntityHistoryExists([
            'action_log_id' => $actionLog['id'],
            'foreign_model' => 'PermissionsHistory',
            'foreign_key' => $permissionHistory['id'],
            'crud' => EntityHistory::CRUD_CREATE,
        ]);
    }

    public function testRemoveSuccess()
    {
        // Define actors of this tests
        $resourceId = UuidFactory::uuid('resource.id.apache');
        // Udd Edith in the list of permissions.
        $userBId = UuidFactory::uuid('user.id.betty');

        // Build the changes.
        $data = ['permissions' => []];
        // Delete the permission of the user Betty.
        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.$resourceId-$userBId"), 'delete' => true];

        $this->authenticateAs('ada');
        $this->putJson("/share/resource/$resourceId.json", $data);
        $this->assertSuccess();

        // Assert action log is correct.
        $this->assertOneActionLog();
        $actionLog = $this->assertActionLogExists([
            'action_id' => UuidFactory::uuid('Share.share'),
            'user_id' => UuidFactory::uuid('user.id.ada'),
            'status' => 1
        ]);
        $this->assertActionLogIdMatchesResponse($actionLog['id'], $this->_responseJsonHeader);

        // Assert permissionHistory is correct.
        $this->assertOnePermissionHistory();
        $permissionHistory = $this->assertPermissionHistoryExists([
            'id' => UuidFactory::uuid("permission.id.$resourceId-$userBId"),
            'aco_foreign_key' => $resourceId,
            'aro_foreign_key' => $userBId,
            'type' =>  Permission::UPDATE,
        ]);

        // Assert entityHistory is correct.
        $this->assertOneEntityHistory();
        $this->assertEntityHistoryExists([
            'action_log_id' => $actionLog['id'],
            'foreign_model' => 'PermissionsHistory',
            'foreign_key' => $permissionHistory['id'],
            'crud' => EntityHistory::CRUD_DELETE,
        ]);
    }

    public function testUpdateSuccess()
    {
        // Define actors of this tests
        $resourceId = UuidFactory::uuid('resource.id.apache');
        // Udd Edith in the list of permissions.
        $userBId = UuidFactory::uuid('user.id.betty');

        // Build the changes.
        $data = ['permissions' => []];
        // Delete the permission of the user Betty.
        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.$resourceId-$userBId"), 'type' => Permission::OWNER];

        $this->authenticateAs('ada');
        $this->putJson("/share/resource/$resourceId.json", $data);
        $this->assertSuccess();

        // Assert action log is correct.
        $this->assertOneActionLog();
        $actionLog = $this->assertActionLogExists([
            'action_id' => UuidFactory::uuid('Share.share'),
            'user_id' => UuidFactory::uuid('user.id.ada'),
            'status' => 1
        ]);
        $this->assertActionLogIdMatchesResponse($actionLog['id'], $this->_responseJsonHeader);

        // Assert permissionHistory is correct.
        $this->assertOnePermissionHistory();
        $permissionHistory = $this->assertPermissionHistoryExists([
            'id' => UuidFactory::uuid("permission.id.$resourceId-$userBId"),
            'aco_foreign_key' => $resourceId,
            'aro_foreign_key' => $userBId,
            'type' =>  Permission::OWNER,
        ]);

        // Assert entityHistory is correct.
        $this->assertOneEntityHistory();
        $this->assertEntityHistoryExists([
            'action_log_id' => $actionLog['id'],
            'foreign_model' => 'PermissionsHistory',
            'foreign_key' => $permissionHistory['id'],
            'crud' => EntityHistory::CRUD_UPDATE,
        ]);
    }

    public function testAddDryRun()
    {
        // Define actors of this tests
        $resourceId = UuidFactory::uuid('resource.id.apache');
        // Udd Edith in the list of permissions.
        $userEId = UuidFactory::uuid('user.id.edith');

        // Expected results.
        $expectedAddedUsersIds = [];

        // Build the changes.
        $data = ['permissions' => []];

        // Users permissions changes.
        // Add an owner permission for the user Edith
        $data['permissions'][] = ['aro' => 'User', 'aro_foreign_key' => $userEId, 'type' => Permission::OWNER];
        $expectedAddedUsersIds[] = $userEId;

        $this->authenticateAs('ada');
        $this->postJson("/share/simulate/resource/$resourceId.json", $data);
        $this->assertSuccess();

        // Assert that actionLog is correct
        $actionLogs = $this->ActionLogs->find()->all();
        $this->assertOneActionLog();
        $actionLog = $this->assertActionLogExists([
            'action_id' => UuidFactory::uuid('Share.dryRun'),
            'user_id' => UuidFactory::uuid('user.id.ada'),
            'status' => 1
        ]);
        $this->assertActionLogIdMatchesResponse($actionLog['id'], $this->_responseJsonHeader);

        // Assert that no entry has been done.
        $this->assertPermissionsHistoryEmpty();
        $this->assertEntitiesHistoryEmpty();
    }

    public function testFailure()
    {
        // Define actors of this tests
        $resourceId = UuidFactory::uuid('resource.id.apache');
        // Add a non existing user in the list of permissions.
        $userEId = UuidFactory::uuid('user.id.dontexist');

        // Expected results.
        $expectedAddedUsersIds = [];

        // Build the changes.
        $data = ['permissions' => []];

        // Users permissions changes.
        // Add an owner permission for the user Edith
        $data['permissions'][] = ['aro' => 'User', 'aro_foreign_key' => $userEId, 'type' => Permission::OWNER];
        $data['secrets'][] = ['user_id' => $userEId, 'data' => $this->getValidSecret()];
        $expectedAddedUsersIds[] = $userEId;

        $this->authenticateAs('ada');
        $this->putJson("/share/resource/$resourceId.json", $data);
        $this->assertError();

        $this->assertOneActionLog();
        $actionLog = $this->assertActionLogExists([
            'action_id' => UuidFactory::uuid('Share.share'),
            'user_id' => UuidFactory::uuid('user.id.ada'),
            'status' => 0
        ]);
        $this->assertActionLogIdMatchesResponse($actionLog['id'], $this->_responseJsonHeader);
    }
}
