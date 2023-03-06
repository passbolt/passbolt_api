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
 * @since         2.12.0
 */

namespace Passbolt\MultiFactorAuthentication\Service;

use App\Model\Entity\User;
use App\Utility\UserAccessControl;
use Exception;
use Passbolt\MultiFactorAuthentication\Utility\MfaAccountSettings;

class GetMfaAccountSettingsService
{
    /**
     * @param \App\Model\Entity\User $user User to get MfaSettings
     * @return \Passbolt\MultiFactorAuthentication\Utility\MfaAccountSettings
     * @throws \Exception
     */
    public function getSettingsForUser(User $user): MfaAccountSettings
    {
        /** @var \Passbolt\AccountSettings\Model\Entity\AccountSetting $mfaSettings */
        $mfaSettings = $user->get(Query\IsMfaEnabledQueryService::MFA_SETTINGS_PROPERTY) ?? null;
        if (empty($mfaSettings)) {
            throw new Exception('Unable to retrieve MFA settings for user');
        }

        return new MfaAccountSettings(
            new UserAccessControl($user->role->name, $user->id),
            json_decode($mfaSettings->value, true)
        );
    }
}
