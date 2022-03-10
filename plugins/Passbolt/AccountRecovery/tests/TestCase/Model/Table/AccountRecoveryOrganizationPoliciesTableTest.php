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

namespace Passbolt\AccountRecovery\Test\TestCase\Model\Table;

use App\Test\Lib\Model\FormatValidationTrait;
use Cake\ORM\TableRegistry;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPolicyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPublicKeyFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryTestCase;

/**
 * @covers \Passbolt\AccountRecovery\Model\Table\AccountRecoveryOrganizationPoliciesTable
 */
class AccountRecoveryOrganizationPoliciesTableTest extends AccountRecoveryTestCase
{
    use FormatValidationTrait;

    protected $AccountRecoveryOrganizationPolicies;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->AccountRecoveryOrganizationPolicies = TableRegistry::getTableLocator()->get('Passbolt/AccountRecovery.AccountRecoveryOrganizationPolicies');
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->AccountRecoveryOrganizationPolicies);
        parent::tearDown();
    }

    /**
     * @return array Default options
     */
    private function getDefaultOptions(): array
    {
        return [
            'checkRules' => true,
            'accessibleFields' => [
                '*' => true,
            ],
        ];
    }

    /**
     * Check org id field validation rules
     */
    public function testAccountRecoveryOrganizationPoliciesTable_ValidationId()
    {
        $testCases = [
            'uuid' => self::getUuidTestCases(),
        ];
        $this->assertFieldFormatValidation(
            $this->AccountRecoveryOrganizationPolicies,
            'id',
            AccountRecoveryOrganizationPolicyFactory::make()->getEntity()->toArray(),
            $this->getDefaultOptions(),
            $testCases
        );
    }

    /**
     * Check org policy field validation rules
     */
    public function testAccountRecoveryOrganizationPoliciesTable_ValidationPolicy()
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'inList' => self::getInListTestCases(AccountRecoveryOrganizationPolicy::SUPPORTED_POLICIES),
        ];
        $this->assertFieldFormatValidation(
            $this->AccountRecoveryOrganizationPolicies,
            'policy',
            AccountRecoveryOrganizationPolicyFactory::make()->getEntity()->toArray(),
            $this->getDefaultOptions(),
            $testCases
        );
    }

    /**
     * Account Recovery Organization Policy is disabled if no policy is in the DB
     * or if the policy has no associated public key
     * or if the associated public key is deleted.
     */
    public function testAccountRecoveryOrganizationPoliciesTable_GetCurrentPolicy_Disabled()
    {
        $disabledPolicy = $this->AccountRecoveryOrganizationPolicies->getCurrentPolicyName();
        $this->assertSame(AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_DISABLED, $disabledPolicy);

        AccountRecoveryOrganizationPolicyFactory::make()->disabled()->persist();
        $disabledPolicy = $this->AccountRecoveryOrganizationPolicies->getCurrentPolicyName();
        $this->assertSame(AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_DISABLED, $disabledPolicy);

        // Mandatory policy with deleted key
        AccountRecoveryOrganizationPolicyFactory::make()
            ->withAccountRecoveryOrganizationPublicKey(AccountRecoveryOrganizationPublicKeyFactory::make()->deleted())
            ->mandatory()
            ->persist();
        $disabledPolicy = $this->AccountRecoveryOrganizationPolicies->getCurrentPolicyName();
        $this->assertSame(AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_DISABLED, $disabledPolicy);
    }

    /**
     * Account Recovery Organization Policy is disabled if no policy is in the DB
     * or if the policy has no associated public key
     * or if the associated public key is deleted.
     */
    public function testAccountRecoveryOrganizationPoliciesTable_GetCurrentPolicy_Mandatory()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->withAccountRecoveryOrganizationPublicKey()
            ->mandatory()
            ->persist();

        $mandatoryPolicy = $this->AccountRecoveryOrganizationPolicies->getCurrentPolicyName();
        $this->assertSame(AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_MANDATORY, $mandatoryPolicy);
    }
}
