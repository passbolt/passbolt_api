<?php
declare(strict_types=1);

namespace Passbolt\Log\Test\Factory;

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         3.7.0
 */

use App\Utility\UuidFactory;
use Cake\Chronos\Chronos;
use Cake\I18n\FrozenTime;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * ActionLogFactory
 *
 * @method \Passbolt\Log\Model\Entity\ActionLog|\Passbolt\Log\Model\Entity\ActionLog[] persist()
 * @method \Passbolt\Log\Model\Entity\ActionLog getEntity()
 * @method \Passbolt\Log\Model\Entity\ActionLog[] getEntities()
 * @method static \Passbolt\Log\Model\Entity\ActionLog get($primaryKey, array $options = [])
 */
class ActionLogFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Passbolt/Log.ActionLogs';
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
                'user_id' => $faker->uuid(),
                'action_id' => $faker->uuid(),
                'context' => $faker->text(255),
                'status' => 1,
                'created' => Chronos::now()->subMinutes($faker->randomNumber(8)),
            ];
        });
    }

    /**
     * @return $this
     */
    public function setActionId(string $actionName)
    {
        return $this->setField('action_id', UuidFactory::uuid($actionName));
    }

    /**
     * @return $this
     */
    public function loginAction()
    {
        return $this->setActionId('AuthLogin.loginPost');
    }

    /**
     * @return $this
     */
    public function success()
    {
        return $this->setField('status', 1);
    }

    /**
     * @return $this
     */
    public function error()
    {
        return $this->setField('status', 0);
    }

    /**
     * @param string $userId User ID
     * @return $this
     */
    public function userId(string $userId)
    {
        return $this->setField('user_id', $userId);
    }

    /**
     * @param string|FrozenTime $userId User ID
     * @return $this
     */
    public function created($created)
    {
        if (is_string($created)) {
            $created = FrozenTime::parse($created);
        }

        return $this->setField('created', $created);
    }
}
