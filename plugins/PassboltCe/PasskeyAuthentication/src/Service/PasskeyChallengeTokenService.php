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
namespace Passbolt\PasskeyAuthentication\Service;

use App\Model\Entity\AuthenticationToken;
use App\Service\AuthenticationTokens\AuthenticationTokenConsumeService;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\UnauthorizedException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Validation\Validation;
use InvalidArgumentException;
use Throwable;

/**
 * Persists the in-flight WebAuthn ceremony state (the challenge and the serialized options) between
 * the "begin" and "finish" requests, in an AuthenticationToken bound to the user.
 *
 * Two flows are supported:
 *  - enrollment (passkey_setup): the user is authenticated, so the token is created/consumed against
 *    the current access control, exactly like the MFA provider;
 *  - login (passkey_login): there is NO session yet, so the token is created for and consumed against
 *    the *target* user id supplied by the client on begin (and echoed on finish). The token itself
 *    was minted for that user id on begin, so a mismatched user id on finish simply fails to consume.
 *
 * The token is single-use (consumed on finish) and its id is returned to the client on begin.
 */
class PasskeyChallengeTokenService
{
    use LocatorAwareTrait;

    /**
     * Provider discriminator stored on the token data.
     *
     * @var string
     */
    public const PROVIDER = 'passkey';

    /**
     * @var array<string>
     */
    public static array $ALLOWED_TOKEN_TYPES = [
        AuthenticationToken::TYPE_PASSKEY_SETUP,
        AuthenticationToken::TYPE_PASSKEY_LOGIN,
    ];

    /**
     * Create a challenge token storing the serialized ceremony options.
     *
     * @param string $userId The user the ceremony is bound to
     * @param string $tokenType One of the allowed token types
     * @param string $optionsJson The serialized PublicKeyCredentialCreationOptions/RequestOptions
     * @return \App\Model\Entity\AuthenticationToken
     */
    public function create(string $userId, string $tokenType, string $optionsJson): AuthenticationToken
    {
        $this->assertTokenType($tokenType);

        /** @var \App\Model\Table\AuthenticationTokensTable $authenticationTokens */
        $authenticationTokens = $this->fetchTable('AuthenticationTokens');
        $data = [
            'provider' => self::PROVIDER,
            'options' => $optionsJson,
        ];

        try {
            return $authenticationTokens->generate($userId, $tokenType, null, $data);
        } catch (Throwable $th) {
            throw new InternalErrorException(__('Unable to create the passkey challenge token.'), null, $th);
        }
    }

    /**
     * Consume a challenge token and return the serialized ceremony options it holds.
     *
     * @param string $userId The user the ceremony is bound to
     * @param string $tokenType One of the allowed token types
     * @param string $token The token uuid supplied by the client
     * @return string The serialized ceremony options
     * @throws \InvalidArgumentException If the token is not a valid uuid or the token type is not supported
     * @throws \Cake\Http\Exception\UnauthorizedException If the token could not be consumed
     * @throws \Cake\Http\Exception\InternalErrorException If the token does not hold ceremony options
     */
    public function consume(string $userId, string $tokenType, string $token): string
    {
        $this->assertTokenType($tokenType);
        if (!Validation::uuid($token)) {
            throw new InvalidArgumentException('The passkey challenge token should be a valid UUID.');
        }

        try {
            $authToken = (new AuthenticationTokenConsumeService())->consumeActiveNotExpiredOrFail(
                $token,
                $userId,
                $tokenType
            );
        } catch (Throwable $th) {
            throw new UnauthorizedException(
                __('The token should reference an active passkey challenge token.'),
                null,
                $th
            );
        }

        $options = $authToken->getDataValue('options');
        if (!is_string($options) || $options === '') {
            throw new InternalErrorException(__('The passkey challenge token is missing its options.'));
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
