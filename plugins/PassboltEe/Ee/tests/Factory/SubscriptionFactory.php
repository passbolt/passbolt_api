<?php
declare(strict_types=1);

namespace Passbolt\Ee\Test\Factory;

use App\Test\Factory\OrganizationSettingFactory;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
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
    protected function setDefaultTemplate(): void
    {
        $this->setDefaultData(function (Generator $faker) {
            /** @var \Passbolt\Ee\Model\Table\SubscriptionsTable $registry */
            $registry = TableRegistry::getTableLocator()->get($this->getRootTableRegistryName());

            return [
                'property' => $registry->getProperty(),
                'property_id' => $registry->getPropertyId(),
                'value' => $faker->text(),
                'created_by' => UuidFactory::uuid(),
                'modified_by' => UuidFactory::uuid(),
            ];
        });
    }
}
