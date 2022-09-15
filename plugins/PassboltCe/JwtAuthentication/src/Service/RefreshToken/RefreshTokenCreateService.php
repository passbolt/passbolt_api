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
use Cake\Event\Event;
use Cake\Http\ServerRequest;

class RefreshTokenCreateService extends RefreshTokenAbstractService
{
    public const REFRESH_TOKEN_CREATED_EVENT = 'refresh_token_created_event';
    public const REQUEST_DATA_KEY = 'request_data_key';

    /**
     * Creates a new refresh token
     * Triggers an event with the refresh token as subject and
     * the associated access token in the data.
     *
     * @param \Cake\Http\ServerRequest $request Server request.
     * @param string $userId user uuid
     * @param string $accessToken access token associated to the created refresh token
     * @return \App\Model\Entity\AuthenticationToken
     */
    public function createToken(ServerRequest $request, string $userId, string $accessToken): AuthenticationToken
    {
        $data = $this->getTokenData($accessToken);
        $refreshToken = $this->AuthenticationTokens->generate(
            $userId,
            AuthenticationToken::TYPE_REFRESH_TOKEN,
            null,
            $data
        );
        $this->dispatchRefreshTokenCreatedEvent($request, $refreshToken, $accessToken);

        return $refreshToken;
    }

    /**
     * Dispatch an event when a new refresh token has been created.
     *
     * @param \Cake\Http\ServerRequest $request Server request.
     * @param \App\Model\Entity\AuthenticationToken $refreshToken Refresh token issued
     * @param string $accessToken Access token associated to the refresh token issued
     * @return void
     */
    protected function dispatchRefreshTokenCreatedEvent(
        ServerRequest $request,
        AuthenticationToken $refreshToken,
        string $accessToken
    ): void {
        $event = new Event(self::REFRESH_TOKEN_CREATED_EVENT, $refreshToken, [
            self::ACCESS_TOKEN_DATA_KEY => $accessToken,
            self::REQUEST_DATA_KEY => $request,
        ]);
        $this->AuthenticationTokens->getEventManager()->dispatch($event);
    }

    /**
     * Use the logic in the authentication token entity
     * to hash the session.
     *
     * @param string $accessToken Access token to hash and store as session ID
     * @return array
     */
    protected function getTokenData(string $accessToken): array
    {
        $token = new AuthenticationToken();
        $token->hashAndSetSessionId($accessToken);

        return $token->getJsonDecodedData();
    }
}
