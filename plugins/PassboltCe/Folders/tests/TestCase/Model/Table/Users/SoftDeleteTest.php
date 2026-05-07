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
 * @since         2.0.0
 */

namespace Passbolt\Folders\Test\TestCase\Model\Table\Users;

use App\Model\Entity\Permission;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\UserFactory;
use Cake\ORM\TableRegistry;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Folders\Test\Lib\FoldersTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;

class SoftDeleteTest extends FoldersTestCase
{
    use FoldersModelTrait;

    /**
     * @var \App\Model\Table\UsersTable
     */
    private $usersTable;

    public function setUp(): void
    {
        parent::setUp();
        $this->usersTable = TableRegistry::getTableLocator()->get('Users');
    }

    public function testUsersSoftDeleteSuccess_PersonalFolder()
    {
        /** @var \App\Model\Entity\User $userA */
        $userA = UserFactory::make()->user()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withFoldersRelationsFor([$userA])
            ->persist();

        $this->assertNotFalse($this->usersTable->softDelete($userA));

        // Assert the user is deleted as long as the folder and the folders relations
        $this->assertUserIsSoftDeleted($userA->id);
        $this->assertFolderNotExist($folderA->id);
        $this->assertFolderRelationNotExist($folderA->id, $userA->id);
    }

    public function testUsersSoftDeleteError_SoleOwnerFolder_FolderSharedWithUser()
    {
        // Ada is OWNER of folder A
        // Betty has READ on folder A
        // ---
        // A (Ada:O, Betty:R)
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withPermissionsFor([$userB], Permission::READ)
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();

        $this->usersTable->softDelete($userA);

        // Assert errors
        $errors = $userA->getErrors();
        $this->assertNotEmpty($errors['id']['soleOwnerOfSharedContent']);

        // Assert nothing has been updated/deleted
        $this->assertUserIsNotSoftDeleted($userA->id);
        $this->assertFolder($folderA->id);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
    }

    public function testUsersSoftDeleteSuccess_NotSoleOwnerFolder_FolderSharedWithUser()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // ---
        // A (Ada:O, Betty:O)
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();

        $result = $this->usersTable->softDelete($userA);
        $this->assertNotFalse($result);

        // Assert the user is soft deleted but the folder is not.
        $this->assertUserIsSoftDeleted($userA->id);
        $this->assertFolder($folderA->id);
        $this->assertFolderRelationNotExist($folderA->id, $userA->id);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
    }

    public function testUsersSoftDeleteSuccess_NotOwnerFolder_FolderSharedWithUser()
    {
        // Ada has READ on folder A
        // Betty is OWNER of folder A
        // ---
        // A (Ada:R, Betty:O)
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA], Permission::READ)
            ->withPermissionsFor([$userB])
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();

        $result = $this->usersTable->softDelete($userA);
        $this->assertNotFalse($result);

        // Assert the user is soft deleted but the folder is not.
        $this->assertUserIsSoftDeleted($userA->id);
        $this->assertFolder($folderA->id);
        $this->assertFolderRelationNotExist($folderA->id, $userA->id);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
    }

    public function testUsersSoftDeleteError_SoleOwnerFolder_FolderSharedWithGroup_UserIsOnlyGroupMember()
    {
        // Ada is OWNER of folder A
        // G1 has READ on folder A
        // Ada is group manager of G1
        // ---
        // A (Ada:O, G1:R)
        /** @var \App\Model\Entity\User $userA */
        $userA = UserFactory::make()->user()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$userA])->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withPermissionsFor([$group], Permission::READ)
            ->withFoldersRelationsFor([$userA, $group])
            ->persist();

        $result = $this->usersTable->softDelete($userA);
        $this->assertNotFalse($result);

        // Assert the user is deleted as long as the folder and the folders relations
        $this->assertUserIsSoftDeleted($userA->id);
        $this->assertFolderNotExist($folderA->id);
        $this->assertFolderRelationNotExist($folderA->id, $userA->id);
    }

    public function testUsersSoftDeleteError_SoleOwnerFolder_FolderSharedWithGroup()
    {
        // Ada is OWNER of folder A
        // G1 has READ on folder A
        // Betty is group manager of G1
        // ---
        // A (Ada:O, G1:R)
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$userB])->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$userA])
            ->withPermissionsFor([$group], Permission::READ)
            ->withFoldersRelationsFor([$userA, $userB])
            ->persist();

        $this->usersTable->softDelete($userA);

        // Assert errors
        $errors = $userA->getErrors();
        $this->assertNotEmpty($errors['id']['soleOwnerOfSharedContent']);

        // Assert nothing has been updated/deleted
        $this->assertUserIsNotSoftDeleted($userA->id);
        $this->assertFolder($folderA->id);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userA->id, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userB->id, FoldersRelation::ROOT);
    }

    public function testUsersSoftDeleteError_GroupIsSoleOwnerFolder_UserIsOnlyGroupMember()
    {
        // G1 has READ on folder A
        // Ada is group manager of G1
        // ---
        // A (G1:O)
        /** @var \App\Model\Entity\User $userA */
        $userA = UserFactory::make()->user()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$userA])->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderA */
        $folderA = FolderFactory::make()
            ->withPermissionsFor([$group], Permission::READ)
            ->withFoldersRelationsFor([$userA])
            ->persist();

        $result = $this->usersTable->softDelete($userA);
        $this->assertNotFalse($result);

        // Assert the user is deleted as long as the folder and the folders relations
        $this->assertUserIsSoftDeleted($userA->id);
        $this->assertFolderNotExist($folderA->id);
        $this->assertFolderRelationNotExist($folderA->id, $userA->id);
    }
}
