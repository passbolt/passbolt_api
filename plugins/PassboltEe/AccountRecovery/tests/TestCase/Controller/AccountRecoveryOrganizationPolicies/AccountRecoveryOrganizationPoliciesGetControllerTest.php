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

namespace Passbolt\AccountRecovery\Test\TestCase\Controller\AccountRecoveryOrganizationPolicies;

use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPolicyFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryIntegrationTestCase;

class AccountRecoveryOrganizationPoliciesGetControllerTest extends AccountRecoveryIntegrationTestCase
{
    /**
     * check accessing organization policies without being logged in triggers an error
     */
    public function testAccountRecoveryOrganizationPoliciesGetController_ErrorNotLoggedIn()
    {
        $this->getJson('/account-recovery/organization-policies.json');
        $this->assertResponseCode(401);
        $this->assertResponseError('You need to login to access this location.');
    }

    /**
     * check accessing organization policies works when enabled
     */
    public function testAccountRecoveryOrganizationPoliciesGetController_Success_Optin()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->optin()
            ->withAccountRecoveryOrganizationPublicKey()
            ->persist();

        $this->logInAsUser();
        $this->getJson('/account-recovery/organization-policies.json');
        $this->assertSuccess();
        $this->assertEquals($this->_responseJsonBody->policy, AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_OPT_IN);
        $this->assertNotEmpty($this->_responseJsonBody->account_recovery_organization_public_key);
    }

    /**
     * check accessing organization policies works when disabled
     */
    public function testAccountRecoveryOrganizationPoliciesGetController_Success_Disabled()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->disabled()
            ->persist();

        $this->logInAsUser();
        $this->getJson('/account-recovery/organization-policies.json');
        $this->assertSuccess();
        $this->assertEquals($this->_responseJsonBody->policy, AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_DISABLED);
        $this->assertEmpty($this->_responseJsonBody->public_key_id);
        $this->assertTrue(!isset($this->_responseJsonBody->account_recovery_organization_public_key));
    }

    /**
     * check accessing organization policies works when no record present
     */
    public function testAccountRecoveryOrganizationPoliciesGetController_Success_DisabledNoRecord()
    {
        $this->logInAsUser();
        $this->getJson('/account-recovery/organization-policies.json');
        $this->assertSuccess();
        $this->assertEquals($this->_responseJsonBody->policy, AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_DISABLED);
        $this->assertEmpty($this->_responseJsonBody->public_key_id);
        $this->assertTrue(!isset($this->_responseJsonBody->account_recovery_organization_public_key));
        $this->assertTrue(!isset($this->_responseJsonBody->created));
        $this->assertTrue(!isset($this->_responseJsonBody->modified));
    }
}
