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
namespace Passbolt\JwtAuthentication\Service\AccessToken;

use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;
use Cake\I18n\FrozenTime;
use Cake\Routing\Router;
use Cake\Validation\Validation;
use Firebase\JWT\JWT;
use InvalidArgumentException;

class JwtTokenCreateService extends JwtAbstractService
{
    public const JWT_SECRET_KEY_PATH = self::JWT_CONFIG_DIR . 'jwt.key';
    public const JWT_ALG = 'RS256';
    public const JWT_KEY_LENGTH = 4096;
    public const JWT_EXPIRY_CONFIG_KEY = 'passbolt.auth.token.access_token.expiry';

    protected string $keyPath = self::JWT_SECRET_KEY_PATH;

    /**
     * @param string $userId The id of the user successfully logging in.
     * @param ?string $expiration The validity duration of the token in words (optional).
     * @return string
     * @throws \InvalidArgumentException if the userId is not a valid Uuid
     * @throws \Passbolt\JwtAuthentication\Error\Exception\AccessToken\InvalidJwtKeyPairException if the JWT secret key is not readable.
     */
    public function createToken(string $userId, ?string $expiration = null): string
    {
        if (!Validation::uuid($userId)) {
            throw new InvalidArgumentException(__('The resource identifier should be a valid UUID.'));
        }

        $privateKey = $this->readKeyFileContent();
        $payload = [
            'iss' => Router::url('/', true),
            'sub' => $userId,
            'exp' => $this->createExpiryDate($expiration),
        ];

        return JWT::encode($payload, $privateKey, self::JWT_ALG);
    }

    /**
     * Create a UNIX time from a time expressed in words.
     * This should return an integer.
     *
     * @param string|null $expirationPeriod Expiration period in words.
     * @return int Unix time
     */
    public function createExpiryDate(?string $expirationPeriod = null): int
    {
        $expiryPeriod = $expirationPeriod ?? Configure::read(JwtTokenCreateService::JWT_EXPIRY_CONFIG_KEY);
        try {
            return (int)(new FrozenTime('+' . $expiryPeriod))->toUnixString();
        } catch (\Throwable $e) {
            throw new InternalErrorException(
                __('The configuration {0} is not correctly set.', JwtTokenCreateService::JWT_EXPIRY_CONFIG_KEY),
                500,
                $e
            );
        }
    }
}
