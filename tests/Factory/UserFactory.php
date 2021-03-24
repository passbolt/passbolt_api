<?php
declare(strict_types=1);

namespace App\Test\Factory;

use App\Model\Entity\Role;
use Cake\Chronos\Chronos;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;
use Passbolt\AccountSettings\Test\Factory\AccountSettingFactory;

/**
 * UserFactory
 */
class UserFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Users';
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
                'username' => $faker->userName . '@passbolt.com',
                'active' => true,
                'deleted' => false,
                'created' => Chronos::now(),
                'modified' => Chronos::now(),
            ];
        });

        $this
            ->with('Roles')
            ->with('Profiles')
            ->with('Gpgkeys')
            ->with('GroupsUsers');
    }

    /**
     * @param string $name
     * @return $this
     */
    public function withRole(string $name)
    {
        return $this->with('Roles', compact('name'));
    }

    /**
     * @return $this
     */
    public function admin()
    {
        return $this->withRole(Role::ADMIN);
    }

    /**
     * @return $this
     */
    public function user()
    {
        return $this->withRole(Role::USER);
    }

    /**
     * @return $this
     */
    public function inactive()
    {
        return $this->patchData(['active' => false]);
    }

    /**
     * @return $this
     */
    public function active()
    {
        return $this->patchData(['active' => true]);
    }

    /**
     * Set the locale of this user in her account settings.
     *
     * @param string $locale
     * @return $this
     */
    public function withLocale(string $locale)
    {
        return $this->with(
            'AccountSettings',
            AccountSettingFactory::make()->locale($locale)
        );
    }
}
