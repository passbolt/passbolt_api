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
namespace Passbolt\Scim\Authenticator;

use Authentication\Authenticator\Result;
use Authentication\Authenticator\ResultInterface;
use Authentication\Authenticator\TokenAuthenticator;
use Cake\Event\EventDispatcherTrait;
use Cake\Log\Log;
use Passbolt\Scim\Service\ScimGetSettingsService;
use Passbolt\Scim\Service\ScimTokenExpiryChecker;
use Psr\Http\Message\ServerRequestInterface;

class ScimTokenAuthenticator extends TokenAuthenticator
{
    use EventDispatcherTrait;

    /**
     * The key of the failure reason in the Result errors. WP3 reads this key.
     */
    public const RESULT_ERROR_DETAIL = 'detail';

    /**
     * Events
     */
    public const EVENT_TOKEN_EXPIRED = 'scim_authentication_token_expired';
    public const EVENT_TOKEN_NEAR_EXPIRY = 'scim_authentication_token_near_expiry';

    /**
     * Authenticate the request, then enforce the SCIM token expiry.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @return \Authentication\Authenticator\ResultInterface
     */
    public function authenticate(ServerRequestInterface $request): ResultInterface
    {
        $result = parent::authenticate($request);
        // A missing or a wrong token gives an invalid result. Return it without a change.
        if (!$result->isValid()) {
            return $result;
        }

        $scimConfig = (new ScimGetSettingsService())->getSettingsDecryptedValue();
        $checker = new ScimTokenExpiryChecker();

        $expired = $scimConfig['expired'];

        if ($checker->isExpired($scimConfig)) {
            return $this->rejectExpiredToken($checker, $result, $expired);
        }

        if ($checker->isNearExpiry($scimConfig)) {
            $this->dispatchEvent(self::EVENT_TOKEN_NEAR_EXPIRY, ['expired' => $expired]);
        }

        return $result;
    }

    /**
     * Send the expired event, then fail or pass by the enforcement flag.
     *
     * @param \Passbolt\Scim\Service\ScimTokenExpiryChecker $checker The expiry checker.
     * @param \Authentication\Authenticator\ResultInterface $successResult Result object.
     * @param string|null $expired The token expiry date.
     * @return \Authentication\Authenticator\ResultInterface
     */
    private function rejectExpiredToken(
        ScimTokenExpiryChecker $checker,
        ResultInterface $successResult,
        ?string $expired,
    ): ResultInterface {
        // Notify the admins even when enforcement is off.
        $this->dispatchEvent(self::EVENT_TOKEN_EXPIRED, ['expired' => $expired]);

        if ($checker->isEnforced()) {
            return new Result(null, Result::FAILURE_OTHER, [
                self::RESULT_ERROR_DETAIL => __('The SCIM secret token is expired.'),
            ]);
        }

        Log::warning(__('The SCIM secret token is expired, you are requested to rotate it.'));

        return $successResult;
    }
}
