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
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         4.1.0
 */

namespace Passbolt\Rbacs\Test\Factory;

use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * UiActionFactory
 *
 * @method \Passbolt\Rbacs\Model\Entity\UiAction|\Passbolt\Rbacs\Model\Entity\UiAction[] persist()
 * @method \Passbolt\Rbacs\Model\Entity\UiAction getEntity()
 * @method \Passbolt\Rbacs\Model\Entity\UiAction[] getEntities()
 * @method static \Passbolt\Rbacs\Model\Entity\UiAction get($primaryKey, array $options = [])
 */
class UiActionFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Passbolt/Rbacs.UiActions';
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

    /**
     * @return $this
     */
    public function name(string $name)
    {
        return $this->patchData(['name' => $name]);
    }
}
