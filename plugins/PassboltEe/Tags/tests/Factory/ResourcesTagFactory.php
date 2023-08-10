<?php
declare(strict_types=1);

namespace Passbolt\Tags\Test\Factory;

use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * ResourcesTagFactory
 *
 * @method \Passbolt\Tags\Model\Entity\ResourcesTag getEntity()
 * @method \Passbolt\Tags\Model\Entity\ResourcesTag[] getEntities()
 * @method \Passbolt\Tags\Model\Entity\ResourcesTag|\Passbolt\Tags\Model\Entity\ResourcesTag[] persist()
 * @method static \Passbolt\Tags\Model\Entity\ResourcesTag get(mixed $primaryKey, array $options = [])
 */
class ResourcesTagFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Passbolt/Tags.ResourcesTags';
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
                'tag_id' => $faker->uuid(),
                'resource_id' => $faker->uuid(),
            ];
        });
    }
}
