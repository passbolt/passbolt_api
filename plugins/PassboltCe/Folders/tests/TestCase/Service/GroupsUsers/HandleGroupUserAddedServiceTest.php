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
use App\Model\Entity\Role;
use App\Test\Fixture\Base\GroupsFixture;
use App\Test\Fixture\Base\GroupsUsersFixture;
use App\Test\Fixture\Base\PermissionsFixture;
use App\Test\Fixture\Base\ProfilesFixture;
use App\Test\Fixture\Base\ResourcesFixture;
use App\Test\Fixture\Base\SecretsFixture;
use App\Test\Fixture\Base\UsersFixture;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Service\GroupsUsers\HandleGroupUserAddedService;
use Passbolt\Folders\Test\Lib\FoldersTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
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
        [$r1, $r2, $g1, $userAId] = $this->insertFixture_AddResourceToUserTree();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $userBId = UuidFactory::uuid('user.id.betty');

        // Prepare the test by deleting the group user entry
        $userBGroupUser = $this->addGroupUser(['group_id' => $g1->id, 'user_id' => $userBId]);

        $this->service->handle($uac, $userBGroupUser);

        $this->assertItemIsInTrees($r1->id, 2);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, null);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, null);
        $this->assertItemIsInTrees($r2->id, 2);
        $this->assertFolderRelation($r2->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, null);
        $this->assertFolderRelation($r2->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, null);
    }

    public function insertFixture_AddResourceToUserTree()
    {
        // Ada is OWNER of resource R1
        // G1 is OWNER of resource R1
        // Ada is OWNER of resource R2
        // G1 is OWNER of resource R2
        // Ada is group manager of G1
        // ----
        // R1 (Ada:O, G1:O)
        // R2 (Ada:O, G1:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $g1 = $this->addGroup(['name' => 'G1', 'groups_users' => [
            ['user_id' => $userAId, 'is_admin' => true],
        ]]);
        $r1 = $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER], [$g1->id => Permission::OWNER]);
        $r2 = $this->addResourceFor(['name' => 'R2'], [$userAId => Permission::OWNER], [$g1->id => Permission::OWNER]);

        return [$r1, $r2, $g1, $userAId];
    }

    public function testGroupsAfterUserAddedSuccess_ResourceWasAlreadyInUserTree()
    {
        [$r1, $r2, $g1, $userAId] = $this->insertFixture_ResourceWasAlreadyInUserTree();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $userBId = UuidFactory::uuid('user.id.betty');

        // Prepare the test by deleting the group user entry
        $userBGroupUser = $this->addGroupUser(['group_id' => $g1->id, 'user_id' => $userBId]);

        $this->service->handle($uac, $userBGroupUser);

        $this->assertItemIsInTrees($r1->id, 2);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, null);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, null);
        $this->assertItemIsInTrees($r2->id, 2);
        $this->assertFolderRelation($r2->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, null);
        $this->assertFolderRelation($r2->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, null);
    }

    public function insertFixture_ResourceWasAlreadyInUserTree()
    {
        // Ada is OWNER of resource R1
        // G1 is OWNER of resource R1
        // Betty is OWNER of resource R2
        // G1 is OWNER of resource R2
        // Ada is group manager of G1
        // ----
        // R1 (Ada:O, G1:O)
        // R2 (Betty:O, G1:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $g1 = $this->addGroup(['name' => 'G1', 'groups_users' => [
            ['user_id' => $userAId, 'is_admin' => true],
        ]]);
        $r1 = $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER], [$g1->id => Permission::OWNER]);
        $r2 = $this->addResourceFor(['name' => 'R2'], [$userAId => Permission::OWNER], [$g1->id => Permission::OWNER]);

        return [$r1, $r2, $g1, $userAId];
    }

    public function testGroupsAfterUserAddedSuccess_AddFolderToUserTree()
    {
        [$folderA, $folderB, $g1, $userAId] = $this->insertFixture_AddFolderToUserTree();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $userBId = UuidFactory::uuid('user.id.betty');

        // Prepare the test by deleting the group user entry
        $userBGroupUser = $this->addGroupUser(['group_id' => $g1->id, 'user_id' => $userBId]);

        $this->service->handle($uac, $userBGroupUser);

        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
    }

    public function insertFixture_AddFolderToUserTree()
    {
        // Ada is OWNER of folder A
        // G1 is OWNER of folder A
        // Ada is OWNER of folder B
        // G1 is OWNER of folder B
        // Ada is group manager of G1
        // ----
        // A (Ada:O, G1:O)
        // B (Ada:O, G1:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $g1 = $this->addGroup(['name' => 'G1', 'groups_users' => [
            ['user_id' => $userAId, 'is_admin' => true],
        ]]);
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER], [$g1->id => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B'], [$userAId => Permission::OWNER], [$g1->id => Permission::OWNER]);

        return [$folderA, $folderB, $g1, $userAId];
    }

    public function testGroupsAfterUserAddedSuccess_FolderWasAlreadyInUserTree()
    {
        [$folderA, $folderB, $g1, $userAId, $userBId] = $this->insertFixture_FolderWasAlreadyInUserTree();
        $uac = new UserAccessControl(Role::USER, $userAId);

        // Prepare the test by deleting the group user entry
        $userBGroupUser = $this->addGroupUser(['group_id' => $g1->id, 'user_id' => $userBId]);

        $this->service->handle($uac, $userBGroupUser);

        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        $this->assertItemIsInTrees($folderB->id, 2);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderB->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
    }

    public function insertFixture_FolderWasAlreadyInUserTree()
    {
        // Ada is OWNER of folder A
        // G1 is OWNER of folder A
        // Betty is OWNER of folder B
        // G1 is OWNER of folder B
        // Ada is group manager of G1
        // ----
        // A (Ada:O, G1:O)
        // B (Betty:O, G1:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $g1 = $this->addGroup(['name' => 'G1', 'groups_users' => [
            ['user_id' => $userAId, 'is_admin' => true],
        ]]);
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER], [$g1->id => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B'], [$userBId => Permission::OWNER], [$g1->id => Permission::OWNER]);

        return [$folderA, $folderB, $g1, $userAId, $userBId];
    }
}
