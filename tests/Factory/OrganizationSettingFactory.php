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
 * @since         3.2.0
 */

namespace App\Test\Factory;

use App\Model\Entity\OrganizationSetting;
use App\Utility\UuidFactory;
use Cake\Chronos\Chronos;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * OrganizationSettingFactory
 *
 * @method \App\Model\Entity\OrganizationSetting|\App\Model\Entity\OrganizationSetting[] persist()
 * @method \App\Model\Entity\OrganizationSetting getEntity()
 * @method \App\Model\Entity\OrganizationSetting[] getEntities()
 * @method static \App\Model\Entity\OrganizationSetting get($primaryKey, array $options = [])
 */
class OrganizationSettingFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'OrganizationSettings';
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
            $property = OrganizationSetting::UUID_NAMESPACE . $faker->word();

            return [
                'property' => $property,
                'property_id' => UuidFactory::uuid($property),
                'value' => $faker->text(),
                'created' => Chronos::now()->subDay($faker->randomNumber(4)),
                'modified' => Chronos::now()->subDay($faker->randomNumber(4)),
                'created_by' => UuidFactory::uuid(),
                'modified_by' => UuidFactory::uuid(),
            ];
        });
    }

    /**
     * @param string $property
     * @param string $value
     * @return $this
     */
    public function setPropertyAndValue(string $property, string $value)
    {
        $property_id = UuidFactory::uuid(OrganizationSetting::UUID_NAMESPACE . $property);

        return $this->patchData(compact('property', 'property_id', 'value'));
    }

    /**
     * @param string|array $value Value to set
     * @return $this
     */
    public function value($value)
    {
        if (is_array($value)) {
            $value = json_encode($value);
        }

        return $this->setField('value', $value);
    }
}
