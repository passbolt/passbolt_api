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
namespace Passbolt\Log\Test\TestCase\Model\Traits;

trait EntitiesHistoryTrait
{
    public function assertEntityHistoryExists($conditions)
    {
        $entityHistory = $this->EntitiesHistory
            ->find()
            ->where($conditions)
            ->first();

        $this->assertNotEmpty($entityHistory, 'No corresponding entityHistory could be found');

        return $entityHistory;
    }

    public function assertEntitiesHistoryCount($count, $conditions = [])
    {
        $entityHistoryCount = $this->EntitiesHistory
            ->find()
            ->where($conditions)
            ->count();

        $this->assertEquals($entityHistoryCount, $count);
    }

    public function assertOneEntityHistory($conditions = [])
    {
        return $this->assertEntitiesHistoryCount(1, $conditions);
    }

    public function assertEntitiesHistoryEmpty()
    {
        return $this->assertEntitiesHistoryCount(0);
    }
}
