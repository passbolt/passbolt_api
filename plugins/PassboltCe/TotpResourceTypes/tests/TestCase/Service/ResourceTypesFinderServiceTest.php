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

        $resourceTypes = $resourceTypes->all()->toArray();

        $this->assertCount(1, $resourceTypes);
        $this->assertSame($resourceTypeDeletedId, $resourceTypes[0]['id']);
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

        $resourceTypes = $resourceTypes->all()->toArray();

        $this->assertCount(1, $resourceTypes);
        $this->assertSame($resourceTypeNotDeletedId, $resourceTypes[0]['id']);
    }

    public function testResourceTypesFinderService_No_Filter()
    {
        ResourceTypeFactory::make()->deleted()->persist();
        $resourceTypeNotDeletedId = ResourceTypeFactory::make()->persist()->get('id');
        $resourceTypes = $this->service->find();

        $options = [];
        $this->service->filter($resourceTypes, $options);

        $resourceTypes = $resourceTypes->all()->toArray();

        $this->assertCount(1, $resourceTypes);
        $this->assertSame($resourceTypeNotDeletedId, $resourceTypes[0]['id']);
    }

    public function testResourceTypesFinderService_Contain_Resources_Count()
    {
        [$resourceType1, $resourceType2, $resourceType3] = ResourceTypeFactory::make(3)->persist();
        ResourceFactory::make(2)->with('ResourceTypes', $resourceType1)->persist();
        ResourceFactory::make(3)->with('ResourceTypes', $resourceType2)->persist();
        ResourceFactory::make()->deleted()->with('ResourceTypes', $resourceType3)->persist();

        $resourceTypes = $this->service->find();

        $options = [
            'contain' => ['resources_count' => 1],
        ];
        $this->service->contain($resourceTypes, $options);
        $resourceTypes = $resourceTypes->all()->toArray();
        $this->assertCount(3, $resourceTypes);
        foreach ($resourceTypes as $resourceType) {
            if ($resourceType['id'] === $resourceType1->get('id')) {
                $this->assertSame(2, $resourceType['resources_count']);
            } elseif ($resourceType['id'] === $resourceType2->get('id')) {
                $this->assertSame(3, $resourceType['resources_count']);
            } else {
                $this->assertSame(0, $resourceType['resources_count']);
            }
        }
    }
}
