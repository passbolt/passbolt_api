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
use App\Test\Factory\PermissionFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Utility\Hash;
use Passbolt\Log\Model\Entity\EntityHistory;
use Passbolt\Log\Test\Lib\LogIntegrationTestCase;
use Passbolt\Log\Test\Lib\Traits\PermissionsHistoryTestTrait;
use Passbolt\Log\Test\Lib\Traits\SecretsHistoryTestTrait;

/**
 * Class ShareControllerLogTest
 */
class ShareControllerLogTest extends LogIntegrationTestCase
{
    use LocatorAwareTrait;
    use PermissionsHistoryTestTrait;
    use SecretsHistoryTestTrait;

    /**
     * @var \Passbolt\Log\Model\Table\PermissionsHistoryTable
     */
    protected $PermissionsHistory;

    /**
     * @var \App\Model\Table\SecretsTable
     */
    protected $Secrets;

    /**
     * @var \Passbolt\Log\Model\Table\SecretsHistoryTable
     */
    protected $SecretsHistory;

    public function setUp(): void
    {
        parent::setUp();
        $this->PermissionsHistory = $this->fetchTable('Passbolt/Log.PermissionsHistory');
        $this->Secrets = $this->fetchTable('Secrets');
        $this->SecretsHistory = $this->fetchTable('Passbolt/Log.SecretsHistory');
    }

    public function testLogShareAddSuccess()
    {
        $user = UserFactory::make()->user()->persist();
        $edith = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        $resourceId = $resource->id;
        // Add an owner permission for the user Edith
        $data = [
            'permissions' => [
                ['aro' => 'User', 'aro_foreign_key' => $edith->id, 'type' => Permission::OWNER],
            ],
            'secrets' => [
                ['user_id' => $edith->id, 'data' => Hash::get(self::getDummySecretData(), 'data')],
            ],
        ];
        $this->logInAs($user);

        $this->putJson("/share/resource/$resourceId.json", $data);

        $this->assertSuccess();
        /** @var \App\Model\Entity\Secret $secret */
        $secret = $this->Secrets->findByResourceIdAndUserId($resourceId, $edith->id)->first();
        // Assert action log is correct.
        $this->assertOneActionLog();
        $actionLog = $this->assertActionLogExists([
            'action_id' => UuidFactory::uuid('Share.share'),
            'user_id' => $user->id,
            'status' => 1,
        ]);
        $this->assertActionLogIdMatchesResponse($actionLog['id'], $this->_responseJsonHeader);
        // Assert permissionHistory is correct.
        $this->assertPermissionsHistoryCount(1);
        $permissionHistory = $this->assertPermissionHistoryExists([
            'aco_foreign_key' => $resourceId,
            'aro_foreign_key' => $edith->id,
            'type' => Permission::OWNER,
        ]);
        // Assert secretHistory is correct.
        $this->assertSecretsHistoryCount(1);
        $this->assertSecretHistoryExists([
            'id' => $secret->id,
            'resource_id' => $resourceId,
            'user_id' => $edith->id,
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
        $user = UserFactory::make()->user()->persist();
        $betty = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withCreatorAndPermission($user)
            ->withPermissionsFor([$user], Permission::OWNER)
            ->withSecretsFor([$user, $betty])
            ->persist();
        $resourceId = $resource->id;
        $permission = PermissionFactory::make()->acoResource($resource)->aroUser($betty)->typeUpdate()->persist();
        $this->logInAs($user);
        // Delete the permission of the user Betty.
        $data = [
            'permissions' => [
                ['id' => $permission->id, 'delete' => true],
            ],
        ];

        $this->putJson("/share/resource/$resourceId.json", $data);

        $this->assertSuccess();
        // Assert action log is correct.
        $this->assertOneActionLog();
        $actionLog = $this->assertActionLogExists([
            'action_id' => UuidFactory::uuid('Share.share'),
            'user_id' => $user->id,
            'status' => 1,
        ]);
        $this->assertActionLogIdMatchesResponse($actionLog['id'], $this->_responseJsonHeader);
        // Assert permissionHistory is correct.
        $this->assertOnePermissionHistory();
        $permissionHistory = $this->assertPermissionHistoryExists([
            'id' => $permission->id,
            'aco_foreign_key' => $resourceId,
            'aro_foreign_key' => $betty->id,
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
        $user = UserFactory::make()->user()->persist();
        $betty = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withCreatorAndPermission($user)
            ->withPermissionsFor([$user], Permission::OWNER)
            ->withSecretsFor([$user, $betty])
            ->persist();
        $resourceId = $resource->id;
        $permission = PermissionFactory::make()->acoResource($resource)->aroUser($betty)->typeUpdate()->persist();
        $this->logInAs($user);
        // Update the permission of the user Betty from Update to Owner.
        $data = [
            'permissions' => [
                ['id' => $permission->id, 'type' => Permission::OWNER],
            ],
        ];

        $this->putJson("/share/resource/$resourceId.json", $data);

        $this->assertSuccess();
        // Assert action log is correct.
        $this->assertOneActionLog();
        $actionLog = $this->assertActionLogExists([
            'action_id' => UuidFactory::uuid('Share.share'),
            'user_id' => $user->id,
            'status' => 1,
        ]);
        $this->assertActionLogIdMatchesResponse($actionLog['id'], $this->_responseJsonHeader);
        // Assert permissionHistory is correct.
        $this->assertOnePermissionHistory();
        $permissionHistory = $this->assertPermissionHistoryExists([
            'id' => $permission->id,
            'aco_foreign_key' => $resourceId,
            'aro_foreign_key' => $betty->id,
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
        $user = UserFactory::make()->user()->persist();
        $edith = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withCreatorAndPermission($user)
            ->withPermissionsFor([$user], Permission::OWNER)
            ->withSecretsFor([$user])
            ->persist();
        $resourceId = $resource->id;
        $this->logInAs($user);
        // Add an owner permission for the user Edith.
        $data = [
            'permissions' => [
                ['aro' => 'User', 'aro_foreign_key' => $edith->id, 'type' => Permission::OWNER],
            ],
        ];

        $this->postJson("/share/simulate/resource/$resourceId.json", $data);

        $this->assertSuccess();
        // Assert that actionLog is correct
        $this->assertOneActionLog();
        $actionLog = $this->assertActionLogExists([
            'action_id' => UuidFactory::uuid('Share.dryRun'),
            'user_id' => $user->id,
            'status' => 1,
        ]);
        $this->assertActionLogIdMatchesResponse($actionLog['id'], $this->_responseJsonHeader);
        // Assert that no entry has been done.
        $this->assertPermissionsHistoryEmpty();
        $this->assertEntitiesHistoryEmpty();
    }

    public function testLogShareFailure()
    {
        $user = UserFactory::make()->user()->persist();
        $alienId = UuidFactory::uuid();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withCreatorAndPermission($user)
            ->withPermissionsFor([$user], Permission::OWNER)
            ->withSecretsFor([$user])
            ->persist();
        $resourceId = $resource->id;
        $this->logInAs($user);
        // Add an owner permission to alien user who doesn't exist.
        $data = [
            'permissions' => [
                ['aro' => 'User', 'aro_foreign_key' => $alienId, 'type' => Permission::OWNER],
            ],
            'secrets' => [
                ['user_id' => $alienId, 'data' => Hash::get(self::getDummySecretData(), 'data')],
            ],
        ];

        $this->putJson("/share/resource/$resourceId.json", $data);

        $this->assertError();
        $this->assertOneActionLog();
        $actionLog = $this->assertActionLogExists([
            'action_id' => UuidFactory::uuid('Share.share'),
            'user_id' => $user->id,
            'status' => 0,
        ]);
        $this->assertActionLogIdMatchesResponse($actionLog['id'], $this->_responseJsonHeader);
    }
}
