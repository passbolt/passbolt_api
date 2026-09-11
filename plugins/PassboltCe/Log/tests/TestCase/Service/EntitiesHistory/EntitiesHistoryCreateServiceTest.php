<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         5.15.0
 */
namespace Passbolt\Log\Test\TestCase\Service\EntitiesHistory;

use App\Model\Entity\Permission;
use App\Model\Entity\Role;
use App\Model\Table\PermissionsTable;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Utility\UserAccessControl;
use App\Utility\UserAction;
use Cake\Core\Configure;
use Cake\Event\EventManager;
use Cake\ORM\TableRegistry;
use Passbolt\Log\Events\ActionLogsModelListener;
use Passbolt\Log\Model\Table\PermissionsHistoryTable;

/**
 * @covers \Passbolt\Log\Service\EntitiesHistory\EntitiesHistoryCreateService
 */
class EntitiesHistoryCreateServiceTest extends AppTestCase
{
    private PermissionsTable $Permissions;

    private PermissionsHistoryTable $PermissionsHistory;

    private ActionLogsModelListener $listener;

    public function setUp(): void
    {
        parent::setUp();
        Configure::write('passbolt.plugins.log.enabled', true);
        // Model.afterSave fires on the global EventManager; attach the listener there so this test can
        // reach EntitiesHistoryCreateService without going through the HTTP stack. Detached in tearDown
        // to avoid double-listening in downstream tests.
        $this->listener = new ActionLogsModelListener();
        EventManager::instance()->on($this->listener);

        /** @var \App\Model\Table\PermissionsTable $permissions */
        $permissions = TableRegistry::getTableLocator()->get('Permissions');
        $this->Permissions = $permissions;
        /** @var \Passbolt\Log\Model\Table\PermissionsHistoryTable $permissionsHistory */
        $permissionsHistory = TableRegistry::getTableLocator()->get('Passbolt/Log.PermissionsHistory');
        $this->PermissionsHistory = $permissionsHistory;

        // Bind belongsTo defensively — the Model.initialize listener may not have fired
        // for Permissions before the Log plugin's event manager was attached in this test.
        if (!$this->Permissions->hasAssociation('PermissionsHistory')) {
            $this->Permissions->belongsTo('Passbolt/Log.PermissionsHistory', [
                'foreignKey' => 'foreign_key',
            ]);
        }
    }

    public function tearDown(): void
    {
        EventManager::instance()->off($this->listener);
        UserAction::destroy();
        unset($this->Permissions, $this->PermissionsHistory, $this->listener);
        parent::tearDown();
    }

    public function testEntitiesHistoryCreateService_PermissionUpdate_WritesFreshSnapshotRow(): void
    {
        [$owner, $target] = UserFactory::make(2)->user()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$owner])->persist();
        UserAction::getInstance(
            new UserAccessControl(Role::USER, $owner->id),
            'Share.share',
            'PUT /share/resource/*',
        );

        $permission = $this->Permissions->newEntity([
            'aco' => PermissionsTable::RESOURCE_ACO,
            'aco_foreign_key' => $resource->id,
            'aro' => PermissionsTable::USER_ARO,
            'aro_foreign_key' => $target->id,
            'type' => Permission::OWNER,
            'created_by' => $owner->id,
            'modified_by' => $owner->id,
        ], [
            'accessibleFields' => [
                'aco' => true,
                'aco_foreign_key' => true,
                'aro' => true,
                'aro_foreign_key' => true,
                'type' => true,
                'created_by' => true,
                'modified_by' => true,
            ],
        ]);
        $this->Permissions->saveOrFail($permission);

        $this->Permissions->patchEntity($permission, [
            'type' => Permission::READ,
            'modified_by' => $owner->id,
        ], [
            'accessibleFields' => ['type' => true, 'modified_by' => true],
        ]);
        $this->Permissions->saveOrFail($permission);

        $rows = $this->PermissionsHistory
            ->find()
            ->where([
                'aco_foreign_key' => $resource->id,
                'aro_foreign_key' => $target->id,
            ])
            ->toArray();

        $this->assertCount(2, $rows);

        $ids = array_column($rows, 'id');
        $this->assertNotEquals($ids[0], $ids[1]);
        $this->assertNotContains($permission->id, $ids);

        $types = array_column($rows, 'type');
        sort($types);
        $this->assertSame([Permission::READ, Permission::OWNER], $types);
    }
}
