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
use Passbolt\Folders\Model\Table\FoldersHistoryTable;
use Passbolt\Folders\Model\Table\FoldersTable;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Log\Events\ActionLogsModelListener;
use Passbolt\Log\Model\Entity\EntityHistory;
use Passbolt\Log\Model\Table\PermissionsHistoryTable;

/**
 * @covers \Passbolt\Log\Service\EntitiesHistory\EntitiesHistoryCreateService
 */
class EntitiesHistoryCreateServiceTest extends AppTestCase
{
    private PermissionsTable $Permissions;

    private PermissionsHistoryTable $PermissionsHistory;

    private FoldersTable $Folders;

    private FoldersHistoryTable $FoldersHistory;

    private ActionLogsModelListener $listener;

    public function setUp(): void
    {
        parent::setUp();
        Configure::write('passbolt.plugins.log.enabled', true);
        Configure::write('passbolt.plugins.folders.enabled', true);
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
        /** @var \Passbolt\Folders\Model\Table\FoldersTable $folders */
        $folders = TableRegistry::getTableLocator()->get('Passbolt/Folders.Folders');
        $this->Folders = $folders;
        /** @var \Passbolt\Folders\Model\Table\FoldersHistoryTable $foldersHistory */
        $foldersHistory = TableRegistry::getTableLocator()->get('Passbolt/Folders.FoldersHistory');
        $this->FoldersHistory = $foldersHistory;

        // Bind belongsTo defensively — the Model.initialize listener may not have fired
        // for Permissions before the Log plugin's event manager was attached in this test.
        if (!$this->Permissions->hasAssociation('PermissionsHistory')) {
            $this->Permissions->belongsTo('Passbolt/Log.PermissionsHistory', [
                'foreignKey' => 'foreign_key',
            ]);
        }
        if (!$this->Folders->hasAssociation('FoldersHistory')) {
            $this->Folders->belongsTo('FoldersHistory', [
                'className' => 'Passbolt/Folders.FoldersHistory',
                'foreignKey' => 'foreign_key',
            ]);
        }
    }

    public function tearDown(): void
    {
        EventManager::instance()->off($this->listener);
        UserAction::destroy();
        unset(
            $this->Permissions,
            $this->PermissionsHistory,
            $this->Folders,
            $this->FoldersHistory,
            $this->listener
        );
        parent::tearDown();
    }

    public function testEntitiesHistoryCreateService_V5FolderCreate_WritesHistoryWithoutPlaintextName(): void
    {
        $owner = UserFactory::make()->user()->persist();
        UserAction::getInstance(
            new UserAccessControl(Role::USER, $owner->id),
            'FoldersCreate.create',
            'POST /folders.json'
        );

        /** @var \Passbolt\Folders\Model\Entity\Folder $folder */
        $folder = FolderFactory::make([
            'created_by' => $owner->id,
            'modified_by' => $owner->id,
        ])->v5Fields([
            'metadata' => 'encrypted-metadata',
            'metadata_key_id' => null,
        ])->persist();

        $folderHistory = $this->FoldersHistory
            ->find()
            ->where(['folder_id' => $folder->id])
            ->firstOrFail();

        $this->assertNull($folderHistory->name);
        $this->assertNotSame($folder->id, $folderHistory->id);

        $entitiesHistory = $this->fetchTable('Passbolt/Log.EntitiesHistory');
        $entityHistory = $entitiesHistory
            ->find()
            ->where([
                'foreign_model' => 'FoldersHistory',
                'foreign_key' => $folderHistory->id,
            ])
            ->firstOrFail();

        $this->assertSame(EntityHistory::CRUD_CREATE, $entityHistory->crud);
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
