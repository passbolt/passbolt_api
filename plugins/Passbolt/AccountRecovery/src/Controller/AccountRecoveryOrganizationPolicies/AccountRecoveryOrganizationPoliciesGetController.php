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
 * @since         3.4.0
 */

namespace Passbolt\AccountRecovery\Controller\AccountRecoveryOrganizationPolicies;

use App\Controller\AppController;
use Passbolt\AccountRecovery\Service\AccountRecoveryOrganizationPolicies\GetAccountRecoveryOrganizationPolicyService;

/**
 * @property \Passbolt\AccountRecovery\Model\Table\AccountRecoveryOrganizationPoliciesTable $AccountRecoveryOrganizationPolicy
 */
class AccountRecoveryOrganizationPoliciesGetController extends AppController
{
    /**
     * @return void
     */
    public function get(): void
    {
        $policy = (new GetAccountRecoveryOrganizationPolicyService())->get();
        $this->success(__('The operation was successful.'), $policy);
    }
}
