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
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Validation\Validation;

/**
 * @property \Passbolt\AccountRecovery\Model\Table\AccountRecoveryRequestsTable $AccountRecoveryRequests
 */
class AccountRecoveryRequestsViewController extends AppController
{
    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        /** @phpstan-ignore-next-line */
        $this->AccountRecoveryRequests = $this->fetchTable('Passbolt/AccountRecovery.AccountRecoveryRequests');
    }

    /**
     * List the details of one account recovery request
     *
     * @param string $id uuid of the request
     * @throws \Cake\Http\Exception\ForbiddenException if the user is not an admin
     * @throws \Cake\Http\Exception\NotFoundException if request id could not be found
     * @throws \Cake\Http\Exception\BadRequestException if the id is not a uuid
     * @return void
     */
    public function view(string $id): void
    {
        if (!$this->User->isAdmin()) {
            throw new ForbiddenException(__('You are not authorized to access that location.'));
        }
        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('Please provide a valid request id.'));
        }

        // Whitelisted filters and contain parameters
        $options = $this->QueryString->get([
            'contain' => [
                'armored_key', 'account_recovery_private_key_passwords',
                'account_recovery_request_responses',
                'creator',
            ],
        ]);

        $options['id'] = $id;
        $request = $this->AccountRecoveryRequests->findView($options)->firstOrFail();

        $this->success(__('The operation was successful.'), $request);
    }
}
