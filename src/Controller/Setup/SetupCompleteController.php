<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
namespace App\Controller\Setup;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Network\Exception\NotFoundException;
use Cake\Validation\Validation;

class SetupCompleteController extends AppController
{
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
     * Setup start
     *
     * @throws BadRequestException if the user or token id are missing or not uuids
     * @throws BadRequestException if the authentication token is expired or not valid for this user
     * @param string $userId uuid of the user
     * @param string $tokenId uuid of the token
     * @return void
     */
    public function complete($userId)
    {
        // Check request sanity
        $user = $this->_getAndAssertUser($userId);
        $token = $this->_getAndAssertToken($userId);
        $gpgkey = $this->_getAndAssertGpgkey($userId);

        // Either validation or checkRules error triggers this exception
        // User can still retry the full setup with the auth token
        if ($gpgkey->getErrors()) {
            $this->set('errors', $gpgkey->getErrors());
            throw new BadRequestException(__('The OpenPGP key data is not valid.'));
        }

        // Deactivate the authentication token
        $token->active = false;
        if (!$this->AuthenticationTokens->save($token, ['checkRules' => false])) {
            throw new InternalErrorException(__('Could not update the authentication token data.'));
        }

        // Save user GPG key, rules were already checked
        if (!$this->Gpgkeys->save($gpgkey, ['checkRules' => false])) {
            $this->set('errors', $gpgkey->getErrors());
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
     * @throws BadRequestException if no authentication token was provided
     * @throws BadRequestException if the authentication token is not a uuid
     * @return entity Token entity
     */
    protected function _getAndAssertToken($userId) {
        $data = $this->request->getData();
        if (!isset($data['AuthenticationToken']) || !isset($data['AuthenticationToken']['token'])) {
            throw new BadRequestException(__('An authentication token must be provided.'));
        }
        $tokenId = $data['AuthenticationToken']['token'];
        if (!Validation::uuid($tokenId)) {
            throw new BadRequestException(__('The authentication token should be a valid uuid.'));
        }
        if (!$this->AuthenticationTokens->isValid($tokenId, $userId)) {
            throw new BadRequestException(__('The authentication token is not valid or has expired.'));
        }
        $token = $this->AuthenticationTokens->findByToken($tokenId);
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
    protected function _getAndAssertUser($userId) {
        if (!Validation::uuid($userId)) {
            throw new BadRequestException(__('The user id is not valid. It should be a uuid.'));
        }
        $user = $this->Users->findSetupStart($userId);
        if (empty($user)) {
            // @TODO more precise error message
            throw new NotFoundException(__('The user does not exist or is already active or has been deleted.'));
        }
        return $user;
    }

    /**
     * Return the gpg key entity for matching the requesting id
     *
     * @param string $userId the user uuid
     * @throws BadRequestException if the user id is not a valid uuid
     * @throws BadRequestException if the user was deleted, is already active or does not exist
     * @return object Gpgkey entity
     */
    protected function _getAndAssertGpgkey($userId) {
        $data = $this->request->getData();
        $armoredKey = null;

        if (isset($data['Gpgkey']['armored_key'])) {
            $armoredKey = $data['Gpgkey']['armored_key'];
        }
        if (isset($data['Gpgkey']['key'])) {
            // legacy name v1 backward compatibility support
            $armoredKey = $data['Gpgkey']['key'];
        }
        if (empty($armoredKey)) {
            throw new BadRequestException(__('An OpenPGP key must be provided.'));
        }

        $this->loadModel('Gpgkeys');
        $gpgkey = $this->Gpgkeys->buildEntityFromArmoredKey($armoredKey, $userId);

        return $gpgkey;
    }
}
