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
 * @since         4.7.0
 */
namespace Passbolt\DirectorySync\Test\Factory;

use App\Test\Factory\GroupFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Cake\I18n\FrozenDate;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;
use Passbolt\DirectorySync\Utility\Alias;

/**
 * DirectoryEntryFactory
 *
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryEntry|\Passbolt\DirectorySync\Model\Entity\DirectoryEntry[] persist()
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryEntry getEntity()
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryEntry[] getEntities()()
 */
class DirectoryEntryFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Passbolt/DirectorySync.DirectoryEntries';
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
                'foreign_key' => UuidFactory::uuid(),
                'directory_name' => $faker->word(),
                'directory_created' => FrozenDate::now()->subDays($faker->randomNumber(1)),
                'directory_modified' => FrozenDate::now()->subDays($faker->randomNumber(1)),
                'created' => FrozenDate::now()->subDays($faker->randomNumber(1)),
                'modified' => FrozenDate::now()->subDays($faker->randomNumber(1)),
            ];
        });
    }

    /**
     * @param UserFactory|null $userFactory user factory
     * @return DirectoryEntryFactory
     */
    public function withUser(?UserFactory $userFactory = null): DirectoryEntryFactory
    {
        if (!isset($userFactory)) {
            $userFactory = UserFactory::make()->user();
        }
        $this->with('Users', $userFactory);

        return $this->setField('foreign_model', Alias::MODEL_USERS);
    }

    /**
     * @param GroupFactory|null $groupFactory user factory
     * @return DirectoryEntryFactory
     */
    public function withGroup(?GroupFactory $groupFactory = null): DirectoryEntryFactory
    {
        if (!isset($groupFactory)) {
            $groupFactory = GroupFactory::make();
        }
        $this->with('Groups', $groupFactory);

        return $this->setField('foreign_model', Alias::MODEL_GROUPS);
    }
}
