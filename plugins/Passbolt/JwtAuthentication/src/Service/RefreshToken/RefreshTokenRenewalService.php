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

/**
 * @property \App\Model\Table\AuthenticationTokensTable $AuthenticationTokens
 */
class RefreshTokenRenewalService extends RefreshTokenAbstractService
{
    /**
     * @var \Cake\Http\ServerRequest|null
     */
    protected $request;

    /**
     * @var string $userId uuid
     */
    protected $userId;

    /**
     * @var string $token uuid
     */
    protected $token;

    /**
     * @var string $accessToken access token generated along the refresh token
     */
    protected $accessToken;

    /**
     * @param string $userId User ID.
     * @param string $token refresh token uuid
     * @param string $accessToken refresh token
     * @throws \InvalidArgumentException if the userId or the token are not valid UUIDs
     */
    final public function __construct(string $userId, string $token, string $accessToken)
    {
        parent::__construct();

        $this->validateRefreshToken($token);
        $this->validateUserId($userId);

        $this->userId = $userId;
        $this->token = $token;
        $this->accessToken = $accessToken;
    }

    /**
     * 1. Consume the refresh token passed in the request
     * 2. Return a new token
     *
     * @return \App\Model\Entity\AuthenticationToken
     * @throws \Passbolt\JwtAuthentication\Error\Exception\RefreshToken\RefreshTokenNotFoundException if the token is not found
     * @throws \Passbolt\JwtAuthentication\Error\Exception\RefreshToken\ConsumedRefreshTokenAccessException if the token was already consumed
     * @throws \Passbolt\JwtAuthentication\Error\Exception\RefreshToken\ExpiredRefreshTokenAccessException if the token is expired
     */
    public function renewToken(): AuthenticationToken
    {
        $this->consumeToken($this->token, $this->userId);

        return (new RefreshTokenCreateService())->createToken($this->userId, $this->accessToken);
    }
}
