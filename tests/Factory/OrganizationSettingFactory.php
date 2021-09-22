<?php
declare(strict_types=1);

namespace App\Test\Factory;

use App\Model\Entity\OrganizationSetting;
use App\Utility\UuidFactory;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * ResourceFactory
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
            return [
                'created_by' => $faker->uuid(),
                'modified_by' => $faker->uuid(),
            ];
        });
    }

    /**
     * @param string $property
     * @param string $value
     * @return $this
     */
    public function setPropertyValue(string $property, string $value)
    {
        $property_id = UuidFactory::uuid(OrganizationSetting::UUID_NAMESPACE . $property);

        return $this->patchData(compact('property', 'property_id', 'value'));
    }

    /**
     * @param string $value
     * @return $this
     */
    public function locale(string $value)
    {
        return $this->setPropertyValue('locale', $value);
    }

    /**
     * @param $value
     * @return $this
     */
    public function value($value)
    {
        return $this->patchData(['value' => json_encode($value)]);
    }
}
