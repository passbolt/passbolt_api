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
 * @since         5.18.0
 */

namespace App\Service\Database;

use PDOException;
use Throwable;

class DatabaseLockErrorDetector
{
    /**
     * 40001: serialization failure or MySQL deadlock, 40P01: Postgres deadlock, 55P03: Postgres lock not available.
     */
    private const LOCK_ERROR_SQL_STATES = ['40001', '40P01', '55P03'];

    private const MYSQL_GENERAL_ERROR_SQL_STATE = 'HY000';

    private const MYSQL_LOCK_WAIT_TIMEOUT_DRIVER_CODE = 1205;

    /**
     * Check if the exception or one of its previous exceptions is a database deadlock or lock wait timeout.
     *
     * @param \Throwable $exception The exception to check.
     * @return bool
     */
    public function isLockError(Throwable $exception): bool
    {
        $sqlState = $this->getSqlState($exception);
        if ($sqlState === null) {
            return false;
        }

        if (in_array($sqlState, self::LOCK_ERROR_SQL_STATES, true)) {
            return true;
        }

        return $sqlState === self::MYSQL_GENERAL_ERROR_SQL_STATE
            && $this->getDriverCode($exception) === self::MYSQL_LOCK_WAIT_TIMEOUT_DRIVER_CODE;
    }

    /**
     * @param \Throwable $exception The exception to inspect.
     * @return string|null The SQLSTATE code, for example "40001", or null if not found.
     */
    public function getSqlState(Throwable $exception): ?string
    {
        $sqlState = $this->findPdoErrorInfo($exception)[0] ?? null;

        return is_string($sqlState) ? $sqlState : null;
    }

    /**
     * @param \Throwable $exception The exception to inspect.
     * @return int|null The database driver error code, for example 1213, or null if not found.
     */
    public function getDriverCode(Throwable $exception): ?int
    {
        $driverCode = $this->findPdoErrorInfo($exception)[1] ?? null;

        return is_int($driverCode) ? $driverCode : null;
    }

    /**
     * @param \Throwable $exception The exception to inspect.
     * @return array The PDO error info [SQLSTATE, driver code, driver message], or an empty array.
     */
    private function findPdoErrorInfo(Throwable $exception): array
    {
        $current = $exception;
        while ($current !== null) {
            if ($current instanceof PDOException && is_array($current->errorInfo) && $current->errorInfo !== []) {
                return $current->errorInfo;
            }
            $current = $current->getPrevious();
        }

        return [];
    }
}
