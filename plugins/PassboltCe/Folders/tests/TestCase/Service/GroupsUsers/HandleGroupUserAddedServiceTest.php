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
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Service\GroupsUsers\HandleGroupUserAddedService;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Folders\Test\Factory\ResourceFactory;
use Passbolt\Folders\Test\Lib\FoldersTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

/**
 * \Passbolt\Folders\Service\Groups\HandleGroupUserAddedServiceTest Test Case
 *
 * Test that after a user is added to a group, the user's folders tree is reconstructed.
 * Only simple tests with resources and folders are tested here.
 * Complex scenarios can be found in the FoldersRelationsAddItemFromUserTreeServiceTest.
 *
 * @uses \Passbolt\Folders\Service\GroupsUsers\HandleGroupUserAddedService
 */
class HandleGroupUserAddedServiceTest extends FoldersTestCase
{
    use FoldersRelationsModelTrait;

    /**
     * @var HandleGroupUserAddedService
     */
    private $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new HandleGroupUserAddedService();
    }

    public function testGroupsAfterUserAddedSuccess_AddResourceToUserTree()
    {
        // Ada is OWNER of resource R1
        // G1 is OWNER of resource R1
        // Ada is OWNER of resource R2
        // G1 is OWNER of resource R2
        // Ada is group manager of G1
        // ----
        // R1 (Ada:O, G1:O)
        // R2 (Ada:O, G1:O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \App\Model\Entity\Group $g1 */
        $g1 = GroupFactory::make()->withGroupsManagersFor([$userA, $userB])->persist();
        $userBGroupUser = $g1->groups_users[1];
        [$r1,$r2] = ResourceFactory::make(2)
            ->withFoldersRelationsFor([$userA])
            ->withPermissionsFor([$userA, $g1])
            ->withSecretsFor([$userA, $g1])
            ->persist();

        $this->service->handle($this->makeUac($userA), $userBGroupUser);

        $this->assertItemIsInTrees($r1->id, 2);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, null);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userB->id, null);
        $this->assertItemIsInTrees($r2->id, 2);
        $this->assertFolderRelation($r2->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, null);
        $this->assertFolderRelation($r2->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userB->id, null);
    }

    public function testGroupsAfterUserAddedSuccess_ResourceWasAlreadyInUserTree()
    {
        // Ada is OWNER of resource R1
        // G1 is OWNER of resource R1
        // Betty is OWNER of resource R2
        // G1 is OWNER of resource R2
        // Ada is group manager of G1
        // ----
        // R1 (Ada:O, G1:O)
        // R2 (Betty:O, G1:O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \App\Model\Entity\Group $g1 */
        $g1 = GroupFactory::make()->withGroupsManagersFor([$userA, $userB])->persist();
        $userBGroupUser = $g1->groups_users[1];
        /** @var \App\Model\Entity\Resource $r1 */
        $r1 = ResourceFactory::make()
            ->withFoldersRelationsFor([$userA])
            ->withPermissionsFor([$userA, $g1])
            ->withSecretsFor([$userA, $g1])
            ->persist();
        /** @var \App\Model\Entity\Resource $r2 */
        $r2 = ResourceFactory::make()
            ->withFoldersRelationsFor([$userA, $userB])
            ->withPermissionsFor([$userB, $g1])
            ->withSecretsFor([$userB, $g1])
            ->persist();

        $this->service->handle($this->makeUac($userA), $userBGroupUser);

        $this->assertItemIsInTrees($r1->id, 2);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, null);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userB->id, null);
        $this->assertItemIsInTrees($r2->id, 2);
        $this->assertFolderRelation($r2->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, null);
        $this->assertFolderRelation($r2->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userB->id, null);
    }

    public function testGroupsAfterUserAddedSuccess_AddFolderToUserTree()
    {
        // Ada is OWNER of folder A
        // G1 is OWNER of folder A
        // Ada is OWNER of folder B
        // G1 is OWNER of folder B
        // Ada is group manager of G1
        // ----
        // A (Ada:O, G1:O)
        // B (Ada:O, G1:O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \App\Model\Entity\Group $group */
        $group = GroupFactory::make()->withGroupsManagersFor([$userA, $userB])->persist();
        $userBGroupUser = $group->groups_users[1];
        [$folderA, $folderB] = FolderFactory::make(2)
            ->withPermissionsFor([$userA, $group])
            ->withFoldersRelationsFor([$userA])
            ->persist();

        $uac = $this->makeUac($userA);

        $this->service->handle($uac, $userBGroupUser);

        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, null);
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, null);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, null);
    }

    public function testGroupsAfterUserAddedSuccess_FolderWasAlreadyInUserTree()
    {
        // Ada is OWNER of folder A
        // G1 is OWNER of folder A
        // Betty is OWNER of folder B
        // G1 is OWNER of folder B
        // Ada is group manager of G1
        // ----
        // A (Ada:O, G1:O)
        // B (Betty:O, G1:O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \App\Model\Entity\Group $group */
        $group = GroupFactory::make()->withGroupsManagersFor([$userA, $userB])->persist();
        $userBGroupUser = $group->groups_users[1];
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA, $group])
            ->withFoldersRelationsFor([$userA])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderB */
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userB, $group])
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();

        $uac = $this->makeUac($userA);

        $this->service->handle($uac, $userBGroupUser);

        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, null);
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, null);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, null);
    }
}
