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
 * @since         3.3.0
 */

namespace Passbolt\MultiFactorAuthentication\Service;

use App\Authenticator\SessionIdentificationServiceInterface;
use App\Utility\UserAccessControl;
use Cake\Event\EventManager;
use Cake\Http\ServerRequest;
use Passbolt\MultiFactorAuthentication\Event\ClearInvalidMfaCookieInResponse;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedToken;

class IsMfaAuthenticationRequiredService
{
    /**
     * Check that the user has MFA Settings activated, and that
     * the provided MFA cookie is valid.
     *
     * If the MFA cookie is not valid, remove the cookie from the response.
     *
     * @param \Cake\Http\ServerRequest $request request
     * @param \Passbolt\MultiFactorAuthentication\Utility\MfaSettings $mfaSettings MFA settings
     * @param \App\Utility\UserAccessControl $uac User Access Controller
     * @param \App\Authenticator\SessionIdentificationServiceInterface $sessionIdentificationService Session ID service
     * @return bool
     */
    public function isMfaCheckRequired(
        ServerRequest $request,
        MfaSettings $mfaSettings,
        UserAccessControl $uac,
        SessionIdentificationServiceInterface $sessionIdentificationService
    ): bool {
        if ($this->isRouteWhiteListed($request)) {
            return false;
        }

        // Mfa not enabled for org or user
        $providers = $mfaSettings->getEnabledProviders();
        if (!count($providers)) {
            return false;
        }

        // Mfa cookie is set and a valid token
        $mfa = $request->getCookie(MfaVerifiedCookie::MFA_COOKIE_ALIAS);
        if (isset($mfa)) {
            $sessionId = $sessionIdentificationService->getSessionId($request);
            $isMfaCookieInvalid = !MfaVerifiedToken::check($uac, $mfa, $sessionId);

            // If the MFA Cookie is invalid, clear that cookie in the response
            if ($isMfaCookieInvalid) {
                EventManager::instance()->on(new ClearInvalidMfaCookieInResponse());
            }

            return $isMfaCookieInvalid;
        }

        return true;
    }

    /**
     * @param \Cake\Http\ServerRequest $request request
     * @return bool
     */
    protected function isRouteWhiteListed(ServerRequest $request): bool
    {
        // Do not redirect on mfa setup or check page
        // same goes for authentication pages
        $whitelistedPaths = [
            '/mfa/verify',
            '/auth/logout',
            '/logout',
        ];
        foreach ($whitelistedPaths as $path) {
            if (substr($request->getUri()->getPath(), 0, strlen($path)) === $path) {
                return true;
            }
        }

        return false;
    }
}
