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
 * @since         4.3.0
 */

namespace Passbolt\UserPassphrasePolicies\Test\Factory;

use App\Test\Factory\OrganizationSettingFactory;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Faker\Generator;
use Passbolt\UserPassphrasePolicies\Model\Table\UserPassphrasePoliciesSettingsTable;

/**
 * @method \Passbolt\UserPassphrasePolicies\Model\Entity\UserPassphrasePoliciesSetting|\Passbolt\UserPassphrasePolicies\Model\Entity\UserPassphrasePoliciesSetting[] persist()
 * @method \Passbolt\UserPassphrasePolicies\Model\Entity\UserPassphrasePoliciesSetting getEntity()
 * @method \Passbolt\UserPassphrasePolicies\Model\Entity\UserPassphrasePoliciesSetting[] getEntities()
 * @method static \Passbolt\UserPassphrasePolicies\Model\Entity\UserPassphrasePoliciesSetting get($primaryKey, array $options = [])
 * @see \Passbolt\UserPassphrasePolicies\Model\Table\UserPassphrasePoliciesSettingsTable
 */
class UserPassphrasePoliciesSettingFactory extends OrganizationSettingFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return UserPassphrasePoliciesSettingsTable::class;
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
            /** @var \Passbolt\UserPassphrasePolicies\Model\Table\UserPassphrasePoliciesSettingsTable $registry */
            $registry = TableRegistry::getTableLocator()->get($this->getRootTableRegistryName());

            return [
                'id' => $faker->uuid(),
                'property' => $registry->getProperty(),
                'property_id' => $registry->getPropertyId(),
                'value' => [
                    'entropy_minimum' => $faker->numberBetween(50, 224),
                    'external_dictionary_check' => true,
                ],
                'created_by' => UuidFactory::uuid(),
                'modified_by' => UuidFactory::uuid(),
            ];
        });
    }
}
