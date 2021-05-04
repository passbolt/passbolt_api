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

use App\Model\Entity\AuthenticationToken;
use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosInterface;
use Cake\I18n\FrozenDate;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * AuthenticationTokenFactory
 */
class AuthenticationTokenFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'AuthenticationTokens';
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
                'token' => $faker->uuid,
                'user_id' => $faker->uuid,
                'type' => AuthenticationToken::TYPE_LOGIN,
                'data' => null,
                'active' => $faker->boolean,
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
        return $this->patchData(['modified' => $modified]);
    }

    /**
     * @param ChronosInterface $created token type
     * @return $this
     */
    public function created(ChronosInterface $created)
    {
        return $this->patchData(['created' => $created]);
    }

    /**
     * @return $this
     */
    public function expired()
    {
        return $this->patchData(['created' => new FrozenDate('5 years ago')]);
    }

    /**
     * @param string $type token type
     * @return $this
     */
    public function type(string $type)
    {
        return $this->patchData(['type' => $type]);
    }

    /**
     * @param string $data token type
     * @return $this
     */
    public function data(string $data)
    {
        return $this->patchData(['data' => $data]);
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
}
