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
 * @since         3.6.0
 */
namespace Passbolt\AccountRecovery\Test\Factory;

use App\Test\Factory\UserFactory;
use Cake\Chronos\Chronos;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting;
use Passbolt\AccountRecovery\Model\Table\AccountRecoveryUserSettingsTable;

/**
 * AccountRecoveryUserSettingFactory
 *
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting|\Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting[] persist()
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting getEntity()
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting[] getEntities()
 * @method static \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting get($primaryKey, array $options = [])
 */
class AccountRecoveryUserSettingFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return AccountRecoveryUserSettingsTable::class;
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
                'status' => AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_REJECTED,
                'user_id' => $faker->uuid(),
                'created_by' => $faker->uuid(),
                'modified_by' => $faker->uuid(),
                'created' => Chronos::now()->subDay($faker->randomNumber(4)),
                'modified' => Chronos::now()->subDay($faker->randomNumber(4)),
            ];
        });
    }

    /**
     * @param UserFactory|null $factory User Factory
     * @return AccountRecoveryUserSettingFactory
     */
    public function withUser(?UserFactory $factory = null)
    {
        return $this->with('Users', $factory);
    }

    /**
     * @param UserFactory|null $factory User Factory
     * @return AccountRecoveryUserSettingFactory
     */
    public function createdBy(?UserFactory $factory = null)
    {
        return $this->with('Creator', $factory);
    }

    /**
     * @param UserFactory|null $factory User Factory
     * @return AccountRecoveryUserSettingFactory
     */
    public function modifiedBy(?UserFactory $factory = null)
    {
        return $this->with('Modifier', $factory);
    }

    /**
     * @return $this
     */
    public function approved()
    {
        return $this->setField('status', AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_APPROVED);
    }

    /**
     * @return $this
     */
    public function rejected()
    {
        return $this->setField('status', AccountRecoveryUserSetting::ACCOUNT_RECOVERY_USER_SETTING_REJECTED);
    }
}
