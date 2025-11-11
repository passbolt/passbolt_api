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
 * @since         3.7.3
 */

namespace Passbolt\MultiFactorAuthentication\Service\MfaUserSettings;

use App\Model\Entity\User;
use App\Utility\UserAccessControl;
use Cake\Event\EventDispatcherTrait;
use Passbolt\MultiFactorAuthentication\Utility\MfaAccountSettings;

class MfaUserSettingsDeleteService
{
    use EventDispatcherTrait;

    public const MFA_USER_ACCOUNT_SETTINGS_DELETE_EVENT = 'mfa.user_account.settings.delete';

    /**
     * @param \App\Model\Entity\User $user
     * @param \App\Utility\UserAccessControl $uac
     * @return bool operation success
     */
    public function disableUserSettings(
        User $user,
        UserAccessControl $uac
    ): bool {
        $mfaSettings = MfaAccountSettings::get(new UserAccessControl($user->role->name, $user->id));

        if ($mfaSettings->getEnabledProviders()) {
            $mfaSettings->delete();
        }
        $this->dispatchSettingsDeletedEvent($user, $uac);

        return true;
    }

    /**
     * @param \App\Model\Entity\User $user user
     * @return void
     */
    private function dispatchSettingsDeletedEvent(User $user, UserAccessControl $uac): void
    {
        $eventData['target'] = $user;
        $eventData['uac'] = $uac;
        $this->dispatchEvent(self::MFA_USER_ACCOUNT_SETTINGS_DELETE_EVENT, $eventData);
    }
}
