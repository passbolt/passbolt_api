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
 * @since         5.17.0
 */
namespace Passbolt\MultiFactorAuthentication\Service\Webauthn\Delegated;

use App\Model\Entity\AuthenticationToken;
use App\Model\Table\AuthenticationTokensTable;
use App\Utility\UserAccessControl;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query\SelectQuery;
use Cake\Validation\Validation;

class MfaWebauthnDelegatedCeremonyService
{
    use LocatorAwareTrait;

    public const STATE_BYTES = 32;
    public const STATE_KEY = 'state';

    protected AuthenticationTokensTable $AuthenticationTokens;

    /**
     * Constructor
     */
    public function __construct()
    {
        /** @var \App\Model\Table\AuthenticationTokensTable $tokens */
        $tokens = $this->fetchTable('AuthenticationTokens');
        $this->AuthenticationTokens = $tokens;
    }

    /**
     * @param \App\Utility\UserAccessControl $uac UAC.
     * @param string $state Base64url-encoded 32-byte state.
     * @return string
     * @throws \Cake\Http\Exception\BadRequestException When state is missing or malformed.
     */
    public function create(UserAccessControl $uac, string $state): string
    {
        $this->assertValidState($state);

        $this->AuthenticationTokens->updateAll(
            ['active' => false],
            [
                'user_id' => $uac->getId(),
                'type' => AuthenticationToken::TYPE_MFA_DELEGATED_CEREMONY,
                'active' => true,
            ],
        );

        $token = $this->AuthenticationTokens->generate(
            $uac->getId(),
            AuthenticationToken::TYPE_MFA_DELEGATED_CEREMONY,
            null,
            [self::STATE_KEY => $state],
        );

        return $token->token;
    }

    /**
     * @param string $ceremonyToken Ceremony token.
     * @return \App\Utility\UserAccessControl
     * @throws \Cake\Http\Exception\NotFoundException When the token is invalid.
     */
    public function resolve(string $ceremonyToken): UserAccessControl
    {
        $token = $this->findActiveCeremonyOrFail($ceremonyToken);

        return new UserAccessControl(
            $token->user->role->name,
            $token->user->id,
            $token->user->username,
        );
    }

    /**
     * @param string $ceremonyToken Ceremony token.
     * @return string
     * @throws \Cake\Http\Exception\NotFoundException When the token is invalid.
     */
    public function getStateForToken(string $ceremonyToken): string
    {
        if (!Validation::uuid($ceremonyToken)) {
            throw new NotFoundException(__('The ceremony token is invalid.'));
        }

        /** @var \App\Model\Entity\AuthenticationToken|null $token */
        $token = $this->AuthenticationTokens
            ->find(
                'byTokenActiveNotExpired',
                token: $ceremonyToken,
                tokenType: AuthenticationToken::TYPE_MFA_DELEGATED_CEREMONY,
            )
            ->first();

        if ($token === null) {
            throw new NotFoundException(__('The ceremony token is invalid.'));
        }

        $state = $token->getDataValue(self::STATE_KEY);
        if ($state === null) {
            throw new NotFoundException(__('The ceremony token is invalid.'));
        }

        return $state;
    }

    /**
     * @param string $state State.
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException When state is missing or malformed.
     */
    private function assertValidState(string $state): void
    {
        if ($state === '') {
            throw new BadRequestException(__('A state is required.'));
        }

        $decoded = base64_decode(strtr($state, '-_', '+/'), true);
        if ($decoded === false || strlen($decoded) !== self::STATE_BYTES) {
            throw new BadRequestException(__('The state is not a valid base64url-encoded 32-byte value.'));
        }
    }

    /**
     * @param string $ceremonyToken Ceremony token.
     * @return \App\Model\Entity\AuthenticationToken
     * @throws \Cake\Http\Exception\NotFoundException When the token is invalid, expired, or the user is inactive.
     */
    private function findActiveCeremonyOrFail(string $ceremonyToken): AuthenticationToken
    {
        if (!Validation::uuid($ceremonyToken)) {
            throw new NotFoundException(__('The ceremony token is invalid.'));
        }

        /** @var \App\Model\Entity\AuthenticationToken|null $token */
        $token = $this->AuthenticationTokens
            ->find(
                'byTokenActiveNotExpired',
                token: $ceremonyToken,
                tokenType: AuthenticationToken::TYPE_MFA_DELEGATED_CEREMONY,
            )
            ->contain(
                'Users',
                fn(SelectQuery $q): SelectQuery => $q->find('activeNotDeletedNotDisabledContainRole'),
            )
            ->first();

        if ($token === null || $token->user === null) {
            throw new NotFoundException(__('The ceremony token is invalid or has expired.'));
        }

        return $token;
    }
}
