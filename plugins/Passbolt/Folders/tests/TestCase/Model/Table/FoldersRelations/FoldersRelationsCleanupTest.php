<?php
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
 * @since         2.13.0
 */

namespace Passbolt\Folders\Test\TestCase\Model\Table;

use App\Model\Entity\Permission;
use App\Model\Table\PermissionsTable;
use App\Model\Table\ResourcesTable;
use App\Test\Fixture\Base\GroupsFixture;
use App\Test\Fixture\Base\GroupsUsersFixture;
use App\Test\Fixture\Base\PermissionsFixture;
use App\Test\Fixture\Base\RolesFixture;
use App\Test\Fixture\Base\SecretsFixture;
use App\Test\Fixture\Base\UsersFixture;
use App\Test\Lib\Utility\CleanupTrait;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Model\Table\FoldersTable;
use Passbolt\Folders\Test\Fixture\FoldersFixture;
use Passbolt\Folders\Test\Fixture\FoldersRelationsFixture;
use Passbolt\Folders\Test\Fixture\ResourcesFixture;
use Passbolt\Folders\Test\Lib\FoldersTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

/**
 * Passbolt\Folders\Model\Table\FoldersRelationsTable Test Case
 */
class FoldersRelationsCleanupTest extends FoldersTestCase
{
    use CleanupTrait;
    use FoldersModelTrait;
    use FoldersRelationsModelTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        FoldersFixture::class,
        FoldersRelationsFixture::class,
        GroupsFixture::class,
        GroupsUsersFixture::class,
        PermissionsFixture::class,
        ResourcesFixture::class,
        RolesFixture::class,
        SecretsFixture::class,
        UsersFixture::class,
    ];

    /**
     * @var FoldersTable
     */
    private $foldersTable;

    /**
     * @var ResourcesTable
     */
    private $resourcesTables;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        Configure::write('passbolt.plugins.folders', ['enabled' => true]);
        $this->resourcesTables = TableRegistry::getTableLocator()->get('Resources');
        $this->foldersTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.Folders');
    }

    public function testCleanupFoldersRelationsSoftDeletedResourcesSuccess()
    {
        $originalCount = 4;
        $checkOptions = ['cleanupCount' => 2];
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');

        $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        // The resource R2 is going to be soft deleted
        $resourceR2 = $this->addResourceFor(['name' => 'R2'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $this->resourcesTables->updateAll(['deleted' => true], ['id' => $resourceR2->id]);

        $this->runCleanupChecks('Passbolt/Folders.FoldersRelations', 'cleanupSoftDeletedResources', $originalCount, $checkOptions);
    }

    public function testCleanupFoldersRelationsHardDeletedResourcesSuccess()
    {
        $originalCount = 4;
        $checkOptions = ['cleanupCount' => 2];
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');

        $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        // The resource R2 is going to be hard deleted
        $resourceR2 = $this->addResourceFor(['name' => 'R2'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $this->resourcesTables->deleteAll(['id' => $resourceR2->id]);

        $this->runCleanupChecks('Passbolt/Folders.FoldersRelations', 'cleanupHardDeletedResources', $originalCount, $checkOptions);
    }

    public function testCleanupFoldersRelationsSoftDeletedUsersSuccess()
    {
        $originalCount = 6;
        $checkOptions = ['cleanupCount' => 2];
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $userSId = UuidFactory::uuid('user.id.sofia');

        $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        // Resource R2 and folder B have a folder relations associated to soft deleted user.
        $this->addFolderFor(['name' => 'B'], [$userAId => Permission::OWNER, $userSId => Permission::OWNER]);
        $this->addResourceFor(['name' => 'R2'], [$userAId => Permission::OWNER, $userSId => Permission::OWNER]);

        $this->runCleanupChecks('Passbolt/Folders.FoldersRelations', 'cleanupSoftDeletedUsers', $originalCount, $checkOptions);
    }

    public function testCleanupFoldersRelationsHardDeletedUsersSuccess()
    {
        $originalCount = 6;
        $checkOptions = ['cleanupCount' => 2];
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $userHardDeletedId = UuidFactory::uuid('user.id.hard-deleted');

        $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        // Resource R2 and folder B have a folder relations associated to a hard deleted user.
        $this->addFolderFor(['name' => 'B'], [$userAId => Permission::OWNER, $userHardDeletedId => Permission::OWNER]);
        $this->addResourceFor(['name' => 'R2'], [$userAId => Permission::OWNER, $userHardDeletedId => Permission::OWNER]);

        $this->runCleanupChecks('Passbolt/Folders.FoldersRelations', 'cleanupHardDeletedUsers', $originalCount, $checkOptions);
    }

    public function testCleanupFoldersRelationsHardDeletedFoldersSuccess()
    {
        $originalCount = 4;
        $checkOptions = ['cleanupCount' => 2];
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');

        $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        // The folder B is going to be hard deleted
        $folderB = $this->addFolderFor(['name' => 'B'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $this->foldersTable->deleteAll(['id' => $folderB->id]);

        $this->runCleanupChecks('Passbolt/Folders.FoldersRelations', 'cleanupHardDeletedFolders', $originalCount, $checkOptions);
    }

    public function testCleanupFoldersRelationsHardDeletedFoldersParentsSuccess()
    {
        $originalCount = 4;
        $checkOptions = ['cleanupCount' => 4];
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderHardDeletedId = UuidFactory::uuid('folder.id.hard-deleted');

        $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        // The folder B and the resource R2 has a folder parent that has been hard deleted.
        $this->addFolderFor(['name' => 'B', 'folder_parent_id' => $folderHardDeletedId], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $this->addResourceFor(['name' => 'R2', 'folder_parent_id' => $folderHardDeletedId], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);

        $this->runCleanupChecks('Passbolt/Folders.FoldersRelations', 'cleanupHardDeletedFoldersParents', $originalCount, $checkOptions);
    }

    public function testCleanupMissingResourcesFoldersRelationsSuccess()
    {
        $originalCount = 4;
        $checkOptions = ['isDeleteCleanup' => false, 'cleanupCount' => 1];
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');

        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $r1 = $this->addResourceFor(['name' => 'R1', 'folder_parent_id' => $folderA->id], [$userAId => Permission::OWNER]);
        $this->addPermission(PermissionsTable::RESOURCE_ACO, $r1->id, PermissionsTable::USER_ARO, $userBId);

        $this->runCleanupChecks('Passbolt/Folders.FoldersRelations', 'cleanupMissingResourcesFoldersRelations', $originalCount, $checkOptions);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, $folderA->id);
    }

    public function testCleanupMissingFoldersFoldersRelationsSuccess()
    {
        $originalCount = 4;
        $checkOptions = ['isDeleteCleanup' => false, 'cleanupCount' => 1];
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');

        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B', 'folder_parent_id' => $folderA->id], [$userAId => Permission::OWNER]);
        $this->addPermission(PermissionsTable::FOLDER_ACO, $folderB->id, PermissionsTable::USER_ARO, $userBId);

        $this->runCleanupChecks('Passbolt/Folders.FoldersRelations', 'cleanupMissingFoldersFoldersRelations', $originalCount, $checkOptions);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderA->id);
    }
}
