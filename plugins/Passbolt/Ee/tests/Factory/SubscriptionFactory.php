<?php
declare(strict_types=1);

namespace Passbolt\Ee\Test\Factory;

use App\Test\Factory\OrganizationSettingFactory;
use App\Utility\UuidFactory;
use Faker\Generator;

/**
 * SubscriptionFactory
 */
class SubscriptionFactory extends OrganizationSettingFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Passbolt/Ee.Subscriptions';
    }

    /**
     * Defines the factory's default values. This is useful for
     * not nullable fields. You may use methods of the present factory here too.
     *
     * @return void
     */
    protected function setDefaultTemplate()
    {
        $this->setDefaultData(function (Generator $faker) {
            return [
                'property' => $this->getRootTableRegistry()->getProperty(),
                'property_id' => $this->getRootTableRegistry()->getPropertyId(),
                'value' => $faker->text(),
                'created_by' => UuidFactory::uuid(),
                'modified_by' => UuidFactory::uuid(),
            ];
        });
    }
}
