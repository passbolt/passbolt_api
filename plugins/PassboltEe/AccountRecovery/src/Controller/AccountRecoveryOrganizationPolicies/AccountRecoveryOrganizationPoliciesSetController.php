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

namespace Passbolt\AccountRecovery\Controller\AccountRecoveryOrganizationPolicies;

use App\Controller\AppController;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Passbolt\AccountRecovery\Service\AccountRecoveryOrganizationPolicies\AccountRecoveryOrganizationPolicySetServiceInterface; // phpcs:ignore

/**
 * @property \Passbolt\AccountRecovery\Model\Table\AccountRecoveryOrganizationPoliciesTable $AccountRecoveryOrganizationPolicy
 */
class AccountRecoveryOrganizationPoliciesSetController extends AppController
{
    /**
     * @param \Passbolt\AccountRecovery\Service\AccountRecoveryOrganizationPolicies\AccountRecoveryOrganizationPolicySetServiceInterface $service Service
     * @return void
     */
    public function createOrUpdate(AccountRecoveryOrganizationPolicySetServiceInterface $service): void
    {
        $this->assertRequestSanity();
        $uac = $this->User->getAccessControl();
        $policy = $service->set($uac, $this->request->getData());
        $this->success(__('The operation was successful.'), $policy);
    }

    /**
     * @throw ForbiddenException if the user is not an administrator
     * @throw BadRequestException if no data are provided
     * @return void
     */
    protected function assertRequestSanity(): void
    {
        $this->assertIsAdmin();
        $this->assertRequestData();
    }

    /**
     * @throw BadRequestException if no data are provided
     * @return void
     */
    protected function assertRequestData(): void
    {
        $data = $this->request->getData();
        if (!isset($data) || !is_array($data) || !count($data)) {
            throw new BadRequestException(__('The request data should not be empty.'));
        }
    }

    /**
     * @throw ForbiddenException if the user is not an administrator
     * @return void
     */
    protected function assertIsAdmin(): void
    {
        if (!$this->User->isAdmin()) {
            throw new ForbiddenException(__('You are not allowed to access this location.'));
        }
    }
}
