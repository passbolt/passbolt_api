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
namespace Passbolt\Scim\Test\Factory;

use App\Test\Factory\GroupFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Cake\I18n\DateTime;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;
use Passbolt\Scim\Model\Entity\ScimEntry;
use Passbolt\Scim\Model\Table\ScimEntriesTable;

/**
 * ScimEntryFactory
 *
 * @method \Passbolt\Scim\Model\Entity\ScimEntry|\Passbolt\Scim\Model\Entity\ScimEntry[] persist()
 * @method \Passbolt\Scim\Model\Entity\ScimEntry getEntity()
 * @method \Passbolt\Scim\Model\Entity\ScimEntry[] getEntities()()
 */
class ScimEntryFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return ScimEntriesTable::class;
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
                'external_identifier' => UuidFactory::uuid(),
                'scim_name' => $faker->userName() . '@passbolt.com',
                'created' => DateTime::now()->subDays($faker->randomNumber(1)),
                'modified' => DateTime::now()->subDays($faker->randomNumber(1)),
            ];
        });
    }

    /**
     * @param \App\Test\Factory\UserFactory|array|null $user
     * @return \Passbolt\Scim\Test\Factory\ScimEntryFactory
     */
    public function withUser(UserFactory|array|null $user = null): ScimEntryFactory
    {
        if (!isset($user)) {
            $user = UserFactory::make()->user();
        }
        $this->with('Users', $user);

        return $this->setField('foreign_model', ScimEntry::FOREIGN_MODEL_USERS);
    }

    /**
     * @param GroupFactory|null $groupFactory user factory
     * @return ScimEntryFactory
     */
    public function withGroup(?GroupFactory $groupFactory = null): ScimEntryFactory
    {
        if (!isset($groupFactory)) {
            $groupFactory = GroupFactory::make();
        }
        $this->with('Groups', $groupFactory);

        return $this->setField('foreign_model', ScimEntry::FOREIGN_MODEL_USERS);
    }
}
