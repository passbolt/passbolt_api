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

namespace Passbolt\PasswordExpiryPolicies\Test\Factory;

use Passbolt\PasswordExpiry\Model\Dto\PasswordExpirySettingsDto;
use Passbolt\PasswordExpiry\Test\Factory\PasswordExpirySettingFactory;

/**
 * @method \Passbolt\PasswordExpiry\Model\Entity\PasswordExpirySetting|\Passbolt\PasswordExpiry\Model\Entity\PasswordExpirySetting[] persist()
 * @method \Passbolt\PasswordExpiry\Model\Entity\PasswordExpirySetting getEntity()
 * @method \Passbolt\PasswordExpiry\Model\Entity\PasswordExpirySetting[] getEntities()
 * @method static \Passbolt\PasswordExpiry\Model\Entity\PasswordExpirySetting get($primaryKey, array $options = [])
 * @see \Passbolt\PasswordExpiry\Model\Table\PasswordExpirySettingsTable
 */
class PasswordExpiryPoliciesSettingFactory extends PasswordExpirySettingFactory
{
    protected function getDefaultValidSettings(): array
    {
        return [
            PasswordExpirySettingsDto::AUTOMATIC_EXPIRY => true,
            PasswordExpirySettingsDto::AUTOMATIC_UPDATE => true,
            PasswordExpirySettingsDto::POLICY_OVERRIDE => true,
            PasswordExpirySettingsDto::DEFAULT_EXPIRY_PERIOD => $this->getFaker()->numberBetween(1, 100),
//            PasswordExpirySettingsDto::EXPIRY_NOTIFICATION => $this->getFaker()->numberBetween(1, 100),
        ];
    }

    public function automaticExpiryDisabled()
    {
        return $this->setField(
            'value.' . PasswordExpirySettingsDto::AUTOMATIC_EXPIRY,
            false
        );
    }
}
