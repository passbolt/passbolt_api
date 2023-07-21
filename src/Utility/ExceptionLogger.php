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
 * @since         3.11.1
 */
namespace App\Utility;

class ExceptionLogger
{
    /**
     * Logs an exception to the emergency log file, including the previous exceptions
     *
     * @param \Throwable $th Exception (chain) to log
     * @return void
     */
    public static function emergency(\Throwable $th): void
    {
        \Cake\Log\Log::emergency(self::getLogMessage($th));
    }

    /**
     * Logs an exception to the alert log file, including the previous exceptions
     *
     * @param \Throwable $th Exception (chain) to log
     * @return void
     */
    public static function alert(\Throwable $th): void
    {
        \Cake\Log\Log::alert(self::getLogMessage($th));
    }

    /**
     * Logs an exception to the critical log file, including the previous exceptions
     *
     * @param \Throwable $th Exception (chain) to log
     * @return void
     */
    public static function critical(\Throwable $th): void
    {
        \Cake\Log\Log::critical(self::getLogMessage($th));
    }

    /**
     * Logs an exception to the error log file, including the previous exceptions
     *
     * @param \Throwable $th Exception (chain) to log
     * @return void
     */
    public static function error(\Throwable $th): void
    {
        \Cake\Log\Log::error(self::getLogMessage($th));
    }

    /**
     * Logs an exception to the warning log file, including the previous exceptions
     *
     * @param \Throwable $th Exception (chain) to log
     * @return void
     */
    public static function warning(\Throwable $th): void
    {
        \Cake\Log\Log::warning(self::getLogMessage($th));
    }

    /**
     * Logs an exception to the notice log file, including the previous exceptions
     *
     * @param \Throwable $th Exception (chain) to log
     * @return void
     */
    public static function notice(\Throwable $th): void
    {
        \Cake\Log\Log::notice(self::getLogMessage($th));
    }

    /**
     * Logs an exception to the debug log file, including the previous exceptions
     *
     * @param \Throwable $th Exception (chain) to log
     * @return void
     */
    public static function debug(\Throwable $th): void
    {
        \Cake\Log\Log::debug(self::getLogMessage($th));
    }

    /**
     * Logs an exception to the info log file, including the previous exceptions
     *
     * @param \Throwable $th Exception (chain) to log
     * @return void
     */
    public static function info(\Throwable $th): void
    {
        \Cake\Log\Log::info(self::getLogMessage($th));
    }

    /**
     * @param \Throwable $th Exception (chain) to log
     * @param int $index Exception index in the chain/backtrace of exceptions to log
     * @return string The exception message to insert in the logs
     */
    private static function getLogMessage(\Throwable $th, int $index = 0): string
    {
        $errorMessage = '';
        if ($index === 0) {
            $errorMessage .= 'Exception Backtrace: ';
        }
        $errorMessage .= '[$index] ' . $th->getMessage();
        $index++;
        if (!is_null($th->getPrevious())) {
            $errorMessage .= '\n' . self::getLogMessage($th->getPrevious(), $index);
        }

        return $errorMessage;
    }
}
