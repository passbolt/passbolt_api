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
use App\Service\AuthenticationTokens\AuthenticationTokenGetService;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\UnauthorizedException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Validation\Validation;
use Throwable;

/**
 * Bridges the top-level ceremony page and the browser extension.
 *
 * WebAuthn can only run at the passbolt top-level origin (never in the extension's cross-origin app
 * iframe). So the ceremony runs in a top-level ceremony page which DEPOSITS the raw credential onto
 * the in-flight challenge token; the extension then COLLECTS it (out of band) and finishes the
 * enrollment/login. Both operations look the token up as active (not expired) WITHOUT consuming it —
 * only the finish step consumes the token.
 */
class PasskeyCeremonyStoreService
{
    use LocatorAwareTrait;

    /**
     * @var array<string>
     */
    public static array $ALLOWED_TOKEN_TYPES = [
        AuthenticationToken::TYPE_PASSKEY_SETUP,
        AuthenticationToken::TYPE_PASSKEY_LOGIN,
    ];

    /**
     * Store the raw WebAuthn credential onto the challenge token (called by the ceremony page).
     *
     * @param string $userId The user the token is bound to
     * @param string $tokenType One of the allowed token types
     * @param string $token The challenge token uuid
     * @param string $credentialJson The raw JSON credential returned by the authenticator
     * @return void
     */
    public function deposit(string $userId, string $tokenType, string $token, string $credentialJson): void
    {
        $authToken = $this->getActiveOrFail($userId, $tokenType, $token);
        if ($credentialJson === '') {
            throw new BadRequestException(__('A ceremony response is required.'));
        }

        $data = $authToken->getJsonDecodedData();
        $data['credential'] = $credentialJson;
        $authToken->set('data', json_encode($data), ['guard' => false]);

        $this->fetchTable('AuthenticationTokens')->saveOrFail($authToken);
    }

    /**
     * Read the deposited credential from the challenge token (called by the extension).
     *
     * @param string $userId The user the token is bound to
     * @param string $tokenType One of the allowed token types
     * @param string $token The challenge token uuid
     * @return string The raw JSON credential
     */
    public function collect(string $userId, string $tokenType, string $token): string
    {
        $authToken = $this->getActiveOrFail($userId, $tokenType, $token);
        $credential = $authToken->getDataValue('credential');
        if (!is_string($credential) || $credential === '') {
            throw new BadRequestException(__('The ceremony response has not been deposited yet.'));
        }

        return $credential;
    }

    /**
     * @param string $userId User id
     * @param string $tokenType Token type
     * @param string $token Token uuid
     * @return \App\Model\Entity\AuthenticationToken
     * @throws \Cake\Http\Exception\UnauthorizedException If the token is not an active challenge token
     */
    private function getActiveOrFail(string $userId, string $tokenType, string $token): AuthenticationToken
    {
        if (!Validation::inList($tokenType, self::$ALLOWED_TOKEN_TYPES)) {
            throw new BadRequestException(__('Invalid token type.'));
        }
        if (!Validation::uuid($token)) {
            throw new BadRequestException(__('The challenge token should be a valid UUID.'));
        }
        if (!Validation::uuid($userId)) {
            throw new BadRequestException(__('A valid user id is required.'));
        }

        try {
            return (new AuthenticationTokenGetService())->getActiveOrFail($token, $userId, $tokenType);
        } catch (Throwable $th) {
            throw new UnauthorizedException(__('The token should reference an active challenge token.'), null, $th);
        }
    }
}
