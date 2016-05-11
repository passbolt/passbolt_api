<?php

/**
 * @license Apache 2.0
 */

namespace Swagger;

use Closure;
use Exception;

/**
 * Logger reports the parser and validation messages.
 */
class Logger
{
    /**
     * Singleton
     * @var Logger
     */
    public static $instance;

    /**
     * @var Closure
     */
    public $log;

    protected function __construct()
    {
        /**
         * @param \Exception|string $entry
         * @param int $type Error type
         */
        $this->log = function ($entry, $type) {
            if ($entry instanceof Exception) {
                $entry = $entry->getMessage();
            }
            trigger_error($entry, $type);
        };
    }

    /**
     * @return Logger
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Logger();
        }
        return self::$instance;
    }

    /**
     * Log a Swagger warning.
     * @param Exception|string $entry
     */
    public static function warning($entry)
    {
        call_user_func(self::getInstance()->log, $entry, E_USER_WARNING);
    }

    /**
     * Log a Swagger notice.
     * @param Exception|string $entry
     */
    public static function notice($entry)
    {
        call_user_func(self::getInstance()->log, $entry, E_USER_NOTICE);
    }
}
