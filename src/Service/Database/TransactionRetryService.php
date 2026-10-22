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

use Cake\Database\Connection;
use Cake\Log\Log;
use Closure;
use InvalidArgumentException;
use Throwable;

class TransactionRetryService
{
    public const DEFAULT_MAX_ATTEMPTS = 3;

    private const RETRY_DELAY_IN_MICROSECONDS = 100000;

    /**
     * @var int
     */
    private int $maxAttempts;

    /**
     * @var \App\Service\Database\DatabaseLockErrorDetector
     */
    private DatabaseLockErrorDetector $lockErrorDetector;

    /**
     * @param int $maxAttempts Total number of attempts, including the first one.
     * @throws \InvalidArgumentException If the number of attempts is lower than 1.
     */
    public function __construct(int $maxAttempts = self::DEFAULT_MAX_ATTEMPTS)
    {
        if ($maxAttempts < 1) {
            throw new InvalidArgumentException('The maximum number of attempts should be at least 1.');
        }
        $this->maxAttempts = $maxAttempts;
        $this->lockErrorDetector = new DatabaseLockErrorDetector();
    }

    /**
     * Run the callback in a transaction and run it again if it fails on a database lock error.
     * No retry if a transaction is already open, the caller owns it.
     *
     * @template T of object
     * @param \Cake\Database\Connection $connection The connection.
     * @param \Closure(): T $callback The transaction body.
     * @return T The callback result.
     * @throws \Throwable The callback exception, if it is not a lock error or if all the attempts failed.
     */
    public function run(Connection $connection, Closure $callback): object
    {
        if ($connection->inTransaction()) {
            return $connection->transactional($callback);
        }

        for ($attempt = 1; $attempt < $this->maxAttempts; $attempt++) {
            try {
                return $connection->transactional($callback);
            } catch (Throwable $e) {
                if (!$this->lockErrorDetector->isLockError($e)) {
                    throw $e;
                }
                $this->logRetry($e, $attempt);
                usleep(self::RETRY_DELAY_IN_MICROSECONDS);
            }
        }

        return $connection->transactional($callback);
    }

    /**
     * @param \Throwable $e The lock error.
     * @param int $attempt The failed attempt number.
     * @return void
     */
    private function logRetry(Throwable $e, int $attempt): void
    {
        Log::warning(sprintf(
            'Database transaction attempt %d/%d failed on a lock error (SQLSTATE: %s, driver code: %s), retrying.',
            $attempt,
            $this->maxAttempts,
            $this->lockErrorDetector->getSqlState($e) ?? 'unknown',
            $this->lockErrorDetector->getDriverCode($e) ?? 'unknown',
        ));
    }
}
