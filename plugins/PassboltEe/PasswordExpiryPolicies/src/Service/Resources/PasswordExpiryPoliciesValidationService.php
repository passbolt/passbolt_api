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

namespace Passbolt\PasswordExpiryPolicies\Service\Resources;

use Cake\Http\Exception\BadRequestException;
use Cake\I18n\FrozenTime;
use Passbolt\PasswordExpiry\Service\Resources\PasswordExpiryValidationService;

/**
 * Class PasswordExpiryPoliciesValidationService.
 *
 * Enable the expiry date to be null or a date in the future
 */
class PasswordExpiryPoliciesValidationService extends PasswordExpiryValidationService
{
    /**
     * Expiry date must be in the future, or null
     *
     * @param ?string $expiryDate Expiry date
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException if the expiration date provided is not in the future
     * @throws \Cake\Http\Exception\BadRequestException if the expiration date provided is parsable
     */
    protected function validateValue(?string $expiryDate): void
    {
        if (is_null($expiryDate)) {
            return;
        }

        try {
            $parsedExpiryDate = FrozenTime::parse($expiryDate);
        } catch (\Throwable $e) {
            $parsedExpiryDate = null;
        }
        if (is_null($parsedExpiryDate)) {
            throw new BadRequestException(__('The expiration date should be null or a valid datetime.'));
        }
    }
}
