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
 * @since         5.16.0
 */
namespace Passbolt\OfflineMode\Test\Factory;

use App\Model\Entity\Resource;
use App\Model\Entity\User;
use Cake\Chronos\Chronos;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;
use Passbolt\OfflineMode\Model\Table\OfflineItemsTable;

/**
 * OfflineItemFactory
 *
 * @method \Passbolt\OfflineMode\Model\Entity\OfflineItem|\Passbolt\OfflineMode\Model\Entity\OfflineItem[] persist()
 * @method \Passbolt\OfflineMode\Model\Entity\OfflineItem getEntity()
 * @method \Passbolt\OfflineMode\Model\Entity\OfflineItem[] getEntities()
 * @method static \Passbolt\OfflineMode\Model\Entity\OfflineItem firstOrFail(\Cake\Database\ExpressionInterface|\Closure|array|string|null $conditions = null)
 */
class OfflineItemFactory extends CakephpBaseFactory
{
    /**
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Passbolt/OfflineMode.OfflineItems';
    }

    /**
     * @return void
     */
    protected function setDefaultTemplate(): void
    {
        $this->setDefaultData(function (Generator $faker) {
            return [
                'user_id' => $faker->uuid(),
                'foreign_model' => OfflineItemsTable::FOREIGN_MODEL_RESOURCE,
                'foreign_key' => $faker->uuid(),
                'created' => Chronos::now()->subDays($faker->randomNumber(4)),
                'created_by' => $faker->uuid(),
            ];
        });
    }

    /**
     * Pin the row to a specific user (sets both `user_id` and `created_by`).
     *
     * @param \App\Model\Entity\User $user The user owning the offline item.
     * @return $this
     */
    public function setUser(User $user)
    {
        return $this
            ->setField('user_id', $user->get('id'))
            ->setField('created_by', $user->get('id'));
    }

    /**
     * Pin the row to a specific resource. Always uses `foreign_model = 'Resource'`.
     *
     * @param \App\Model\Entity\Resource $resource The target resource.
     * @return $this
     */
    public function setResource(Resource $resource)
    {
        return $this
            ->setField('foreign_key', $resource->get('id'))
            ->setField('foreign_model', OfflineItemsTable::FOREIGN_MODEL_RESOURCE);
    }
}
