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
 * @since         3.10.0
 */

namespace Passbolt\MfaPolicies\Service;

use Cake\Core\Exception\Exception;
use Cake\Log\Log;
use Passbolt\MultiFactorAuthentication\Service\MfaPolicies\RememberAMonthSettingInterface;

class RememberAMonthSettingService implements RememberAMonthSettingInterface
{
    /**
     * @inheritDoc
     */
    public function isEnabled(): bool
    {
        try {
            $mfaPolicySettings = (new MfaPoliciesGetSettingsService())->get();
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return false;
        }

        return (bool)$mfaPolicySettings->remember_me_for_a_month;
    }
}
