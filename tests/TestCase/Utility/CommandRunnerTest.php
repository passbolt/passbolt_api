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
 * @since         5.11.0
 */
namespace App\Test\TestCase\Utility;

use App\Utility\CommandRunner;
use Cake\TestSuite\TestCase;
use Symfony\Component\Process\Process;

/**
 * @covers \App\Utility\CommandRunner
 */
class CommandRunnerTest extends TestCase
{
    public function testCommandRunner_Run_WithArrayCommand(): void
    {
        $result = CommandRunner::run(['echo', 'hello']);

        $this->assertInstanceOf(Process::class, $result);
        $this->assertTrue($result->isSuccessful());
        $this->assertStringContainsString('hello', $result->getOutput());
        $this->assertSame(60.0, $result->getTimeout());
    }

    public function testCommandRunner_Run_WithStringCommand(): void
    {
        $result = CommandRunner::run('echo "hello world" | cat');

        $this->assertInstanceOf(Process::class, $result);
        $this->assertTrue($result->isSuccessful());
        $this->assertStringContainsString('hello world', $result->getOutput());
    }

    public function testCommandRunner_Run_WithCustomCwd(): void
    {
        $result = CommandRunner::run(['pwd'], TMP);

        $this->assertInstanceOf(Process::class, $result);
        $this->assertTrue($result->isSuccessful());
        $this->assertStringContainsString(realpath(TMP), trim($result->getOutput()));
    }

    public function testCommandRunner_Run_WithProjectRootCwd(): void
    {
        $result = CommandRunner::run(['ls', 'bin/cake.php'], ROOT);

        $this->assertInstanceOf(Process::class, $result);
        $this->assertTrue($result->isSuccessful());
    }

    public function testCommandRunner_Run_WithInvalidCwdReturnsFalse(): void
    {
        $result = CommandRunner::run(['echo', 'hello'], '/nonexistent/directory/path');

        $this->assertFalse($result);
    }

    public function testCommandRunner_Run_WithFailingCommandReturnsProcess(): void
    {
        $result = CommandRunner::run(['ls', '/nonexistent/path/that/does/not/exist']);

        $this->assertInstanceOf(Process::class, $result);
        $this->assertFalse($result->isSuccessful());
    }

    public function testCommandRunner_Run_WithCustomEnvVariables(): void
    {
        $result = CommandRunner::run(['printenv', 'MY_TEST_VAR'], null, ['MY_TEST_VAR' => 'test_value']);

        $this->assertInstanceOf(Process::class, $result);
        $this->assertTrue($result->isSuccessful());
        $this->assertSame('test_value', trim($result->getOutput()));
    }
}
