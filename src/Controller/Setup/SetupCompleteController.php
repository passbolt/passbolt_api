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

use App\Controller\AppController;
use App\Error\Exception\CustomValidationException;
use App\Error\Exception\ValidationException;
use App\Model\Entity\AuthenticationToken;
use Cake\Event\Event;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Validation\Validation;

class SetupCompleteController extends AppController
{
    private $_data; // formated request data

    /**
     * Before filter
     *
     * @param Event $event An Event instance
     * @return \Cake\Http\Response|null
     */
    public function beforeFilter(Event $event)
    {
        $this->Auth->allow('complete');

        $this->loadModel('GpgKey');
        $this->loadModel('AuthenticationTokens');
        $this->loadModel('Users');

        return parent::beforeFilter($event);
    }

    /**
     * Setup completion
     * Save the user gpg public key and set the account to active
     *
     * @throws BadRequestException if the user id is not a valid uuid
     * @throws BadRequestException if the user was deleted, is already active or does not exist
     * @throws BadRequestException if no authentication token was provided
     * @throws BadRequestException if the authentication token is not a uuid
     * @throws BadRequestException if the authentication token is expired or invalid
     * @throws BadRequestException if the gpg key is not provided or not a valid OpenPGP key
     * @throws InternalErrorException if something went wrong when updating the data
     *
     * @param string $userId uuid of the user
     * @return void
     */
    public function complete(string $userId)
    {
        // Check request sanity
        $user = $this->_getAndAssertUser($userId);
        $token = $this->_getAndAssertToken($userId, AuthenticationToken::TYPE_REGISTER);
        $gpgkey = $this->_getAndAssertGpgkey($userId);

        // Check business rules before saving
        $this->Gpgkeys->checkRules($gpgkey);
        if ($gpgkey->getErrors()) {
            throw new ValidationException(__('The OpenPGP key data is not valid.'), $gpgkey, $this->Gpgkeys);
        }

        // Deactivate the authentication token
        $token->active = false;
        if (!$this->AuthenticationTokens->save($token, ['checkRules' => false])) {
            throw new InternalErrorException(__('Could not update the authentication token data.'));
        }

        // Save user GPG key, rules were already checked
        if (!$this->Gpgkeys->save($gpgkey, ['checkRules' => false])) {
            throw new InternalErrorException(__('Could not save the OpenPGP key data.'));
        }

        // Update the user
        $user->active = true;
        if (!$this->Users->save($user, ['checkRules' => false])) {
            throw new InternalErrorException(__('Could not save the user data.'));
        }

        $this->success(__('The setup was completed successfully!'));
    }

    /**
     * Return the authentication from data if any
     *
     * @param string $userId the user uuid the token belongs to
     * @param string $tokenType AuthenticationToken::TYPE_*
     * @throws BadRequestException if no authentication token was provided
     * @throws BadRequestException if the authentication token is not a uuid
     * @throws BadRequestException if the authentication token is expired or invalid
     * @return object Token entity
     */
    protected function _getAndAssertToken(string $userId, string $tokenType)
    {
        $data = $this->_formatRequestData();
        if (!isset($data['authenticationtoken']) || !isset($data['authenticationtoken']['token'])) {
            throw new BadRequestException(__('An authentication token must be provided.'));
        }
        $tokenId = $data['authenticationtoken']['token'];
        if (!Validation::uuid($tokenId)) {
            throw new BadRequestException(__('The authentication token should be a valid uuid.'));
        }
        if (!$this->AuthenticationTokens->isValid($tokenId, $userId, $tokenType)) {
            throw new BadRequestException(__('The authentication token is not valid or has expired.'));
        }
        $token = $this->AuthenticationTokens->getByToken($tokenId);

        return $token;
    }

    /**
     * Return the user for matching the requesting id
     *
     * @param string $userId the user uuid
     * @throws BadRequestException if the user id is not a valid uuid
     * @throws BadRequestException if the user was deleted, is already active or does not exist
     * @return bool if user id is valid
     */
    protected function _getAndAssertUser(string $userId)
    {
        if (!Validation::uuid($userId)) {
            throw new BadRequestException(__('The user id is not valid. It should be a uuid.'));
        }
        $user = $this->Users->findSetup($userId);
        if (empty($user)) {
            $msg = __('The user does not exist or is already active or has been deleted.');
            throw new BadRequestException($msg);
        }

        return $user;
    }

    /**
     * Return the gpg key entity for matching the requesting id
     *
     * @param string $userId the user uuid
     * @throws BadRequestException if the gpg key is not provided or not a valid OpenPGP key
     * @return object Gpgkey entity
     */
    protected function _getAndAssertGpgkey(string $userId)
    {
        $data = $this->_formatRequestData();
        $armoredKey = $data['gpgkey']['armored_key'];

        if (empty($armoredKey)) {
            throw new BadRequestException(__('An OpenPGP key must be provided.'));
        }

        $this->loadModel('Gpgkeys');
        if (!$this->Gpgkeys->isParsableArmoredPublicKey($armoredKey)) {
            throw new BadRequestException(__('A valid OpenPGP key must be provided.'));
        }
        try {
            $gpgkey = $this->Gpgkeys->buildEntityFromArmoredKey($armoredKey, $userId);
        } catch (CustomValidationException $e) {
            throw new BadRequestException(__('A valid OpenPGP key must be provided.'));
        }

        return $gpgkey;
    }

    /**
     * Format request data formatted for API v1 to API v2 format
     * Example:
     * - API v1: ['Gpgkey' => ['key' => '...'], 'AuthenticationToken' => [...]]
     * - API v2: ['gpgkey' => ['armored_key' => '...']', 'authenticationtoken' => [...]]
     *
     * @return null|array $data
     */
    protected function _formatRequestData()
    {
        if (!isset($this->_data)) {
            $data = $this->request->getData();
            if (isset($data['Gpgkey']) || isset($data['AuthenticationToken'])) {
                if (isset($data['Gpgkey']['armored_key'])) {
                    $this->_data['gpgkey']['armored_key'] = $data['Gpgkey']['armored_key'];
                }
                if (isset($data['Gpgkey']['key'])) {
                    // legacy name v1 backward compatibility support
                    $this->_data['gpgkey']['armored_key'] = $data['Gpgkey']['key'];
                }
                if (isset($data['AuthenticationToken'])) {
                    $this->_data['authenticationtoken'] = $data['AuthenticationToken'];
                }
            } else {
                $this->_data = $data;
            }
        }

        return $this->_data;
    }
}
