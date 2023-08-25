<?php
declare(strict_types=1);

namespace Passbolt\Tags\Test\Factory;

use App\Model\Entity\Resource;
use App\Model\Entity\User;
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
                'slug' => $faker->text(128),
            ];
        });
    }

    /**
     * Set is_shared to boolean (true by default)
     *
     * @param bool $isShared is_shared
     * @return $this
     */
    public function isShared(bool $isShared = true)
    {
        $this->setField('is_shared', $isShared);

        return $this;
    }

    /**
     * Is tagging the given resource for the given user
     *
     * @param Resource $resource The resource tog
     * @param User $user The user to tag the resource for
     * @return $this
     */
    public function isPersonalFor(Resource $resource, User $user)
    {
        return $this->with(
            'ResourcesTags',
            ResourcesTagFactory::make()
                ->with('Resources', $resource)
                ->with('Users', $user)
        );
    }

    /**
     * Is tagging the given resource for the given user
     *
     * @param Resource $resource The resource tog
     * @return $this
     */
    public function isSharedFor(Resource $resource)
    {
        return $this->with(
            'ResourcesTags',
            ResourcesTagFactory::make()->with('Resources', $resource)
        )->isShared();
    }
}
