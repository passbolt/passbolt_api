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

namespace Passbolt\Folders\Test\TestCase\Service\Folders;

use App\Model\Entity\Permission;
use App\Notification\Email\EmailSubscriptionDispatcher;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Utility\UuidFactory;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Service\Folders\FoldersDeleteService;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Folders\Test\Factory\FoldersRelationFactory;
use Passbolt\Folders\Test\Factory\PermissionFactory;
use Passbolt\Folders\Test\Factory\ResourceFactory;
use Passbolt\Folders\Test\Lib\FoldersTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

/**
 * Passbolt\Folders\Service\FoldersDeleteService Test Case
 *
 * @uses \Passbolt\Folders\Service\Folders\FoldersDeleteService
 * @property \App\Model\Table\ResourcesTable $Resources
 */
class FoldersDeleteServiceTest extends FoldersTestCase
{
    use EmailNotificationSettingsTestTrait;
    use EmailQueueTrait;
    use FoldersModelTrait;

    /**
     * @var FoldersDeleteService
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

        $this->loadNotificationSettings();
        (new EmailSubscriptionDispatcher())->collectSubscribedEmailRedactors();

        $this->service = new FoldersDeleteService();
    }

    public function tearDown(): void
    {
        $this->unloadNotificationSettings();
        unset($this->service);
        parent::tearDown();
    }

    /* COMMON & VALIDATION */

    public function testFolderDelete_CommonError1_FolderNotExist()
    {
        $user = UserFactory::make()->persist();

        $this->expectException(NotFoundException::class);
        $this->service->delete($this->makeUac($user), UuidFactory::uuid());
    }

    public function testFolderDelete_CommonError2_NoAccess()
    {
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folder */
        $folder = FolderFactory::make()->withPermissionsFor([$userB], Permission::READ)->persist();

        $this->expectException(ForbiddenException::class);
        $this->service->delete($this->makeUac($userA), $folder->id);
    }

    public function testFolderDelete_CommonSuccess2_NotifyUsersAfterDelete()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // ---
        // A (Ada:O, Betty:O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make(['name' => 'A'])->withPermissionsFor([$userA, $userB])->persist();

        $this->service->delete($this->makeUac($userA), $folderA->id);

        $this->assertEmailQueueCount(2);
        $this->assertEmailSubject($userA->username, 'You deleted the folder A');
        $this->assertEmailInBatchContains('You deleted a folder', $userA->username);
        $this->assertEmailSubject($userB->username, "{$userA->profile->first_name} deleted the folder A");
        $this->assertEmailInBatchContains("{$userA->profile->first_name} deleted a folder", $userB->username);
    }

    /* PERSONAL FOLDER */

    public function testFolderDelete_PersoSuccess1_DeleteFolder()
    {
        // Ada has access to folder A as a OWNER
        // ---
        // A (Ada:O)
        $userA = UserFactory::make()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()->withPermissionsFor([$userA])->persist();

        $this->service->delete($this->makeUac($userA), $folderA->id);
        $this->assertFolderNotExist($folderA->id);
    }

    public function testFolderDelete_PersoSuccess2_NoCascadeMoveChildrenToRoot()
    {
        /** @var \App\Model\Entity\User $userA */
        $userA = UserFactory::make()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()->withPermissionsFor([$userA])->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderB */
        $folderB = FolderFactory::make()->withPermissionsFor([$userA])->withFoldersRelationsFor([$userA], $folderA)->persist();

        $this->service->delete($this->makeUac($userA), $folderA->id, false);
        $this->assertFolderNotExist($folderA->id);
        $this->assertFolder($folderB->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, null);
    }

    public function testFolderDelete_PersoSuccess3_CascadeDelete()
    {
        // Ada is OWNER of folder A
        // Ada is OWNER of resource R1
        // Resource R1 is in folder A
        // Ada is OWNER of folder B
        // Folder B is in folder A
        // Ada is OWNER of resource R2
        // Resource R2 is in folder B
        // ---
        // A (Ada:O)
        // |- R1 (Ada:O)
        // |- B (Ada:O)
        //    |- R2 (Ada:O)
        $userA = UserFactory::make()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()->withPermissionsFor([$userA])->persist();
        /** @var \App\Model\Entity\Resource $resource1 */
        $resource1 = ResourceFactory::make()->withFoldersRelationsFor([$userA], $folderA)->withPermissionsFor([$userA])->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderB */
        $folderB = FolderFactory::make()->withPermissionsFor([$userA])->withFoldersRelationsFor([$userA], $folderA)->persist();
        /** @var \App\Model\Entity\Resource $resource2 */
        $resource2 = ResourceFactory::make()->withFoldersRelationsFor([$userA], $folderB)->withPermissionsFor([$userA])->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderC */
        $folderC = FolderFactory::make(['name' => 'A.F1.F1'])->withPermissionsFor([$userA])->withFoldersRelationsFor([$userA], $folderB)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderD */
        $folderD = FolderFactory::make(['name' => 'A.F1.F1.F1'])->withPermissionsFor([$userA])->withFoldersRelationsFor([$userA], $folderC)->persist();
        /** @var \App\Model\Entity\Resource $resource3 */
        $resource3 = ResourceFactory::make(['name' => 'A.F1.F1.R1'])->withFoldersRelationsFor([$userA], $folderC)->withPermissionsFor([$userA])->persist();
        /** @var \App\Model\Entity\Resource $resource4 */
        $resource4 = ResourceFactory::make(['name' => 'A.F1.F1.R2'])->withFoldersRelationsFor([$userA], $folderC)->withPermissionsFor([$userA])->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderE */
        $folderE = FolderFactory::make(['name' => 'A.F1.F1.F1.F1'])->withPermissionsFor([$userA])->withFoldersRelationsFor([$userA], $folderD)->persist();

        $this->service->delete($this->makeUac($userA), $folderA->id, true);
        $this->assertFolderNotExist($folderA->id);
        $this->assertResourceIsSoftDeleted($resource1->id);
        $this->assertFolderNotExist($folderB->id);
        $this->assertResourceIsSoftDeleted($resource2->id);
        $this->assertFolderNotExist($folderC->id);
        $this->assertFolderNotExist($folderD->id);
        $this->assertFolderNotExist($folderE->id);
        $this->assertResourceIsSoftDeleted($resource3->id);
        $this->assertResourceIsSoftDeleted($resource4->id);
    }

    public function testFolderDelete_PersoSuccess4_CascadeDeleteContentMoveToRootWhenInsufficientPermission()
    {
        // Ada is OWNER of folder A
        // Ada has read ACCESS on resource R1
        // Betty is OWNER of resource R1
        // Resource R1 is in folder A
        // ---
        //  A (Ada:O)
        // |- R1 (Ada:R, Betty:O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()->withPermissionsFor([$userA])->persist();
        /** @var \App\Model\Entity\Resource $resource1 */
        $resource1 = ResourceFactory::make()
            ->withFoldersRelationsFor([$userA, $userB], $folderA)
            ->withPermissionsFor([$userA], Permission::READ)
            ->withPermissionsFor([$userB])
            ->persist();

        $this->service->delete($this->makeUac($userA), $folderA->id, true);

        $this->assertFolderNotExist($folderA->id);
        $this->assertResourceIsNotSoftDeleted($resource1->id);
        $this->assertPermission($resource1->id, $userA->id, Permission::READ);
        $this->assertFolderRelation($resource1->id, 'Resource', $userA->id, null);
    }

    /* SHARED FOLDER */

    public function testFolderDelete_SharedError1_InsufficientPermission()
    {
        // Ada has access to folder A as a READ
        // Betty is OWNER of folder A
        // A (Ada:R, Betty:O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()->withPermissionsFor([$userA], Permission::READ)->withPermissionsFor([$userB])->persist();

        $this->expectException(ForbiddenException::class);
        $this->service->delete($this->makeUac($userA), $folderA->id, true);
    }

    public function testFolderDelete_SharedSuccess1_DeleteSharedFolder()
    {
        // Ada is OWNER of folder A
        // Betty has access to folder A as a READ
        // Carol is manager of group 1
        // Group 1 has access to folder A as a READ
        //
        // G1 (Carol:O)
        // A (Ada:O, Betty:R, G1:R)
        [$userA, $userB, $userC] = UserFactory::make(3)->persist();
        $g1 = GroupFactory::make()->withGroupsManagersFor([$userC])->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withPermissionsFor([$userB, $g1], Permission::READ)
            ->persist();

        $this->service->delete($this->makeUac($userA), $folderA->id, true);
        $this->assertFolderNotExist($folderA->id);
    }

    public function testFolderDelete_SharedSuccess2_NoCascadeMoveChildrenToRoot()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // Ada is OWNER of folder B
        // Betty is OWNER of folder B
        // Folder B is in A
        // ---
        // A (Ada:O, Betty:O)
        // |
        // B (Ada:O, Betty:O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()->withPermissionsFor([$userA, $userB])->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderB */
        $folderB = FolderFactory::make()->withPermissionsFor([$userA, $userB])->withFoldersRelationsFor([$userA, $userB], $folderA)->persist();

        $this->service->delete($this->makeUac($userA), $folderA->id, false);
        $this->assertFolderNotExist($folderA->id);
        $this->assertFolder($folderB->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, null);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, null);
    }

    public function testFolderDelete_SharedSuccess3_CascadeDelete()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // Ada is OWNER of resource R1
        // Betty is OWNER of resource R1
        // Resource R1 is in folder A
        // Ada is OWNER of folder B
        // Betty is OWNER of folder B
        // Folder B is in folder A
        // Ada is OWNER of resource R2
        // Betty is OWNER of resource R2
        // Resource R2 is in folder B
        // ---
        // A (Ada:O, Betty:O)
        // |- R1 (Ada:O, Betty:O)
        // |- B (Ada:O, Betty:O)
        //    |- R2 (Ada:O, Betty:O)
        [$userA, $userB, $userC, $userD] = UserFactory::make(4)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make(['name' => 'A'])->withPermissionsFor([$userA, $userB])->persist();
        /** @var \App\Model\Entity\Resource $resource1 */
        $resource1 = ResourceFactory::make(['name' => 'R1'])->withFoldersRelationsFor([$userA, $userB], $folderA)->withPermissionsFor([$userA, $userB])->persist();
        /** @var \App\Model\Entity\Group $group */
        $group = GroupFactory::make()->withGroupsManagersFor([$userC])->withGroupsUsersFor([$userD])->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderB */
        $folderB = FolderFactory::make(['name' => 'B'])->withPermissionsFor([$userA, $userB, $group])->withFoldersRelationsFor([$userA, $userB, $group], $folderA)->persist();
        /** @var \App\Model\Entity\Resource $resource2 */
        $resource2 = ResourceFactory::make(['name' => 'R2'])->withFoldersRelationsFor([$userA, $userB], $folderB)->withPermissionsFor([$userA, $userB])->persist();

        $this->service->delete($this->makeUac($userA), $folderA->id, true);
        $this->assertFolderNotExist($folderA->id);
        $this->assertResourceIsSoftDeleted($resource1->id);
        $this->assertFolderNotExist($folderB->id);
        $this->assertResourceIsSoftDeleted($resource2->id);
    }

    public function testFolderDelete_SharedGroupUserSuccess4_CascadeDelete()
    {
        [$userA, $userB, $userC, $userD] = UserFactory::make(4)->persist();
        /** @var \App\Model\Entity\Group $group */
        $group = GroupFactory::make()->withGroupsManagersFor([$userC])->withGroupsUsersFor([$userD])->persist();
        $permissions = [$userA, $userB, $group];
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make(['name' => 'A'])->withPermissionsFor($permissions)->persist();
        /** @var \App\Model\Entity\Resource $resource1 */
        $resource1 = ResourceFactory::make(['name' => 'R1'])->withFoldersRelationsFor($permissions, $folderA)->withPermissionsFor($permissions)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderB */
        $folderB = FolderFactory::make(['name' => 'B'])->withPermissionsFor($permissions)->withFoldersRelationsFor($permissions, $folderA)->persist();
        /** @var \App\Model\Entity\Resource $resource2 */
        $resource2 = ResourceFactory::make(['name' => 'R2'])->withFoldersRelationsFor($permissions, $folderB)->withPermissionsFor($permissions)->persist();

        // Perform action with group manager
        $this->service->delete($this->makeUac($userC), $folderA->id, true);

        $this->assertFolderNotExist($folderA->id);
        $this->assertResourceIsSoftDeleted($resource1->id);
        $this->assertFolderNotExist($folderB->id);
        $this->assertResourceIsSoftDeleted($resource2->id);
    }

    public function testFolderDelete_SharedSuccess4_CascadeDeleteContentMoveToRootWhenInsufficientPermission()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // Ada has read ACCESS on resource R1
        // Betty is OWNER of resource R1
        // Resource R1 is in folder A
        // Ada has read ACCESS on folderB
        // Betty is OWNER of folder B
        // Folder B is in folder A
        // Betty is OWNER of folder C
        // Folder C is in folder A
        // Betty is OWNER of resource R2
        // Resource R2 is in folder A
        // ---
        // A (Ada:R, Betty:O)
        // |- R1 (Ada:R, Betty:O)
        // |- B (Ada:R, Betty:O)
        // |- R2 (Betty:O)
        // |- C (Betty:O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()->withPermissionsFor([$userA, $userB])->persist();
        /** @var \App\Model\Entity\Resource $resource1 */
        $resource1 = ResourceFactory::make()
            ->withFoldersRelationsFor([$userA, $userB], $folderA)
            ->withPermissionsFor([$userA], Permission::READ)
            ->withPermissionsFor([$userB])
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderB */
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userA], Permission::READ)
            ->withPermissionsFor([$userB])
            ->withFoldersRelationsFor([$userA, $userB], $folderA)
            ->persist();
        /** @var \App\Model\Entity\Resource $resource2 */
        $resource2 = ResourceFactory::make()->withFoldersRelationsFor([$userB], $folderA)->withPermissionsFor([$userB])->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderC */
        $folderC = FolderFactory::make()->withPermissionsFor([$userB])->withFoldersRelationsFor([$userB], $folderA)->persist();

        $this->service->delete($this->makeUac($userA), $folderA->id, true);
        $this->assertFolderNotExist($folderA->id);
        $this->assertResourceIsNotSoftDeleted($resource1->id);
        $this->assertPermission($resource1->id, $userA->id, Permission::READ);
        $this->assertPermission($resource1->id, $userB->id, Permission::OWNER);
        $this->assertFolderRelation($resource1->id, 'Resource', $userA->id, null);
        $this->assertFolderRelation($resource1->id, 'Resource', $userB->id, null);
        $this->assertFolder($folderB->id);
        $this->assertPermission($folderB->id, $userA->id, Permission::READ);
        $this->assertPermission($folderB->id, $userB->id, Permission::OWNER);
        $this->assertFolderRelation($folderB->id, 'Folder', $userA->id, null);
        $this->assertFolderRelation($folderB->id, 'Folder', $userB->id, null);
        $this->assertResourceIsNotSoftDeleted($resource2->id);
        $this->assertPermission($resource2->id, $userB->id, Permission::OWNER);
        $this->assertFolderRelation($resource2->id, 'Resource', $userB->id, null);
        $this->assertFolder($folderC->id);
        $this->assertPermission($folderC->id, $userB->id, Permission::OWNER);
        $this->assertFolderRelation($folderC->id, 'Folder', $userB->id, null);
    }

    public function testFolderDelete_SharedSuccess5_CascadeDeleteNestedMixedOwnership()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // Ada is OWNER of folder B
        // Betty is OWNER of folder B
        // Folder B is in folder A
        // Ada has READ on folder C
        // Betty is OWNER of folder C
        // Folder C is in folder B
        // Ada has READ on resource R1
        // Betty is OWNER of resource R1
        // Resource R1 is in folder C
        // ---
        // A (Ada:O, Betty:O)
        // |- B (Ada:O, Betty:O)
        //    |- C (Ada:R, Betty:O)
        //       |- R1 (Ada:R, Betty:O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()->withPermissionsFor([$userA, $userB])->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderB */
        $folderB = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA, $userB], $folderA)
            ->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderC */
        $folderC = FolderFactory::make()
            ->withPermissionsFor([$userA], Permission::READ)
            ->withPermissionsFor([$userB])
            ->withFoldersRelationsFor([$userA, $userB], $folderB)
            ->persist();
        /** @var \App\Model\Entity\Resource $resource1 */
        $resource1 = ResourceFactory::make()
            ->withFoldersRelationsFor([$userA, $userB], $folderC)
            ->withPermissionsFor([$userA], Permission::READ)
            ->withPermissionsFor([$userB])
            ->persist();

        $this->service->delete($this->makeUac($userA), $folderA->id, true);

        // Folder A and B are deleted (Ada has OWNER)
        $this->assertFolderNotExist($folderA->id);
        $this->assertFolderNotExist($folderB->id);
        // Folder C is NOT deleted (Ada only has READ) and moves to root for Betty
        $this->assertFolder($folderC->id);
        $this->assertPermission($folderC->id, $userA->id, Permission::READ);
        $this->assertPermission($folderC->id, $userB->id, Permission::OWNER);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, null);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, null);
        // Resource R1 is NOT deleted and stays under C
        $this->assertResourceIsNotSoftDeleted($resource1->id);
        $this->assertPermission($resource1->id, $userA->id, Permission::READ);
        $this->assertPermission($resource1->id, $userB->id, Permission::OWNER);
        $this->assertFolderRelation($resource1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, $folderC->id);
        $this->assertFolderRelation($resource1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userB->id, $folderC->id);
    }

    public function testFolderDelete_PersoSuccess5_CascadeDeleteResourceSharedWithUsersOutsideFolder()
    {
        // Ada is OWNER of folder A
        // Ada is OWNER of resource R1
        // Carol is OWNER of resource R1
        // Resource R1 is in folder A for Ada
        // Carol has R1 at root (not inside folder A)
        // ---
        // Ada's tree:
        // A (Ada:O)
        // |- R1 (Ada:O, Carol:O)
        //
        // Carol's tree:
        // R1 (Ada:O, Carol:O)
        [$userA, $userC] = UserFactory::make(2)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()->withPermissionsFor([$userA])->persist();
        /** @var \App\Model\Entity\Resource $resource1 */
        $resource1 = ResourceFactory::make()
            ->withFoldersRelationsFor([$userA], $folderA)
            ->withFoldersRelationsFor([$userC])
            ->withPermissionsFor([$userA, $userC])
            ->persist();

        $this->service->delete($this->makeUac($userA), $folderA->id, true);

        // Folder A is deleted
        $this->assertFolderNotExist($folderA->id);
        // Resource R1 is soft-deleted (Ada has OWNER)
        $this->assertResourceIsSoftDeleted($resource1->id);
    }

    public function testFolderDelete_PersoSuccess6_CascadeDeleteResourceWithGroupUpdatePermission()
    {
        // Ada is OWNER of folder A
        // Ada is member of Group1
        // Group1 has UPDATE on resource R1
        // Resource R1 is in folder A
        // ---
        // A (Ada:O)
        // |- R1 (G1:U)
        $userA = UserFactory::make()->persist();
        /** @var \App\Model\Entity\Group $group */
        $group = GroupFactory::make()->withGroupsManagersFor([$userA])->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()->withPermissionsFor([$userA])->persist();
        /** @var \App\Model\Entity\Resource $resource1 */
        $resource1 = ResourceFactory::make()
            ->withFoldersRelationsFor([$userA], $folderA)
            ->withPermissionsFor([$group], Permission::UPDATE)
            ->persist();

        $this->service->delete($this->makeUac($userA), $folderA->id, true);

        // Folder A is deleted
        $this->assertFolderNotExist($folderA->id);
        // Resource R1 is soft-deleted (UPDATE permission via group is sufficient)
        $this->assertResourceIsSoftDeleted($resource1->id);
    }

    public function testFolderDelete_PersoSuccess7_NoCascadeMoveResourcesToRoot()
    {
        // Ada is OWNER of folder A
        // Folder A contains resources R1, R2, R3 but no sub-folders
        // ---
        // A (Ada:O)
        // |- R1 (Ada:O)
        // |- R2 (Ada:O)
        // |- R3 (Ada:O)
        /** @var \App\Model\Entity\User $userA */
        $userA = UserFactory::make()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()->withPermissionsFor([$userA])->persist();
        /** @var \App\Model\Entity\Resource $resource1 */
        $resource1 = ResourceFactory::make()->withFoldersRelationsFor([$userA], $folderA)->withPermissionsFor([$userA])->persist();
        /** @var \App\Model\Entity\Resource $resource2 */
        $resource2 = ResourceFactory::make()->withFoldersRelationsFor([$userA], $folderA)->withPermissionsFor([$userA])->persist();
        /** @var \App\Model\Entity\Resource $resource3 */
        $resource3 = ResourceFactory::make()->withFoldersRelationsFor([$userA], $folderA)->withPermissionsFor([$userA])->persist();

        $this->service->delete($this->makeUac($userA), $folderA->id, false);

        // Folder A is deleted
        $this->assertFolderNotExist($folderA->id);
        // Resources are NOT deleted, they move to root
        $this->assertResourceIsNotSoftDeleted($resource1->id);
        $this->assertResourceIsNotSoftDeleted($resource2->id);
        $this->assertResourceIsNotSoftDeleted($resource3->id);
        $this->assertFolderRelation($resource1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, null);
        $this->assertFolderRelation($resource2->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, null);
        $this->assertFolderRelation($resource3->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, null);
    }

    public function testFolderDelete_PersoSuccess8_CascadeDeleteEmptyFolder()
    {
        // Ada is OWNER of empty folder A
        // ---
        // A (Ada:O)
        $userA = UserFactory::make()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()->withPermissionsFor([$userA])->persist();

        $this->service->delete($this->makeUac($userA), $folderA->id, true);

        // Folder A is deleted
        $this->assertFolderNotExist($folderA->id);
    }

    public function testFolderDelete_CommonSuccess3_NotifyGroupMembersAfterDelete()
    {
        // Ada is OWNER of folder A
        // Group1 has READ on folder A
        // Betty and Carol are members of Group1
        // ---
        // G1 (Betty:M, Carol:U)
        // A (Ada:O, G1:R)
        [$userA, $userB, $userC] = UserFactory::make(3)->persist();
        /** @var \App\Model\Entity\Group $group */
        $group = GroupFactory::make()->withGroupsManagersFor([$userB])->withGroupsUsersFor([$userC])->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make(['name' => 'A'])
            ->withPermissionsFor([$userA])
            ->withPermissionsFor([$group], Permission::READ)
            ->persist();

        $this->service->delete($this->makeUac($userA), $folderA->id);

        // All users with access (direct and via group) should be notified
        $this->assertEmailQueueCount(3);
        $this->assertEmailSubject($userA->username, 'You deleted the folder A');
        $this->assertEmailSubject($userB->username, "{$userA->profile->first_name} deleted the folder A");
        $this->assertEmailSubject($userC->username, "{$userA->profile->first_name} deleted the folder A");
    }

    public function testFolderDelete_SharedSuccess6_DeleteFolderWithUpdatePermissionViaGroup()
    {
        // Ada is member of Group1
        // Group1 has UPDATE on folder A
        // Betty is OWNER of folder A
        // ---
        // G1 (Ada:M)
        // A (G1:U, Betty:O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \App\Model\Entity\Group $group */
        $group = GroupFactory::make()->withGroupsManagersFor([$userA])->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$group], Permission::UPDATE)
            ->withPermissionsFor([$userB])
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();

        $this->service->delete($this->makeUac($userA), $folderA->id);

        // Folder A is deleted
        $this->assertFolderNotExist($folderA->id);
    }

    public function testFolderDelete_PersoSuccess9_DeleteFolderWithConcurrentlyAddedRelation()
    {
        // Ada is OWNER of folder A
        // Betty has been given access to folder A concurrently (relation exists)
        // ---
        // A (Ada:O, Betty:R)
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withPermissionsFor([$userB], Permission::READ)
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();

        // Simulate that a new user was concurrently added
        $userC = UserFactory::make()->persist();
        FoldersRelationFactory::make()
            ->foreignModelFolder($folderA)
            ->user($userC)
            ->root()
            ->persist();

        $this->service->delete($this->makeUac($userA), $folderA->id);

        // Folder A is deleted, all relations cleaned up including the concurrently added one
        $this->assertFolderNotExist($folderA->id);
    }

    public function testFolderDelete_SharedSuccess7_DeleteFolderWithDirectUpdatePermission()
    {
        // Ada has direct UPDATE permission on folder A
        // Betty is OWNER of folder A
        // ---
        // A (Ada:U, Betty:O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA], Permission::UPDATE)
            ->withPermissionsFor([$userB])
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();

        $this->service->delete($this->makeUac($userA), $folderA->id);

        $this->assertFolderNotExist($folderA->id);
    }

    public function testFolderDelete_SharedError2_InsufficientPermissionViaGroup()
    {
        // Ada is member of Group1
        // Group1 has READ on folder A
        // Betty is OWNER of folder A
        // Ada has no direct permission on folder A
        // ---
        // G1 (Ada:M)
        // A (G1:R, Betty:O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \App\Model\Entity\Group $group */
        $group = GroupFactory::make()->withGroupsManagersFor([$userA])->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$group], Permission::READ)
            ->withPermissionsFor([$userB])
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();

        $this->expectException(ForbiddenException::class);
        $this->service->delete($this->makeUac($userA), $folderA->id);
    }

    public function testFolderDelete_PersoSuccess10_NoCascadeMovesMixedChildrenToRoot()
    {
        // Ada is OWNER of folder A
        // Folder A contains folder B, resource R1, and resource R2
        // ---
        // A (Ada:O)
        // |- B (Ada:O)
        // |- R1 (Ada:O)
        // |- R2 (Ada:O)
        /** @var \App\Model\Entity\User $userA */
        $userA = UserFactory::make()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()->withPermissionsFor([$userA])->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderB */
        $folderB = FolderFactory::make()->withPermissionsFor([$userA])->withFoldersRelationsFor([$userA], $folderA)->persist();
        /** @var \App\Model\Entity\Resource $resource1 */
        $resource1 = ResourceFactory::make()->withFoldersRelationsFor([$userA], $folderA)->withPermissionsFor([$userA])->persist();
        /** @var \App\Model\Entity\Resource $resource2 */
        $resource2 = ResourceFactory::make()->withFoldersRelationsFor([$userA], $folderA)->withPermissionsFor([$userA])->persist();

        $this->service->delete($this->makeUac($userA), $folderA->id, false);

        $this->assertFolderNotExist($folderA->id);
        // Folder B moves to root
        $this->assertFolder($folderB->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, null);
        // Resources move to root
        $this->assertResourceIsNotSoftDeleted($resource1->id);
        $this->assertResourceIsNotSoftDeleted($resource2->id);
        $this->assertFolderRelation($resource1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, null);
        $this->assertFolderRelation($resource2->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, null);
    }

    public function testFolderDelete_SharedSuccess8_NoCascadeMoveResourcesToRootForAllUsers()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // Resource R1 (Ada:O, Betty:O) is in folder A
        // ---
        // A (Ada:O, Betty:O)
        // |- R1 (Ada:O, Betty:O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()->withPermissionsFor([$userA, $userB])->persist();
        /** @var \App\Model\Entity\Resource $resource1 */
        $resource1 = ResourceFactory::make()
            ->withFoldersRelationsFor([$userA, $userB], $folderA)
            ->withPermissionsFor([$userA, $userB])
            ->persist();

        $this->service->delete($this->makeUac($userA), $folderA->id, false);

        $this->assertFolderNotExist($folderA->id);
        // Resource R1 is NOT deleted and moves to root for both users
        $this->assertResourceIsNotSoftDeleted($resource1->id);
        $this->assertFolderRelation($resource1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, null);
        $this->assertFolderRelation($resource1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userB->id, null);
    }

    public function testFolderDelete_CommonSuccess4_NotifyOnlyOperatorForPersonalFolder()
    {
        // Ada is OWNER of personal folder A (no other users)
        // ---
        // A (Ada:O)
        /** @var \App\Model\Entity\User $userA */
        $userA = UserFactory::make()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make(['name' => 'A'])->withPermissionsFor([$userA])->persist();

        $this->service->delete($this->makeUac($userA), $folderA->id);

        $this->assertEmailQueueCount(1);
        $this->assertEmailSubject($userA->username, 'You deleted the folder A');
    }

    public function testFolderDelete_CommonSuccess5_NotificationRespectsEmailSettings()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // Betty has disabled folder delete notifications
        // ---
        // A (Ada:O, Betty:O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make(['name' => 'A'])->withPermissionsFor([$userA, $userB])->persist();

        // Disable folder delete notifications globally
        $this->setEmailNotificationSetting('send.folder.delete', false);

        $this->service->delete($this->makeUac($userA), $folderA->id);

        $this->assertEmailQueueCount(0);
    }

    public function testFolderDelete_PersoSuccess11_NoCascadePreservesGrandchildrenStructure()
    {
        // Ada is OWNER of A, B, C and resource R1
        // A -> B -> C -> R1
        // Non-cascade delete of A should only move B to root
        // C should remain inside B, R1 should remain inside C
        // ---
        // A (Ada:O)
        // |- B (Ada:O)
        //    |- C (Ada:O)
        //       |- R1 (Ada:O)
        /** @var \App\Model\Entity\User $userA */
        $userA = UserFactory::make()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()->withPermissionsFor([$userA])->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderB */
        $folderB = FolderFactory::make()->withPermissionsFor([$userA])->withFoldersRelationsFor([$userA], $folderA)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderC */
        $folderC = FolderFactory::make()->withPermissionsFor([$userA])->withFoldersRelationsFor([$userA], $folderB)->persist();
        /** @var \App\Model\Entity\Resource $resource1 */
        $resource1 = ResourceFactory::make()->withFoldersRelationsFor([$userA], $folderC)->withPermissionsFor([$userA])->persist();

        $this->service->delete($this->makeUac($userA), $folderA->id, false);

        // Folder A is deleted
        $this->assertFolderNotExist($folderA->id);
        // Folder B moves to root
        $this->assertFolder($folderB->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, null);
        // Folder C stays inside B
        $this->assertFolder($folderC->id);
        $this->assertFolderRelation($folderC->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, $folderB->id);
        // Resource R1 stays inside C
        $this->assertResourceIsNotSoftDeleted($resource1->id);
        $this->assertFolderRelation($resource1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, $folderC->id);
    }

    /**
     * Given that a resource has a deleted resource type, it should not be soft deleted
     */
    public function testFolderDelete_Resource_With_Deleted_Resource_Type_Should_Not_Validate()
    {
        $user = UserFactory::make()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folder */
        $folder = FolderFactory::make()->withPermissionsFor([$user])->persist();

        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withFoldersRelationsFor([$user], $folder)
            ->withPermissionsFor([$user])
            ->with('ResourceTypes', ResourceTypeFactory::make()->passwordAndDescription()->deleted())
            ->persist();

        $permissionOnResourcesExists = PermissionFactory::make()->getTable()->exists(['aco_foreign_key' => $resource->id]);
        $this->assertTrue($permissionOnResourcesExists);

        $this->service->delete($this->makeUac($user), $folder->id, true);

        $this->assertResourceIsNotSoftDeleted($resource->id);
        $folderExists = FolderFactory::make()->getTable()->exists(['id' => $folder->id]);
        $this->assertFalse($folderExists);
        $folderRelationExists = FoldersRelationFactory::make()->getTable()->exists(['foreign_id' => $folder->id]);
        $this->assertFalse($folderRelationExists);
        $permissionOnFolderExists = PermissionFactory::make()->getTable()->exists(['aco_foreign_key' => $folder->id]);
        $this->assertFalse($permissionOnFolderExists);

        $parentFolderRelationExists = FoldersRelationFactory::make()->getTable()->exists(['folder_parent_id' => $folder->id]);
        $this->assertTrue($parentFolderRelationExists);
        $permissionOnResourcesExists = PermissionFactory::make()->getTable()->exists(['aco_foreign_key' => $resource->id]);
        $this->assertTrue($permissionOnResourcesExists);
    }
}
