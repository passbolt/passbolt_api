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
 * @since         3.7.2
 */
namespace Passbolt\DirectorySync\Test\Factory;

use App\Test\Factory\GroupFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Cake\I18n\FrozenDate;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * DirectoryRelationFactory
 *
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryRelation|\Passbolt\DirectorySync\Model\Entity\DirectoryRelation[] persist()
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryRelation getEntity()
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryRelation[] getEntities()()
 */
class DirectoryRelationFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Passbolt/DirectorySync.DirectoryRelations';
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
                'parent_key' => UuidFactory::uuid(),
                'child_key' => UuidFactory::uuid(),
                'created' => FrozenDate::now()->subDays($faker->randomNumber(1)),
            ];
        });
    }

    public function setParentKey(string $parentKey)
    {
        return $this->setField('parent_key', $parentKey);
    }

    public function setChildKey(string $childKey)
    {
        return $this->setField('child_key', $childKey);
    }

    public function withGroup(?GroupFactory $groupFactory = null)
    {
        if (!isset($groupFactory)) {
            $groupFactory = GroupFactory::make();
        }

        return $this->with('GroupDirectoryEntry', $groupFactory);
    }

    public function withUser(?UserFactory $userFactory = null)
    {
        if (!isset($userFactory)) {
            $userFactory = UserFactory::make()->user();
        }

        return $this->with('UserDirectoryEntry', $userFactory);
    }
}
