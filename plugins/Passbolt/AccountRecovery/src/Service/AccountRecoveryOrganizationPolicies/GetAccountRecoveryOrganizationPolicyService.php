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

namespace Passbolt\AccountRecovery\Service\AccountRecoveryOrganizationPolicies;

use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ModelAwareTrait;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy;

/**
 * @property \Passbolt\AccountRecovery\Model\Table\AccountRecoveryOrganizationPoliciesTable $AccountRecoveryOrganizationPolicies
 */
class GetAccountRecoveryOrganizationPolicyService
{
    use ModelAwareTrait;

    /**
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When there is no policy set.
     */
    public function get(): AccountRecoveryOrganizationPolicy
    {
        $this->loadModel('Passbolt/AccountRecovery.AccountRecoveryOrganizationPolicies');

        try {
            /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy $policy */
            $policy = $this->AccountRecoveryOrganizationPolicies
                ->find()
                ->order(['AccountRecoveryOrganizationPolicies.created' => 'ASC'])
                ->firstOrFail();
        } catch (RecordNotFoundException $exception) {
            $policy = $this->AccountRecoveryOrganizationPolicies
                ->newEntity([
                    'policy' => AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_DISABLED,
                ], [
                    'accessibleFields' => [
                        'policy' => true,
                    ],
                ]);
        }

        return $policy;
    }
}
