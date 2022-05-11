<?php
declare(strict_types=1);

namespace Passbolt\Tags\Test\Factory;

use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * TagFactory
 *
 * @method \Passbolt\Tags\Model\Entity\Tag getEntity()
 * @method \Passbolt\Tags\Model\Entity\Tag[] getEntities()
 * @method \Passbolt\Tags\Model\Entity\Tag|\Passbolt\Tags\Model\Entity\Tag[] persist()
 * @method static \Passbolt\Tags\Model\Entity\Tag get(mixed $primaryKey, array $options = [])
 */
class TagFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Passbolt/Tags.Tags';
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
                // set the model's default values
                // For example:
                // 'name' => $faker->lastName
            ];
        });
    }
}
