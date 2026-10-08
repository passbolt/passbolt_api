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
 * @since         4.1.0
 */

namespace Passbolt\MultiFactorAuthentication\Service;

use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\I18n\DateTime;
use Cake\ORM\Locator\LocatorAwareTrait;

class MfaRateLimiterService
{
    use LocatorAwareTrait;

    private const SESSION_LOGIN_ACTION = 'AuthLogin.loginPost';
    private const JWT_LOGIN_ACTION = 'JwtLogin.loginPost';
    private const TOTP_VERIFY_ACTION = 'TotpVerifyPost.post';

    /**
     * Checks if given user has exceeded the failed attempts.
     *
     * @param string $userId User identifier.
     * @param bool $incrementFailedAttempts If set to `true` increment failed attempts received from DB to +1.
     * @return bool Returns `true` if failed attempts exceeded, `false` otherwise.
     */
    public function isFailedAttemptsExceeded(string $userId, bool $incrementFailedAttempts = false): bool
    {
        $maxAttempts = (int)Configure::read('passbolt.security.mfa.maxAttempts');
        // WARNING: Any value less than 1 means infinite number of attempts!
        if ($maxAttempts < 1) {
            return false;
        }

        $latestLoginDate = $this->findLatestLoginDate($userId);

        $conditions = [
            'user_id' => $userId,
            'action_id' => UuidFactory::uuid(self::TOTP_VERIFY_ACTION),
        ];
        if ($latestLoginDate !== null) {
            // Greater or equal, as the action log entries are stored with a one second precision.
            $conditions['created >='] = $latestLoginDate;
        }

        $failedAttemptsCount = $this->fetchTable('Passbolt/Log.ActionLogs')
            ->find()
            ->where($conditions)
            ->all()
            ->count();

        if ($incrementFailedAttempts) {
            $failedAttemptsCount++;
        }

        return $failedAttemptsCount > $maxAttempts;
    }

    /**
     * @param string $userId User identifier.
     * @return \Cake\I18n\DateTime|null Null when the user has no successful login entry.
     */
    private function findLatestLoginDate(string $userId): ?DateTime
    {
        /** @var \Passbolt\Log\Model\Entity\ActionLog|null $latestLogin */
        $latestLogin = $this->fetchTable('Passbolt/Log.ActionLogs')
            ->find()
            ->select(['created'])
            ->where([
                'user_id' => $userId,
                'status' => 1,
                'action_id IN' => [
                    UuidFactory::uuid(self::SESSION_LOGIN_ACTION),
                    UuidFactory::uuid(self::JWT_LOGIN_ACTION),
                ],
            ])
            ->orderByDesc('created')
            ->first();

        return $latestLogin?->created;
    }
}
