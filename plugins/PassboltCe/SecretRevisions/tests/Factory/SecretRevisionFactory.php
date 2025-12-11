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
 * @since         5.7.0
 */
namespace Passbolt\SecretRevisions\Test\Factory;

use App\Utility\UuidFactory;
use Cake\Chronos\Chronos;
use Cake\I18n\DateTime;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;
use Passbolt\ResourceTypes\Model\Entity\ResourceType;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

/**
 * SecretRevisionFactory
 *
 * @method \Passbolt\SecretRevisions\Model\Entity\SecretRevision getEntity()
 * @method \Passbolt\SecretRevisions\Model\Entity\SecretRevision[] getEntities()
 * @method \Passbolt\SecretRevisions\Model\Entity\SecretRevision|\Passbolt\SecretRevisions\Model\Entity\SecretRevision[] persist()
 * @method static \Passbolt\SecretRevisions\Model\Entity\SecretRevision firstOrFail($conditions = null)
 */
class SecretRevisionFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Passbolt/SecretRevisions.SecretRevisions';
    }

    /**
     * @inheritDoc
     */
    protected function setDefaultTemplate(): void
    {
        $this->setDefaultData(function (Generator $faker) {
            return [
                'resource_id' => $faker->uuid(),
                'resource_type_id' => UuidFactory::uuid5('resource-types.id.' . ResourceType::SLUG_PASSWORD_AND_DESCRIPTION),
                'deleted' => null,
                'created_by' => $faker->uuid(),
                'modified_by' => $faker->uuid(),
                'created' => Chronos::now(),
                'modified' => Chronos::now(),
            ];
        });

        $this->with('ResourceTypes', ResourceTypeFactory::make()->passwordAndDescription());
    }

    /**
     * @param DateTime|null $deleted
     * @return self
     */
    public function deleted(?DateTime $deleted = null): self
    {
        return $this->patchData(['deleted' => $deleted ?? DateTime::yesterday()]);
    }

    /**
     * @param string $resourceId Identifier to set.
     * @return self
     */
    public function resourceId(string $resourceId): self
    {
        return $this->patchData(['resource_id' => $resourceId]);
    }

    /**
     * @param string $resourceTypeId Identifier to set.
     * @return self
     */
    public function resourceTypeId(string $resourceTypeId): self
    {
        return $this->patchData(['resource_type_id' => $resourceTypeId]);
    }
}
