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
 * @since         3.2.0
 */

namespace App\Test\TestCase\Controller\Component;

use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Utility\PaginationTestTrait;
use Cake\TestSuite\IntegrationTestTrait;

class ApiPaginationComponentIntegrationTest extends AppIntegrationTestCase
{
    use IntegrationTestTrait;
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
        $this->defaultSortDirection = 'asc';
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

    /**
     * Test the expected pagination information for the component's default
     * config.
     *
     * @Given I have 39 resources
     * @When I paginate on page 4 with 10 resources by page sorting by resource name
     * @Then I should see 9 resources sorted according to $direction 'asc' resp. 'desc'.
     * @return void
     * @throws \Exception
     */
    public function testDefaultPaginationSettings()
    {
        $numberOfResources = 39;
        $limit = 10;
        $page = 4;
        $expectedCurrent = 9;
        $direction = 'asc';
        $sortedField = 'Resources.modified';

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
        $this->assertCount(9, $this->_responseJsonBody);

        $expectedPagination = (object)[
            'count' => $numberOfResources,
            'page' => $page,
            'limit' => $limit,
        ];

        $this->assertEquals($expectedPagination, $this->_responseJsonPagination);
        $this->assertCountPaginatedEntitiesEquals($expectedCurrent);
        $this->assertBodyContentIsSorted('modified', $direction);
    }

    /**
     * @Given I have 1 resource
     * @When I paginate on page 2 with 10 resources by page
     * @Then the response will return an error.
     */
    public function testDataRequestedOutOfLimits(): void
    {
        $numberOfResources = 1;
        $limit = 10;
        $page = 2;

        $user = UserFactory::make()->user()->persist();
        ResourceFactory::make($numberOfResources)->withCreatorAndPermission($user)->persist();
        $this->logInAs($user);

        $paginationParameter = implode('&', [
            'limit=' . $limit,
            'page=' . $page,
        ]);

        $this->getJson("/resources.json?$paginationParameter&api-version=2");
        $this->assertResponseError();
        $this->assertResponseContains('Not Found');
    }

    /**
     * @Given I am logged in
     * @When I paginate on a non authorized field
     * @Then the response will return an error.
     */
    public function testSortOnNotAllowedField(): void
    {
        $this->logInAsUser();
        $this->getJson('/resources.json?sort=Resources.foo&api-version=2');
        $this->assertResponseError('Invalid order. "Resources.id" is not in the list of allowed order.');
    }
}
