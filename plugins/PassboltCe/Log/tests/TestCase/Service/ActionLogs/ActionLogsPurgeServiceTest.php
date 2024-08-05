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

use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\Core\Configure;
use Cake\I18n\FrozenDate;
use Passbolt\Log\Service\ActionLogs\ActionLogsCreateService;
use Passbolt\Log\Service\ActionLogs\ActionLogsPurgeService;
use Passbolt\Log\Test\Factory\ActionLogFactory;
use Passbolt\Log\Test\Factory\EntitiesHistoryFactory;
use Passbolt\Log\Test\Factory\SecretAccessFactory;

class ActionLogsPurgeServiceTest extends AppTestCase
{
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
            $result = $service->purge($retentionPeriodInDays, 1000);
            $this->assertSame($result, $totalCountToDelete);
            $this->assertSame($totalCountToIgnore, ActionLogFactory::count());
        }
        $this->assertSame($entitiesHistoryCount, EntitiesHistoryFactory::count());
    }

    public function testActionLogsPurgeService_SecretAccess()
    {
        $user = UserFactory::make()->user()->persist();
        $resource = ResourceFactory::make()
            ->withCreatorAndPermission($user)
            ->withSecretsFor([$user])
            ->persist();
        SecretAccessFactory::make(['user_id' => $user->get('id')])
            ->withResources(ResourceFactory::make($resource))
            ->persist();
        // before retention days
        [$toBeDeletedActionLog1, $toBeDeletedActionLog2] = ActionLogFactory::make(2)
            ->setActionId('ResourcesView.view')
            ->userId($user->get('id'))
            ->created(FrozenDate::now()->subMonths(6))
            ->persist();
        // after retention days
        $notToBeDeletedActionLog = ActionLogFactory::make(1)
            ->setActionId('ResourcesView.view')
            ->userId($user->get('id'))
            ->created(FrozenDate::now())
            ->persist();
        // Create entity histories related to action logs
        EntitiesHistoryFactory::make()
            ->withActionLog(ActionLogFactory::make($toBeDeletedActionLog1))
            ->withResource(ResourceFactory::make($resource))
            ->create()
            ->persist();
        EntitiesHistoryFactory::make()
            ->withActionLog(ActionLogFactory::make($toBeDeletedActionLog2))
            ->withResource(ResourceFactory::make($resource))
            ->create()
            ->persist();
        EntitiesHistoryFactory::make()
            ->withActionLog(ActionLogFactory::make($notToBeDeletedActionLog))
            ->withResource(ResourceFactory::make($resource))
            ->create()
            ->persist();

        $service = new ActionLogsPurgeService();
        $result = $service->purge(5, 100);

        // Make sure no entries are deleted from action logs, because resource contains entities history.
        $this->assertSame(0, $result);
        $this->assertSame(3, ActionLogFactory::count());
    }

    public function testActionLogsPurgeService_Get_Action_List()
    {
        Configure::write(ActionLogsCreateService::LOG_CONFIG_BLACKLIST_CONFIG_KEY, ['foo']);
        $actionList = (new ActionLogsPurgeService())->getActionList();
        $this->assertContains('foo', $actionList);
    }
}
