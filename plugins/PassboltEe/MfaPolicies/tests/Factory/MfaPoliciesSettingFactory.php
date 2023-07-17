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
 * @since         3.10.0
 */

namespace Passbolt\MfaPolicies\Test\Factory;

use App\Test\Factory\OrganizationSettingFactory;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Faker\Generator;
use Passbolt\MfaPolicies\Model\Entity\MfaPoliciesSetting;
use Passbolt\MfaPolicies\Model\Table\MfaPoliciesSettingsTable;

/**
 * @method \Passbolt\MfaPolicies\Model\Entity\MfaPoliciesSetting|\Passbolt\MfaPolicies\Model\Entity\MfaPoliciesSetting[] persist()
 * @method \Passbolt\MfaPolicies\Model\Entity\MfaPoliciesSetting getEntity()
 * @method \Passbolt\MfaPolicies\Model\Entity\MfaPoliciesSetting[] getEntities()
 * @method static \Passbolt\MfaPolicies\Model\Entity\MfaPoliciesSetting get($primaryKey, array $options = [])
 * @see \Passbolt\MfaPolicies\Model\Table\MfaPoliciesSettingsTable
 */
class MfaPoliciesSettingFactory extends OrganizationSettingFactory
{
    /**
     * @var string
     */
    private $policy = MfaPoliciesSetting::POLICY_OPT_IN;

    /**
     * @var bool
     */
    private $rememberMeForAMonth = true;

    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return MfaPoliciesSettingsTable::class;
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
            /** @var \Passbolt\MfaPolicies\Model\Table\MfaPoliciesSettingsTable $registry */
            $registry = TableRegistry::getTableLocator()->get($this->getRootTableRegistryName());

            return [
                'property' => $registry->getProperty(),
                'property_id' => $registry->getPropertyId(),
                'value' => ['policy' => $this->policy, 'remember_me_for_a_month' => $this->rememberMeForAMonth],
                'created_by' => UuidFactory::uuid(),
                'modified_by' => UuidFactory::uuid(),
            ];
        });
    }

    /**
     * Sets policy field.
     *
     * @param string|null $policy Value to set.
     * @return $this
     */
    public function setPolicy(?string $policy)
    {
        $this->policy = $policy;

        return $this;
    }

    /**
     * Sets remember me for a month field.
     *
     * @param bool|null $value Value to set.
     * @return $this
     */
    public function setRememberMeForAMonth(?bool $value)
    {
        $this->rememberMeForAMonth = $value;

        return $this;
    }
}
