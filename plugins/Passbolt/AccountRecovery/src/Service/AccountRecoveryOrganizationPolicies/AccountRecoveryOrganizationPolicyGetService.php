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
use Cake\ORM\Query;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy;

/**
 * @property \Passbolt\AccountRecovery\Model\Table\AccountRecoveryOrganizationPoliciesTable $AccountRecoveryOrganizationPolicies
 */
class AccountRecoveryOrganizationPolicyGetService implements AccountRecoveryOrganizationPolicyGetServiceInterface
{
    use ModelAwareTrait;

    /**
     * Get the current account recovery policy or fallback on the default one (disabled)
     *
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy
     */
    public function get(): AccountRecoveryOrganizationPolicy
    {
        $this->loadModel('Passbolt/AccountRecovery.AccountRecoveryOrganizationPolicies');

        try {
            /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy $policy */
            $policy = $this->AccountRecoveryOrganizationPolicies
                ->find()
                ->select()
                ->contain('AccountRecoveryOrganizationPublicKeys', function (Query $q) {
                    return $q->select([
                        'AccountRecoveryOrganizationPublicKeys.id',
                        'AccountRecoveryOrganizationPublicKeys.armored_key',
                    ]);
                })
                ->order(['AccountRecoveryOrganizationPolicies.created' => 'ASC'])
                ->firstOrFail();
        } catch (RecordNotFoundException $exception) {
            $policy = $this->getDefaultPolicy();
        }

        return $policy;
    }

    /**
     * Get a disabled AccountRecoveryOrganizationPolicy entity
     * with an empty key and no creation / modified date
     * Used as a fallback when no policy is present
     *
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy
     */
    public function getDefaultPolicy(): AccountRecoveryOrganizationPolicy
    {
        return $this->AccountRecoveryOrganizationPolicies
            ->newEntity([
                'policy' => AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_DISABLED,
                'public_key_id' => null,
            ], [
                'accessibleFields' => [
                    'policy' => true,
                    'public_key_id' => true,
                ],
            ]);
    }
}
