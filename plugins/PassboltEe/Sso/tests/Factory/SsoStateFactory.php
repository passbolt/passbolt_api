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
 * @since         3.11.0
 */

namespace Passbolt\Sso\Test\Factory;

use App\Utility\UuidFactory;
use Cake\I18n\FrozenTime;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Model\Table\SsoStatesTable;

/**
 * SsoStateFactory
 *
 * @method \Passbolt\Sso\Model\Entity\SsoState getEntity()
 * @method \Passbolt\Sso\Model\Entity\SsoState[] getEntities()
 * @method \Passbolt\Sso\Model\Entity\SsoState|\Passbolt\Sso\Model\Entity\SsoState[] persist()
 * @method static \Passbolt\Sso\Model\Entity\SsoState get(mixed $primaryKey, array $options = [])
 */
class SsoStateFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return SsoStatesTable::class;
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
                'nonce' => SsoState::generate(),
                'state' => SsoState::generate(),
                'type' => $faker->randomElement([
                    SsoState::TYPE_SSO_SET_SETTINGS,
                    SsoState::TYPE_SSO_GET_KEY,
                ]),
                'sso_settings_id' => UuidFactory::uuid(),
                'user_id' => UuidFactory::uuid(),
                'user_agent' => $faker->userAgent(),
                'ip' => $faker->ipv4(),
                'created' => FrozenTime::now(),
                'deleted' => FrozenTime::now()->addMinutes(5),
            ];
        });
    }

    /**
     * Sets "sso_set_settings" type
     *
     * @return $this
     */
    public function withTypeSsoSetSettings()
    {
        return $this->patchData(['type' => SsoState::TYPE_SSO_SET_SETTINGS]);
    }

    /**
     * Sets "sso_get_key" type
     *
     * @return $this
     */
    public function withTypeSsoGetKey()
    {
        return $this->patchData(['type' => SsoState::TYPE_SSO_GET_KEY]);
    }

    /**
     * Sets "sso_recover" type
     *
     * @return $this
     */
    public function withTypeSsoRecover()
    {
        return $this->patchData(['type' => SsoState::TYPE_SSO_RECOVER]);
    }

    /**
     * @param string $ssoSettingsId SSO settings ID.
     * @return $this
     */
    public function ssoSettingsId(string $ssoSettingsId)
    {
        return $this->patchData(['sso_settings_id' => $ssoSettingsId]);
    }

    /**
     * @param string $userId user ID
     * @return $this
     */
    public function userId(string $userId)
    {
        return $this->patchData(['user_id' => $userId]);
    }

    /**
     * @return $this
     */
    public function userAgent(string $userAgent)
    {
        return $this->setField('user_agent', $userAgent);
    }

    /**
     * @return $this
     */
    public function deleted()
    {
        return $this->setField('deleted', FrozenTime::now());
    }
}
