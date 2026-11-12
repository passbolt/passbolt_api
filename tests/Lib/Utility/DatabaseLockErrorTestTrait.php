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
 * @since         5.17.0
 */

namespace App\Test\Lib\Utility;

use Cake\Database\Exception\QueryException;
use PDOException;

trait DatabaseLockErrorTestTrait
{
    /**
     * Build a query exception as thrown by CakePHP, wrapping a PDO exception with the given error info.
     *
     * @param array $errorInfo [SQLSTATE, driver code, driver message]
     * @return \Cake\Database\Exception\QueryException
     */
    protected function makeQueryException(array $errorInfo): QueryException
    {
        $pdoException = new PDOException($errorInfo[2]);
        $pdoException->errorInfo = $errorInfo;

        return new QueryException('SELECT 1', $pdoException);
    }

    /**
     * @return \Cake\Database\Exception\QueryException A MySQL / MariaDB deadlock.
     */
    protected function makeMysqlDeadlockException(): QueryException
    {
        return $this->makeQueryException(['40001', 1213, 'Deadlock found when trying to get lock']);
    }
}
