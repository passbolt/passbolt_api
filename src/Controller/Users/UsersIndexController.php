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

/**
 * @property \App\Model\Table\UsersTable $Users
 * @property \BryanCrowe\ApiPagination\Controller\Component\ApiPaginationComponent $ApiPagination
 */
class UsersIndexController extends AppController
{
    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ApiPagination', [
            'model' => 'Users',
        ]);
    }

    public $paginate = [
        'sortableFields' => [
            'Profiles.first_name',
            'Profiles.last_name',
            'Profiles.created',
            'Profiles.modified',
            'Users.username',
            'Users.created',
            'Users.modified',
            'Users.last_logged_in',
        ],
        'order' => [
            'Users.username' => 'asc', // Default sorted field
        ],
    ];

    /**
     * @return void
     */
    public function index()
    {
        $this->loadModel('Users');

        $findIndexOptions = (new FindIndexOptions())
            ->allowContains([
                'last_logged_in', 'groups_users', 'gpgkey', 'profile', 'role',
                // @deprecate when v2.13 support drops
                'LastLoggedIn', // remapped to last_logged_in in QueryStringComponent
            ])
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
        $this->paginate($users);
        $this->success(__('The operation was successful.'), $users);
    }
}
