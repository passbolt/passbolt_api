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
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Service\Resources\ResourcesAfterAccessGrantedService;
use Passbolt\Folders\Test\Lib\FoldersTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

/**
 * Passbolt\Folders\Service\Folders\ResourcesAfterAccessGrantedService Test Case
 *
 * @uses \Passbolt\Folders\Service\Resources\ResourcesAfterAccessGrantedService
 */
class ResourcesAfterAccessGrantedServiceTest extends FoldersTestCase
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
     * @var ResourcesAfterAccessGrantedService
     */
    private $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new ResourcesAfterAccessGrantedService();
    }

    public function testResourceAfterAccessGrantedSuccess_UserPermissionAdded()
    {
        [$r1, $userAId] = $this->insertFixture_UserPermissionAdded();
        $userBId = UuidFactory::uuid('user.id.betty');
        $uac = new UserAccessControl(Role::USER, $userAId);

        $permission = $this->addPermission(PermissionsTable::RESOURCE_ACO, $r1->id, PermissionsTable::USER_ARO, $userBId);
        $this->service->afterAccessGranted($uac, $permission);

        $this->assertItemIsInTrees($r1->id, 2);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, null);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, null);
    }

    public function insertFixture_UserPermissionAdded()
    {
        // Ada is OWNER of resource R1
        // R1 (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $r1 = $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER]);

        return [$r1, $userAId];
    }

    public function testResourceAfterAccessGrantedSuccess_UserPermissionAdded_GroupUserAlreadyHavingAccess()
    {
        [$r1, $g1, $userAId] = $this->insertFixture_UserPermissionAdded_GroupUserAlreadyHavingAccess();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $permission = $this->addPermission(PermissionsTable::RESOURCE_ACO, $r1->id, PermissionsTable::USER_ARO, $userAId);
        $this->service->afterAccessGranted($uac, $permission);

        $this->assertItemIsInTrees($r1->id, 1);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, null);
    }

    public function insertFixture_UserPermissionAdded_GroupUserAlreadyHavingAccess()
    {
        // Ada is OWNER of resource R1
        // R1 (Ada:O)
        // G1 is OWNER of resource R1
        $userAId = UuidFactory::uuid('user.id.ada');
        $g1 = $this->addGroup(['name' => 'G1', 'groups_users' => [
            ['user_id' => $userAId, 'is_admin' => true],
        ]]);
        $r1 = $this->addResourceFor(['name' => 'R1', 'created_by' => $userAId, 'modified_by' => $userAId], [], [$g1->id => Permission::OWNER]);

        return [$r1, $g1, $userAId];
    }

    public function testResourceAfterAccessGrantedSuccess_GroupPermissionAdded()
    {
        [$r1, $g1, $userAId, $userBId, $userCId] = $this->insertFixture_GroupPermissionAdded();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $permission = $this->addPermission(PermissionsTable::RESOURCE_ACO, $r1->id, PermissionsTable::GROUP_ARO, $g1->id);
        $this->service->afterAccessGranted($uac, $permission);

        $this->assertItemIsInTrees($r1->id, 3);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, null);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, null);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userCId, null);
    }

    public function insertFixture_GroupPermissionAdded()
    {
        // Ada is OWNER of resource R1
        // G1 is OWNER of resource R1
        // R1 (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $userCId = UuidFactory::uuid('user.id.carol');
        $r1 = $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER]);
        $g1 = $this->addGroup(['name' => 'G1', 'groups_users' => [
            ['user_id' => $userBId, 'is_admin' => true],
            ['user_id' => $userCId, 'is_admin' => true],
        ]]);

        return [$r1, $g1, $userAId, $userBId, $userCId];
    }

    public function testResourceAfterAccessGrantedSuccess_GroupPermissionAdded_UserAlreadyHaveDirectAccess()
    {
        [$r1, $g1, $userAId, $userBId] = $this->insertFixture_GroupPermissionAdded_UserAlreadyHaveDirectAccess();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $permission = $this->addPermission(PermissionsTable::RESOURCE_ACO, $r1->id, PermissionsTable::GROUP_ARO, $g1->id);
        $this->service->afterAccessGranted($uac, $permission);

        $this->assertItemIsInTrees($r1->id, 2);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userAId, null);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userBId, null);
    }

    public function insertFixture_GroupPermissionAdded_UserAlreadyHaveDirectAccess()
    {
        // Ada is OWNER of resource R1
        // G1 is OWNER of resource R1
        // R1 (Ada:O, G1:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $r1 = $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER]);
        $g1 = $this->addGroup(['name' => 'G1', 'groups_users' => [
            ['user_id' => $userAId, 'is_admin' => true],
            ['user_id' => $userBId, 'is_admin' => true],
        ]]);

        return [$r1, $g1, $userAId, $userBId];
    }
}
