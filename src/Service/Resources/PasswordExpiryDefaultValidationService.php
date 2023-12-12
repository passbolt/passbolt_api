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

use Cake\Http\Exception\BadRequestException;

/**
 * Class PasswordExpiryDefaultResourceService.
 *
 * By default, no validation is performed on the expiry date. No expiry date should be in the payload
 */
class PasswordExpiryDefaultValidationService implements PasswordExpiryValidationServiceInterface
{
    /**
     * @param array $data Payload
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException if the plugin is not enabled
     */
    public function validateAndParseExpiryDate(array &$data): void
    {
        if (isset($data[self::PASSWORD_EXPIRED_DATE])) {
            throw new BadRequestException(__('The password expiry plugin is not enabled.'));
        }
    }

    /**
     * @inheritDoc
     */
    public function isExpiryAutomatic(): bool
    {
        return false;
    }
}
