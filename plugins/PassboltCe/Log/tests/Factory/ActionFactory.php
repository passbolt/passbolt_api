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
 * @since         4.0.0
 */

use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * ActionFactory
 *
 * @method \Passbolt\Log\Model\Entity\Action|\Passbolt\Log\Model\Entity\Action[] persist()
 * @method \Passbolt\Log\Model\Entity\Action getEntity()
 * @method \Passbolt\Log\Model\Entity\Action[] getEntities()
 * @method static \Passbolt\Log\Model\Entity\Action get($primaryKey, array $options = [])
 */
class ActionFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Passbolt/Log.Actions';
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
                'name' => $faker->text(100),
            ];
        });
    }
}
