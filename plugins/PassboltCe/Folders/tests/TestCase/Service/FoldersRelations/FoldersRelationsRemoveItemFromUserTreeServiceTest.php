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
 * @since         2.13.0
 */

namespace Passbolt\Folders\Test\TestCase\Service\FoldersRelations;

use App\Model\Entity\Permission;
use App\Model\Entity\Role;
use App\Test\Fixture\Base\GpgkeysFixture;
use App\Test\Fixture\Base\GroupsFixture;
use App\Test\Fixture\Base\GroupsUsersFixture;
use App\Test\Fixture\Base\PermissionsFixture;
use App\Test\Fixture\Base\ProfilesFixture;
use App\Test\Fixture\Base\ResourcesFixture;
use App\Test\Fixture\Base\SecretsFixture;
use App\Test\Fixture\Base\UsersFixture;
use App\Test\Lib\Model\PermissionsModelTrait;
use App\Test\Lib\Utility\FixtureProviderTrait;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Service\FoldersRelations\FoldersRelationsRemoveItemFromUserTreeService;
use Passbolt\Folders\Test\Lib\FoldersTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

/**
 * Passbolt\Folders\Service\FoldersRelations\FoldersRelationsRemoveItemFromUserTreeService Test Case
 *
 * @covers \Passbolt\Folders\Service\FoldersRelations\FoldersRelationsRemoveItemFromUserTreeService
 */
class FoldersRelationsRemoveItemFromUserTreeServiceTest extends FoldersTestCase
{
    use FixtureProviderTrait;
    use FoldersModelTrait;
    use FoldersRelationsModelTrait;
    use PermissionsModelTrait;

    public $fixtures = [
        GpgkeysFixture::class,
        GroupsFixture::class,
        GroupsUsersFixture::class,
        PermissionsFixture::class,
        ProfilesFixture::class,
        ResourcesFixture::class,
        SecretsFixture::class,
        UsersFixture::class,
    ];

    /**
     * @var FoldersRelationsRemoveItemFromUserTreeService
     */
    private $service;

    /**
     * @var \App\Model\Table\PermissionsTable
     */
    private $permissionsTable;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->permissionsTable = TableRegistry::getTableLocator()->get('Permissions');
        $this->service = new FoldersRelationsRemoveItemFromUserTreeService();
    }

    /* FOLDERS */

    public function RemoveItemFromUserTreeSuccess_Folder1_NoParentNoChildren()
    {
        [$folderA, $userAId, $userBId] = $this->insertFixture_Folder1();

        $this->service->removeItemFromUserTree($folderA->id, $userBId, true);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 1);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
    }

    public function insertFixture_Folder1()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // A (Ada:O, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);

        // Remove the permission for the user we want to remove the folder from the tree.
        $this->permissionsTable->deleteAll(['aco_foreign_key' => $folderA->get('id'), 'aro_foreign_key' => $userBId]);

        return [$folderA, $userAId, $userBId];
    }

    public function RemoveItemFromUserTreeSuccess_Folder2_HavingAParent()
    {
        [$folderA, $folderB, $userAId, $userBId] = $this->insertFixture_Folder2();
        new UserAccessControl(Role::USER, $userAId);

        $this->service->removeItemFromUserTree($folderB->id, $userBId, true);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        // Folder B.
        $this->assertItemIsInTrees($folderB->id, 1);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
    }

    public function insertFixture_Folder2()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // Ada is OWNER of folder B
        // Betty is OWNER of folder B
        // Ada sees B in A
        // Betty sees B in A
        // ----
        // A (Ada:O, Betty:O)
        // |- B (Ada:O, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B', 'folder_parent_id' => $folderA->get('id')], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);

        // Remove the permission for the user we want to remove the folder from the tree.
        $this->permissionsTable->deleteAll(['aco_foreign_key' => $folderB->get('id'), 'aro_foreign_key' => $userBId]);

        return [$folderA, $folderB, $userAId, $userBId];
    }

    public function RemoveItemFromUserTreeSuccess_Folder3_HavingAChild()
    {
        [$folderA, $folderB, $userAId, $userBId] = $this->insertFixture_Folder3();

        $this->service->removeItemFromUserTree($folderA->id, $userBId, true);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 1);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        // Folder B.
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
    }

    public function insertFixture_Folder3()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // Ada is OWNER of folder B
        // Betty is OWNER of folder B
        // Ada sees B in A
        // Betty sees B in A
        // ----
        // A (Ada:O, Betty:O)
        // |- B (Ada:O, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B', 'folder_parent_id' => $folderA->get('id')], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);

        // Remove the permission for the user we want to remove the folder from the tree.
        $this->permissionsTable->deleteAll(['aco_foreign_key' => $folderA->id, 'aro_foreign_key' => $userBId]);

        return [$folderA, $folderB, $userAId, $userBId];
    }

    public function RemoveItemFromUserTreeSuccess_Folder4_HavingChildren()
    {
        [$folderA, $folderB, $folderC, $userAId, $userBId] = $this->insertFixture_Folder4();

        $this->service->removeItemFromUserTree($folderA->id, $userBId, true);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 1);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        // Folder B.
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        // Folder C.
        $this->assertItemIsInTrees($folderC->id, 2);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
    }

    public function insertFixture_Folder4()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // Ada is OWNER of folder B
        // Betty is OWNER of folder B
        // Ada is OWNER of folder C
        // Betty is OWNER of folder C
        // Ada sees B in A
        // Betty sees B in A
        // Ada sees C in A
        // Betty sees C in A
        // ----
        // A (Ada:O, Betty:O)
        // |- B (Ada:O, Betty:O)
        // |- C (Ada:O, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B', 'folder_parent_id' => $folderA->get('id')], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $folderC = $this->addFolderFor(['name' => 'C', 'folder_parent_id' => $folderA->get('id')], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);

        // Remove the permission for the user we want to remove the folder from the tree.
        $this->permissionsTable->deleteAll(['aco_foreign_key' => $folderA->id, 'aro_foreign_key' => $userBId]);

        return [$folderA, $folderB, $folderC, $userAId, $userBId];
    }

    /* RESOURCES */

    public function testRemoveItemFromUserTreeSuccess_Resource1_NoParent()
    {
        [$r1, $userAId, $userBId] = $this->insertFixture_Resource1();

        $this->service->removeItemFromUserTree($r1->id, $userBId);

        $this->assertItemIsInTrees($r1->id, 1);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, null);
    }

    public function insertFixture_Resource1()
    {
        // Ada is OWNER of resource R1
        // Betty is OWNER of resource R1
        // R1 (Ada:O, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $r1 = $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);

        // Remove the permission for the user we want to remove the folder from the tree.
        $this->permissionsTable->deleteAll(['aco_foreign_key' => $r1->get('id'), 'aro_foreign_key' => $userBId]);

        return [$r1, $userAId, $userBId];
    }

    public function testRemoveItemFromUserTreeSuccess_Resource2_HavingAParent()
    {
        [$folderA, $r1, $userAId, $userBId] = $this->insertFixture_Resource2();

        $this->service->removeItemFromUserTree($r1->id, $userBId);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        // Resource1
        $this->assertItemIsInTrees($r1->id, 1);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, $folderA->id);
    }

    public function insertFixture_Resource2()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // Ada is OWNER of resource R1
        // Betty is OWNER of resource R1
        // Ada sees R1 in A
        // Betty sees R1 in A
        // ----
        // A (Ada:O, Betty:O)
        // |- R1 (Ada:O, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $r1 = $this->addResourceFor(['name' => 'R1', 'folder_parent_id' => $folderA->get('id')], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);

        // Remove the permission for the user we want to remove the folder from the tree.
        $this->permissionsTable->deleteAll(['aco_foreign_key' => $r1->get('id'), 'aro_foreign_key' => $userBId]);

        return [$folderA, $r1, $userAId, $userBId];
    }
}
