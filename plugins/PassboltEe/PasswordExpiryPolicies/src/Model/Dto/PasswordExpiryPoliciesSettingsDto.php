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
namespace Passbolt\PasswordExpiryPolicies\Model\Dto;

use Passbolt\PasswordExpiry\Model\Dto\PasswordExpirySettingsDto;

class PasswordExpiryPoliciesSettingsDto extends PasswordExpirySettingsDto
{
    /**
     * @inheritDoc
     */
    public function getValue(): array
    {
        return parent::getValue() + [
            self::EXPIRY_NOTIFICATION => $this->expiry_notification,
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getSettingsIfDisabled(): array
    {
        return parent::getSettingsIfDisabled() + [
                self::POLICY_OVERRIDE => false,
                self::DEFAULT_EXPIRY_PERIOD => null,
                self::EXPIRY_NOTIFICATION => null,
        ];
    }
}
