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
 * @since         3.3.0
 */
namespace App\Test\Factory;

use App\Model\Entity\User;
use Cake\I18n\Date;
use Cake\I18n\DateTime;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;
use Passbolt\SecretRevisions\Test\Factory\SecretRevisionFactory;

/**
 * SecretFactory
 *
 * @method \App\Model\Entity\Secret getEntity()
 * @method \App\Model\Entity\Secret[] getEntities()
 * @method \App\Model\Entity\Secret|\App\Model\Entity\Secret[] persist()
 * @method static \App\Model\Entity\Secret get(mixed $primaryKey, array $options = [])
 * @method static \App\Model\Entity\Secret firstOrFail($conditions = null)
 */
class SecretFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Secrets';
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
                'resource_id' => $faker->uuid(),
                'data' => $this->getValidSecret(),
                'created_by' => $faker->uuid(),
                'modified_by' => $faker->uuid(),
                'created' => Date::now()->subDays($faker->randomNumber(4)),
                'modified' => Date::now()->subDays($faker->randomNumber(4)),
            ];
        });
    }

    /**
     * @param DateTime|null $deleted
     * @return SecretFactory
     */
    public function deleted(?DateTime $deleted = null): SecretFactory
    {
        return $this->patchData(['deleted' => $deleted ?? DateTime::yesterday()]);
    }

    /**
     * Produces a valid secret
     *
     * @return string
     */
    protected function getValidSecret(): string
    {
        return "-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf9HpfcNeuC5W/VAzEtAe8mTBUk1vcJENtGpMyRkVTC8KbQ
xaEr3+UG6h0ZVzfrMFYrYLolS3fie83cj4FnC3gg1uijo7zTf9QhJMdi7p/ASB6N
y7//8AriVqUAOJ2WCxAVseQx8qt2KqkQvS7F7iNUdHfhEhiHkczTlehyel7PEeas
SdM/kKEsYKk6i4KLPBrbWsflFOkfQGcPL07uRK3laFz8z4LNzvNQOoU7P/C1L0X3
tlK3vuq+r01zRwmflCaFXaHVifj3X74ljhlk5i/JKLoPRvbxlPTevMNag5e6QhPQ
kpj+TJD2frfGlLhyM50hQMdJ7YVypDllOBmnTRwZ0tJFAXm+F987ovAVLMXGJtGO
P+b3c493CfF0fQ1MBYFluVK/Wka8usg/b0pNkRGVWzBcZ1BOONYlOe/JmUyMutL5
hcciUFw5
=TcQF
-----END PGP MESSAGE-----";
    }

    /**
     * @param User $factory User Factory class.
     * @return SecretFactory
     */
    public function withCreator(User $factory): self
    {
        return $this->with('Creator', $factory);
    }

    /**
     * @param User $factory User Factory class.
     * @return SecretFactory
     */
    public function withModifier(User $factory): self
    {
        return $this->with('Creator', $factory);
    }

    /**
     * @param User $user User to set as creator and modifier.
     * @return SecretFactory
     */
    public function withCreatorAndModifier(User $user): self
    {
        return $this->withModifier($user)->withCreator($user);
    }

    /**
     * @param SecretRevisionFactory|null $factory secret revision
     * @return self
     */
    public function withSecretRevision(?SecretRevisionFactory $factory = null): self
    {
        if (is_null($factory)) {
            $factory = SecretRevisionFactory::make();
        }

        return $this->with('SecretRevisions', $factory);
    }
}
