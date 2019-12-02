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
 * @deprecated since v2.11.0. It extends the user session. Use AuthIsAuthenticatedController instead.
 */
namespace App\Controller\Auth;

use App\Controller\AppController;
use App\Model\Entity\Role;
use Cake\Event\Event;

class AuthCheckSessionController extends AppController
{
    /**
     * Before filter
     *
     * @param Event $event An Event instance
     * @return \Cake\Http\Response|null
     */
    public function beforeFilter(Event $event)
    {
        $this->Auth->allow('checkSessionGet');

        return parent::beforeFilter($event);
    }

    /**
     * User Index action
     *
     * @return void
     */
    public function checkSessionGet()
    {
        if ($this->User->role() !== Role::GUEST) {
            $this->success(__('Look! It\'s moving. It\'s alive. It\'s alive...'));
        } else {
            // Use AppController:error instead of exception to avoid logging the error
            $this->error(__('You need to login to access this location.'), null, 403);
        }
    }
}
