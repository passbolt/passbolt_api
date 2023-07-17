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
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Factory;
use Faker\Generator;
use Passbolt\Sso\Model\Entity\SsoSetting;
use Passbolt\Sso\Model\Table\SsoSettingsTable;

/**
 * @method \Passbolt\Sso\Model\Entity\SsoSetting|\Passbolt\Sso\Model\Entity\SsoSetting[] persist()
 * @method \Passbolt\Sso\Model\Entity\SsoSetting getEntity()
 * @method \Passbolt\Sso\Model\Entity\SsoSetting[] getEntities()
 */
class SsoSettingsFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return SsoSettingsTable::class;
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
            'status' => SsoSetting::STATUS_DRAFT,
            'provider' => SsoSetting::PROVIDER_AZURE,
            'data' => file_get_contents(__DIR__ . DS . '..' . DS . 'Fixture' . DS . 'SsoSettings' . DS . 'azure.msg'),
            'created_by' => UuidFactory::uuid(),
            'modified_by' => UuidFactory::uuid(),
            'created' => Chronos::now()->subDay(3),
            'modified' => Chronos::now()->subDay(3),
        ];
    }

    public function azure(): SsoSettingsFactory
    {
        $file = file_get_contents(__DIR__ . DS . '..' . DS . 'Fixture' . DS . 'SsoSettings' . DS . 'azure.msg');

        return $this
            ->patchData(['provider' => SsoSetting::PROVIDER_AZURE])
            ->patchData(['data' => $file]);
    }

    public function google(): SsoSettingsFactory
    {
        $file = file_get_contents(__DIR__ . DS . '..' . DS . 'Fixture' . DS . 'SsoSettings' . DS . 'google.msg');

        return $this->patchData(['provider' => SsoSetting::PROVIDER_GOOGLE])->patchData(['data' => $file]);
    }

    /**
     * @param string $data
     * @return SsoSettingsFactory this
     */
    public function data(string $data): SsoSettingsFactory
    {
        return $this->patchData(compact('data'));
    }

    /**
     * @param string $status
     * @return SsoSettingsFactory this
     */
    public function status(string $status): SsoSettingsFactory
    {
        return $this->patchData(compact('status'));
    }

    /**
     * @return SsoSettingsFactory this
     */
    public function draft(): SsoSettingsFactory
    {
        return $this->status(SsoSetting::STATUS_DRAFT);
    }

    /**
     * @return SsoSettingsFactory this
     */
    public function active(): SsoSettingsFactory
    {
        return $this->status(SsoSetting::STATUS_ACTIVE);
    }

    /**
     * @param string $provider
     * @return SsoSettingsFactory this
     */
    public function provider(string $provider): SsoSettingsFactory
    {
        return $this->patchData(compact('provider'));
    }

    /**
     * @param ChronosInterface $modified token type
     * @return SsoSettingsFactory this
     */
    public function modified(ChronosInterface $modified): SsoSettingsFactory
    {
        return $this->patchData(compact('modified'));
    }

    /**
     * @param ChronosInterface $created token type
     * @return SsoSettingsFactory this
     */
    public function created(ChronosInterface $created): SsoSettingsFactory
    {
        return $this->patchData(compact('created'));
    }

    /**
     * Returns fake credentials of google SSO provider.
     *
     * @return array
     */
    public static function getGoogleCredentials(): array
    {
        $faker = Factory::create();

        return [
            'client_id' => $faker->bothify('############-????????????????????????????????') . '.apps.googleusercontent.com',
            'client_secret' => $faker->bothify('??????-#????#??????????????#???#?-#'),
        ];
    }
}
