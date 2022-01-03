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
use Passbolt\MultiFactorAuthentication\Event\ClearMfaCookieInResponse;
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
     * @param \App\Authenticator\SessionIdentificationServiceInterface $sessionIdentificationService Session ID identifier
     * @return bool
     */
    public function isMfaCheckRequired(
        ServerRequest $request,
        MfaSettings $mfaSettings,
        UserAccessControl $uac,
        ?SessionIdentificationServiceInterface $sessionIdentificationService = null
    ): bool {
        // Mfa not enabled for org or user
        if (!$mfaSettings->hasEnabledProviders()) {
            return false;
        }

        // Mfa cookie is set and a valid token
        $mfa = $request->getCookie(MfaVerifiedCookie::MFA_COOKIE_ALIAS);
        if (isset($mfa)) {
            $isMfaCookieInvalid = !MfaVerifiedToken::check($uac, $mfa, $sessionIdentificationService, $request);

            // If the MFA Cookie is invalid, clear that cookie in the response
            if ($isMfaCookieInvalid) {
                EventManager::instance()->on(new ClearMfaCookieInResponse());
            }

            return $isMfaCookieInvalid;
        }

        return true;
    }
}
