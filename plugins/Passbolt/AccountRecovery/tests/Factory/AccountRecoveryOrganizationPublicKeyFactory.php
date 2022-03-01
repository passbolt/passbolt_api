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
 * @since         3.5.0
 */
namespace Passbolt\AccountRecovery\Test\Factory;

use App\Utility\UuidFactory;
use Cake\Chronos\Chronos;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * AccountRecoveryOrganizationPublicKeyFactory
 *
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey persist()
 * @method \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey getEntity()
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
        $this->setDefaultData(function (Generator $faker) {
            return [
                'armored_key' => file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'),
                'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                'created_by' => UuidFactory::uuid(),
                'modified_by' => UuidFactory::uuid(),
                'created' => Chronos::now()->subDay($faker->randomNumber(4)),
                'modified' => Chronos::now()->subDay($faker->randomNumber(4)),
                'deleted' => null,
            ];
        });
    }

    /**
     * @return $this
     */
    public function deleted()
    {
        return $this->setField('deleted', Chronos::now());
    }

    /**
     * @return $this
     */
    public function expiredKey()
    {
        return $this
            ->setField('fingerprint', 'BD92B8DE3FCF8DD5D60A4DF91E5E3B142396F2C7')
            ->setField('armored_key', file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_expired_public.key'));
    }

    /**
     * @return $this
     */
    public function revokedKey()
    {
        return $this
            ->setField('fingerprint', '67BFFCB7B74AF4C85E81AB26508850525CD78BAA')
            ->setField('armored_key', file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key'));
    }

    /**
     * @return $this
     */
    public function rsa2048Key()
    {
        return $this
            ->setField('fingerprint', '26FD986838F4F9AB318FF56AE5DFCEE142949B78')
            ->setField('armored_key', file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa2048_public.key'));
    }
}
