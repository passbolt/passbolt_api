<?php
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

trait ActionLogsTrait
{
    public function assertActionLogExists($conditions)
    {
        $actionLog = $this->ActionLogs
            ->find()
            ->where($conditions)
            ->first();
        $this->assertNotEmpty($actionLog, 'No corresponding actionLog could be found');

        return $actionLog;
    }

    public function assertActionLogsCount($expectedCount)
    {
        $actionLogCount = $this->ActionLogs
            ->find()
            ->count();
        $this->assertEquals($expectedCount, $actionLogCount);
    }

    public function assertOneActionLog()
    {
        return $this->assertActionLogsCount(1);
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
