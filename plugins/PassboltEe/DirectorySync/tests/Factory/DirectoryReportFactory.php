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
 * @since         4.10.0
 */
namespace Passbolt\DirectorySync\Test\Factory;

use Cake\I18n\FrozenDate;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;
use Passbolt\DirectorySync\Model\Entity\DirectoryReport;

/**
 * DirectoryReportFactory
 *
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryReport|\Passbolt\DirectorySync\Model\Entity\DirectoryReport[] persist()
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryReport getEntity()
 * @method \Passbolt\DirectorySync\Model\Entity\DirectoryReport[] getEntities()()
 */
class DirectoryReportFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Passbolt/DirectorySync.DirectoryReports';
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
                'parent_id' => null,
                'status' => DirectoryReport::STATUS_DONE,
                'created' => FrozenDate::now()->subDays($faker->randomNumber(1)),
                'modified' => FrozenDate::now()->subDays($faker->randomNumber(1)),
            ];
        });
    }

    /**
     * @param DirectoryReportsItemFactory $factory Factory object.
     * @return DirectoryReportFactory
     */
    public function withDirectoryReportsItems(DirectoryReportsItemFactory $factory): DirectoryReportFactory
    {
        return $this->with('DirectoryReportsItems', $factory);
    }
}
