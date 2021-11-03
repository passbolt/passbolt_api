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
 * @since         3.4.0
 */
namespace Passbolt\Folders\Test\Factory;

use Cake\Chronos\Chronos;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;
use Passbolt\Folders\Model\Entity\FoldersRelation;

/**
 * FoldersRelationFactory
 *
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation getEntity()
 * @method \Passbolt\Folders\Model\FoldersRelation[] getEntities()
 * @method \Passbolt\Folders\Model\FoldersRelation persist()
 */
class FoldersRelationFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Passbolt/Folders.FoldersRelations';
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
                'foreign_model' => $faker->text(30),
                'foreign_id' => $faker->uuid(),
                'user_id' => $faker->uuid(),
                'folder_parent_id' => $faker->uuid(),
                'created' => Chronos::now()->subDay($faker->randomNumber(4)),
                'modified' => Chronos::now()->subDay($faker->randomNumber(4)),
            ];
        });
    }

    public function folder()
    {
        return $this->patchData(['foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER]);
    }

    public function resource()
    {
        return $this->patchData(['foreign_model' => FoldersRelation::FOREIGN_MODEL_RESOURCE]);
    }
}
