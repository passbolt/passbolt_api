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
 * @since         2.0.0
 */
namespace App\Controller\Users;

use App\Controller\AppController;
use App\Controller\Events\ControllerFindIndexOptionsBeforeMarshal;
use App\Model\Entity\Role;
use App\Model\Table\Dto\FindIndexOptions;
use Cake\Event\Event;

/**
 * @property UsersTable Users
 */
class UsersIndexController extends AppController
{
    /**
     * Before filter
     *
     * @param \Cake\Event\Event $event An Event instance
     * @return \Cake\Http\Response|null
     */
    public function beforeFilter(Event $event)
    {
        $this->loadModel('Users');

        return parent::beforeFilter($event);
    }

    /**
     * @return void
     */
    public function index()
    {
        $findIndexOptions = (new FindIndexOptions())
            ->allowContains([
                'last_logged_in', 'groups_users', 'gpgkey', 'profile', 'role',
                // @deprecate when v2.13 support drops
                'LastLoggedIn', // remapped ot last_logged_in in QueryStringComponent
            ])
            ->allowOrders(['User.username', 'User.created', 'User.modified'])
            ->allowOrders(['Profile.first_name', 'Profile.last_name', 'Profile.created', 'Profile.modified'])
            ->allowFilters(['search', 'has-groups', 'has-access', 'is-admin']);

        if ($this->User->role() === Role::ADMIN) {
            $findIndexOptions->allowFilter('is-active');
        }

        // Get additional filters, contain, etc. from plugin
        $event = ControllerFindIndexOptionsBeforeMarshal::create($findIndexOptions, $this);
        $this->getEventManager()->dispatch($event);
        $computedFindIndexOptions = $this->QueryString->get(
            $event->getOptions()->getAllowedOptions(),
            $event->getOptions()->getFilterValidators()
        );

        $users = $this->Users->findIndex($this->User->role(), $computedFindIndexOptions);

        $this->success(__('The operation was successful.'), $users);
    }
}
