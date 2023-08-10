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

namespace Passbolt\SsoRecover\Service;

use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\User;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Routing\Router;

class SsoRecoverStartService
{
    use LocatorAwareTrait;

    /**
     * Generates auth token and returns recover(or setup) URL.
     *
     * @param string $userId User identifier.
     * @return string
     */
    public function generateAndGetRecoverUrl(string $userId): string
    {
        $user = $this->getUser($userId);

        $type = $this->getType($user);

        $authToken = $this->generateAuthToken($user, $type);

        /**
         * Prepare URL.
         *
         * Note: Here we are using v1 URLs as new ones(with the "start" e.g. "/setup/start") is not implemented in BExt.
         */
        if ($type === AuthenticationToken::TYPE_RECOVER) {
            $url = Router::url("/setup/recover/{$user->id}/{$authToken->token}", true);
        } else {
            $url = Router::url("/setup/start/{$user->id}/{$authToken->token}", true);
        }

        return $url;
    }

    /**
     * Determines if user should be redirected to recover flow or setup flow.
     *
     * @param \App\Model\Entity\User $user User entity.
     * @return string
     */
    private function getType(User $user): string
    {
        if ($user->active) {
            // The user is already active, so it is anticipated that they have to recover the account
            return AuthenticationToken::TYPE_RECOVER;
        }

        // The user has not completed the setup, restart setup
        return AuthenticationToken::TYPE_REGISTER;
    }

    /**
     * Returns user entity from user identifier.
     *
     * @param string $userId User identifier.
     * @return \App\Model\Entity\User
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When user is not found.
     */
    private function getUser(string $userId): User
    {
        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = $this->fetchTable('Users');

        /** @var \App\Model\Entity\User $user */
        $user = $usersTable->findById($userId)->firstOrFail();

        return $user;
    }

    /**
     * Generate and store authentication token.
     *
     * @param \App\Model\Entity\User $user User entity.
     * @param string $type Type, can be "register"(aka setup) or "recover".
     * @return \App\Model\Entity\AuthenticationToken
     */
    private function generateAuthToken(User $user, string $type): AuthenticationToken
    {
        /** @var \App\Model\Table\AuthenticationTokensTable $authenticationTokensTable */
        $authenticationTokensTable = $this->fetchTable('AuthenticationTokens');

        return $authenticationTokensTable->generate($user->id, $type);
    }
}
