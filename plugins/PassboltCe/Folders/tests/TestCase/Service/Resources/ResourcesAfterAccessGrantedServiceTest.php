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
use App\Test\Factory\PermissionFactory;
use App\Test\Factory\UserFactory;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Service\Resources\ResourcesAfterAccessGrantedService;
use Passbolt\Folders\Test\Factory\ResourceFactory;
use Passbolt\Folders\Test\Lib\FoldersTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;

/**
 * Passbolt\Folders\Service\Folders\ResourcesAfterAccessGrantedService Test Case
 *
 * @uses \Passbolt\Folders\Service\Resources\ResourcesAfterAccessGrantedService
 */
class ResourcesAfterAccessGrantedServiceTest extends FoldersTestCase
{
    use FoldersModelTrait;

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
        // Ada is OWNER of resource R1
        // R1 (Ada:O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        $r1 = ResourceFactory::make()->withFoldersRelationsFor([$userA])->withPermissionsFor([$userA])->persist();

        $permission = PermissionFactory::make()->typeOwner()->acoResource($r1)->aroUser($userB)->persist();
        $this->service->afterAccessGranted($this->makeUac($userA), $permission);

        $this->assertItemIsInTrees($r1->get('id'), 2);
        $this->assertFolderRelation($r1->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, null);
        $this->assertFolderRelation($r1->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userB->id, null);
    }

    public function testResourceAfterAccessGrantedSuccess_UserPermissionAdded_GroupUserAlreadyHavingAccess()
    {
        // Ada is OWNER of resource R1
        // R1 (Ada:O)
        // G1 is OWNER of resource R1
        /** @var \App\Model\Entity\User $userA */
        $userA = UserFactory::make()->persist();
        $g1 = GroupFactory::make()->withGroupsManagersFor([$userA])->persist();
        $r1 = ResourceFactory::make()->withFoldersRelationsFor([$userA])->withCreatorAndPermission($userA)->withPermissionsFor([$g1])->persist();

        $permission = PermissionFactory::make()->typeOwner()->acoResource($r1)->aroUser($userA)->persist();
        $this->service->afterAccessGranted($this->makeUac($userA), $permission);

        $this->assertItemIsInTrees($r1->get('id'), 1);
        $this->assertFolderRelation($r1->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, null);
    }

    public function testResourceAfterAccessGrantedSuccess_GroupPermissionAdded()
    {
        // Ada is OWNER of resource R1
        // G1 is OWNER of resource R1
        // R1 (Ada:O)
        [$userA, $userB, $userC] = UserFactory::make(3)->persist();
        $r1 = ResourceFactory::make()->withFoldersRelationsFor([$userA])->withPermissionsFor([$userA])->persist();
        $g1 = GroupFactory::make()->withGroupsManagersFor([$userB, $userC])->persist();

        $permission = PermissionFactory::make()->typeOwner()->acoResource($r1)->aroGroup($g1)->persist();
        $this->service->afterAccessGranted($this->makeUac($userA), $permission);

        $this->assertItemIsInTrees($r1->get('id'), 3);
        $this->assertFolderRelation($r1->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, null);
        $this->assertFolderRelation($r1->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userB->id, null);
        $this->assertFolderRelation($r1->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userC->id, null);
    }

    public function testResourceAfterAccessGrantedSuccess_GroupPermissionAdded_UserAlreadyHaveDirectAccess()
    {
        // Ada is OWNER of resource R1
        // G1 is OWNER of resource R1
        // R1 (Ada:O, G1:O)
        [$userA, $userB] = UserFactory::make(2)->persist();
        $r1 = ResourceFactory::make()->withFoldersRelationsFor([$userA])->withPermissionsFor([$userA])->persist();
        $g1 = GroupFactory::make()->withGroupsManagersFor([$userA, $userB])->persist();

        $permission = PermissionFactory::make()->typeOwner()->acoResource($r1)->aroGroup($g1)->persist();
        $this->service->afterAccessGranted($this->makeUac($userA), $permission);

        $this->assertItemIsInTrees($r1->get('id'), 2);
        $this->assertFolderRelation($r1->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->id, null);
        $this->assertFolderRelation($r1->get('id'), FoldersRelation::FOREIGN_MODEL_RESOURCE, $userB->id, null);
    }
}
