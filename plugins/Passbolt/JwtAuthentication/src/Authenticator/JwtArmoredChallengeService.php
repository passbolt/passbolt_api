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
namespace Passbolt\JwtAuthentication\Authenticator;

use App\Model\Entity\User;
use Cake\Http\ServerRequest;
use Cake\Routing\Router;
use Passbolt\JwtAuthentication\Service\AccessToken\JwtTokenCreateService;
use Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenCreateService;
use Passbolt\JwtAuthentication\Service\VerifyToken\VerifyTokenCreateService;

class JwtArmoredChallengeService implements JwtArmoredChallengeInterface
{
    /**
     * @inheritDoc
     */
    public function makeArmoredChallenge(ServerRequest $request, User $user, string $verifyToken): array
    {
        $accessToken = $this->createAccessToken($user);
        $refreshToken = $this->createRefreshToken($user, $accessToken);
        $verifyToken = $this->createVerifyToken($user, $verifyToken);

        return [
            'version' => GpgJwtAuthenticator::PROTOCOL_VERSION,
            'domain' => Router::url('/', true),
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'verify_token' => $verifyToken,
        ];
    }

    /**
     * @param \App\Model\Entity\User $user Successfully authenticated user
     * @return string
     */
    protected function createAccessToken(User $user): string
    {
        return (new JwtTokenCreateService())->createToken($user->id);
    }

    /**
     * @param \App\Model\Entity\User $user Successfully authenticated user
     * @param string $accessToken Access token
     * @return string
     */
    protected function createRefreshToken(User $user, string $accessToken): string
    {
        return (new RefreshTokenCreateService())->createToken($user->id, $accessToken)->token;
    }

    /**
     * @param \App\Model\Entity\User $user Successfully authenticated user
     * @param string $verifyToken Verify token provided by the client
     * @return string
     */
    protected function createVerifyToken(User $user, string $verifyToken): string
    {
        return (new VerifyTokenCreateService())->createToken($verifyToken, $user->id)->token;
    }
}
