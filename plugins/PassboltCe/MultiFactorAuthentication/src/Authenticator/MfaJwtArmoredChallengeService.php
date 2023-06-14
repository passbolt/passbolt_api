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
 * When successfully logged in, but with MFA required,
 * appends the MFA providers to the challenge to inform
 * that an additional MFA token is required.
 */
class MfaJwtArmoredChallengeService extends JwtArmoredChallengeService
{
    /**
     * @inheritDoc
     */
    public function makeArmoredChallenge(ServerRequest $request, User $user, string $verifyToken): array
    {
        $challenge = parent::makeArmoredChallenge($request, $user, $verifyToken);

        $uac = new UserAccessControl($user['role']['name'], $user['id'], $user['username']);
        $mfaSettings = MfaSettings::get($uac);

        $isMfaAuthenticationRequired = (new IsMfaAuthenticationRequiredService())
            ->isMfaCheckRequired($request, $mfaSettings, $uac);

        if ($isMfaAuthenticationRequired) {
            $challenge['providers'] = $mfaSettings->getEnabledProvidersWithLastUsedFirst();
        }

        return $challenge;
    }
}
