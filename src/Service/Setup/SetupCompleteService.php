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

use App\Controller\Setup\SetupCompleteController;
use App\Error\Exception\ValidationException;
use App\Model\Entity\AuthenticationToken;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Validation\Validation;

class SetupCompleteService extends AbstractCompleteService implements SetupCompleteServiceInterface
{
    /**
     * Setup completion
     * Save the user gpg public key and set the account to active
     *
     * @throws \Cake\Http\Exception\BadRequestException if the user id is not a valid uuid
     * @throws \Cake\Http\Exception\BadRequestException if the user was deleted, is already active or does not exist
     * @throws \Cake\Http\Exception\BadRequestException if no authentication token was provided
     * @throws \Cake\Http\Exception\BadRequestException if the authentication token is not a uuid
     * @throws \Cake\Http\Exception\BadRequestException if the authentication token is expired or invalid
     * @throws \Cake\Http\Exception\BadRequestException if the OpenPGP key is not provided or not a valid OpenPGP key
     * @throws \Cake\Http\Exception\InternalErrorException if something went wrong when updating the data
     * @param string $userId uuid of the user
     * @return void
     */
    public function complete(string $userId): void
    {
        // Check request sanity
        $user = $this->getAndAssertUser($userId);
        $token = $this->getAndAssertToken($userId, AuthenticationToken::TYPE_REGISTER);
        $gpgkey = $this->getAndAssertGpgkey($userId);

        // Check business rules before saving
        $this->Gpgkeys->checkRules($gpgkey);
        if ($gpgkey->getErrors()) {
            throw new ValidationException(__('The OpenPGP key data is not valid.'), $gpgkey, $this->Gpgkeys);
        }

        // Deactivate the authentication token
        $token->active = false;
        if (!$this->AuthenticationTokens->save($token, ['checkRules' => false])) {
            throw new InternalErrorException('Could not update the authentication token data.');
        }

        // Save user GPG key, rules were already checked
        if (!$this->Gpgkeys->save($gpgkey, ['checkRules' => false])) {
            throw new InternalErrorException('Could not save the OpenPGP key data.');
        }

        // Update the user
        $user->active = true;
        if (!$this->Users->save($user, ['checkRules' => false])) {
            throw new InternalErrorException('Could not save the user data.');
        }

        $this->dispatchEvent(SetupCompleteController::COMPLETE_SUCCESS_EVENT_NAME, [
            'user' => $user,
            'data' => $this->request->getData(),
        ]);
    }

    /**
     * Return the user for matching the requesting id
     *
     * @param string $userId the user uuid
     * @throws \Cake\Http\Exception\BadRequestException if the user id is not a valid uuid
     * @throws \Cake\Http\Exception\BadRequestException if the user was deleted, is already active or does not exist
     * @return \App\Model\Entity\User user entity
     */
    protected function getAndAssertUser(string $userId)
    {
        if (!Validation::uuid($userId)) {
            throw new BadRequestException(__('The user identifier should be a valid UUID.'));
        }
        $user = $this->Users->findSetup($userId);
        if (empty($user)) {
            $msg = __('The user does not exist, is already active or has been deleted.');
            throw new BadRequestException($msg);
        }

        return $user;
    }
}
