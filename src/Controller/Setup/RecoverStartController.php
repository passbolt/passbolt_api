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

use App\Controller\Setup\SetupStartController;
use App\Model\Entity\AuthenticationToken;
use Cake\Http\Exception\BadRequestException;

class RecoverStartController extends SetupStartController
{
    /**
     * Recover start
     *
     * @throws BadRequestException if the user id is missing or not a uuid
     * @throws BadRequestException if the token is missing or not a uuid
     * @throws BadRequestException if the authentication token is expired or not valid for this user
     * @throws BadRequestException if the user does not exist or is not active
     *
     * @param string $userId uuid of the user
     * @param string $tokenId uuid of the token
     * @return void
     */
    public function start($userId, $tokenId)
    {
        // Check user id and token id are valid
        $this->_assertRequestSanity($userId, $tokenId, AuthenticationToken::TYPE_RECOVER);

        // Retrieve the user.
        $this->loadModel('Users');
        $user = $this->Users->findSetupRecover($userId);
        if (empty($user)) {
            $msg = __('The user does not exist or is not active.');
            throw new BadRequestException($msg);
        }
        $this->set('user', $user);

        // Parse the user agent
        $this->loadModel('UserAgents');
        $browserName = $this->UserAgents->browserName();
        $this->set('browserName', strtolower($browserName));

        $this->set('setupCase', 'recover');
        $this->viewBuilder()
            ->setTemplatePath('/Setup')
            ->setLayout('default')
            ->setTemplate('start');
    }
}
