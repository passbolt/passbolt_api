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
use App\Test\Fixture\Base\FavoritesFixture;
use App\Test\Fixture\Base\GpgkeysFixture;
use App\Test\Fixture\Base\GroupsFixture;
use App\Test\Fixture\Base\GroupsUsersFixture;
use App\Test\Fixture\Base\SecretsFixture;
use App\Test\Fixture\Base\UsersFixture;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Test\Lib\FoldersTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

class SoftDeleteTest extends FoldersTestCase
{
    use FoldersModelTrait;
    use FoldersRelationsModelTrait;

    public $fixtures = [
        FavoritesFixture::class,
        GpgkeysFixture::class,
        GroupsFixture::class,
        GroupsUsersFixture::class,
        SecretsFixture::class,
        UsersFixture::class,
    ];

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
        [$folderA, $userAId] = $this->insertFixture_PersonalFolder();
        $user = $this->usersTable->get($userAId);

        $this->assertNotFalse($this->usersTable->softDelete($user));

        // Assert the user is deleted as long as the folder and the folders relations
        $this->assertUserIsSoftDeleted($userAId);
        $this->assertFolderNotExist($folderA->id);
        $this->assertFolderRelationNotExist($folderA->id, $userAId);
    }

    private function insertFixture_PersonalFolder()
    {
        // Ada is OWNER of folder A
        // ---
        // A (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER]);

        return [$folderA, $userAId];
    }

    public function testUsersSoftDeleteError_SoleOwnerFolder_FolderSharedWithUser()
    {
        [$folderA, $userAId, $userBId] = $this->insertFixture_SoleOwnerFolder_FolderSharedWithUser();
        $user = $this->usersTable->get($userAId);
        $this->usersTable->softDelete($user);

        // Assert errors
        $errors = $user->getErrors();
        $this->assertNotEmpty($errors['id']['soleOwnerOfSharedContent']);

        // Assert nothing has been updated/deleted
        $this->assertUserIsNotSoftDeleted($userAId);
        $this->assertFolder($folderA->id);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
    }

    private function insertFixture_SoleOwnerFolder_FolderSharedWithUser()
    {
        // Ada is OWNER of folder A
        // Betty has READ on folder A
        // ---
        // A (Ada:O, Betty:R)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER, $userBId => Permission::READ]);

        return [$folderA, $userAId, $userBId];
    }

    public function testUsersSoftDeleteSuccess_NotSoleOwnerFolder_FolderSharedWithUser()
    {
        [$folderA, $userAId, $userBId] = $this->insertFixture_NotSoleOwnerFolder_FolderSharedWithUser();
        $user = $this->usersTable->get($userAId);
        $result = $this->usersTable->softDelete($user);
        $this->assertNotFalse($result);

        // Assert the user is soft deleted but the folder is not.
        $this->assertUserIsSoftDeleted($userAId);
        $this->assertFolder($folderA->id);
        $this->assertFolderRelationNotExist($folderA->id, $userAId);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
    }

    private function insertFixture_NotSoleOwnerFolder_FolderSharedWithUser()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // ---
        // A (Ada:O, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);

        return [$folderA, $userAId, $userBId];
    }

    public function testUsersSoftDeleteSuccess_NotOwnerFolder_FolderSharedWithUser()
    {
        [$folderA, $userAId, $userBId] = $this->insertFixture_NotOwnerFolder_FolderSharedWithUser();
        $user = $this->usersTable->get($userAId);
        $result = $this->usersTable->softDelete($user);
        $this->assertNotFalse($result);

        // Assert the user is soft deleted but the folder is not.
        $this->assertUserIsSoftDeleted($userAId);
        $this->assertFolder($folderA->id);
        $this->assertFolderRelationNotExist($folderA->id, $userAId);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
    }

    private function insertFixture_NotOwnerFolder_FolderSharedWithUser()
    {
        // Ada has READ on folder A
        // Betty is OWNER of folder A
        // ---
        // A (Ada:R, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::READ, $userBId => Permission::OWNER]);

        return [$folderA, $userAId, $userBId];
    }

    public function testUsersSoftDeleteError_SoleOwnerFolder_FolderSharedWithGroup_UserIsOnlyGroupMember()
    {
        [$folderA, $g1, $userAId] = $this->insertFixture_SoleOwnerFolder_FolderSharedWithGroup_UserIsOnlyGroupMember();
        $user = $this->usersTable->get($userAId);

        $result = $this->usersTable->softDelete($user);
        $this->assertNotFalse($result);

        // Assert the user is deleted as long as the folder and the folders relations
        $this->assertUserIsSoftDeleted($userAId);
        $this->assertFolderNotExist($folderA->id);
        $this->assertFolderRelationNotExist($folderA->id, $userAId);
    }

    private function insertFixture_SoleOwnerFolder_FolderSharedWithGroup_UserIsOnlyGroupMember()
    {
        // Ada has READ on folder A
        // G1 is OWNER of folder A
        // Ada is group manager of G1
        // ---
        // A (Ada:R, G1:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $groupData = [
            'groups_users' => [
                ['user_id' => $userAId, 'is_admin' => true],
            ],
        ];
        $g1 = $this->addGroup($groupData);
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER], [$g1->id => Permission::READ]);

        return [$folderA, $g1, $userAId];
    }

    public function testUsersSoftDeleteError_SoleOwnerFolder_FolderSharedWithGroup()
    {
        [$folderA, $g1, $userAId, $userBId] = $this->insertFixture_SoleOwnerFolder_FolderSharedWithGroup();
        $user = $this->usersTable->get($userAId);
        $this->usersTable->softDelete($user);

        // Assert errors
        $errors = $user->getErrors();
        $this->assertNotEmpty($errors['id']['soleOwnerOfSharedContent']);

        // Assert nothing has been updated/deleted
        $this->assertUserIsNotSoftDeleted($userAId);
        $this->assertFolder($folderA->id);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, FoldersRelation::ROOT);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, FoldersRelation::ROOT);
    }

    private function insertFixture_SoleOwnerFolder_FolderSharedWithGroup()
    {
        // Ada has READ on folder A
        // G1 is OWNER of folder A
        // Betty is group manager of G1
        // ---
        // A (Ada:R, G1:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $groupData = [
            'groups_users' => [
                ['user_id' => $userBId, 'is_admin' => true],
            ],
        ];
        $g1 = $this->addGroup($groupData);
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER], [$g1->id => Permission::READ]);

        return [$folderA, $g1, $userAId, $userBId];
    }

    public function testUsersSoftDeleteError_GroupIsSoleOwnerFolder_UserIsOnlyGroupMember()
    {
        [$folderA, $g1, $userAId] = $this->insertFixture_GroupIsSoleOwnerFolder_UserIsOnlyGroupMember();
        $user = $this->usersTable->get($userAId);

        $result = $this->usersTable->softDelete($user);
        $this->assertNotFalse($result);

        // Assert the user is deleted as long as the folder and the folders relations
        $this->assertUserIsSoftDeleted($userAId);
        $this->assertFolderNotExist($folderA->id);
        $this->assertFolderRelationNotExist($folderA->id, $userAId);
    }

    private function insertFixture_GroupIsSoleOwnerFolder_UserIsOnlyGroupMember()
    {
        // G1 is OWNER of folder A
        // Ada is group manager of G1
        // ---
        // A (G1:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $groupData = [
            'groups_users' => [
                ['user_id' => $userAId, 'is_admin' => true],
            ],
        ];
        $g1 = $this->addGroup($groupData);
        $folderA = $this->addFolderFor(['name' => 'A'], [], [$g1->id => Permission::READ]);

        return [$folderA, $g1, $userAId];
    }
}
