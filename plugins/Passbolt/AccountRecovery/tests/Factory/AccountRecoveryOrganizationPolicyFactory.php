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
namespace Passbolt\AccountRecovery\Test\Factory;

use App\Utility\UuidFactory;
use Cake\I18n\FrozenDate;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy;

/**
 * AccountRecoveryOrganizationPolicyFactory
 *
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy|\Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy[] persist()
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy getEntity()
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy[] getEntities()()
 */
class AccountRecoveryOrganizationPolicyFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Passbolt/AccountRecovery.AccountRecoveryOrganizationPolicies';
    }

    /**
     * Defines the factory's default values. This is useful for
     * not nullable fields. You may use methods of the present factory here too.
     *
     * @return void
     */
    protected function setDefaultTemplate(): void
    {
        $this->setDefaultData(function (Generator $faker) {
            return [
                'policy' => AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_DISABLED,
                'created_by' => UuidFactory::uuid(),
                'modified_by' => UuidFactory::uuid(),
                'created' => FrozenDate::now()->subDay($faker->randomNumber(1)),
                'modified' => FrozenDate::now()->subDay($faker->randomNumber(1)),
                'public_key_id' => UuidFactory::uuid(),
            ];
        });
    }

    /**
     * @param ?AccountRecoveryOrganizationPublicKeyFactory $factory factory
     * @return $this
     */
    public function withAccountRecoveryOrganizationPublicKey(?AccountRecoveryOrganizationPublicKeyFactory $factory = null)
    {
        return $this->with('AccountRecoveryOrganizationPublicKeys', $factory);
    }

    /**
     * @return $this
     */
    public function mandatory()
    {
        return $this->setField('policy', AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_MANDATORY);
    }

    /**
     * @return $this
     */
    public function disabled()
    {
        return $this->setField('policy', AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_DISABLED)
            ->setField('public_key_id', null);
    }

    /**
     * @return $this
     */
    public function optin()
    {
        return $this->setField('policy', AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_OPT_IN);
    }

    /**
     * @return $this
     */
    public function optout()
    {
        return $this->setField('policy', AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_OPT_OUT);
    }
}
