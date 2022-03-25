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
use Cake\Event\EventInterface;
use Passbolt\AccountRecovery\Service\AccountRecoveryRequests\AccountRecoveryGetRequestService;

/**
 * @property \Passbolt\AccountRecovery\Model\Table\AccountRecoveryOrganizationPoliciesTable $AccountRecoveryOrganizationPolicy
 */
class AccountRecoveryRequestsGetController extends AppController
{
    public const ACCOUNT_RECOVERY_GET_BAD_REQUEST = 'account_recovery_get_bad_request';

    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        $this->Authentication->allowUnauthenticated(['getJson']);

        return parent::beforeFilter($event);
    }

    /**
     * Gets an account recovery request
     * Sends an email to the admins on suspect request
     *
     * @param string|null $requestId Request ID
     * @param string|null $userId User ID
     * @param string|null $tokenId Token ID
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException if the data provided is not valid
     */
    public function getJson(?string $requestId, ?string $userId, ?string $tokenId): void
    {
        $service = new AccountRecoveryGetRequestService(
            compact('requestId', 'userId', 'tokenId'),
            $this->getRequest()->clientIp()
        );
        $data = $service->getData();

        $this->success(__('The operation was successful.'), $data);
    }
}
