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
namespace Passbolt\JwtAuthentication\Authenticator;

use Authentication\Authenticator\JwtAuthenticator;
use Passbolt\JwtAuthentication\Service\AccessToken\JwtTokenCreateService;

class JwtAccessTokenAuthenticator extends JwtAuthenticator
{
    /**
     * @inheritDoc
     */
    protected function decodeToken(string $token): ?object
    {
        $payload = parent::decodeToken($token);
        if ($payload === null) {
            return null;
        }

        $aud = $payload->aud ?? null;
        if ($aud !== null && $aud !== JwtTokenCreateService::ACCESS_TOKEN_AUDIENCE) {
            return null;
        }

        return $payload;
    }
}
