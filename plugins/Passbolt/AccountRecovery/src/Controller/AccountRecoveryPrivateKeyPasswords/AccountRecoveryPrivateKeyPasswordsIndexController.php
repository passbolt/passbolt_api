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

namespace Passbolt\AccountRecovery\Controller\AccountRecoveryPrivateKeyPasswords;

use App\Controller\AppController;
use Cake\Http\Exception\ForbiddenException;

/**
 * @property \Passbolt\AccountRecovery\Model\Table\AccountRecoveryPrivateKeyPasswordsTable $AccountRecoveryPrivateKeyPasswords
 */
class AccountRecoveryPrivateKeyPasswordsIndexController extends AppController
{
    public $paginate = [
        'sortableFields' => [
            'AccountRecoveryPrivateKeyPasswords.created',
            'AccountRecoveryPrivateKeyPasswords.modified',
        ],
    ];

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel('Passbolt/AccountRecovery.AccountRecoveryPrivateKeyPasswords');
        $this->loadComponent('ApiPagination', [
            'model' => 'AccountRecoveryPrivateKeyPasswords',
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

        $passwords = $this->AccountRecoveryPrivateKeyPasswords->find();
        $this->paginate($passwords);

        $this->success(__('The operation was successful.'), $passwords);
    }
}
