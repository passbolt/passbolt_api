<?php

namespace App\Utility\AuthToken;

use App\Model\Entity\AuthenticationToken;
use Cake\Core\Configure;
use InvalidArgumentException;

class AuthTokenExpiry
{
    const VALID_TOKEN_TYPES = [
        AuthenticationToken::TYPE_LOGIN,
        AuthenticationToken::TYPE_RECOVER,
        AuthenticationToken::TYPE_REGISTER,
        AuthenticationToken::TYPE_MFA,
    ];

    /**
     * @param string $tokenType Token type
     * @return string
     */
    public function getExpirationForTokenType(string $tokenType)
    {
        if (!in_array($tokenType, self::VALID_TOKEN_TYPES)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Invalid $tokenType `%s`. Must be one of `%s`.',
                    $tokenType,
                    implode(',', self::VALID_TOKEN_TYPES)
                )
            );
        }

        $tokenTypeExpiry = Configure::read(sprintf('passbolt.auth.token.%s.expiry', $tokenType));

        return $tokenTypeExpiry ?? Configure::read('passbolt.auth.tokenExpiry');
    }
}
