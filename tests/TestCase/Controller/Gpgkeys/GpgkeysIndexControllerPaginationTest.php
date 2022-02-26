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
 * @since         3.4.0
 */

namespace App\Test\TestCase\Controller\Gpgkeys;

use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Utility\PaginationTestTrait;

class GpgkeysIndexControllerPaginationTest extends AppIntegrationTestCase
{
    use PaginationTestTrait;

    public function dataProviderForSortingDirection(): array
    {
        return [
            ['Gpgkeys.key_id', 'asc', 'key_id'],
            ['Gpgkeys.key_id', 'desc', 'key_id'],
        ];
    }

    /**
     * Test the expected pagination information for the component's default
     * config.
     *
     * @Given I have 19 gpgkeys
     * @When I paginate on page 2 with 10 gpgkeys by page sorting by gpgkey name
     * @Then I should see 9 gpgkeys sorted according to $direction 'asc' resp. 'desc'.
     * @dataProvider dataProviderForSortingDirection
     * @param string|null $sortedField Sorted field.
     * @param string $direction Sorting direction.
     * @param string|null $path Path where to find the sorted field in the response data.
     * @return void
     * @throws \Exception
     */
    public function testGpgkeysIndexPagination(?string $sortedField = null, string $direction = 'asc', ?string $path = null)
    {
        // There is already one in the db by default
        $numberOfGpgkeys = 19 - 1;
        $limit = 10;
        $page = 2;
        $expectedCurrent = 9;

        $user = UserFactory::make()->user()->with('Gpgkeys')->persist();
        GpgkeyFactory::make($numberOfGpgkeys)->patchData(['user_id' => $user->id])->persist();
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

        $this->getJson("/gpgkeys.json?$paginationParameter&api-version=2");

        $this->assertSuccess();
        $this->assertCountPaginatedEntitiesEquals($expectedCurrent);
        $this->assertBodyContentIsSorted($path ?? 'name', $direction);
    }
}
