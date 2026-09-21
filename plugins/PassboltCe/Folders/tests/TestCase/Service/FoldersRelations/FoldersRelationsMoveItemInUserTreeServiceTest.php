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
use App\Test\Factory\GroupFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Utility\Hash;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Service\FoldersRelations\FoldersRelationsDetectStronglyConnectedComponentsService;
use Passbolt\Folders\Service\FoldersRelations\FoldersRelationsMoveItemInUserTreeService;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Folders\Test\Factory\FoldersRelationFactory;
use Passbolt\Folders\Test\Factory\ResourceFactory;
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
        // Ada has access to folder A as a OWNER
        // A (Ada:O)
        /** @var \App\Model\Entity\User $userA */
        $userA = UserFactory::make()->user()->persist();
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);

        try {
            $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->get('id'), UuidFactory::uuid());
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertEquals('Could not validate move data.', $e->getMessage());
            $this->assertNotEmpty(Hash::get($e->getErrors(), 'folder_parent_id.folder_exists'));
        }
    }

    /* FOLDER - PERSONAL */

    public function testMoveItemInUserTreeSuccess_Folder_Personal_MoveFolderFromRoot()
    {
        // Ada has access to folder A as a OWNER
        // Ada has access to folder B as a OWNER
        // ---
        // A (Ada:O)
        // B (Ada:O)
        /** @var \App\Model\Entity\User $userA */
        $userA = UserFactory::make()->user()->persist();
        [$folderA, $folderB] = FolderFactory::make(2)
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->get('id'), $folderA->get('id'));

        $this->assertItemIsInTrees($folderA->get('id'), 1);
        $this->assertFolderRelation($folderA->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertItemIsInTrees($folderB->get('id'), 1);
        $this->assertFolderRelation($folderB->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderA->get('id'));
    }

    public function testMoveItemInUserTreeSuccess_Folder_Personal_MoveFolderToRoot()
    {
        // Ada has access to folder A as a OWNER
        // Ada has access to folder B as a OWNER
        // A (Ada:O)
        // |- B (Ada:O)
        /** @var \App\Model\Entity\User $userA */
        $userA = UserFactory::make()->user()->persist();
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA], $folderA)
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->get('id'), FoldersRelation::ROOT);

        $this->assertItemIsInTrees($folderA->get('id'), 1);
        $this->assertFolderRelation($folderA->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertItemIsInTrees($folderB->get('id'), 1);
        $this->assertFolderRelation($folderB->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
    }

    public function testMoveItemInUserTreeError_Folder_Personal_CannotCreateCycle()
    {
        // Ada has access to folder A as a OWNER
        // Ada has access to folder B as a OWNER
        // A (Ada:O)
        // |- B (Ada:O)
        /** @var \App\Model\Entity\User $userA */
        $userA = UserFactory::make()->user()->persist();
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA], $folderA)
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);

        try {
            $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->get('id'), $folderB->get('id'));
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertEquals('Could not validate move data.', $e->getMessage());
            $this->assertNotEmpty(Hash::get($e->getErrors(), 'folder_parent_id.cycle'));
        }
    }

    /* FOLDER - SHARED */

    public function testMoveItemInUserTreeSuccess_Folder_SharedWithUser_MoveFolderFromRoot()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // Ada is OWNER of folder B
        // Betty is OWNER of folder B
        // ---
        // A (Ada:O, Betty:Owner)
        // B (Ada:O, Betty:Owner)
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        [$folderA, $folderB] = FolderFactory::make(2)
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->get('id'), $folderA->get('id'));

        $this->assertItemIsInTrees($folderA->get('id'), 2);
        $this->assertFolderRelation($folderA->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        $this->assertItemIsInTrees($folderB->get('id'), 2);
        $this->assertFolderRelation($folderB->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderA->get('id'));
        $this->assertFolderRelation($folderB->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, $folderA->get('id'));
    }

    public function testMoveItemInUserTreeSuccess_Folder_SharedWithGroup_MoveFolderFromRoot()
    {
        // Ada is OWNER of folder A
        // G1 is OWNER of folder A
        // Ada is OWNER of folder B
        // G1 is OWNER of folder B
        // Betty is member of G1
        // ---
        // A (Ada:O, G1:Owner)
        // B (Ada:O, G1:Owner)
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $groupA = GroupFactory::make()->withGroupsManagersFor([$userB])->persist();
        [$folderA, $folderB] = FolderFactory::make(2)
            ->withPermissionsFor([$userA, $groupA])
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->get('id'), $folderA->get('id'));

        // Folder A
        $this->assertItemIsInTrees($folderA->get('id'), 2);
        $this->assertFolderRelation($folderA->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        // Folder B
        $this->assertItemIsInTrees($folderB->get('id'), 2);
        $this->assertFolderRelation($folderB->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderA->get('id'));
        $this->assertFolderRelation($folderB->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, $folderA->get('id'));
    }

    public function testMoveItemInUserTreeSuccess_Folder_SharedWithUser_MoveIntoSharedFolder_MoveToRootForUsersNotSeeingDestination()
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
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA, $userB], $folderA)
            ->persist();
        $folderC = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->get('id'), $folderC->get('id'));

        // Folder A
        $this->assertItemIsInTrees($folderA->get('id'), 2);
        $this->assertFolderRelation($folderA->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        // Folder B
        $this->assertItemIsInTrees($folderB->get('id'), 2);
        $this->assertFolderRelation($folderB->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderC->get('id'));
        $this->assertFolderRelation($folderB->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        // Folder C
        $this->assertItemIsInTrees($folderC->get('id'), 1);
        $this->assertFolderRelation($folderC->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
    }

    public function testMoveItemInUserTreeSuccess_Folder_SharedWithUser_MoveFromSharedFolderToSharedFolder_DontMoveForUsersNotSeeingOriginalAndTargetFolders()
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
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderD */
        [$folderA, $folderD] = FolderFactory::make(2)
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderC */
        $folderC = FolderFactory::make()
            ->withPermissionsFor([$userB])
            ->withFoldersRelationsFor([$userB])
            ->persist();
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA], $folderA)
            ->withFoldersRelationsFor([$userB], $folderC)
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->get('id'), $folderD->get('id'));

        // Folder A
        $this->assertItemIsInTrees($folderA->get('id'), 1);
        $this->assertFolderRelation($folderA->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        // Folder B
        $this->assertItemIsInTrees($folderB->get('id'), 2);
        $this->assertFolderRelation($folderB->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderD->get('id'));
        $this->assertFolderRelation($folderB->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, $folderC->get('id'));
        // Folder C
        $this->assertItemIsInTrees($folderC->get('id'), 1);
        $this->assertFolderRelation($folderC->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        // Folder D
        $this->assertItemIsInTrees($folderD->get('id'), 1);
        $this->assertFolderRelation($folderD->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
    }

    public function testMoveItemInUserTreeSuccess_Folder_SharedWithUser_MoveFromRootToSharedFolder_MoveToTargetFolderForUsersSeeingIt()
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
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userB])
            ->withFoldersRelationsFor([$userB])
            ->persist();
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA])
            ->withFoldersRelationsFor([$userB], $folderA)
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderC */
        $folderC = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->get('id'), $folderC->get('id'));

        // Folder A
        $this->assertItemIsInTrees($folderA->get('id'), 1);
        $this->assertFolderRelation($folderA->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        // Folder B
        $this->assertItemIsInTrees($folderB->get('id'), 2);
        $this->assertFolderRelation($folderB->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderC->get('id'));
        $this->assertFolderRelation($folderB->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, $folderC->get('id'));
        // Folder C
        $this->assertItemIsInTrees($folderC->get('id'), 2);
        $this->assertFolderRelation($folderC->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderC->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
    }

    public function testMoveItemInUserTreeSuccess_Folder_SharedWithUser_MoveFromSharedFolderToRoot_MoveToRootForUsersSeeingItInTheOriginalOperatorFolder()
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
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userB])
            ->withFoldersRelationsFor([$userB])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderC */
        $folderC = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA], $folderA)
            ->withFoldersRelationsFor([$userB], $folderB)
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderC->get('id'), FoldersRelation::ROOT);

        // Folder A
        $this->assertItemIsInTrees($folderA->get('id'), 1);
        $this->assertFolderRelation($folderA->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        // Folder B
        $this->assertItemIsInTrees($folderB->get('id'), 1);
        $this->assertFolderRelation($folderB->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        // Folder C
        $this->assertItemIsInTrees($folderC->get('id'), 2);
        $this->assertFolderRelation($folderC->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderC->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, $folderB->get('id'));
    }

    public function testMoveItemInUserTreeSuccess_Folder_MoveSharedFolderShouldNotCreateSCCInOtherUsersTrees()
    {
        [$userA, $userB, $userC, $userD] = UserFactory::make(4)->user()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderC */
        $folderC = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderD */
        $folderD = FolderFactory::make()
            ->withPermissionsFor([$userB, $userC, $userD])
            ->withFoldersRelationsFor([$userB], $folderC)
            ->withFoldersRelationsFor([ $userC, $userD])
            ->persist();
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB, $userD])
            ->withFoldersRelationsFor([$userA])
            ->withFoldersRelationsFor([$userB, $userD], $folderD)
            ->persist();
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userA, $userC])
            ->withFoldersRelationsFor([$userA], $folderA)
            ->withFoldersRelationsFor([$userC])
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderC->get('id'), $folderB->get('id'));

        // Folder A
        $this->assertItemIsInTrees($folderA->get('id'), 3);
        $this->assertFolderRelation($folderA->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, $folderD->get('id'));
        $this->assertFolderRelation($folderA->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userD->id, $folderD->get('id'));
        // Folder B
        $this->assertItemIsInTrees($folderB->get('id'), 2);
        $this->assertFolderRelation($folderB->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderA->get('id'));
        $this->assertFolderRelation($folderB->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, FoldersRelation::ROOT);
        // Folder C
        $this->assertItemIsInTrees($folderC->get('id'), 2);
        $this->assertFolderRelation($folderC->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderB->get('id'));
        $this->assertFolderRelation($folderC->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        // Folder D
        $this->assertItemIsInTrees($folderD->get('id'), 3);
        $this->assertFolderRelation($folderD->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderD->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderD->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userD->id, FoldersRelation::ROOT);
    }

    public function testMoveItemInUserTreeSuccess_Folder_MoveSharedFolderShouldNotCreateIndirectSCCInOtherUsersTrees()
    {
        [$userA, $userB, $userC, $userD, $userE] = UserFactory::make(5)->user()->persist();
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB, $userC])
            ->withFoldersRelationsFor([$userA, $userB, $userC])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderD */
        $folderD = FolderFactory::make()
            ->withPermissionsFor([$userC, $userD, $userE])
            ->withFoldersRelationsFor([$userD, $userE])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderC */
        $folderC = FolderFactory::make()
            ->withPermissionsFor([$userB, $userC, $userE])
            ->withFoldersRelationsFor([$userB])
            ->persist();
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        // SCC repair tie-breaks on MIN(created). Pin the cycle relations explicitly so the
        // algorithm always picks "D under B" as the relation to break: the kept "A under C"
        // must be older than "D under B". FoldersRelationFactory's default `created` is a
        // random past date, which makes the comparison non-deterministic without this pin.
        FoldersRelationFactory::make(['created' => '2020-01-01 00:00:00'])
            ->user($userB)
            ->foreignModelFolder($folderA)
            ->folderParent($folderC)
            ->persist();
        FoldersRelationFactory::make(['created' => '2020-02-01 00:00:00'])
            ->user($userC)
            ->foreignModelFolder($folderD)
            ->folderParent($folderB)
            ->persist();
        FoldersRelationFactory::make(['created' => '2020-02-01 00:00:00'])
            ->user($userC)
            ->foreignModelFolder($folderC)
            ->folderParent($folderD)
            ->persist();
        FoldersRelationFactory::make(['created' => '2020-02-01 00:00:00'])
            ->user($userE)
            ->foreignModelFolder($folderC)
            ->folderParent($folderD)
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->get('id'), $folderA->get('id'));

        // Folder A
        $this->assertItemIsInTrees($folderA->get('id'), 2);
        $this->assertFolderRelation($folderA->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, $folderC->get('id'));
        // Folder B
        $this->assertItemIsInTrees($folderB->get('id'), 3);
        $this->assertFolderRelation($folderB->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderA->get('id'));
        $this->assertFolderRelation($folderB->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, $folderA->get('id'));
        $this->assertFolderRelation($folderB->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, FoldersRelation::ROOT);
        // Folder C
        $this->assertItemIsInTrees($folderC->get('id'), 3);
        $this->assertFolderRelation($folderC->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderC->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, $folderD->get('id'));
        $this->assertFolderRelation($folderC->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userE->id, $folderD->get('id'));
        // Folder D
        $this->assertItemIsInTrees($folderD->get('id'), 3);
        $this->assertFolderRelation($folderD->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userC->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderD->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userD->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderD->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userE->id, FoldersRelation::ROOT);
    }

    public function testMoveItemInUserTreeSuccess_Folder_SharedWithUser_CannotMoveIntoSharedFolder_InsufficientPermissionMovedFolder()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // Ada has READ on B
        // ----
        // A (Ada:O, Betty:O)
        // B (Ada:R, Betty:O)
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userA], Permission::READ)
            ->withPermissionsFor([$userB])
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);

        try {
            $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->get('id'), $folderA->get('id'));
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertEquals('Could not validate move data.', $e->getMessage());
            $this->assertNotEmpty(Hash::get($e->getErrors(), 'folder_parent_id.has_access'));
        }
    }

    public function testMoveItemInUserTreeError_Folder_SharedWithUser_CannotMoveIntoFolderWithInsufficientPermission()
    {
        // Ada is OWNER of folder A
        // Ada has READ access on folder B
        // Betty is OWNER of folder B
        // ----
        // A (Ada:O)
        // B (Ada:R, Betty:O)
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userA], Permission::READ)
            ->withPermissionsFor([$userB])
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);

        try {
            $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->get('id'), $folderB->get('id'));
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertEquals('Could not validate move data.', $e->getMessage());
            $this->assertNotEmpty(Hash::get($e->getErrors(), 'folder_parent_id.has_folder_access'));
        }
    }

    public function testMoveItemInUserTreeError_Folder_SharedWithUser_CannotMoveOutOfFolderWithInsufficientPermission()
    {
        // Ada has access to folder A as a READ
        // Betty is OWNER of folder A
        // Ada is OWNER of folder B
        // Betty is OWNER of folder B
        // Folder B is in folder A
        // ---
        // A (Ada:R, Betty:O)
        // |- B (Ada:O, Betty:O)
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA], Permission::READ)
            ->withPermissionsFor([$userB])
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA, $userB], $folderA)
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);

        try {
            $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->get('id'), FoldersRelation::ROOT);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertEquals('Could not validate move data.', $e->getMessage());
            $this->assertNotEmpty(Hash::get($e->getErrors(), 'folder_parent_id.has_folder_access'));
        }
    }

    public function testMoveItemInUserTreeError_Folder_SharedWithUser_CannotMoveOutOfFolder_InsufficientPermissionMovedFolder()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // Ada has READ on folder B
        // Betty is OWNER of folder B
        // Folder A is in B
        // ---
        // A (Ada:O, Betty:O)
        // |- B (Ada:R, Betty:O)
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userA], Permission::READ)
            ->withPermissionsFor([$userB])
            ->withFoldersRelationsFor([$userA, $userB], $folderA)
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);

        try {
            $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->get('id'), FoldersRelation::ROOT);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertEquals('Could not validate move data.', $e->getMessage());
            $this->assertNotEmpty(Hash::get($e->getErrors(), 'folder_parent_id.has_access'));
        }
    }

    /* FOLDER - SELF ORGANIZE */

    public function testMoveItemInUserTreeSuccess_Folder_SelfOrganize_MoveFromRootToPersonalFolder()
    {
        // Ada is OWNER of folder A
        // Ada has READ on folder B
        // Betty is OWNER of folder B
        // ---
        // A (Ada:O)
        // B (Ada:R, Betty:O)
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userA], Permission::READ)
            ->withPermissionsFor([$userB])
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->get('id'), $folderA->get('id'));

        $this->assertItemIsInTrees($folderA->get('id'), 1);
        $this->assertFolderRelation($folderA->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertItemIsInTrees($folderB->get('id'), 2);
        $this->assertFolderRelation($folderB->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderA->get('id'));
        $this->assertFolderRelation($folderB->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
    }

    public function testMoveItemInUserTreeSuccess_Folder_SelfOrganize_MoveFromPersonalToRoot()
    {
        // Ada is OWNER of folder A
        // Ada has READ on folder B
        // Betty is OWNER of folder B
        // ---
        // A (Ada:O)
        // |- B (Ada:R, Betty:O)
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userA], Permission::READ)
            ->withPermissionsFor([$userB])
            ->withFoldersRelationsFor([$userA, $userB], $folderA)
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderB->get('id'), FoldersRelation::ROOT);

        $this->assertItemIsInTrees($folderA->get('id'), 1);
        $this->assertFolderRelation($folderA->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertItemIsInTrees($folderB->get('id'), 2);
        $this->assertFolderRelation($folderB->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderB->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
    }

    public function testMoveItemInUserTreeError_Folder_SelfOrganize_CannotCreateCycle()
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
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA], $folderA)
            ->persist();
        $folderC = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA], $folderB)
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);

        try {
            $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderA->get('id'), $folderC->get('id'));
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertEquals('Could not validate move data.', $e->getMessage());
            $this->assertNotEmpty(Hash::get($e->getErrors(), 'folder_parent_id.cycle'));
        }
    }

    /* RESOURCE - COMMON */

    public function testMoveItemInUserTreeError_Resource_Common_CannotMoveIntoAFolderDoesNotExist()
    {
        /** @var \App\Model\Entity\User $userA */
        $userA = UserFactory::make()->user()->persist();
        $resourceA = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);

        try {
            $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_RESOURCE, $resourceA->get('id'), UuidFactory::uuid());
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertEquals('Could not validate move data.', $e->getMessage());
            $this->assertNotEmpty(Hash::get($e->getErrors(), 'folder_parent_id.folder_exists'));
        }
    }

    /* RESOURCE - PERSONAL */

    public function testMoveItemInUserTreeSuccess_Resource_Personal_MoveFromRoot()
    {
        /** @var \App\Model\Entity\User $userA */
        $userA = UserFactory::make()->user()->persist();
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        $resourceA = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_RESOURCE, $resourceA->get('id'), $folderA->get('id'));

        // Folder A
        $this->assertItemIsInTrees($folderA->get('id'), 1);
        $this->assertFolderRelation($folderA->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        // Resource
        $this->assertItemIsInTrees($resourceA->get('id'), 1);
        $this->assertFolderRelation($resourceA->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, $folderA->get('id'));
    }

    public function testMoveItemInUserTreeSuccess_Resource_Personal_MoveToRoot()
    {
        /** @var \App\Model\Entity\User $userA */
        $userA = UserFactory::make()->user()->persist();
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        $resourceA = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA], $folderA)
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_RESOURCE, $resourceA->get('id'), FoldersRelation::ROOT);

        // Folder A
        $this->assertItemIsInTrees($folderA->get('id'), 1);
        $this->assertFolderRelation($folderA->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        // Resource
        $this->assertItemIsInTrees($resourceA->get('id'), 1);
        $this->assertFolderRelation($resourceA->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, FoldersRelation::ROOT);
    }

    /* RESOURCE - SHARED */

    public function testMoveItemInUserTreeSuccess_Resource_SharedWithUser_MoveFromRoot()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // Ada is OWNER of resource R1
        // Betty is OWNER resource R1
        // ---
        // A (Ada:O, Betty:Owner)
        // R1 (Ada:O, Betty:Owner)
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();
        $resourceA = ResourceFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_RESOURCE, $resourceA->get('id'), $folderA->get('id'));

        $this->assertItemIsInTrees($folderA->get('id'), 2);
        $this->assertFolderRelation($folderA->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        $this->assertItemIsInTrees($resourceA->get('id'), 2);
        $this->assertFolderRelation($resourceA->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, $folderA->get('id'));
        $this->assertFolderRelation($resourceA->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userB->id, $folderA->get('id'));
    }

    public function testMoveItemInUserTreeSuccess_Resource_SharedWithGroup_MoveFromRoot()
    {
        // Ada is OWNER of folder A
        // G1 is OWNER of folder A
        // Ada is OWNER of resource R1
        // G1 is OWNER of resource R1
        // Betty is member of G1
        // ---
        // A (Ada:O, G1:Owner)
        // R1 (Ada:O, G1:Owner)
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $groupA = GroupFactory::make()->withGroupsManagersFor([$userB])->persist();
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA, $groupA])
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();
        $resourceA = ResourceFactory::make()
            ->withPermissionsFor([$userA, $groupA])
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_RESOURCE, $resourceA->get('id'), $folderA->get('id'));

        // Folder A
        $this->assertItemIsInTrees($folderA->get('id'), 2);
        $this->assertFolderRelation($folderA->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        // Folder B
        $this->assertItemIsInTrees($resourceA->get('id'), 2);
        $this->assertFolderRelation($resourceA->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, $folderA->get('id'));
        $this->assertFolderRelation($resourceA->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userB->id, $folderA->get('id'));
    }

    public function testMoveItemInUserTreeSuccess_Resource_SharedWithUser_MoveIntoSharedFolder_MoveToRootForUsersNotSeeingDestination()
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
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();
        $resourceA = ResourceFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA, $userB], $folderA)
            ->persist();
        $folderC = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_RESOURCE, $resourceA->get('id'), $folderC->get('id'));

        // Folder A
        $this->assertItemIsInTrees($folderA->get('id'), 2);
        $this->assertFolderRelation($folderA->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        // Resource
        $this->assertItemIsInTrees($resourceA->get('id'), 2);
        $this->assertFolderRelation($resourceA->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, $folderC->get('id'));
        $this->assertFolderRelation($resourceA->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userB->id, FoldersRelation::ROOT);
        // Folder C
        $this->assertItemIsInTrees($folderC->get('id'), 1);
        $this->assertFolderRelation($folderC->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
    }

    public function testMoveItemInUserTreeSuccess_Resource_SharedWithUser_MoveFromSharedFolderToSharedFolder_DontMoveForUsersNotSeeingOriginalAndTargetFolders()
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
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        $folderC = FolderFactory::make()
            ->withPermissionsFor([$userB])
            ->withFoldersRelationsFor([$userB])
            ->persist();
        $resourceA = ResourceFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA], $folderA)
            ->withFoldersRelationsFor([$userB], $folderC)
            ->persist();
        $folderD = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_RESOURCE, $resourceA->get('id'), $folderD->get('id'));

        // Folder A
        $this->assertItemIsInTrees($folderA->get('id'), 1);
        $this->assertFolderRelation($folderA->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        // R1
        $this->assertItemIsInTrees($resourceA->get('id'), 2);
        $this->assertFolderRelation($resourceA->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, $folderD->get('id'));
        $this->assertFolderRelation($resourceA->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userB->id, $folderC->get('id'));
        // Folder C
        $this->assertItemIsInTrees($folderC->get('id'), 1);
        $this->assertFolderRelation($folderC->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        // Folder D
        $this->assertItemIsInTrees($folderD->get('id'), 1);
        $this->assertFolderRelation($folderD->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
    }

    public function testMoveItemInUserTreeSuccess_Resource_SharedWithUser_MoveFromRootToSharedFolder_MoveToTargetFolderForUsersSeeingIt()
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
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userB])
            ->withFoldersRelationsFor([$userB])
            ->persist();
        $resourceA = ResourceFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA])
            ->withFoldersRelationsFor([$userB], $folderA)
            ->persist();
        $folderC = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_RESOURCE, $resourceA->get('id'), $folderC->get('id'));

        // Folder A
        $this->assertItemIsInTrees($folderA->get('id'), 1);
        $this->assertFolderRelation($folderA->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        // R1
        $this->assertItemIsInTrees($resourceA->get('id'), 2);
        $this->assertFolderRelation($resourceA->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, $folderC->get('id'));
        $this->assertFolderRelation($resourceA->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userB->id, $folderC->get('id'));
        // Folder C
        $this->assertItemIsInTrees($folderC->get('id'), 2);
        $this->assertFolderRelation($folderC->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderC->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
    }

    public function testMoveItemInUserTreeSuccess_Resource_SharedWithUser_MoveFromSharedFolderToRoot_MoveToRootForUsersSeeingItInTheOriginalOperatorFolder()
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
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userB])
            ->withFoldersRelationsFor([$userB])
            ->persist();
        $resourceA = ResourceFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA], $folderA) // Ada → under A
            ->withFoldersRelationsFor([$userB], $folderB) // Betty → under B
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_RESOURCE, $resourceA->get('id'), FoldersRelation::ROOT);

        // Folder A
        $this->assertItemIsInTrees($folderA->get('id'), 1);
        $this->assertFolderRelation($folderA->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        // Folder B
        $this->assertItemIsInTrees($folderB->get('id'), 1);
        $this->assertFolderRelation($folderB->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
        // R1
        $this->assertItemIsInTrees($resourceA->get('id'), 2);
        $this->assertFolderRelation($resourceA->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($resourceA->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userB->id, $folderB->get('id'));
    }

    public function testMoveItemInUserTreeError_Resource_SharedWithUser_CannotMoveIntoFolderWithInsufficientPermission()
    {
        // Ada is OWNER of resource R1
        // Ada has access READ access on folder B
        // Betty is OWNER of folder B
        // A (Ada:R, Betty:O)
        // R1 (Ada:Owner)
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA], Permission::READ)
            ->withPermissionsFor([$userB])
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();
        $resourceA = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);

        try {
            $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_RESOURCE, $resourceA->get('id'), $folderA->get('id'));
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertEquals('Could not validate move data.', $e->getMessage());
            $this->assertNotEmpty(Hash::get($e->getErrors(), 'folder_parent_id.has_folder_access'));
        }
    }

    public function testMoveItemInUserTreeError_Resource_SharedWithUser_CannotMoveOutOfFolderWithInsufficientPermission()
    {
        // Ada has access to folder A as a READ
        // Betty is OWNER of folder A
        // Ada is OWNER of resource R1
        // Betty is OWNER of resource R1
        // R1 is A
        // ---
        // A (Ada:R, Betty:O)
        // |- R1 (Ada:O, Betty:O)
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA], Permission::READ)
            ->withPermissionsFor([$userB])
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();
        $resourceA = ResourceFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA, $userB], $folderA)
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);

        try {
            $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_RESOURCE, $resourceA->get('id'), FoldersRelation::ROOT);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (CustomValidationException $e) {
            $this->assertEquals('Could not validate move data.', $e->getMessage());
            $this->assertNotEmpty(Hash::get($e->getErrors(), 'folder_parent_id.has_folder_access'));
        }
    }

    /* RESOURCE - SELF ORGANIZE */

    public function testMoveItemInUserTreeSuccess_Resource_SelfOrganize_MoveFromRootToPersonalFolder()
    {
        // Ada is OWNER of folder A
        // Ada has READ on folder B
        // Betty is OWNER of resource R1
        // ---
        // A (Ada:O)
        // R1 (Ada:R, Betty:O)
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        $resourceA = ResourceFactory::make()
            ->withPermissionsFor([$userA], Permission::READ)
            ->withPermissionsFor([$userB])
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_RESOURCE, $resourceA->get('id'), $folderA->get('id'));

        $this->assertItemIsInTrees($folderA->get('id'), 1);
        $this->assertFolderRelation($folderA->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertItemIsInTrees($resourceA->get('id'), 2);
        $this->assertFolderRelation($resourceA->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, $folderA->get('id'));
        $this->assertFolderRelation($resourceA->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userB->id, FoldersRelation::ROOT);
    }

    public function testMoveItemInUserTreeSuccess_Resource_SelfOrganize_MoveFromPersonalFolderToRoot()
    {
        // Ada is OWNER of folder A
        // Ada has READ on resource R1
        // Betty is OWNER of resource R1
        // ---
        // A (Ada:O)
        // |- R1 (Ada:R, Betty:O)
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        $resourceA = ResourceFactory::make()
            ->withPermissionsFor([$userA], Permission::READ)
            ->withPermissionsFor([$userB])
            ->withFoldersRelationsFor([$userA, $userB], $folderA)
            ->persist();
        $uac = new UserAccessControl(Role::USER, $userA->id);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_RESOURCE, $resourceA->get('id'), FoldersRelation::ROOT);

        $this->assertItemIsInTrees($folderA->get('id'), 1);
        $this->assertFolderRelation($folderA->get('id'), FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertItemIsInTrees($resourceA->get('id'), 2);
        $this->assertFolderRelation($resourceA->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($resourceA->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userB->id, FoldersRelation::ROOT);
    }

    /*
     * End-to-end regression for the cooperating-users cycle exploit.
     *
     * Setup mirrors the exploit's pre-move state:
     *   Ada's tree:   Alpha (shared) -> Bravo (personal) -> Charlie (shared)
     *   Carol's tree: Alpha at root, Charlie at root (Carol cannot see Bravo)
     *
     * Carol then moves Alpha into Charlie. Before the fix, the global SCC detector
     * skipped Bravo (1 user => "personal"), so the resulting cycle in Ada's tree
     * (Charlie -> Alpha -> Bravo -> Charlie) went undetected and her three folders
     * silently disappeared from the UI. With the fix, the detector sees the cycle
     * and the repair service breaks one edge so Ada's folders remain reachable.
     */

    public function testMoveItemInUserTreeSuccess_Folder_CycleThroughPersonalIntermediaryIsDetectedAndRepaired()
    {
        [$folderAlpha, $folderBravo, $folderCharlie, $userAdaId, $userCarolId] =
            $this->insertFixture_Folder_CycleThroughPersonalIntermediary();
        $uac = new UserAccessControl(Role::USER, $userCarolId);

        $this->service->move($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderAlpha->id, $folderCharlie->id);

        // Carol's view: the move she requested took effect.
        $this->assertFolderRelation($folderAlpha->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCarolId, $folderCharlie->id);
        $this->assertFolderRelation($folderCharlie->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userCarolId, FoldersRelation::ROOT);

        // Ada's tree must remain cycle-free. The repair service breaks exactly one
        // edge in the cycle; we don't lock down which one (it depends on relation
        // age/usage tie-breakers), but Ada's tree must not contain a closed loop.
        $sccDetector = new FoldersRelationsDetectStronglyConnectedComponentsService();
        $this->assertEmpty(
            $sccDetector->detectInUserTree($userAdaId),
            "Ada's tree still contains a cycle after the move; the repair service did not break it.",
        );

        // All three folders are still present in Ada's tree (none were deleted).
        $this->assertItemIsInTrees($folderAlpha->id, 2);
        $this->assertItemIsInTrees($folderBravo->id, 1);
        $this->assertItemIsInTrees($folderCharlie->id, 2);
    }

    private function insertFixture_Folder_CycleThroughPersonalIntermediary()
    {
        // Pre-move state (Phase 1-4 of the exploit):
        //   Alpha   (Ada:O, Carol:O) - root for both
        //   Bravo   (Ada:O)          - parent: Alpha (personal to Ada)
        //   Charlie (Ada:O, Carol:O) - parent: Bravo for Ada, root for Carol
        $userAdaId = UuidFactory::uuid('user.id.ada');
        $userCarolId = UuidFactory::uuid('user.id.carol');

        $folderAlpha = $this->addFolder(['name' => 'Alpha']);
        $folderBravo = $this->addFolder(['name' => 'Bravo']);
        $folderCharlie = $this->addFolder(['name' => 'Charlie']);

        // Alpha is shared with Ada and Carol, both at root.
        $this->addPermission('Folder', $folderAlpha->id, 'User', $userAdaId, Permission::OWNER);
        $this->addPermission('Folder', $folderAlpha->id, 'User', $userCarolId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderAlpha->id, 'user_id' => $userAdaId, 'folder_parent_id' => FoldersRelation::ROOT]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderAlpha->id, 'user_id' => $userCarolId, 'folder_parent_id' => FoldersRelation::ROOT]);

        // Bravo is personal to Ada, parent = Alpha.
        $this->addPermission('Folder', $folderBravo->id, 'User', $userAdaId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderBravo->id, 'user_id' => $userAdaId, 'folder_parent_id' => $folderAlpha->id]);

        // Charlie is shared. Ada sees it under Bravo; Carol sees it at root.
        $this->addPermission('Folder', $folderCharlie->id, 'User', $userAdaId, Permission::OWNER);
        $this->addPermission('Folder', $folderCharlie->id, 'User', $userCarolId, Permission::OWNER);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderCharlie->id, 'user_id' => $userAdaId, 'folder_parent_id' => $folderBravo->id]);
        $this->addFolderRelation(['foreign_model' => PermissionsTable::FOLDER_ACO, 'foreign_id' => $folderCharlie->id, 'user_id' => $userCarolId, 'folder_parent_id' => FoldersRelation::ROOT]);

        return [$folderAlpha, $folderBravo, $folderCharlie, $userAdaId, $userCarolId];
    }
}
