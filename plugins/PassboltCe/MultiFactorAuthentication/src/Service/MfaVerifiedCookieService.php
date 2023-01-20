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
 * @since         3.11.0
 */

namespace Passbolt\MultiFactorAuthentication\Service;

use App\Authenticator\SessionIdentificationServiceInterface;
use App\Utility\UserAccessControl;
use Cake\Http\Cookie\Cookie;
use Cake\Http\ServerRequest;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedToken;

/**
 * Class MfaVerifiedCookieService
 */
class MfaVerifiedCookieService
{
    /**
     * Create MFA verified cookie.
     *
     * @param \App\Utility\UserAccessControl $uac User access control
     * @param \App\Authenticator\SessionIdentificationServiceInterface $sessionIdentificationService session ID service
     * @param \Cake\Http\ServerRequest $request Server request
     * @return \Cake\Http\Cookie\Cookie
     */
    public function createDuoMfaVerifiedCookie(
        UserAccessControl $uac,
        SessionIdentificationServiceInterface $sessionIdentificationService,
        ServerRequest $request
    ): Cookie {
        $sessionId = $sessionIdentificationService->getSessionIdentifier($request);
        $token = MfaVerifiedToken::get($uac, MfaSettings::PROVIDER_DUO, $sessionId);

        return MfaVerifiedCookie::get($request, $token);
    }
}
