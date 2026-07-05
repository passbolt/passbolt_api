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
 * @since         5.14.0
 */
namespace Passbolt\MultiFactorAuthentication\Service\Webauthn;

use App\Model\Entity\AuthenticationToken;
use App\Service\AuthenticationTokens\AuthenticationTokenConsumeService;
use App\Utility\UserAccessControl;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\UnauthorizedException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Validation\Validation;
use InvalidArgumentException;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
use Throwable;

/**
 * Persists the in-flight WebAuthn ceremony state (the challenge and the serialized options) between
 * the "begin" and "finish" requests.
 *
 * The state is stored in an AuthenticationToken of type mfa_setup / mfa_verify, exactly like the Duo
 * provider. Because the WebAuthn flow is an XHR flow (not a browser redirect), the token id is
 * returned to the client on "begin" and echoed back on "finish"; the token is bound to the user and
 * single-use (consumed on finish).
 */
class MfaWebauthnChallengeTokenService
{
    use LocatorAwareTrait;

    /**
     * @var array<string>
     */
    public static array $ALLOWED_TOKEN_TYPES = [
        AuthenticationToken::TYPE_MFA_SETUP,
        AuthenticationToken::TYPE_MFA_VERIFY,
    ];

    /**
     * Create a challenge token storing the serialized ceremony options.
     *
     * @param \App\Utility\UserAccessControl $uac User access control
     * @param string $tokenType One of the allowed token types
     * @param string $optionsJson The serialized PublicKeyCredentialCreationOptions/RequestOptions
     * @return \App\Model\Entity\AuthenticationToken
     */
    public function create(UserAccessControl $uac, string $tokenType, string $optionsJson): AuthenticationToken
    {
        $this->assertTokenType($tokenType);

        /** @var \App\Model\Table\AuthenticationTokensTable $authenticationTokens */
        $authenticationTokens = $this->fetchTable('AuthenticationTokens');
        $data = [
            'provider' => MfaSettings::PROVIDER_WEBAUTHN,
            'options' => $optionsJson,
        ];

        try {
            return $authenticationTokens->generate($uac->getId(), $tokenType, null, $data);
        } catch (Throwable $th) {
            throw new InternalErrorException(__('Unable to create the WebAuthn challenge token.'), null, $th);
        }
    }

    /**
     * Consume a challenge token and return the serialized ceremony options it holds.
     *
     * @param \App\Utility\UserAccessControl $uac User access control
     * @param string $tokenType One of the allowed token types
     * @param string $token The token uuid supplied by the client
     * @return string The serialized ceremony options
     * @throws \InvalidArgumentException If the token is not a valid uuid or the token type is not supported
     * @throws \Cake\Http\Exception\UnauthorizedException If the token could not be consumed
     * @throws \Cake\Http\Exception\InternalErrorException If the token does not hold ceremony options
     */
    public function consume(UserAccessControl $uac, string $tokenType, string $token): string
    {
        $this->assertTokenType($tokenType);
        if (!Validation::uuid($token)) {
            throw new InvalidArgumentException('The WebAuthn challenge token should be a valid UUID.');
        }

        try {
            $authToken = (new AuthenticationTokenConsumeService())->consumeActiveNotExpiredOrFail(
                $token,
                $uac->getId(),
                $tokenType
            );
        } catch (Throwable $th) {
            throw new UnauthorizedException(
                __('The token should reference an active WebAuthn challenge token.'),
                null,
                $th
            );
        }

        $options = $authToken->getDataValue('options');
        if (!is_string($options) || $options === '') {
            throw new InternalErrorException(__('The WebAuthn challenge token is missing its options.'));
        }

        return $options;
    }

    /**
     * @param string $tokenType Token type to validate
     * @return void
     * @throws \InvalidArgumentException If the token type is not supported
     */
    private function assertTokenType(string $tokenType): void
    {
        if (!Validation::inList($tokenType, self::$ALLOWED_TOKEN_TYPES)) {
            $allowed = implode(', ', self::$ALLOWED_TOKEN_TYPES);
            throw new InvalidArgumentException("The token type should be one of the following: $allowed.");
        }
    }
}
