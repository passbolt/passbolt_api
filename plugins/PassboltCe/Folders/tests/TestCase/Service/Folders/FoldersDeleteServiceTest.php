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
use Passbolt\Folders\Test\Factory\ResourceFactory;
use Passbolt\Folders\Test\Lib\FoldersTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;

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

        $this->service->delete($this->makeUac($userA), $folderA->id, true);
        $this->assertFolderNotExist($folderA->id);
        $this->assertResourceIsSoftDeleted($resource1->id);
        $this->assertFolderNotExist($folderB->id);
        $this->assertResourceIsSoftDeleted($resource2->id);
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
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()->withPermissionsFor([$userA, $userB])->persist();
        /** @var \App\Model\Entity\Resource $resource1 */
        $resource1 = ResourceFactory::make()->withFoldersRelationsFor([$userA, $userB], $folderA)->withPermissionsFor([$userA, $userB])->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderB */
        $folderB = FolderFactory::make()->withPermissionsFor([$userA, $userB])->withFoldersRelationsFor([$userA, $userB], $folderA)->persist();
        /** @var \App\Model\Entity\Resource $resource2 */
        $resource2 = ResourceFactory::make()->withFoldersRelationsFor([$userA, $userB], $folderB)->withPermissionsFor([$userA, $userB])->persist();

        $this->service->delete($this->makeUac($userA), $folderA->id, true);
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
}
