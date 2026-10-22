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
 * @since         5.17.0
 */

namespace Passbolt\MultiFactorAuthentication\Service;

use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\Role;
use App\Model\Entity\User;
use Cake\Core\Configure;
use Cake\Event\EventDispatcherTrait;
use Cake\Log\Log;
use Cake\ORM\Locator\LocatorAwareTrait;

class MfaRateLimitDisableUserService
{
    use EventDispatcherTrait;
    use LocatorAwareTrait;

    public const CONFIG_KEY = 'passbolt.security.mfa.disableUserOnRateLimit';
    public const EVENT_USER_WAS_DISABLED_ON_MFA_RATE_LIMIT = 'Service.MfaRateLimit.userWasDisabled';

    /**
     * Disables the user account after too many failed MFA attempts.
     *
     * @param string $userId User identifier.
     * @return bool Returns `true` if the user was disabled, `false` otherwise.
     */
    public function disable(string $userId): bool
    {
        if (!Configure::read(self::CONFIG_KEY, false)) {
            return false;
        }

        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = $this->fetchTable('Users');
        /** @var \App\Model\Entity\User|null $user */
        $user = $usersTable
            ->find('activeNotDeletedNotDisabledContainRole')
            ->where(['Users.id' => $userId])
            ->contain('Profiles')
            ->first();
        if ($user === null) {
            return false;
        }

        if ($this->isLastAdministrator($user)) {
            $msg = sprintf('The MFA failed attempts limit was reached for the last administrator %s.', $user->id);
            $msg .= ' ' . 'The account was not disabled to keep the organization administrable.';
            Log::warning($msg);

            return false;
        }

        $usersTable->disableUser($user);
        $this->deactivateRefreshTokens($userId);
        $this->dispatchEvent(self::EVENT_USER_WAS_DISABLED_ON_MFA_RATE_LIMIT, compact('user'), $this);

        return true;
    }

    /**
     * @param \App\Model\Entity\User $user User entity.
     * @return bool
     */
    private function isLastAdministrator(User $user): bool
    {
        if ($user->role->name !== Role::ADMIN) {
            return false;
        }

        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = $this->fetchTable('Users');

        return $usersTable
            ->findAdmins()
            ->find('notDisabled')
            ->where(['Users.id !=' => $user->id])
            ->all()
            ->isEmpty();
    }

    /**
     * @param string $userId User identifier.
     * @return void
     */
    private function deactivateRefreshTokens(string $userId): void
    {
        $this->fetchTable('AuthenticationTokens')->updateAll(
            ['active' => false],
            [
                'user_id' => $userId,
                'type' => AuthenticationToken::TYPE_REFRESH_TOKEN,
                'active' => true,
            ],
        );
    }
}
