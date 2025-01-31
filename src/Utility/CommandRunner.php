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
 * @since         4.11.0
 */
namespace App\Utility;

use Cake\Log\Log;
use Symfony\Component\Process\Process;

class CommandRunner
{
    /**
     * Runs a given command as a Symfony Process instance.
     *
     * @param array|string $command The command line to pass to the shell of the OS
     * @param string|null $cwd The working directory or null to use the working dir of the current PHP process
     * @param array|null $env The environment variables or null to use the same environment as the current PHP process
     * @param mixed $input The input as stream resource, scalar or \Traversable, or null for no input
     * @param float|null $timeout The timeout in seconds or null to disable
     * @return \Symfony\Component\Process\Process|false Returns a Symfony Process instance, or false in case of any exceptions.
     */
    public static function run(
        $command,
        ?string $cwd = null,
        ?array $env = null,
        $input = null,
        ?float $timeout = 60
    ) {
        try {
            if (is_array($command)) {
                $process = new Process($command, $cwd, $env, $input, $timeout);
            } else {
                $process = Process::fromShellCommandLine($command, $cwd, $env, $input, $timeout);
            }

            $process->run();
        } catch (\Exception $e) {
            $errorMessage = 'The command runner failed to run. Error: ' . $e->getMessage();
            Log::error($errorMessage);

            return false;
        }

        return $process;
    }
}
