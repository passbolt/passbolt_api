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
 * @since         3.11.0
 */

namespace Passbolt\MultiFactorAuthentication\Service\Duo;

use App\Model\Entity\AuthenticationToken;
use App\Service\AuthenticationTokens\AuthenticationTokenConsumeService;
use App\Utility\UserAccessControl;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\UnauthorizedException;
use Cake\Validation\Validation;

/**
 * Class MfaDuoCallbackAuthenticationTokenService
 */
class MfaDuoCallbackAuthenticationTokenService
{
    /**
     * @var array
     */
    public static $ALLOWED_TOKEN_TYPES = [
        AuthenticationToken::TYPE_MFA_SETUP,
        AuthenticationToken::TYPE_MFA_VERIFY,
    ];

    /**
     * Consume and verify Duo callback authentication token.
     *
     * @param \App\Utility\UserAccessControl $uac User access control
     * @param string $tokenType AuthenticationToken's token type
     * @param string $token AuthenticationToken's token
     * @param string $duoState The Duo state to verify the token state value against
     * @return \App\Model\Entity\AuthenticationToken
     * @throws \InvalidArgumentException if token is not a valid UUID.
     * @throws \InvalidArgumentException if token type is not supported.
     * @throws \InvalidArgumentException if the Duo state token is not a valid UUID.
     */
    public function consumeAndVerifyAuthenticationToken(
        UserAccessControl $uac,
        string $tokenType,
        string $token,
        string $duoState
    ): AuthenticationToken {
        if (!Validation::uuid($token)) {
            throw new \InvalidArgumentException('The authentication token should be a valid UUID.');
        }
        if (!Validation::inList($tokenType, MfaDuoCallbackAuthenticationTokenService::$ALLOWED_TOKEN_TYPES)) {
            $readableAllowedTokenTypes = implode(', ', MfaDuoCallbackAuthenticationTokenService::$ALLOWED_TOKEN_TYPES);
            $msg = 'The authentication token type should be one of the following: ' . $readableAllowedTokenTypes . '.';
            throw new \InvalidArgumentException($msg);
        }

        $authToken = $this->consumeAuthenticationTokenOrFail($uac, $tokenType, $token);
        $this->assertDuoStateMatchesAuthenticationTokenState($authToken, $duoState);

        return $authToken;
    }

    /**
     * Consume the duo callback authentication token or fail.
     *
     * @param \App\Utility\UserAccessControl $uac User access control
     * @param string $tokenType AuthenticationToken's token type
     * @param string $token AuthenticationToken's token
     * @return \App\Model\Entity\AuthenticationToken
     * @throws \Cake\Http\Exception\UnauthorizedException If the token could not be consumed
     */
    private function consumeAuthenticationTokenOrFail(
        UserAccessControl $uac,
        string $tokenType,
        string $token
    ): AuthenticationToken {
        try {
            return (new AuthenticationTokenConsumeService())->consumeActiveNotExpiredOrFail(
                $token,
                $uac->getId(),
                $tokenType
            );
        } catch (\Throwable $th) {
            $msg = __('The token should reference an active Duo callback authentication token.');
            throw new UnauthorizedException($msg, null, $th);
        }
    }

    /**
     * Assert the Duo callback authentication token state value.
     *
     * @param \App\Model\Entity\AuthenticationToken $authToken The callback authentication token
     * @param string $duoState The Duo callback state
     * @return void
     * @throws \Cake\Http\Exception\InternalErrorException if the callback authentication token does not have state defined
     * @throws \Cake\Http\Exception\UnauthorizedException if the callback authentication token state value does not match the Duo callback state
     */
    private function assertDuoStateMatchesAuthenticationTokenState(
        AuthenticationToken $authToken,
        string $duoState
    ): void {
        $authTokenState = $authToken->getDataValue('state');
        if (empty($authTokenState)) {
            throw new InternalErrorException(__('An authentication token state is required.'));
        }
        if ($authTokenState !== $duoState) {
            throw new UnauthorizedException(__('The Duo state should match the authentication token state.'));
        }
    }
}
