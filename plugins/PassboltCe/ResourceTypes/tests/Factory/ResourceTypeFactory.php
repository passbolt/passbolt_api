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
 * @since         4.0.0
 */

namespace Passbolt\ResourceTypes\Test\Factory;

use App\Utility\UuidFactory;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;
use Passbolt\ResourceTypes\Model\Entity\ResourceType;

/**
 * ResourceFactory
 *
 * @method \Passbolt\ResourceTypes\Model\Entity\ResourceType|\Passbolt\ResourceTypes\Model\Entity\ResourceType[] persist()
 * @method \Passbolt\ResourceTypes\Model\Entity\ResourceType getEntity()
 * @method \Passbolt\ResourceTypes\Model\Entity\ResourceType[] getEntities()
 * @method static \Passbolt\ResourceTypes\Model\Entity\ResourceType get($primaryKey, array $options = [])
 */
class ResourceTypeFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'ResourceTypes';
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
                'slug' => $faker->slug(3),
                'name' => $faker->words(3, true),
                'description' => $faker->text(64),
                'definition' => json_encode([]),
                'created' => \Cake\I18n\Date::now()->subDays($faker->randomNumber(4)),
                'modified' => \Cake\I18n\Date::now()->subDays($faker->randomNumber(4)),
            ];
        });
    }

    public function default(): self
    {
        return $this->passwordString();
    }

    public function passwordString(): self
    {
        return $this->patchData([
            'id' => UuidFactory::uuid('resource-types.id.' . ResourceType::SLUG_PASSWORD_STRING),
            'slug' => ResourceType::SLUG_PASSWORD_STRING,
        ]);
    }

    public function passwordAndDescription(): self
    {
        return $this->patchData([
            'id' => UuidFactory::uuid('resource-types.id.' . ResourceType::SLUG_PASSWORD_AND_DESCRIPTION),
            'slug' => ResourceType::SLUG_PASSWORD_AND_DESCRIPTION,
        ]);
    }

    public function standaloneTotp(): self
    {
        return $this->patchData([
            'id' => UuidFactory::uuid('resource-types.id.' . ResourceType::SLUG_STANDALONE_TOTP),
            'slug' => ResourceType::SLUG_STANDALONE_TOTP,
        ]);
    }

    public function passwordDescriptionTotp(): self
    {
        return $this->patchData([
            'id' => UuidFactory::uuid('resource-types.id.' . ResourceType::SLUG_PASSWORD_DESCRIPTION_TOTP),
            'slug' => ResourceType::SLUG_PASSWORD_DESCRIPTION_TOTP,
        ]);
    }

    public function v5PasswordString(): self
    {
        return $this->patchData([
            'id' => UuidFactory::uuid('resource-types.id.' . ResourceType::SLUG_V5_PASSWORD_STRING),
            'slug' => ResourceType::SLUG_V5_PASSWORD_STRING,
        ]);
    }

    public function v5Default(): self
    {
        return $this->patchData([
            'id' => UuidFactory::uuid('resource-types.id.' . ResourceType::SLUG_V5_DEFAULT),
            'slug' => ResourceType::SLUG_V5_DEFAULT,
        ]);
    }

    public function v5StandaloneTotp(): self
    {
        return $this->patchData([
            'id' => UuidFactory::uuid('resource-types.id.' . ResourceType::SLUG_V5_TOTP_STANDALONE),
            'slug' => ResourceType::SLUG_V5_TOTP_STANDALONE,
        ]);
    }

    public function v5DefaultWithTotp(): self
    {
        return $this->patchData([
            'id' => UuidFactory::uuid('resource-types.id.' . ResourceType::SLUG_V5_DEFAULT_WITH_TOTP),
            'slug' => ResourceType::SLUG_V5_DEFAULT_WITH_TOTP,
        ]);
    }

    public function deleted(?\Cake\I18n\Date $deleted = null): self
    {
        if (is_null($deleted)) {
            $deleted = \Cake\I18n\Date::yesterday();
        }

        return $this->setField('deleted', $deleted);
    }
}
