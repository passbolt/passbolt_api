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
namespace Passbolt\JwtAuthentication\Service\VerifyToken;

use App\Model\Entity\AuthenticationToken;
use Cake\Core\Configure;
use Cake\I18n\FrozenTime;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;
use Passbolt\JwtAuthentication\Error\Exception\VerifyToken\ConsumedVerifyTokenAccessException;
use Passbolt\JwtAuthentication\Error\Exception\VerifyToken\ExpiredVerifyTokenAccessException;
use Passbolt\JwtAuthentication\Error\Exception\VerifyToken\InvalidVerifyTokenException;

class VerifyTokenValidationService
{
    public const VERIFY_TOKEN_EXPIRY_CONFIG_KEY = 'passbolt.auth.token.verify_token.expiry';

    /**
     * A validate token should be a Uuid, with a valid expiry date.
     * It should not be present in the authentication_tokens table.
     *
     * @param string|int $verifyTokenExpiry Verify Token Expiry
     * @param string|null $verifyToken Verify Token
     * @param string $userId User ID
     * @return void
     * @throws \Passbolt\JwtAuthentication\Error\Exception\VerifyToken\InvalidVerifyTokenException if version is not supported
     */
    public function validateToken($verifyTokenExpiry, ?string $verifyToken, string $userId): void
    {
        $this->validateTokenExpiry($verifyTokenExpiry);
        $this->validateFormat($verifyToken);
        $this->validateUserId($userId);
        $this->validateNonce($verifyToken, $userId);
    }

    /**
     * Assert that the token expiry is valid and not set too far in the future.
     *
     * @param mixed $verifyTokenExpiry unix timestamp
     * @return void
     * @throws \Passbolt\JwtAuthentication\Error\Exception\VerifyToken\InvalidVerifyTokenException if the token is expired.
     */
    protected function validateTokenExpiry($verifyTokenExpiry): void
    {
        $maxTokenExpiry = FrozenTime::now()
            ->modify('+' . Configure::read(self::VERIFY_TOKEN_EXPIRY_CONFIG_KEY))
            ->toUnixString();
        if (
            !isset($verifyTokenExpiry) ||
            !is_numeric($verifyTokenExpiry) ||
            $verifyTokenExpiry > $maxTokenExpiry
        ) {
            throw new InvalidVerifyTokenException(__('Invalid verify token expiry.'));
        }
        if ($verifyTokenExpiry < time()) {
            throw new ExpiredVerifyTokenAccessException(
                __('Attempt to access an expired verify token.')
            );
        }
    }

    /**
     * Assert verify token is a UUID
     *
     * @param mixed $verifyToken token
     * @return void
     * @throws \Passbolt\JwtAuthentication\Error\Exception\VerifyToken\InvalidVerifyTokenException if the format is not valid.
     * @throws \Cake\ORM\Exception\PersistenceFailedException
     */
    protected function validateFormat($verifyToken): void
    {
        if (
            !isset($verifyToken) ||
            !is_string($verifyToken) ||
            !Validation::uuid($verifyToken)
        ) {
            throw new InvalidVerifyTokenException(__('Invalid verify token format.'));
        }
    }

    /**
     * Assert verify token is a UUID
     *
     * @param string $userId User ID
     * @return void
     * @throws \Passbolt\JwtAuthentication\Error\Exception\VerifyToken\InvalidVerifyTokenException if the user ID is not a UUID.
     * @throws \Cake\ORM\Exception\PersistenceFailedException
     */
    protected function validateUserId(string $userId): void
    {
        if (!Validation::uuid($userId)) {
            throw new InvalidVerifyTokenException(__('Invalid user ID format.'));
        }
    }

    /**
     * Check that this token - userId pair does not exist.
     *
     * @param string $verifyToken Verify Token
     * @param string $userId User ID
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException if the token has already been used.
     */
    protected function validateNonce(string $verifyToken, string $userId): void
    {
        $AuthenticationTokens = TableRegistry::getTableLocator()->get('AuthenticationTokens');
        $existingTokenWithSameValue = $AuthenticationTokens
            ->find()
            ->where([
                $AuthenticationTokens->aliasField('token') => $verifyToken,
                $AuthenticationTokens->aliasField('user_id') => $userId,
                $AuthenticationTokens->aliasField('type') => AuthenticationToken::TYPE_VERIFY_TOKEN,
            ])
            ->count();

        if ($existingTokenWithSameValue !== 0) {
            throw new ConsumedVerifyTokenAccessException(__('Verify token has been already used in the past.'));
        }
    }
}
