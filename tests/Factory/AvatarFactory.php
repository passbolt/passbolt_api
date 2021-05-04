<?php
declare(strict_types=1);

namespace App\Test\Factory;

use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * AvatarFactory
 */
class AvatarFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Avatars';
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
                // For performance sake, we do not create an image here
                'data' => $faker->text(),
            ];
        });
    }

    public function withProfile(?ProfileFactory $profileFactory = null): self
    {
        if (!isset($profileFactory)) {
            $profileFactory = ProfileFactory::make()->without('Avatars');
        }

        return $this->with('Profiles', $profileFactory);
    }

    /**
     * @param UserFactory|null $userFactory Associated user
     * @return $this
     */
    public function withUser(?UserFactory $userFactory = null)
    {
        if (!isset($userFactory)) {
            $userFactory = UserFactory::make()->with(
                'Profiles',
                ProfileFactory::make()->without('Avatars')
            );
        }

        return $this->with('Profiles.Users', $userFactory);
    }
}
