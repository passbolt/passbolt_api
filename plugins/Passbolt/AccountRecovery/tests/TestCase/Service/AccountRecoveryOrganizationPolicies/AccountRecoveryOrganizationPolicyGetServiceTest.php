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

namespace Passbolt\AccountRecovery\Test\TestCase\Service\AccountRecoveryOrganizationPolicies;

use Cake\Validation\Validation;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy;
use Passbolt\AccountRecovery\Service\AccountRecoveryOrganizationPolicies\AccountRecoveryOrganizationPolicyGetService;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPolicyFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryTestCase;

class AccountRecoveryOrganizationPolicyGetServiceTest extends AccountRecoveryTestCase
{
    /**
     * Get a disabled policy is returned if there is no record found
     */
    public function testAccountRecoveryOrganizationPolicyGetService_EmptySuccess()
    {
        $service = new AccountRecoveryOrganizationPolicyGetService();
        $policy = $service->get();
        $this->assertEquals(AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_DISABLED, $policy->policy);
        $this->assertEmpty($policy->id);
        $this->assertEmpty($policy->created);
        $this->assertEmpty($policy->modified);
        $this->assertEmpty($policy->created_by);
        $this->assertEmpty($policy->modified_by);
        $this->assertEmpty($policy->account_recovery_organization_public_key);
    }

    /**
     * Get a disabled policy is returned if there is no record found
     */
    public function testAccountRecoveryOrganizationPolicyGetService_DefaultSuccess()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->withAccountRecoveryOrganizationPublicKey()
            ->persist();

        $service = new AccountRecoveryOrganizationPolicyGetService();
        $policy = $service->get();
        $this->assertTrue(in_array($policy->policy, AccountRecoveryOrganizationPolicy::SUPPORTED_POLICIES));
        $this->assertTrue(Validation::uuid($policy->id));
        $this->assertTrue(Validation::datetime($policy->created));
        $this->assertTrue(Validation::datetime($policy->modified));
        $this->assertTrue(Validation::uuid($policy->created_by));
        $this->assertTrue(Validation::uuid($policy->modified_by));
    }
}
