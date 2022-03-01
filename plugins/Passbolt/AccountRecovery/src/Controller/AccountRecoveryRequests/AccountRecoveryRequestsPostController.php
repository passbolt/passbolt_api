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
 * @since         3.5.0
 */

namespace Passbolt\AccountRecovery\Controller\AccountRecoveryRequests;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Event\EventInterface;
use Passbolt\AccountRecovery\Service\AccountRecoveryRequests\AccountRecoveryCreateRequestService;

/**
 * @property \Passbolt\AccountRecovery\Model\Table\AccountRecoveryOrganizationPoliciesTable $AccountRecoveryOrganizationPolicy
 */
class AccountRecoveryRequestsPostController extends AppController
{
    public const REQUEST_CREATED_EVENT_NAME = 'account_recovery_request_created';

    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        $this->Authentication->allowUnauthenticated(['post']);

        return parent::beforeFilter($event);
    }

    /**
     * Creates an account recovery request
     * Sends an email to the requesting user and the admins on success
     *
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException if the data provided is not valid
     */
    public function post(): void
    {
        $request = (new AccountRecoveryCreateRequestService($this->getRequest()))->create();

        $event = new Event(static::REQUEST_CREATED_EVENT_NAME, $request);
        $this->getEventManager()->dispatch($event);

        $this->success(__('The operation was successful.'));
    }
}
