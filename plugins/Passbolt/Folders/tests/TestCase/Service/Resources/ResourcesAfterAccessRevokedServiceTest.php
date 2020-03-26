<?php
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
 * @since         2.14.0
 */

namespace Passbolt\Folders\Test\TestCase\Service\Resources;

use App\Model\Entity\Permission;
use App\Model\Entity\Role;
use App\Model\Table\PermissionsTable;
use App\Test\Fixture\Base\GroupsFixture;
use App\Test\Fixture\Base\GroupsUsersFixture;
use App\Test\Fixture\Base\PermissionsFixture;
use App\Test\Fixture\Base\ProfilesFixture;
use App\Test\Fixture\Base\ResourcesFixture;
use App\Test\Fixture\Base\SecretsFixture;
use App\Test\Fixture\Base\UsersFixture;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Service\Resources\ResourcesAfterAccessRevokedService;
use Passbolt\Folders\Test\Fixture\FoldersFixture;
use Passbolt\Folders\Test\Fixture\FoldersRelationsFixture;
use Passbolt\Folders\Test\Lib\FoldersTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

/**
 * Passbolt\Folders\Service\Folders\ResourcesAfterAccessRevokedService Test Case
 *
 * @uses \Passbolt\Folders\Service\Resources\ResourcesAfterAccessRevokedService
 */
class ResourcesAfterAccessRevokedServiceTest extends FoldersTestCase
{
    use FoldersModelTrait;
    use FoldersRelationsModelTrait;

    public $fixtures = [
        FoldersFixture::class,
        FoldersRelationsFixture::class,
        GroupsFixture::class,
        GroupsUsersFixture::class,
        PermissionsFixture::class,
        ProfilesFixture::class,
        ResourcesFixture::class,
        SecretsFixture::class,
        UsersFixture::class,
    ];

    /**
     * @var ResourcesAfterAccessRevokedService
     */
    private $service;

    /**
     * @var PermissionsTable
     */
    private $permissionsTable;

    public function setUp()
    {
        parent::setUp();
        $this->permissionsTable = TableRegistry::getTableLocator()->get('Permissions');
        $this->service = new ResourcesAfterAccessRevokedService();
    }

    /* ************************************************************** */
    /* REMOVE USER PERMISSION - RESOURCE NO PARENT */
    /* ************************************************************** */

    public function testAfterShareResourceRemoveUserSuccess1_NoParent()
    {
        list($r1, $userAId, $userBId) = $this->insertFixture_AfterShareResourceRemoveUserSuccess1();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $permission = $this->permissionsTable->findByAcoForeignKeyAndAroForeignKey($r1->id, $userBId)->first();
        $this->permissionsTable->delete($permission);
        $this->service->afterAccessRevoked($uac, $permission);

        $this->assertItemIsInTrees($r1->id, 1);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, null);
        $this->assertPermission($r1->id, $userAId, Permission::OWNER);
        $this->assertPermissionNotExist($r1->id, $userBId);
    }

    public function insertFixture_AfterShareResourceRemoveUserSuccess1()
    {
        // Ada is OWNER of resource R1
        // Betty is OWNER of resource R1
        // R1 (Ada:O, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $r1 = $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);

        return [$r1, $userAId, $userBId];
    }

    /* ************************************************************** */
    /* REMOVE USER PERMISSION - RESOURCE HAVING A PARENT */
    /* ************************************************************** */

    public function testAfterShareResourceRemoveUserSuccess2_HavingAParent()
    {
        list($folderA, $r1, $userAId, $userBId) = $this->insertFixture_AfterShareResourceRemoveUserSuccess2();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $permission = $this->permissionsTable->findByAcoForeignKeyAndAroForeignKey($r1->id, $userBId)->first();
        $this->permissionsTable->delete($permission);
        $this->service->afterAccessRevoked($uac, $permission);

        // Folder A.
        $this->assertItemIsInTrees($folderA->id, 2);
        $this->assertPermission($folderA->id, $userAId, Permission::OWNER);
        $this->assertPermission($folderA->id, $userBId, Permission::OWNER);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userAId, null);
        $this->assertFolderRelation($folderA->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $userBId, null);
        // Resource1
        $this->assertItemIsInTrees($r1->id, 1);
        $this->assertPermission($r1->id, $userAId, Permission::OWNER);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, $folderA->id);
        $this->assertFolderRelationNotExist($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, null);
    }

    public function insertFixture_AfterShareResourceRemoveUserSuccess2()
    {
        // Ada is OWNER of folder A
        // Betty is OWNER of folder A
        // Ada is OWNER of resource R1
        // Betty is OWNER of resource R1
        // Ada sees R1 in A
        // Betty sees R1 in A
        // ----
        // A (Ada:O, Betty:O)
        // |- R1 (Ada:O, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $r1 = $this->addResourceFor(['name' => 'R1', 'folder_parent_id' => $folderA->id], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);

        return [$folderA, $r1, $userAId, $userBId];
    }
}
