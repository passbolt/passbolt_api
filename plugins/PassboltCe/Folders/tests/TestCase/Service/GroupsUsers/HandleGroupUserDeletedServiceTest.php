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

use App\Model\Entity\Permission;
use App\Test\Fixture\Base\GroupsFixture;
use App\Test\Fixture\Base\GroupsUsersFixture;
use App\Test\Fixture\Base\PermissionsFixture;
use App\Test\Fixture\Base\ProfilesFixture;
use App\Test\Fixture\Base\ResourcesFixture;
use App\Test\Fixture\Base\SecretsFixture;
use App\Test\Fixture\Base\UsersFixture;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Service\GroupsUsers\HandleGroupUserDeletedService;
use Passbolt\Folders\Test\Lib\FoldersTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

/**
 * Passbolt\Folders\Service\Groups\HandleGroupUserDeletedServices Test Case
 *
 * @uses \Passbolt\Folders\Service\GroupsUsers\HandleGroupUserDeletedService
 */
class HandleGroupUserDeletedServiceTest extends FoldersTestCase
{
    use FoldersModelTrait;
    use FoldersRelationsModelTrait;

    public $fixtures = [
        GroupsFixture::class,
        GroupsUsersFixture::class,
        PermissionsFixture::class,
        ProfilesFixture::class,
        ResourcesFixture::class,
        SecretsFixture::class,
        UsersFixture::class,
    ];

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
        [$r1, $r2, $g1, $userAId, $userBId] = $this->insertFixture_RemoveResourceFromUserTree();

        // Prepare the test by deleting the group user entry
        /** @var \App\Model\Entity\GroupsUser $userBGroupUser */
        $userBGroupUser = $this->groupsUsersTable->findByGroupIdAndUserId($g1->id, $userBId)->first();
        $this->groupsUsersTable->delete($userBGroupUser);

        $this->service->handle($userBGroupUser);

        $this->assertItemIsInTrees($r1->id, 1);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, null);
        $this->assertItemIsInTrees($r2->id, 1);
        $this->assertFolderRelation($r2->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, null);
    }

    public function insertFixture_RemoveResourceFromUserTree()
    {
        // Betty is OWNER of resource R1
        // G1 is OWNER of resource R1
        // Betty is OWNER of resource R2
        // G1 is OWNER of resource R2
        // Ada is group manager of G1
        // Betty is group manager of G1
        // ----
        // R1 (Betty:O, G1:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $g1 = $this->addGroup(['name' => 'G1', 'groups_users' => [
            ['user_id' => $userAId, 'is_admin' => true],
            ['user_id' => $userBId, 'is_admin' => true],
        ]]);
        $r1 = $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER], [$g1->get('id') => Permission::OWNER]);
        $r2 = $this->addResourceFor(['name' => 'R2'], [$userAId => Permission::OWNER], [$g1->get('id') => Permission::OWNER]);

        return [$r1, $r2, $g1, $userAId, $userBId];
    }

    public function testGroupsAfterUserRemovedSuccess_KeepResourceInTreeWhenUserHasAnotherAccess()
    {
        [$r1, $r2, $g1, $userAId, $userBId] = $this->insertFixture_KeepResourceInTreeWhenUserHasAnotherAccess();

        // Prepare the test by deleting the group user entry
        /** @var \App\Model\Entity\GroupsUser $userBGroupUser */
        $userBGroupUser = $this->groupsUsersTable->findByGroupIdAndUserId($g1->get('id'), $userBId)->first();
        $this->groupsUsersTable->delete($userBGroupUser);

        $this->service->handle($userBGroupUser);

        $this->assertItemIsInTrees($r1->get('id'), 2);
        $this->assertFolderRelation($r1->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, null);
        $this->assertFolderRelation($r1->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, null);
        $this->assertItemIsInTrees($r2->get('id'), 2);
        $this->assertFolderRelation($r2->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, null);
        $this->assertFolderRelation($r2->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, null);
    }

    public function insertFixture_KeepResourceInTreeWhenUserHasAnotherAccess()
    {
        // Ada is OWNER of resource R1
        // G1 is OWNER of resource R1
        // Ada is group manager of G1
        // Betty is group manager of G1
        // ----
        // R1 (Ada:O, G1:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $g1 = $this->addGroup(['name' => 'G1', 'groups_users' => [
            ['user_id' => $userAId, 'is_admin' => true],
            ['user_id' => $userBId, 'is_admin' => true],
        ]]);
        $r1 = $this->addResourceFor(['name' => 'R1'], [$userBId => Permission::OWNER], [$g1->id => Permission::OWNER]);
        $r2 = $this->addResourceFor(['name' => 'R2'], [$userBId => Permission::OWNER], [$g1->id => Permission::OWNER]);

        return [$r1, $r2, $g1, $userAId, $userBId];
    }

    public function testGroupsAfterUserRemovedSuccess_RemoveFolderFromUserTree()
    {
        [$folderA, $folderB, $g1, $userAId, $userBId] = $this->insertFixture_RemoveFolderFromUserTree();

        // Prepare the test by deleting the group user entry
        /** @var \App\Model\Entity\GroupsUser $userBGroupUser */
        $userBGroupUser = $this->groupsUsersTable->findByGroupIdAndUserId($g1->id, $userBId)->first();
        $this->groupsUsersTable->delete($userBGroupUser);

        $this->service->handle($userBGroupUser);

        $this->assertItemIsInTrees($folderA->id, 1);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertItemIsInTrees($folderB->id, 1);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
    }

    public function insertFixture_RemoveFolderFromUserTree()
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
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $g1 = $this->addGroup(['name' => 'G1', 'groups_users' => [
            ['user_id' => $userAId, 'is_admin' => true],
            ['user_id' => $userBId, 'is_admin' => true],
        ]]);
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER], [$g1->id => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B'], [$userAId => Permission::OWNER], [$g1->id => Permission::OWNER]);

        return [$folderA, $folderB, $g1, $userAId, $userBId];
    }

    public function testGroupsAfterUserRemovedSuccess_RemoveFolderFromUserTreeMoveContentToRoot()
    {
        [$folderA, $folderB, $g1, $userAId, $userBId] = $this->insertFixture_RemoveFolderFromUserTreeMoveContentToRoot();

        // Prepare the test by deleting the group user entry
        /** @var \App\Model\Entity\GroupsUser $userBGroupUser */
        $userBGroupUser = $this->groupsUsersTable->findByGroupIdAndUserId($g1->id, $userBId)->first();
        $this->groupsUsersTable->delete($userBGroupUser);

        $this->service->handle($userBGroupUser);

        $this->assertItemIsInTrees($folderA->id, 1);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, $folderA->id);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
    }

    public function insertFixture_RemoveFolderFromUserTreeMoveContentToRoot()
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
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $g1 = $this->addGroup(['name' => 'G1', 'groups_users' => [
            ['user_id' => $userAId, 'is_admin' => true],
            ['user_id' => $userBId, 'is_admin' => true],
        ]]);
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER], [$g1->id => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B', 'folder_parent_id' => $folderA->id], [$userBId => Permission::OWNER], [$g1->id => Permission::OWNER]);

        return [$folderA, $folderB, $g1, $userAId, $userBId];
    }

    public function testGroupsAfterUserRemovedSuccess_KeepFolderInTreeWhenUserHasAnotherAccess()
    {
        [$folderA, $folderB, $g1, $userAId, $userBId] = $this->insertFixture_KeepFolderInTreeWhenUserHasAnotherAccess();

        // Prepare the test by deleting the group user entry
        /** @var \App\Model\Entity\GroupsUser $userBGroupUser */
        $userBGroupUser = $this->groupsUsersTable->findByGroupIdAndUserId($g1->id, $userBId)->first();
        $this->groupsUsersTable->delete($userBGroupUser);

        $this->service->handle($userBGroupUser);

        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
    }

    public function insertFixture_KeepFolderInTreeWhenUserHasAnotherAccess()
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
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $g1 = $this->addGroup(['name' => 'G1', 'groups_users' => [
            ['user_id' => $userAId, 'is_admin' => true],
            ['user_id' => $userBId, 'is_admin' => true],
        ]]);
        $folderA = $this->addFolderFor(['name' => 'A'], [$userBId => Permission::OWNER], [$g1->id => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B'], [$userBId => Permission::OWNER], [$g1->id => Permission::OWNER]);

        return [$folderA, $folderB, $g1, $userAId, $userBId];
    }
}
