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
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse;
use Passbolt\AccountRecovery\Model\Table\AccountRecoveryResponsesTable;

/**
 * AccountRecoveryResponseFactory
 *
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse|\Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse[] persist()
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse getEntity()
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse[] getEntities()
 */
class AccountRecoveryResponseFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return AccountRecoveryResponsesTable::class;
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
            $date = Chronos::now()->subDay($faker->randomNumber(5));

            return [
                'account_recovery_request_id' => $faker->uuid(),
                'status' => AccountRecoveryResponse::STATUS_APPROVED,
                'responder_foreign_model' => AccountRecoveryResponse::RESPONDER_FOREIGN_MODEL_ORGANIZATION_KEY,
                'responder_foreign_key' => $faker->uuid(),
                'data' => file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'Messages' . DS . 'ada_for_betty_signed.msg'),
                'created_by' => $faker->uuid(),
                'modified_by' => $faker->uuid(),
                'created' => $date,
                'modified' => $date,
            ];
        });
    }

    /**
     * @param UserFactory|null $factory User Factory
     * @return AccountRecoveryResponseFactory
     */
    public function createdBy(?UserFactory $factory = null)
    {
        return $this->with('Creator', $factory);
    }

    /**
     * @param UserFactory|null $factory User Factory
     * @return AccountRecoveryResponseFactory
     */
    public function modifiedBy(?UserFactory $factory = null)
    {
        return $this->with('Modifier', $factory);
    }

    /**
     * @return $this
     */
    public function rejected()
    {
        return $this->setField('status', AccountRecoveryResponse::STATUS_REJECTED);
    }

    /**
     * @return $this
     */
    public function approved()
    {
        return $this->setField('status', AccountRecoveryResponse::STATUS_APPROVED);
    }
}
