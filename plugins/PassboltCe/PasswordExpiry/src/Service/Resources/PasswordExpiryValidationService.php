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
use Cake\Http\Exception\BadRequestException;
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
     * @param array $data Payload
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException if the plugin is not enabled. If false, only future dates are valid
     */
    final public function validateAndParseExpiryDate(array &$data): void
    {
        if (!array_key_exists(self::PASSWORD_EXPIRED_DATE, $data)) {
            return;
        }
        $dto = $this->settingsService->get();
        if (!$dto->isSettingsEnabled()) {
            throw new BadRequestException(__('Password expiry is not enabled.'));
        }
        $expiryDate = $data[self::PASSWORD_EXPIRED_DATE];
        $this->validateValue($expiryDate);
        if (!is_null($expiryDate)) {
            $data[self::PASSWORD_EXPIRED_DATE] = FrozenTime::parse($expiryDate);
        }
    }

    /**
     * Expiry date must be null
     *
     * @param ?string $expiryDate Expiry date
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException if the expiration date provided is not null
     */
    protected function validateValue(?string $expiryDate): void
    {
        if (!is_null($expiryDate)) {
            throw new BadRequestException(__('The expiration date should be null.'));
        }
    }

    /**
     * @inheritDoc
     */
    public function isExpiryAutomatic(): bool
    {
        return $this->settingsService->get()->isExpiryAutomatic();
    }
}
