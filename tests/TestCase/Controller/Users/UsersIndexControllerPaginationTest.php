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

namespace App\Test\TestCase\Controller\Users;

use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Utility\PaginationTestTrait;

class UsersIndexControllerPaginationTest extends AppIntegrationTestCase
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
        $this->defaultSortField = 'Profiles.first_name';
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
            ['Profiles.first_name', 'asc', 'profile.first_name'],
            ['Profiles.first_name', 'desc', 'profile.first_name'],
            ['Profiles.created', 'asc', 'profile.created'],
            ['Profiles.created', 'desc', 'profile.created'],
            ['Users.username', 'asc', 'username'],
            ['Users.username', 'desc', 'username'],
            ['Users.last_logged_in', 'asc', 'last_logged_in'],
            ['Users.last_logged_in', 'desc', 'last_logged_in'],
        ];
    }

    /**
     * Test the expected pagination information for users index page
     *
     * @Given I have 19 users
     * @When I paginate on page 2 with 10 users by page sorting by resource name
     * @Then I should see 9 users sorted according to $direction 'asc' resp. 'desc'.
     * @dataProvider dataProviderForSortingDirection
     * @param string|null $sortedField Sorted field.
     * @param string $direction Sorting direction.
     * @param string $path Path where to find the sorted field in the response data.
     * @return void
     * @throws \Exception
     */
    public function testUsersIndexPagination(?string $sortedField = null, string $direction = 'asc', string $path = 'username')
    {
        $numberOfUsers = 19;
        $limit = 10;
        $page = 2;
        $expectedCurrent = 9;

        $admin = UserFactory::make()->admin()->withLogIn(3)->persist();
        UserFactory::make($numberOfUsers - 1)
            ->user()
            ->with('Profiles')
            ->withLogIn(3)
            ->persist();
        UserFactory::make(2)->guest()->persist();

        $this->logInAs($admin);

        $paginationParameter = [
            'limit=' . $limit,
            'direction=' . $direction,
            'page=' . $page,
        ];
        if ($sortedField === 'Users.last_logged_in') {
            $paginationParameter[] = 'contain[last_logged_in]=1';
        }

        // If the option sorted is defined and set to empty, no sorting will apply
        if ($sortedField) {
            $paginationParameter[] = 'sort=' . $sortedField;
        }

        $paginationParameter = implode('&', $paginationParameter);

        $this->getJson("/users.json?$paginationParameter&api-version=2");

        $this->assertSuccess();
        $this->assertCountPaginatedEntitiesEquals($expectedCurrent);
        $this->assertBodyContentIsSorted($path, $direction);
    }
}
