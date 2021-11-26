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
namespace Passbolt\AccountRecovery\Test\Factory;

use App\Utility\UuidFactory;
use Cake\Chronos\Chronos;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy;

/**
 * AccountRecoveryOrganizationPublicKeyFactory
 */
class AccountRecoveryOrganizationPublicKeyFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Passbolt/AccountRecovery.AccountRecoveryOrganizationPublicKeys';
    }

    /**
     * Defines the factory's default values. This is useful for
     * not nullable fields. You may use methods of the present factory here too.
     *
     * @return void
     */
    protected function setDefaultTemplate(): void
    {
        $this->setDefaultData(self::getDefaultData());
    }

    /**
     * Get some default entity data
     * @return array
     */
    static public function getDefaultData(): array
    {
        $faker = new Generator();
        return [
            'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----

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
WXi2h/I=
=L/Vu
-----END PGP PUBLIC KEY BLOCK-----',
            'fingerprint' => 'B16B0A095A9AF46D7D7FAA440A848FBED334B3EC',
            'created_by' => UuidFactory::uuid(),
            'modified_by' => UuidFactory::uuid(),
            'created' => Chronos::now()->subDay($faker->randomNumber(4)),
            'modified' => Chronos::now()->subDay($faker->randomNumber(4)),
            'deleted' => null
        ];
    }

    /**
     * Get default entity options.
     * @return array checkRules, accessibleFields
     */
    static public function getDefaultOptions(): array
    {
        return [
            'checkRules' => true,
            'accessibleFields' => [
                '*' => true,
            ],
        ];
    }
}
