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
use Cake\Core\Configure;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ModelAwareTrait;
use Cake\Http\Cookie\Cookie;
use Cake\I18n\FrozenTime;
use Cake\ORM\Query;
use Cake\Validation\Validation;
use Passbolt\JwtAuthentication\Error\Exception\RefreshToken\ConsumedRefreshTokenAccessException;
use Passbolt\JwtAuthentication\Error\Exception\RefreshToken\ExpiredRefreshTokenAccessException;
use Passbolt\JwtAuthentication\Error\Exception\RefreshToken\RefreshTokenNotFoundException;

/**
 * @property \App\Model\Table\AuthenticationTokensTable $AuthenticationTokens
 */
abstract class RefreshTokenAbstractService
{
    use ModelAwareTrait;

    public const REFRESH_TOKEN_COOKIE = 'refresh_token';
    public const REFRESH_TOKEN_DATA_KEY = 'refresh_token';
    public const REFRESH_TOKEN_EXPIRY_CONFIG_KEY = 'passbolt.auth.token.refresh_token.expiry';
    public const ACCESS_TOKEN_DATA_KEY = 'access_token';

    /**
     * RefreshTokenCreateService constructor.
     */
    public function __construct()
    {
        $this->loadModel('AuthenticationTokens');
    }

    /**
     * @param \App\Model\Entity\AuthenticationToken $token token
     * @return \Cake\Http\Cookie\Cookie
     */
    public function createHttpOnlySecureCookie(AuthenticationToken $token): Cookie
    {
        $cookie = new Cookie(self::REFRESH_TOKEN_COOKIE, $token->token);
        $expiry = new FrozenTime(
            '+' . Configure::read(self::REFRESH_TOKEN_EXPIRY_CONFIG_KEY)
        );

        return $cookie
            ->withSecure(true)
            ->withHttpOnly(true)
            ->withExpiry($expiry);
    }

    /**
     * Find the token corresponding to the user and refresh token.
     * If no user id is provided (e.g. if only the token is provided in cookie),
     * fetch the user id.
     *
     * Throw a security error if this token is inactive.
     * Set it to inactive.
     *
     * @param string $token Refresh token to retrieve.
     * @param string $userId User ID.
     * @return \App\Model\Entity\AuthenticationToken
     * @throws \Passbolt\JwtAuthentication\Error\Exception\RefreshToken\RefreshTokenNotFoundException if the token is not found
     * @throws \Passbolt\JwtAuthentication\Error\Exception\RefreshToken\ConsumedRefreshTokenAccessException if the token was already consumed
     * @throws \Passbolt\JwtAuthentication\Error\Exception\RefreshToken\ExpiredRefreshTokenAccessException if the token is expired
     */
    protected function consumeToken(string $token, string $userId): AuthenticationToken
    {
        $this->validateRefreshToken($token);

        try {
            /** @var \App\Model\Entity\AuthenticationToken|null $refreshToken */
            $refreshToken = $this->queryRefreshToken($token)
                ->where([
                    'user_id' => $userId,
                ])
                ->firstOrFail();
        } catch (RecordNotFoundException $e) {
            throw new RefreshTokenNotFoundException(
                __('No active refresh token matching the request could be found.')
            );
        }

        if ($refreshToken->isNotActive()) {
            throw new ConsumedRefreshTokenAccessException(
                __('The refresh token provided was already used.')
            );
        }

        if ($refreshToken->isExpired()) {
            throw new ExpiredRefreshTokenAccessException(
                __('Expired refresh token provided.')
            );
        }

        $refreshToken->set('active', false);

        return $this->AuthenticationTokens->saveOrFail($refreshToken);
    }

    /**
     * @param mixed $token Refresh token to be validated.
     * @return void
     * @throws \InvalidArgumentException if the $token is not valid
     */
    protected function validateRefreshToken($token): void
    {
        if (!Validation::uuid($token)) {
            throw new \InvalidArgumentException(__('The refresh token should be a valid UUID.'));
        }
    }

    /**
     * @param mixed $userId User id to be validated.
     * @return void
     * @throws \InvalidArgumentException if the $id is not valid
     */
    protected function validateUserId($userId): void
    {
        if (!Validation::uuid($userId)) {
            throw new \InvalidArgumentException(__('This is not a valid user id.'));
        }
    }

    /**
     * @param string $token Refresh token
     * @return \Cake\ORM\Query
     */
    protected function queryRefreshToken(string $token): Query
    {
        $this->validateRefreshToken($token);

        return $this->AuthenticationTokens->find()->where([
            'token' => $token,
            'type' => AuthenticationToken::TYPE_REFRESH_TOKEN,
        ]);
    }
}
