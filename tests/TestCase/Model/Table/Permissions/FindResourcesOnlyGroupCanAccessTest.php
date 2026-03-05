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

namespace App\Test\TestCase\Model\Table\Permissions;

use App\Model\Table\PermissionsTable;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\ORM\TableRegistry;

class FindResourcesOnlyGroupCanAccessTest extends AppTestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PermissionsTable
     */
    public $Permissions;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->Permissions = TableRegistry::getTableLocator()->get('Permissions');
    }

    public function testFindOnlyGroupCanAccessSuccess()
    {
        // Group C is sole owner of Resource C
        $groupC = GroupFactory::make()->persist();
        $resourceC = ResourceFactory::make()->withPermissionsFor([$groupC])->persist();
        $resources = $this->Permissions->findAcosOnlyAroCanAccess(PermissionsTable::RESOURCE_ACO, $groupC->id)->all()->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 1);
        $this->assertEquals($resources[0], $resourceC->id);

        // Group D is sole owner of Resource D
        $groupD = GroupFactory::make()->persist();
        $resourceD = ResourceFactory::make()->withPermissionsFor([$groupD])->persist();
        $resources = $this->Permissions->findAcosOnlyAroCanAccess(PermissionsTable::RESOURCE_ACO, $groupD->id)->all()->extract('aco_foreign_key')->toArray();
        $this->assertEquals(count($resources), 1);
        $this->assertEquals($resources[0], $resourceD->id);
    }

    public function testFindOnlyGroupCanAccessNoResult()
    {
        // Group F not owner of anything
        $groupF = GroupFactory::make()->persist();
        $resources = $this->Permissions->findAcosOnlyAroCanAccess(PermissionsTable::RESOURCE_ACO, $groupF->id)->all()->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);

        // Group E is owner of some resources but never sole owner
        $groupE = GroupFactory::make()->persist();
        $user = UserFactory::make()->persist();
        ResourceFactory::make(3)->withPermissionsFor([$groupE, $user])->persist();
        $resources = $this->Permissions->findAcosOnlyAroCanAccess(PermissionsTable::RESOURCE_ACO, $groupE->id)->all()->extract('aco_foreign_key')->toArray();
        $this->assertEmpty($resources);
    }
}
