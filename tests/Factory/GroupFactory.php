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
 * @since         3.5.0
 */
namespace App\Test\Factory;

use Cake\I18n\Time;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * GroupFactory
 *
 * @method \App\Model\Entity\Group|\App\Model\Entity\Group[] persist()
 * @method \App\Model\Entity\Group getEntity()
 * @method \App\Model\Entity\Group[] getEntities()
 */
class GroupFactory extends CakephpBaseFactory
{
    use FactoryDeletedTrait;

    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Groups';
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
                'name' => $faker->text(64),
                'created_by' => $faker->uuid(),
                'modified_by' => $faker->uuid(),
                'created' => Time::now()->subDay($faker->randomNumber(4)),
                'modified' => Time::now()->subDay($faker->randomNumber(4)),
            ];
        });
    }

    /**
     * Define the associated groups users to create for a given list of users
     *
     * @param array $users Array of users or groups to create the resource for
     * @return GroupFactory
     */
    public function withGroupsUsersFor(array $users): GroupFactory
    {
        foreach ($users as $user) {
            $groupUserMeta = ['user_id' => $user->id];
            $this->with('GroupsUsers', $groupUserMeta);
        }

        return $this;
    }
}
