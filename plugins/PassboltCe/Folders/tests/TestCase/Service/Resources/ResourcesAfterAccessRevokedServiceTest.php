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

use App\Test\Factory\GroupFactory;
use App\Test\Factory\UserFactory;
use Cake\ORM\TableRegistry;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Service\Resources\ResourcesAfterAccessRevokedService;
use Passbolt\Folders\Test\Factory\ResourceFactory;
use Passbolt\Folders\Test\Lib\FoldersTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;

/**
 * Passbolt\Folders\Service\Folders\ResourcesAfterAccessRevokedService Test Case
 *
 * @uses \Passbolt\Folders\Service\Resources\ResourcesAfterAccessRevokedService
 */
class ResourcesAfterAccessRevokedServiceTest extends FoldersTestCase
{
    use FoldersModelTrait;

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
        // Ada is OWNER of resource R1
        // Betty is OWNER of resource R1
        // R1 (Ada:O, Betty:O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \App\Model\Entity\Resource $r1 */
        $r1 = ResourceFactory::make()->withFoldersRelationsFor([$userA, $userB])->withPermissionsFor([$userA, $userB])->persist();

        /** @var \App\Model\Entity\Permission $permission */
        $permission = $r1->permissions[1];
        $this->permissionsTable->delete($permission);
        $this->service->afterAccessRevoked($this->makeUac($userA), $permission);

        $this->assertItemIsInTrees($r1->get('id'), 1);
        $this->assertFolderRelation($r1->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, null);
    }

    public function testResourceAfterAccessRevokedSuccess_GroupPermissionRevoked()
    {
        // Ada is OWNER of resource R1
        // G1 is OWNER of resource R1
        // Betty is member of G1
        // Carol is member of G1
        // R1 (Ada:O, G1:O)
        [$userA, $userB, $userC] = UserFactory::make(3)->persist();
        $g1 = GroupFactory::make()->withGroupsManagersFor([$userB, $userC])->persist();
        /** @var \App\Model\Entity\Resource $r1 */
        $r1 = ResourceFactory::make()->withFoldersRelationsFor([$userA, $userB, $userC])->withPermissionsFor([$userA, $g1])->persist();

        /** @var \App\Model\Entity\Permission $permission */
        $permission = $r1->permissions[1];
        $this->permissionsTable->delete($permission);
        $this->service->afterAccessRevoked($this->makeUac($userA), $permission);

        $this->assertItemIsInTrees($r1->id, 1);
        $this->assertFolderRelation($r1->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, null);
    }
}
