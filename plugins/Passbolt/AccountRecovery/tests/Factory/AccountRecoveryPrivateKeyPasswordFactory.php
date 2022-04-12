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
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKeyPassword;
use Passbolt\AccountRecovery\Model\Table\AccountRecoveryPrivateKeyPasswordsTable;

/**
 * AccountRecoveryPrivateKeyPasswordsFactory
 *
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKeyPassword|\Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKeyPassword[] persist()
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKeyPassword getEntity()
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKeyPassword[] getEntities()
 */
class AccountRecoveryPrivateKeyPasswordFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return AccountRecoveryPrivateKeyPasswordsTable::class;
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
                'recipient_fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                'recipient_foreign_model' => AccountRecoveryPrivateKeyPassword::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY,
                'private_key_id' => $faker->uuid(),
                'data' => file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'Messages' . DS . 'ada_for_betty_signed.msg'),
                'created_by' => $faker->uuid(),
                'modified_by' => $faker->uuid(),
                'created' => Chronos::now()->subDay($faker->randomNumber(2)),
                'modified' => Chronos::now()->subDay($faker->randomNumber(2)),
            ];
        });
    }

    /**
     * @param UserFactory|null $factory User Factory
     * @return AccountRecoveryPrivateKeyPasswordFactory
     */
    public function withUser(?UserFactory $factory = null)
    {
        return $this->with('Users', $factory);
    }

    /**
     * @param UserFactory|null $factory User Factory
     * @return AccountRecoveryPrivateKeyPasswordFactory
     */
    public function createdBy(?UserFactory $factory = null)
    {
        return $this->with('Creator', $factory);
    }

    /**
     * @param UserFactory|null $factory User Factory
     * @return AccountRecoveryPrivateKeyPasswordFactory
     */
    public function modifiedBy(?UserFactory $factory = null)
    {
        return $this->with('Modifier', $factory);
    }
}
