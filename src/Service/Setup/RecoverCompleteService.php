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

use App\Error\Exception\ValidationException;
use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\User;
use App\Service\Users\UserGetService;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;

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
        $user = $this->validateData($userId);
        $token = $this->buildAuthenticationTokenEntity($userId);

        if (!$this->AuthenticationTokens->save($token)) {
            throw new ValidationException(
                __('Could not update the authentication token data.'),
                $token,
                $this->AuthenticationTokens
            );
        }

        $this->dispatchEvent(RecoverCompleteServiceInterface::COMPLETE_SUCCESS_EVENT_NAME, [
            'user' => $user,
            'data' => $this->request->getData(),
            'clientIp' => $this->request->clientIp(),
            'userAgent' => $this->request->getEnv('HTTP_USER_AGENT'),
        ]);
    }

    /**
     * Validate the user and the gpgkey
     *
     * @param string $userId User ID
     * @return \App\Model\Entity\User
     * @throws \Cake\Http\Exception\BadRequestException if the data provided is not valid
     */
    protected function validateData(string $userId): User
    {
        // Check request sanity
        $user = $this->getAndAssertUser($userId);
        $gpgkey = $this->getAndAssertGpgkey($userId);

        // Check that the "new" gpg key match the old one
        $userKey = $this->Gpgkeys->getByFingerprintAndUserId($gpgkey->fingerprint, $userId);
        if (empty($userKey)) {
            throw new BadRequestException(__('The key provided does not belong to given user.'));
        }

        return $user;
    }

    /**
     * Method to be extended if saving additional settings
     *
     * @param string $userId User ID
     * @return \App\Model\Entity\AuthenticationToken
     */
    protected function buildAuthenticationTokenEntity(string $userId): AuthenticationToken
    {
        $token = $this->getAndAssertToken($userId, AuthenticationToken::TYPE_RECOVER);
        // Deactivate the authentication token
        $token->active = false;

        return $token;
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
        try {
            return (new UserGetService())->getActiveNotDeletedNotDisabledOrFail($userId);
        } catch (NotFoundException $exception) {
            $msg = __('The user does not exist, has not completed the setup or was deleted.');
            throw new BadRequestException($msg);
        }
    }
}
