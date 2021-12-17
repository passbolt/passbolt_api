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
 * @since         3.5.0
 */

namespace App\Service\Setup;

use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\User;
use Cake\Http\Exception\BadRequestException;
use Cake\View\ViewBuilder;

class RecoverStartService extends AbstractStartService implements RecoverStartServiceInterface
{
    /**
     * @inheritDoc
     */
    public function getInfo(string $userId, string $token): array
    {
        $this->assertRequestSanity($userId, $token);
        $user = $this->findUser($userId);
        $token = $this->findToken($user, $token);
        $this->assertTokenExpiry($user, $token);

        return compact('user');
    }

    /**
     * @inheritDoc
     */
    public function setTemplate(ViewBuilder $viewBuilder): void
    {
        $viewBuilder
            ->setTemplatePath('/Setup')
            ->setLayout('default')
            ->setTemplate('recoverStart');
    }

    /**
     * Find the user requesting the recover
     *
     * @param string $userId uuid of the user
     * @return \App\Model\Entity\User
     * @throw BadRequestException if the user cannot be found, is deleted or is inactive.
     */
    private function findUser(string $userId): User
    {
        $user = $this->Users->findSetupRecover($userId);
        if (empty($user)) {
            throw new BadRequestException(__('The user does not exist or is not active.'));
        }

        return $user;
    }

    /**
     * Find the recover token
     *
     * @param \App\Model\Entity\User $user user attempting to recover
     * @param string $token uuid of the token
     * @return \App\Model\Entity\AuthenticationToken
     * @throw BadRequestException if the token is not valid
     */
    private function findToken(User $user, string $token): AuthenticationToken
    {
        $finderOptions = ['userId' => $user->id, 'token' => $token];
        /** @var \App\Model\Entity\AuthenticationToken $token */
        $token = $this->AuthenticationTokens->find('activeUserRecoveryToken', $finderOptions)->first();
        if (empty($token)) {
            throw new BadRequestException(__('The authentication token is not valid.'));
        }

        return $token;
    }
}
