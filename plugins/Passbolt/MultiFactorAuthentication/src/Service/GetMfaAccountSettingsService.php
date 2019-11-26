<?php
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
use Passbolt\AccountSettings\Model\Entity\AccountSetting;
use Passbolt\MultiFactorAuthentication\Utility\EntityMapper\User\MfaEntityMapper;
use Passbolt\MultiFactorAuthentication\Utility\MfaAccountSettings;

class GetMfaAccountSettingsService
{
    /**
     * @param User $user User to get MfaSettings
     * @return MfaAccountSettings
     * @throws Exception
     */
    public function getSettingsForUser(User $user)
    {
        /** @var AccountSetting $mfaSettings */
        $mfaSettings = $user->get(MfaEntityMapper::MFA_SETTINGS_PROPERTY) ?? false;
        if (!$mfaSettings) {
            throw new Exception('Unable to retrieve MFA settings for user');
        }

        return new MfaAccountSettings(
            new UserAccessControl($user->role->name, $user->id),
            json_decode($mfaSettings->value, true)
        );
    }
}
