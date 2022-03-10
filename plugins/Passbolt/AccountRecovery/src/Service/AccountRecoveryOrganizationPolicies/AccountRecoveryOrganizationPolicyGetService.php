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

namespace Passbolt\AccountRecovery\Service\AccountRecoveryOrganizationPolicies;

use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ModelAwareTrait;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy;

/**
 * @property \Passbolt\AccountRecovery\Model\Table\AccountRecoveryOrganizationPoliciesTable $AccountRecoveryOrganizationPolicies
 */
class AccountRecoveryOrganizationPolicyGetService implements AccountRecoveryOrganizationPolicyGetServiceInterface
{
    use ModelAwareTrait;

    /**
     * AccountRecoveryOrganizationPolicyGetService constructor.
     */
    public function __construct()
    {
        $this->loadModel('Passbolt/AccountRecovery.AccountRecoveryOrganizationPolicies');
    }

    /**
     * Get the current account recovery policy or fallback on the default one (disabled)
     *
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy
     */
    public function get(): AccountRecoveryOrganizationPolicy
    {
        try {
            $policy = $this->AccountRecoveryOrganizationPolicies->getCurrentPolicyOrFail();
        } catch (RecordNotFoundException $exception) {
            $policy = $this->AccountRecoveryOrganizationPolicies->newEntityForDefaultFallback();
        }

        return $policy;
    }
}
