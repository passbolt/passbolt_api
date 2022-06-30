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
 */
namespace Passbolt\Log\Test\Lib\Traits;

use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Passbolt\Log\Model\Entity\ActionLog;
use Passbolt\Log\Test\Factory\ActionLogFactory;

trait ActionLogsTestTrait
{
    /**
     * Add an action log
     *
     * @param array|null $data The data
     * @param array|null $options The options
     * @return ActionLog
     * @throws \Exception
     */
    public function addActionLog(?array $data = [], ?array $options = []): ActionLog
    {
        $actionLogsTable = TableRegistry::getTableLocator()->get('Passbolt/Log.ActionLogs');
        $actionLog = self::getDummyActionLogEntity($data, $options);

        $actionLogsTable->saveOrFail($actionLog);

        return $actionLog;
    }

    /**
     * Get a dummy action log entity
     *
     * @param array|null $data The data
     * @param array|null $options The options
     * @return ActionLog
     * @throws \Exception
     */
    public function getDummyActionLogEntity(?array $data = [], ?array $options = []): ActionLog
    {
        /** @var \Passbolt\Log\Model\Table\ActionLogsTable $actionLogsTable */
        $actionLogsTable = TableRegistry::getTableLocator()->get('Passbolt/Log.ActionLogs');
        $defaultOptions = [
            'checkRules' => true,
            'accessibleFields' => [
                '*' => true,
            ],
        ];

        $data = self::getDummyActionLogData($data);
        $options = array_merge($defaultOptions, $options);

        return $actionLogsTable->newEntity($data, $options);
    }

    /**
     * Get a dummy action log with test data.
     * The relation returned should pass a default validation.
     *
     * @param array|null $data Custom data that will be merged with the default content.
     * @return array
     */
    public function getDummyActionLogData(?array $data = []): array
    {
        $entityContent = [
            'user_id' => UuidFactory::uuid('user.id.test-place-holder'),
            'action_id' => UuidFactory::uuid('action.id.test-place-holder'),
            'context' => 'TEST PLACE HOlDER',
            'status' => 1,
            'created' => (new \DateTime())->format('Y-m-d H:i:s'),
        ];
        $entityContent = array_merge($entityContent, $data);

        return $entityContent;
    }

    public function assertActionLogExists($conditions)
    {
        $actionLog = ActionLogFactory::find()
            ->where($conditions)
            ->first();
        $this->assertNotEmpty($actionLog, 'No corresponding actionLog could be found');

        return $actionLog;
    }

    public function assertActionLogsCount($expectedCount)
    {
        $this->assertEquals($expectedCount, ActionLogFactory::count());
    }

    public function assertOneActionLog()
    {
        $this->assertActionLogsCount(1);
    }

    public function assertActionLogIdMatchesResponse($id, $response)
    {
        $this->assertEquals($id, $response->id, 'ActionLogId doesn\'t match response id');
    }

    public function assertActionLogsEmpty()
    {
        $this->assertActionLogsCount(0);
    }
}
