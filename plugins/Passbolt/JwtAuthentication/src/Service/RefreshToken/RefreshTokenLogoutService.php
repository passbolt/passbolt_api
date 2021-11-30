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
namespace Passbolt\JwtAuthentication\Service\RefreshToken;

use App\Model\Entity\AuthenticationToken;
use Cake\Http\ServerRequest;

class RefreshTokenLogoutService extends RefreshTokenAbstractService
{
    /**
     * @param string $userId user uuid
     * @param \Cake\Http\ServerRequest $request server request
     * @return int The number of tokens deactivated.
     */
    public function logout(string $userId, ServerRequest $request): int
    {
        $token = $this->getRefreshTokenInRequest($request);

        // If no refresh token is specified in the request, deactivate all the user's refresh token
        if (!is_string($token)) {
            return $this->AuthenticationTokens->updateAll(
                ['active' => false],
                [
                    $this->AuthenticationTokens->aliasField('user_id') => $userId,
                    $this->AuthenticationTokens->aliasField('type') => AuthenticationToken::TYPE_REFRESH_TOKEN,
                ]
            );
        }

        $token = $this->getActiveRefreshToken($token, $userId);
        $this->consumeToken($token);

        return 1;
    }

    /**
     * Read the refresh token in the payload or in the cookies.
     *
     * @param \Cake\Http\ServerRequest $request Server request
     * @return string|null
     */
    protected function getRefreshTokenInRequest(ServerRequest $request): ?string
    {
        /** @var string|null $refreshToken */
        $refreshToken = $request->getData(self::REFRESH_TOKEN_DATA_KEY);
        if ($refreshToken === null) {
            /** @var string|null $refreshToken */
            $refreshToken = $request->getCookie(self::REFRESH_TOKEN_COOKIE);
        }

        return $refreshToken;
    }
}
