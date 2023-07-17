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

namespace Passbolt\AccountRecovery\Controller\AccountRecoveryResponses;

use App\Controller\AppController;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Passbolt\AccountRecovery\Service\AccountRecoveryResponses\AccountRecoveryResponsesCreateService;

/**
 * @property \Passbolt\AccountRecovery\Model\Table\AccountRecoveryOrganizationPoliciesTable $AccountRecoveryOrganizationPolicy
 */
class AccountRecoveryResponsesCreateController extends AppController
{
    /**
     * Creates an account recovery response
     * Sends an email to the requesting user and the admins on success
     *
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException if the data provided is not valid
     */
    public function post(): void
    {
        if (!$this->User->isAdmin()) {
            throw new ForbiddenException(__('Only admin are allowed to create an account recovery request.'));
        }

        $data = $this->getRequest()->getData();
        if (!isset($data) || !is_array($data) || empty($data)) {
            throw new BadRequestException(__('Invalid request. Please provide the required data.'));
        }

        $response = (new AccountRecoveryResponsesCreateService())->create($this->User->getAccessControl(), $data);

        $this->success(__('The operation was successful.'), $response);
    }
}
