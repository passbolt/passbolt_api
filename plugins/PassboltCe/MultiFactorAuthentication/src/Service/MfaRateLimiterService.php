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
use Cake\ORM\Locator\LocatorAwareTrait;

class MfaRateLimiterService
{
    use LocatorAwareTrait;

    /**
     * Checks if given user has exceeded the failed attempts.
     *
     * @param string $userId User identifier.
     * @param bool $isJwtAuth Is authentication is JWT or session.
     * @param bool $incrementFailedAttempts If set to `true` increment failed attempts received from DB to +1.
     * @return bool Returns `true` if failed attempts exceeded, `false` otherwise.
     */
    public function isFailedAttemptsExceeded(
        string $userId,
        bool $isJwtAuth,
        bool $incrementFailedAttempts = false
    ): bool {
        // WARNING: Any value less than 1 means infinite number of attempts!
        if (Configure::read('passbolt.security.mfa.maxAttempts') < 1) {
            return false;
        }

        $actionLogsTable = $this->fetchTable('Passbolt/Log.ActionLogs');

        $actionId = UuidFactory::uuid('AuthLogin.loginPost');
        if ($isJwtAuth) {
            $actionId = UuidFactory::uuid('JwtLogin.loginPost');
        }

        // Subquery: Get last login of given user
        $latestLoginDate = $actionLogsTable
            ->find()
            ->select(['created'])
            ->where([
                'user_id' => $userId,
                'status' => 1,
                'action_id' => $actionId,
            ])
            ->orderDesc('created')
            ->limit(1);

        /**
         * This condition is required because for SESSION auth, we render HTML page with 200 status code with form errors,
         * but for JWT we are using JSON API and return 400 with errors.
         *
         * Example:
         * 1. SESSION auth(with Cookie), SUCCESS: No "TotpVerifyPost.post" action entry in action log.
         * 2. SESSION auth(with Cookie), FAIL ATTEMPT: Entry in action log with "TotpVerifyPost.post" action with successful(1) status.
         * 3. JWT auth(json request, without cookie), SUCCESS: Entry in action log with "TotpVerifyPost.post" action with success(1) status.
         * 4. JWT auth(json request, without cookie), FAIL ATTEMPT: Entry in action log with "TotpVerifyPost.post" action with error(0) status.
         */
        $status = $isJwtAuth ? 0 : 1;

        $failedAttemptsCount = $actionLogsTable
            ->find()
            ->where([
                'user_id' => $userId,
                'status' => $status,
                'action_id' => UuidFactory::uuid('TotpVerifyPost.post'),
                'created >' => $latestLoginDate,
            ])
            ->count();

        if ($incrementFailedAttempts) {
            $failedAttemptsCount++;
        }

        return $failedAttemptsCount > Configure::read('passbolt.security.mfa.maxAttempts');
    }
}
