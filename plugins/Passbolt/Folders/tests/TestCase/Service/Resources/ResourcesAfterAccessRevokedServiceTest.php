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

namespace Passbolt\Folders\Test\TestCase\Service\Resources;

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
use Cake\ORM\TableRegistry;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Service\Resources\ResourcesAfterAccessRevokedService;
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
     * @var \App\Model\Table\PermissionsTable
     */
    private $permissionsTable;

    public function setUp(): void
    {
        parent::setUp();
        $this->permissionsTable = TableRegistry::getTableLocator()->get('Permissions');
        $this->service = new ResourcesAfterAccessRevokedService();
    }

    public function testResourceAfterAccessRevokedSuccess_UserPermissionRevoked()
    {
        [$r1, $userAId, $userBId] = $this->insertFixture_UserPermissionRevoked();
        $uac = new UserAccessControl(Role::USER, $userAId);

        /** @var \App\Model\Entity\Permission $permission */
        $permission = $this->permissionsTable->findByAcoForeignKeyAndAroForeignKey($r1->get('id'), $userBId)->first();
        $this->permissionsTable->delete($permission);
        $this->service->afterAccessRevoked($uac, $permission);

        $this->assertItemIsInTrees($r1->get('id'), 1);
        $this->assertFolderRelation($r1->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, null);
    }

    public function insertFixture_UserPermissionRevoked()
    {
        // Ada is OWNER of resource R1
        // Betty is OWNER of resource R1
        // R1 (Ada:O, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $r1 = $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);

        return [$r1, $userAId, $userBId];
    }

    public function testResourceAfterAccessRevokedSuccess_GroupPermissionRevoked()
    {
        [$r1, $g1, $userAId, $userBId, $userCId] = $this->insertFixture_GroupPermissionRevoked();
        $uac = new UserAccessControl(Role::USER, $userAId);

        /** @var \App\Model\Entity\Permission $permission */
        $permission = $this->permissionsTable->findByAcoForeignKeyAndAroForeignKey($r1->get('id'), $g1->get('id'))->first();
        $this->permissionsTable->delete($permission);
        $this->service->afterAccessRevoked($uac, $permission);

        $this->assertItemIsInTrees($r1->id, 1);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, null);
    }

    public function insertFixture_GroupPermissionRevoked()
    {
        // Ada is OWNER of resource R1
        // G1 is OWNER of resource R1
        // Betty is member of G1
        // Carol is member of G1
        // R1 (Ada:O, G1)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $userCId = UuidFactory::uuid('user.id.carol');
        $g1 = $this->addGroup(['name' => 'G1', 'groups_users' => [
            ['user_id' => $userBId, 'is_admin' => true],
            ['user_id' => $userCId, 'is_admin' => true],
        ]]);
        $r1 = $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER], [$g1->id => Permission::OWNER]);

        return [$r1, $g1, $userAId, $userBId, $userCId];
    }
}
