<?php
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
 * @since         2.13.0
 */

namespace App\Log;

use Cake\Log\Log as CakeLog;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

class DefaultLogger implements LoggerInterface
{
    use LoggerTrait;

    /**
     * @param mixed $level Level
     * @param string $message Message
     * @param array $context Context
     * @return bool|void
     */
    public function log($level, $message, array $context = [])
    {
        return CakeLog::write($level, $message, $context);
    }
}
