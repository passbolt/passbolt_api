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
 * @since         5.3.0
 */

namespace App\Error;

use Cake\Error\ErrorLogger;
use Throwable;

class AppErrorLogger extends ErrorLogger
{
    /**
     * {@inheritDoc}
     *
     * Overwritten method to include stack traces for internal errors(500) in production mode('debug' => false).
     */
    protected function getMessage(Throwable $exception, bool $isPrevious = false, bool $includeTrace = false): string
    {
        if ($exception->getCode() === 500) {
            $includeTrace = true;
        }

        return parent::getMessage($exception, $isPrevious, $includeTrace);
    }
}
