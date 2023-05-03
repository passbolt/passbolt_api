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
use Cake\Database\Expression\QueryExpression;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;

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

        $status = $isJwtAuth ? 0 : 1;

        $failedAttemptsCount = $actionLogsTable
            ->find()
            ->where(function (QueryExpression $exp, Query $q) use ($userId, $latestLoginDate, $status) {
                return $exp
                    ->eq('user_id', $userId)
                    ->eq('status', $status)
                    ->eq('action_id', UuidFactory::uuid('TotpVerifyPost.post'))
                    ->gt('created', $latestLoginDate);
            })
            ->count();

        // WARNING: Any value less than 1 means infinite number of attempts!
        if (Configure::read('passbolt.security.mfa.maxAttempts') < 1) {
            return false;
        }

        if ($incrementFailedAttempts) {
            $failedAttemptsCount++;
        }

        return $failedAttemptsCount > Configure::read('passbolt.security.mfa.maxAttempts');
    }
}
