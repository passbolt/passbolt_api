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

namespace Passbolt\Folders\Test\TestCase\Service\GroupsUsers;

use App\Test\Factory\GroupFactory;
use App\Test\Factory\UserFactory;
use Cake\ORM\TableRegistry;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Service\GroupsUsers\HandleGroupUserDeletedService;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Folders\Test\Factory\ResourceFactory;
use Passbolt\Folders\Test\Lib\FoldersTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;

/**
 * Passbolt\Folders\Service\Groups\HandleGroupUserDeletedServices Test Case
 *
 * @uses \Passbolt\Folders\Service\GroupsUsers\HandleGroupUserDeletedService
 */
class HandleGroupUserDeletedServiceTest extends FoldersTestCase
{
    use FoldersModelTrait;

    /**
     * @var \App\Model\Table\GroupsUsersTable
     */
    private $groupsUsersTable;

    /**
     * @var HandleGroupUserDeletedService
     */
    private $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->groupsUsersTable = TableRegistry::getTableLocator()->get('GroupsUsers');
        $this->service = new HandleGroupUserDeletedService();
    }

    public function testGroupsAfterUserRemovedSuccess_RemoveResourceFromUserTree()
    {
        // Betty is OWNER of resource R1
        // G1 is OWNER of resource R1
        // Betty is OWNER of resource R2
        // G1 is OWNER of resource R2
        // Ada is group manager of G1
        // Betty is group manager of G1
        // ----
        // R1 (Betty:O, G1:O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \App\Model\Entity\Group $g1 */
        $g1 = GroupFactory::make()->withGroupsManagersFor([$userA, $userB])->persist();
        [$r1, $r2] = ResourceFactory::make(2)
            ->withPermissionsFor([$userA, $g1])
            ->withSecretsFor([$userA, $g1])
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();

        // Prepare the test by deleting the group user entry
        $userBGroupUser = $g1->groups_users[1];
        $this->groupsUsersTable->delete($userBGroupUser);

        $this->service->handle($userBGroupUser);

        $this->assertItemIsInTrees($r1->id, 1);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, null);
        $this->assertItemIsInTrees($r2->id, 1);
        $this->assertFolderRelation($r2->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, null);
    }

    public function testGroupsAfterUserRemovedSuccess_KeepResourceInTreeWhenUserHasAnotherAccess()
    {
        // Ada is OWNER of resource R1
        // G1 is OWNER of resource R1
        // Ada is group manager of G1
        // Betty is group manager of G1
        // ----
        // R1 (Ada:O, G1:O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \App\Model\Entity\Group $g1 */
        $g1 = GroupFactory::make()->withGroupsManagersFor([$userA, $userB])->persist();
        [$r1, $r2] = ResourceFactory::make(2)
            ->withPermissionsFor([$userB, $g1])
            ->withSecretsFor([$userB, $g1])
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();

        // Prepare the test by deleting the group user entry
        $userBGroupUser = $g1->groups_users[1];
        $this->groupsUsersTable->delete($userBGroupUser);

        $this->service->handle($userBGroupUser);

        $this->assertItemIsInTrees($r1->get('id'), 2);
        $this->assertFolderRelation($r1->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, null);
        $this->assertFolderRelation($r1->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userB->id, null);
        $this->assertItemIsInTrees($r2->get('id'), 2);
        $this->assertFolderRelation($r2->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, null);
        $this->assertFolderRelation($r2->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userB->id, null);
    }

    public function testGroupsAfterUserRemovedSuccess_RemoveFolderFromUserTree()
    {
        // Ada is OWNER of folder A
        // G1 is OWNER of folder A
        // Ada is OWNER of folder B 
        // G1 is OWNER of folder B
        // Ada is group manager of G1
        // Betty is group manager of G1
        // ----
        // A (Ada:O, G1:O)
        // B (Ada:O, G1:O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \App\Model\Entity\Group $g1 */
        $g1 = GroupFactory::make()->withGroupsManagersFor([$userA, $userB])->persist();
        [$folderA, $folderB] = FolderFactory::make(2)
            ->withPermissionsFor([$userA, $g1])
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();

        // Prepare the test by deleting the group user entry
        $userBGroupUser = $g1->groups_users[1];
        $this->groupsUsersTable->delete($userBGroupUser);

        $this->service->handle($userBGroupUser);

        $this->assertItemIsInTrees($folderA->id, 1);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, null);
        $this->assertItemIsInTrees($folderB->id, 1);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, null);
    }

    public function testGroupsAfterUserRemovedSuccess_RemoveFolderFromUserTreeMoveContentToRoot()
    {
        // Ada is OWNER of folder A
        // G1 is OWNER of folder A
        // Betty is OWNER of folder B 
        // G1 is OWNER of folder B
        // Folder B is in folder A
        // Ada is group manager of G1
        // Betty is group manager of G1
        // ----
        // A (Ada:O, G1:O)
        // B (Ada:O, G1:O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \App\Model\Entity\Group $g1 */
        $g1 = GroupFactory::make()->withGroupsManagersFor([$userA, $userB])->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA, $g1])
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderB */
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userB, $g1])
            ->withFoldersRelationsFor([$userA, $userB], $folderA)
            ->persist();

        // Prepare the test by deleting the group user entry
        $userBGroupUser = $g1->groups_users[1];
        $this->groupsUsersTable->delete($userBGroupUser);

        $this->service->handle($userBGroupUser);

        $this->assertItemIsInTrees($folderA->id, 1);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, null);
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, null);
    }

    public function testGroupsAfterUserRemovedSuccess_KeepFolderInTreeWhenUserHasAnotherAccess()
    {
        // Betty is OWNER of folder A
        // G1 is OWNER of folder A
        // Betty is OWNER of folder B 
        // G1 is OWNER of folder B
        // Ada is group manager of G1
        // Betty is group manager of G1
        // ----
        // A (Betty:O, G1:O)
        // B (Betty:O, G1:O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \App\Model\Entity\Group $g1 */
        $g1 = GroupFactory::make()->withGroupsManagersFor([$userA, $userB])->persist();
        [$folderA, $folderB] = FolderFactory::make(2)
            ->withPermissionsFor([$userB, $g1])
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();

        // Prepare the test by deleting the group user entry
        $userBGroupUser = $g1->groups_users[1];
        $this->groupsUsersTable->delete($userBGroupUser);

        $this->service->handle($userBGroupUser);

        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, null);
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, null);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, null);
    }
}
