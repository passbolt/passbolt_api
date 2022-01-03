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

use App\Controller\Setup\RecoverCompleteController;
use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\User;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Validation\Validation;

class RecoverCompleteService extends AbstractCompleteService implements RecoverCompleteServiceInterface
{
    /**
     * Recovery completion
     * Check if the public key matches the currently stored fingerprint
     * Unlike setup completion we do not update anything
     *
     * @throws \Cake\Http\Exception\BadRequestException if the user id is not a valid uuid
     * @throws \Cake\Http\Exception\BadRequestException if the user was deleted, is already active or does not exist
     * @throws \Cake\Http\Exception\BadRequestException if no authentication token was provided
     * @throws \Cake\Http\Exception\BadRequestException if the authentication token is not a uuid
     * @throws \Cake\Http\Exception\BadRequestException if the authentication token is expired or invalid
     * @throws \Cake\Http\Exception\BadRequestException if the OpenPGP key is not provided or not a valid OpenPGP key
     * @throws \Cake\Http\Exception\BadRequestException if the OpenPGP key does not belong to the user
     * @throws \Cake\Http\Exception\InternalErrorException if something went wrong when updating the data
     * @param string $userId uuid of the user
     * @return void
     */
    public function complete(string $userId): void
    {
        // Check request sanity
        $user = $this->getAndAssertUser($userId);
        $token = $this->getAndAssertToken($userId, AuthenticationToken::TYPE_RECOVER);
        $gpgkey = $this->getAndAssertGpgkey($userId);

        // Check that the "new" gpg key match the old one
        $userKey = $this->Gpgkeys->getByFingerprintAndUserId($gpgkey->fingerprint, $userId);
        if (empty($userKey)) {
            throw new BadRequestException(__('The key provided does not belong to given user.'));
        }

        // Deactivate the authentication token
        $token->active = false;
        if (!$this->AuthenticationTokens->save($token, ['checkRules' => false])) {
            throw new InternalErrorException('Could not update the authentication token data.');
        }

        $this->dispatchEvent(RecoverCompleteController::COMPLETE_SUCCESS_EVENT_NAME, [
            'user' => $user,
            'data' => $this->request->getData(),
        ]);
    }

    /**
     * Return the user for matching the requesting id
     *
     * @param string $userId the user uuid
     * @throws \Cake\Http\Exception\BadRequestException if the user id is not a valid uuid
     * @throws \Cake\Http\Exception\BadRequestException if the user was deleted or has not completed the setup
     * @return \App\Model\Entity\User
     */
    protected function getAndAssertUser(string $userId): User
    {
        if (!Validation::uuid($userId)) {
            throw new BadRequestException(__('The user identifier should be a valid UUID.'));
        }
        $user = $this->Users->findSetupRecover($userId);
        if (empty($user)) {
            $msg = __('The user does not exist, has not completed the setup or was deleted.');
            throw new BadRequestException($msg);
        }

        return $user;
    }
}
