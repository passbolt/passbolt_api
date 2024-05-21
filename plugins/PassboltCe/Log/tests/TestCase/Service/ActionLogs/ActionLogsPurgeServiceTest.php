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
 * @since         4.8.0
 */

namespace Passbolt\Log\Test\TestCase\Service\ActionLogs;

use Cake\I18n\FrozenDate;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\Log\Service\ActionLogs\ActionLogsPurgeService;
use Passbolt\Log\Test\Factory\ActionLogFactory;
use Passbolt\Log\Test\Factory\EntitiesHistoryFactory;

class ActionLogsPurgeServiceTest extends TestCase
{
    use TruncateDirtyTables;

    public function dataForTest(): array
    {
        return [
            'dryRun' => [true],
            'purge' => [false],
        ];
    }

    /**
     * @dataProvider dataForTest
     */
    public function testActionLogsPurgeService(bool $isDryRun)
    {
        $service = new ActionLogsPurgeService();

        $actionsToDelete = $service->getActionList();
        // Limit the number of action logs persisted to 5 in order to speed up the test
        $randKeys = array_rand($actionsToDelete, 5);
        $totalCountToDelete = 0;
        $totalCountToIgnore = rand(2, 5);
        $entitiesHistoryCount = 0;
        $retentionPeriodInDays = 30;
        ActionLogFactory::make($totalCountToIgnore)->persist();
        foreach ($randKeys as $k) {
            $action = $actionsToDelete[$k];
            $toDelete = rand(1, 5);
            ActionLogFactory::make($toDelete)
                ->setActionId($action)
                ->created(FrozenDate::now()->subDays($retentionPeriodInDays + $toDelete))
                ->persist();
            $totalCountToDelete += $toDelete;

            // Ignore actions within retention period
            $toIgnore = rand(1, 5);
            ActionLogFactory::make($toIgnore)
                ->setActionId($action)
                ->created(FrozenDate::now()->subDays($toIgnore))
                ->persist();
            $totalCountToIgnore += $toIgnore;

            // Skip actions associated to some entity history
            ActionLogFactory::make()
                ->setActionId($action)
                ->created(FrozenDate::now()->subDays($retentionPeriodInDays + $toDelete))
                ->with('EntitiesHistory')
                ->persist();
            $totalCountToIgnore++;
            $entitiesHistoryCount++;
        }

        if ($isDryRun) {
            $result = $service->dryRun($retentionPeriodInDays);
            $expectedCount = count($randKeys) + 1; // Add one as we add a line with the total
            $this->assertSame($expectedCount, $result->all()->count());
            $this->assertSame($totalCountToDelete + $totalCountToIgnore, ActionLogFactory::count());
        } else {
            $result = $service->purge($retentionPeriodInDays);
            $this->assertSame($result, $totalCountToDelete);
            $this->assertSame($totalCountToIgnore, ActionLogFactory::count());
        }
        $this->assertSame($entitiesHistoryCount, EntitiesHistoryFactory::count());
    }
}
