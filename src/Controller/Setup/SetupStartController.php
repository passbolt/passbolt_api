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
use App\Model\Entity\AuthenticationToken;
use Cake\Event\Event;
use Cake\Http\Exception\BadRequestException;
use Cake\Validation\Validation;

class SetupStartController extends AppController
{
    /**
     * Before filter
     *
     * @param Event $event An Event instance
     * @return \Cake\Http\Response|null
     */
    public function beforeFilter(Event $event)
    {
        $this->Auth->allow('start');
        $this->loadModel('AuthenticationTokens');

        return parent::beforeFilter($event);
    }

    /**
     * Setup start
     *
     * @throws BadRequestException if the user id is missing or not a uuid
     * @throws BadRequestException if the token is missing or not a uuid
     * @throws BadRequestException if the authentication token is expired or not valid for this user
     * @throws BadRequestException if the user does not exist or is already active
     *
     * @param string $userId uuid of the user
     * @param string $tokenId uuid of the token
     * @return void
     */
    public function start($userId, $tokenId)
    {
        // Check user id and token id are valid
        $this->_assertRequestSanity($userId, $tokenId);

        // Retrieve the user.
        $this->loadModel('Users');
        $user = $this->Users->findSetup($userId);
        if (empty($user)) {
            $msg = __('The user does not exist or is already active or has been deleted.');
            throw new BadRequestException($msg);
        }
        $this->set('user', $user);

        // Parse the user agent
        $this->loadModel('UserAgents');
        $browserName = $this->UserAgents->browserName();
        $this->set('browserName', strtolower($browserName));

        $this->viewBuilder()
            ->setTemplatePath('/Setup')
            ->setLayout('default')
            ->setTemplate('start');
    }

    /**
     * Assert that the setup start request is valid
     *
     * @throws BadRequestException if the user id is missing or not a uuid
     * @throws BadRequestException if the token is missing or not a uuid
     * @throws BadRequestException if the authentication token is expired or not valid for this user
     * @param string $userId uuid
     * @param string $tokenId uuid
     * @param string $tokenType register or recover
     * @return void
     */
    protected function _assertRequestSanity($userId, $tokenId, $tokenType = AuthenticationToken::TYPE_REGISTER)
    {
        // Check request sanity
        if (!isset($userId)) {
            throw new BadRequestException(__('The user id is missing.'));
        }
        if (!Validation::uuid($userId)) {
            throw new BadRequestException(__('The user id is not valid. It should be a uuid.'));
        }
        if (!isset($tokenId)) {
            throw new BadRequestException(__('The authentication token is missing.'));
        }
        if (!Validation::uuid($tokenId)) {
            throw new BadRequestException(__('The token is not valid. It should be a uuid.'));
        }

        // Check that the token exists
        if (!$this->AuthenticationTokens->isValid($tokenId, $userId, $tokenType)) {
            throw new BadRequestException(__('The authentication token is not valid or expired.'));
        }
    }
}
