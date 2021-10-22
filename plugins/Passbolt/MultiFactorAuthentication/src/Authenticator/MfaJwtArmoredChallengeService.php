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
namespace Passbolt\MultiFactorAuthentication\Authenticator;

use App\Model\Entity\User;
use App\Utility\UserAccessControl;
use Cake\Http\ServerRequest;
use Passbolt\JwtAuthentication\Authenticator\JwtArmoredChallengeService;
use Passbolt\MultiFactorAuthentication\Service\IsMfaAuthenticationRequiredService;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

/**
 * When successfully authenticated with MFA required
 * appends the MFA providers to inform that an additional
 * MFA token is required.
 */
class MfaJwtArmoredChallengeService extends JwtArmoredChallengeService
{
    /**
     * @inheritDoc
     */
    public function makeArmoredChallenge(ServerRequest $request, User $user, string $verifyToken): array
    {
        $challenge = parent::makeArmoredChallenge($request, $user, $verifyToken);

        $sessionId = $challenge['access_token'];
        $uac = new UserAccessControl($user['role']['name'], $user['id'], $user['username']);
        $mfaSettings = MfaSettings::get($uac);

        if ($this->isMfaAuthenticationRequired($request, $uac, $mfaSettings, $sessionId)) {
            $challenge['providers'] = $mfaSettings->getEnabledProviders();
        }

        return $challenge;
    }

    /**
     * @param \Cake\Http\ServerRequest $request Server Request
     * @param \App\Utility\UserAccessControl $uac UAC
     * @param \Passbolt\MultiFactorAuthentication\Utility\MfaSettings $mfaSettings MFA Settings
     * @param string $sessionId Session ID (here access token)
     * @return bool
     */
    public function isMfaAuthenticationRequired(
        ServerRequest $request,
        UserAccessControl $uac,
        MfaSettings $mfaSettings,
        string $sessionId
    ): bool {
        return (new IsMfaAuthenticationRequiredService())->isMfaCheckRequired(
            $request,
            $mfaSettings,
            $uac,
            $sessionId
        );
    }
}
