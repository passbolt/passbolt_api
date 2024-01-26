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
 * @since         4.5.0
 */

namespace App\Service\Resources;

/**
 * Class PasswordExpiryValidationServiceInterface.
 */
interface PasswordExpiryValidationServiceInterface
{
    public const PASSWORD_EXPIRED_DATE = 'expired';

    /**
     * True if the password expiry settings are enabled and
     * password automatically expire on permission losses by a user
     *
     * @return bool
     */
    public function isExpiryAutomatic(): bool;
}
