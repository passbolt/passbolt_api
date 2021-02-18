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
 * @since         2.8.0
 */
namespace Passbolt\Log\Test\TestCase\Controller\Share;

use App\Model\Entity\Permission;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\Log\Model\Entity\EntityHistory;
use Passbolt\Log\Test\Lib\LogIntegrationTestCase;
use Passbolt\Log\Test\Lib\Traits\PermissionsHistoryTrait;
use Passbolt\Log\Test\Lib\Traits\SecretsHistoryTrait;

class ShareControllerLogTest extends LogIntegrationTestCase
{
    use PermissionsHistoryTrait;
    use SecretsHistoryTrait;

    /**
     * @var PermissionsHistoryTable
     */
    protected $PermissionHistory;

    public $fixtures = [
        'app.Base/Users', 'app.Base/Gpgkeys', 'app.Base/Profiles', 'app.Base/Avatars', 'app.Base/Roles',
        'app.Base/Groups', 'app.Base/GroupsUsers', 'app.Base/Resources', 'app.Base/Permissions', 'app.Base/Secrets',
        'app.Base/Favorites',
    ];

    public function setUp()
    {
        parent::setUp();
        $this->PermissionsHistory = TableRegistry::getTableLocator()->get('Passbolt/Log.PermissionsHistory');
        $this->Secrets = TableRegistry::getTableLocator()->get('Secrets');
        $this->SecretsHistory = TableRegistry::getTableLocator()->get('Passbolt/Log.SecretsHistory');
    }

    public function testLogShareAddSuccess()
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
        $data['secrets'][] = ['user_id' => $userEId, 'data' => Hash::get(self::getDummySecretData(), 'data')];
        $expectedAddedUsersIds[] = $userEId;

        $this->authenticateAs('ada');
        $this->putJson("/share/resource/$resourceId.json", $data);
        $this->assertSuccess();
        $secret = $this->Secrets->findByResourceIdAndUserId($resourceId, $userEId)->first();

        // Assert action log is correct.
        $this->assertOneActionLog();
        $actionLog = $this->assertActionLogExists([
            'action_id' => UuidFactory::uuid('Share.share'),
            'user_id' => UuidFactory::uuid('user.id.ada'),
            'status' => 1,
        ]);
        $this->assertActionLogIdMatchesResponse($actionLog['id'], $this->_responseJsonHeader);

        // Assert permissionHistory is correct.
        $this->assertPermissionsHistoryCount(1);
        $permissionHistory = $this->assertPermissionHistoryExists([
            'aco_foreign_key' => $resourceId,
            'aro_foreign_key' => $userEId,
            'type' => Permission::OWNER,
        ]);

        // Assert secretHistory is correct.
        $this->assertSecretsHistoryCount(1);
        $this->assertSecretHistoryExists([
            'id' => $secret->id,
            'resource_id' => $resourceId,
            'user_id' => $userEId,
        ]);

        // Assert entityHistory is correct.
        $this->assertEntitiesHistoryCount(2);
        $this->assertEntityHistoryExists([
            'action_log_id' => $actionLog['id'],
            'foreign_model' => 'PermissionsHistory',
            'foreign_key' => $permissionHistory['id'],
            'crud' => EntityHistory::CRUD_CREATE,
        ]);
        $this->assertEntityHistoryExists([
            'action_log_id' => $actionLog['id'],
            'foreign_model' => 'SecretsHistory',
            'foreign_key' => $secret->id,
            'crud' => EntityHistory::CRUD_CREATE,
        ]);
    }

    public function testLogShareRemoveSuccess()
    {
        // Define actors of this tests
        $resourceId = UuidFactory::uuid('resource.id.apache');
        // Udd Edith in the list of permissions.
        $userBId = UuidFactory::uuid('user.id.betty');

        // Build the changes.
        $data = ['permissions' => []];
        // Delete the permission of the user Betty.
        $data['permissions'][] = [
            'id' => UuidFactory::uuid("permission.id.$resourceId-$userBId"), 'delete' => true,
        ];

        $this->authenticateAs('ada');
        $this->putJson("/share/resource/$resourceId.json", $data);
        $this->assertSuccess();

        // Assert action log is correct.
        $this->assertOneActionLog();
        $actionLog = $this->assertActionLogExists([
            'action_id' => UuidFactory::uuid('Share.share'),
            'user_id' => UuidFactory::uuid('user.id.ada'),
            'status' => 1,
        ]);
        $this->assertActionLogIdMatchesResponse($actionLog['id'], $this->_responseJsonHeader);

        // Assert permissionHistory is correct.
        $this->assertOnePermissionHistory();
        $permissionHistory = $this->assertPermissionHistoryExists([
            'id' => UuidFactory::uuid("permission.id.$resourceId-$userBId"),
            'aco_foreign_key' => $resourceId,
            'aro_foreign_key' => $userBId,
            'type' => Permission::UPDATE,
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

    public function testLogShareUpdateSuccess()
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
            'status' => 1,
        ]);
        $this->assertActionLogIdMatchesResponse($actionLog['id'], $this->_responseJsonHeader);

        // Assert permissionHistory is correct.
        $this->assertOnePermissionHistory();
        $permissionHistory = $this->assertPermissionHistoryExists([
            'id' => UuidFactory::uuid("permission.id.$resourceId-$userBId"),
            'aco_foreign_key' => $resourceId,
            'aro_foreign_key' => $userBId,
            'type' => Permission::OWNER,
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

    public function testLogShareAddDryRun()
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
        $this->assertOneActionLog();
        $actionLog = $this->assertActionLogExists([
            'action_id' => UuidFactory::uuid('Share.dryRun'),
            'user_id' => UuidFactory::uuid('user.id.ada'),
            'status' => 1,
        ]);
        $this->assertActionLogIdMatchesResponse($actionLog['id'], $this->_responseJsonHeader);

        // Assert that no entry has been done.
        $this->assertPermissionsHistoryEmpty();
        $this->assertEntitiesHistoryEmpty();
    }

    public function testLogShareFailure()
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
        $data['secrets'][] = ['user_id' => $userEId, 'data' => Hash::get(self::getDummySecretData(), 'data')];
        $expectedAddedUsersIds[] = $userEId;

        $this->authenticateAs('ada');
        $this->putJson("/share/resource/$resourceId.json", $data);
        $this->assertError();

        $this->assertOneActionLog();
        $actionLog = $this->assertActionLogExists([
            'action_id' => UuidFactory::uuid('Share.share'),
            'user_id' => UuidFactory::uuid('user.id.ada'),
            'status' => 0,
        ]);
        $this->assertActionLogIdMatchesResponse($actionLog['id'], $this->_responseJsonHeader);
    }
}
