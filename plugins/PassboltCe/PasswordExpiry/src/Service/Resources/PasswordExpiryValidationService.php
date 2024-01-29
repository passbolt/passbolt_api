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
     * @inheritDoc
     */
    public function isExpiryAutomatic(): bool
    {
        return $this->settingsService->get()->isExpiryAutomatic();
    }
}
