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
use App\Model\Entity\Role;
use Cake\Event\EventInterface;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Passbolt\AccountRecovery\Service\AccountRecoveryRequests\AccountRecoveryRequestCreateService;

/**
 * @property \Passbolt\AccountRecovery\Model\Table\AccountRecoveryOrganizationPoliciesTable $AccountRecoveryOrganizationPolicy
 */
class AccountRecoveryRequestsCreateController extends AppController
{
    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        $this->Authentication->allowUnauthenticated(['create']);

        return parent::beforeFilter($event);
    }

    /**
     * Creates an account recovery request
     * Sends an email to the requesting user and the admins on success
     *
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException if the data provided is not valid
     */
    public function create(): void
    {
        if ($this->User->role() !== Role::GUEST) {
            throw new ForbiddenException(__('Only guests are allowed to create an account recovery request.'));
        }

        $data = $this->getRequest()->getData();
        if (!isset($data) || !is_array($data) || empty($data)) {
            throw new BadRequestException(__('Invalid request. Please provide the required data.'));
        }

        $request = (new AccountRecoveryRequestCreateService())->create($data);

        $this->success(__('The operation was successful.'), $request);
    }
}
