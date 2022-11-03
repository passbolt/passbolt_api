<?php
declare(strict_types=1);

namespace Passbolt\AccountSettings\Test\Factory;

use App\Model\Entity\User;
use App\Utility\UuidFactory;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * AccountSettingFactory
 */
class AccountSettingFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Passbolt/AccountSettings.AccountSettings';
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
            return [];
        });
    }

    /**
     * @param string $property
     * @param string $value
     * @return $this
     */
    public function setPropertyValue(string $property, string $value)
    {
        $property_id = UuidFactory::uuid('account.settings.property.id.' . $property);

        return $this->patchData(compact('property', 'property_id', 'value'));
    }

    /**
     * @param string $value
     * @return $this
     */
    public function theme(string $value)
    {
        return $this->setPropertyValue('theme', $value);
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
     * @param User $user
     * @return $this
     */
    public function withUser(User $user)
    {
        return $this->with('Users', $user);
    }
}
