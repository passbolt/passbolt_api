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
 * @since         4.6.0
 */

namespace App\Log\Formatter;

use Cake\Log\Formatter\AbstractFormatter;
use Cake\Log\Formatter\JsonFormatter;

class JsonTraceFormatter extends JsonFormatter
{
    public const TRACE = 'trace';

    /**
     * @inheritDoc
     */
    public function format($level, string $message, array $context = []): string
    {
        $trace = $context[self::TRACE] ?? null;
        if (!is_string($trace)) {
            return parent::format($level, $message, $context);
        }

        $log = [
            'date' => date(DATE_ATOM),
            'level' => (string)$level,
            'message' => $message,
        ];

        $trace = explode(PHP_EOL, $trace);
        $log[self::TRACE] = $trace;

        return json_encode($log);
    }
}
