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

use App\Error\Exception\CustomValidationException;
use App\Error\Exception\ValidationException;
use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\User;
use App\Service\OpenPGP\PublicKeyCanEncryptCheckService;
use App\Service\OpenPGP\PublicKeyValidationService;
use App\Service\Users\UserGetService;
use Cake\Core\Configure;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use Cake\Log\Log;

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
     * @param array|null $saveOptions options
     * @return \App\Model\Entity\User
     */
    public function complete(string $userId, ?array $saveOptions = []): User
    {
        $user = $this->buildUserEntity($userId);

        return $this->saveUserEntity($user, $saveOptions);
    }

    /**
     * Build the user entity to be saved.
     * This method can be extended if additional associations are needed.
     *
     * @param string $userId User ID
     * @return \App\Model\Entity\User
     */
    protected function buildUserEntity(string $userId): User
    {
        // Check request sanity
        $user = $this->getAndAssertUser($userId);
        $token = $this->getAndAssertToken($userId, AuthenticationToken::TYPE_REGISTER);
        $gpgkey = $this->getAndAssertGpgkey($userId);

        // New with 3.6 - Check armored key content
        // The key must not be expired or revoked, or have multiple key blocks, etc.
        // TODO w4.0 - Move to getAndAssertGpgkey
        //  Will break compat on recover for non compliant keys
        PublicKeyValidationService::parseAndValidatePublicKey(
            $gpgkey->armored_key,
            PublicKeyValidationService::getStrictRules()
        );

        // Check business rules before saving
        $this->Gpgkeys->checkRules($gpgkey);
        if ($gpgkey->getErrors()) {
            throw new ValidationException(__('The OpenPGP key data is not valid.'), $gpgkey, $this->Gpgkeys);
        }

        // Check key can be used to encrypt
        // This can happen for example if the key is created in the future
        // or some other issue prevent the backend to use it, we don't want to fail at the login step
        if (Configure::read('passbolt.gpg.experimental.encryptValidate')) {
            if (!PublicKeyCanEncryptCheckService::check($gpgkey->armored_key, $gpgkey->fingerprint)) {
                $msg = __('The OpenPGP key can not be used to encrypt.');
                Log::debug($msg, [$gpgkey->armored_key]);
                throw new CustomValidationException($msg, ['gpgkey' => ['armored_key' => $msg]]);
            }
        }

        $user->active = true;
        $token->active = false;
        $user->authentication_tokens = [$token];
        $user->gpgkey = $gpgkey;

        return $user;
    }

    /**
     * Saves and performs some checks on the user and its association
     *
     * @param \App\Model\Entity\User $user User to save
     * @param array|null $saveOptions options
     * @return \App\Model\Entity\User
     * @throws \Cake\Http\Exception\InternalErrorException
     */
    protected function saveUserEntity(User $user, ?array $saveOptions = []): User
    {
        $this->Users->save($user, $saveOptions);

        if ($user->authentication_tokens[0]->hasErrors()) {
            throw new InternalErrorException('Could not update the authentication token data.');
        }

        if ($user->gpgkey->hasErrors()) {
            throw new InternalErrorException('Could not save the OpenPGP key data.');
        }

        if ($user->hasErrors()) {
            throw new InternalErrorException('Could not save the user data.');
        }

        return $user;
    }

    /**
     * Return the user for matching the requesting id
     *
     * @param string $userId the user uuid
     * @throws \Cake\Http\Exception\BadRequestException if the user id is not a valid uuid
     * @throws \Cake\Http\Exception\BadRequestException if the user was deleted, is already active or does not exist
     * @return \App\Model\Entity\User user entity
     */
    protected function getAndAssertUser(string $userId): User
    {
        try {
            return (new UserGetService())->getNotActiveNotDeletedNotDisabledOrFail($userId);
        } catch (NotFoundException $exception) {
            $msg = __('The user does not exist, is already active or has been deleted.');
            throw new BadRequestException($msg);
        }
    }
}
