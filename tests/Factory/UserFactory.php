<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         3.0.0
 */
namespace App\Test\Factory;

use App\Model\Entity\Role;
use App\Model\Entity\User;
use App\Utility\UserAccessControl;
use Cake\I18n\Time;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;
use Passbolt\AccountSettings\Test\Factory\AccountSettingFactory;
use Passbolt\Log\Test\Factory\ActionLogFactory;

/**
 * UserFactory
 *
 * @method \App\Model\Entity\User|\App\Model\Entity\User[] persist()
 * @method \App\Model\Entity\User getEntity()
 * @method \App\Model\Entity\User[] getEntities()
 */
class UserFactory extends CakephpBaseFactory
{
    use FactoryDeletedTrait;

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
                'username' => $faker->userName() . '@passbolt.com',
                'role_id' => $faker->uuid(),
                'active' => true,
                'deleted' => false,
                'created' => Time::now()->subDay($faker->randomNumber(4)),
                'modified' => Time::now()->subDay($faker->randomNumber(4)),
            ];
        });

        $this->with('Profiles')
            ->with('Roles');
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
    public function guest()
    {
        return $this->withRole(Role::GUEST);
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
     * @param int $n
     * @return self
     */
    public function withLogIn(int $n = 1): self
    {
        return $this->with('ActionLogs', ActionLogFactory::make($n)->loginAction());
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

    /**
     * Return a non persisted UAC
     *
     * @return UserAccessControl
     */
    public function nonPersistedUAC(): UserAccessControl
    {
        return $this->makeUserAccessControl($this->getEntity());
    }

    /**
     * Persist and return UAC
     *
     * @return UserAccessControl
     */
    public function persistedUAC(): UserAccessControl
    {
        return $this->makeUserAccessControl($this->persist());
    }

    /**
     * @param User $user User
     * @return UserAccessControl UAC
     */
    private function makeUserAccessControl(User $user): UserAccessControl
    {
        return new UserAccessControl($user->role->name, $user->get('id'), $user->get('username'));
    }

    /**
     * @param AuthenticationTokenFactory $factory Authentication token
     * @return UserFactory this
     */
    public function withAuthenticationTokens(AuthenticationTokenFactory $factory): self
    {
        return $this->with('AuthenticationTokens', $factory);
    }

    /**
     * @param string $filename File to import
     * @return UserFactory this
     */
    public function withAvatar(string $filename = FIXTURES . 'Avatar' . DS . 'ada.jpg'): self
    {
        return $this->with('Profiles.Avatars', [
            'data' => file_get_contents($filename),
        ]);
    }
}
