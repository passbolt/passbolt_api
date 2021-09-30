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

use App\Error\Exception\CustomValidationException;
use App\Model\Entity\Permission;
use App\Model\Entity\Role;
use App\Model\Table\PermissionsTable;
use App\Test\Fixture\Base\GroupsFixture;
use App\Test\Fixture\Base\GroupsUsersFixture;
use App\Test\Fixture\Base\PermissionsFixture;
use App\Test\Fixture\Base\ResourcesFixture;
use App\Test\Fixture\Base\SecretsFixture;
use App\Test\Fixture\Base\UsersFixture;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Utility\Hash;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Service\FoldersRelations\FoldersRelationsMoveItemInUserTreeService;
use Passbolt\Folders\Test\Lib\FoldersTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

/**
 * Passbolt\Folders\Service\FoldersRelations\FoldersRelationsMoveItemInUserTreeService Test Case
 *
 * @covers \Passbolt\Folders\Service\FoldersRelations\FoldersRelationsMoveItemInUserTreeService
 */
class FoldersRelationsMoveItemInUserTreeServiceTest extends FoldersTestCase
{
    use FoldersModelTrait;
    use FoldersRelationsModelTrait;

    public $fixtures = [
        GroupsFixture::class,
        GroupsUsersFixture::class,
        PermissionsFixture::class,
        ResourcesFixture::class,
        SecretsFixture::class,
        UsersFixture::class,
    ];

    /**
     * @var FoldersRelationsMoveItemInUserTreeService
     */
    private $service;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->service = new FoldersRelationsMoveItemInUserTreeService();
    }

    /* FOLDER - COMMON */

    public function testMoveItemInUserTreeError_Folder_Common_CannotMoveIntoFolderDoesNotExist()
    {
        [$folderA, $userAId] = $this->insertFixture_Folder_Common_CannotMoveIntoFolderDoesNotExist();
        $uac = new UserAccessControl(Role::USER, $userAId);

        try {
            $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->id, UuidFactory::uuid());
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertEquals('Could not validate move data.', $e->getMessage());
            $this->assertNotEmpty(Hash::get($e->getErrors(), 'folder_parent_id.folder_exists'));
        }
    }

    private function insertFixture_Folder_Common_CannotMoveIntoFolderDoesNotExist()
    {
        // Ada has access to folder A as a OWNER
        // A (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);

        return [$folderA, $userAId];
    }

    /* FOLDER - PERSONAL */

    public function testMoveItemInUserTreeSuccess_Folder_Personal_MoveFolderFromRoot()
    {
        [$folderA, $folderB, $userAId] = $this->insertFixture_Folder_Personal_MoveFolderFromRoot();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id, $folderA->id);

        $this->assertItemIsInTrees($folderA->id, 1);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertItemIsInTrees($folderB->id, 1);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
    }

    private function insertFixture_Folder_Personal_MoveFolderFromRoot()
    {
        // Ada has access to folder A as a OWNER
        // Ada has access to folder B as a OWNER
        // ---
        // A (Ada:O)
        // B (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B'], [$userAId => Permission::OWNER]);

        return [$folderA, $folderB, $userAId];
    }

    public function testMoveItemInUserTreeSuccess_Folder_Personal_MoveFolderToRoot()
    {
        [$folderA, $folderB, $userAId] = $this->insertFixture_Folder_Personal_MoveFolderToRoot();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id, FoldersRelation::ROOT);

        $this->assertItemIsInTrees($folderA->id, 1);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertItemIsInTrees($folderB->id, 1);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
    }

    private function insertFixture_Folder_Personal_MoveFolderToRoot()
    {
        // Ada has access to folder A as a OWNER
        // Ada has access to folder B as a OWNER
        // A (Ada:O)
        // |- B (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B', 'folder_parent_id' => $folderA->get('id')], [$userAId => Permission::OWNER]);

        return [$folderA, $folderB, $userAId];
    }

    public function testMoveItemInUserTreeError_Folder_Personal_CannotCreateCycle()
    {
        [$folderA, $folderB, $userAId] = $this->insertFixture_Folder_Personal_CannotCreateCycle();
        $uac = new UserAccessControl(Role::USER, $userAId);

        try {
            $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->get('id'), $folderB->get('id'));
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertEquals('Could not validate move data.', $e->getMessage());
            $this->assertNotEmpty(Hash::get($e->getErrors(), 'folder_parent_id.cycle'));
        }
    }

    private function insertFixture_Folder_Personal_CannotCreateCycle()
    {
        // Ada has access to folder A as a OWNER
        // Ada has access to folder B as a OWNER
        // A (Ada:O)
        // |- B (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B', 'folder_parent_id' => $folderA->get('id')], [$userAId => Permission::OWNER]);

        return [$folderA, $folderB, $userAId];
    }

    /* FOLDER - SHARED */

    public function testMoveItemInUserTreeSuccess_Folder_SharedWithUser_MoveFolderFromRoot()
    {
        [$folderA, $folderB, $userAId, $userBId] = $this->insertFixture_Folder_SharedWithUser_MoveFolderFromRoot();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->get('id'), $folderA->get('id'));

        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderA->id);
    }

    private function insertFixture_Folder_SharedWithUser_MoveFolderFromRoot()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // Ada is OWNER of folder B
        // Betty is OWNER of folder B
        // ---
        // A (Ada:O, Betty:Owner)
        // B (Ada:O, Betty:Owner)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);

        return [$folderA, $folderB, $userAId, $userBId];
    }

    public function testMoveItemInUserTreeSuccess_Folder_SharedWithGroup_MoveFolderFromRoot()
    {
        [$folderA, $folderB, $userAId, $userBId] = $this->insertFixture_Folder_SharedWithGroup_MoveFolderFromRoot();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id, $folderA->id);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderA->id);
    }

    private function insertFixture_Folder_SharedWithGroup_MoveFolderFromRoot()
    {
        // Ada is OWNER of folder A
        // G1 is OWNER of folder A
        // Ada is OWNER of folder B
        // G1 is OWNER of folder B
        // Betty is member of G1
        // ---
        // A (Ada:O, G1:Owner)
        // B (Ada:O, G1:Owner)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $g1 = $this->addGroup(['name' => 'G1', 'groups_users' => [
            ['user_id' => $userBId, 'is_admin' => true],
        ]]);
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER], [$g1->id => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B'], [$userAId => Permission::OWNER], [$g1->id => Permission::OWNER]);

        return [$folderA, $folderB, $userAId, $userBId];
    }

    public function testMoveItemInUserTreeSuccess_Folder_SharedWithUser_MoveIntoSharedFolder_MoveToRootForUsersNotSeeingDestination()
    {
        [$folderA, $folderB, $folderC, $userAId, $userBId] = $this->insertFixture_Folder_SharedWithUser_MoveIntoSharedFolder_MoveToRootForUsersNotSeeingDestination();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id, $folderC->id);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderC->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 1);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
    }

    private function insertFixture_Folder_SharedWithUser_MoveIntoSharedFolder_MoveToRootForUsersNotSeeingDestination()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // Ada is OWNER of folder B
        // Betty is OWNER of folder B
        // Ada is OWNER of folder C
        // ---
        // C (Ada:O)
        // A (Ada:O, Betty:O)
        // |- B (Ada:O, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B', 'folder_parent_id' => $folderA->get('id')], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $folderC = $this->addFolderFor(['name' => 'C'], [$userAId => Permission::OWNER]);

        return [$folderA, $folderB, $folderC, $userAId, $userBId];
    }

    public function testMoveItemInUserTreeSuccess_Folder_SharedWithUser_MoveFromSharedFolderToSharedFolder_DontMoveForUsersNotSeeingOriginalAndTargetFolders()
    {
        [$folderA, $folderB, $folderC, $folderD, $userAId, $userBId] =
            $this->insertFixture_Folder_SharedWithUser_MoveFromSharedFolderToSharedFolder_DontMoveForUsersNotSeeingOriginalAndTargetFolders();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->get('id'), $folderD->get('id'));

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 1);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderD->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderC->id);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 1);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        // Folder D
        $this->assertItemIsInTrees($folderD->id, 1);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
    }

    private function insertFixture_Folder_SharedWithUser_MoveFromSharedFolderToSharedFolder_DontMoveForUsersNotSeeingOriginalAndTargetFolders()
    {
        // Ada is OWNER of folder A
        // Ada is OWNER of folder B
        // Betty is OWNER of folder B
        // Betty is OWNER of folder C
        // Ada is OWNER of folder D
        // Ada sees folder B in folder A
        // Betty sees folder B in folder C
        // ---
        // A (Ada:O)
        // |- B (Ada:O, Betty:O)
        //
        // C (Betty:O)
        // |- B (Ada:O, Betty:O)
        //
        // D (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);
        $folderC = $this->addFolderFor(['name' => 'C'], [$userBId => Permission::OWNER]);
        $folderB = $this->addFolder(['name' => 'B']);
        $this->addPermission('Folder', $folderB->id, 'User', $userAId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userAId, 'folder_parent_id' => $folderA->get('id')]);
        $this->addPermission('Folder', $folderB->id, 'User', $userBId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userBId, 'folder_parent_id' => $folderC->get('id')]);
        $folderD = $this->addFolderFor(['name' => 'D'], [$userAId => Permission::OWNER]);

        return [$folderA, $folderB, $folderC, $folderD, $userAId, $userBId];
    }

    public function testMoveItemInUserTreeSuccess_Folder_SharedWithUser_MoveFromRootToSharedFolder_MoveToTargetFolderForUsersSeeingIt()
    {
        [$folderA, $folderB, $folderC, $userAId, $userBId] =
            $this->insertFixture_Folder_SharedWithUser_MoveFromRootToSharedFolder_MoveToTargetFolderForUsersSeeingIt();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id, $folderC->id);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 1);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderC->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderC->id);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 2);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
    }

    private function insertFixture_Folder_SharedWithUser_MoveFromRootToSharedFolder_MoveToTargetFolderForUsersSeeingIt()
    {
        // Betty is OWNER of folder A
        // Ada is OWNER of folder B
        // Betty is OWNER of folder B
        // Folder B is in Folder A
        // Ada is OWNER of folder C
        // Betty is OWNER of folder C
        // ---
        // A (Betty:O)
        // |- B (Ada:O, Betty:O)
        //
        // C (Ada:O, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userBId => Permission::OWNER]);
        $folderB = $this->addFolder(['name' => 'B']);
        $this->addPermission('Folder', $folderB->id, 'User', $userAId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userAId, 'folder_parent_id' => FoldersRelation::ROOT]);
        $this->addPermission('Folder', $folderB->id, 'User', $userBId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userBId, 'folder_parent_id' => $folderA->get('id')]);
        $folderC = $this->addFolderFor(['name' => 'C'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);

        return [$folderA, $folderB, $folderC, $userAId, $userBId];
    }

    public function testMoveItemInUserTreeSuccess_Folder_SharedWithUser_MoveFromSharedFolderToRoot_MoveToRootForUsersSeeingItInTheOriginalOperatorFolder()
    {
        [$folderA, $folderB, $folderC, $userAId, $userBId] = $this->insertFixture_Folder_SharedWithUser_MoveFromSharedFolderToRoot_MoveToRootForUsersSeeingItInTheOriginalOperatorFolder();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderC->id, FoldersRelation::ROOT);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 1);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 1);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 2);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderB->id);
    }

    private function insertFixture_Folder_SharedWithUser_MoveFromSharedFolderToRoot_MoveToRootForUsersSeeingItInTheOriginalOperatorFolder()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder B
        // Ada is OWNER of folder C
        // Betty is OWNER of folder C
        // Ada sees C at in A
        // Betty sees C in B
        // ---
        // A (Ada:O)
        // |- C (Ada:O, Betty:O)
        //
        // B (Betty:O)
        // |- C (Ada:O, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B'], [$userBId => Permission::OWNER]);
        $folderC = $this->addFolder(['name' => 'C']);
        $this->addPermission('Folder', $folderC->id, 'User', $userAId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userAId, 'folder_parent_id' => $folderA->get('id')]);
        $this->addPermission('Folder', $folderC->id, 'User', $userBId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userBId, 'folder_parent_id' => $folderB->get('id')]);

        return [$folderA, $folderB, $folderC, $userAId, $userBId];
    }

    public function testMoveItemInUserTreeSuccess_Folder_MoveSharedFolderShouldNotCreateSCCInOtherUsersTrees()
    {
        [$folderA, $folderB, $folderC, $folderD, $userAId, $userBId, $userCId, $userDId] = $this->insertFixture_Folder_MoveSharedFolderShouldNotCreateSCCInOtherUsersTrees();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderC->id, $folderB->id);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 3);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderD->id);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userDId, $folderD->id);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, FoldersRelation::ROOT);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 2);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderB->id);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        // Folder D
        $this->assertItemIsInTrees($folderD->id, 3);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userDId, FoldersRelation::ROOT);
    }

    private function insertFixture_Folder_MoveSharedFolderShouldNotCreateSCCInOtherUsersTrees()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $userCId = UuidFactory::uuid('user.id.carol');
        $userDId = UuidFactory::uuid('user.id.dame');
        $folderA = $this->addFolder(['name' => 'A']);
        $folderB = $this->addFolder(['name' => 'B']);
        $folderC = $this->addFolder(['name' => 'C']);
        $folderD = $this->addFolder(['name' => 'D']);

        // Folder A
        $this->addPermission('Folder', $folderA->id, 'User', $userAId, Permission::OWNER);
        $this->addPermission('Folder', $folderA->id, 'User', $userBId, Permission::OWNER);
        $this->addPermission('Folder', $folderA->id, 'User', $userDId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userAId, 'folder_parent_id' => FoldersRelation::ROOT]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userBId, 'folder_parent_id' => $folderD->id]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userDId, 'folder_parent_id' => $folderD->id]);
        // Folder B
        $this->addPermission('Folder', $folderB->id, 'User', $userAId, Permission::OWNER);
        $this->addPermission('Folder', $folderB->id, 'User', $userBId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userAId, 'folder_parent_id' => $folderA->id]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userCId, 'folder_parent_id' => FoldersRelation::ROOT]);
        // Folder C
        $this->addPermission('Folder', $folderC->id, 'User', $userAId, Permission::OWNER);
        $this->addPermission('Folder', $folderC->id, 'User', $userBId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userAId, 'folder_parent_id' => FoldersRelation::ROOT]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userBId, 'folder_parent_id' => FoldersRelation::ROOT]);
        // Folder D
        $this->addPermission('Folder', $folderC->id, 'User', $userAId, Permission::OWNER);
        $this->addPermission('Folder', $folderC->id, 'User', $userBId, Permission::OWNER);
        $this->addPermission('Folder', $folderC->id, 'User', $userDId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderD->id, 'user_id' => $userBId, 'folder_parent_id' => $folderC->id]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderD->id, 'user_id' => $userCId, 'folder_parent_id' => FoldersRelation::ROOT]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderD->id, 'user_id' => $userDId, 'folder_parent_id' => FoldersRelation::ROOT]);

        return [$folderA, $folderB, $folderC, $folderD, $userAId, $userBId, $userCId, $userDId];
    }

    public function testMoveItemInUserTreeSuccess_Folder_MoveSharedFolderShouldNotCreateIndirectSCCInOtherUsersTrees()
    {
        [$folderA, $folderB, $folderC, $folderD, $userAId, $userBId, $userCId, $userDId, $userEId] = $this->insertFixture_Folder_MoveSharedFolderShouldNotCreateIndirectSCCInOtherUsersTrees();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id, $folderA->id);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderC->id);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 3);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, FoldersRelation::ROOT);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 3);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, $folderD->id);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userEId, $folderD->id);
        // Folder D
        $this->assertItemIsInTrees($folderD->id, 3);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userDId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userEId, FoldersRelation::ROOT);
    }

    private function insertFixture_Folder_MoveSharedFolderShouldNotCreateIndirectSCCInOtherUsersTrees()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $userCId = UuidFactory::uuid('user.id.carol');
        $userDId = UuidFactory::uuid('user.id.dame');
        $userEId = UuidFactory::uuid('user.id.edith');
        $folderA = $this->addFolder(['name' => 'A']);
        $folderB = $this->addFolder(['name' => 'B']);
        $folderC = $this->addFolder(['name' => 'C']);
        $folderD = $this->addFolder(['name' => 'D']);

        // Folder A
        $this->addPermission('Folder', $folderA->id, 'User', $userAId, Permission::OWNER);
        $this->addPermission('Folder', $folderA->id, 'User', $userBId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userAId, 'folder_parent_id' => FoldersRelation::ROOT]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderA->id, 'user_id' => $userBId, 'folder_parent_id' => $folderC->id]);
        // in order to make the test predictable, we need to make the relation C->A older than the relation D->C, so
        // the algorithm will always choose the relation to break.
        sleep(1);
        // Folder B
        $this->addPermission('Folder', $folderB->id, 'User', $userAId, Permission::OWNER);
        $this->addPermission('Folder', $folderB->id, 'User', $userBId, Permission::OWNER);
        $this->addPermission('Folder', $folderB->id, 'User', $userCId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userAId, 'folder_parent_id' => FoldersRelation::ROOT]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userBId, 'folder_parent_id' => FoldersRelation::ROOT]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderB->id, 'user_id' => $userCId, 'folder_parent_id' => FoldersRelation::ROOT]);
        // Folder C
        $this->addPermission('Folder', $folderC->id, 'User', $userBId, Permission::OWNER);
        $this->addPermission('Folder', $folderC->id, 'User', $userCId, Permission::OWNER);
        $this->addPermission('Folder', $folderC->id, 'User', $userEId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userBId, 'folder_parent_id' => FoldersRelation::ROOT]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userCId, 'folder_parent_id' => $folderD->id]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderC->id, 'user_id' => $userEId, 'folder_parent_id' => $folderD->id]);
        // Folder D
        $this->addPermission('Folder', $folderC->id, 'User', $userCId, Permission::OWNER);
        $this->addPermission('Folder', $folderC->id, 'User', $userDId, Permission::OWNER);
        $this->addPermission('Folder', $folderC->id, 'User', $userEId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderD->id, 'user_id' => $userCId, 'folder_parent_id' => $folderB->id]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderD->id, 'user_id' => $userDId, 'folder_parent_id' => FoldersRelation::ROOT]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderD->id, 'user_id' => $userEId, 'folder_parent_id' => FoldersRelation::ROOT]);

        return [$folderA, $folderB, $folderC, $folderD, $userAId, $userBId, $userCId, $userDId, $userEId];
    }

    public function testMoveItemInUserTreeSuccess_Folder_SharedWithUser_CannotMoveIntoSharedFolder_InsufficientPermissionMovedFolder()
    {
        [$folderA, $folderB, $userAId, $userBId] = $this->insertFixture_Folder_SharedWithUser_CannotMoveIntoSharedFolder_InsufficientPermissionMovedFolder();
        $uac = new UserAccessControl(Role::USER, $userAId);

        try {
            $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id, $folderA->id);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertEquals('Could not validate move data.', $e->getMessage());
            $this->assertNotEmpty(Hash::get($e->getErrors(), 'folder_parent_id.has_access'));
        }
    }

    public function insertFixture_Folder_SharedWithUser_CannotMoveIntoSharedFolder_InsufficientPermissionMovedFolder()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // Ada has READ on B
        // ----
        // A (Ada:O, Betty:O)
        // B (Ada:R, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B'], [$userAId => Permission::READ, $userBId => Permission::OWNER]);

        return [$folderA, $folderB, $userAId, $userBId];
    }

    public function testMoveItemInUserTreeError_Folder_SharedWithUser_CannotMoveIntoFolderWithInsufficientPermission()
    {
        [$folderA, $folderB, $userAId, $userBId] = $this->insertFixture_Folder_SharedWithUser_CannotMoveIntoFolderWithInsufficientPermission();
        $uac = new UserAccessControl(Role::USER, $userAId);

        try {
            $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->id, $folderB->id);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertEquals('Could not validate move data.', $e->getMessage());
            $this->assertNotEmpty(Hash::get($e->getErrors(), 'folder_parent_id.has_folder_access'));
        }
    }

    public function insertFixture_Folder_SharedWithUser_CannotMoveIntoFolderWithInsufficientPermission()
    {
        // Ada is OWNER of folder A
        // Ada has READ access on folder B
        // Betty is OWNER of folder B
        // ----
        // A (Ada:O)
        // B (Ada:R, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B'], [$userAId => Permission::READ, $userBId => Permission::OWNER]);

        return [$folderA, $folderB, $userAId, $userBId];
    }

    public function testMoveItemInUserTreeError_Folder_SharedWithUser_CannotMoveOutOfFolderWithInsufficientPermission()
    {
        [$folderA, $folderB, $userAId, $userBId] = $this->insertFixture_Folder_SharedWithUser_CannotMoveOutOfFolderWithInsufficientPermissionShared_InsufficientPermissionOnOriginalParentFolder();
        $uac = new UserAccessControl(Role::USER, $userAId);

        try {
            $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id, FoldersRelation::ROOT);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertEquals('Could not validate move data.', $e->getMessage());
            $this->assertNotEmpty(Hash::get($e->getErrors(), 'folder_parent_id.has_folder_access'));
        }
    }

    public function insertFixture_Folder_SharedWithUser_CannotMoveOutOfFolderWithInsufficientPermissionShared_InsufficientPermissionOnOriginalParentFolder()
    {
        // Ada has access to folder A as a READ
        // Betty is OWNER of folder A
        // Ada is OWNER of folder B
        // Betty is OWNER of folder B
        // Folder B is in folder A
        // ---
        // A (Ada:R, Betty:O)
        // |- B (Ada:O, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::READ, $userBId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B', 'folder_parent_id' => $folderA->get('id')], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);

        return [$folderA, $folderB, $userAId, $userBId];
    }

    public function testMoveItemInUserTreeError_Folder_SharedWithUser_CannotMoveOutOfFolder_InsufficientPermissionMovedFolder()
    {
        [$folderA, $folderB, $userAId, $userBId] = $this->insertFixture_Folder_SharedWithUser_CannotMoveOutOfFolder_InsufficientPermissionMovedFolder();
        $uac = new UserAccessControl(Role::USER, $userAId);

        try {
            $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id, FoldersRelation::ROOT);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertEquals('Could not validate move data.', $e->getMessage());
            $this->assertNotEmpty(Hash::get($e->getErrors(), 'folder_parent_id.has_access'));
        }
    }

    public function insertFixture_Folder_SharedWithUser_CannotMoveOutOfFolder_InsufficientPermissionMovedFolder()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // Ada has READ on folder B
        // Betty is OWNER of folder B
        // Folder A is in B
        // ---
        // A (Ada:O, Betty:O)
        // |- B (Ada:R, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B', 'folder_parent_id' => $folderA->get('id')], [$userAId => Permission::READ, $userBId => Permission::OWNER]);

        return [$folderA, $folderB, $userAId, $userBId];
    }

    /* FOLDER - SELF ORGANIZE */

    public function testMoveItemInUserTreeSuccess_Folder_SelfOrganize_MoveFromRootToPersonalFolder()
    {
        [$folderA, $folderB, $userAId, $userBId] = $this->insertFixture_Folder_SelfOrganize_MoveFromRootToPersonalFolder();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->get('id'), $folderA->get('id'));

        $this->assertItemIsInTrees($folderA->id, 1);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
    }

    private function insertFixture_Folder_SelfOrganize_MoveFromRootToPersonalFolder()
    {
        // Ada is OWNER of folder A
        // Ada has READ on folder B
        // Betty is OWNER of folder B
        // ---
        // A (Ada:O)
        // B (Ada:R, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B'], [$userAId => Permission::READ, $userBId => Permission::OWNER]);

        return [$folderA, $folderB, $userAId, $userBId];
    }

    public function testMoveItemInUserTreeSuccess_Folder_SelfOrganize_MoveFromPersonalToRoot()
    {
        [$folderA, $folderB, $userAId, $userBId] = $this->insertFixture_Folder_SelfOrganize_MoveFromPersonalToRoot();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->id, FoldersRelation::ROOT);

        $this->assertItemIsInTrees($folderA->id, 1);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
    }

    private function insertFixture_Folder_SelfOrganize_MoveFromPersonalToRoot()
    {
        // Ada is OWNER of folder A
        // Ada has READ on folder B
        // Betty is OWNER of folder B
        // ---
        // A (Ada:O)
        // |- B (Ada:R, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B', 'folder_parent_id' => $folderA->get('id')], [$userAId => Permission::READ, $userBId => Permission::OWNER]);

        return [$folderA, $folderB, $userAId, $userBId];
    }

    public function testMoveItemInUserTreeError_Folder_SelfOrganize_CannotCreateCycle()
    {
        [$folderA, $folderB, $folderC, $userAId, $userBId] = $this->insertFixture_Folder_SelfOrganize_CannotCreateCycle();
        $uac = new UserAccessControl(Role::USER, $userAId);

        try {
            $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->get('id'), $folderC->get('id'));
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertEquals('Could not validate move data.', $e->getMessage());
            $this->assertNotEmpty(Hash::get($e->getErrors(), 'folder_parent_id.cycle'));
        }
    }

    private function insertFixture_Folder_SelfOrganize_CannotCreateCycle()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // Ada is OWNER of folder B
        // Ada is OWNER of folder C
        // Betty is OWNER of folder C
        // Folder B is in A
        // Folder C is in B
        // ---
        // A (Ada:O)
        // |- B (Ada:O)
        //    |- C (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B'], [$userAId => Permission::OWNER]);
        $folderC = $this->addFolderFor(['name' => 'C', 'folder_parent_id' => $folderA->get('id')], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);

        return [$folderA, $folderB, $folderC, $userAId, $userBId];
    }

    /* RESOURCE - COMMON */

    public function testMoveItemInUserTreeError_Resource_Common_CannotMoveIntoAFolderDoesNotExist()
    {
        [$resource, $userAId] = $this->insertFixture_Resource_Common_CannotMoveIntoAFolderDoesNotExist();
        $uac = new UserAccessControl(Role::USER, $userAId);

        try {
            $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_RESOURCE, $resource->id, UuidFactory::uuid());
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertEquals('Could not validate move data.', $e->getMessage());
            $this->assertNotEmpty(Hash::get($e->getErrors(), 'folder_parent_id.folder_exists'));
        }
    }

    public function insertFixture_Resource_Common_CannotMoveIntoAFolderDoesNotExist()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $resource = $this->addResourceFor([], [$userAId => Permission::OWNER]);

        return [$resource, $userAId];
    }

    /* RESOURCE - PERSONAL */

    public function testMoveItemInUserTreeSuccess_Resource_Personal_MoveFromRoot()
    {
        [$folderA, $resource, $userAId] = $this->insertFixture_Resource_Personal_MoveFromRoot();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_RESOURCE, $resource->id, $folderA->id);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 1);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        // Resource
        $this->assertItemIsInTrees($resource->id, 1);
        $this->assertFolderRelation($resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, $folderA->id);
    }

    public function insertFixture_Resource_Personal_MoveFromRoot()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $folderA = $this->addFolderFor([], [$userAId => Permission::OWNER]);
        $resource = $this->addResourceFor([], [$userAId => Permission::OWNER]);

        return [$folderA, $resource, $userAId];
    }

    public function testMoveItemInUserTreeSuccess_Resource_Personal_MoveToRoot()
    {
        [$folderA, $resource, $userAId] = $this->insertFixture_Resource_Personal_MoveToRoot();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_RESOURCE, $resource->id, FoldersRelation::ROOT);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 1);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        // Resource
        $this->assertItemIsInTrees($resource->id, 1);
        $this->assertFolderRelation($resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, FoldersRelation::ROOT);
    }

    public function insertFixture_Resource_Personal_MoveToRoot()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $folderA = $this->addFolderFor([], [$userAId => Permission::OWNER]);
        $resource = $this->addResourceFor(['folder_parent_id' => $folderA->get('id')], [$userAId => Permission::OWNER]);

        return [$folderA, $resource, $userAId];
    }

    /* RESOURCE - SHARED */

    public function testMoveItemInUserTreeSuccess_Resource_SharedWithUser_MoveFromRoot()
    {
        [$folderA, $resource, $userAId, $userBId] = $this->insertFixture_Resource_SharedWithUser_MoveFromRoot();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_RESOURCE, $resource->id, $folderA->id);

        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        $this->assertItemIsInTrees($resource->id, 2);
        $this->assertFolderRelation($resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, $folderA->id);
        $this->assertFolderRelation($resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, $folderA->id);
    }

    private function insertFixture_Resource_SharedWithUser_MoveFromRoot()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // Ada is OWNER of resource R1
        // Betty is OWNER resource R1
        // ---
        // A (Ada:O, Betty:Owner)
        // R1 (Ada:O, Betty:Owner)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $resource = $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);

        return [$folderA, $resource, $userAId, $userBId];
    }

    public function testMoveItemInUserTreeSuccess_Resource_SharedWithGroup_MoveFromRoot()
    {
        [$folderA, $resource, $g1, $userAId, $userBId] = $this->insertFixture_Resource_SharedWithGroup_MoveFromRoot();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_RESOURCE, $resource->id, $folderA->id);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        // Folder B
        $this->assertItemIsInTrees($resource->id, 2);
        $this->assertFolderRelation($resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, $folderA->id);
        $this->assertFolderRelation($resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, $folderA->id);
    }

    private function insertFixture_Resource_SharedWithGroup_MoveFromRoot()
    {
        // Ada is OWNER of folder A
        // G1 is OWNER of folder A
        // Ada is OWNER of resource R1
        // G1 is OWNER of resource R1
        // Betty is member of G1
        // ---
        // A (Ada:O, G1:Owner)
        // R1 (Ada:O, G1:Owner)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $g1 = $this->addGroup(['name' => 'G1', 'groups_users' => [
            ['user_id' => $userBId, 'is_admin' => true],
        ]]);
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER], [$g1->id => Permission::OWNER]);
        $resource = $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER], [$g1->id => Permission::OWNER]);

        return [$folderA, $resource, $g1, $userAId, $userBId];
    }

    public function testMoveItemInUserTreeSuccess_Resource_SharedWithUser_MoveIntoSharedFolder_MoveToRootForUsersNotSeeingDestination()
    {
        [$folderA, $resource, $folderC, $userAId, $userBId] = $this->insertFixture_Resource_SharedWithUser_MoveIntoSharedFolder_MoveToRootForUsersNotSeeingDestination();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_RESOURCE, $resource->id, $folderC->id);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        // Resource
        $this->assertItemIsInTrees($resource->id, 2);
        $this->assertFolderRelation($resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, $folderC->id);
        $this->assertFolderRelation($resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, FoldersRelation::ROOT);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 1);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
    }

    private function insertFixture_Resource_SharedWithUser_MoveIntoSharedFolder_MoveToRootForUsersNotSeeingDestination()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // Ada is OWNER of resource R1
        // Betty is OWNER of resource R1
        // Ada is OWNER of folder C
        // ---
        // C (Ada:O)
        // A (Ada:O, Betty:O)
        // |- R1 (Ada:O, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $resource = $this->addResourceFor(['name' => 'R1', 'folder_parent_id' => $folderA->get('id')], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $folderC = $this->addFolderFor(['name' => 'C'], [$userAId => Permission::OWNER]);

        return [$folderA, $resource, $folderC, $userAId, $userBId];
    }

    public function testMoveItemInUserTreeSuccess_Resource_SharedWithUser_MoveFromSharedFolderToSharedFolder_DontMoveForUsersNotSeeingOriginalAndTargetFolders()
    {
        [$folderA, $resource, $folderC, $folderD, $userAId, $userBId] =
            $this->insertFixture_Resource_SharedWithUser_MoveFromSharedFolderToSharedFolder_DontMoveForUsersNotSeeingOriginalAndTargetFolders();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_RESOURCE, $resource->get('id'), $folderD->get('id'));

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 1);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        // R1
        $this->assertItemIsInTrees($resource->id, 2);
        $this->assertFolderRelation($resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, $folderD->id);
        $this->assertFolderRelation($resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, $folderC->id);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 1);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        // Folder D
        $this->assertItemIsInTrees($folderD->id, 1);
        $this->assertFolderRelation($folderD->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
    }

    private function insertFixture_Resource_SharedWithUser_MoveFromSharedFolderToSharedFolder_DontMoveForUsersNotSeeingOriginalAndTargetFolders()
    {
        // Ada is OWNER of folder A
        // Ada is OWNER of folder B
        // Betty is OWNER of resource R1
        // Betty is OWNER of resource R1
        // Ada is OWNER of folder D
        // Ada sees R1 in folder A
        // Betty sees R1 in folder C
        // ---
        // A (Ada:O)
        // |- R1 (Ada:O, Betty:O)
        //
        // C (Betty:O)
        // |- R1 (Ada:O, Betty:O)
        //
        // D (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);
        $folderC = $this->addFolderFor(['name' => 'C'], [$userBId => Permission::OWNER]);
        $resource = $this->addResource(['name' => 'R1']);
        $this->addPermission(PermissionsTable::RESOURCE_ACO, $resource->id, 'User', $userAId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::RESOURCE_ACO, 'foreign_id' => $resource->id, 'user_id' => $userAId, 'folder_parent_id' => $folderA->get('id')]);
        $this->addPermission(PermissionsTable::RESOURCE_ACO, $resource->id, 'User', $userBId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::RESOURCE_ACO, 'foreign_id' => $resource->id, 'user_id' => $userBId, 'folder_parent_id' => $folderC->get('id')]);
        $folderD = $this->addFolderFor(['name' => 'D'], [$userAId => Permission::OWNER]);

        return [$folderA, $resource, $folderC, $folderD, $userAId, $userBId];
    }

    public function testMoveItemInUserTreeSuccess_Resource_SharedWithUser_MoveFromRootToSharedFolder_MoveToTargetFolderForUsersSeeingIt()
    {
        [$folderA, $resource, $folderC, $userAId, $userBId] =
            $this->insertFixture_Resource_SharedWithUser_MoveFromRootToSharedFolder_MoveToTargetFolderForUsersSeeingIt();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_RESOURCE, $resource->id, $folderC->id);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 1);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        // R1
        $this->assertItemIsInTrees($resource->id, 2);
        $this->assertFolderRelation($resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, $folderC->id);
        $this->assertFolderRelation($resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, $folderC->id);
        // Folder C
        $this->assertItemIsInTrees($folderC->id, 2);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
    }

    private function insertFixture_Resource_SharedWithUser_MoveFromRootToSharedFolder_MoveToTargetFolderForUsersSeeingIt()
    {
        // Betty is OWNER of folder A
        // Ada is OWNER of resource R1
        // Betty is OWNER of resource R1
        // Folder B is in Folder A
        // Ada is OWNER of folder C
        // Betty is OWNER of folder C
        // ---
        // A (Betty:O)
        // |- R1 (Ada:O, Betty:O)
        //
        // C (Ada:O, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userBId => Permission::OWNER]);
        $resource = $this->addResource(['name' => 'R1']);
        $this->addPermission(PermissionsTable::RESOURCE_ACO, $resource->id, 'User', $userAId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::RESOURCE_ACO, 'foreign_id' => $resource->id, 'user_id' => $userAId, 'folder_parent_id' => FoldersRelation::ROOT]);
        $this->addPermission(PermissionsTable::RESOURCE_ACO, $resource->id, 'User', $userBId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::RESOURCE_ACO, 'foreign_id' => $resource->id, 'user_id' => $userBId, 'folder_parent_id' => $folderA->id]);
        $folderC = $this->addFolderFor(['name' => 'C'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);

        return [$folderA, $resource, $folderC, $userAId, $userBId];
    }

    public function testMoveItemInUserTreeSuccess_Resource_SharedWithUser_MoveFromSharedFolderToRoot_MoveToRootForUsersSeeingItInTheOriginalOperatorFolder()
    {
        [$folderA, $folderB, $resource, $userAId, $userBId] = $this->insertFixture_Resource_SharedWithUser_MoveFromSharedFolderToRoot_MoveToRootForUsersSeeingItInTheOriginalOperatorFolder();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_RESOURCE, $resource->id, FoldersRelation::ROOT);

        // Folder A
        $this->assertItemIsInTrees($folderA->id, 1);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        // Folder B
        $this->assertItemIsInTrees($folderB->id, 1);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
        // R1
        $this->assertItemIsInTrees($resource->id, 2);
        $this->assertFolderRelation($resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, $folderB->id);
    }

    private function insertFixture_Resource_SharedWithUser_MoveFromSharedFolderToRoot_MoveToRootForUsersSeeingItInTheOriginalOperatorFolder()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder B
        // Ada is OWNER of resource R1
        // Betty is OWNER of resource R1
        // Ada sees R1 at in A
        // Betty sees R1 in B
        // ---
        // A (Ada:O)
        // |- R1 (Ada:O, Betty:O)
        //
        // B (Betty:O)
        // |- R1 (Ada:O, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B'], [$userBId => Permission::OWNER]);
        $resource = $this->addResource(['name' => 'R1']);
        $this->addPermission(PermissionsTable::RESOURCE_ACO, $resource->id, 'User', $userAId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => FoldersRelation::FOREIGN_MODEL_RESOURCE, 'foreign_id' => $resource->id, 'user_id' => $userAId, 'folder_parent_id' => $folderA->get('id')]);
        $this->addPermission(PermissionsTable::RESOURCE_ACO, $resource->id, 'User', $userBId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => FoldersRelation::FOREIGN_MODEL_RESOURCE, 'foreign_id' => $resource->id, 'user_id' => $userBId, 'folder_parent_id' => $folderB->get('id')]);

        return [$folderA, $folderB, $resource, $userAId, $userBId];
    }

    public function testMoveItemInUserTreeError_Resource_SharedWithUser_CannotMoveIntoFolderWithInsufficientPermission()
    {
        [$folderA, $resource, $userAId, $userBId] = $this->insertFixture_Resource_SharedWithUser_CannotMoveIntoFolderWithInsufficientPermission();
        $uac = new UserAccessControl(Role::USER, $userAId);

        try {
            $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_RESOURCE, $resource->id, $folderA->id);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertEquals('Could not validate move data.', $e->getMessage());
            $this->assertNotEmpty(Hash::get($e->getErrors(), 'folder_parent_id.has_folder_access'));
        }
    }

    public function insertFixture_Resource_SharedWithUser_CannotMoveIntoFolderWithInsufficientPermission()
    {
        // Ada is OWNER of resource R1
        // Ada has access READ access on folder B
        // Betty is OWNER of folder B
        // A (Ada:R, Betty:O)
        // R1 (Ada:Owner)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::READ, $userBId => Permission::OWNER]);
        $resource = $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER]);

        return [$folderA, $resource, $userAId, $userBId];
    }

    public function testMoveItemInUserTreeError_Resource_SharedWithUser_CannotMoveOutOfFolderWithInsufficientPermission()
    {
        [$folderA, $resource, $userAId, $userBId] = $this->insertFixture_Resource_SharedWithUser_CannotMoveOutOfFolderWithInsufficientPermissionShared_InsufficientPermissionOnOriginalParentFolder();
        $uac = new UserAccessControl(Role::USER, $userAId);

        try {
            $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_RESOURCE, $resource->id, FoldersRelation::ROOT);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertEquals('Could not validate move data.', $e->getMessage());
            $this->assertNotEmpty(Hash::get($e->getErrors(), 'folder_parent_id.has_folder_access'));
        }
    }

    public function insertFixture_Resource_SharedWithUser_CannotMoveOutOfFolderWithInsufficientPermissionShared_InsufficientPermissionOnOriginalParentFolder()
    {
        // Ada has access to folder A as a READ
        // Betty is OWNER of folder A
        // Ada is OWNER of resource R1
        // Betty is OWNER of resource R1
        // R1 is A
        // ---
        // A (Ada:R, Betty:O)
        // |- R1 (Ada:O, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::READ, $userBId => Permission::OWNER]);
        $resource = $this->addResourceFor(['name' => 'R1', 'folder_parent_id' => $folderA->get('id')], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);

        return [$folderA, $resource, $userAId, $userBId];
    }

    /* RESOURCE - SELF ORGANIZE */

    public function testMoveItemInUserTreeSuccess_Resource_SelfOrganize_MoveFromRootToPersonalFolder()
    {
        [$folderA, $resource, $userAId, $userBId] = $this->insertFixture_Resource_SelfOrganize_MoveFromRootToPersonalFolder();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_RESOURCE, $resource->get('id'), $folderA->get('id'));

        $this->assertItemIsInTrees($folderA->id, 1);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertItemIsInTrees($resource->id, 2);
        $this->assertFolderRelation($resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, $folderA->id);
        $this->assertFolderRelation($resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, FoldersRelation::ROOT);
    }

    private function insertFixture_Resource_SelfOrganize_MoveFromRootToPersonalFolder()
    {
        // Ada is OWNER of folder A
        // Ada has READ on folder B
        // Betty is OWNER of folder B
        // ---
        // A (Ada:O)
        // B (Ada:R, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);
        $resource = $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::READ, $userBId => Permission::OWNER]);

        return [$folderA, $resource, $userAId, $userBId];
    }

    public function testMoveItemInUserTreeSuccess_Resource_SelfOrganize_MoveFromPersonalFolderToRoot()
    {
        [$folderA, $resource, $userAId, $userBId] = $this->insertFixture_Resource_SelfOrganize_MoveFromPersonalFolderToRoot();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_RESOURCE, $resource->id, FoldersRelation::ROOT);

        $this->assertItemIsInTrees($folderA->id, 1);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertItemIsInTrees($resource->id, 2);
        $this->assertFolderRelation($resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, FoldersRelation::ROOT);
    }

    private function insertFixture_Resource_SelfOrganize_MoveFromPersonalFolderToRoot()
    {
        // Ada is OWNER of folder A
        // Ada has READ on resource R1
        // Betty is OWNER of resource R1
        // ---
        // A (Ada:O)
        // |- R1 (Ada:R, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);
        $resource = $this->addResourceFor(['name' => 'R1', 'folder_parent_id' => $folderA->get('id')], [$userAId => Permission::READ, $userBId => Permission::OWNER]);

        return [$folderA, $resource, $userAId, $userBId];
    }
}
