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
use App\Utility\UuidFactory;
use Cake\Http\Exception\BadRequestException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query\SelectQuery;
use Cake\Validation\Validation;
use Passbolt\MultiFactorAuthentication\Model\Dto\MfaWebauthnDelegatedStateDto;

class MfaWebauthnDelegatedStateService
{
    use LocatorAwareTrait;

    public const SECRET_KEY = 'secret';

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
     * Mints the state that identifies one delegated ceremony and the secret that binds it to the calling application.
     * The secret is returned to the application only, and is never published in the MFA page URL.
     *
     * @param \App\Utility\UserAccessControl $uac Session of the application starting the ceremony.
     * @return \Passbolt\MultiFactorAuthentication\Model\Dto\MfaWebauthnDelegatedStateDto
     */
    public function create(UserAccessControl $uac): MfaWebauthnDelegatedStateDto
    {
        $secret = UuidFactory::uuid();
        $state = $this->AuthenticationTokens->generate(
            $uac->getId(),
            AuthenticationToken::TYPE_MFA_DELEGATED_STATE,
            null,
            [self::SECRET_KEY => $secret],
        );

        return new MfaWebauthnDelegatedStateDto($state->token, $secret);
    }

    /**
     * Builds the ceremony-scoped access control of the user the state belongs to.
     * It identifies the ceremony and must never reach code that grants access to data.
     *
     * @param string $state Delegated state.
     * @return \App\Utility\UserAccessControl
     * @throws \Cake\Http\Exception\BadRequestException When the state does not resolve to an active user.
     */
    public function resolve(string $state): UserAccessControl
    {
        /** @var \App\Model\Entity\User $user */
        $user = $this->findActiveStateOrFail($state)->user;

        return new UserAccessControl($user->role->name, $user->id, $user->username);
    }

    /**
     * @param string $state Delegated state.
     * @return string
     * @throws \Cake\Http\Exception\BadRequestException When the state does not resolve to an active user.
     */
    public function getSecretForState(string $state): string
    {
        return $this->findActiveStateOrFail($state)->getDataValue(self::SECRET_KEY)
            ?? throw new BadRequestException(__('The delegated state is not valid.'));
    }

    /**
     * @param string $state Delegated state.
     * @return \App\Model\Entity\AuthenticationToken Carrying its user.
     * @throws \Cake\Http\Exception\BadRequestException When the state is unknown, expired, superseded or signed already,
     *   or when its user is not active. Every one of those reports the same error, so a caller cannot learn from the
     *   response whether a state exists.
     */
    private function findActiveStateOrFail(string $state): AuthenticationToken
    {
        if (!Validation::uuid($state)) {
            throw new BadRequestException(__('The delegated state is not valid.'));
        }

        /** @var \App\Model\Entity\AuthenticationToken|null $token */
        $token = $this->AuthenticationTokens
            ->find(
                'byTokenActiveNotExpired',
                token: $state,
                tokenType: AuthenticationToken::TYPE_MFA_DELEGATED_STATE,
            )
            ->contain(
                'Users',
                fn(SelectQuery $q): SelectQuery => $q->find('activeNotDeletedNotDisabledContainRole'),
            )
            ->first();

        if ($token === null || $token->user === null) {
            throw new BadRequestException(__('The delegated state is not valid.'));
        }

        return $token;
    }
}
