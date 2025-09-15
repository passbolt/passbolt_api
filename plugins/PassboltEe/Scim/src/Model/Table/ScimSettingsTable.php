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
 * @since         5.5.0
 */

namespace Passbolt\Scim\Model\Table;

use App\Model\Table\OrganizationSettingsTable;
use App\Utility\UuidFactory;
use ArrayObject;
use Cake\Event\Event;
use Cake\ORM\Query;
use Passbolt\Scim\Model\Entity\ScimSetting;

/**
 * ScimEntries Model
 *
 * @method \Passbolt\Scim\Model\Entity\ScimSetting newEmptyEntity()
 * @method \Passbolt\Scim\Model\Entity\ScimSetting newEntity(array $data, array $options = [])
 * @method array<\Passbolt\Scim\Model\Entity\ScimSetting> newEntities(array $data, array $options = [])
 * @method \Passbolt\Scim\Model\Entity\ScimSetting get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \Passbolt\Scim\Model\Entity\ScimSetting findOrCreate(\Cake\ORM\Query\SelectQuery|callable|array $search, callable|array|null $callback = null, array $options = [])
 * @method \Passbolt\Scim\Model\Entity\ScimSetting patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\Passbolt\Scim\Model\Entity\ScimSetting> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Passbolt\Scim\Model\Entity\ScimSetting|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \Passbolt\Scim\Model\Entity\ScimSetting saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\Passbolt\Scim\Model\Entity\ScimSetting>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\Passbolt\Scim\Model\Entity\ScimSetting> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\Passbolt\Scim\Model\Entity\ScimSetting>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\Passbolt\Scim\Model\Entity\ScimSetting> deleteManyOrFail(iterable $entities, array $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ScimSettingsTable extends OrganizationSettingsTable
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setEntityClass(ScimSetting::class);
    }

    /**
     * Filter organization settings by property.
     *
     * @param \Cake\Event\Event $event Model.beforeFind event.
     * @param \Cake\ORM\Query $query Any query performed on the present table.
     * @return void
     */
    public function beforeFind(Event $event, Query $query): void
    {
        $query->where([
            $this->aliasField('property_id') => $this->getPropertyId(),
        ]);
    }

    /**
     * Fields property and property_id are fixed.
     *
     * @param \Cake\Event\Event $event the event
     * @param \ArrayObject $data data
     * @param \ArrayObject $options options
     * @return void
     * @throws \Exception
     */
    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options): void
    {
        $data['property'] = $this->getProperty();
        $data['property_id'] = $this->getPropertyId();
    }

    /**
     * Returns current property name.
     *
     * @return string
     */
    public function getProperty(): string
    {
        return ScimSetting::PROPERTY_NAME;
    }

    /**
     * Generates property ID from property name.
     *
     * @return string
     * @throws \Exception
     */
    public function getPropertyId(): string
    {
        return UuidFactory::uuid($this->getProperty());
    }

    /**
     * Returns unique property id.
     *
     * @param string $property property name
     * @return string (uuid) property id
     * @throws \Exception
     */
    protected function _getSettingPropertyId(string $property): string
    {
        return $this->getPropertyId();
    }
}
