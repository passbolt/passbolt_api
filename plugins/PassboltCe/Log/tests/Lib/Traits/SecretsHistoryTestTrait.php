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

trait SecretsHistoryTestTrait
{
    public function assertSecretHistoryExists($conditions)
    {
        $secretHistory = $this->SecretsHistory
            ->find()
            ->where($conditions)
            ->first();
        $this->assertNotEmpty($secretHistory, 'No corresponding secretsHistory could be found');

        return $secretHistory;
    }

    public function assertSecretsHistoryCount($expectedCount)
    {
        $count = $this->SecretsHistory
            ->find()
            ->count();
        $this->assertEquals($expectedCount, $count);
    }
}
