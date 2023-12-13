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

namespace Passbolt\PasswordExpiry\Service\Resources;

use App\Service\Resources\PasswordExpiryValidationServiceInterface;
use Cake\I18n\FrozenTime;
use Passbolt\PasswordExpiry\Service\Settings\PasswordExpiryGetSettingsServiceInterface;

/**
 * Class PasswordExpiryNullableValidationService.
 *
 * By default, no validation is performed on the expiry date. No expiry date should be in the payload
 */
class PasswordExpiryValidationService implements PasswordExpiryValidationServiceInterface
{
    protected PasswordExpiryGetSettingsServiceInterface $settingsService;

    /**
     * @param \Passbolt\PasswordExpiry\Service\Settings\PasswordExpiryGetSettingsServiceInterface $settingsService get setting service
     */
    public function __construct(PasswordExpiryGetSettingsServiceInterface $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    /**
     * Check if the expiry date is in the data provided in the payload
     * If the feature is not enabled, remove expiry date from the payload
     * If data is passed that are not compatible with the solution in place, e.g. a date when only null is accepte,
     * remove expiry date from the payload
     *
     * Finally parse the date passed in the payload into a date, or null
     *
     * @param array $data Payload
     * @return void
     */
    final public function validateAndParseExpiryDate(array &$data): void
    {
        if (!array_key_exists(self::PASSWORD_EXPIRED_DATE, $data)) {
            return;
        }
        $dto = $this->settingsService->get();
        if (!$dto->isSettingsEnabled()) {
            unset($data[self::PASSWORD_EXPIRED_DATE]);

            return;
        }
        $expiryDate = $data[self::PASSWORD_EXPIRED_DATE];
        $isDateValueValid = $this->isDateValueValid($expiryDate);
        if (!$isDateValueValid) {
            unset($data[self::PASSWORD_EXPIRED_DATE]);

            return;
        }
        if (!is_null($expiryDate)) {
            $data[self::PASSWORD_EXPIRED_DATE] = FrozenTime::parse($expiryDate);
        }
    }

    /**
     * Expiry date must be null
     *
     * @param ?string $expiryDate Expiry date
     * @return bool
     */
    protected function isDateValueValid(?string $expiryDate): bool
    {
        return is_null($expiryDate);
    }

    /**
     * @inheritDoc
     */
    public function isExpiryAutomatic(): bool
    {
        return $this->settingsService->get()->isExpiryAutomatic();
    }
}
