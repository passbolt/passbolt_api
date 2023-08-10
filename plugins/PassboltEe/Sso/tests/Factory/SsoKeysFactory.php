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
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;
use Passbolt\Sso\Model\Table\SsoKeysTable;

/**
 * @method \Passbolt\Sso\Model\Entity\SsoKey|\Passbolt\Sso\Model\Entity\SsoKey[] persist()
 * @method \Passbolt\Sso\Model\Entity\SsoKey getEntity()
 * @method \Passbolt\Sso\Model\Entity\SsoKey[] getEntities()
 */
class SsoKeysFactory extends CakephpBaseFactory
{
    public const DATA = 'eyJhbGciOiJBMjU2R0NNIiwiZXh0Ijp0cnVlLCJrIjoiSGFTbl9tdlM3VXBpYlVXMVc5MERQc1BNYWxxeEJYSkpWNXItMmtlTTl6RSIsImtleV9vcHMiOlsiZW5jcnlwdCIsImRlY3J5cHQiXSwia3R5Ijoib2N0In0=';

    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return SsoKeysTable::class;
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
            return self::getDefaultData();
        });
    }

    /**
     * @return array
     */
    protected static function getDefaultData(): array
    {
        return [
            'user_id' => UuidFactory::uuid(),
            'data' => self::DATA,
            'created_by' => UuidFactory::uuid(),
            'modified_by' => UuidFactory::uuid(),
            'created' => Chronos::now()->subDay(3),
            'modified' => Chronos::now()->subDay(3),
        ];
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
