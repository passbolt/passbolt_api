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
 * @since         3.8.0
 */
namespace App\Utility\Validation;

class UserAgentValidation
{
    public const MIN_USER_AGENT_LENGTH = 1;
    public const MAX_USER_AGENT_LENGTH = 1024; // Anything beyond this is weird

    /**
     * @param mixed $data a string is expected
     * @return bool
     */
    public static function isValid($data): bool
    {
        if (!isset($data)) {
            return false;
        }
        if (!is_string($data)) {
            return false;
        }
        if (empty($data)) {
            return false;
        }
        if (mb_strlen($data) > self::MAX_USER_AGENT_LENGTH) {
            return false;
        }
        if (mb_strlen($data) < self::MIN_USER_AGENT_LENGTH) {
            return false;
        }

        return true;
    }
}
