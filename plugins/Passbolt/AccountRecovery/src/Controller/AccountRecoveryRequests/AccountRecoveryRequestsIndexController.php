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
 * @since         3.6.0
 */

namespace Passbolt\AccountRecovery\Controller\AccountRecoveryRequests;

use App\Controller\AppController;
use Cake\Http\Exception\ForbiddenException;

/**
 * @property \Passbolt\AccountRecovery\Model\Table\AccountRecoveryRequestsTable $AccountRecoveryRequests
 */
class AccountRecoveryRequestsIndexController extends AppController
{
    public $paginate = [
        'sortableFields' => [
            'AccountRecoveryRequests.status',
            'AccountRecoveryRequests.created',
            'AccountRecoveryRequests.modified',
        ],
    ];

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel('Passbolt/AccountRecovery.AccountRecoveryRequests');
        $this->loadComponent('ApiPagination', [
            'model' => 'AccountRecoveryRequests',
        ]);
    }

    /**
     * List all the account recovery requests
     *
     * @return void
     * @throws \Cake\Http\Exception\ForbiddenException if the user is not an admin
     */
    public function index(): void
    {
        if (!$this->User->isAdmin()) {
            throw new ForbiddenException(__('You are not authorized to access that location.'));
        }

        // Whitelisted filters and contain parameters
        $options = $this->QueryString->get([
            'filter' => ['has-users'],
            'contain' => [
                'armored_key',
                'account_recovery_private_key_passwords',
                'account_recovery_request_responses',
                'creator',
            ],
            'order' => ['created', 'modified'],
        ]);

        $requests = $this->AccountRecoveryRequests->findIndex($options);
        $this->paginate($requests);

        $this->success(__('The operation was successful.'), $requests);
    }
}
