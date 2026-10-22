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

namespace App\Test\TestCase\Service\Database;

use App\Service\Database\TransactionRetryService;
use App\Test\Lib\Utility\DatabaseLockErrorTestTrait;
use Cake\Database\Connection;
use Cake\TestSuite\TestCase;
use Closure;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use RuntimeException;
use stdClass;
use Throwable;

#[CoversClass(TransactionRetryService::class)]
class TransactionRetryServiceTest extends TestCase
{
    use DatabaseLockErrorTestTrait;

    private TransactionRetryService $service;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->service = new TransactionRetryService(3);
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testTransactionRetryService_Run_SuccessOnFirstAttempt(): void
    {
        $result = new stdClass();
        $calls = 0;

        $returned = $this->service->run($this->mockConnection(), function () use ($result, &$calls): stdClass {
            $calls++;

            return $result;
        });

        $this->assertSame($result, $returned);
        $this->assertSame(1, $calls);
    }

    public function testTransactionRetryService_Run_SuccessOnSecondAttemptAfterLockError(): void
    {
        $result = new stdClass();
        $calls = 0;

        $returned = $this->service->run($this->mockConnection(), function () use ($result, &$calls): stdClass {
            $calls++;
            if ($calls === 1) {
                throw $this->makeMysqlDeadlockException();
            }

            return $result;
        });

        $this->assertSame($result, $returned);
        $this->assertSame(2, $calls);
    }

    public function testTransactionRetryService_Run_RespectsMaxAttempts(): void
    {
        $service = new TransactionRetryService(2);
        $lockError = $this->makeMysqlDeadlockException();
        $calls = 0;

        try {
            $service->run($this->mockConnection(), function () use ($lockError, &$calls): stdClass {
                $calls++;
                throw $lockError;
            });
            $this->fail('An exception was expected.');
        } catch (Throwable $e) {
            $this->assertSame($lockError, $e);
        }

        $this->assertSame(2, $calls);
    }

    public function testTransactionRetryService_Run_RethrowsOriginalExceptionWhenAllAttemptsFail(): void
    {
        $lockError = $this->makeQueryException(['HY000', 1205, 'Lock wait timeout exceeded']);
        $calls = 0;

        $caught = $this->runAndCatch($this->mockConnection(), function () use ($lockError, &$calls): stdClass {
            $calls++;
            throw $lockError;
        });

        $this->assertSame($lockError, $caught);
        $this->assertSame(3, $calls);
    }

    public function testTransactionRetryService_Run_NoRetryOnOtherDatabaseError(): void
    {
        $otherError = $this->makeQueryException(['23000', 1062, 'Duplicate entry']);
        $calls = 0;

        $caught = $this->runAndCatch($this->mockConnection(), function () use ($otherError, &$calls): stdClass {
            $calls++;
            throw $otherError;
        });

        $this->assertSame($otherError, $caught);
        $this->assertSame(1, $calls);
    }

    public function testTransactionRetryService_Run_NoRetryOnNonDatabaseError(): void
    {
        $otherError = new RuntimeException('Something failed');
        $calls = 0;

        $caught = $this->runAndCatch($this->mockConnection(), function () use ($otherError, &$calls): stdClass {
            $calls++;
            throw $otherError;
        });

        $this->assertSame($otherError, $caught);
        $this->assertSame(1, $calls);
    }

    public function testTransactionRetryService_Run_NoRetryWhenTransactionAlreadyOpen(): void
    {
        $lockError = $this->makeMysqlDeadlockException();
        $calls = 0;

        $caught = $this->runAndCatch($this->mockConnection(true), function () use ($lockError, &$calls): stdClass {
            $calls++;
            throw $lockError;
        });

        $this->assertSame($lockError, $caught);
        $this->assertSame(1, $calls);
    }

    public function testTransactionRetryService_Construct_InvalidMaxAttempts(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new TransactionRetryService(0);
    }

    /**
     * @param bool $inTransaction Specify value to pass to the inTransaction() method.
     * @return \Cake\Database\Connection&\PHPUnit\Framework\MockObject\MockObject
     */
    private function mockConnection(bool $inTransaction = false): Connection&MockObject
    {
        $connection = $this->createMock(Connection::class);
        $connection->method('inTransaction')->willReturn($inTransaction);
        $connection->method('transactional')->willReturnCallback(function (Closure $callback): object {
            return $callback();
        });

        return $connection;
    }

    /**
     * @param \Cake\Database\Connection $connection The connection.
     * @param \Closure(): \stdClass $callback The callback to run.
     * @return \Throwable
     */
    private function runAndCatch(Connection $connection, Closure $callback): Throwable
    {
        try {
            $this->service->run($connection, $callback);
        } catch (Throwable $e) {
            return $e;
        }
        $this->fail('An exception was expected.');
    }
}
