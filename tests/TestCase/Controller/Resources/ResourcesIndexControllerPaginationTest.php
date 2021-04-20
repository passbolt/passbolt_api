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

namespace App\Test\TestCase\Controller\Resources;

use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Utility\PaginationTestTrait;

class ResourcesIndexControllerPaginationTest extends AppIntegrationTestCase
{
    use PaginationTestTrait;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->defaultSortField = 'Resources.name';
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->defaultSortField);
        unset($this->defaultSortDirection);
    }

    public function dataProviderForSortingDirection(): array
    {
        return [
            [],
            ['Resources.name', 'asc', 'name'],
            ['Resources.name', 'desc', 'name'],
            ['Resources.username', 'asc', 'username'],
            ['Resources.username', 'desc', 'username'],
            ['Resources.uri', 'asc', 'uri'],
            ['Resources.uri', 'desc', 'uri'],
            ['Resources.modified', 'asc', 'modified'],
            ['Resources.modified', 'desc', 'modified'],
        ];
    }

    /**
     * Test the expected pagination information for the component's default
     * config.
     *
     * @Given I have 19 resources
     * @When I paginate on page 2 with 10 resources by page sorting by resource name
     * @Then I should see 9 resources sorted according to $direction 'asc' resp. 'desc'.
     * @dataProvider dataProviderForSortingDirection
     * @param string|null $sortedField Sorted field.
     * @param string $direction Sorting direction.
     * @param string|null $path Path where to find the sorted field in the response data.
     * @return void
     * @throws \Exception
     */
    public function testResourcesIndexPagination(?string $sortedField = null, string $direction = 'asc', ?string $path = null)
    {
        $numberOfResources = 19;
        $limit = 10;
        $page = 2;
        $expectedCurrent = 9;

        $user = UserFactory::make()->user()->persist();
        ResourceFactory::make($numberOfResources)
            ->withCreatorAndPermission($user)
            ->with('Modifier')
            ->persist();
        $this->logInAs($user);

        $paginationParameter = [
            'limit=' . $limit,
            'direction=' . $direction,
            'page=' . $page,
        ];

        // If the option sorted is defined and set to empty, no sorting will apply
        if ($sortedField) {
            $paginationParameter[] = 'sort=' . $sortedField;
        }

        $paginationParameter = implode('&', $paginationParameter);

        $this->getJson("/resources.json?$paginationParameter&api-version=2");

        $this->assertSuccess();
        $this->assertCountPaginatedEntitiesEquals($expectedCurrent);
        $this->assertBodyContentIsSorted($path ?? 'name', $direction);
    }

    public function testResourcesIndexWithLegacyOrderPagination()
    {
        $numberOfResources = 11;
        $limit = 10;
        $expectedCurrent = $limit;

        $user = UserFactory::make()->user()->persist();
        ResourceFactory::make($numberOfResources)
            ->withCreatorAndPermission($user)
            ->with('Modifier')
            ->persist();
        $this->logInAs($user);

        $paginationParameter = [
            'limit=' . $limit,
            'order[]=Resource.modified DESC',
        ];

        $paginationParameter = implode('&', $paginationParameter);

        $this->getJson("/resources.json?$paginationParameter&api-version=2");

        $this->assertSuccess();
        $this->assertCountPaginatedEntitiesEquals($expectedCurrent);
        $this->assertBodyContentIsSorted('modified', 'desc');
    }

    public function testResourcesIndexWithLegacyOrderAndApiComponentPagination()
    {
        $numberOfResources = 11;
        $limit = 10;
        $expectedCurrent = $limit;

        $user = UserFactory::make()->user()->persist();
        ResourceFactory::make($numberOfResources)
            ->withCreatorAndPermission($user)
            ->with('Modifier')
            ->persist();
        $this->logInAs($user);

        $paginationParameter = [
            'limit=' . $limit,
            'order[]=Resource.modified DESC',
            'direction=asc',
            'sort=Resources.modified',
        ];

        $paginationParameter = implode('&', $paginationParameter);

        $this->getJson("/resources.json?$paginationParameter&api-version=2");

        $this->assertSuccess();
        $this->assertCountPaginatedEntitiesEquals($expectedCurrent);
        $this->assertBodyContentIsSorted('modified');
    }
}
