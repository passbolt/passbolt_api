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
 * @since         3.9.0
 */
namespace Passbolt\Sso\Test\Factory;

use App\Utility\UuidFactory;
use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosInterface;
use Cake\I18n\FrozenDate;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Model\Table\SsoAuthenticationTokensTable;

/**
 * @method \Passbolt\Sso\Model\Entity\SsoAuthenticationToken|\Passbolt\Sso\Model\Entity\SsoAuthenticationToken[] persist()
 * @method \Passbolt\Sso\Model\Entity\SsoAuthenticationToken getEntity()
 * @method \Passbolt\Sso\Model\Entity\SsoAuthenticationToken[] getEntities()
 */
class SsoAuthenticationTokenFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return SsoAuthenticationTokensTable::class;
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
                'token' => UuidFactory::uuid(),
                'type' => $faker->randomElement([
                    SsoState::TYPE_SSO_GET_KEY,
                    SsoState::TYPE_SSO_SET_SETTINGS,
                ]),
                'data' => null,
                'created_by' => $faker->uuid(),
                'modified_by' => $faker->uuid(),
                'created' => Chronos::now(),
                'modified' => Chronos::now(),
            ];
        });
    }

    /**
     * @param ChronosInterface $modified token type
     * @return $this
     */
    public function modified(ChronosInterface $modified)
    {
        return $this->patchData(compact('modified'));
    }

    /**
     * @param ChronosInterface $created token type
     * @return $this
     */
    public function created(ChronosInterface $created)
    {
        return $this->patchData(compact('created'));
    }

    /**
     * @return $this
     */
    public function expired()
    {
        return $this->created(new FrozenDate('5 years ago'));
    }

    /**
     * @param string $type token type
     * @return $this
     */
    public function type(string $type)
    {
        return $this->patchData(compact('type'));
    }

    /**
     * @param array $data token type
     * @return $this
     */
    public function data(array $data)
    {
        $data = empty($data) ? null : json_encode($data);

        return $this->patchData(compact('data'));
    }

    /**
     * @return $this
     */
    public function inactive()
    {
        return $this->patchData(['active' => false]);
    }

    /**
     * @return $this
     */
    public function active()
    {
        return $this->patchData(['active' => true]);
    }

    /**
     * @param string $userId user ID
     * @return $this
     */
    public function userId(string $userId)
    {
        return $this->patchData(['user_id' => $userId]);
    }
}
