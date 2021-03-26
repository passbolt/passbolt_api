<?php
declare(strict_types=1);

namespace App\Test\Factory;

use App\Model\Entity\OrganizationSetting;
use App\Utility\UuidFactory;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * OrganizationSettingFactory
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
            $property = OrganizationSetting::UUID_NAMESPACE . $faker->word;

            return [
                'property' => $property,
                'property_id' => UuidFactory::uuid($property),
                'value' => $faker->text(),
                'created_by' => UuidFactory::uuid(),
                'modified_by' => UuidFactory::uuid(),
            ];
        });
    }
}
