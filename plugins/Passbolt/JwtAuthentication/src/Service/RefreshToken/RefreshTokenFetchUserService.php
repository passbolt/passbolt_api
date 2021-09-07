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

use Cake\Datasource\Exception\RecordNotFoundException;
use Passbolt\JwtAuthentication\Error\Exception\RefreshToken\RefreshTokenNotFoundException;

/**
 * @property \App\Model\Table\AuthenticationTokensTable $AuthenticationTokens
 */
class RefreshTokenFetchUserService extends RefreshTokenAbstractService
{
    /**
     * @var string $token uuid
     */
    protected $token;

    /**
     * @param string|null $token refresh token uuid
     * @throws \InvalidArgumentException if the token is not a valid UUIDs
     */
    final public function __construct(?string $token)
    {
        parent::__construct();

        $this->validateRefreshToken($token);
        $this->token = $token;
    }

    /**
     * Fetch the user from a provided refresh token.
     *
     * @return string
     * @throws \Passbolt\JwtAuthentication\Error\Exception\RefreshToken\RefreshTokenNotFoundException When there is no user associated to this token.
     */
    public function getUserIdFromToken(): string
    {
        try {
            return $this->queryRefreshToken($this->token)->firstOrFail()->get('user_id');
        } catch (RecordNotFoundException $e) {
            throw new RefreshTokenNotFoundException(
                __('No active refresh token matching the request could be found.')
            );
        }
    }
}
