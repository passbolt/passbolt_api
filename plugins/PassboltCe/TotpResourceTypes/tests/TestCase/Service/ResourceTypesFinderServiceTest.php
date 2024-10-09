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
 * @since         4.10.0
 */

namespace Passbolt\TotpResourceTypes\Test\TestCase\Service;

use App\Test\Factory\ResourceFactory;
use App\Test\Lib\AppTestCase;
use Passbolt\ResourceTypes\Service\ResourceTypesFinderService;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

/**
 * @covers \Passbolt\ResourceTypes\Service\ResourceTypesFinderService
 */
class ResourceTypesFinderServiceTest extends AppTestCase
{
    private ResourceTypesFinderService $service;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new ResourceTypesFinderService();
    }

    public function testResourceTypesFinderService_Filter_Deleted()
    {
        $resourceTypeDeletedId = ResourceTypeFactory::make()->deleted()->persist()->get('id');
        ResourceTypeFactory::make()->persist();
        $resourceTypes = $this->service->find();

        $options = [
            'filter' => ['is-deleted' => 1],
        ];
        $this->service->filter($resourceTypes, $options);

        $this->assertCount(1, $resourceTypes->all());
        $this->assertSame($resourceTypeDeletedId, $resourceTypes->firstOrFail()->get('id'));
    }

    public function testResourceTypesFinderService_Filter_Not_Deleted()
    {
        ResourceTypeFactory::make()->deleted()->persist();
        $resourceTypeNotDeletedId = ResourceTypeFactory::make()->persist()->get('id');
        $resourceTypes = $this->service->find();

        $options = [
            'filter' => ['is-deleted' => 0],
        ];
        $this->service->filter($resourceTypes, $options);

        $this->assertCount(1, $resourceTypes->all());
        $this->assertSame($resourceTypeNotDeletedId, $resourceTypes->firstOrFail()->get('id'));
    }

    public function testResourceTypesFinderService_Contain_Resources_Count()
    {
        [$resourceType1, $resourceType2, $resourceType3] = ResourceTypeFactory::make(3)->persist();
        ResourceFactory::make(2)->with('ResourceTypes', $resourceType1)->persist();
        ResourceFactory::make(3)->with('ResourceTypes', $resourceType2)->persist();

        $resourceTypes = $this->service->find();

        $options = [
            'contain' => ['resources_count' => 1],
        ];
        $this->service->contain($resourceTypes, $options);
        $resourceTypes = $resourceTypes->all();
        $this->assertCount(3, $resourceTypes);
        $resourceType1 = $resourceTypes->filter(function ($resourceType) use ($resourceType1) {
            return $resourceType->id === $resourceType1->get('id');
        })->first();
        $resourceType2 = $resourceTypes->filter(function ($resourceType) use ($resourceType2) {
            return $resourceType->id === $resourceType2->get('id');
        })->first();
        $resourceType3 = $resourceTypes->filter(function ($resourceType) use ($resourceType3) {
            return $resourceType->id === $resourceType3->get('id');
        })->first();
        $this->assertSame(2, $resourceType1->get('resources_count'));
        $this->assertSame(3, $resourceType2->get('resources_count'));
        $this->assertSame(0, $resourceType3->get('resources_count'));
    }
}
