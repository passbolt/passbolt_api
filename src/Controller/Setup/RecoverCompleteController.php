<?php
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
 * @since         2.0.0
 */
namespace App\Controller\Setup;

use App\Model\Entity\AuthenticationToken;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Validation\Validation;

class RecoverCompleteController extends SetupCompleteController
{
    /**
     * Recovery completion
     * Check if the public key matches the currently stored fingerprint
     * Unlike setup completion we do not update anything
     *
     * @throws BadRequestException if the user id is not a valid uuid
     * @throws BadRequestException if the user was deleted, is already active or does not exist
     * @throws BadRequestException if no authentication token was provided
     * @throws BadRequestException if the authentication token is not a uuid
     * @throws BadRequestException if the authentication token is expired or invalid
     * @throws BadRequestException if the gpg key is not provided or not a valid OpenPGP key
     * @throws BadRequestException if the gpg key does not belong to the user
     * @throws InternalErrorException if something went wrong when updating the data
     *
     * @param string $userId uuid of the user
     * @return void
     */
    public function complete(string $userId)
    {
        // Check request sanity
        $this->_getAndAssertUser($userId);
        $token = $this->_getAndAssertToken($userId, AuthenticationToken::TYPE_RECOVER);
        $gpgkey = $this->_getAndAssertGpgkey($userId);

        // Check that the "new" gpg key match the old one
        $userKey = $this->Gpgkeys->getByFingerprintAndUserId($gpgkey->fingerprint, $userId);
        if (empty($userKey)) {
            throw new BadRequestException(__('The key provided does not belong to given user.'));
        }

        // Deactivate the authentication token
        $token->active = false;
        if (!$this->AuthenticationTokens->save($token, ['checkRules' => false])) {
            throw new InternalErrorException(__('Could not update the authentication token data.'));
        }

        $this->success(__('The recovery was completed successfully!'));
    }

    /**
     * Return the user for matching the requesting id
     *
     * @param string $userId the user uuid
     * @throws BadRequestException if the user id is not a valid uuid
     * @throws BadRequestException if the user was deleted or has not completed the setup
     * @return bool if user id is valid
     */
    protected function _getAndAssertUser(string $userId)
    {
        if (!Validation::uuid($userId)) {
            throw new BadRequestException(__('The user id is not valid. It should be a uuid.'));
        }
        $user = $this->Users->findSetupRecover($userId);
        if (empty($user)) {
            $msg = __('The user does not exist or has not completed the setup or was deleted.');
            throw new BadRequestException($msg);
        }

        return $user;
    }
}
