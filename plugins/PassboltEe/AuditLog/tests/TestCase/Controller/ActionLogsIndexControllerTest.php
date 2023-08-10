<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         3.11.0
 */

namespace Passbolt\AuditLog\Test\TestCase\Controller;

use App\Test\Lib\Utility\PaginationTestTrait;
use App\Utility\UuidFactory;
use Cake\I18n\FrozenDate;
use Passbolt\Log\Test\Factory\ActionLogFactory;
use Passbolt\Log\Test\Lib\LogIntegrationTestCase;

/**
 * @uses \Passbolt\AuditLog\Controller\ActionLogsIndexController
 */
class ActionLogsIndexControllerTest extends LogIntegrationTestCase
{
    use PaginationTestTrait;

    public function testActionLogsIndexController_Pagination_And_Contain()
    {
        $numberOfLogs = 19;
        $limit = 10;
        $page = 2;
        $expectedCurrent = 9;

        ActionLogFactory::make($numberOfLogs)
            ->with('Users.Profiles.Avatars')
            ->persist();

        $queryParams = [
            'limit=' . $limit,
            'direction=asc',
            'page=' . $page,
            'sort=ActionLogs.created',
            'contain[user.profile]=1',
        ];
        $queryParams = implode('&', $queryParams);

        $this->logInAsAdmin();
        $this->getJson('actionlog/logs.json?' . $queryParams);
        $this->assertSuccess();
        $this->assertCountPaginatedEntitiesEquals($expectedCurrent);
        $this->assertBodyContentIsSorted('created');
        $body = $this->convertObjectToArrayRecursively($this->_responseJsonBody);
        $this->assertIsString($body[0]['user']['id']);
        $this->assertIsString($body[0]['user']['profile']['id']);
        $this->assertIsString($body[0]['user']['profile']['avatar']['id']);
    }

    public function testActionLogsIndexController_Filters()
    {
        // The test will filter logs from yesterday to tomorrow
        $from = FrozenDate::now()->subDays(1)->format('Y-m-d');
        $to = FrozenDate::now()->addDays(1)->format('Y-m-d');

        // The test will filter by this user
        $userIdFiltered = UuidFactory::uuid();
        $expectedCount = 3;
        // Log to retrieved (success, between yesterday and today, correct user ID)
        $expectedLogYesterday = ActionLogFactory::make([])->success()->userId($userIdFiltered)->created('yesterday')->persist();
        $expectedLogToday = ActionLogFactory::make([])->success()->userId($userIdFiltered)->created('today')->persist();
        $expectedLogTomorrow = ActionLogFactory::make([])->success()->userId($userIdFiltered)->created('tomorrow')->persist();

        // Wrong user ID
        ActionLogFactory::make()->success()->created('now')->persist();

        // Wrong status
        ActionLogFactory::make()->error()->userId($userIdFiltered)->created('now');

        // Before the filter date
        ActionLogFactory::make()->success()->userId($userIdFiltered)->created('-3 days')->persist();

        // After the filter date
        ActionLogFactory::make()->success()->userId($userIdFiltered)->created('+3 days')->persist();

        $queryParams = [
            'filter[created-after]=' . $from,
            'filter[created-before]=' . $to,
            'filter[has-users]=' . $userIdFiltered,
            'filter[is-success]=1',
        ];

        $queryParams = implode('&', $queryParams);

        $this->logInAsAdmin();
        $this->getJson('actionlog/logs.json?' . $queryParams);
        $this->assertSuccess();
        $this->assertCountPaginatedEntitiesEquals($expectedCount);
        $this->assertBodyContentIsSorted('created', 'desc');
        $body = $this->convertObjectToArrayRecursively($this->_responseJsonBody);

        // Assert the pagination order
        $this->assertSame($expectedLogTomorrow->get('id'), $body[0]['id']);
        $this->assertSame($expectedLogToday->get('id'), $body[1]['id']);
        $this->assertSame($expectedLogYesterday->get('id'), $body[2]['id']);
    }

    public function testActionLogsIndexController_Filters_From_After_Before()
    {
        $from = FrozenDate::parse('tomorrow')->format('Y-m-d');
        $to = FrozenDate::parse('yesterday')->format('Y-m-d');

        $queryParams = [
            'filter[created-after]=' . $from,
            'filter[created-before]=' . $to,
        ];

        $queryParams = implode('&', $queryParams);

        $this->logInAsAdmin();
        $this->getJson('actionlog/logs.json?' . $queryParams);
        $this->assertBadRequestError('The date created-after should be after the date created-before.');
    }

    public function testActionLogsIndexController_Non_Admin_Should_Not_Have_Access()
    {
        $this->logInAsUser();
        $this->getJson('actionlog/logs.json');
        $this->assertForbiddenError('Access restricted to administrators.');
    }
}
