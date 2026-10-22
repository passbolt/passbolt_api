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

use App\Service\Database\DatabaseLockErrorDetector;
use App\Test\Lib\Utility\DatabaseLockErrorTestTrait;
use Cake\TestSuite\TestCase;
use PDOException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use RuntimeException;

#[CoversClass(DatabaseLockErrorDetector::class)]
class DatabaseLockErrorDetectorTest extends TestCase
{
    use DatabaseLockErrorTestTrait;

    private DatabaseLockErrorDetector $detector;

    public function setUp(): void
    {
        parent::setUp();
        $this->detector = new DatabaseLockErrorDetector();
    }

    /**
     * @return array<string, array{0: array{0: string, 1: int, 2: string}, 1: bool}>
     */
    public static function errorInfoProvider(): array
    {
        return [
            'mysql deadlock' => [['40001', 1213, 'Deadlock found when trying to get lock'], true],
            'mysql lock wait timeout' => [['HY000', 1205, 'Lock wait timeout exceeded'], true],
            'postgres deadlock' => [['40P01', 7, 'deadlock detected'], true],
            'postgres lock not available' => [['55P03', 7, 'could not obtain lock on row'], true],
            'postgres serialization failure' => [['40001', 7, 'could not serialize access'], true],
            'mysql server has gone away' => [['HY000', 2006, 'MySQL server has gone away'], false],
            'mysql duplicate entry' => [['23000', 1062, 'Duplicate entry'], false],
            'postgres unique violation' => [['23505', 7, 'duplicate key value violates unique constraint'], false],
            'postgres statement timeout' => [['57014', 7, 'canceling statement due to statement timeout'], false],
        ];
    }

    /**
     * @param array{0: string, 1: int, 2: string} $errorInfo
     */
    #[DataProvider('errorInfoProvider')]
    public function testDatabaseLockErrorDetector_IsLockError(array $errorInfo, bool $expected): void
    {
        $exception = $this->makeQueryException($errorInfo);

        $this->assertSame($expected, $this->detector->isLockError($exception));
    }

    public function testDatabaseLockErrorDetector_IsLockError_PdoExceptionNotWrapped(): void
    {
        $exception = new PDOException('Deadlock found when trying to get lock');
        $exception->errorInfo = ['40001', 1213, 'Deadlock found when trying to get lock'];

        $this->assertTrue($this->detector->isLockError($exception));
    }

    public function testDatabaseLockErrorDetector_IsLockError_LockErrorInPreviousOfAnotherException(): void
    {
        $exception = new RuntimeException('Something failed', 0, $this->makeMysqlDeadlockException());

        $this->assertTrue($this->detector->isLockError($exception));
    }

    public function testDatabaseLockErrorDetector_IsLockError_NotADatabaseError(): void
    {
        $this->assertFalse($this->detector->isLockError(new RuntimeException('Something failed')));
    }

    public function testDatabaseLockErrorDetector_IsLockError_PdoExceptionWithoutErrorInfo(): void
    {
        $this->assertFalse($this->detector->isLockError(new PDOException('Something failed')));
    }

    public function testDatabaseLockErrorDetector_GetSqlStateAndDriverCode(): void
    {
        $exception = $this->makeQueryException(['HY000', 1205, 'Lock wait timeout exceeded']);

        $this->assertSame('HY000', $this->detector->getSqlState($exception));
        $this->assertSame(1205, $this->detector->getDriverCode($exception));
    }

    public function testDatabaseLockErrorDetector_GetSqlStateAndDriverCode_NotFound(): void
    {
        $exception = new RuntimeException('Something failed');

        $this->assertNull($this->detector->getSqlState($exception));
        $this->assertNull($this->detector->getDriverCode($exception));
    }
}
